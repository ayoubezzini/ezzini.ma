@php($next = $project->next())

<x-layout :title="$project->name.' · '.rtrim($project->copy('tagline'), '.').' · '.config('resume.name')" :description="$project->copy('summary')">
    <article class="mx-auto max-w-5xl px-5 sm:px-8" aria-labelledby="project-title">
        <header class="pt-12 pb-12 sm:pt-16">
            <a href="{{ route('home') }}#work" class="group inline-flex items-center gap-1.5 text-sm text-muted transition-colors hover:text-ink">
                <x-icon name="arrow-left" class="size-4 transition-transform duration-300 group-hover:-translate-x-0.5" />
                {{ __('site.project.back') }}
            </a>

            <p class="mt-12 flex items-center gap-2.5 font-mono text-xs text-subtle" data-reveal>
                <span class="size-2 rounded-full" style="background-color: {{ $project->accent }}" aria-hidden="true"></span>
                {{ $project->host() }}
            </p>
            <h1 id="project-title" class="mt-4 text-5xl font-semibold tracking-[-0.045em] sm:text-7xl" data-reveal style="--reveal-delay: 60ms">{{ $project->name }}</h1>
            <p class="mt-5 max-w-2xl text-xl leading-relaxed text-balance text-muted sm:text-2xl sm:leading-snug" data-reveal style="--reveal-delay: 120ms">{{ $project->copy('tagline') }}</p>

            <dl class="mt-12 grid grid-cols-2 gap-6 border-t border-line pt-6 text-sm sm:grid-cols-[2fr_1fr_1fr]" data-reveal style="--reveal-delay: 180ms">
                <div class="col-span-2 sm:col-span-1">
                    <dt class="font-mono text-xs text-subtle">{{ __('site.project.role') }}</dt>
                    <dd class="mt-1.5 font-medium text-pretty">{{ __('site.project.role_value') }}</dd>
                </div>
                <div>
                    <dt class="font-mono text-xs text-subtle">{{ __('site.project.year') }}</dt>
                    <dd class="mt-1.5 font-medium tabular-nums">{{ $project->year }}</dd>
                </div>
                <div>
                    <dt class="font-mono text-xs text-subtle">{{ __('site.project.website') }}</dt>
                    <dd class="mt-1.5">
                        <a href="{{ $project->url }}" target="_blank" rel="noopener" class="group inline-flex items-center gap-1 font-medium transition-colors hover:text-accent">
                            {{ $project->host() }}
                            <x-icon name="arrow-up-right" class="size-3.5 transition-transform duration-300 group-hover:translate-x-px group-hover:-translate-y-px" />
                        </a>
                    </dd>
                </div>
            </dl>
        </header>

        <x-browser-frame :project="$project" eager class="shadow-lifted" style="view-transition-name: shot-{{ $project->slug }}" />

        <div class="mt-20 space-y-20 sm:mt-28 sm:space-y-24">
            <section class="grid gap-6 lg:grid-cols-12 lg:gap-12" aria-labelledby="problem-title">
                <h2 id="problem-title" class="font-mono text-xs uppercase tracking-[0.14em] text-subtle lg:col-span-4 lg:pt-2" data-reveal>{{ __('site.project.problem') }}</h2>
                <p class="text-lg leading-relaxed text-pretty sm:text-xl sm:leading-relaxed lg:col-span-8" data-reveal>{{ $project->copy('summary') }}</p>
            </section>

            <section class="grid gap-6 lg:grid-cols-12 lg:gap-12" aria-labelledby="built-title">
                <h2 id="built-title" class="font-mono text-xs uppercase tracking-[0.14em] text-subtle lg:col-span-4 lg:pt-6" data-reveal>{{ __('site.project.built') }}</h2>
                <ol class="divide-y divide-line border-y border-line lg:col-span-8">
                    @foreach($project->copy('built') as $index => $item)
                        <li class="copy flex gap-5 py-5 leading-relaxed text-pretty text-muted" data-reveal>
                            <span class="pt-0.5 font-mono text-xs text-subtle tabular-nums">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <span>@copy("resume.projects.{$project->slug}.built.{$index}")</span>
                        </li>
                    @endforeach
                </ol>
            </section>

            <section class="grid gap-6 lg:grid-cols-12 lg:gap-12" aria-labelledby="details-title">
                <h2 id="details-title" class="font-mono text-xs uppercase tracking-[0.14em] text-subtle lg:col-span-4 lg:pt-2" data-reveal>{{ __('site.project.details') }}</h2>
                <ul class="grid gap-4 sm:grid-cols-2 lg:col-span-8">
                    @foreach($project->copy('details') as $index => $detail)
                        <li class="copy rounded-xl border border-line bg-surface p-5 leading-relaxed text-pretty text-muted" data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms">
                            <span class="mb-3 grid size-7 place-items-center rounded-full bg-accent-soft text-accent">
                                <x-icon name="check" class="size-3.5" />
                            </span>
                            @copy("resume.projects.{$project->slug}.details.{$index}")
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="grid gap-6 lg:grid-cols-12 lg:gap-12" aria-labelledby="stack-title">
                <h2 id="stack-title" class="font-mono text-xs uppercase tracking-[0.14em] text-subtle lg:col-span-4 lg:pt-2" data-reveal>{{ __('site.project.stack') }}</h2>
                <ul class="flex flex-wrap gap-2 lg:col-span-8" data-reveal>
                    @foreach($project->stack as $technology)
                        <x-tag class="px-2.5 py-1.5 text-xs">{{ $technology }}</x-tag>
                    @endforeach
                </ul>
            </section>
        </div>

        <a href="{{ route('projects.show', $next) }}" class="group mt-28 flex items-center justify-between gap-6 rounded-2xl border border-line bg-surface p-6 shadow-soft transition duration-300 hover:-translate-y-0.5 hover:shadow-lifted sm:p-8" data-reveal>
            <span>
                <span class="font-mono text-xs uppercase tracking-[0.14em] text-subtle">{{ __('site.project.next') }}</span>
                <span class="mt-2 flex items-center gap-2.5 text-2xl font-semibold tracking-[-0.03em] sm:text-3xl">
                    <span class="size-2.5 rounded-full" style="background-color: {{ $next->accent }}" aria-hidden="true"></span>
                    {{ $next->name }}
                </span>
                <span class="mt-1.5 block text-muted">{{ $next->copy('tagline') }}</span>
            </span>
            <span class="grid size-12 shrink-0 place-items-center rounded-full border border-line-strong transition-colors duration-300 group-hover:border-ink group-hover:bg-ink group-hover:text-canvas">
                <x-icon name="arrow-right" class="size-5" />
            </span>
        </a>
    </article>
</x-layout>
