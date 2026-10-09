<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'whatsapp',
        'address',
        'city',
        'delivery_available',
        'featured',
        'featured_until',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'delivery_available' => 'boolean',
            'unlimited_products' => 'boolean',
            'unlimited_products_activated_at' => 'datetime',
            'featured' => 'boolean',
            'featured_until' => 'datetime',
        ];
    }

    public function hasUnlimitedProducts(): bool
    {
        return $this->unlimited_products;
    }

    public function canCreateMoreProducts(?int $productsCount = null): bool
    {
        if ($this->hasUnlimitedProducts()) {
            return true;
        }

        $productsCount ??= $this->products()->count();

        return $productsCount < config('vendefacil.free_product_limit');
    }

    public function remainingFreeProducts(?int $productsCount = null): int
    {
        if ($this->hasUnlimitedProducts()) {
            return PHP_INT_MAX;
        }

        $productsCount ??= $this->products()->count();

        return max(config('vendefacil.free_product_limit') - $productsCount, 0);
    }

    public function locationLabel(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city ? $this->city . ', Perú' : null,
        ]);

        return $parts
            ? implode(', ', $parts)
            : 'Perú';
    }

    public function mapUrl(): ?string
    {
        if (! $this->address && ! $this->city) {
            return null;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->locationLabel());
    }

    public function mapEmbedUrl(): ?string
    {
        if (! $this->address && ! $this->city) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . urlencode($this->locationLabel()) . '&output=embed';
    }

    public function isFeatured(): bool
    {
        return $this->featured
            && (! $this->featured_until || $this->featured_until->isFuture());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function whatsappClicks()
    {
        return $this->hasMany(WhatsappClick::class);
    }

    public function visits()
    {
        return $this->hasMany(StoreVisit::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }
}
