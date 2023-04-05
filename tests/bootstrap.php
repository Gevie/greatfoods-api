<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new \Symfony\Component\Filesystem\Filesystem())->remove(__DIR__.'/../var/cache/test');

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

passthru(sprintf('APP_ENV=test php "%s/../bin/console" doctrine:database:drop --force --if-exists', __DIR__));
passthru(sprintf('APP_ENV=test php "%s/../bin/console" doctrine:database:create', __DIR__));
passthru(sprintf('APP_ENV=test php "%s/../bin/console" doctrine:migrations:migrate -n', __DIR__));
