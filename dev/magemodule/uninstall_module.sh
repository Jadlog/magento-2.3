cd /var/www/html/
php bin/magento module:status
php bin/magento module:uninstall -c -r Jadlog_Embarcador
#rm -rf /var/www/html/app/code/Jadlog
#php bin/magento module:disable Jadlog_Embarcador --clear-static-content
#composer remove -r jadlog/embarcador
php bin/magento setup:upgrade
