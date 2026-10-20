<?php

use App\Http\Controllers\ContactMessagesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreferredLocaleController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialCardsController;
use App\Http\Middleware\SetLocaleFromUrl;
use Illuminate\Support\Facades\Route;

Route::get('/', [PreferredLocaleController::class, 'show'])->name('preferredLocale');

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::post('contact-messages', [ContactMessagesController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contactMessages.store');

Route::prefix('{locale}')
    ->whereIn('locale', config('app.locales'))
    ->middleware([SetLocaleFromUrl::class, 'cache.headers:public;max_age=300;s_maxage=604800;etag'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('projects/{project}', [ProjectsController::class, 'show'])->name('projects.show');
        Route::get('cv', [ResumeController::class, 'show'])->name('resume');
        Route::get('social-card', [SocialCardsController::class, 'show'])->name('socialCard');
    });
