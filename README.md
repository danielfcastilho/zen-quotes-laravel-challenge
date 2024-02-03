## Requirements
- Docker desktop installed

## Running the app
- run `docker compose up`
- run `docker exec -it zen-quotes-laravel-challenge php artisan migrate --seed`
- Go to http://localhost:3000

## Testing
- run `docker exec -it zen-quotes-laravel-challenge php artisan test`