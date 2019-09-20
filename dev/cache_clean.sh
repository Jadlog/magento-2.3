sudo rm -rf /var/www/html/app/code/Jadlog
sudo cp -Rf /src/app/* /var/www/html/app/
sudo chown -R www-data.www-data /var/www/html
sudo php /var/www/html/bin/magento cache:clean
sudo chown -R www-data.www-data /var/www/html

