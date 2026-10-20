@props(['project', 'eager' => false])

<figure {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-line bg-surface shadow-soft']) }}>
    <div class="flex h-9 items-center gap-3 border-b border-line bg-sunken/70 px-3.5" aria-hidden="true">
        <span class="flex gap-1.5">
            <span class="size-2.5 rounded-full bg-line-strong"></span>
            <span class="size-2.5 rounded-full bg-line-strong"></span>
            <span class="size-2.5 rounded-full bg-line-strong"></span>
        </span>
        <span class="mx-auto flex items-center gap-1.5 rounded-md bg-surface px-3 py-1 font-mono text-[0.6875rem] text-subtle ring-1 ring-line">
            <x-icon name="lock" class="size-3" />
            {{ $project->host() }}
        </span>
        <span class="w-[2.625rem]"></span>
    </div>

    <img
        src="{{ asset($project->image()) }}"
        alt="{{ $project->copy('image_alt') }}"
        width="1920"
        height="1200"
        @if($eager) fetchpriority="high" @else loading="lazy" @endif
        decoding="async"
        class="aspect-[16/10] w-full bg-sunken object-cover object-top"
    >
</figure>
