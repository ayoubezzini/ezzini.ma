@php
    $revision = config('app.revision');
    $manifest = public_path('build/manifest.json');
    $builtAt = file_exists($manifest) ? Carbon\CarbonImmutable::createFromTimestamp(filemtime($manifest), config('app.timezone')) : null;
@endphp

<footer class="mt-24 border-t border-line">
    <div class="mx-auto flex max-w-5xl flex-col gap-6 px-5 py-10 text-sm text-muted sm:px-8 md:flex-row md:items-end md:justify-between">
        <div class="space-y-2">
            <p class="copy">@copy('site.footer.built')</p>

            @if($revision && $builtAt)
                <p class="font-mono text-xs text-subtle">
                    {!! __('site.footer.deployed', [
                        'commit' => '<a href="'.e(config('resume.repository')."/commit/{$revision}").'" class="underline decoration-line-strong underline-offset-2 transition-colors hover:text-ink">'.e(Str::limit($revision, 7, '')).'</a>',
                        'date' => e($builtAt->locale(app()->getLocale())->isoFormat('LL')),
                    ]) !!}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-5">
            <p class="hidden font-mono text-xs text-subtle lg:block" x-data="{ mac: /Mac|iPhone|iPad/.test(navigator.platform) }">
                {!! __('site.footer.palette', ['shortcut' => '<kbd class="kbd" x-text="mac ? \'⌘K\' : \'Ctrl K\'">⌘K</kbd>']) !!}
            </p>

            <ul class="flex items-center gap-1">
                <li>
                    <a href="{{ config('resume.links.github') }}" class="grid size-9 place-items-center rounded-lg transition-colors hover:bg-sunken hover:text-ink" rel="me noopener" target="_blank" aria-label="GitHub">
                        <x-icon name="github" class="size-[1.05rem]" />
                    </a>
                </li>
                <li>
                    <a href="{{ config('resume.links.linkedin') }}" class="grid size-9 place-items-center rounded-lg transition-colors hover:bg-sunken hover:text-ink" rel="me noopener" target="_blank" aria-label="LinkedIn">
                        <x-icon name="linkedin" class="size-[1.05rem]" />
                    </a>
                </li>
                <li>
                    <a href="{{ App\Resume\Resume::phoneUri() }}" class="grid size-9 place-items-center rounded-lg transition-colors hover:bg-sunken hover:text-ink" aria-label="{{ config('resume.phone') }}">
                        <x-icon name="phone" class="size-[1.05rem]" />
                    </a>
                </li>
                <li>
                    <a href="mailto:{{ config('resume.email') }}" class="grid size-9 place-items-center rounded-lg transition-colors hover:bg-sunken hover:text-ink" aria-label="{{ config('resume.email') }}">
                        <x-icon name="mail" class="size-[1.05rem]" />
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
