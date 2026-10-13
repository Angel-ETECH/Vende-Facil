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
        'gallery_images',
        'description',
        'whatsapp',
        'address',
        'location_url',
        'city',
        'delivery_available',
        'opening_hours',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'website_url',
        'featured',
        'featured_until',
        'verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'gallery_images' => 'array',
            'delivery_available' => 'boolean',
            'unlimited_products' => 'boolean',
            'unlimited_products_activated_at' => 'datetime',
            'featured' => 'boolean',
            'featured_until' => 'datetime',
            'verified' => 'boolean',
            'verified_at' => 'datetime',
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
        if ($this->location_url && in_array(strtolower((string) parse_url($this->location_url, PHP_URL_SCHEME)), ['http', 'https'], true)) {
            return $this->location_url;
        }

        if (! $this->address && ! $this->city) {
            return null;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->locationLabel());
    }

    public function mapEmbedUrl(): ?string
    {
        $query = null;
        $host = strtolower((string) parse_url((string) $this->location_url, PHP_URL_HOST));
        if (in_array($host, ['google.com', 'www.google.com', 'maps.google.com', 'google.com.pe', 'www.google.com.pe'], true)) {
            parse_str((string) parse_url($this->location_url, PHP_URL_QUERY), $parameters);
            $candidate = $parameters['q'] ?? $parameters['query'] ?? null;
            if (is_string($candidate) && $candidate !== '') {
                $query = $candidate;
            } elseif (preg_match('/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', rawurldecode($this->location_url), $coordinates)) {
                $query = $coordinates[1].','.$coordinates[2];
            } elseif (preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', rawurldecode($this->location_url), $coordinates)) {
                $query = $coordinates[1].','.$coordinates[2];
            }
        }
        if ($query !== null) {
            return 'https://www.google.com/maps?q='.urlencode($query).'&output=embed';
        }
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

    public function isVerified(): bool
    {
        return (bool) $this->verified;
    }

    public function socialLinks(): array
    {
        return array_filter([
            'Facebook' => $this->facebook_url,
            'Instagram' => $this->instagram_url,
            'TikTok' => $this->tiktok_url,
            'Web' => $this->website_url,
        ], fn ($url) => is_string($url) && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true));
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
