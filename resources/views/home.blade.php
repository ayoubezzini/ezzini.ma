@php
    use App\Resume\Period;
    use App\Resume\Resume;
    use App\Services\Turnstile\TurnstileVerifier;
@endphp

<x-layout>
    <div class="mx-auto max-w-5xl px-5 sm:px-8">

        {{-- Hero --}}
        <section class="pt-16 pb-20 sm:pt-24 sm:pb-28" aria-labelledby="hero-title">
            <p class="flex flex-wrap items-center gap-x-3 gap-y-1 font-mono text-xs text-muted" data-reveal>
                {{-- On phones the headline right below already says it, and the clock would wrap. --}}
                <span class="hidden sm:inline">{{ __('resume.role') }}</span>
                <span class="hidden text-line-strong sm:inline" aria-hidden="true">/</span>
                <span class="inline-flex items-center gap-1.5">
                    <x-icon name="pin" class="size-3.5 text-subtle" />
                    {{ __('resume.location') }}
                </span>
                <span
                    x-data="clock(@js(config('resume.timezone')), @js(app()->getLocale()))"
                    class="inline-flex items-center gap-1.5"
                    title="{{ __('site.hero.local_time') }}"
                >
                    <span class="size-1.5 rounded-full bg-accent" aria-hidden="true"></span>
                    <span class="sr-only">{{ __('site.hero.local_time') }}</span>
                    {{-- Fixed width, so the line doesn't shift when the time appears. --}}
                    <time class="inline-block min-w-[5ch] tabular-nums" x-text="time"></time>
                </span>
            </p>

            <h1 id="hero-title" class="copy mt-7 max-w-4xl text-[2.6rem] leading-[1.04] font-semibold tracking-[-0.04em] text-balance sm:text-6xl md:text-[4.25rem]" data-reveal style="--reveal-delay: 60ms">
                @copy('resume.headline')
            </h1>

            <p class="copy mt-7 max-w-[40rem] text-lg leading-relaxed text-pretty text-muted sm:text-xl sm:leading-relaxed" data-reveal style="--reveal-delay: 120ms">
                @copy('resume.intro')
            </p>

            {{-- Buttons on one row, profile links on the next on phones; all on one line from sm. --}}
            <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-3" data-reveal style="--reveal-delay: 180ms">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#contact" class="group inline-flex h-11 items-center gap-2 rounded-full bg-ink pr-4 pl-5 text-sm font-medium text-canvas shadow-soft transition-all hover:shadow-lifted active:scale-[0.98]">
                        {{ __('site.hero.contact') }}
                        <x-icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </a>
                    <a href="{{ Resume::downloadUrl(app()->getLocale()) }}" class="group inline-flex h-11 items-center gap-2 rounded-full border border-line-strong bg-surface px-5 text-sm font-medium text-ink transition-colors hover:border-ink/40 active:scale-[0.98]">
                        <x-icon name="download" class="size-4 text-muted transition-transform duration-300 group-hover:translate-y-0.5" />
                        {{ __('site.hero.resume') }}
                    </a>
                </div>

                <span class="mx-1 hidden h-5 w-px bg-line sm:block" aria-hidden="true"></span>

                {{-- -ml-3: the icon glyph, not its 44px tap target, lines up with the buttons above. --}}
                <div class="-ml-3 flex items-center gap-1 sm:ml-0">
                    <a href="{{ config('resume.links.github') }}" target="_blank" rel="me noopener" class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:bg-sunken hover:text-ink" aria-label="GitHub">
                        <x-icon name="github" class="size-5" />
                    </a>
                    <a href="{{ config('resume.links.linkedin') }}" target="_blank" rel="me noopener" class="grid size-11 place-items-center rounded-full text-muted transition-colors hover:bg-sunken hover:text-ink" aria-label="LinkedIn">
                        <x-icon name="linkedin" class="size-5" />
                    </a>
                </div>
            </div>

            <dl class="mt-16 grid grid-cols-3 border-t border-line sm:mt-20" data-reveal style="--reveal-delay: 240ms">
                @foreach(__('resume.stats') as $key => $stat)
                    <div @class(['flex flex-col pt-6 pr-4', 'border-l border-line pl-4 sm:pl-8' => ! $loop->first])>
                        <dt class="order-2 mt-1.5 text-xs leading-snug text-pretty text-muted sm:text-sm">{{ $stat['label'] }}</dt>
                        <dd class="order-1 text-3xl font-semibold tracking-[-0.04em] tabular-nums sm:text-[2.75rem]">@copy("resume.stats.{$key}.value")</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- Work --}}
        <section id="work" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="work-title">
            <x-section-heading number="01" :eyebrow="__('site.sections.work.eyebrow')" :title="__('site.sections.work.title')" id="work-title">
                <x-slot:intro>{{ __('site.sections.work.intro') }}</x-slot:intro>
            </x-section-heading>

            <div class="mt-14 space-y-20 sm:space-y-28">
                @foreach($projects as $project)
                    <article class="group grid items-center gap-8 lg:grid-cols-12 lg:gap-12" data-reveal aria-labelledby="project-{{ $project->slug }}">
                        <div @class(['lg:col-span-5', 'lg:order-2' => $loop->even])>
                            <p class="flex items-center gap-2.5 font-mono text-xs text-subtle">
                                <span class="size-2 rounded-full" style="background-color: {{ $project->accent }}" aria-hidden="true"></span>
                                {{ $project->host() }}
                                <span aria-hidden="true">·</span>
                                {{ $project->year }}
                            </p>
                            <h3 id="project-{{ $project->slug }}" class="mt-3 text-2xl font-semibold tracking-[-0.03em] sm:text-3xl">{{ $project->name }}</h3>
                            <p class="mt-3 text-lg leading-relaxed text-balance text-muted">{{ $project->copy('tagline') }}</p>

                            <ul class="mt-6 flex flex-wrap gap-1.5" aria-label="{{ __('site.project.stack') }}">
                                @foreach(array_slice($project->stack, 0, 6) as $technology)
                                    <x-tag>{{ $technology }}</x-tag>
                                @endforeach
                            </ul>

                            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium">
                                <a href="{{ route('projects.show', $project) }}" class="group/link inline-flex items-center gap-1.5 text-ink">
                                    {{ __('site.project.case_study') }}
                                    <x-icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover/link:translate-x-0.5" />
                                </a>
                                <a href="{{ $project->url }}" target="_blank" rel="noopener" class="group/link inline-flex items-center gap-1 text-muted transition-colors hover:text-ink">
                                    {{ __('site.project.visit', ['host' => $project->host()]) }}
                                    <x-icon name="arrow-up-right" class="size-3.5 transition-transform duration-300 group-hover/link:translate-x-px group-hover/link:-translate-y-px" />
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('projects.show', $project) }}" @class(['block lg:col-span-7', 'lg:order-1' => $loop->even]) tabindex="-1" aria-hidden="true">
                            <x-browser-frame :project="$project" class="transition duration-500 ease-out group-hover:-translate-y-1 group-hover:shadow-lifted" style="view-transition-name: shot-{{ $project->slug }}" />
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Experience --}}
        <section id="experience" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="experience-title">
            <x-section-heading number="02" :eyebrow="__('site.sections.experience.eyebrow')" :title="__('site.sections.experience.title')" id="experience-title" />

            <ol class="mt-14 border-t border-line">
                @foreach(config('resume.experience') as $key => $job)
                    @php($tenure = Period::covering($job['roles']))

                    <li class="grid gap-x-12 gap-y-4 border-b border-line py-10 last:border-b-0 md:grid-cols-[12rem_minmax(0,1fr)]" data-reveal>
                        <div class="font-mono text-xs leading-relaxed text-subtle md:pt-1.5">
                            <p class="text-muted">{{ $tenure->label() }}</p>
                            <p>{{ $tenure->duration() }}</p>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                                <h3 class="text-xl font-semibold tracking-[-0.02em]">
                                    @if($job['url'])
                                        <a href="{{ $job['url'] }}" target="_blank" rel="noopener" class="group/company inline-flex items-center gap-1 transition-colors hover:text-accent">
                                            {{ $job['company'] }}
                                            <x-icon name="arrow-up-right" class="size-3.5 text-subtle transition-transform duration-300 group-hover/company:translate-x-px group-hover/company:-translate-y-px" />
                                        </a>
                                    @else
                                        {{ $job['company'] }}
                                    @endif
                                </h3>
                                <p class="text-sm text-subtle">{{ __("resume.experience.{$key}.location") }}</p>
                            </div>

                            <ul @class(['mt-3 space-y-1.5', 'border-l border-line-strong pl-4' => count($job['roles']) > 1])>
                                @foreach($job['roles'] as $role => $dates)
                                    <li class="flex flex-wrap items-baseline gap-x-3 text-[0.95rem]">
                                        <span @class(['font-medium', 'text-ink' => $loop->first, 'text-muted' => ! $loop->first])>{{ __("resume.experience.{$key}.roles.{$role}") }}</span>
                                        @if(count($job['roles']) > 1)
                                            <span class="font-mono text-xs text-subtle">{{ Period::fromArray($dates)->label() }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <ul class="mt-5 space-y-2.5 text-[0.95rem] leading-relaxed text-muted">
                                @foreach(__("resume.experience.{$key}.highlights") as $index => $highlight)
                                    <li class="copy relative pl-5 text-pretty before:absolute before:top-[0.7em] before:left-0 before:h-px before:w-2.5 before:bg-line-strong">
                                        @copy("resume.experience.{$key}.highlights.{$index}")
                                    </li>
                                @endforeach
                            </ul>

                            <ul class="mt-6 flex flex-wrap gap-1.5" aria-label="{{ __('site.project.stack') }}">
                                @foreach($job['stack'] as $technology)
                                    <x-tag>{{ $technology }}</x-tag>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Open source & writing --}}
        <section id="open-source" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="open-source-title">
            <x-section-heading number="03" :eyebrow="__('site.sections.open_source.eyebrow')" :title="__('site.sections.open_source.title')" id="open-source-title">
                <x-slot:intro>{{ __('site.sections.open_source.intro') }}</x-slot:intro>
            </x-section-heading>

            <ul class="mt-14 grid gap-4 sm:grid-cols-2">
                @foreach(config('resume.packages') as $key => $package)
                    <li class="flex flex-col rounded-2xl border border-line bg-surface p-6 shadow-soft transition duration-300 hover:-translate-y-0.5 hover:shadow-lifted sm:p-7" data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-lg font-semibold tracking-[-0.02em]">{{ $package['name'] }}</h3>
                            <span class="rounded-full bg-accent-soft px-2.5 py-1 font-mono text-[0.6875rem] text-accent">{{ __('site.sections.open_source.creator') }} · {{ $package['year'] }}</span>
                        </div>
                        <p class="mt-3 flex-1 leading-relaxed text-pretty text-muted">{{ __("resume.packages.{$key}.description") }}</p>
                        <div class="mt-6 flex items-center gap-5 text-sm font-medium">
                            <a href="{{ $package['repository'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 transition-colors hover:text-accent">
                                <x-icon name="github" class="size-4" />
                                {{ __('site.sections.open_source.source') }}
                            </a>
                            @if($package['documentation'])
                                <a href="{{ $package['documentation'] }}" target="_blank" rel="noopener" class="group/docs inline-flex items-center gap-1 text-muted transition-colors hover:text-ink">
                                    {{ __('site.sections.open_source.documentation') }}
                                    <x-icon name="arrow-up-right" class="size-3.5 transition-transform duration-300 group-hover/docs:translate-x-px group-hover/docs:-translate-y-px" />
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <a href="{{ config('resume.urls.packages') }}" target="_blank" rel="noopener" class="group mt-6 inline-flex items-center gap-1 text-sm font-medium text-muted transition-colors hover:text-ink" data-reveal>
                {{ __('site.sections.open_source.all_packages') }}
                <x-icon name="arrow-up-right" class="size-3.5 transition-transform duration-300 group-hover:translate-x-px group-hover:-translate-y-px" />
            </a>

            <div class="mt-20 grid gap-x-12 gap-y-8 border-t border-line pt-12 lg:grid-cols-12">
                <div class="lg:col-span-4" data-reveal>
                    <h3 class="font-mono text-xs uppercase tracking-[0.14em] text-subtle">{{ __('site.sections.open_source.writing') }}</h3>
                    <p class="mt-4 leading-relaxed text-pretty text-muted">{{ __('site.sections.open_source.writing_intro') }}</p>
                    <a href="{{ config('resume.urls.blog') }}" target="_blank" rel="noopener" class="group mt-5 inline-flex items-center gap-1 text-sm font-medium transition-colors hover:text-accent">
                        {{ __('site.sections.open_source.all_articles') }}
                        <x-icon name="arrow-up-right" class="size-3.5 transition-transform duration-300 group-hover:translate-x-px group-hover:-translate-y-px" />
                    </a>
                </div>

                <ol class="divide-y divide-line border-y border-line lg:col-span-8">
                    @foreach(config('resume.articles') as $article)
                        <li data-reveal>
                            <a href="{{ $article['url'] }}" target="_blank" rel="noopener" hreflang="en" class="group flex items-baseline justify-between gap-6 py-4">
                                <span lang="en" class="font-medium text-pretty transition-colors group-hover:text-accent">{{ $article['title'] }}</span>
                                <time datetime="{{ $article['date'] }}" class="shrink-0 font-mono text-xs text-subtle tabular-nums">{{ Carbon\CarbonImmutable::parse($article['date'])->locale(app()->getLocale())->translatedFormat('M Y') }}</time>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- Approach --}}
        <section id="approach" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="approach-title">
            <x-section-heading number="04" :eyebrow="__('site.sections.approach.eyebrow')" :title="__('site.sections.approach.title')" id="approach-title" />

            <div class="mt-14 grid gap-px overflow-hidden rounded-2xl border border-line bg-line md:grid-cols-2" data-reveal>
                @foreach(__('resume.principles') as $key => $principle)
                    <div class="bg-canvas p-8 sm:p-10">
                        <p class="font-mono text-xs text-accent">{{ sprintf('%02d', $loop->iteration) }}</p>
                        <h3 class="mt-4 text-xl font-semibold tracking-[-0.02em]">{{ $principle['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-pretty text-muted">{{ $principle['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Toolbox & background --}}
        <section id="toolbox" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="toolbox-title">
            <x-section-heading number="05" :eyebrow="__('site.sections.toolbox.eyebrow')" :title="__('site.sections.toolbox.title')" id="toolbox-title" />

            <div class="mt-14 grid gap-x-12 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach(config('resume.skills') as $group => $skills)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 40 }}ms">
                        <h3 class="text-sm font-semibold">{{ __("resume.skills.{$group}") }}</h3>
                        <ul class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($skills as $skill)
                                <x-tag>{{ $skill }}</x-tag>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-20 grid gap-x-12 gap-y-10 border-t border-line pt-12 md:grid-cols-3">
                <div data-reveal>
                    <h3 class="font-mono text-xs uppercase tracking-[0.14em] text-subtle">{{ __('site.sections.background.education') }}</h3>
                    <ul class="mt-5 space-y-5">
                        @foreach(config('resume.education') as $key => $dates)
                            <li>
                                <p class="font-medium text-pretty">{{ __("resume.education.{$key}.title") }}</p>
                                <p class="mt-1 text-sm text-muted">{{ __("resume.education.{$key}.school") }}</p>
                                <p class="mt-1 font-mono text-xs text-subtle">{{ $dates['start'] }} – {{ $dates['end'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div data-reveal style="--reveal-delay: 60ms">
                    <h3 class="font-mono text-xs uppercase tracking-[0.14em] text-subtle">{{ __('site.sections.background.languages') }}</h3>
                    <dl class="mt-5 space-y-3">
                        @foreach(config('resume.languages') as $language)
                            <div class="flex items-baseline justify-between gap-4 border-b border-dashed border-line pb-3">
                                <dt class="font-medium">{{ __("resume.languages.{$language}.name") }}</dt>
                                <dd class="text-sm text-muted">{{ __("resume.languages.{$language}.level") }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div data-reveal style="--reveal-delay: 120ms">
                    <h3 class="font-mono text-xs uppercase tracking-[0.14em] text-subtle">{{ __('site.sections.background.interests') }}</h3>
                    <p class="mt-5 leading-relaxed text-pretty text-muted">{{ __('resume.interests') }}</p>
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section id="contact" class="scroll-mt-20 border-t border-line py-20 sm:py-28" aria-labelledby="contact-title">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <x-section-heading number="06" :eyebrow="__('site.contact.eyebrow')" :title="__('site.contact.title')" id="contact-title">
                        <x-slot:intro>{{ __('site.contact.intro') }}</x-slot:intro>
                    </x-section-heading>

                    <div class="mt-10" data-reveal>
                        <p class="text-sm text-subtle">{{ __('site.contact.email_label') }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="mailto:{{ config('resume.email') }}" class="text-xl font-medium tracking-[-0.02em] underline decoration-line-strong decoration-1 underline-offset-[0.2em] transition-colors hover:text-accent hover:decoration-current">{{ config('resume.email') }}</a>
                            <button
                                type="button"
                                x-data="{ copied: false }"
                                x-on:click="$copy(@js(config('resume.email')), @js(__('site.contact.copied'))); copied = true; setTimeout(() => copied = false, 1800)"
                                class="grid size-9 place-items-center rounded-lg text-muted transition-colors hover:bg-sunken hover:text-ink"
                                :aria-label="copied ? @js(__('site.contact.copied')) : @js(__('site.contact.copy'))"
                                aria-label="{{ __('site.contact.copy') }}"
                            >
                                <x-icon name="copy" class="size-4" x-show="! copied" />
                                <x-icon name="check" class="size-4 text-accent" x-show="copied" x-cloak />
                            </button>
                        </div>
                    </div>

                    <div class="mt-6" data-reveal>
                        <p class="text-sm text-subtle">{{ __('site.contact.phone_label') }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="{{ Resume::phoneUri() }}" class="text-xl font-medium tracking-[-0.02em] whitespace-nowrap tabular-nums underline decoration-line-strong decoration-1 underline-offset-[0.2em] transition-colors hover:text-accent hover:decoration-current">{{ config('resume.phone') }}</a>
                            <button
                                type="button"
                                x-data="{ copied: false }"
                                x-on:click="$copy(@js(config('resume.phone')), @js(__('site.contact.copied'))); copied = true; setTimeout(() => copied = false, 1800)"
                                class="grid size-9 place-items-center rounded-lg text-muted transition-colors hover:bg-sunken hover:text-ink"
                                :aria-label="copied ? @js(__('site.contact.copied')) : @js(__('site.contact.copy'))"
                                aria-label="{{ __('site.contact.copy') }}"
                            >
                                <x-icon name="copy" class="size-4" x-show="! copied" />
                                <x-icon name="check" class="size-4 text-accent" x-show="copied" x-cloak />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7" data-reveal>
                    <form
                        x-data="contactForm({
                            endpoint: @js(route('contactMessages.store')),
                            siteKey: @js(TurnstileVerifier::enabled() ? config('services.turnstile.site_key') : null),
                            locale: @js(app()->getLocale()),
                            messages: @js(__('site.contact.errors')),
                        })"
                        x-intersect.once.margin.400px="prepare()"
                        x-on:submit.prevent="submit()"
                        action="{{ route('contactMessages.store') }}"
                        method="post"
                        novalidate
                        class="rounded-2xl border border-line bg-surface p-6 shadow-soft sm:p-8"
                    >
                        <div x-show="state === 'sent'" x-cloak class="flex flex-col items-center py-16 text-center" role="status">
                            <span class="grid size-12 place-items-center rounded-full bg-accent-soft text-accent">
                                <x-icon name="check" class="size-6" />
                            </span>
                            <p class="mt-5 max-w-sm text-lg text-pretty" x-text="feedback"></p>
                        </div>

                        <div x-show="state !== 'sent'" class="space-y-5">
                            <input type="hidden" name="locale" value="{{ app()->getLocale() }}">

                            {{-- Honeypot: hidden from people and assistive technology, filled by bots. --}}
                            <div class="absolute -left-[9999px]" aria-hidden="true">
                                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                @foreach(['name' => 'name', 'email' => 'email'] as $field => $autocomplete)
                                    <div>
                                        <label for="contact-{{ $field }}" class="text-sm font-medium">{{ __("site.contact.fields.{$field}") }}</label>
                                        <input
                                            id="contact-{{ $field }}"
                                            name="{{ $field }}"
                                            type="{{ $field === 'email' ? 'email' : 'text' }}"
                                            autocomplete="{{ $autocomplete }}"
                                            required
                                            maxlength="{{ $field === 'email' ? 190 : 120 }}"
                                            :aria-invalid="error('{{ $field }}') ? 'true' : 'false'"
                                            aria-describedby="contact-{{ $field }}-error"
                                            class="mt-2 h-11 w-full rounded-lg border border-line-strong bg-canvas px-3.5 text-[0.95rem] transition-colors outline-none placeholder:text-subtle focus:border-accent focus:ring-4 focus:ring-accent/15 aria-[invalid=true]:border-red-500"
                                        >
                                        <p id="contact-{{ $field }}-error" class="mt-1.5 text-sm text-red-600 dark:text-red-400" x-text="error('{{ $field }}')" x-show="error('{{ $field }}')" x-cloak></p>
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                <label for="contact-company" class="text-sm font-medium">
                                    {{ __('site.contact.fields.company') }}
                                    <span class="font-normal text-subtle">({{ __('site.contact.fields.optional') }})</span>
                                </label>
                                <input id="contact-company" name="company" type="text" autocomplete="organization" maxlength="120" class="mt-2 h-11 w-full rounded-lg border border-line-strong bg-canvas px-3.5 text-[0.95rem] transition-colors outline-none focus:border-accent focus:ring-4 focus:ring-accent/15">
                            </div>

                            <div>
                                <label for="contact-message" class="text-sm font-medium">{{ __('site.contact.fields.message') }}</label>
                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows="5"
                                    required
                                    minlength="10"
                                    maxlength="5000"
                                    placeholder="{{ __('site.contact.fields.message_placeholder') }}"
                                    :aria-invalid="error('message') ? 'true' : 'false'"
                                    aria-describedby="contact-message-error"
                                    class="mt-2 block w-full resize-y rounded-lg border border-line-strong bg-canvas px-3.5 py-3 text-[0.95rem] leading-relaxed transition-colors outline-none placeholder:text-subtle focus:border-accent focus:ring-4 focus:ring-accent/15 aria-[invalid=true]:border-red-500"
                                ></textarea>
                                <p id="contact-message-error" class="mt-1.5 text-sm text-red-600 dark:text-red-400" x-text="error('message')" x-show="error('message')" x-cloak></p>
                            </div>

                            <div x-ref="turnstile" class="empty:hidden"></div>
                            <p class="text-sm text-red-600 dark:text-red-400" x-text="error('cf-turnstile-response')" x-show="error('cf-turnstile-response')" x-cloak></p>

                            <div class="flex flex-wrap items-center gap-4 pt-1">
                                <button
                                    type="submit"
                                    :disabled="state === 'sending'"
                                    class="group inline-flex h-11 items-center gap-2 rounded-full bg-ink pr-4 pl-5 text-sm font-medium text-canvas transition-all hover:shadow-lifted active:scale-[0.98] disabled:cursor-wait disabled:opacity-70"
                                >
                                    <span x-text="state === 'sending' ? @js(__('site.contact.sending')) : @js(__('site.contact.send'))">{{ __('site.contact.send') }}</span>
                                    <x-icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                                </button>
                                <p class="text-sm text-red-600 dark:text-red-400" x-text="feedback" x-show="state === 'failed' && feedback" role="alert" x-cloak></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-layout>
