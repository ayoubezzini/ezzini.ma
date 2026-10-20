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
