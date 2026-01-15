<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        // Get all settings with defaults
        $appSettings = [
            'app_name' => Setting::get('app_name', 'Apartment Management System'),
            'app_start_date' => Setting::get('app_start_date', null),
            'app_close_date' => Setting::get('app_close_date', null),
            'language' => Setting::get('language', 'en'),
            'theme' => Setting::get('theme', 'light'),
            'currency' => Setting::get('currency', 'USD'),
            'timezone' => Setting::get('timezone', 'UTC'),
            'company_name' => Setting::get('company_name', ''),
            'company_address' => Setting::get('company_address', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_email' => Setting::get('company_email', ''),
            'tax_rate' => Setting::get('tax_rate', '0'),
            'maintenance_mode' => Setting::get('maintenance_mode', 'off'),
        ];
        
        return view('admin.settings.index', compact('appSettings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_start_date' => 'nullable|date',
            'app_close_date' => 'nullable|date',
            'language' => 'required|in:en,km',
            'theme' => 'required|in:light,dark',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'maintenance_mode' => 'required|in:on,off',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
