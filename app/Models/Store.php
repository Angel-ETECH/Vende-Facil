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
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'delivery_available' => 'boolean',
        ];
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
}
