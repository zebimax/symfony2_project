Bug_Tracker
========================

composer install

app/console doctrine:database:create

app/console doctrine:schema:update --force

phpunit -c app/

phpcs --standard=PSR2 src/ --ignore="Resources"

app/console doctrine:fixtures:load

app/console assets:install

app/console assetic:dump

