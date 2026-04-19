<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'user_id', 'bill_session_id', 'type', 'person_name',
        'description', 'amount', 'paid_amount', 'status', 'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function billSession() { return $this->belongsTo(BillSession::class); }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->paid_amount;
    }
}