<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('X-Livewire')) {
            $todayKey = 'visitor_tracked_' . now()->format('Y-m-d');
            if (!session()->has($todayKey)) {
                $this->incrementVisitorStats();
                session()->put($todayKey, true);
            }
        }

        return $next($request);
    }

    private function incrementVisitorStats(): void
    {
        cache()->put('visitors_today', cache()->get('visitors_today', 0) + 1, now()->endOfDay());
        cache()->put('visitors_week',  cache()->get('visitors_week',  0) + 1, now()->next('Monday')->startOfDay());
        cache()->put('visitors_month', cache()->get('visitors_month', 0) + 1, now()->addMonth()->startOfMonth());
        cache()->put('visitors_total', cache()->get('visitors_total', 0) + 1);
    }
}
