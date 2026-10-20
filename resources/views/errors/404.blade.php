@php
    $locale = in_array(request()->segment(1), config('app.locales'), true) ? request()->segment(1) : 'en';

    app()->setLocale($locale);
    URL::defaults(['locale' => $locale]);
@endphp

<x-layout :title="__('site.meta.not_found_title')">
    <section class="mx-auto flex min-h-[60vh] max-w-5xl flex-col justify-center px-5 py-24 sm:px-8">
        <p class="font-mono text-xs uppercase tracking-[0.14em] text-accent">404</p>
        <h1 class="mt-4 max-w-2xl text-4xl font-semibold tracking-[-0.035em] text-balance sm:text-6xl">{{ __('site.not_found.title') }}</h1>
        <p class="mt-5 max-w-xl text-lg leading-relaxed text-pretty text-muted">{{ __('site.not_found.text') }}</p>
        <a href="{{ route('home') }}" class="group mt-10 inline-flex h-11 w-fit items-center gap-2 rounded-full bg-ink pr-4 pl-5 text-sm font-medium text-canvas">
            {{ __('site.not_found.back') }}
            <x-icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
        </a>
    </section>
</x-layout>
