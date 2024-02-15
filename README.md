## Requirements
- Docker desktop installed

## Running the app
- Go to the project root folder
- run `docker compose up`
- run `touch database/database.sqlite`
- run `docker exec -it zen-quotes-laravel-challenge php artisan migrate --seed`
- run `docker exec -it zen-quotes-laravel-challenge npm run dev`
- Go to http://localhost:3000

## Testing
- run `docker exec -it zen-quotes-laravel-challenge php artisan test`