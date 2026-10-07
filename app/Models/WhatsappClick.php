<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_id',
        'type',
        'message',
        'ip_address',
        'user_agent',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
