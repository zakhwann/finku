<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillSession extends Model
{
    protected $fillable = [
        'user_id', 'title', 'place', 'date',
        'tax_percent', 'discount_amount', 'split_mode', 'status'
    ];

    protected $casts = [
        'date' => 'date',
        'tax_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function members() { return $this->hasMany(BillMember::class); }
    public function items() { return $this->hasMany(BillItem::class); }
    public function debts() { return $this->hasMany(Debt::class); }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($i) => $i->price * $i->qty);
    }

    public function getTaxAmountAttribute()
    {
        return $this->subtotal * ($this->tax_percent / 100);
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->tax_amount - $this->discount_amount;
    }
}