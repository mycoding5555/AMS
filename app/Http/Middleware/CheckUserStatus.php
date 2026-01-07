<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            if ($user->status === 'suspended') {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is suspended.']);
            }
        }

        return $next($request);
    }
}


