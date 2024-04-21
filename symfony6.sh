#!/bin/bash
# Deploy Symfony6 application

cd /var/www/app || exit

/usr/local/bin/php bin/console doctrine:database:create --if-not-exists -e prod
/usr/local/bin/php bin/console doctrine:migrations:migrate -n -e prod

/usr/local/bin/php bin/console cache:clear -e prod --no-warmup
/usr/local/bin/php bin/console cache:warmup -e prod

/usr/local/bin/php bin/console doctrine:fixtures:load -n

chmod ug+w /var/www/app
chown -R www-data:www-data /var/www/app

mkdir -p var

chmod -R 0777 var
chmod -R a+x bin
chmod a+x *.sh

echo ".env:"
cat .env
printf "\nDeploy done\n"
