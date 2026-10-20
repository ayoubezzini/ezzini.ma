<?php

namespace App\Resume;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Collection;

readonly class Project implements UrlRoutable
{
    /** @param array<int, string> $stack */
    public function __construct(
        public string $slug,
        public string $name,
        public string $url,
        public int $year,
        public string $accent,
        public array $stack,
    ) {}

    /** @return Collection<int, Project> */
    public static function all(): Collection
    {
        return collect(config('resume.projects'))
            ->map(fn (array $project, string $slug) => new self(
                slug: $slug,
                name: $project['name'],
                url: $project['url'],
                year: $project['year'],
                accent: $project['accent'],
                stack: $project['stack'],
            ))
            ->values();
    }

    public static function find(string $slug): ?self
    {
        return self::all()->firstWhere('slug', $slug);
    }

    public function image(): string
    {
        $locale = app()->getLocale();

        return "images/projects/{$locale}/{$this->slug}.webp";
    }

    public function host(): string
    {
        return parse_url($this->url, PHP_URL_HOST);
    }

    public function next(): self
    {
        $projects = self::all();

        $position = $projects->search(fn (Project $project) => $project->slug === $this->slug);

        return $projects->get(($position + 1) % $projects->count());
    }

    public function copy(string $key): mixed
    {
        return __("resume.projects.{$this->slug}.{$key}");
    }

    public function getRouteKey(): string
    {
        return $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return self::find($value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): ?self
    {
        return null;
    }
}
