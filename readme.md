# Organisation API

## Installation

```bash
composer install
cp .env.example .env
```
Fill `.evn` with your settings

## Tests

### Unit
```bash
./vendor/bin/phpunit --testsuite=unit
```

### Integration
```bash
./vendor/bin/phpunit --testsuite=integration
```

## Migrations
```bash
./artisan migrate
```

## Run web-server
```bash
php -S localhost:9999 -t . ./app.php
```

Of course you can use any external web-сервер, e.g. _nginx_:
```
server {
    listen   80;

    server_name organisations-api.local;

    charset utf-8;

    set $project_root /path/to/organisations-api;

    root $project_root/public;

    location / {
        try_files $uri @app;
    }

    location @app{
        fastcgi_pass 127.0.0.1:9000;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $project_root/app.php;
    }
}
```


## API endpoints

### Create organisations
POST /api/v1/organisations

### Delete all organisations
DELETE /api/v1/organisations

### Get organisation relations
GET /api/v1/organisations/{title}/relations

Example: http://localhost:9999/api/v1/organisations/Black%20Banana/relations

## Command line interface

### Create organisations
```bash
./artisan organisations:create "$(< ./tests/integration/samples/input.json)"
```

### Delete all organisations
```bash
./artisan organisations:delete-all
```

### Get organisation relations
```bash
./artisan organisations:relations "Black Banana"
```


### Populate database with fake data
```bash
./artisan organisations:seed
```
