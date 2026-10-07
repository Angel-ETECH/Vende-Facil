<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'ip_address',
        'user_agent',
        'referer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
