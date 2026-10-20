@props([
    'title' => __('site.meta.title'),
    'description' => __('site.meta.description', ['years' => App\Resume\Resume::yearsOfExperience()]),
])

@php
    $locale = app()->getLocale();
    $socialCard = asset(App\Resume\Resume::socialCardPath($locale));
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" data-theme="light" class="no-js">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">
        <meta name="author" content="{{ config('resume.name') }}">

        {{-- Runs before the first paint: no flash of the wrong theme. --}}
        <script>
            (() => {
                const root = document.documentElement;
                let theme = null;

                try {
                    theme = localStorage.getItem('theme');
                } catch {}

                root.dataset.theme = theme ?? (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                root.classList.replace('no-js', 'js');
            })();
        </script>

        <link rel="canonical" href="{{ url()->current() }}">
        @foreach(config('app.locales') as $alternateLocale)
            <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ App\Support\Localized::currentUrl($alternateLocale) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ route('preferredLocale') }}">

        <meta property="og:type" content="profile">
        <meta property="og:site_name" content="{{ config('resume.name') }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ $socialCard }}">
        <meta property="og:image:width" content="2400">
        <meta property="og:image:height" content="1260">
        <meta property="og:locale" content="{{ $locale === 'fr' ? 'fr_FR' : 'en_US' }}">
        <meta property="profile:first_name" content="Ayoub">
        <meta property="profile:last_name" content="Ezzini">
        <meta name="twitter:card" content="summary_large_image">

        <meta name="theme-color" content="#faf9f6" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#0e0e11" media="(prefers-color-scheme: dark)">
        <meta name="color-scheme" content="light dark">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => config('resume.name'),
                'jobTitle' => __('resume.role'),
                'email' => 'mailto:'.config('resume.email'),
                'telephone' => config('resume.phone'),
                'url' => route('home'),
                'image' => $socialCard,
                'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'MA'],
                'sameAs' => array_values(config('resume.links')),
                'knowsLanguage' => config('resume.languages'),
                'knowsAbout' => ['PHP', 'Laravel', 'Symfony', 'Alpine.js', 'Vue.js', 'MySQL', 'Docker', 'UI/UX'],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-dvh bg-canvas font-sans text-ink antialiased [font-feature-settings:'ss01','cv11']">
        <a href="#content" class="sr-only z-50 rounded-md bg-ink px-3 py-2 text-sm text-canvas focus:not-sr-only focus:fixed focus:top-3 focus:left-3">
            {{ __('site.skip') }}
        </a>

        <x-header />

        <main id="content" tabindex="-1" class="outline-none">
            {{ $slot }}
        </main>

        <x-footer />

        <x-palette />

        <div
            x-data
            x-cloak
            x-show="$store.toast.visible"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-end="translate-y-1 opacity-0"
            role="status"
            aria-live="polite"
            class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-full bg-ink px-4 py-2 text-sm font-medium text-canvas shadow-lifted"
        >
            <span class="inline-flex items-center gap-2">
                <x-icon name="check" class="size-4" />
                <span x-text="$store.toast.message"></span>
            </span>
        </div>
    </body>
</html>
