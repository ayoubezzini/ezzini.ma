@php
    use App\Resume\Period;
    use App\Resume\Resume;

    // Not route(): while the PDF is rendered the request comes from 127.0.0.1, but the document must point to the real site.
    $siteUrl = rtrim(config('app.url'), '/').'/'.app()->getLocale();
    $site = parse_url($siteUrl, PHP_URL_HOST);
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('site.meta.resume_title') }}</title>
        <meta name="description" content="{{ __('site.meta.description', ['years' => Resume::yearsOfExperience()]) }}">
        <link rel="canonical" href="{{ url()->current() }}">
        @foreach(config('app.locales') as $alternateLocale)
            <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ route('resume', ['locale' => $alternateLocale]) }}">
        @endforeach
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        @fonts
        @vite('resources/css/app.css')

        <style>
            @page {
                size: A4;
                margin: 14mm 15mm 14mm;
            }

            .sheet {
                font-size: 9.25pt;
                line-height: 1.5;
                /* Instrument Sans has a narrow space: at 9pt, "PHP · Laravel" looks cramped without it. */
                word-spacing: 0.06em;
            }

            @media screen {
                .sheet {
                    width: 210mm;
                    min-height: 297mm;
                    padding: 14mm 15mm;
                }
            }

            @media print {
                html,
                body {
                    background: #fff;
                }
            }

            .avoid-break {
                break-inside: avoid;
            }
        </style>
    </head>
    <body class="bg-sunken font-sans text-ink antialiased print:bg-white">
        <nav class="sticky top-0 z-10 border-b border-line bg-canvas/85 backdrop-blur-xl print:hidden" aria-label="{{ __('site.nav.label') }}">
            <div class="mx-auto flex h-14 max-w-[210mm] items-center justify-between gap-3 px-4">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-1.5 text-sm text-muted transition-colors hover:text-ink">
                    <x-icon name="arrow-left" class="size-4 transition-transform group-hover:-translate-x-0.5" />
                    {{ __('site.resume.back') }}
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('resume', ['locale' => App\Support\Localized::other()]) }}" class="grid h-9 place-items-center rounded-lg px-2.5 font-mono text-xs uppercase text-muted hover:bg-sunken hover:text-ink" hreflang="{{ App\Support\Localized::other() }}" title="{{ __('site.language.switch') }}">{{ __('site.language.other') }}</a>
                    <button type="button" onclick="window.print()" class="hidden h-9 items-center gap-2 rounded-full border border-line-strong bg-surface px-4 text-sm font-medium sm:inline-flex">
                        <x-icon name="printer" class="size-4 text-muted" />
                        {{ __('site.resume.print') }}
                    </button>
                    <a href="{{ Resume::downloadUrl(app()->getLocale()) }}" download class="inline-flex h-9 items-center gap-2 rounded-full bg-ink px-4 text-sm font-medium text-canvas">
                        <x-icon name="download" class="size-4" />
                        {{ __('site.resume.download') }}
                    </a>
                </div>
            </div>
        </nav>

        <div class="overflow-x-auto py-8 print:overflow-visible print:p-0">
            <main class="sheet mx-auto bg-white shadow-lifted print:shadow-none">
                <header class="flex items-end justify-between gap-6 border-b-2 border-ink pb-5">
                    <div>
                        <h1 class="text-[22pt] leading-none font-semibold tracking-[-0.015em]">{{ config('resume.name') }}</h1>
                        <p class="mt-2 text-[11pt] text-muted">{{ __('resume.role') }}</p>
                    </div>
                    <ul class="text-right font-mono text-[7.75pt] leading-[1.7] text-muted">
                        <li><a href="mailto:{{ config('resume.email') }}">{{ config('resume.email') }}</a></li>
                        <li><a href="{{ $siteUrl }}">{{ $site }}</a></li>
                        <li><a href="{{ config('resume.links.github') }}">{{ Str::after(config('resume.links.github'), 'https://') }}</a></li>
                        <li><a href="{{ config('resume.links.linkedin') }}">{{ Str::after(config('resume.links.linkedin'), 'https://www.') }}</a></li>
                    </ul>
                </header>

                <section class="mt-5 grid grid-cols-[33mm_1fr] gap-x-6">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.profile') }}</h2>
                    <p class="copy text-pretty">@copy('resume.summary')</p>
                </section>

                <section class="mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.products') }}</h2>
                    <ul class="space-y-2.5">
                        @foreach($projects as $project)
                            <li class="avoid-break">
                                <p>
                                    <a href="{{ $project->url }}" class="font-semibold">{{ $project->name }}</a>
                                    <span class="font-mono text-[7.5pt] text-subtle">· {{ $project->host() }}</span>
                                </p>
                                <p class="text-pretty text-muted">{{ $project->copy('tagline') }}</p>
                                <p class="mt-0.5 font-mono text-[7.25pt] text-subtle">{{ implode("\u{00A0}· ", $project->stack) }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.experience') }}</h2>
                    <ol class="space-y-4">
                        @foreach(config('resume.experience') as $key => $job)
                            <li class="avoid-break">
                                <div class="flex items-baseline justify-between gap-4">
                                    <h3 class="text-[10pt] font-semibold">{{ $job['company'] }}</h3>
                                    <p class="text-[8pt] text-subtle">{{ __("resume.experience.{$key}.location") }}</p>
                                </div>
                                @foreach($job['roles'] as $role => $dates)
                                    <p class="flex items-baseline justify-between gap-4">
                                        <span class="font-medium">{{ __("resume.experience.{$key}.roles.{$role}") }}</span>
                                        <span class="font-mono text-[7.5pt] text-subtle tabular-nums">{{ Period::fromArray($dates)->label() }}</span>
                                    </p>
                                @endforeach
                                <ul class="mt-1 space-y-0.5 text-muted">
                                    @foreach(__("resume.experience.{$key}.highlights") as $index => $highlight)
                                        <li class="copy flex gap-2"><span class="text-line-strong" aria-hidden="true">–</span><span class="text-pretty">@copy("resume.experience.{$key}.highlights.{$index}")</span></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="avoid-break mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.skills') }}</h2>
                    <dl class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1">
                        @foreach(config('resume.skills') as $group => $skills)
                            <dt class="font-medium">{{ __("resume.skills.{$group}") }}</dt>
                            <dd class="text-muted">{{ implode("\u{00A0}· ", $skills) }}</dd>
                        @endforeach
                    </dl>
                </section>

                <section class="avoid-break mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.education') }}</h2>
                    <ul class="space-y-1.5">
                        @foreach(config('resume.education') as $key => $dates)
                            <li>
                                <p class="flex items-baseline justify-between gap-4">
                                    <span class="font-medium">{{ __("resume.education.{$key}.title") }}</span>
                                    <span class="shrink-0 font-mono text-[7.5pt] text-subtle tabular-nums">{{ $dates['start'] }} – {{ $dates['end'] }}</span>
                                </p>
                                <p class="text-muted">{{ __("resume.education.{$key}.school") }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="avoid-break mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.languages') }}</h2>
                    <p>
                        @foreach(config('resume.languages') as $language)
                            <span class="font-medium">{{ __("resume.languages.{$language}.name") }}</span> <span class="text-muted">{{ __("resume.languages.{$language}.level") }}</span>@if(! $loop->last)<span class="px-2 text-line-strong">/</span>@endif
                        @endforeach
                    </p>
                </section>

                <section class="avoid-break mt-5 grid grid-cols-[33mm_1fr] gap-x-6 border-t border-line pt-5">
                    <h2 class="pt-px font-mono text-[7.5pt] uppercase tracking-[0.14em] text-subtle">{{ __('site.resume.interests') }}</h2>
                    <p class="text-muted">{{ __('resume.interests') }}</p>
                </section>

                <footer class="avoid-break mt-6 border-t border-line pt-4 text-center font-mono text-[7.5pt] text-subtle">
                    {{ __('site.resume.more') }} <a href="{{ $siteUrl }}" class="text-accent">{{ $site }}</a>
                </footer>
            </main>
        </div>
    </body>
</html>
