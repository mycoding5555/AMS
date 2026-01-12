<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'document_path',
        'archived_at',
        'leave_reason',
        'final_utility_charges',
        'final_other_charges',
        'total_rent_paid',
        'invoice_notes'
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'archived_at' => 'datetime',
        'final_utility_charges' => 'decimal:2',
        'final_other_charges' => 'decimal:2',
        'total_rent_paid' => 'decimal:2',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Scope to get only active (non-archived) tenants
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at')->where('status', 'active');
    }

    /**
     * Scope to get only archived tenants
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope to get non-archived tenants
     */
    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Check if tenant is archived
     */
    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /**
     * Archive the tenant (mark as left)
     */
    public function archive(array $data = []): void
    {
        $this->update([
            'status' => 'moved_out',
            'move_out_date' => $data['move_out_date'] ?? now(),
            'archived_at' => now(),
            'leave_reason' => $data['leave_reason'] ?? null,
            'final_utility_charges' => $data['final_utility_charges'] ?? 0,
            'final_other_charges' => $data['final_other_charges'] ?? 0,
            'total_rent_paid' => $data['total_rent_paid'] ?? 0,
            'invoice_notes' => $data['invoice_notes'] ?? null,
        ]);
    }

    /**
     * Restore archived tenant
     */
    public function restore(): void
    {
        $this->update([
            'status' => 'active',
            'archived_at' => null,
            'move_out_date' => null,
        ]);
    }

    /**
     * Calculate the duration of stay in days
     */
    public function getStayDurationDays(): int
    {
        $endDate = $this->move_out_date ?? Carbon::now();
        return $this->move_in_date->diffInDays($endDate);
    }

    /**
     * Calculate the duration of stay as formatted string
     */
    public function getStayDurationFormatted(): string
    {
        $endDate = $this->move_out_date ?? Carbon::now();
        $diff = $this->move_in_date->diff($endDate);
        
        $parts = [];
        if ($diff->y > 0) $parts[] = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
        if ($diff->m > 0) $parts[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
        if ($diff->d > 0) $parts[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        
        return implode(', ', $parts) ?: '0 days';
    }

    /**
     * Calculate total amount due on final invoice
     */
    public function getTotalFinalAmount(): float
    {
        return $this->total_rent_paid + $this->final_utility_charges + $this->final_other_charges;
    }
}

