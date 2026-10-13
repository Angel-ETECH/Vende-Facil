<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ListingReport;
use App\Models\User;
use App\Notifications\MarketplaceNotice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplacePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_pages_render_with_authenticated_custom_controls(): void
    {
        $user = User::factory()->create(['name' => 'Vendedor de prueba', 'is_admin' => true]);
        $store = $user->stores()->create(['name' => 'Mi tienda de prueba', 'slug' => 'tienda-qa', 'whatsapp' => '51999999999', 'city' => 'Lima', 'address' => 'Av. Principal 123']);
        $category = Category::create(['name' => 'Hogar', 'slug' => 'hogar']);
        $product = $store->products()->create(['name' => 'Producto de prueba', 'slug' => 'producto-qa', 'price' => 25, 'stock' => 4, 'category_id' => $category->id]);
        $user->favorites()->create(['product_id' => $product->id]);
        $user->favorites()->create(['store_id' => $store->id]);
        $user->notify(new MarketplaceNotice('Pago aprobado', 'Tu plan ilimitado esta activo.', '/mi-plan'));
        ListingReport::forceCreate(['user_id' => $user->id, 'store_id' => $store->id, 'product_id' => $product->id, 'reason' => 'misleading', 'details' => 'La descripcion requiere una revision.']);
        $this->actingAs($user);
        foreach ([
            'home' => '/',
            'store' => '/tienda/tienda-qa',
            'category' => '/categoria/hogar',
            'city' => '/ciudad/lima',
            'dashboard' => '/dashboard',
            'profile' => '/profile',
            'products' => '/productos',
            'product-create' => '/productos/crear',
            'product-edit' => '/productos/'.$product->id.'/editar',
            'store-edit' => '/mi-tienda/editar',
            'plan' => '/mi-plan',
            'favorites' => '/favoritos',
            'notifications' => '/notificaciones',
            'analytics' => '/estadisticas',
            'report' => '/denunciar/tienda-qa?producto='.$product->id,
            'admin-dashboard' => '/admin',
            'admin-stores' => '/admin/tiendas',
            'admin-store-detail' => '/admin/tiendas/'.$store->id,
            'admin-payments' => '/admin/pagos',
            'admin-reports' => '/admin/denuncias',
        ] as $name => $path) {
            if ($name === 'admin-payments') {
                $store->paymentProofs()->create(['method' => 'yape', 'amount' => 15, 'payment_number' => '989652889', 'proof_path' => 'payment-proofs/qa.pdf', 'status' => 'pending']);
            }
            $response = $this->get($path)->assertOk();
            $response->assertSee('Favoritos')->assertSee('Notificaciones');
            if (getenv('VENDEFACIL_QA_EXPORT') === '1') {
                $directory = storage_path('framework/testing');
                if (! is_dir($directory)) { mkdir($directory, 0777, true); }
                file_put_contents($directory.'/qa-'.$name.'.html', $response->getContent());
            }
        }
        foreach (['plan-pending' => false, 'plan-unlimited' => true] as $name => $unlimited) {
            $store->forceFill(['unlimited_products' => $unlimited])->save();
            $response = $this->get('/mi-plan')->assertOk();
            if (getenv('VENDEFACIL_QA_EXPORT') === '1') {
                file_put_contents(storage_path('framework/testing/qa-'.$name.'.html'), $response->getContent());
            }
        }
        $this->get('/favoritos')->assertSee('aria-pressed="true"', false);
    }
}
