<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Redirect requests from the retired verification screen.
     */
    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
