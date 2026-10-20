<?php

/** @return array<int, string> */
function translationKeys(array $translations, string $prefix = ''): array
{
    return collect($translations)
        ->flatMap(fn (mixed $value, string|int $key) => is_array($value)
            ? translationKeys($value, "{$prefix}{$key}.")
            : ["{$prefix}{$key}"])
        ->all();
}

it('has the same keys in French and English', function (string $file) {
    $english = translationKeys(require lang_path("en/{$file}.php"));
    $french = translationKeys(require lang_path("fr/{$file}.php"));

    expect(array_diff($english, $french))->toBeEmpty('Missing in French')
        ->and(array_diff($french, $english))->toBeEmpty('Missing in English');
})->with(['resume', 'site']);

it('has copy for every project, job, skill group, school and language in the config', function (string $locale) {
    $copy = require lang_path("{$locale}/resume.php");

    foreach (['projects', 'experience', 'packages', 'skills', 'education'] as $section) {
        expect(array_keys($copy[$section]))->toEqualCanonicalizing(array_keys(config("resume.{$section}")));
    }

    foreach (config('resume.experience') as $key => $job) {
        expect(array_keys($copy['experience'][$key]['roles']))->toEqualCanonicalizing(array_keys($job['roles']));
    }

    expect(array_keys($copy['languages']))->toEqualCanonicalizing(config('resume.languages'));
})->with(['en', 'fr']);

it('uses French typography: no-break spaces before double punctuation', function () {
    $french = collect(['resume', 'site'])
        ->flatMap(fn (string $file) => array_values(Illuminate\Support\Arr::dot(require lang_path("fr/{$file}.php"))))
        ->implode("\n");

    expect($french)->not->toMatch('/[^\x{202F}\x{00A0}][?!;]/u')
        ->and($french)->not->toMatch('/[^\x{00A0}]:(?![a-z])/u');
});
