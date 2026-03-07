#!/bin/sh

php /var/www/html/batch/batch_daily.php >/var/log/alchemis/batch_daily.html 2>&1
php /var/www/html/batch/batch_post_initiative_status.php >/var/log/alchemis/batch_post_initiative_status.html 2>&1
php /var/www/html/batch/batch.php >/var/log/alchemis/batch.html 2>&1