<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
        protected $fillable = [
        'floor_id',
        'room_number',
        'apartment_number',
        'monthly_rent',
        'status',
        'supervisor_id'
    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
