<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Localized
{
    /** The page being viewed, in another language (the home page when there is no localized route, e.g. a 404). */
    public static function currentUrl(string $locale): string
    {
        $route = Route::current();

        if (! $route?->getName() || ! in_array('locale', $route->parameterNames(), true)) {
            return route('home', ['locale' => $locale]);
        }

        return route($route->getName(), [...$route->parameters(), 'locale' => $locale]);
    }

    public static function other(): string
    {
        return collect(config('app.locales'))->first(fn (string $locale) => $locale !== app()->getLocale());
    }
}
