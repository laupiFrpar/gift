# Gift project

![Code style](https://github.com/laupiFrpar/gift/workflows/Code%20style/badge.svg)
![Test](https://github.com/laupiFrpar/gift/workflows/Test/badge.svg)

## Requirements

* **[PHP](https://php.net) >= 8.0.2**
* **[Composer 2](https://getcomposer.org/)**
* **[Symfony Cli](https://symfony.com/download)**
* **[Yarn](https://yarnpkg.com/)**

## Install

```
composer install
yarn install

# Install Database
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```

## Run

```
docker-composer up -d
symfony server:start -d
yarn watch
```

You can connect on `https://127.0.0.1`.

### Login

| User           | Password |
| -------------- | -------- |
| admin@gift.wip | azerty   |

## Test

```
bin/phpunit run
```
