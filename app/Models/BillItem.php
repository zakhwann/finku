<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = ['bill_session_id', 'bill_member_id', 'name', 'price', 'qty'];

    public function session() { return $this->belongsTo(BillSession::class, 'bill_session_id'); }
    public function member() { return $this->belongsTo(BillMember::class, 'bill_member_id'); }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->qty;
    }
}