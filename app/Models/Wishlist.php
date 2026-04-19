<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'target_price',
        'saved_amount', 'image_url', 'product_url',
        'priority', 'status', 'target_date'
    ];

    protected $casts = [
        'target_date'  => 'date',
        'target_price' => 'decimal:2',
        'saved_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute(): int
    {
        if ($this->target_price <= 0) return 0;
        return min(round(($this->saved_amount / $this->target_price) * 100), 100);
    }

    public function getRemainingAttribute(): float
    {
        return max($this->target_price - $this->saved_amount, 0);
    }

    public function getIsReadyAttribute(): bool
    {
        return $this->saved_amount >= $this->target_price;
    }
}