git pull origin main &&
./vendor/bin/sail up -d &&
./vendor/bin/sail composer install &&
./vendor/bin/sail artisan migrate &&
./vendor/bin/sail bun run build &&
./vendor/bin/sail down &&
./vendor/bin/sail up -d