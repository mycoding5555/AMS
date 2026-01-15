<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalPeriod;
use App\Models\BalanceSheetItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FiscalPeriodController extends Controller
{
    /**
     * Display listing of fiscal periods.
     */
    public function index()
    {
        $fiscalPeriods = FiscalPeriod::orderBy('opening_date', 'desc')->get();
        $currentPeriod = FiscalPeriod::current()->first();

        return view('admin.revenue-expense.fiscal-periods.index', compact('fiscalPeriods', 'currentPeriod'));
    }

    /**
     * Show form for creating a new fiscal period.
     */
    public function create()
    {
        // Get the last closed period to suggest opening balance
        $lastPeriod = FiscalPeriod::closed()->orderBy('closing_date', 'desc')->first();
        $suggestedOpeningBalance = $lastPeriod?->closing_balance ?? 0;

        return view('admin.revenue-expense.fiscal-periods.create', compact('suggestedOpeningBalance', 'lastPeriod'));
    }

    /**
     * Store a newly created fiscal period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'opening_date' => 'required|date',
            'closing_date' => 'required|date|after:opening_date',
            'opening_balance' => 'required|numeric',
            'notes' => 'nullable|string',
            'set_as_current' => 'nullable|boolean',
            'carry_forward_balance_sheet' => 'nullable|boolean'
        ]);

        $fiscalPeriod = FiscalPeriod::create([
            'name' => $validated['name'],
            'opening_date' => $validated['opening_date'],
            'closing_date' => $validated['closing_date'],
            'opening_balance' => $validated['opening_balance'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'draft',
            'created_by' => Auth::id()
        ]);

        // Carry forward balance sheet items from previous closed period
        if ($request->boolean('carry_forward_balance_sheet')) {
            $this->carryForwardBalanceSheet($fiscalPeriod);
        }

        if ($request->boolean('set_as_current')) {
            $fiscalPeriod->setAsCurrent();
        }

        return redirect()->route('admin.fiscal-periods.index')
            ->with('success', 'Fiscal period created successfully.');
    }
    
    /**
     * Carry forward balance sheet items from the last closed period.
     */
    protected function carryForwardBalanceSheet(FiscalPeriod $newPeriod)
    {
        $lastPeriod = FiscalPeriod::closed()
            ->where('id', '!=', $newPeriod->id)
            ->orderBy('closing_date', 'desc')
            ->first();
            
        if (!$lastPeriod) {
            return;
        }
        
        // Get items to carry forward (assets, liabilities, and permanent equity items)
        $itemsToCarry = $lastPeriod->balanceSheetItems()
            ->whereIn('item_type', ['asset', 'liability'])
            ->orWhere(function($query) use ($lastPeriod) {
                $query->where('fiscal_period_id', $lastPeriod->id)
                      ->where('item_type', 'equity')
                      ->where('sub_type', 'owner_equity');
            })
            ->get();
            
        foreach ($itemsToCarry as $item) {
            BalanceSheetItem::create([
                'fiscal_period_id' => $newPeriod->id,
                'item_type' => $item->item_type,
                'sub_type' => $item->sub_type,
                'name' => $item->name,
                'description' => 'Carried forward from ' . $lastPeriod->name,
                'amount' => $item->amount,
                'as_of_date' => $newPeriod->opening_date,
            ]);
        }
        
        // Create retained earnings from previous period's closing balance
        $previousRetainedEarnings = $lastPeriod->closing_balance - $lastPeriod->opening_balance;
        if ($previousRetainedEarnings != 0) {
            BalanceSheetItem::create([
                'fiscal_period_id' => $newPeriod->id,
                'item_type' => 'equity',
                'sub_type' => 'retained_earnings',
                'name' => 'Retained Earnings (Carried Forward)',
                'description' => 'Accumulated from previous fiscal periods',
                'amount' => $previousRetainedEarnings,
                'as_of_date' => $newPeriod->opening_date,
            ]);
        }
    }

    /**
     * Display the specified fiscal period with balance sheet.
     */
    public function show(FiscalPeriod $fiscalPeriod)
    {
        $balanceSheetItems = $fiscalPeriod->balanceSheetItems()
            ->orderBy('item_type')
            ->orderBy('sub_type')
            ->get()
            ->groupBy(['item_type', 'sub_type']);

        // Get income/expense summary
        $totalIncome = $fiscalPeriod->total_income;
        $totalExpenses = $fiscalPeriod->total_expenses;
        $netIncome = $fiscalPeriod->net_income;
        
        // Check accounting equation
        $accountingEquation = $fiscalPeriod->checkAccountingEquation();
        
        // Get all transactions for this period
        $transactions = $fiscalPeriod->getAllTransactions();

        return view('admin.revenue-expense.fiscal-periods.show', compact(
            'fiscalPeriod',
            'balanceSheetItems',
            'totalIncome',
            'totalExpenses',
            'netIncome',
            'accountingEquation',
            'transactions'
        ));
    }

    /**
     * Show the form for editing the specified fiscal period.
     */
    public function edit(FiscalPeriod $fiscalPeriod)
    {
        if ($fiscalPeriod->status === 'closed') {
            return redirect()->route('admin.fiscal-periods.show', $fiscalPeriod)
                ->with('error', 'Cannot edit a closed fiscal period.');
        }

        return view('admin.revenue-expense.fiscal-periods.edit', compact('fiscalPeriod'));
    }

    /**
     * Update the specified fiscal period.
     */
    public function update(Request $request, FiscalPeriod $fiscalPeriod)
    {
        if ($fiscalPeriod->status === 'closed') {
            return redirect()->route('admin.fiscal-periods.show', $fiscalPeriod)
                ->with('error', 'Cannot edit a closed fiscal period.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'opening_date' => 'required|date',
            'closing_date' => 'required|date|after:opening_date',
            'opening_balance' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $fiscalPeriod->update($validated);

        return redirect()->route('admin.fiscal-periods.show', $fiscalPeriod)
            ->with('success', 'Fiscal period updated successfully.');
    }

    /**
     * Set fiscal period as current/active.
     */
    public function setAsCurrent(FiscalPeriod $fiscalPeriod)
    {
        if ($fiscalPeriod->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot set a closed period as current.');
        }

        $fiscalPeriod->setAsCurrent();

        return redirect()->route('admin.fiscal-periods.index')
            ->with('success', 'Fiscal period set as current.');
    }

    /**
     * Close the fiscal period.
     */
    public function close(FiscalPeriod $fiscalPeriod)
    {
        if (!$fiscalPeriod->canBeClosed()) {
            return redirect()->back()
                ->with('error', 'This period cannot be closed yet. Please ensure the closing date has passed.');
        }

        $fiscalPeriod->closePeriod(Auth::id());

        return redirect()->route('admin.fiscal-periods.show', $fiscalPeriod)
            ->with('success', 'Fiscal period closed successfully. Closing balance: $' . number_format($fiscalPeriod->closing_balance, 2));
    }

    /**
     * Export closing balance report.
     */
    public function exportClosingBalance(FiscalPeriod $fiscalPeriod)
    {
        $summary = $fiscalPeriod->getExportSummary();
        
        // Get detailed breakdowns
        $assets = $fiscalPeriod->balanceSheetItems()->assets()->get();
        $liabilities = $fiscalPeriod->balanceSheetItems()->liabilities()->get();
        $equity = $fiscalPeriod->balanceSheetItems()->equity()->get();
        
        $incomeAccounts = $fiscalPeriod->accounts()->where('account_type', 'income')->get();
        $expenseAccounts = $fiscalPeriod->accounts()->where('account_type', 'expense')->get();

        return view('admin.revenue-expense.fiscal-periods.export', compact(
            'fiscalPeriod',
            'summary',
            'assets',
            'liabilities',
            'equity',
            'incomeAccounts',
            'expenseAccounts'
        ));
    }

    /**
     * Download closing balance as PDF.
     */
    public function downloadClosingBalance(FiscalPeriod $fiscalPeriod)
    {
        $summary = $fiscalPeriod->getExportSummary();
        $assets = $fiscalPeriod->balanceSheetItems()->assets()->get();
        $liabilities = $fiscalPeriod->balanceSheetItems()->liabilities()->get();
        $equity = $fiscalPeriod->balanceSheetItems()->equity()->get();
        $incomeAccounts = $fiscalPeriod->accounts()->where('account_type', 'income')->get();
        $expenseAccounts = $fiscalPeriod->accounts()->where('account_type', 'expense')->get();

        $pdf = Pdf::loadView('admin.revenue-expense.fiscal-periods.pdf', compact(
            'fiscalPeriod',
            'summary',
            'assets',
            'liabilities',
            'equity',
            'incomeAccounts',
            'expenseAccounts'
        ));

        return $pdf->download('closing-balance-' . Str::slug($fiscalPeriod->name) . '.pdf');
    }

    /**
     * Remove the specified fiscal period.
     */
    public function destroy(FiscalPeriod $fiscalPeriod)
    {
        if ($fiscalPeriod->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot delete a closed fiscal period.');
        }

        if ($fiscalPeriod->accounts()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a fiscal period with transactions.');
        }

        $fiscalPeriod->delete();

        return redirect()->route('admin.fiscal-periods.index')
            ->with('success', 'Fiscal period deleted successfully.');
    }
}
