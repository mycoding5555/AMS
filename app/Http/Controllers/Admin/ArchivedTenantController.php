<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ArchivedTenantController extends Controller
{
    /**
     * Display a listing of archived tenants.
     */
    public function index(Request $request)
    {
        $query = Tenant::with('apartment.floor')->archived();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $archivedTenants = $query->orderBy('archived_at', 'desc')->paginate(10);

        return view('admin.tenants.archived', [
            'tenants' => $archivedTenants,
            'search' => $request->search ?? ''
        ]);
    }

    /**
     * Show archived tenant details
     */
    public function show(Tenant $tenant)
    {
        if (!$tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'This tenant is not archived.');
        }

        return view('admin.tenants.archived-show', [
            'tenant' => $tenant->load('apartment.floor')
        ]);
    }

    /**
     * Restore an archived tenant back to active status
     */
    public function restore(Tenant $tenant)
    {
        if (!$tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'This tenant is not archived.');
        }

        $tenant->restore();

        return redirect()->route('admin.tenants.archived.index')
            ->with('success', 'Tenant has been restored successfully!');
    }

    /**
     * Generate PDF invoice for archived tenant
     */
    public function generateInvoice(Tenant $tenant)
    {
        if (!$tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'Invoice can only be generated for archived tenants.');
        }

        $tenant->load('apartment.floor');

        $pdf = Pdf::loadView('admin.tenants.invoice-pdf', [
            'tenant' => $tenant,
            'invoiceNumber' => 'INV-' . str_pad($tenant->id, 6, '0', STR_PAD_LEFT),
            'generatedAt' => now()
        ]);

        $filename = 'tenant-invoice-' . $tenant->id . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * View invoice in browser
     */
    public function viewInvoice(Tenant $tenant)
    {
        if (!$tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'Invoice can only be viewed for archived tenants.');
        }

        $tenant->load('apartment.floor');

        $pdf = Pdf::loadView('admin.tenants.invoice-pdf', [
            'tenant' => $tenant,
            'invoiceNumber' => 'INV-' . str_pad($tenant->id, 6, '0', STR_PAD_LEFT),
            'generatedAt' => now()
        ]);

        return $pdf->stream('tenant-invoice-' . $tenant->id . '.pdf');
    }

    /**
     * Permanently delete an archived tenant
     */
    public function destroy(Tenant $tenant)
    {
        if (!$tenant->isArchived()) {
            return redirect()->route('admin.tenants.index')
                ->with('error', 'Only archived tenants can be permanently deleted.');
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.archived.index')
            ->with('success', 'Archived tenant has been permanently deleted.');
    }
}
