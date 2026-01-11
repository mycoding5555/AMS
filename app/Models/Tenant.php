<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'apartment_id',
        'name',
        'email',
        'phone',
        'address',
        'move_in_date',
        'move_out_date',
        'status',
        'notes',
        'photo_path',
        'document_path'
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

