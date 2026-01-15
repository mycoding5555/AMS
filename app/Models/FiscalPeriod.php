<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class FiscalPeriod extends Model
{
    protected $fillable = [
        'name',
        'opening_date',
        'closing_date',
        'opening_balance',
        'closing_balance',
        'status',
        'is_current',
        'notes',
        'created_by',
        'closed_by',
        'closed_at'
    ];

    protected $casts = [
        'opening_date' => 'date',
        'closing_date' => 'date',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'is_current' => 'boolean'
    ];

    // Status labels
    public static $statusLabels = [
        'draft' => 'Draft',
        'open' => 'Open',
        'closed' => 'Closed'
    ];

    // Relationships
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function balanceSheetItems()
    {
        return $this->hasMany(BalanceSheetItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => '#ff9500',
            'open' => '#34c759',
            'closed' => '#007aff',
            default => '#86868b'
        };
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Calculate totals from accounts
    // Uses both fiscal_period_id relationship AND date range for comprehensive tracking
    public function getTotalIncomeAttribute()
    {
        // First try by relationship
        $byRelation = $this->accounts()->where('account_type', 'income')->sum('amount');
        
        // Also get accounts within date range that may not be linked
        $byDate = Account::where('account_type', 'income')
            ->whereNull('fiscal_period_id')
            ->whereBetween('transaction_date', [$this->opening_date, $this->closing_date])
            ->sum('amount');
            
        return $byRelation + $byDate;
    }

    public function getTotalExpensesAttribute()
    {
        // First try by relationship
        $byRelation = $this->accounts()->where('account_type', 'expense')->sum('amount');
        
        // Also get accounts within date range that may not be linked
        $byDate = Account::where('account_type', 'expense')
            ->whereNull('fiscal_period_id')
            ->whereBetween('transaction_date', [$this->opening_date, $this->closing_date])
            ->sum('amount');
            
        return $byRelation + $byDate;
    }

    public function getNetIncomeAttribute()
    {
        return $this->total_income - $this->total_expenses;
    }

    // Balance Sheet Calculations
    public function getTotalAssetsAttribute()
    {
        return $this->balanceSheetItems()->where('item_type', 'asset')->sum('amount');
    }

    public function getTotalLiabilitiesAttribute()
    {
        return $this->balanceSheetItems()->where('item_type', 'liability')->sum('amount');
    }

    public function getTotalEquityAttribute()
    {
        return $this->balanceSheetItems()->where('item_type', 'equity')->sum('amount');
    }

    public function getCurrentAssetsAttribute()
    {
        return $this->balanceSheetItems()
            ->where('item_type', 'asset')
            ->where('sub_type', 'current_asset')
            ->sum('amount');
    }

    public function getFixedAssetsAttribute()
    {
        return $this->balanceSheetItems()
            ->where('item_type', 'asset')
            ->where('sub_type', 'fixed_asset')
            ->sum('amount');
    }

    public function getCurrentLiabilitiesAttribute()
    {
        return $this->balanceSheetItems()
            ->where('item_type', 'liability')
            ->where('sub_type', 'current_liability')
            ->sum('amount');
    }

    public function getLongTermLiabilitiesAttribute()
    {
        return $this->balanceSheetItems()
            ->where('item_type', 'liability')
            ->where('sub_type', 'long_term_liability')
            ->sum('amount');
    }

    // Calculate final closing balance
    public function calculateClosingBalance()
    {
        $netIncome = $this->net_income;
        $totalAssets = $this->total_assets;
        $totalLiabilities = $this->total_liabilities;
        
        // Final Balance = Opening Balance + Net Income + (Assets - Liabilities)
        return $this->opening_balance + $netIncome;
    }

    // Close the fiscal period
    public function closePeriod($userId = null)
    {
        // Calculate and store closing balance
        $this->closing_balance = $this->calculateClosingBalance();
        $this->status = 'closed';
        $this->closed_by = $userId ?? Auth::id();
        $this->closed_at = now();
        $this->is_current = false;
        $this->save();
        
        // Create or update Retained Earnings in equity to reflect net income
        $this->createRetainedEarningsEntry();
        
        // Link any unlinked accounts within this period's date range
        $this->linkUnlinkedAccounts();

        return $this;
    }
    
    // Create retained earnings entry from net income
    protected function createRetainedEarningsEntry()
    {
        $netIncome = $this->net_income;
        
        // Find existing retained earnings or create new
        $retainedEarnings = $this->balanceSheetItems()
            ->where('item_type', 'equity')
            ->where('sub_type', 'retained_earnings')
            ->where('name', 'Net Income for Period')
            ->first();
            
        if ($retainedEarnings) {
            $retainedEarnings->update(['amount' => $netIncome]);
        } else {
            BalanceSheetItem::create([
                'fiscal_period_id' => $this->id,
                'item_type' => 'equity',
                'sub_type' => 'retained_earnings',
                'name' => 'Net Income for Period',
                'description' => 'Automatically created from period net income',
                'amount' => $netIncome,
                'as_of_date' => $this->closing_date,
            ]);
        }
    }
    
    // Link accounts that fall within this period's date range
    protected function linkUnlinkedAccounts()
    {
        Account::whereNull('fiscal_period_id')
            ->whereBetween('transaction_date', [$this->opening_date, $this->closing_date])
            ->update(['fiscal_period_id' => $this->id]);
    }

    // Check if period can be closed
    public function canBeClosed()
    {
        return $this->status === 'open' && $this->closing_date <= now();
    }
    
    // Check accounting equation: Assets = Liabilities + Equity
    public function checkAccountingEquation()
    {
        $assets = $this->total_assets;
        $liabilitiesAndEquity = $this->total_liabilities + $this->total_equity;
        $difference = abs($assets - $liabilitiesAndEquity);
        
        return [
            'balanced' => $difference < 0.01, // Allow for small rounding differences
            'assets' => $assets,
            'liabilities' => $this->total_liabilities,
            'equity' => $this->total_equity,
            'liabilities_plus_equity' => $liabilitiesAndEquity,
            'difference' => $difference
        ];
    }
    
    // Get all transactions for this period (both linked and by date range)
    public function getAllTransactions()
    {
        return Account::where(function($query) {
            $query->where('fiscal_period_id', $this->id)
                  ->orWhere(function($q) {
                      $q->whereNull('fiscal_period_id')
                        ->whereBetween('transaction_date', [$this->opening_date, $this->closing_date]);
                  });
        })->orderBy('transaction_date', 'desc')->get();
    }

    // Set as current period
    public function setAsCurrent()
    {
        // Remove current flag from other periods
        self::where('is_current', true)->update(['is_current' => false]);
        
        $this->is_current = true;
        $this->status = 'open';
        $this->save();

        return $this;
    }

    // Get summary data for export
    public function getExportSummary()
    {
        return [
            'fiscal_period' => $this->name,
            'opening_date' => $this->opening_date->format('Y-m-d'),
            'closing_date' => $this->closing_date->format('Y-m-d'),
            'opening_balance' => $this->opening_balance,
            'total_income' => $this->total_income,
            'total_expenses' => $this->total_expenses,
            'net_income' => $this->net_income,
            'total_assets' => $this->total_assets,
            'current_assets' => $this->current_assets,
            'fixed_assets' => $this->fixed_assets,
            'total_liabilities' => $this->total_liabilities,
            'current_liabilities' => $this->current_liabilities,
            'long_term_liabilities' => $this->long_term_liabilities,
            'total_equity' => $this->total_equity,
            'closing_balance' => $this->closing_balance ?? $this->calculateClosingBalance(),
            'status' => $this->status,
            'closed_at' => $this->closed_at?->format('Y-m-d H:i:s'),
        ];
    }
}
