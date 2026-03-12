<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private RegistrationService $registrationService
    ) {}

    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle student registration.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->registrationService->registerStudent($request->validated());

        // Auto-login and redirect to verification status page
        Auth::login($user);

        return redirect()->route('verification.status');
    }
}
