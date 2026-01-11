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

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * Scope to get only occupied apartments (those with active tenants)
     */
    public function scopeOccupied($query)
    {
        return $query->whereHas('tenants', function ($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Scope to get only available apartments (those without active tenants)
     */
    public function scopeAvailable($query)
    {
        return $query->doesntHave('tenants')
            ->orWhereHas('tenants', function ($q) {
                $q->where('status', '!=', 'active');
            });
    }
}

