@php
    use App\Resume\Resume;
    use App\Support\Localized;

    $home = route('home');
    $onHome = request()->routeIs('home');
    $otherLocale = Localized::other();

    $sections = ['work' => 'work', 'experience' => 'experience', 'open-source' => 'open_source', 'approach' => 'approach', 'contact' => 'contact'];
@endphp

<header
    x-data="header"
    x-trap.inert.noscroll.noautofocus="menu"
    x-on:keydown.escape.window="menu = false"
    x-on:resize.window.debounce.100ms="if (window.innerWidth >= 1024) menu = false"
    :class="menu ? 'border-line bg-canvas' : (scrolled ? 'border-line bg-canvas/80 backdrop-blur-xl backdrop-saturate-150' : 'border-transparent')"
    class="sticky top-0 z-40 border-b border-transparent transition-colors duration-300"
>
    <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-5 sm:px-8">
        <a href="{{ $home }}" class="group -m-1.5 flex min-w-0 items-center gap-2.5 rounded-lg p-1.5">
            <span class="grid size-8 shrink-0 place-items-center rounded-[0.6rem] bg-ink font-serif text-[1.15rem] italic leading-none text-canvas transition-transform duration-300 group-hover:-rotate-6" aria-hidden="true">ae</span>
            <span class="truncate text-sm font-semibold tracking-tight">{{ config('resume.name') }}</span>
        </a>

        <nav aria-label="{{ __('site.nav.label') }}" class="hidden lg:block">
            <ul class="flex items-center gap-1 text-sm text-muted">
                @foreach($sections as $anchor => $label)
                    <li>
                        <a href="{{ $onHome ? '' : $home }}#{{ $anchor }}" class="rounded-md px-3 py-2 transition-colors hover:text-ink">
                            {{ __("site.nav.{$label}") }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex shrink-0 items-center gap-1">
            <a
                href="{{ Localized::currentUrl($otherLocale) }}"
                hreflang="{{ $otherLocale }}"
                lang="{{ $otherLocale }}"
                title="{{ __('site.language.switch') }}"
                class="hidden h-9 min-w-9 place-items-center rounded-lg px-2 font-mono text-xs font-medium uppercase tracking-wider text-muted transition-colors hover:bg-sunken hover:text-ink sm:grid"
            >
                <span class="sr-only">{{ __('site.language.switch') }}</span>
                <span aria-hidden="true">{{ __('site.language.other') }}</span>
            </a>

            <button
                type="button"
                x-on:click="$store.theme.toggle()"
                :aria-label="$store.theme.current === 'dark' ? @js(__('site.theme.light')) : @js(__('site.theme.dark'))"
                aria-label="{{ __('site.theme.label') }}"
                class="hidden size-9 place-items-center rounded-lg text-muted transition-colors hover:bg-sunken hover:text-ink sm:grid"
            >
                <x-icon name="moon" class="size-[1.1rem] dark:hidden" />
                <x-icon name="sun" class="hidden size-[1.1rem] dark:block" />
            </button>

            <button
                type="button"
                x-on:click="menu = false; $dispatch('palette-open')"
                aria-label="{{ __('site.palette.open') }}"
                aria-keyshortcuts="Meta+K Control+K"
                class="ml-1 flex h-9 items-center gap-2 rounded-lg border border-line bg-surface pr-1.5 pl-2.5 text-muted shadow-soft transition-colors hover:border-line-strong hover:text-ink pointer-coarse:w-9 pointer-coarse:justify-center pointer-coarse:px-0"
            >
                <x-icon name="search" class="size-[0.95rem]" />
                <kbd class="kbd h-6 border-b pointer-coarse:hidden" x-data="{ mac: /Mac|iPhone|iPad/.test(navigator.platform) }" x-text="mac ? '⌘K' : 'Ctrl K'">⌘K</kbd>
            </button>

            {{-- Two lines that cross into an X. --}}
            <button
                type="button"
                x-on:click="menu = ! menu"
                :aria-expanded="menu"
                :aria-label="menu ? @js(__('site.nav.close')) : @js(__('site.nav.open'))"
                aria-label="{{ __('site.nav.open') }}"
                aria-expanded="false"
                aria-controls="mobile-menu"
                class="relative grid size-9 place-items-center rounded-lg text-ink transition-colors hover:bg-sunken lg:hidden"
            >
                <span :class="menu ? 'rotate-45' : '-translate-y-[3.5px]'" class="absolute h-[1.5px] w-[1.125rem] rounded-full bg-current transition-transform duration-300 ease-[cubic-bezier(0.2,0.8,0.2,1)]" aria-hidden="true"></span>
                <span :class="menu ? '-rotate-45' : 'translate-y-[3.5px]'" class="absolute h-[1.5px] w-[1.125rem] rounded-full bg-current transition-transform duration-300 ease-[cubic-bezier(0.2,0.8,0.2,1)]" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <div
        id="mobile-menu"
        x-cloak
        x-show="menu"
        x-transition:enter="transition duration-300 ease-[cubic-bezier(0.2,0.8,0.2,1)]"
        x-transition:enter-start="-translate-y-2 opacity-0"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-end="opacity-0"
        class="absolute inset-x-0 top-full h-[calc(100dvh-4rem)] overflow-y-auto overscroll-contain border-t border-line bg-canvas lg:hidden"
    >
        <div class="mx-auto flex min-h-full max-w-5xl flex-col px-5 pt-4 pb-[max(2rem,env(safe-area-inset-bottom))] sm:px-8">
            <nav aria-label="{{ __('site.nav.label') }}">
                <ol class="divide-y divide-line">
                    @foreach($sections as $anchor => $label)
                        <li>
                            <a
                                href="{{ $onHome ? '' : $home }}#{{ $anchor }}"
                                x-on:click="go($event)"
                                class="group flex items-baseline gap-4 py-4 text-2xl font-semibold tracking-[-0.03em]"
                            >
                                <span class="w-6 font-mono text-xs font-normal tracking-normal text-accent tabular-nums">{{ sprintf('%02d', $loop->iteration) }}</span>
                                <span class="transition-colors group-hover:text-accent">{{ __("site.nav.{$label}") }}</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </nav>

            <div class="mt-auto space-y-6 pt-10">
                <a href="{{ Resume::downloadUrl(app()->getLocale()) }}" class="flex h-12 items-center justify-center gap-2 rounded-full bg-ink text-sm font-medium text-canvas">
                    <x-icon name="download" class="size-4" />
                    {{ __('site.hero.resume') }}
                </a>

                <div class="flex items-center justify-between gap-4 border-t border-line pt-5">
                    <div class="-ml-2.5 flex items-center gap-1">
                        <a href="{{ config('resume.links.github') }}" target="_blank" rel="me noopener" class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:text-ink" aria-label="GitHub">
                            <x-icon name="github" class="size-5" />
                        </a>
                        <a href="{{ config('resume.links.linkedin') }}" target="_blank" rel="me noopener" class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:text-ink" aria-label="LinkedIn">
                            <x-icon name="linkedin" class="size-5" />
                        </a>
                        <a href="{{ Resume::phoneUri() }}" class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:text-ink" aria-label="{{ config('resume.phone') }}">
                            <x-icon name="phone" class="size-5" />
                        </a>
                    </div>

                    <div class="-mr-2.5 flex items-center gap-1">
                        <a
                            href="{{ Localized::currentUrl($otherLocale) }}"
                            hreflang="{{ $otherLocale }}"
                            lang="{{ $otherLocale }}"
                            class="grid h-11 min-w-11 place-items-center rounded-full px-2 font-mono text-xs font-medium uppercase tracking-wider text-muted transition-colors hover:text-ink"
                        >
                            <span class="sr-only">{{ __('site.language.switch') }}</span>
                            <span aria-hidden="true">{{ __('site.language.other') }}</span>
                        </a>
                        <button
                            type="button"
                            x-on:click="$store.theme.toggle()"
                            :aria-label="$store.theme.current === 'dark' ? @js(__('site.theme.light')) : @js(__('site.theme.dark'))"
                            aria-label="{{ __('site.theme.label') }}"
                            class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:text-ink"
                        >
                            <x-icon name="moon" class="size-5 dark:hidden" />
                            <x-icon name="sun" class="hidden size-5 dark:block" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
