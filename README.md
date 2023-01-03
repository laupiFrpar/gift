# Gift project

![Code style](https://github.com/laupiFrpar/gift/workflows/Code%20style/badge.svg)
![Test](https://github.com/laupiFrpar/gift/workflows/Test/badge.svg)

## Requirements

* **[PHP](https://php.net) >= 8.0.2**
* **[Composer 2](https://getcomposer.org/)**
* **[Symfony Cli](https://symfony.com/download)**

## Install

```sh
composer install

# Install Database
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate

# Load data fixtures
bin/console doctrine:fixtures

# Install assets
bin/console assets:install
```

## Run

```sh
docker-composer up -d
symfony server:start -d
```

You can connect on `https://127.0.0.1/docs`.

## Test

```sh
bin/console --env=test doctrine:database:create
bin/console --env=test doctrine:schema:create
bin/phpunit run
```
