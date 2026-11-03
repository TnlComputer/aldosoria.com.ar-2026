<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AntiBot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        AntiBot::verificar($request);

        $request->merge(['email' => \App\Support\Reglas::normalizarEmail($request->email)]);

        $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        // Mismo mensaje exista o no la cuenta, para no revelar que emails estan registrados
        return back()->with('status', __(Password::RESET_LINK_SENT));
    }
}
