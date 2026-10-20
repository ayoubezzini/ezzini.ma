<?php

namespace App\Http\Requests;

use App\Rules\PassesTurnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactMessageRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // Sent by the form so that validation messages match the page the visitor is reading.
        app()->setLocale(in_array($this->input('locale'), config('app.locales'), true) ? $this->input('locale') : 'en');
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'company' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'locale' => ['required', Rule::in(config('app.locales'))],
            // Honeypot: invisible to people, irresistible to bots.
            'website' => ['prohibited'],
            'cf-turnstile-response' => [new PassesTurnstile('contact', $this)],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'name' => __('site.contact.fields.name'),
            'email' => __('site.contact.fields.email'),
            'company' => __('site.contact.fields.company'),
            'message' => __('site.contact.fields.message'),
        ];
    }
}
