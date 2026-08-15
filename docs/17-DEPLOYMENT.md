# Deployment
Production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain.com
```

Commands:
```bash
php artisan migrate --force
php artisan storage:link
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ensure storage/ and bootstrap/cache/ are writable by the web server.
