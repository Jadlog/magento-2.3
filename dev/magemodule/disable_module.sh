cd /var/www/html/
php bin/magento module:status
php bin/magento module:disable Jadlog_Embarcador --clear-static-content
#rm -rf /var/www/html/app/code/Jadlog
#php /var/www/html/bin/magento setup:upgrade

