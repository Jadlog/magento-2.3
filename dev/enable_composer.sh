echo -e "\n\n============================================\n"
echo -e "Begin enabling magento composer for local code.\n"
echo -e "============================================\n\n"

cd /var/www/html
composer require magento/magento-composer-installer
composer config repositories.jadlog-embarcador path /src/app/code/Jadlog/Embarcador

echo -e "\n\n============================================\n"
echo -e "Finished enabling magento composer for local code.\n"
echo -e "============================================\n\n"

