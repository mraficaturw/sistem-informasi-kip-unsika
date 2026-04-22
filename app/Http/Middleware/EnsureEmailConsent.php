<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailConsent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Hanya cek mahasiswa yang sudah approved dan belum menjawab consent
        if ($user
            && $user->isStudent()
            && $user->isApproved()
            && !$user->hasRespondedEmailConsent()
        ) {
            return redirect()->route('email.consent');
        }

        return $next($request);
    }
}
