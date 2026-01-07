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
        'occupancyRate' => round(
            (Apartment::where('status','occupied')->count() / Apartment::count()) * 100, 2
        )
    ]);
    }
}
