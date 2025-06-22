<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class EmailVerificationPromptController
{
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended('/dashboard')
            : view('auth.verify-email');
    }
} 