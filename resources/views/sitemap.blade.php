{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach($pages as [$name, $parameters])
@foreach(config('app.locales') as $locale)
    <url>
        <loc>{{ route($name, [...$parameters, 'locale' => $locale]) }}</loc>
@foreach(config('app.locales') as $alternateLocale)
        <xhtml:link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ route($name, [...$parameters, 'locale' => $alternateLocale]) }}"/>
@endforeach
    </url>
@endforeach
@endforeach
</urlset>
