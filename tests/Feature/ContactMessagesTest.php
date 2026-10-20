<?php

use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

beforeEach(fn () => Mail::fake());

function contactMessage(array $overrides = []): array
{
    return [
        'name' => 'Sara Bennani',
        'email' => 'sara@example.com',
        'company' => 'Acme',
        'message' => 'We are hiring a senior Laravel engineer.',
        'locale' => 'en',
        ...$overrides,
    ];
}

it('sends the message to Ayoub with the visitor as reply-to', function () {
    $this->postJson('/contact-messages', contactMessage())
        ->assertOk()
        ->assertJson(['message' => __('site.contact.sent')]);

    Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo('ayoub@ezzini.ma')
        && $mail->hasReplyTo('sara@example.com')
        && $mail->messageBody === 'We are hiring a senior Laravel engineer.');
});

it('answers validation errors in the visitor’s language', function () {
    $this->postJson('/contact-messages', contactMessage(['email' => 'not-an-email', 'locale' => 'fr']))
        ->assertUnprocessable()
        ->assertJsonPath('errors.email.0', 'Le champ E-mail doit être une adresse e-mail valide.');

    Mail::assertNothingSent();
});

it('silently rejects bots that fill the honeypot', function () {
    $this->postJson('/contact-messages', contactMessage(['website' => 'https://spam.example']))
        ->assertUnprocessable();

    Mail::assertNothingSent();
});

it('rejects messages that fail the Turnstile check', function () {
    config(['services.turnstile.site_key' => 'site', 'services.turnstile.secret_key' => 'secret']);

    Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

    $this->postJson('/contact-messages', contactMessage(['cf-turnstile-response' => 'forged']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cf-turnstile-response');

    Mail::assertNothingSent();
});

it('accepts messages that pass the Turnstile check for the contact action', function () {
    config(['services.turnstile.site_key' => 'site', 'services.turnstile.secret_key' => 'secret']);

    Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true, 'action' => 'contact', 'hostname' => 'localhost'])]);

    $this->postJson('/contact-messages', contactMessage(['cf-turnstile-response' => 'valid']))->assertOk();

    Mail::assertSentCount(1);
});

it('limits how many messages a visitor can send', function () {
    foreach (range(1, 5) as $attempt) {
        $this->postJson('/contact-messages', contactMessage())->assertOk();
    }

    $this->postJson('/contact-messages', contactMessage())->assertTooManyRequests();
});
