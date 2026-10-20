<?php

namespace App\Console\Commands;

use App\Resume\Resume;
use Illuminate\Console\Command;
use Illuminate\Process\InvokedProcess;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use RuntimeException;
use Throwable;

class RenderDocumentsCommand extends Command
{
    protected $signature = 'render-documents {--port=8123}';

    protected $description = 'Render the CV PDFs and the social cards from the real pages with headless Chromium';

    public function handle(): int
    {
        $baseUrl = "http://127.0.0.1:{$this->option('port')}";

        File::ensureDirectoryExists(public_path('documents'));

        $this->info("Starting a local server on {$baseUrl}...");

        $server = Process::forever()
            ->path(public_path())
            ->start([PHP_BINARY, '-S', "127.0.0.1:{$this->option('port')}", base_path('vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php')]);

        try {
            $this->waitFor($baseUrl, $server);

            foreach (config('app.locales') as $locale) {
                $this->info("Rendering the {$locale} CV...");
                $this->chromium(
                    '--no-pdf-header-footer',
                    '--print-to-pdf='.public_path(Resume::pdfPath($locale)),
                    "{$baseUrl}/{$locale}/cv",
                );

                $this->info("Rendering the {$locale} social card...");
                $this->chromium(
                    '--hide-scrollbars',
                    '--window-size=1200,630',
                    '--screenshot='.public_path(Resume::socialCardPath($locale)),
                    "{$baseUrl}/{$locale}/social-card",
                );
            }
        } finally {
            $server->stop();
        }

        $documentCount = count(config('app.locales')) * 2;

        $this->comment("Rendered {$documentCount} documents in public/documents.");

        return self::SUCCESS;
    }

    private function waitFor(string $baseUrl, InvokedProcess $server): void
    {
        foreach (range(1, 50) as $attempt) {
            try {
                if (Http::timeout(1)->get("{$baseUrl}/up")->successful()) {
                    return;
                }
            } catch (Throwable) {
            }

            usleep(100_000);
        }

        throw new RuntimeException("The local server never answered: {$server->errorOutput()}");
    }

    private function chromium(string ...$arguments): void
    {
        Process::timeout(60)
            ->run([
                config('services.chromium.path'),
                '--headless',
                '--no-sandbox',
                '--disable-gpu',
                '--disable-dev-shm-usage',
                '--force-device-scale-factor=2',
                // Lets web fonts and images finish loading before the capture.
                '--virtual-time-budget=10000',
                ...$arguments,
            ])
            ->throw();
    }
}
