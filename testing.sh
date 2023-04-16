#!/bin/bash
php vendor/bin/phpcs || exit 1
php vendor/bin/phpstan || exit 1
php vendor/bin/psalm || exit 1
php vendor/bin/phpunit || exit 1
php vendor/bin/behat || exit 1
