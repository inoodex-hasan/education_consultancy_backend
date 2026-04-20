# Laravel + Vite Production Deployment (No Missing CSS/JS)

This project uses Vite for frontend assets. `public/build` is ignored by git, so production must build assets during deploy.

## Why CSS/JS fails temporarily

- New HTML references new hashed files like `app-ABC123.css`.
- If build is missing or deploy order is wrong, those files are not present yet.
- If HTML is cached (CDN/server/browser), users may keep seeing stale references.

## Safe Deploy Steps (on live server)

Run from project root:

```bash
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## Required Checks After Deploy

```bash
ls -lah public/build
ls -lah public/build/assets
cat public/build/manifest.json
```

You should see fresh hashed files and a valid manifest.

## Critical Caching Rules

- Do not aggressively cache HTML.
- Cache built assets for long time.

Recommended headers:

- For HTML responses:
  - `Cache-Control: no-cache, no-store, must-revalidate`
- For `/build/assets/*`:
  - `Cache-Control: public, max-age=31536000, immutable`

## Important Operational Rules

- Keep previous release assets for at least 24-48 hours (prevents stale HTML -> 404 asset issue).
- Purge CDN cache (Cloudflare/etc.) after deploy.
- Ensure `public/hot` does not exist on live.

## Fast Emergency Fix (if style/js missing now)

```bash
rm -f public/hot
npm ci
npm run build
php artisan optimize:clear
```

Then purge CDN cache.

