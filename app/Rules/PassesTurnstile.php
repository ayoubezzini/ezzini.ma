<?php

namespace App\Rules;

use App\Services\Turnstile\TurnstileVerifier;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class PassesTurnstile implements ValidationRule
{
    public function __construct(
        private string $action,
        private Request $request,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $passes = app(TurnstileVerifier::class)->verify(
            token: is_string($value) ? $value : null,
            action: $this->action,
            ip: $this->request->ip(),
            hostname: $this->request->getHost(),
        );

        if (! $passes) {
            $fail(__('site.contact.errors.turnstile'));
        }
    }
}
