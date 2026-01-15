<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceSheetItem extends Model
{
    protected $fillable = [
        'fiscal_period_id',
        'item_type',
        'sub_type',
        'name',
        'description',
        'amount',
        'as_of_date',
        'reference_number',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'as_of_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Item type labels
    public static $itemTypeLabels = [
        'asset' => 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity'
    ];

    // Sub type labels
    public static $subTypeLabels = [
        'current_asset' => 'Current Asset',
        'fixed_asset' => 'Fixed Asset',
        'current_liability' => 'Current Liability',
        'long_term_liability' => 'Long-term Liability',
        'owner_equity' => "Owner's Equity",
        'retained_earnings' => 'Retained Earnings'
    ];

    // Predefined items
    public static $predefinedItems = [
        'asset' => [
            'current_asset' => [
                'cash' => 'Cash',
                'bank_accounts' => 'Bank Accounts',
                'accounts_receivable' => 'Accounts Receivable',
                'security_deposits_held' => 'Security Deposits Held',
                'prepaid_expenses' => 'Prepaid Expenses',
                'inventory' => 'Inventory/Supplies'
            ],
            'fixed_asset' => [
                'building' => 'Building/Property',
                'land' => 'Land',
                'furniture' => 'Furniture & Fixtures',
                'equipment' => 'Equipment',
                'vehicles' => 'Vehicles',
                'accumulated_depreciation' => 'Accumulated Depreciation'
            ]
        ],
        'liability' => [
            'current_liability' => [
                'accounts_payable' => 'Accounts Payable',
                'security_deposits_owed' => 'Security Deposits Owed',
                'short_term_loans' => 'Short-term Loans',
                'accrued_expenses' => 'Accrued Expenses',
                'unearned_rent' => 'Unearned Rent',
                'taxes_payable' => 'Taxes Payable'
            ],
            'long_term_liability' => [
                'mortgage' => 'Mortgage Payable',
                'long_term_loans' => 'Long-term Loans',
                'bonds_payable' => 'Bonds Payable'
            ]
        ],
        'equity' => [
            'owner_equity' => [
                'owner_capital' => "Owner's Capital",
                'owner_contribution' => "Owner's Contribution",
                'owner_drawings' => "Owner's Drawings"
            ],
            'retained_earnings' => [
                'retained_earnings' => 'Retained Earnings',
                'current_year_earnings' => 'Current Year Earnings'
            ]
        ]
    ];

    // Relationships
    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getItemTypeLabelAttribute()
    {
        return self::$itemTypeLabels[$this->item_type] ?? $this->item_type;
    }

    public function getSubTypeLabelAttribute()
    {
        return self::$subTypeLabels[$this->sub_type] ?? $this->sub_type;
    }

    public function getItemTypeColorAttribute()
    {
        return match($this->item_type) {
            'asset' => '#34c759',
            'liability' => '#ff3b30',
            'equity' => '#007aff',
            default => '#86868b'
        };
    }

    // Get sub types by item type
    public static function getSubTypesByItemType($itemType)
    {
        return match($itemType) {
            'asset' => ['current_asset', 'fixed_asset'],
            'liability' => ['current_liability', 'long_term_liability'],
            'equity' => ['owner_equity', 'retained_earnings'],
            default => []
        };
    }

    // Scopes
    public function scopeAssets($query)
    {
        return $query->where('item_type', 'asset');
    }

    public function scopeLiabilities($query)
    {
        return $query->where('item_type', 'liability');
    }

    public function scopeEquity($query)
    {
        return $query->where('item_type', 'equity');
    }

    public function scopeCurrentAssets($query)
    {
        return $query->where('item_type', 'asset')->where('sub_type', 'current_asset');
    }

    public function scopeFixedAssets($query)
    {
        return $query->where('item_type', 'asset')->where('sub_type', 'fixed_asset');
    }

    public function scopeCurrentLiabilities($query)
    {
        return $query->where('item_type', 'liability')->where('sub_type', 'current_liability');
    }

    public function scopeLongTermLiabilities($query)
    {
        return $query->where('item_type', 'liability')->where('sub_type', 'long_term_liability');
    }

    public function scopeForPeriod($query, $fiscalPeriodId)
    {
        return $query->where('fiscal_period_id', $fiscalPeriodId);
    }
}
