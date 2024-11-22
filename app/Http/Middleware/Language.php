<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('applocale', config('app.fallback_locale'));
        if (array_key_exists($locale, config('app.locales'))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}


