cd /var/www/html/
#if [ "$1" = "composer" ]; then
  composer require jadlog/embarcador
#else
#  rm -rf /var/www/html/app/code/Jadlog
#  cp -Rf /src/app/* /var/www/html/app/
#fi
php bin/magento module:status
php bin/magento module:enable Jadlog_Embarcador
php bin/magento setup:di:compile
php bin/magento setup:upgrade
