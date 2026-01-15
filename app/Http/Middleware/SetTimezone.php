<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = Setting::get('timezone', 'UTC');
        
        // Set the default timezone
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);
        
        return $next($request);
    }
}
