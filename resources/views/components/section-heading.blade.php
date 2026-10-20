@props(['eyebrow', 'title', 'number' => null, 'id' => null])

<div {{ $attributes->merge(['class' => 'max-w-2xl']) }} data-reveal>
    <p class="flex items-center gap-3 font-mono text-xs uppercase tracking-[0.14em] text-subtle">
        @if($number)
            <span class="text-accent">{{ $number }}</span>
            <span class="h-px w-6 bg-line-strong" aria-hidden="true"></span>
        @endif
        {{ $eyebrow }}
    </p>
    <h2 @if($id) id="{{ $id }}" @endif class="mt-4 text-3xl font-semibold tracking-[-0.03em] text-balance sm:text-4xl">{{ $title }}</h2>

    @isset($intro)
        <p class="mt-4 text-lg leading-relaxed text-pretty text-muted">{{ $intro }}</p>
    @endisset
</div>
