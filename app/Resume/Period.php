<?php

namespace App\Resume;

use Carbon\CarbonImmutable;

readonly class Period
{
    public function __construct(
        public CarbonImmutable $start,
        public ?CarbonImmutable $end,
    ) {}

    /** @param array{start: string, end: ?string} $dates */
    public static function fromArray(array $dates): self
    {
        return new self(
            CarbonImmutable::parse($dates['start']),
            $dates['end'] ? CarbonImmutable::parse($dates['end']) : null,
        );
    }

    /** @param array<string, array{start: string, end: ?string}> $roles */
    public static function covering(array $roles): self
    {
        $periods = collect($roles)->map(fn (array $dates) => self::fromArray($dates));

        return new self(
            $periods->min('start'),
            $periods->contains(fn (Period $period) => $period->isCurrent()) ? null : $periods->max('end'),
        );
    }

    public function isCurrent(): bool
    {
        return $this->end === null;
    }

    public function label(): string
    {
        $end = $this->isCurrent() ?
            __('site.experience.present') :
            $this->month($this->end);

        return "{$this->month($this->start)} – {$end}";
    }

    /** Counted like LinkedIn does: both the first and the last month are included. */
    public function duration(): string
    {
        $end = $this->end ?? CarbonImmutable::now()->startOfMonth();

        $months = (int) $this->start->diffInMonths($end) + 1;

        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;

        return collect([
            $years ? trans_choice('site.experience.years', $years) : null,
            $remainingMonths ? trans_choice('site.experience.months', $remainingMonths) : null,
        ])->filter()->implode(' ');
    }

    private function month(CarbonImmutable $date): string
    {
        return $date->locale(app()->getLocale())->translatedFormat('M Y');
    }
}
