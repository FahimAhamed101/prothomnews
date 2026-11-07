<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch application language.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, ['bn', 'en'], true)) {
            $request->session()->put('locale', $locale);
            cookie()->queue(cookie('locale', $locale, 60 * 24 * 365, '/'));

            if ($locale === 'en') {
                cookie()->queue(cookie('googtrans', '/bn/en', 60 * 24 * 365, '/', null, false, false));
            } else {
                cookie()->queue(cookie('googtrans', '/bn/bn', 60 * 24 * 365, '/', null, false, false));
                cookie()->queue(cookie()->forget('googtrans'));
            }
        }

        return redirect()->back();
    }
}
