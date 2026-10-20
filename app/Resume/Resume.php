<?php

namespace App\Resume;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\File;

class Resume
{
    public static function yearsOfExperience(): int
    {
        return (int) round(CarbonImmutable::parse(config('resume.career_started_at'))->diffInYears());
    }

    public static function phoneUri(): string
    {
        $digits = preg_replace('/[^\d+]/', '', config('resume.phone'));

        return "tel:{$digits}";
    }

    public static function pdfPath(string $locale): string
    {
        return "documents/ayoub-ezzini-cv-{$locale}.pdf";
    }

    public static function socialCardPath(string $locale): string
    {
        return "documents/social-card-{$locale}.png";
    }

    /** The PDF only exists once `php artisan render-documents` ran (always the case in production). */
    public static function downloadUrl(string $locale): string
    {
        return File::exists(public_path(self::pdfPath($locale))) ?
            asset(self::pdfPath($locale)) :
            route('resume', ['locale' => $locale]);
    }
}
