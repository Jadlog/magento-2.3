#!/usr/bin/env bash

echo -e "\n\n============================================\n"
echo -e "Begin prepare installing magento2.\n"
echo -e "============================================\n\n"

export DEBIAN_FRONTEND=noninteractive

##not needed anymore
##vagrant default group is www-data now
##sudo usermod -g www-data vagrant
##sudo usermod -a -G vagrant vagrant

#stop apache
echo -e "\n --> Stoping Apache. \n\n"
sudo systemctl stop apache2 >/dev/null 2>&1

#clean any html data
sudo rm -rf /var/www/html/*
sudo find /var/www/html/ -maxdepth 1 -name ".*" | xargs rm -rf

#set ownership
sudo chown -R vagrant.vagrant /var/www

#permit group write
sudo chmod -R g+w /var/www

#maintain group id on new files
sudo chmod -R g+s /var/www

#change apache user and group
echo -e "\n --> Change Apache User and Group. \n\n"
sudo sed -i -e 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sudo sed -i -e 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars

#start apache
echo -e "\n --> Starting Apache. \n\n"
sudo systemctl start apache2 >/dev/null 2>&1


echo -e "\n\n============================================\n"
echo -e "Finished prepare installing magento2.\n"
echo -e "============================================\n\n"
