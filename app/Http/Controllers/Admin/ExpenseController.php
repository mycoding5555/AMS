<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of accounts with break-even analysis.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $view = $request->get('view', 'all'); // all, income, expense, breakeven

        // Get all accounts for the period
        $accountsQuery = Account::forMonth($month, $year)->orderBy('transaction_date', 'desc');

        if ($view === 'income') {
            $accountsQuery->income();
        } elseif ($view === 'expense') {
            $accountsQuery->expense();
        }

        $accounts = $accountsQuery->get();

        // Calculate summaries
        $totalIncome = Account::forMonth($month, $year)->income()->sum('amount');
        $totalFixedCosts = Account::forMonth($month, $year)->fixedCosts()->sum('amount');
        $totalVariableCosts = Account::forMonth($month, $year)->variableCosts()->sum('amount');
        $totalBankCosts = Account::forMonth($month, $year)->bankCosts()->sum('amount');
        $totalExpenses = $totalFixedCosts + $totalVariableCosts + $totalBankCosts;
        $netProfit = $totalIncome - $totalExpenses;

        // Break-even analysis
        $breakEvenData = $this->calculateBreakEven($year);

        // Sync payments from tenants as income
        $this->syncPaymentsAsIncome($month, $year);

        return view('admin.expenses.index', compact(
            'accounts',
            'totalIncome',
            'totalFixedCosts',
            'totalVariableCosts',
            'totalBankCosts',
            'totalExpenses',
            'netProfit',
            'breakEvenData',
            'month',
            'year',
            'view'
        ));
    }

    /**
     * Show the form for creating a new account entry.
     */
    public function create()
    {
        $categories = Account::$categoryLabels;
        $costTypes = Account::$costTypeLabels;
        return view('admin.expenses.create', compact('categories', 'costTypes'));
    }

    /**
     * Store a newly created account entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:income,expense',
            'category' => 'required|string',
            'cost_type' => 'required|in:fixed,variable,bank,income',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        $date = Carbon::parse($validated['transaction_date']);
        $validated['month'] = $date->month;
        $validated['year'] = $date->year;
        $validated['user_id'] = auth()->id();

        Account::create($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Account entry created successfully.');
    }

    /**
     * Display the specified account entry.
     */
    public function show(string $id)
    {
        $account = Account::findOrFail($id);
        return view('admin.expenses.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account entry.
     */
    public function edit(string $id)
    {
        $account = Account::findOrFail($id);
        $categories = Account::$categoryLabels;
        $costTypes = Account::$costTypeLabels;
        return view('admin.expenses.edit', compact('account', 'categories', 'costTypes'));
    }

    /**
     * Update the specified account entry.
     */
    public function update(Request $request, string $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'account_type' => 'required|in:income,expense',
            'category' => 'required|string',
            'cost_type' => 'required|in:fixed,variable,bank,income',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        $date = Carbon::parse($validated['transaction_date']);
        $validated['month'] = $date->month;
        $validated['year'] = $date->year;

        $account->update($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Account entry updated successfully.');
    }

    /**
     * Remove the specified account entry.
     */
    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Account entry deleted successfully.');
    }

    /**
     * Show break-even analysis page.
     */
    public function breakeven(Request $request)
    {
        $year = $request->get('year', now()->year);
        $breakEvenData = $this->calculateBreakEven($year);

        // Monthly data for chart
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $income = Account::forMonth($m, $year)->income()->sum('amount');
            $fixed = Account::forMonth($m, $year)->fixedCosts()->sum('amount');
            $variable = Account::forMonth($m, $year)->variableCosts()->sum('amount');
            $bank = Account::forMonth($m, $year)->bankCosts()->sum('amount');

            $monthlyData[] = [
                'month' => Carbon::create($year, $m, 1)->format('M'),
                'income' => $income,
                'fixed_costs' => $fixed,
                'variable_costs' => $variable,
                'bank_costs' => $bank,
                'total_costs' => $fixed + $variable + $bank,
                'profit' => $income - ($fixed + $variable + $bank)
            ];
        }

        return view('admin.expenses.breakeven', compact('breakEvenData', 'monthlyData', 'year'));
    }

    /**
     * Calculate break-even point.
     */
    private function calculateBreakEven($year)
    {
        // Get yearly totals
        $totalIncome = Account::forYear($year)->income()->sum('amount');
        $totalFixedCosts = Account::forYear($year)->fixedCosts()->sum('amount');
        $totalVariableCosts = Account::forYear($year)->variableCosts()->sum('amount');
        $totalBankCosts = Account::forYear($year)->bankCosts()->sum('amount');

        // Total expenses
        $totalExpenses = $totalFixedCosts + $totalVariableCosts + $totalBankCosts;

        // Contribution Margin Ratio = (Revenue - Variable Costs) / Revenue
        $contributionMarginRatio = $totalIncome > 0
            ? ($totalIncome - $totalVariableCosts) / $totalIncome
            : 0;

        // Break-Even Point = Fixed Costs / Contribution Margin Ratio
        $breakEvenPoint = $contributionMarginRatio > 0
            ? ($totalFixedCosts + $totalBankCosts) / $contributionMarginRatio
            : 0;

        // Margin of Safety = (Actual Sales - Break-Even Sales) / Actual Sales
        $marginOfSafety = $totalIncome > 0
            ? (($totalIncome - $breakEvenPoint) / $totalIncome) * 100
            : 0;

        // Net Profit
        $netProfit = $totalIncome - $totalExpenses;

        // Profit Margin
        $profitMargin = $totalIncome > 0
            ? ($netProfit / $totalIncome) * 100
            : 0;

        return [
            'total_income' => $totalIncome,
            'total_fixed_costs' => $totalFixedCosts,
            'total_variable_costs' => $totalVariableCosts,
            'total_bank_costs' => $totalBankCosts,
            'total_expenses' => $totalExpenses,
            'contribution_margin_ratio' => $contributionMarginRatio * 100,
            'break_even_point' => $breakEvenPoint,
            'margin_of_safety' => $marginOfSafety,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'is_profitable' => $netProfit > 0,
            'reached_breakeven' => $totalIncome >= $breakEvenPoint
        ];
    }

    /**
     * Sync tenant payments as income entries.
     */
    private function syncPaymentsAsIncome($month, $year)
    {
        // Get payments that are not yet synced
        $payments = Payment::where('payment_status', 'paid')
            ->whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year)
            ->whereDoesntHave('account')
            ->get();

        foreach ($payments as $payment) {
            Account::create([
                'account_type' => 'income',
                'category' => 'rental_income',
                'cost_type' => 'income',
                'description' => 'Rental payment - ' . ($payment->rental->customer->name ?? 'Unknown'),
                'amount' => $payment->amount,
                'transaction_date' => $payment->paid_at,
                'month' => $payment->paid_at->month,
                'year' => $payment->paid_at->year,
                'payment_id' => $payment->id,
                'reference_number' => $payment->transaction_reference
            ]);
        }
    }
}
