<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update last activity for authenticated customer
        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();
            
            // Update last_login_at if not updated in the last hour
            if (!$customer->last_login_at || $customer->last_login_at->diffInHours(now()) >= 1) {
                $customer->update(['last_login_at' => now()]);
            }
        }

        return $next($request);
    }
}
