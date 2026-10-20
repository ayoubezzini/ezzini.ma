<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        app()->setLocale($locale);

        URL::defaults(['locale' => $locale]);

        // Controllers never need the locale argument: it is already the application's locale.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
