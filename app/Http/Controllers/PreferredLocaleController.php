<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreferredLocaleController extends Controller
{
    public function show(Request $request): RedirectResponse
    {
        $locale = $request->getPreferredLanguage(config('app.locales')) ?? 'en';

        return redirect()
            ->route('home', ['locale' => $locale])
            ->header('Vary', 'Accept-Language');
    }
}
