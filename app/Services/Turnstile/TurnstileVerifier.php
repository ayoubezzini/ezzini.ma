<?php

namespace App\Services\Turnstile;

use Illuminate\Support\Facades\Http;
use Throwable;

class TurnstileVerifier
{
    public const string ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public static function enabled(): bool
    {
        return filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key'));
    }

    public function verify(?string $token, string $action, ?string $ip, string $hostname): bool
    {
        if (! self::enabled()) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(10)->post(self::ENDPOINT, [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }

        if ($response->json('success') !== true) {
            return false;
        }

        if ($response->json('action') !== $action) {
            return false;
        }

        return ! app()->isProduction() || $response->json('hostname') === $hostname;
    }
}
