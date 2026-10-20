# ezzini.ma

Ayoub Ezzini's résumé and portfolio (open source). Laravel 13, Blade, Alpine.js, Tailwind CSS 4. No database, no admin panel: the content is code.

## Rules

- Content: facts in `config/resume.php`, every sentence in `lang/{en,fr}/resume.php` (interface text in `lang/{en,fr}/site.php`) under identical keys. Never write copy in a view. `tests/Unit/TranslationsTest.php` guards key parity.
- Copy is rendered with `@copy('key')` (`App\Support\Copy`): inline Markdown, placeholders `:years` and `resume.urls` keys. Translation files are trusted input, but HTML in them is escaped.
- Never invent facts about Ayoub, his employers or his products. Anything new comes from him, his résumé, LinkedIn or the product repositories (`../khlass.ma`, `../estock`, `../nuvo`).
- French typography: narrow no-break space (U+202F) before `? ! ;` and `%`, no-break space (U+00A0) before `:` and inside « », thousands separated by U+202F. Curly apostrophes (’) in both languages.
- Pages must stay cookie-less and session-less (see `bootstrap/app.php`), so Cloudflare can cache them. Dynamic behavior goes through JSON endpoints with Turnstile and a rate limit.
- The CV page (`resources/views/resume.blade.php`) is printed to PDF by `php artisan render-documents`: use canonical URLs from `config('app.url')` there, not `route()`, and avoid `position: relative` around text (positioned elements are extracted after the normal flow by ATS parsers).
- Every visible change must work in both themes, both languages, on a phone, and with reduced motion.

## Docker

No PHP, Composer or Node on the host: `docker compose exec app ...` (php-fpm + nginx on :8084, Chromium included), `vite` service on :5176, Mailpit on :8027. Tests: `docker compose exec app ./vendor/bin/pest`.

## Deployment

Push to `main` → `.github/workflows/deploy.yml` tests, triggers Coolify (`compose.production.yaml`, `Dockerfile` target `production`), waits, purges Cloudflare, checks the live commit.
