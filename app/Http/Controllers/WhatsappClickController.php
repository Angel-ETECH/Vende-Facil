<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\WhatsappClick;
use Illuminate\Http\Request;

class WhatsappClickController extends Controller
{
    public function store(Store $store, Request $request)
    {
        if (!$store->active) {
            abort(404);
        }

        $message = 'Hola, vi tu tienda '
            . $store->name
            . ' en VendeFácil y quisiera hacer una consulta.';

        $this->recordClick(
            store: $store,
            product: null,
            type: 'store',
            message: $message,
            request: $request
        );

        return redirect()->away(
            $this->whatsappUrl($store->whatsapp, $message)
        );
    }

    public function product(Store $store, Product $product, Request $request)
    {
        if (
            !$store->active ||
            $product->store_id !== $store->id ||
            !$product->active ||
            $product->stock <= 0
        ) {
            abort(404);
        }

        $message = "Hola, vi este producto en VendeFácil.\n\n"
            . 'Producto: ' . $product->name . "\n"
            . 'Precio: S/ ' . number_format($product->price, 2)
            . "\n\n¿Está disponible?";

        $this->recordClick(
            store: $store,
            product: $product,
            type: 'product',
            message: $message,
            request: $request
        );

        return redirect()->away(
            $this->whatsappUrl($store->whatsapp, $message)
        );
    }

    private function recordClick(
        Store $store,
        ?Product $product,
        string $type,
        string $message,
        Request $request
    ): void {
        if ($request->user()?->id === $store->user_id) { return; }
        WhatsappClick::create([
            'store_id' => $store->id,
            'product_id' => $product?->id,
            'type' => $type,
            'message' => $message,
            'ip_address' => $request->ip(),
            'user_agent' => \Illuminate\Support\Str::substr((string) $request->userAgent(), 0, 1000),
        ]);
    }

    private function whatsappUrl(string $phone, string $message): string
    {
        return 'https://wa.me/'
            . preg_replace('/[^0-9]/', '', $phone)
            . '?text='
            . urlencode($message);
    }
}
