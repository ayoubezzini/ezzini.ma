@php
    $home = route('home');
    $onHome = request()->routeIs('home');
@endphp

<header
    x-data="header"
    :class="scrolled ? 'border-line bg-canvas/80 backdrop-blur-xl backdrop-saturate-150' : 'border-transparent'"
    class="sticky top-0 z-40 border-b border-transparent transition-colors duration-300"
>
    <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-5 sm:px-8">
        <a href="{{ $home }}" class="group -m-1.5 flex items-center gap-2.5 rounded-lg p-1.5" aria-label="{{ __('site.nav.home') }}">
            <span class="grid size-8 place-items-center rounded-[0.6rem] bg-ink font-serif text-[1.15rem] italic leading-none text-canvas transition-transform duration-300 group-hover:-rotate-6" aria-hidden="true">ae</span>
            <span class="hidden text-sm font-semibold tracking-tight sm:block">{{ config('resume.name') }}</span>
        </a>

        <nav aria-label="{{ __('site.nav.label') }}" class="hidden md:block">
            <ul class="flex items-center gap-1 text-sm text-muted">
                @foreach(['work' => 'work', 'experience' => 'experience', 'open-source' => 'open_source', 'approach' => 'approach', 'contact' => 'contact'] as $anchor => $label)
                    <li>
                        <a href="{{ $onHome ? '' : $home }}#{{ $anchor }}" class="rounded-md px-3 py-2 transition-colors hover:text-ink">
                            {{ __("site.nav.{$label}") }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-1">
            <a
                href="{{ App\Support\Localized::currentUrl(App\Support\Localized::other()) }}"
                hreflang="{{ App\Support\Localized::other() }}"
                lang="{{ App\Support\Localized::other() }}"
                title="{{ __('site.language.switch') }}"
                class="grid h-9 min-w-9 place-items-center rounded-lg px-2 font-mono text-xs font-medium uppercase tracking-wider text-muted transition-colors hover:bg-sunken hover:text-ink"
            >
                <span class="sr-only">{{ __('site.language.switch') }}</span>
                <span aria-hidden="true">{{ __('site.language.other') }}</span>
            </a>

            <button
                type="button"
                x-data
                x-on:click="$store.theme.toggle()"
                :aria-label="$store.theme.current === 'dark' ? @js(__('site.theme.light')) : @js(__('site.theme.dark'))"
                aria-label="{{ __('site.theme.label') }}"
                class="grid size-9 place-items-center rounded-lg text-muted transition-colors hover:bg-sunken hover:text-ink"
            >
                <x-icon name="moon" class="size-[1.1rem] dark:hidden" />
                <x-icon name="sun" class="hidden size-[1.1rem] dark:block" />
            </button>

            <button
                type="button"
                x-data
                x-on:click="$dispatch('palette-open')"
                aria-label="{{ __('site.palette.open') }}"
                aria-keyshortcuts="Meta+K Control+K"
                class="ml-1 flex h-9 items-center gap-2 rounded-lg border border-line bg-surface pr-1.5 pl-2.5 text-muted shadow-soft transition-colors hover:border-line-strong hover:text-ink"
            >
                <x-icon name="search" class="size-[0.95rem]" />
                <kbd class="kbd h-6 border-b pointer-coarse:hidden" x-data="{ mac: /Mac|iPhone|iPad/.test(navigator.platform) }" x-text="mac ? '⌘K' : 'Ctrl K'">⌘K</kbd>
            </button>
        </div>
    </div>
</header>
