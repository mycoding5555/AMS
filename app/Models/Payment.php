<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
        protected $fillable = [
        'rental_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_reference',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
