## Requirements
- Docker desktop installed
- Composer installed

## Running the app
- Go to the project root folder
- run `composer update`
- run `docker compose -d up`
- run `touch database/database.sqlite`
- run `docker exec -it zen-quotes-laravel-challenge php artisan storage:link`
- run `docker exec -it zen-quotes-laravel-challenge php artisan migrate --seed`
- run `docker exec -it zen-quotes-laravel-challenge npm install`
- run `docker exec -it zen-quotes-laravel-challenge npm run dev`
- Go to http://localhost:3000

## Testing
- run `docker exec -it zen-quotes-laravel-challenge php artisan test`