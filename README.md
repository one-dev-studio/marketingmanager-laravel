# MarketPulse

AI-powered marketing automation (Laravel 12+/13, PHP 8.3, Blade + Vue 3 + Alpine + Tailwind).

## Requirements

- PHP 8.3+, Composer, Node.js
- MySQL 8 / PostgreSQL 15 or SQLite for local
- Redis 7 (queues, cache, broadcasting)
- Optional: Stripe, PayPal, Pusher, Sentry, OpenAI/Gemini keys

## Install

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Health check: `GET /up`

## Deploy

1. Set `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_KEY`, and DB/Redis/mail.
2. `composer install --no-dev --optimize-autoloader`
3. `php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache`
4. `npm ci && npm run build`
5. Point the web root at `public/`. Queue workers: copy `scripts/supervisor-queue.conf` into Supervisor.
6. Daily backups: cron `scripts/backup.sh` (30-day retention). Restore by loading the latest `db-*.sql.gz` and extracting `files-*.tar.gz` into `storage/app`.
7. Optional APM: set `SENTRY_LARAVEL_DSN`. Local debugging: `composer require --dev laravel/telescope` then `php artisan telescope:install`.

## Payments

Set `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` and/or PayPal keys. Webhooks: `POST /webhooks/stripe` and `/webhooks/paypal`. Without live keys the app records subscriptions locally via `PaymentGatewayService`.

## Remaining work

See [TODO.md](TODO.md). Historical planning: [docs/archive/](docs/archive/README.md).

## License

MIT
