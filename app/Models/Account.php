<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'fiscal_period_id',
        'account_type',
        'category',
        'cost_type',
        'description',
        'amount',
        'transaction_date',
        'month',
        'year',
        'payment_id',
        'user_id',
        'reference_number',
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Category labels for display
    public static $categoryLabels = [
        // Income
        'rental_income' => 'Rental Income',
        'deposit' => 'Security Deposit',
        'late_fee' => 'Late Fee',
        'other_income' => 'Other Income',
        // Fixed Costs
        'rent_building' => 'Building Rent/Mortgage',
        'insurance' => 'Insurance',
        'property_tax' => 'Property Tax',
        'salary' => 'Staff Salary',
        'loan_payment' => 'Loan Payment',
        'depreciation' => 'Depreciation',
        // Variable Costs
        'utilities' => 'Utilities (Electric/Water)',
        'maintenance' => 'Maintenance',
        'cleaning' => 'Cleaning Services',
        'supplies' => 'Supplies',
        'marketing' => 'Marketing/Advertising',
        'repairs' => 'Repairs',
        // Bank
        'bank_fee' => 'Bank Fees',
        'bank_interest' => 'Bank Interest',
        'bank_transfer' => 'Bank Transfer',
        // Other
        'other_expense' => 'Other Expense'
    ];

    // Cost type labels
    public static $costTypeLabels = [
        'fixed' => 'Fixed Cost',
        'variable' => 'Variable Cost',
        'bank' => 'Bank/Financial',
        'income' => 'Income'
    ];

    // Get categories by cost type
    public static function getCategoriesByType($type)
    {
        $categories = [
            'income' => ['rental_income', 'deposit', 'late_fee', 'other_income'],
            'fixed' => ['rent_building', 'insurance', 'property_tax', 'salary', 'loan_payment', 'depreciation'],
            'variable' => ['utilities', 'maintenance', 'cleaning', 'supplies', 'marketing', 'repairs'],
            'bank' => ['bank_fee', 'bank_interest', 'bank_transfer'],
        ];

        return $categories[$type] ?? [];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function getCategoryLabelAttribute()
    {
        return self::$categoryLabels[$this->category] ?? $this->category;
    }

    public function getCostTypeLabelAttribute()
    {
        return self::$costTypeLabels[$this->cost_type] ?? $this->cost_type;
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('account_type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('account_type', 'expense');
    }

    public function scopeFixedCosts($query)
    {
        return $query->where('cost_type', 'fixed');
    }

    public function scopeVariableCosts($query)
    {
        return $query->where('cost_type', 'variable');
    }

    public function scopeBankCosts($query)
    {
        return $query->where('cost_type', 'bank');
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
