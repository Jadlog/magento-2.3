cd /var/www/html/
php bin/magento module:status
php bin/magento module:enable Jadlog_Embarcador
php bin/magento setup:di:compile
php bin/magento setup:upgrade
