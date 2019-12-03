cd /var/www/html
##if [ ! -f "/var/www/html/extensions/Jadlog_Embarcador" ]; then
##  mkdir -p /var/www/html/extensions/
##  ln -s /src/app/code/Jadlog/Embarcador /var/www/html/extensions/Jadlog_Embarcador
##  composer require magento/magento-composer-installer
##  composer config repositories.jadlog-embarcador path /var/www/html/extensions/Jadlog_Embarcador
##fi
composer require magento/magento-composer-installer
composer config repositories.jadlog-embarcador path /var/www/html/extensions/Jadlog_Embarcador
composer require jadlog/embarcador:@dev
php bin/magento module:status
php bin/magento module:enable Jadlog_Embarcador
php bin/magento setup:di:compile
php bin/magento setup:upgrade
