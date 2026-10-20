@php
    $home = route('home');
    $otherLocale = App\Support\Localized::other();

    $items = [
        ['id' => 'home', 'group' => 'pages', 'icon' => 'home', 'label' => __('site.palette.items.home'), 'href' => $home],
        ...collect(['work' => 'sparkle', 'experience' => 'briefcase', 'approach' => 'grid', 'contact' => 'mail'])
            ->map(fn (string $icon, string $section) => [
                'id' => $section,
                'group' => 'pages',
                'icon' => $icon,
                'label' => __("site.palette.items.{$section}"),
                'href' => "{$home}#{$section}",
            ])
            ->values(),
        ['id' => 'resume', 'group' => 'pages', 'icon' => 'file', 'label' => __('site.palette.items.resume'), 'href' => route('resume'), 'keywords' => 'cv resume'],
        ...App\Resume\Project::all()->map(fn (App\Resume\Project $project) => [
            'id' => "project-{$project->slug}",
            'group' => 'projects',
            'icon' => 'arrow-right',
            'label' => $project->name,
            'hint' => $project->host(),
            'href' => route('projects.show', $project),
            'keywords' => $project->copy('tagline'),
        ]),
        ['id' => 'copy-email', 'group' => 'actions', 'icon' => 'copy', 'label' => __('site.palette.items.copy_email'), 'hint' => config('resume.email'), 'action' => 'copyEmail', 'value' => config('resume.email'), 'confirmation' => __('site.contact.copied'), 'keywords' => 'email mail contact'],
        ['id' => 'download', 'group' => 'actions', 'icon' => 'download', 'label' => __('site.palette.items.download_resume'), 'href' => App\Resume\Resume::downloadUrl(app()->getLocale()), 'external' => true, 'keywords' => 'cv pdf resume'],
        ['id' => 'theme', 'group' => 'actions', 'icon' => 'moon', 'label' => __('site.palette.items.theme'), 'action' => 'theme', 'keywords' => 'dark light mode theme'],
        ['id' => 'language', 'group' => 'actions', 'icon' => 'language', 'label' => __('site.palette.items.language'), 'href' => App\Support\Localized::currentUrl($otherLocale), 'keywords' => 'language langue english français'],
        ['id' => 'grid', 'group' => 'actions', 'icon' => 'grid', 'label' => __('site.palette.items.grid'), 'hint' => '⇧G', 'action' => 'grid', 'keywords' => 'grid layout baseline'],
        ['id' => 'source', 'group' => 'links', 'icon' => 'code', 'label' => __('site.palette.items.source'), 'href' => config('resume.repository'), 'external' => true, 'keywords' => 'github source code open'],
        ['id' => 'github', 'group' => 'links', 'icon' => 'github', 'label' => 'GitHub', 'href' => config('resume.links.github'), 'external' => true],
        ['id' => 'linkedin', 'group' => 'links', 'icon' => 'linkedin', 'label' => 'LinkedIn', 'href' => config('resume.links.linkedin'), 'external' => true],
    ];

    $icons = collect($items)->pluck('icon')->unique();
@endphp

<div
    x-data="palette(@js($items), @js(__('site.palette.groups')))"
    x-on:palette-open.window="show()"
    x-on:keydown.escape.window="open && hide()"
>
    {{-- Icons are rendered once by Blade and cloned by Alpine, so the menu stays pure HTML. --}}
    <template x-ref="icons">
        @foreach($icons as $icon)
            <x-icon :name="$icon" data-icon="{{ $icon }}" class="size-4" />
        @endforeach
    </template>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition duration-150 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-ink/20 backdrop-blur-[2px] dark:bg-black/50"
        x-on:click="hide()"
        aria-hidden="true"
    ></div>

    <div
        x-cloak
        x-show="open"
        x-trap.inert.noscroll="open"
        x-transition:enter="transition duration-200 ease-[cubic-bezier(0.2,0.8,0.2,1)]"
        x-transition:enter-start="translate-y-1 scale-[0.98] opacity-0"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-end="scale-[0.99] opacity-0"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('site.palette.title') }}"
        class="fixed inset-x-3 top-[12vh] z-50 mx-auto max-w-xl overflow-hidden rounded-2xl border border-line bg-surface shadow-lifted sm:inset-x-6"
    >
        <div class="flex items-center gap-3 border-b border-line px-4">
            <x-icon name="search" class="size-[1.1rem] text-subtle" />
            <input
                x-ref="input"
                x-model="query"
                x-on:keydown.arrow-down.prevent="move(1)"
                x-on:keydown.arrow-up.prevent="move(-1)"
                x-on:keydown.enter.prevent="run()"
                type="text"
                role="combobox"
                aria-expanded="true"
                aria-controls="palette-results"
                :aria-activedescendant="results[active] ? 'palette-' + results[active].id : null"
                autocomplete="off"
                spellcheck="false"
                placeholder="{{ __('site.palette.placeholder') }}"
                class="h-14 w-full bg-transparent text-[0.95rem] text-ink outline-none placeholder:text-subtle"
            >
            <kbd class="kbd" aria-hidden="true">esc</kbd>
        </div>

        <ul id="palette-results" x-ref="list" role="listbox" class="max-h-[min(24rem,60vh)] overflow-y-auto overscroll-contain p-2">
            <template x-for="group in grouped" :key="group.key">
                <li role="presentation">
                    <p class="px-3 pt-3 pb-1.5 font-mono text-[0.6875rem] uppercase tracking-wider text-subtle" x-text="group.label"></p>
                    <ul role="presentation">
                        <template x-for="item in group.items" :key="item.id">
                            <li
                                :id="'palette-' + item.id"
                                role="option"
                                :aria-selected="indexOf(item) === active"
                                x-on:mousemove="active = indexOf(item)"
                                x-on:click="run(item)"
                                :class="indexOf(item) === active ? 'bg-ink/[0.06] text-ink' : 'text-muted'"
                                class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 text-sm"
                            >
                                <span class="grid size-7 place-items-center rounded-md border border-line bg-surface text-muted" x-html="$refs.icons.content.querySelector(`[data-icon='${item.icon}']`)?.outerHTML"></span>
                                <span class="flex-1 truncate" x-text="item.label"></span>
                                <span x-show="item.hint" class="font-mono text-xs text-subtle" x-text="item.hint"></span>
                                <x-icon name="arrow-up-right" class="size-3.5 text-subtle" x-show="item.external" />
                            </li>
                        </template>
                    </ul>
                </li>
            </template>

            <li x-show="results.length === 0" class="px-3 py-10 text-center text-sm text-muted" x-text="@js(__('site.palette.empty')).replace(':query', query)"></li>
        </ul>

        <div class="flex items-center gap-4 border-t border-line bg-sunken/60 px-4 py-2.5 font-mono text-[0.6875rem] text-subtle">
            <span class="inline-flex items-center gap-1.5"><kbd class="kbd">↑</kbd><kbd class="kbd">↓</kbd> {{ __('site.palette.navigate') }}</span>
            <span class="inline-flex items-center gap-1.5"><kbd class="kbd">↵</kbd> {{ __('site.palette.select') }}</span>
            <span class="ml-auto inline-flex items-center gap-1.5"><kbd class="kbd">esc</kbd> {{ __('site.palette.close') }}</span>
        </div>
    </div>
</div>
