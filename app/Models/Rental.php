<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
      protected $fillable = [
        'customer_id',
        'apartment_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
