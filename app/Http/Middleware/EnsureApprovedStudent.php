<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Admin can always pass
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        // Student must be approved — redirect to verification page
        if (!$request->user()->isApproved()) {
            return redirect()->route('verification.status');
        }

        return $next($request);
    }
}
