## Requirements
- Docker desktop installed
- Composer installed

## Running the app
- Clone the project `git clone git@github.com:danielfcastilho/zen-quotes-laravel-challenge.git`
- Go inside the project root folder `cd zen-quotes-laravel-challenge/`
- run `touch database/database.sqlite`
- run `composer update`
- run `docker compose up -d`
- run `docker exec -it zen-quotes-laravel-challenge php artisan storage:link`
- run `docker exec -it zen-quotes-laravel-challenge php artisan migrate --seed`
- run `docker exec -it zen-quotes-laravel-challenge npm install`
- run `docker exec -it zen-quotes-laravel-challenge npm run dev`
- Go to http://localhost:3000

## Testing
- run `docker exec -it zen-quotes-laravel-challenge php artisan test`