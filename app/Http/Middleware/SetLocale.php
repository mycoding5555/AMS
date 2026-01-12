<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = Setting::get('language', 'en');
        
        // Set the application locale
        app()->setLocale($language);
        
        // Set Carbon locale for date formatting
        \Carbon\Carbon::setLocale($language);
        
        return $next($request);
    }
}
