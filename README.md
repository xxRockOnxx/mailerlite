
# Mailerlite Demo

## Run Locally

Clone the project

```bash
  git clone https://github.com/xxRockOnxx/mailerlite
```

Go to the project directory

```bash
  cd mailerlite
```

Install dependencies

https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects

```bash
  docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Initialize Laravel Sail

```bash
  cp .env.example .env
  php artisan sail:install --with=mysql,redis
  php artisan key:generate
  ./vendor/bin/sail up -d
```

Run migrations and seeders

```bash
  ./vendor/bin/sail artisan migrate
  ./vendor/bin/sail artisan db:seed
```

Build the UI

```bash
  yarn install
  yarn build
```

The app is listening at `http://0.0.0.0:80`
## Running Tests

To run tests, run the following command

```bash
  ./vendor/bin/sail test
```
