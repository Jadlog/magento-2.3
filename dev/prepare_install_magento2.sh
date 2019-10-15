#!/usr/bin/env bash

echo -e "\n\n============================================\n"
echo -e "Begin prepare installing magento2.\n"
echo -e "============================================\n\n"

export DEBIAN_FRONTEND=noninteractive

#clean any html data
sudo rm -rf /var/www/html/*
sudo find /var/www/html/ -maxdepth 1 -name ".*" | xargs rm -rf

#set ownership
sudo chown -R vagrant.www-data /var/www

#permit group write
sudo chmod -R g+w /var/www

#maintain group id on new files
sudo chmod -R g+s /var/www

#change apache user
#sudo sed -i -e 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars

#restart apache
#echo -e "\n --> Restarting Apache. \n\n"
#sudo systemctl restart apache2 >/dev/null 2>&1


echo -e "\n\n============================================\n"
echo -e "Finished prepare installing magento2.\n"
echo -e "============================================\n\n"
