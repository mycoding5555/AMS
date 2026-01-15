<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceSheetItem;
use App\Models\FiscalPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BalanceSheetController extends Controller
{
    /**
     * Display balance sheet items for a fiscal period.
     */
    public function index(Request $request)
    {
        $fiscalPeriodId = $request->get('fiscal_period_id');
        $itemType = $request->get('item_type'); // asset, liability, equity
        
        $query = BalanceSheetItem::with(['fiscalPeriod', 'user'])
            ->orderBy('item_type')
            ->orderBy('sub_type')
            ->orderBy('created_at', 'desc');

        if ($fiscalPeriodId) {
            $query->where('fiscal_period_id', $fiscalPeriodId);
        }

        if ($itemType) {
            $query->where('item_type', $itemType);
        }

        $items = $query->get()->groupBy(['item_type', 'sub_type']);
        $fiscalPeriods = FiscalPeriod::orderBy('opening_date', 'desc')->get();
        $currentPeriod = FiscalPeriod::current()->first();

        // Calculate totals
        $totals = [
            'assets' => BalanceSheetItem::when($fiscalPeriodId, fn($q) => $q->where('fiscal_period_id', $fiscalPeriodId))
                ->assets()->sum('amount'),
            'liabilities' => BalanceSheetItem::when($fiscalPeriodId, fn($q) => $q->where('fiscal_period_id', $fiscalPeriodId))
                ->liabilities()->sum('amount'),
            'equity' => BalanceSheetItem::when($fiscalPeriodId, fn($q) => $q->where('fiscal_period_id', $fiscalPeriodId))
                ->equity()->sum('amount'),
        ];

        return view('admin.revenue-expense.balance-sheet.index', compact(
            'items',
            'fiscalPeriods',
            'currentPeriod',
            'fiscalPeriodId',
            'itemType',
            'totals'
        ));
    }

    /**
     * Show form for creating a new balance sheet item.
     */
    public function create(Request $request)
    {
        $fiscalPeriods = FiscalPeriod::where('status', '!=', 'closed')
            ->orderBy('opening_date', 'desc')
            ->get();
        
        $itemTypes = BalanceSheetItem::$itemTypeLabels;
        $subTypes = BalanceSheetItem::$subTypeLabels;
        $predefinedItems = BalanceSheetItem::$predefinedItems;
        $defaultPeriodId = $request->get('fiscal_period_id');

        return view('admin.revenue-expense.balance-sheet.create', compact(
            'fiscalPeriods',
            'itemTypes',
            'subTypes',
            'predefinedItems',
            'defaultPeriodId'
        ));
    }

    /**
     * Store a newly created balance sheet item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'item_type' => 'required|in:asset,liability,equity',
            'sub_type' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'as_of_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        // Validate sub_type matches item_type
        $validSubTypes = BalanceSheetItem::getSubTypesByItemType($validated['item_type']);
        if (!in_array($validated['sub_type'], $validSubTypes)) {
            return back()->withErrors(['sub_type' => 'Invalid sub-type for the selected item type.']);
        }

        // Check if fiscal period is not closed
        $fiscalPeriod = FiscalPeriod::find($validated['fiscal_period_id']);
        if ($fiscalPeriod->status === 'closed') {
            return back()->withErrors(['fiscal_period_id' => 'Cannot add items to a closed fiscal period.']);
        }

        $validated['user_id'] = Auth::id();

        BalanceSheetItem::create($validated);

        return redirect()->route('admin.balance-sheet.index', ['fiscal_period_id' => $validated['fiscal_period_id']])
            ->with('success', 'Balance sheet item added successfully.');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(BalanceSheetItem $balanceSheet)
    {
        if ($balanceSheet->fiscalPeriod->status === 'closed') {
            return redirect()->route('admin.balance-sheet.index')
                ->with('error', 'Cannot edit items in a closed fiscal period.');
        }

        $fiscalPeriods = FiscalPeriod::where('status', '!=', 'closed')
            ->orderBy('opening_date', 'desc')
            ->get();
        
        $itemTypes = BalanceSheetItem::$itemTypeLabels;
        $subTypes = BalanceSheetItem::$subTypeLabels;
        $predefinedItems = BalanceSheetItem::$predefinedItems;

        return view('admin.revenue-expense.balance-sheet.edit', compact(
            'balanceSheet',
            'fiscalPeriods',
            'itemTypes',
            'subTypes',
            'predefinedItems'
        ));
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, BalanceSheetItem $balanceSheet)
    {
        if ($balanceSheet->fiscalPeriod->status === 'closed') {
            return redirect()->route('admin.balance-sheet.index')
                ->with('error', 'Cannot edit items in a closed fiscal period.');
        }

        $validated = $request->validate([
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'item_type' => 'required|in:asset,liability,equity',
            'sub_type' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'as_of_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        $balanceSheet->update($validated);

        return redirect()->route('admin.balance-sheet.index', ['fiscal_period_id' => $validated['fiscal_period_id']])
            ->with('success', 'Balance sheet item updated successfully.');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(BalanceSheetItem $balanceSheet)
    {
        if ($balanceSheet->fiscalPeriod->status === 'closed') {
            return redirect()->route('admin.balance-sheet.index')
                ->with('error', 'Cannot delete items from a closed fiscal period.');
        }

        $fiscalPeriodId = $balanceSheet->fiscal_period_id;
        $balanceSheet->delete();

        return redirect()->route('admin.balance-sheet.index', ['fiscal_period_id' => $fiscalPeriodId])
            ->with('success', 'Balance sheet item deleted successfully.');
    }
}
