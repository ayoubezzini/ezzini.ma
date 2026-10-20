<?php

use App\Resume\Project;

it('redirects the root to the visitor’s preferred language', function (string $acceptLanguage, string $locale) {
    $this->get('/', ['Accept-Language' => $acceptLanguage])
        ->assertRedirect("/{$locale}")
        ->assertHeader('Vary', 'Accept-Language');
})->with([
    'french' => ['fr-FR,fr;q=0.9,en;q=0.8', 'fr'],
    'english' => ['en-US,en;q=0.9', 'en'],
    'anything else' => ['de-DE,de;q=0.9', 'en'],
]);

it('renders the home page in every language', function (string $locale) {
    $this->get("/{$locale}")
        ->assertOk()
        ->assertSee('<html lang="'.$locale.'"', false)
        ->assertSee(__('resume.role', locale: $locale))
        ->assertSee('ayoub@ezzini.ma')
        ->assertSee('hreflang="'.($locale === 'en' ? 'fr' : 'en').'"', false);
})->with(['en', 'fr']);

it('renders every project case study in every language', function (string $locale) {
    Project::all()->each(fn (Project $project) => $this->get("/{$locale}/projects/{$project->slug}")
        ->assertOk()
        ->assertSee($project->name)
        ->assertSee(__("resume.projects.{$project->slug}.tagline", locale: $locale)));
})->with(['en', 'fr']);

it('renders the printable résumé in every language', function (string $locale) {
    $this->get("/{$locale}/cv")
        ->assertOk()
        ->assertSee('size: A4', false)
        ->assertSee(config('app.url'));
})->with(['en', 'fr']);

it('links the Maline Immobilier platform from the Wave experience', function () {
    $this->get('/en')->assertSee('href="https://maline-immobilier.fr"', false);
});

it('serves pages without cookies, so they can be cached at the edge', function () {
    $response = $this->get('/en');

    expect($response->headers->getCookies())->toBeEmpty()
        ->and($response->headers->get('Cache-Control'))->toContain('public', 's-maxage=604800');
});

it('returns a localized 404 for unknown projects and languages', function () {
    $this->get('/fr/projects/unknown')->assertNotFound()->assertSee('Cette page n’existe pas.');
    $this->get('/de')->assertNotFound()->assertSee('This page doesn’t exist.');
});

it('lists every localized page in the sitemap', function () {
    $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');

    expect(substr_count($response->getContent(), '<loc>'))->toBe((2 + Project::all()->count()) * 2);
});

it('shows the phone number with a tel link on the site and the résumé', function (string $page) {
    $this->get($page)
        ->assertSee('+212 623-357087')
        ->assertSee('href="tel:+212623357087"', false);
})->with(['/en', '/fr', '/en/cv', '/fr/cv']);

it('links every open-source package and every article', function (string $page) {
    $response = $this->get($page);

    foreach (config('resume.packages') as $package) {
        $response->assertSee('href="'.$package['repository'].'"', false);
    }

    foreach (config('resume.articles') as $article) {
        $response->assertSee('href="'.$article['url'].'"', false);
    }
})->with(['/en', '/fr', '/en/cv', '/fr/cv']);

it('versions document URLs with the deployed commit so caches never serve an old CV', function () {
    config(['app.revision' => 'dd01c27a9785e8ea1d3d869c7ed270ca4fedb1b4']);

    expect(App\Resume\Resume::documentUrl('documents/ayoub-ezzini-cv-en.pdf'))
        ->toEndWith('/documents/ayoub-ezzini-cv-en.pdf?v=dd01c27');
});
