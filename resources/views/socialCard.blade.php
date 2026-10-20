<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <title>{{ config('resume.name') }}</title>
        @fonts
        @vite('resources/css/app.css')
    </head>
    {{-- Screenshotted at 1200×630 by `php artisan render-documents`. --}}
    <body class="m-0 overflow-hidden bg-canvas font-sans text-ink antialiased">
        <div class="relative flex h-[630px] w-[1200px] flex-col justify-between p-[72px]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,var(--accent-soft),transparent_45%)]" aria-hidden="true"></div>

            <div class="relative flex items-center justify-between">
                <span class="grid size-14 place-items-center rounded-2xl bg-ink font-serif text-[2rem] italic leading-none text-canvas">ae</span>
                <span class="font-mono text-xl text-muted">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
            </div>

            <div class="relative">
                <p class="font-mono text-xl text-muted">{{ config('resume.name') }} · {{ __('resume.role') }}</p>
                <h1 class="copy mt-6 max-w-[960px] text-[5.25rem] leading-[1.02] font-semibold tracking-[-0.035em]">@copy('resume.headline')</h1>
            </div>

            <ul class="relative flex items-center gap-8 font-mono text-xl text-muted">
                @foreach(App\Resume\Project::all() as $project)
                    <li class="flex items-center gap-3">
                        <span class="size-3 rounded-full" style="background-color: {{ $project->accent }}"></span>
                        {{ $project->host() }}
                    </li>
                @endforeach
            </ul>
        </div>
    </body>
</html>
