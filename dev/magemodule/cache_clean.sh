#rm -rf /var/www/html/app/code/Jadlog
#cp -Rf /src/app/* /var/www/html/app/
str="'$*'"
cd /var/www/html/
php bin/magento cache:clean
if [[ $str == *"compile"* ]] || [[ $str == *"upgrade"* ]]; then
  php bin/magento setup:di:compile
fi
if [[ $str == *"upgrade"* ]]; then
  php bin/magento setup:upgrade
fi

