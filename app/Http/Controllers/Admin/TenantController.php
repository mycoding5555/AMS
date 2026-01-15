<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Apartment;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants.
     */
    public function index(Request $request)
    {
        $query = Tenant::with('apartment')->notArchived();
        
        // Filter by room/apartment if room_id is provided
        if ($request->has('room_id') && $request->room_id) {
            $query->where('apartment_id', $request->room_id);
        }
        
        return view('admin.tenants.index', [
            'tenants' => $query->paginate(10),
            'apartments' => Apartment::all(),
            'selectedRoomId' => $request->room_id ?? null
        ]);
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create(Request $request)
    {
        $selectedApartmentId = $request->query('room_id') ?? null;
        return view('admin.tenants.create', [
            'apartments' => Apartment::all(),
            'selectedApartmentId' => $selectedApartmentId
        ]);
    }

    /**
     * Store a newly created tenant in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tenants',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'move_in_date' => 'required|date',
            'move_out_date' => 'nullable|date|after_or_equal:move_in_date',
            'status' => 'required|in:active,inactive,moved_out',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document' => 'nullable|mimes:pdf|max:5120'
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('tenants/photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Handle document upload
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('tenants/documents', 'public');
            $validated['document_path'] = $documentPath;
        }

        Tenant::create($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant added successfully!');
    }

    /**
     * Display the specified tenant.
     */
    public function show(Tenant $tenant)
    {
        return view('admin.tenants.show', [
            'tenant' => $tenant->load('apartment')
        ]);
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', [
            'tenant' => $tenant,
            'apartments' => Apartment::all()
        ]);
    }

    /**
     * Update the specified tenant in database.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'move_in_date' => 'required|date',
            'move_out_date' => 'nullable|date|after_or_equal:move_in_date',
            'status' => 'required|in:active,inactive,moved_out',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document' => 'nullable|mimes:pdf|max:5120'
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($tenant->photo_path && Storage::disk('public')->exists($tenant->photo_path)) {
                Storage::disk('public')->delete($tenant->photo_path);
            }
            $photoPath = $request->file('photo')->store('tenants/photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($tenant->document_path && Storage::disk('public')->exists($tenant->document_path)) {
                Storage::disk('public')->delete($tenant->document_path);
            }
            $documentPath = $request->file('document')->store('tenants/documents', 'public');
            $validated['document_path'] = $documentPath;
        }

        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully!');
    }

    /**
     * Remove the specified tenant from database.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully!');
    }

    /**
     * Show the tenant leave form
     */
    public function leaveForm(Tenant $tenant)
    {
        if ($tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'This tenant has already left.');
        }

        $tenant->load('apartment.floor');

        // Calculate stay duration for display
        $stayDuration = $tenant->getStayDurationFormatted();
        $stayDays = $tenant->getStayDurationDays();

        // Calculate estimated rent based on monthly rent and stay duration
        $monthlyRent = $tenant->apartment->monthly_rent ?? 0;
        $estimatedTotalRent = ($stayDays / 30) * $monthlyRent;

        return view('admin.tenants.leave', [
            'tenant' => $tenant,
            'stayDuration' => $stayDuration,
            'stayDays' => $stayDays,
            'estimatedTotalRent' => $estimatedTotalRent,
            'monthlyRent' => $monthlyRent
        ]);
    }

    /**
     * Process tenant leave and archive
     */
    public function processLeave(Request $request, Tenant $tenant)
    {
        if ($tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'This tenant has already left.');
        }

        $validated = $request->validate([
            'move_out_date' => 'required|date|after_or_equal:' . $tenant->move_in_date->format('Y-m-d'),
            'leave_reason' => 'nullable|string|max:1000',
            'total_rent_paid' => 'required|numeric|min:0',
            'final_utility_charges' => 'required|numeric|min:0',
            'final_other_charges' => 'required|numeric|min:0',
            'invoice_notes' => 'nullable|string|max:1000',
        ]);

        // Archive the tenant
        $tenant->archive($validated);

        // Get move out date for transaction
        $moveOutDate = Carbon::parse($validated['move_out_date']);

        // Add rental income to accounts if rent was paid
        if ($validated['total_rent_paid'] > 0) {
            Account::create([
                'account_type' => 'income',
                'category' => 'rental_income',
                'cost_type' => 'income',
                'description' => 'Final rent payment from tenant: ' . $tenant->name . ' (Room: ' . ($tenant->apartment->apartment_number ?? 'N/A') . ')',
                'amount' => $validated['total_rent_paid'],
                'transaction_date' => $moveOutDate,
                'month' => $moveOutDate->month,
                'year' => $moveOutDate->year,
                'user_id' => Auth::id(),
                'reference_number' => 'TNT-' . str_pad($tenant->id, 6, '0', STR_PAD_LEFT),
                'notes' => 'Tenant leave - Stay duration: ' . $tenant->getStayDurationFormatted()
            ]);
        }

        // Add utility charges as income if paid
        if ($validated['final_utility_charges'] > 0) {
            Account::create([
                'account_type' => 'income',
                'category' => 'other_income',
                'cost_type' => 'income',
                'description' => 'Utility charges from tenant: ' . $tenant->name . ' (Room: ' . ($tenant->apartment->apartment_number ?? 'N/A') . ')',
                'amount' => $validated['final_utility_charges'],
                'transaction_date' => $moveOutDate,
                'month' => $moveOutDate->month,
                'year' => $moveOutDate->year,
                'user_id' => Auth::id(),
                'reference_number' => 'UTL-' . str_pad($tenant->id, 6, '0', STR_PAD_LEFT),
                'notes' => 'Tenant leave utility charges'
            ]);
        }

        // Add other charges as income if paid
        if ($validated['final_other_charges'] > 0) {
            Account::create([
                'account_type' => 'income',
                'category' => 'other_income',
                'cost_type' => 'income',
                'description' => 'Other charges from tenant: ' . $tenant->name . ' (Room: ' . ($tenant->apartment->apartment_number ?? 'N/A') . ')',
                'amount' => $validated['final_other_charges'],
                'transaction_date' => $moveOutDate,
                'month' => $moveOutDate->month,
                'year' => $moveOutDate->year,
                'user_id' => Auth::id(),
                'reference_number' => 'OTH-' . str_pad($tenant->id, 6, '0', STR_PAD_LEFT),
                'notes' => 'Tenant leave other charges (damages, cleaning, etc.)'
            ]);
        }

        // Optionally generate PDF automatically
        if ($request->has('generate_invoice')) {
            return redirect()->route('admin.tenants.archived.invoice.download', $tenant)
                ->with('success', 'Tenant has been archived and income recorded successfully! Invoice is downloading.');
        }

        return redirect()->route('admin.tenants.archived.index')
            ->with('success', 'Tenant has left, income recorded, and archived successfully!');
    }
}
