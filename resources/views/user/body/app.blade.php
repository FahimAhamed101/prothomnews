<!DOCTYPE html>
@php($seo = App\Models\Seo::query()->find(1))
<html lang="{{ app()->getLocale() }}" class="{{ app()->getLocale() === 'en' ? 'lang-en' : 'lang-bn' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $seo?->meta_description ?? __('Trusted news from Bangladesh and around the world—truth every day.') }}">
    <meta name="keywords" content="{{ $seo?->meta_keyword ?? 'bangla news, bangladesh, sports, entertainment, daily news' }}">
    <meta name="author" content="{{ $seo?->meta_author ?? __('Daily News') }}">
    <title>@yield('title', ($seo?->meta_title ?? __('Daily News')) . ' | ' . __('Daily News | Latest Bengali News'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&family=Noto+Serif+Bengali:wght@900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Keep Google Translate Banner at the bottom of the viewport */
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        iframe[class*="goog-te-banner"],
        iframe.goog-te-banner-frame,
        iframe.VIpgJd-ZVi9od-ORHb-OEVmcd,
        iframe.skiptranslate {
            top: auto !important;
            bottom: 0px !important;
            position: fixed !important;
            left: 0px !important;
            width: 100% !important;
            z-index: 9999999 !important;
            box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.15) !important;
            border-top: 1px solid #cbd5e1 !important;
        }
        body {
            top: 0px !important;
            position: static !important;
        }
        html body {
            margin-top: 0px !important;
            top: 0px !important;
        }
    </style>
</head>
<body class="{{ app()->getLocale() === 'en' ? 'font-sans' : 'font-bengali' }}">
    {{-- Google Translate Element --}}
    <div id="google_translate_element" style="display:none !important;"></div>

    <header class="border-b border-gray-200 bg-white">
        <div class="page-shell hidden items-center justify-between border-b border-gray-100 py-2 text-xs text-gray-600 md:flex">
            <p>{{ \App\Support\LocaleHelper::headerDate() }}</p>
            <div class="flex items-center gap-5">
                <a class="hover:text-news-red" href="{{ route('all-photo-gallery') }}">{{ __('Photos') }}</a>
                <a class="hover:text-news-red" href="{{ route('all-video-gallery') }}">{{ __('Videos') }}</a>
                <a class="hover:text-news-red" href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a>
                
                {{-- Language Switcher --}}
                <div class="flex items-center gap-1.5 border-l border-gray-200 pl-4" aria-label="Language selection">
                    <span class="text-gray-400">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </span>
                    <div class="inline-flex rounded-full bg-gray-100 p-0.5 text-[11px] font-bold">
                        <a href="{{ route('language.switch', 'bn') }}" onclick="handleLanguageSwitch('bn', event)" class="rounded-full px-2.5 py-0.5 transition {{ app()->getLocale() === 'bn' ? 'bg-news-red text-white shadow-xs' : 'text-gray-600 hover:text-news-ink' }}">বাংলা</a>
                        <a href="{{ route('language.switch', 'en') }}" onclick="handleLanguageSwitch('en', event)" class="rounded-full px-2.5 py-0.5 transition {{ app()->getLocale() === 'en' ? 'bg-news-red text-white shadow-xs' : 'text-gray-600 hover:text-news-ink' }}">EN</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-shell flex items-center justify-between gap-4 py-4 md:py-6">
            <button type="button" class="grid size-10 place-items-center border border-gray-200 md:hidden" data-menu-button aria-expanded="false" aria-label="{{ __('Open Menu') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="{{ route('user-home') }}" class="site-logo" aria-label="{{ __('Home') }}">
                <span class="site-logo__wordmark">{{ app()->getLocale() === 'en' ? 'Daily News' : 'প্রথম আলো' }}</span>
                <svg class="site-logo__sun" viewBox="0 0 70 62" aria-hidden="true">
                    <path d="M35 22a18 18 0 0 1 18 18v12H17V40a18 18 0 0 1 18-18Z" fill="currentColor"/>
                    <path d="M35 2v11M14 10l7 9M56 10l-7 9M4 29l11 4M66 29l-11 4" fill="none" stroke="currentColor" stroke-linecap="square" stroke-width="2"/>
                </svg>
            </a>

            <div class="flex items-center gap-2">
                <form action="{{ route('search-by-name') }}" method="POST" class="hidden items-center border border-gray-300 lg:flex">
                    @csrf
                    <input name="search" class="w-52 px-3 py-2 text-sm outline-none" placeholder="{{ __('Search News') }}" required>
                    <button class="grid size-9 place-items-center" aria-label="{{ __('Search') }}">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    </button>
                </form>
                @auth
                    <a href="{{ route('user-dashboard') }}" class="grid size-10 place-items-center border border-gray-200 font-bold" aria-label="{{ __('Profile') }}">{{ mb_substr(Auth::user()->name, 0, 1) }}</a>
                @else
                    <a href="{{ route('user-login') }}" class="hidden border-l border-gray-200 pl-3 text-sm font-semibold hover:text-news-red sm:block">{{ __('Login') }}</a>
                @endauth
            </div>
        </div>

        <nav class="sticky top-0 z-40 border-y border-gray-200 bg-white/95 backdrop-blur">
            <div class="page-shell hidden items-center gap-6 overflow-x-auto py-3 text-[15px] font-semibold whitespace-nowrap md:flex">
                <a href="{{ route('user-home') }}" class="text-news-red">{{ __('Latest') }}</a>
                @foreach ($navigationCategories as $category)
                    <a href="{{ route('category-news', [$category->id, $category->slug]) }}" class="hover:text-news-red">{{ \App\Support\LocaleHelper::categoryName($category->name) }}</a>
                @endforeach
                <a href="{{ route('all-video-gallery') }}" class="hover:text-news-red">{{ __('Videos') }}</a>
                <a href="{{ route('all-photo-gallery') }}" class="hover:text-news-red">{{ __('Photos') }}</a>
            </div>

            <div class="hidden border-t border-gray-100 bg-white px-4 py-4 md:hidden" data-mobile-menu>
                {{-- Mobile Language Switcher --}}
                <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Language') }}</span>
                    <div class="inline-flex rounded-full bg-gray-100 p-0.5 text-xs font-bold">
                        <a href="{{ route('language.switch', 'bn') }}" onclick="handleLanguageSwitch('bn', event)" class="rounded-full px-3 py-1 transition {{ app()->getLocale() === 'bn' ? 'bg-news-red text-white shadow-xs' : 'text-gray-600' }}">বাংলা</a>
                        <a href="{{ route('language.switch', 'en') }}" onclick="handleLanguageSwitch('en', event)" class="rounded-full px-3 py-1 transition {{ app()->getLocale() === 'en' ? 'bg-news-red text-white shadow-xs' : 'text-gray-600' }}">English</a>
                    </div>
                </div>

                <form action="{{ route('search-by-name') }}" method="POST" class="mb-4 flex border border-gray-300">
                    @csrf
                    <input name="search" class="min-w-0 flex-1 px-3 py-2 outline-none text-sm" placeholder="{{ __('Search News') }}" required>
                    <button class="bg-news-red px-4 text-white text-sm font-semibold">{{ __('Search') }}</button>
                </form>
                <div class="grid grid-cols-2 gap-3 font-semibold text-sm">
                    <a href="{{ route('user-home') }}" class="text-news-red">{{ __('Latest') }}</a>
                    @foreach ($navigationCategories as $category)
                        <a href="{{ route('category-news', [$category->id, $category->slug]) }}">{{ \App\Support\LocaleHelper::categoryName($category->name) }}</a>
                    @endforeach
                    <a href="{{ route('all-video-gallery') }}">{{ __('Videos') }}</a>
                    <a href="{{ route('all-photo-gallery') }}">{{ __('Photos') }}</a>
                    <a href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a>
                    @auth
                        <a href="{{ route('user-dashboard') }}">{{ __('Profile') }}</a>
                    @else
                        <a href="{{ route('user-login') }}">{{ __('Login') }}</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    @if ($breakingNews->isNotEmpty())
        <div class="overflow-hidden border-b border-gray-200 bg-news-paper">
            <div class="page-shell flex items-stretch overflow-hidden p-0">
                <div class="z-10 shrink-0 bg-news-red px-4 py-2 text-sm font-bold text-white">{{ __('Breaking') }}</div>
                <div class="overflow-hidden py-2">
                    <div class="news-ticker-track flex w-max gap-10 px-5 text-sm whitespace-nowrap">
                        @foreach ($breakingNews->concat($breakingNews) as $item)
                            <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="hover:text-news-red"><span class="mr-2 text-news-red">●</span>{{ $item->title }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-16 border-t border-gray-200 bg-news-paper">
        <div class="page-shell py-12">
            <div class="grid gap-10 md:grid-cols-[1.4fr_1fr_1fr]">
                <div>
                    <a href="{{ route('user-home') }}" class="site-logo site-logo--footer" aria-label="{{ __('Home') }}">
                        <span class="site-logo__wordmark">{{ app()->getLocale() === 'en' ? 'Daily News' : 'প্রথম আলো' }}</span>
                        <svg class="site-logo__sun" viewBox="0 0 70 62" aria-hidden="true">
                            <path d="M35 22a18 18 0 0 1 18 18v12H17V40a18 18 0 0 1 18-18Z" fill="currentColor"/>
                            <path d="M35 2v11M14 10l7 9M56 10l-7 9M4 29l11 4M66 29l-11 4" fill="none" stroke="currentColor" stroke-linecap="square" stroke-width="2"/>
                        </svg>
                    </a>
                    <p class="mt-4 max-w-md text-sm leading-7 text-gray-600">{{ __('Trusted news from Bangladesh and around the world—truth every day.') }}</p>
                    
                    {{-- Language Switcher in Footer --}}
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Language') }}:</span>
                        <div class="inline-flex rounded-full bg-white border border-gray-200 p-0.5 text-xs font-semibold">
                            <a href="{{ route('language.switch', 'bn') }}" onclick="handleLanguageSwitch('bn', event)" class="rounded-full px-2.5 py-0.5 {{ app()->getLocale() === 'bn' ? 'bg-news-red text-white' : 'text-gray-600 hover:text-news-ink' }}">বাংলা</a>
                            <a href="{{ route('language.switch', 'en') }}" onclick="handleLanguageSwitch('en', event)" class="rounded-full px-2.5 py-0.5 {{ app()->getLocale() === 'en' ? 'bg-news-red text-white' : 'text-gray-600 hover:text-news-ink' }}">English</a>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach ($navigationCategories->take(8) as $category)
                        <a href="{{ route('category-news', [$category->id, $category->slug]) }}" class="hover:text-news-red">{{ \App\Support\LocaleHelper::categoryName($category->name) }}</a>
                    @endforeach
                </div>
                <div class="text-sm leading-7 text-gray-600">
                    <p class="font-bold text-news-ink">{{ __('Editor & Publisher') }}</p>
                    <p>{{ __('Daily News Digital Desk') }}</p>
                    <a href="{{ route('contact-us') }}" class="text-news-red font-semibold">{{ __('Contact Us') }}</a>
                </div>
            </div>
            <div class="mt-10 flex flex-col justify-between gap-3 border-t border-gray-300 pt-5 text-xs text-gray-500 sm:flex-row">
                <p>© {{ \App\Support\LocaleHelper::number(date('Y')) }} {{ __('Daily News') }}. {{ __('All rights reserved.') }} | fahim@copyright</p>
                <div class="flex gap-4">
                    <span>{{ __('Terms of Use') }}</span>
                    <span>{{ __('Privacy Policy') }}</span>
                    <span>{{ __('Cookie Policy') }}</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Automated Translation Engine Scripts --}}
    <script type="text/javascript">
        function handleLanguageSwitch(lang, e) {
            if (lang === 'en') {
                document.cookie = "googtrans=/bn/en; path=/;";
                document.cookie = "googtrans=/bn/en; path=/; domain=" + window.location.hostname + ";";
            } else {
                document.cookie = "googtrans=/bn/bn; path=/;";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname + ";";
            }
        }

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'bn',
                includedLanguages: 'bn,en',
                autoDisplay: false
            }, 'google_translate_element');
        }

        // Keep position clean and dock translate bar to bottom
        function fixTranslateBarPosition() {
            document.body.style.top = '0px';
            const iframes = document.querySelectorAll('iframe.goog-te-banner-frame, iframe.VIpgJd-ZVi9od-ORHb-OEVmcd, iframe.skiptranslate');
            iframes.forEach(iframe => {
                iframe.style.setProperty('top', 'auto', 'important');
                iframe.style.setProperty('bottom', '0px', 'important');
                iframe.style.setProperty('position', 'fixed', 'important');
            });
        }

        const observer = new MutationObserver(fixTranslateBarPosition);
        observer.observe(document.documentElement, { attributes: true, childList: true, subtree: true });

        document.addEventListener('DOMContentLoaded', () => {
            const currentLocale = '{{ app()->getLocale() }}';
            if (currentLocale === 'en') {
                document.cookie = "googtrans=/bn/en; path=/;";
                const timer = setInterval(() => {
                    const select = document.querySelector('.goog-te-combo');
                    if (select) {
                        if (select.value !== 'en') {
                            select.value = 'en';
                            select.dispatchEvent(new Event('change'));
                        }
                        fixTranslateBarPosition();
                        clearInterval(timer);
                    }
                }, 200);
                setTimeout(() => clearInterval(timer), 6000);
            }
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
