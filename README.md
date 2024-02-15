## Requirements
- Docker desktop installed
- Composer installed

## Running the app
- Clone the project `git clone git@github.com:danielfcastilho/zen-quotes-laravel-challenge.git`
- Go inside the project root folder `cd zen-quotes-laravel-challenge/`
- run `touch database/database.sqlite`
- run `composer update`
- Make sure docker desktop is running
- run `docker compose up -d`
- run `docker exec -it zen-quotes-laravel-challenge php artisan storage:link`
- run `docker exec -it zen-quotes-laravel-challenge php artisan migrate --seed`
- run `docker exec -it zen-quotes-laravel-challenge npm install`
- run `docker exec -it zen-quotes-laravel-challenge npm run dev`
- Go to http://localhost:3000

## Testing
- run `docker exec -it zen-quotes-laravel-challenge php artisan test`

## Project Dependencies
# Module devDependencies:
@headlessui/react: ^1.4.2
@inertiajs/react: ^1.0.0
@tailwindcss/forms: ^0.5.3
@vitejs/plugin-react: ^4.2.0
autoprefixer: ^10.4.12
axios: ^1.6.4
laravel-vite-plugin: ^1.0.0
postcss: ^8.4.31
react: ^18.2.0
react-dom: ^18.2.0
tailwindcss: ^3.2.1
vite: ^5.0.0
# Laravel Project require:
php: ^8.1
guzzlehttp/guzzle: ^7.2
inertiajs/inertia-laravel: ^0.6.3
laravel/framework: ^10.10
laravel/sanctum: ^3.2
laravel/tinker: ^2.8
tightenco/ziggy: ^1.0
# Laravel Project require-dev:
fakerphp/faker: ^1.9.1
laravel/breeze: ^1.28
laravel/pint: ^1.0
laravel/sail: ^1.18
mockery/mockery: ^1.4.4
nunomaduro/collision: ^7.0
phpunit/phpunit: ^10.1
spatie/laravel-ignition: ^2.0