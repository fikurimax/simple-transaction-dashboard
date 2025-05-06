cp .env.example .env
npm install
php artisan key:generate
php artisan storage:link
php artisan migrate
