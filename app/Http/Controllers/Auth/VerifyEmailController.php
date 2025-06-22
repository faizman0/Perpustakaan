<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController
{
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard?verified=1');
        }

        $request->fulfill();

        return redirect('/dashboard?verified=1');
    }
} 