<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['bn', 'en'];

        $locale = $request->session()->get('locale', $request->cookie('locale', config('app.locale', 'bn')));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = 'bn';
        }

        App::setLocale($locale);
        Carbon::setLocale($locale === 'bn' ? 'bn' : 'en');

        return $next($request);
    }
}
