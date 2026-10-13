<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ListingReport;
use App\Models\PaymentProof;
use App\Models\Store;
use App\Models\User;
use App\Notifications\MarketplaceNotice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaunchFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function storeFor(User $user): Store
    {
        return $user->stores()->create(['name' => 'Tienda '.$user->id, 'slug' => 'tienda-'.$user->id, 'whatsapp' => '51999999999', 'city' => 'Lima']);
    }

    private function productData(): array
    {
        $category = Category::firstOrCreate(['slug' => 'hogar'], ['name' => 'Hogar']);
        return ['category_id' => $category->id, 'name' => 'Producto', 'price' => 20, 'stock' => 1];
    }

    private function proofFor(Store $store): PaymentProof
    {
        Storage::disk('local')->put('payment-proofs/test.pdf', 'private proof');
        return $store->paymentProofs()->create(['method' => 'yape', 'amount' => 15, 'payment_number' => '989652889', 'proof_path' => 'payment-proofs/test.pdf', 'status' => 'pending']);
    }

    public function test_email_verification_does_not_block_authenticated_users(): void
    {
        $user = User::factory()->unverified()->create(['is_admin' => true]);
        $this->storeFor($user);
        foreach (['/dashboard', '/productos', '/admin', '/estadisticas'] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
        $this->assertNull($user->fresh()->email_verified_at);
        $this->actingAs(User::factory()->unverified()->create(['is_admin' => false]))->get('/admin')->assertForbidden();
    }

    public function test_free_plan_blocks_eleventh_product_then_approval_unlocks_it(): void
    {
        Storage::fake('local');
        $seller = User::factory()->create(); $store = $this->storeFor($seller); $data = $this->productData();
        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($seller)->post('/productos', [...$data, 'name' => 'Producto '.$i])->assertSessionHasNoErrors();
        }
        $this->post('/productos', $data)->assertSessionHas('error');
        $this->assertSame(10, $store->products()->count());
        $proof = $this->proofFor($store);
        $this->actingAs(User::factory()->create(['is_admin' => true]))->patch('/admin/pagos/'.$proof->id.'/aprobar')->assertSessionHasNoErrors();
        $this->assertTrue($store->fresh()->unlimited_products);
        $this->actingAs($seller)->post('/productos', $data)->assertSessionHasNoErrors();
        $this->assertSame(11, $store->products()->count());
        $this->assertSame(1, $seller->notifications()->count());
    }

    public function test_payment_proof_is_private_and_only_owner_or_admin_can_download(): void
    {
        Storage::fake('local'); Storage::fake('public');
        $seller = User::factory()->create(); $store = $this->storeFor($seller);
        $this->actingAs($seller)->post('/pagos', ['method' => 'yape', 'reference' => '123456', 'proof' => UploadedFile::fake()->image('proof.png')])->assertSessionHasNoErrors();
        $proof = $store->paymentProofs()->firstOrFail();
        Storage::disk('local')->assertExists($proof->proof_path); Storage::disk('public')->assertMissing($proof->proof_path);
        $this->get('/storage/'.$proof->proof_path)->assertNotFound();
        $this->get('/comprobantes/'.$proof->id)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $this->actingAs(User::factory()->create())->get('/comprobantes/'.$proof->id)->assertForbidden();
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/comprobantes/'.$proof->id)->assertOk();
    }

    public function test_duplicate_proof_and_reference_are_rejected(): void
    {
        Storage::fake('local');
        $one = User::factory()->create(); $two = User::factory()->create();
        $this->storeFor($one); $this->storeFor($two);
        $this->actingAs($one)->post('/pagos', ['method' => 'bcp', 'reference' => 'ABC 123', 'proof' => UploadedFile::fake()->image('proof.png')])->assertSessionHasNoErrors();
        $this->actingAs($two)->post('/pagos', ['method' => 'bcp', 'reference' => 'abc123', 'proof' => UploadedFile::fake()->image('other.png', 40, 40)])->assertSessionHasErrors('proof');
        $this->post('/pagos', ['method' => 'yape', 'proof' => UploadedFile::fake()->image('proof.png')])->assertSessionHasErrors('proof');
        $this->assertSame(1, PaymentProof::count());
    }

    public function test_reviewed_payment_cannot_be_approved_again(): void
    {
        Storage::fake('local'); $store = $this->storeFor(User::factory()->create()); $proof = $this->proofFor($store);
        $this->actingAs(User::factory()->create(['is_admin' => true]))->patch('/admin/pagos/'.$proof->id.'/aprobar');
        $this->patch('/admin/pagos/'.$proof->id.'/aprobar')->assertSessionHasErrors('payment');
        $this->assertSame(1, $store->user->notifications()->count());
    }

    public function test_approval_rolls_back_if_store_activation_fails(): void
    {
        Storage::fake('local'); $store = $this->storeFor(User::factory()->create()); $proof = $this->proofFor($store);
        Store::updating(function () { throw new \RuntimeException('simulated failure'); });
        try {
            $this->actingAs(User::factory()->create(['is_admin' => true]))->patch('/admin/pagos/'.$proof->id.'/aprobar')->assertStatus(500);
            $this->assertSame('pending', $proof->fresh()->status); $this->assertFalse($store->fresh()->unlimited_products);
        } finally { Store::flushEventListeners(); }
    }

    public function test_rejection_requires_reason_and_notifies_owner(): void
    {
        Storage::fake('local'); $store = $this->storeFor(User::factory()->create()); $proof = $this->proofFor($store);
        $this->actingAs(User::factory()->create(['is_admin' => true]))->patch('/admin/pagos/'.$proof->id.'/rechazar')->assertSessionHasErrors('admin_notes');
        $this->patch('/admin/pagos/'.$proof->id.'/rechazar', ['admin_notes' => 'No coincide el importe.'])->assertSessionHasNoErrors();
        $this->assertSame('rejected', $proof->fresh()->status); $this->assertFalse($store->fresh()->unlimited_products);
        $this->assertSame(1, $store->user->notifications()->count());
    }

    public function test_favorites_are_idempotent_and_private(): void
    {
        $store = $this->storeFor(User::factory()->create()); $buyer = User::factory()->create();
        $this->actingAs($buyer)->post('/favoritos/store/'.$store->id); $this->post('/favoritos/store/'.$store->id);
        $this->assertSame(1, $buyer->favorites()->count());
        $this->get('/favoritos')->assertOk()->assertSee($store->name);
        $this->actingAs(User::factory()->create())->delete('/favoritos/store/'.$store->id);
        $this->assertSame(1, $buyer->favorites()->count());
        $this->actingAs($buyer)->delete('/favoritos/store/'.$store->id); $this->assertSame(0, $buyer->favorites()->count());
    }

    public function test_report_rejects_foreign_product_and_admin_can_hide_listing(): void
    {
        $store = $this->storeFor(User::factory()->create()); $otherStore = $this->storeFor(User::factory()->create());
        $product = $store->products()->create([...$this->productData(), 'slug' => 'one']);
        $other = $otherStore->products()->create([...$this->productData(), 'slug' => 'two']); $buyer = User::factory()->create();
        $this->actingAs($buyer)->post('/denunciar/'.$store->slug, ['reason' => 'fraud', 'details' => 'Descripcion del problema.', 'product_id' => $other->id])->assertSessionHasErrors('product_id');
        $this->post('/denunciar/'.$store->slug, ['reason' => 'fraud', 'details' => 'Descripcion del problema.', 'product_id' => $product->id])->assertSessionHasNoErrors();
        $report = ListingReport::firstOrFail();
        $this->patch('/admin/denuncias/'.$report->id, ['status' => 'resolved', 'admin_notes' => 'Revisado.'])->assertForbidden();
        $this->actingAs(User::factory()->create(['is_admin' => true]))->patch('/admin/denuncias/'.$report->id, ['status' => 'resolved', 'admin_notes' => 'Publicacion retirada.', 'hide_listing' => 1])->assertSessionHasNoErrors();
        $this->assertFalse($product->fresh()->active); $this->assertSame('resolved', $report->fresh()->status);
    }

    public function test_notifications_cannot_be_marked_by_another_user(): void
    {
        $user = User::factory()->create(); $user->notify(new MarketplaceNotice('Pago aprobado', 'Plan activado.', '/mi-plan'));
        $notice = $user->notifications()->first();
        $this->actingAs(User::factory()->create())->patch('/notificaciones/'.$notice->id)->assertNotFound();
        $this->actingAs($user)->get('/notificaciones')->assertOk()->assertSee('Pago aprobado');
        $this->patch('/notificaciones/'.$notice->id); $this->assertNotNull($notice->fresh()->read_at);
    }

    public function test_seller_statistics_show_only_own_products(): void
    {
        $user = User::factory()->create(); $store = $this->storeFor($user);
        $store->products()->create([...$this->productData(), 'name' => 'Mi producto', 'slug' => 'mine']);
        $other = $this->storeFor(User::factory()->create()); $other->products()->create([...$this->productData(), 'name' => 'Producto privado ajeno', 'slug' => 'other']);
        $this->actingAs($user)->get('/estadisticas')->assertOk()->assertSee('Mi producto')->assertDontSee('Producto privado ajeno');
    }

    public function test_images_are_resized_and_converted_to_webp(): void
    {
        Storage::fake('public'); $path = \App\Support\OptimizedImage::store(UploadedFile::fake()->image('photo.jpg', 2400, 1200), 'products');
        $size = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame('image/webp', $size['mime']); $this->assertSame(1600, $size[0]); $this->assertSame(800, $size[1]);
    }

    public function test_legacy_proofs_are_moved_privately_and_command_is_repeatable(): void
    {
        Storage::fake('public'); Storage::fake('local'); $proof = $this->proofFor($this->storeFor(User::factory()->create()));
        Storage::disk('public')->put($proof->proof_path, 'private proof');
        $this->artisan('vendefacil:privatize-proofs')->assertSuccessful();
        Storage::disk('public')->assertMissing($proof->proof_path); Storage::disk('local')->assertExists($proof->proof_path);
        $this->assertNotNull($proof->fresh()->proof_hash); $this->artisan('vendefacil:privatize-proofs')->assertSuccessful();
    }

    public function test_duplicate_store_creation_does_not_bypass_free_plan(): void
    {
        $user = User::factory()->create();
        $data = ['name' => 'Mi tienda', 'whatsapp' => '999999999', 'city' => 'Lima', 'address' => 'Av. Principal 123'];
        $this->actingAs($user)->post('/mi-tienda', $data)->assertSessionHasNoErrors();
        $this->post('/mi-tienda', $data)->assertRedirect(route('stores.edit'));
        $this->assertSame(1, $user->stores()->count());
    }

    public function test_failed_product_update_preserves_existing_images(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $store = $this->storeFor($user);
        $product = $store->products()->create([...$this->productData(), 'slug' => 'old', 'image' => 'products/old.jpg', 'gallery_images' => ['products/gallery/old.jpg']]);
        Storage::disk('public')->put('products/old.jpg', 'old main');
        Storage::disk('public')->put('products/gallery/old.jpg', 'old gallery');
        \App\Models\Product::updating(function () { throw new \RuntimeException('simulated failure'); });
        try {
            $this->actingAs($user)->put('/productos/'.$product->id, [...$this->productData(), 'image' => UploadedFile::fake()->image('new.jpg'), 'remove_gallery_images' => ['products/gallery/old.jpg']])->assertStatus(500);
            Storage::disk('public')->assertExists('products/old.jpg');
            Storage::disk('public')->assertExists('products/gallery/old.jpg');
            $this->assertCount(2, Storage::disk('public')->allFiles());
            $this->assertSame('products/old.jpg', $product->fresh()->image);
        } finally { \App\Models\Product::flushEventListeners(); }
    }
}
