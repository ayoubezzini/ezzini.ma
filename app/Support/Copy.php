<?php

namespace App\Support;

use App\Resume\Resume;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/** Renders translated copy written with inline Markdown (**bold**, [links](:maline)), placeholders included. */
class Copy
{
    public static function render(string $keyOrText): HtmlString
    {
        $replace = [
            ...config('resume.urls'),
            'repository' => config('resume.repository'),
            'years' => Resume::yearsOfExperience(),
        ];

        $translated = __($keyOrText, $replace);

        $markdown = Str::inlineMarkdown($translated, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        return new HtmlString(trim($markdown));
    }
}
