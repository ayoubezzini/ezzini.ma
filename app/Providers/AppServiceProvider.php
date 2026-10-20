<?php

namespace App\Providers;

use App\Resume\Project;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::bind('project', fn (string $slug) => Project::find($slug) ?? abort(404));

        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perHour(5)->by($this->visitorIp($request)),
            Limit::perDay(40),
        ]);

        Blade::directive('copy', fn (string $expression) => "<?php echo \App\Support\Copy::render({$expression}); ?>");
    }

    // Behind Cloudflare, X-Forwarded-For can be forged by the client; CF-Connecting-IP cannot.
    private function visitorIp(Request $request): string
    {
        return $request->header('CF-Connecting-IP') ?? $request->ip();
    }
}
