<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_cannot_assign_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Nuevo vendedor',
            'email' => 'vendedor@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => true,
        ]);

        $this->assertFalse(
            User::where('email', 'vendedor@example.com')->firstOrFail()->is_admin
        );
    }

    public function test_non_admin_cannot_access_admin_stores(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/tiendas')
            ->assertForbidden();
    }

    public function test_password_reset_request_does_not_reveal_unknown_emails(): void
    {
        $this->post('/forgot-password', [
            'email' => 'no-existe@example.com',
        ])->assertSessionHas('status');
    }

    public function test_security_headers_are_present(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Permitted-Cross-Domain-Policies', 'none')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin')
            ->assertHeader('Cross-Origin-Resource-Policy', 'same-origin')
            ->assertHeader(
                'Content-Security-Policy',
                "base-uri 'self'; object-src 'none'; frame-ancestors 'self'; form-action 'self'"
            );
    }

    public function test_seller_cannot_update_another_store_product(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();

        $category = Category::create([
            'name' => 'Hogar',
            'slug' => 'hogar',
        ]);

        $otherStore = $otherSeller->stores()->create([
            'name' => 'Tienda ajena',
            'slug' => 'tienda-ajena',
            'whatsapp' => '51999999999',
            'city' => 'Lima',
        ]);

        $product = $otherStore->products()->create([
            'category_id' => $category->id,
            'name' => 'Producto ajeno',
            'slug' => 'producto-ajeno',
            'price' => 20,
            'stock' => 5,
        ]);

        $this->actingAs($seller)
            ->put('/productos/'.$product->id, [
                'category_id' => $category->id,
                'name' => 'Producto editado',
                'description' => 'Intento de cambio',
                'price' => 10,
                'stock' => 1,
                'active' => true,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas(Product::class, [
            'id' => $product->id,
            'name' => 'Producto ajeno',
        ]);
    }
}
