# AutoElite

Premium car sales SPA built with Laravel, Inertia, and Vue.

## Setup

1. `composer install && npm install`
2. Copy `.env.example` to `.env`, then set DB and `MAIL_*` values
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `php artisan storage:link`
6. `php artisan wayfinder:generate --no-interaction`
7. `npm run dev` (and in another terminal: `php artisan serve`)

## Admin Access

- URL: `/admin`
- Email: `admin@autoelite.com`
- Password: `password`

## Mail

Configure `MAIL_*` in `.env` (SMTP, Mailtrap, etc.) for contact form emails.

