# ezzini.ma

My résumé and portfolio, in French and English: [ezzini.ma](https://ezzini.ma).

It's open source because how I build things says more than a list of skills. Read the code, open an issue if something bothers you: I probably care about it too.

## What's inside

- **Laravel 13, Blade and Alpine.js.** No SPA, no CMS, no database.
- **Content as code.** Facts live in [`config/resume.php`](config/resume.php), sentences in [`lang/en`](lang/en) and [`lang/fr`](lang/fr) under the same keys. A test fails if a key exists in one language and not the other, or if French punctuation is missing its no-break space.
- **One source, three outputs.** The website, the printable page (`/en/cv`) and the PDF résumé are the same Blade data. The PDFs and the social cards are rendered from the real pages by headless Chromium during the Docker build (`php artisan render-documents`), so they can never drift from the site.
- **Cacheable by design.** Pages set no cookies and start no session, so Cloudflare can serve them from the edge. The only dynamic route is the contact form (JSON, Cloudflare Turnstile, honeypot, rate limit).
- **Details.** Light and dark themes without a flash, a ⌘K command menu, self-hosted fonts with metric-matched fallbacks (no layout shift), cross-document view transitions, reduced-motion support, French typography, screenshots in the page's language, `hreflang`, a sitemap and JSON-LD. Press <kbd>⇧</kbd> <kbd>G</kbd> to see the layout grid.

## Local development

Everything runs in Docker; no PHP, Composer or Node on the host.

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

| Service | URL |
|---|---|
| Site | http://localhost:8084 |
| Vite | http://localhost:5176 |
| Mailpit | http://localhost:8027 |

```bash
docker compose exec app ./vendor/bin/pest              # tests
docker compose run --rm vite npm run build              # production assets
docker compose exec app php artisan render-documents    # PDFs + social cards (stop the vite service first)
```

## Editing the content

| To change | Edit |
|---|---|
| A job, a project, a skill, dates, links | `config/resume.php` |
| Any sentence | `lang/en/resume.php` and `lang/fr/resume.php` |
| Interface text | `lang/en/site.php` and `lang/fr/site.php` |
| Project screenshots | `public/images/projects/{en,fr}/{project}.webp` (1920×1200) |

Copy supports inline Markdown (`**bold**`, `` `code` ``, `[links](:maline)`) and the placeholders `:years` and any key of `resume.urls`.

## Deployment

A push to `main` is the whole release process:

1. GitHub Actions runs the tests and builds the assets.
2. It asks Coolify (on a Hetzner server) to deploy. Coolify builds the `production` target of the [`Dockerfile`](Dockerfile), which renders the documents, and swaps containers once `/up` answers.
3. The workflow waits for the deployment, purges Cloudflare's cache, then checks that the live page mentions the new commit.

### One-time setup

**Coolify**: new application from this repository, build pack *Docker Compose*, file `compose.production.yaml`, domain `https://ezzini.ma`. Turn **off** automatic deployment on push (the workflow deploys once the tests pass) and turn **on** *Include Source Commit in Build* (the footer shows it). Variables: `APP_KEY`, `MAIL_PASSWORD` (a Resend API key), `TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`.

**Cloudflare**: proxied DNS record for `ezzini.ma` pointing to the server, SSL mode *Full (strict)*, a cache rule *Eligible for cache* on `ezzini.ma/*` (HTML isn't cached by default; the app's `Cache-Control` headers decide the rest), a Turnstile widget for `ezzini.ma`, and an API token limited to *Zone · Cache Purge* on this zone. In Resend, verify `ezzini.ma` so the site can send from `site@ezzini.ma`.

**GitHub secrets** (environment `production`): `COOLIFY_URL`, `COOLIFY_TOKEN`, `COOLIFY_APPLICATION_UUID`, `CLOUDFLARE_API_TOKEN`, `CLOUDFLARE_ZONE_ID`.

## License

The code is [MIT](LICENSE). The content (texts, screenshots, the résumé itself) is mine: please don't reuse it as your own.
