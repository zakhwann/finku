<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillMember extends Model
{
    protected $fillable = ['bill_session_id', 'name', 'total_items', 'share_amount', 'is_payer'];

    public function session() { return $this->belongsTo(BillSession::class, 'bill_session_id'); }
    public function items() { return $this->hasMany(BillItem::class); }
}