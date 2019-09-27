sudo rm -rf /var/www/html/app/code/Jadlog
sudo cp -Rf /src/app/* /var/www/html/app/
sudo chown -R www-data.www-data /var/www/html
sudo php /var/www/html/bin/magento cache:clean
if [ "$1" = "compile" ]; then
  sudo php /var/www/html/bin/magento setup:di:compile
fi
sudo chown -R www-data.www-data /var/www/html

