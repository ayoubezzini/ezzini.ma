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

No platform, no containers in production: a Hetzner server running nginx and PHP-FPM (the same one that hosts my other products), and one script.

A push to `main` is the whole release process:

1. GitHub Actions runs the tests and builds the assets.
2. It connects over SSH with a key that can do exactly one thing: run [`deploy/deploy.sh`](deploy/deploy.sh) (a forced command in `authorized_keys`).
3. The script builds the commit in a fresh `releases/<date>-<sha>` folder (Composer, assets, then the CV PDFs and social cards with headless Chrome), caches the config, and switches the `current` symlink. Nginx resolves it with `$realpath_root`, so the switch is atomic: no maintenance mode, no half-deployed request.
4. If `/up` doesn't answer, it switches straight back to the previous release. The last five releases are kept.
5. The workflow purges Cloudflare's cache, then checks that the live page mentions the new commit (it's in the footer).

On the server:

```
/var/www/ezzini.ma/
├── current -> releases/20261020-153000-a1b2c3d
├── releases/               last five builds
├── repository.git/         bare clone, fetched on each deploy
├── storage/                shared between releases (logs, deploy.log, view cache)
└── .env                    root:www-data 640
```

```bash
deploy-ezzini               # deploy the latest commit of main by hand
deploy-ezzini --rollback    # back to the previous release, instantly
tail -f /var/www/ezzini.ma/storage/logs/deploy.log
```

### One-time setup

**Server**: copy `deploy/` over and run [`deploy/provision.sh`](deploy/provision.sh) as root. It creates the folders, the bare clone, a `.env` with a fresh `APP_KEY`, the PHP-FPM pool, the nginx site and the `deploy-ezzini` command. It's idempotent: run it again after changing anything in `deploy/`.

```bash
scp -r deploy root@<server>:/tmp/ezzini-deploy
ssh root@<server> bash /tmp/ezzini-deploy/provision.sh --certificate --authorize-key "$(cat github-actions.pub)"
```

Then fill in `MAIL_USERNAME`, `MAIL_PASSWORD`, `TURNSTILE_SITE_KEY` and `TURNSTILE_SECRET_KEY` in `/var/www/ezzini.ma/.env`.

**GitHub**: add the server's repository key as a read-only deploy key. Then add these secrets to the `production` environment:

| Secret | Value |
|---|---|
| `DEPLOY_HOST` | The server's IP. Kept secret so the origin stays hidden behind Cloudflare. |
| `DEPLOY_SSH_KEY` | Private half of the key authorized with `--authorize-key` |
| `DEPLOY_KNOWN_HOSTS` | Output of `ssh-keyscan -t ed25519 <server>`, so CI only ever talks to the real server |
| `CLOUDFLARE_API_TOKEN` | A token limited to *Zone · Cache Purge* on ezzini.ma |
| `CLOUDFLARE_ZONE_ID` | From the ezzini.ma overview page in Cloudflare |

**Cloudflare**:
- A proxied DNS record for `ezzini.ma` and `www`, SSL mode *Full (strict)*.
- A cache rule *Eligible for cache* on `ezzini.ma/*`. Cloudflare doesn't cache HTML by default; the app's `Cache-Control` headers decide the rest.
- A Turnstile widget for `ezzini.ma`.
- *Email Routing* forwards `ayoub@ezzini.ma` to a real inbox.

**Brevo**: add `ezzini.ma` as a sender domain and create the DNS records it asks for (DKIM, the Brevo code, DMARC) in Cloudflare, so the contact form can send from `site@ezzini.ma`. Email Routing already publishes an SPF record: add Brevo to it (`include:spf.brevo.com`) rather than creating a second one.

## License

The code is [MIT](LICENSE). The content (texts, screenshots, the résumé itself) is mine: please don't reuse it as your own.
