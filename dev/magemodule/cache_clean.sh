#rm -rf /var/www/html/app/code/Jadlog
#cp -Rf /src/app/* /var/www/html/app/
cd /var/www/html/
php bin/magento cache:clean
if [ "$1" = "compile" ]; then
  php bin/magento setup:di:compile
fi

