<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Account;
use App\Models\Setting;
use App\Helpers\SettingsHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    $totalApartments = Apartment::count();
    $occupiedApartments = Apartment::occupied()->count();
    $currentYear = now()->year;
    $currentMonth = now()->month;

    // Get monthly data for charts (last 6 months)
    $monthlyData = [];
    $months = [];
    $monthlyRevenue = [];
    $monthlyExpenses = [];
    $monthlyProfit = [];

    for ($i = 5; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $month = $date->month;
        $year = $date->year;
        
        $months[] = $date->format('M');
        
        $income = Account::forMonth($month, $year)->income()->sum('amount');
        $expenses = Account::forMonth($month, $year)->expense()->sum('amount');
        
        $monthlyRevenue[] = (float) $income;
        $monthlyExpenses[] = (float) $expenses;
        $monthlyProfit[] = (float) ($income - $expenses);
    }

    // Current month financial summary
    $currentMonthIncome = Account::forMonth($currentMonth, $currentYear)->income()->sum('amount');
    $currentMonthExpenses = Account::forMonth($currentMonth, $currentYear)->expense()->sum('amount');
    $currentMonthProfit = $currentMonthIncome - $currentMonthExpenses;

    // Yearly totals
    $yearlyIncome = Account::forYear($currentYear)->income()->sum('amount');
    $yearlyExpenses = Account::forYear($currentYear)->expense()->sum('amount');
    $yearlyProfit = $yearlyIncome - $yearlyExpenses;

    // Expense breakdown by cost type
    $fixedCosts = Account::forYear($currentYear)->fixedCosts()->sum('amount');
    $variableCosts = Account::forYear($currentYear)->variableCosts()->sum('amount');
    $bankCosts = Account::forYear($currentYear)->bankCosts()->sum('amount');

    // Break-even calculation
    $contributionMarginRatio = $yearlyIncome > 0
        ? ($yearlyIncome - $variableCosts) / $yearlyIncome
        : 0;
    $breakEvenPoint = $contributionMarginRatio > 0
        ? ($fixedCosts + $bankCosts) / $contributionMarginRatio
        : 0;
    $reachedBreakeven = $yearlyIncome >= $breakEvenPoint;

    return view('admin.dashboard', [
        'totalRevenue' => Payment::where('payment_status','paid')->sum('amount'),
        'overduePayments' => Payment::where('payment_status','overdue')->count(),
        'totalApartments' => $totalApartments,
        'occupiedApartments' => $occupiedApartments,
        'availableApartments' => $totalApartments - $occupiedApartments,
        'totalUsers' => User::count(),
        'occupancyRate' => $totalApartments > 0
            ? round(($occupiedApartments / $totalApartments) * 100, 2)
            : 0,
        'recentApartments' => Apartment::with('floor', 'supervisor')->get(),

        // Chart data
        'months' => $months,
        'monthlyRevenue' => $monthlyRevenue,
        'monthlyExpenses' => $monthlyExpenses,
        'monthlyProfit' => $monthlyProfit,

        // Financial summary
        'currentMonthIncome' => $currentMonthIncome,
        'currentMonthExpenses' => $currentMonthExpenses,
        'currentMonthProfit' => $currentMonthProfit,
        'yearlyIncome' => $yearlyIncome,
        'yearlyExpenses' => $yearlyExpenses,
        'yearlyProfit' => $yearlyProfit,

        // Expense breakdown
        'fixedCosts' => $fixedCosts,
        'variableCosts' => $variableCosts,
        'bankCosts' => $bankCosts,

        // Break-even
        'breakEvenPoint' => $breakEvenPoint,
        'reachedBreakeven' => $reachedBreakeven,

        // Settings data
        'currency' => SettingsHelper::getCurrency(),
        'appStartDate' => SettingsHelper::getStartDate(),
        'appCloseDate' => SettingsHelper::getCloseDate(),
        'companyName' => SettingsHelper::getCompanyName(),

        'paymentStats' => [
            'paid' => Payment::where('payment_status','paid')->count(),
            'pending' => Payment::where('payment_status','pending')->count(),
            'overdue' => Payment::where('payment_status','overdue')->count(),
        ]
    ]);
}

}
