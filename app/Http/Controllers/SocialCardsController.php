<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SocialCardsController extends Controller
{
    /** The page screenshotted into the Open Graph image by `php artisan render-documents`. */
    public function show(): Response
    {
        return response()
            ->view('socialCard')
            ->header('X-Robots-Tag', 'noindex');
    }
}
