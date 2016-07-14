# BDD

## Requirements
- PHP ^5.6
- Composer 1.1.3

## Instructions

1. `composer install`

## Commands
2. `php -f app.php offer:default path:files/primary.xml`
3. `php -f app.php offer:default path:files/secondary.xml`
4. `php -f app.php offer:3for2 path:files/primary.xml`
5. `php -f app.php offer:halfprice path:files/secondary.xml`

## Tests
2. `vendor/bin/behat`
3. `vendor/bin/behat --tags=default`
3. `vendor/bin/behat --tags=3for2`
4. `vendor/bin/behat --tags=halfprice`
