sudo php /var/www/html/bin/magento module:status
sudo php /var/www/html/bin/magento module:disable Jadlog_Embarcador --clear-static-content
sudo rm -rf /var/www/html/app/code/Jadlog
sudo chown -R www-data.www-data /var/www/html
sudo php /var/www/html/bin/magento setup:upgrade
sudo chown -R www-data.www-data /var/www/html

