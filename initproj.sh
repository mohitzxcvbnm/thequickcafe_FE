#!/bin/sh
composer install
chmod -R 777 writable/cache
cp /var/www/project-root/codeigniter4.conf /etc/apache2/sites-available/codeigniter4.conf
cd /etc/apache2/sites-available/
a2dissite 000-default.conf
a2ensite codeigniter4.conf
service apache2 stop
service apache2 start
cd /var/www/project-root