<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Apartment;

class DashboardController extends Controller
{
   public function index()
{
    return view('admin.dashboard', [
        'totalRevenue' => Payment::where('payment_status','paid')->sum('amount'),
        'overduePayments' => Payment::where('payment_status','overdue')->count(),
        'occupancyRate' => Apartment::count() > 0
            ? round((Apartment::where('status','occupied')->count() / Apartment::count()) * 100, 2)
            : 0,

        'months' => ['Jan','Feb','Mar','Apr','May','Jun'],
        'monthlyRevenue' => [1200, 1500, 1800, 1600, 2100, 2400],

        'paymentStats' => [
            'paid' => Payment::where('payment_status','paid')->count(),
            'pending' => Payment::where('payment_status','pending')->count(),
            'overdue' => Payment::where('payment_status','overdue')->count(),
        ]
    ]);
}

}
