<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceMode = Setting::get('maintenance_mode', 'off');
        
        // If maintenance mode is on
        if ($maintenanceMode === 'on') {
            // Allow admin users to access
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                return $next($request);
            }
            
            // Allow access to login page
            if ($request->routeIs('login') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            // Show maintenance page for everyone else
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
