<?php
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Payment;

class DashboardController extends Controller
{
  public function index()
    {
        return view('supervisor.dashboard', [
            'activeTenants' => Customer::where('status', 'active')->count(),
            'activeRentals' => Rental::where('status', 'active')->count(),

            'pendingPayments' => Payment::where('payment_status','pending')->count(),
            'overduePayments' => Payment::where('payment_status','overdue')->count(),

            'months' => ['Jan','Feb','Mar','Apr','May','Jun'],
            'monthlyRent' => [900, 1100, 1300, 1250, 1600, 1900],

            'paymentStats' => [
                'paid' => Payment::where('payment_status','paid')->count(),
                'pending' => Payment::where('payment_status','pending')->count(),
                'overdue' => Payment::where('payment_status','overdue')->count(),
            ]
        ]);
    }
}
