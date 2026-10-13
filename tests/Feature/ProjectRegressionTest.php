<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Notifications\MarketplaceNotice;
use App\Support\SafePublicStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function storeFor(User $user): Store
    {
        return $user->stores()->create(['name' => 'Tienda '.$user->id, 'slug' => 'tienda-'.$user->id, 'whatsapp' => '51999999999', 'city' => 'Pucallpa', 'address' => 'Av. Principal 123']);
    }

    private function productFor(Store $store): Product
    {
        $category = Category::firstOrCreate(['slug' => 'hogar'], ['name' => 'Hogar']);
        return $store->products()->create(['category_id' => $category->id, 'name' => 'Producto de prueba', 'slug' => 'producto', 'price' => 20, 'stock' => 2]);
    }

    private function filesFor(Store $store): void
    {
        $store->update(['logo' => 'stores/logos/old.jpg', 'gallery_images' => ['stores/gallery/old.jpg']]);
        $product = $this->productFor($store);
        $product->update(['image' => 'products/old.jpg', 'gallery_images' => ['products/gallery/old.jpg']]);
        foreach (['stores/logos/old.jpg', 'stores/gallery/old.jpg', 'products/old.jpg', 'products/gallery/old.jpg'] as $path) {
            Storage::disk('public')->put($path, 'old file');
        }
        Storage::disk('local')->put('payment-proofs/private.pdf', 'private receipt');
        $store->paymentProofs()->create(['method' => 'yape', 'amount' => 15, 'payment_number' => '989652889', 'proof_path' => 'payment-proofs/private.pdf', 'status' => 'pending']);
    }

    public function test_malformed_public_filters_return_validation_errors_instead_of_server_errors(): void
    {
        foreach (['buscar', 'categoria', 'ciudad', 'delivery', 'productos'] as $field) {
            $this->getJson('/?'.http_build_query([$field => ['bad']]))->assertUnprocessable()->assertJsonValidationErrors($field);
        }
        $this->getJson('/?buscar='.str_repeat('a', 151))->assertUnprocessable();
    }

    public function test_admin_filter_arrays_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        foreach (['buscar', 'ciudad', 'estado', 'delivery', 'page'] as $field) {
            $this->getJson('/admin/tiendas?'.http_build_query([$field => ['bad']]))->assertUnprocessable()->assertJsonValidationErrors($field);
        }
    }

    public function test_array_whatsapp_input_is_validated_before_normalization(): void
    {
        $this->actingAs(User::factory()->create())->postJson('/mi-tienda', [
            'name' => 'Tienda', 'city' => 'Lima', 'address' => 'Av. 123', 'whatsapp' => ['999999999'],
        ])->assertUnprocessable()->assertJsonValidationErrors('whatsapp');
        $this->assertSame(0, Store::count());
    }

    public function test_admin_search_accepts_zero_as_a_real_search_term(): void
    {
        $first = $this->storeFor(User::factory()->create(['name' => 'Vendedor Uno', 'email' => 'one@example.com']));
        $first->update(['name' => 'Moto 0']);
        $second = $this->storeFor(User::factory()->create(['name' => 'Vendedor Dos', 'email' => 'two@example.com']));
        $second->update(['name' => 'Otro negocio']);
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin/tiendas?buscar=0')
            ->assertOk()->assertSee('Moto 0')->assertDontSee('Otro negocio');
    }

    public function test_favorite_identifiers_and_report_parameters_do_not_cause_server_errors(): void
    {
        $user = User::factory()->create();
        $store = $this->storeFor($user);
        $this->actingAs($user)->post('/favoritos/store/not-a-number')->assertNotFound();
        $this->delete('/favoritos/product/not-a-number')->assertNotFound();
        $this->getJson('/denunciar/'.$store->slug.'?producto[]=1')->assertUnprocessable()->assertJsonValidationErrors('producto');
    }

    public function test_long_referrer_and_user_agent_do_not_break_public_store(): void
    {
        $store = $this->storeFor(User::factory()->create());
        $this->get('/tienda/'.$store->slug, ['Referer' => 'https://example.com/?q='.str_repeat('a', 800), 'User-Agent' => str_repeat('b', 1500)])->assertOk();
        $visit = $store->visits()->firstOrFail();
        $this->assertSame(255, mb_strlen($visit->referer));
        $this->assertSame(1000, mb_strlen($visit->user_agent));
    }

    public function test_sitemap_contains_real_public_pages_and_excludes_unavailable_categories(): void
    {
        $store = $this->storeFor(User::factory()->create());
        $product = $this->productFor($store);
        $emptyCategory = Category::create(['name' => 'Sin stock', 'slug' => 'sin-stock']);
        $store->products()->create(['category_id' => $emptyCategory->id, 'name' => 'Agotado', 'slug' => 'agotado', 'price' => 1, 'stock' => 0]);
        $hiddenStore = $this->storeFor(User::factory()->create());
        $hiddenStore->forceFill(['active' => false, 'city' => 'Ciudad Oculta'])->save();
        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $locations = array_map('strval', iterator_to_array($xml->xpath('//*[local-name()="loc"]')));
        $this->assertContains(route('home'), $locations);
        $this->assertContains(route('categories.show', $product->category), $locations);
        $this->assertContains(route('cities.show', 'pucallpa'), $locations);
        $this->assertContains(route('stores.show', $store), $locations);
        $this->assertNotContains(route('categories.show', $emptyCategory), $locations);
        $this->assertNotContains(route('stores.show', $hiddenStore), $locations);
        $this->assertNotContains(route('cities.show', 'ciudad-oculta'), $locations);
    }

    public function test_partial_gallery_upload_failure_removes_new_files(): void
    {
        Storage::fake('public');
        $helper = new class {
            use SafePublicStorage;
            public function upload(Request $request): array { return $this->storeUploadedGalleryImages($request, 'gallery_images', 'products/gallery'); }
        };
        $request = Request::create('/', 'POST', [], [], ['gallery_images' => [
            UploadedFile::fake()->image('valid.jpg'), UploadedFile::fake()->createWithContent('broken.png', 'not an image'),
        ]]);
        try {
            $helper->upload($request);
            $this->fail('Expected the broken image to fail.');
        } catch (\RuntimeException) {
            $this->assertSame([], Storage::disk('public')->allFiles('products/gallery'));
        }
    }

    public function test_failed_store_update_preserves_old_files_and_cleans_new_ones(): void
    {
        Storage::fake('public'); Storage::fake('local');
        $user = User::factory()->create(); $store = $this->storeFor($user); $this->filesFor($store);
        Store::updating(function () { throw new \RuntimeException('simulated failure'); });
        try {
            $this->actingAs($user)->put('/mi-tienda', ['name' => 'Nuevo nombre', 'whatsapp' => '999999999', 'city' => 'Lima', 'address' => 'Av. 123',
                'logo' => UploadedFile::fake()->image('new.jpg'), 'gallery_images' => [UploadedFile::fake()->image('gallery.jpg')],
                'remove_gallery_images' => ['stores/gallery/old.jpg'],
            ])->assertStatus(500);
            $this->assertCount(4, Storage::disk('public')->allFiles());
            Storage::disk('public')->assertExists('stores/logos/old.jpg');
            Storage::disk('public')->assertExists('stores/gallery/old.jpg');
            $this->assertSame('stores/logos/old.jpg', $store->fresh()->logo);
        } finally { Store::flushEventListeners(); }
    }

    public function test_failed_store_deletion_preserves_uploaded_files(): void
    {
        Storage::fake('public'); Storage::fake('local');
        $store = $this->storeFor(User::factory()->create()); $this->filesFor($store);
        Store::deleting(function () { throw new \RuntimeException('simulated failure'); });
        try {
            $this->actingAs(User::factory()->create(['is_admin' => true]))->delete('/admin/tiendas/'.$store->id)->assertStatus(500);
            $this->assertDatabaseHas('stores', ['id' => $store->id]);
            $this->assertCount(4, Storage::disk('public')->allFiles());
            Storage::disk('local')->assertExists('payment-proofs/private.pdf');
        } finally { Store::flushEventListeners(); }
    }

    public function test_failed_product_deletion_preserves_uploaded_files(): void
    {
        Storage::fake('public'); Storage::fake('local');
        $user = User::factory()->create(); $store = $this->storeFor($user); $this->filesFor($store);
        $product = $store->products()->firstOrFail();
        Product::deleting(function () { throw new \RuntimeException('simulated failure'); });
        try {
            $this->actingAs($user)->delete('/productos/'.$product->id)->assertStatus(500);
            Storage::disk('public')->assertExists('products/old.jpg');
            Storage::disk('public')->assertExists('products/gallery/old.jpg');
            $this->assertDatabaseHas('products', ['id' => $product->id]);
        } finally { Product::flushEventListeners(); }
    }

    public function test_account_deletion_removes_owned_uploads_receipts_and_notifications(): void
    {
        Storage::fake('public'); Storage::fake('local');
        $user = User::factory()->create(); $store = $this->storeFor($user); $this->filesFor($store);
        $user->notify(new MarketplaceNotice('Aviso privado', 'Mensaje privado.', '/mi-plan'));
        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertRedirect('/');
        $this->assertGuest(); $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertSame([], Storage::disk('public')->allFiles());
        Storage::disk('local')->assertMissing('payment-proofs/private.pdf');
        $this->assertSame(0, DB::table('notifications')->where('notifiable_id', $user->id)->count());
    }

    public function test_seller_store_and_product_crud_preserves_location_gallery_and_public_visibility(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user)->post('/mi-tienda', ['name' => 'Negocio real', 'city' => 'Pucallpa', 'whatsapp' => '999999999', 'address' => 'Av. Principal 123',
            'location_url' => 'https://www.google.com/maps?q=-8.38,-74.55', 'logo' => UploadedFile::fake()->image('logo.png'),
        ])->assertSessionHasNoErrors();
        $store = $user->stores()->firstOrFail();
        $this->assertSame('https://www.google.com/maps?q=-8.38,-74.55', $store->mapUrl());
        $this->assertSame('https://www.google.com/maps?q=-8.38%2C-74.55&output=embed', $store->mapEmbedUrl());
        $category = Category::create(['name' => 'Comida', 'slug' => 'comida']);
        $data = ['category_id' => $category->id, 'name' => 'Comida de prueba', 'price' => 25, 'stock' => 3];
        $this->post('/productos', [...$data, 'image' => UploadedFile::fake()->image('product.jpg'), 'gallery_images' => [UploadedFile::fake()->image('detail.jpg')]])->assertSessionHasNoErrors();
        $product = $store->products()->firstOrFail(); $oldGallery = $product->gallery_images[0];
        $this->put('/productos/'.$product->id, [...$data, 'name' => 'Comida actualizada', 'active' => 1,
            'remove_gallery_images' => [$oldGallery], 'gallery_images' => [UploadedFile::fake()->image('new-detail.jpg')],
        ])->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($oldGallery);
        $this->assertCount(1, $product->fresh()->gallery_images);
        $this->patch('/productos/'.$product->id.'/estado')->assertSessionHasNoErrors();
        $this->get('/tienda/'.$store->slug)->assertDontSee('Comida actualizada');
        $this->patch('/productos/'.$product->id.'/estado')->assertSessionHasNoErrors();
        $this->actingAs(User::factory()->create())->get('/tienda/'.$store->slug)->assertOk()->assertSee('Comida actualizada');
        $response = $this->get('/tienda/'.$store->slug.'/producto/'.$product->id.'/whatsapp')->assertRedirect();
        $this->assertStringStartsWith('https://wa.me/51999999999?', $response->headers->get('Location'));
        $this->assertSame(1, $product->whatsappClicks()->count());
        $this->actingAs($user)->delete('/productos/'.$product->id)->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    public function test_state_changing_requests_require_csrf_tokens(): void
    {
        $this->app->instance('env', 'local');
        $this->actingAs(User::factory()->create())->postJson('/mi-tienda', [
            'name' => 'Tienda', 'whatsapp' => '999999999', 'city' => 'Lima', 'address' => 'Av. 123',
        ])->assertStatus(419);
        $this->assertSame(0, Store::count());
    }

    public function test_legacy_unsafe_links_are_not_rendered_as_store_links(): void
    {
        $store = $this->storeFor(User::factory()->create());
        $store->forceFill(['location_url' => 'javascript:alert(1)', 'website_url' => 'javascript:alert(1)', 'facebook_url' => 'https://facebook.com/example'])->save();
        $this->assertStringStartsWith('https://www.google.com/maps/', $store->mapUrl());
        $this->assertSame(['Facebook' => 'https://facebook.com/example'], $store->socialLinks());
    }

    public function test_seller_cannot_assign_verified_featured_or_paid_plan_from_store_forms(): void
    {
        $user = User::factory()->create();
        $data = ['name' => 'Tienda', 'whatsapp' => '999999999', 'city' => 'Lima', 'address' => 'Av. 123',
            'verified' => true, 'featured' => true, 'unlimited_products' => true,
        ];
        $this->actingAs($user)->post('/mi-tienda', $data)->assertSessionHasNoErrors();
        $store = $user->stores()->firstOrFail();
        $this->put('/mi-tienda', $data)->assertSessionHasNoErrors();
        $store->refresh();
        $this->assertFalse($store->verified);
        $this->assertFalse($store->featured);
        $this->assertFalse($store->unlimited_products);
    }
}
