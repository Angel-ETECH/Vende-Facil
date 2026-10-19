<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveryDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_listing_has_vertical_carousel_cards_and_real_store_cover(): void
    {
        $store = User::factory()->create()->stores()->create([
            'name' => 'Tienda local', 'slug' => 'tienda-local', 'whatsapp' => '51999999999',
            'gallery_images' => ['stores/gallery/cover.webp'],
        ]);
        $category = Category::create(['name' => 'Comida', 'slug' => 'comida']);
        $store->products()->create([
            'name' => 'Producto local', 'slug' => 'producto-local', 'price' => 15,
            'stock' => 2, 'category_id' => $category->id,
        ]);

        $this->get('/')->assertOk()
            ->assertSee('x-data="productCarousel"', false)
            ->assertSee('product-carousel-track')
            ->assertDontSee('vf-product-grid--single')
            ->assertDontSee('vf-store-grid--single')
            ->assertSee('stores/gallery/cover.webp')
            ->assertSee('vf-catalog-card__media')
            ->assertSee('vf-store-card__body')
            ->assertDontSee('Abierto')
            ->assertSee(route('stores.show', $store), false);
    }

    public function test_multiple_listings_and_empty_search_render_without_single_layout(): void
    {
        $store = User::factory()->create()->stores()->create([
            'name' => 'Tienda local', 'slug' => 'tienda-local', 'whatsapp' => '51999999999',
        ]);
        foreach ([1, 2, 3, 4] as $number) {
            $store->products()->create([
                'name' => 'Producto '.$number, 'slug' => 'producto-'.$number,
                'price' => 15, 'stock' => 2,
            ]);
        }
        $this->get('/')->assertOk()->assertDontSee('vf-product-grid--single')
            ->assertSee('Producto 1')->assertSee('Producto 4');
        $this->get('/?buscar=inexistente')->assertOk()
            ->assertSee('No encontramos productos')->assertDontSee('vf-catalog-card__media');
    }

    public function test_store_logo_is_not_used_as_a_stretched_cover(): void
    {
        User::factory()->create()->stores()->create([
            'name' => 'Tienda local', 'slug' => 'tienda-local', 'whatsapp' => '51999999999',
            'logo' => 'stores/logos/logo.webp',
        ]);
        $response = $this->get('/')->assertOk()->assertSee('vf-store-logo')
            ->assertSee('bg-white object-contain', false);
        $response->assertDontSee('class="vf-store-card__cover"', false);
    }
}
