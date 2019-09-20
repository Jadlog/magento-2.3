sudo rm -rf /var/www/html/app/code/Jadlog
sudo cp -Rf /src/app/* /var/www/html/app/
sudo php /var/www/html/bin/magento module:status
sudo php /var/www/html/bin/magento module:enable Jadlog_Embarcador
sudo chown -R www-data.www-data /var/www/html
sudo php /var/www/html/bin/magento setup:di:compile
sudo php /var/www/html/bin/magento setup:upgrade
sudo chown -R www-data.www-data /var/www/html
