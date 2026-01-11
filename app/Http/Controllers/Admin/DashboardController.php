<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Tenant;

class DashboardController extends Controller
{
   public function index()
{
    $totalApartments = Apartment::count();
    $occupiedApartments = Apartment::occupied()->count();
    
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

        'months' => ['Jan','Feb','Mar','Apr','May','Jun'],
        'monthlyRevenue' => [1000, 1200, 1400, 1600, 1800, 2000],

        'paymentStats' => [
            'paid' => Payment::where('payment_status','paid')->count(),
            'pending' => Payment::where('payment_status','pending')->count(),
            'overdue' => Payment::where('payment_status','overdue')->count(),
        ]
    ]);
}

}
