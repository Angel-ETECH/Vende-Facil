<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Keep old verification links from blocking access to the dashboard.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
