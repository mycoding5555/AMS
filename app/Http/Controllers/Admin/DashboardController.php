<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Apartment;
use App\Models\User;

class DashboardController extends Controller
{
   public function index()
{
    return view('admin.dashboard', [
        'totalRevenue' => Payment::where('payment_status','paid')->sum('amount'),
        'overduePayments' => Payment::where('payment_status','overdue')->count(),
        'totalApartments' => Apartment::count(),
        'occupiedApartments' => Apartment::where('status','occupied')->count(),
        'availableApartments' => Apartment::where('status','available')->count(),
        'totalUsers' => User::count(),
        'occupancyRate' => Apartment::count() > 0
            ? round((Apartment::where('status','occupied')->count() / Apartment::count()) * 100, 2)
            : 0,
        'recentApartments' => Apartment::with('floor', 'supervisor')->latest()->take(5)->get(),

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
