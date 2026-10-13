<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingReport extends Model
{
    protected $fillable = ['store_id', 'product_id', 'reason', 'details'];

    protected function casts(): array { return ['reviewed_at' => 'datetime']; }
    public function user() { return $this->belongsTo(User::class); }
    public function store() { return $this->belongsTo(Store::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
