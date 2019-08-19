#!/usr/bin/env bash

echo -e "\n\n============================================\n"
echo -e "Begin provisioning.\n"
echo -e "============================================\n\n"

export DEBIAN_FRONTEND=noninteractive
export ROOT_DB_PASSWORD=$1

#speed up network
echo -e " --> Speeding up network (disable IPv6).\n\n"
sudo tee -a /etc/sysctl.conf << EOT
net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1
net.ipv6.conf.lo.disable_ipv6 = 1
EOT
sudo sysctl -p

echo -e " --> Updating the server.\n\n"
sudo apt-get update -y >/dev/null 2>&1
sudo apt-get upgrade -y >/dev/null 2>&1

echo -e "\n --> Installing swapspace - dynamically manage swap space .\n\n"
sudo apt-get install -y swapspace >/dev/null 2>&1

echo -e "\n --> Installing cURL.\n\n"
sudo apt-get install -y curl >/dev/null 2>&1

echo -e "\n --> Installing MySQL tools.\n\n"
sudo apt-get install -y mysql-client >/dev/null 2>&1

echo -e "\n --> Installing Htop, a process monitoring tool.\n\n"
sudo apt-get install -y htop >/dev/null 2>&1

# echo -e "\n --> Installing git and git-flow \n\n"
# sudo apt-get install -y git  >/dev/null 2>&1
# sudo apt-get install -y git-flow  >/dev/null 2>&1

# echo -e "\n --> Installing make. \n\n"
# sudo apt-get install -y make  >/dev/null 2>&1

# echo -e "\n --> Installing vim. \n\n"
# sudo apt-get install -y vim  >/dev/null 2>&1

# echo -e "\n --> Installing SASL libraries. \n\n"
# sudo apt-get install -y libsasl2-dev >/dev/null 2>&1

# echo -e "\n --> Installing redis. \n\n"
# sudo apt-get install -y redis-server >/dev/null 2>&1

# echo -e "\n --> Installing memcached. \n\n"
# sudo apt-get install -y memcached >/dev/null 2>&1

# echo -e "\n --> Installing wget. \n\n"
# sudo apt-get install -y wget >/dev/null 2>&1

echo -e "\n --> Installing GZip.\n\n"
sudo apt-get install -y gzip >/dev/null 2>&1

echo -e "\n --> Installing UnZip.\n\n"
sudo apt-get install -y unzip >/dev/null 2>&1

## If using nginx, uncomment this and comment out the 'apache' stuff that follows.
# echo -e "\n --> Installing NGINX. \n\n"
# sudo apt-get install -y nginx
# sudo service nginx start

## if using apache, this should be uncommented, and commet out the 'nginx' stuff above.
echo -e "\n --> Installing Apache.\n\n"
sudo apt-get install -y apache2 >/dev/null 2>&1
sudo sed -i -e 's/AllowOverride None/AllowOverride All/gi' /etc/apache2/apache2.conf
sudo usermod -a -G www-data vagrant
sudo a2enmod rewrite >/dev/null 2>&1

#echo "ServerName localhost" >> /etc/apache2/apache2.conf

#PHP
#echo -e "\n --> Installing PHP.\n\n"
#sudo apt-get install -y php
#sudo apt-get install -y libapache2-mod-php
#sudo apt-get install -y php-xdebug


# In some cases PHP7.1 might be better suited, in which case simply change 7.2 to 7.1 should suffice.
echo -e "\n --> Installing PHP 7.2 and common modules.\n\n"
#sudo apt-get install -y python-software-properties >/dev/null 2>&1
#sudo add-apt-repository -y ppa:ondrej/php >/dev/null 2>&1
#sudo apt-get update >/dev/null 2>&1

sudo apt-get install -y php7.2 >/dev/null 2>&1
sudo apt-get install -y php-pear >/dev/null 2>&1
sudo apt-get install -y php7.2-curl >/dev/null 2>&1
sudo apt-get install -y php7.2-dev >/dev/null 2>&1
sudo apt-get install -y php7.2-gd >/dev/null 2>&1
sudo apt-get install -y php7.2-mbstring >/dev/null 2>&1
sudo apt-get install -y php7.2-zip >/dev/null 2>&1
sudo apt-get install -y php7.2-mysql >/dev/null 2>&1
sudo apt-get install -y php7.2-xml >/dev/null 2>&1
sudo apt-get install -y php7.2-cli >/dev/null 2>&1
sudo apt-get install -y php7.2-memcached >/dev/null 2>&1
sudo apt-get install -y php7.2-redis >/dev/null 2>&1
sudo apt-get install -y php7.2-imagick >/dev/null 2>&1
sudo apt-get install -y php7.2-gmp >/dev/null 2>&1
sudo apt-get install -y php7.2-json >/dev/null 2>&1
sudo apt-get install -y php7.2-mcrypt >/dev/null 2>&1
sudo apt-get install -y php7.2-mongodb >/dev/null 2>&1
sudo apt-get install -y php7.2-mysql >/dev/null 2>&1
sudo apt-get install -y php7.2-odbc >/dev/null 2>&1
sudo apt-get install -y php7.2-pgsql >/dev/null 2>&1
sudo apt-get install -y php7.2-sqlite3 >/dev/null 2>&1
sudo apt-get install -y php7.2-soap >/dev/null 2>&1
sudo apt-get install -y php7.2-xsl >/dev/null 2>&1
sudo apt-get install -y php7.2-cli >/dev/null 2>&1
sudo apt-get install -y php7.2-bcmath >/dev/null 2>&1
sudo apt-get install -y php7.2-intl >/dev/null 2>&1
sudo apt-get install -y libapache2-mod-php7.2 >/dev/null 2>&1
sudo apt-get install -y php7.2-xdebug >/dev/null 2>&1
sudo a2enmod php7.2 >/dev/null 2>&1

# xdebug format output
echo -e "\n --> Configuring xdebug. \n\n"
sudo tee -a /etc/php/7.2/mods-available/xdebug.ini << END
; with no limits
; (maximum nesting is 1023)
xdebug.var_display_max_depth = -1
xdebug.var_display_max_children = -1
xdebug.var_display_max_data = -1
END

#increase some php limits
sudo sed -i -e 's/memory_limit =.*/memory_limit = 768M/' /etc/php/7.2/apache2/php.ini
sudo sed -i -e 's/max_execution_time = .*/max_execution_time = 300/' /etc/php/7.2/apache2/php.ini

echo -e "\n --> Installing MySQL server and configure it to use '${ROOT_DB_PASSWORD}' as password.\n\n"
echo -e "mysql-server mysql-server/root_password password ${ROOT_DB_PASSWORD}" | sudo debconf-set-selections >/dev/null 2>&1
echo -e "mysql-server mysql-server/root_password_again password ${ROOT_DB_PASSWORD}" | sudo debconf-set-selections >/dev/null 2>&1
sudo apt-get -q -y install mysql-server >/dev/null 2>&1

echo -e "\n --> Update MySQL configuration to make it accessible from host via root.\n"
echo -e "--> NOTE THAT THIS IS NOT SECURE AND FOR DEVELOPMENT ONLY.\n\n"

sudo sed -i -e 's/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

sudo tee -a /etc/mysql/mysql.conf.d/mysqld.cnf << EOT
[mysqld]
character_set_server=utf8mb4
collation_server=utf8mb4_general_ci
innodb_file_per_table
optimizer_search_depth = 0
sql_mode=""

[mysql]
default-character-set=utf8mb4
EOT

SQL="GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '${ROOT_DB_PASSWORD}' WITH GRANT OPTION; FLUSH PRIVILEGES;"
mysql -uroot -p${ROOT_DB_PASSWORD} -e "${SQL}" >/dev/null 2>&1

## Modify the timezone as needed.
#echo -e "\n --> Set timezone for MySQL to 'America/Los_Angeles'. \n\n"
#mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -uroot -p${ROOT_DB_PASSWORD} mysql
#sudo sed -ri '/\[mysqld\]/ a\ default-time-zone = \x27America/Los_Angeles\x27' /etc/mysql/mysql.conf.d/mysqld.cnf

#Some mysql tweaks


echo -e "\n --> Restarting MySQL. \n\n"
sudo systemctl restart mysql >/dev/null 2>&1


echo -e "\n --> Installing Composer.\n\n"
sudo curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer


## Set timezone (modify this if needed).
#echo -e "\n --> Configuring time zone for the server. \n\n"
#sudo timedatectl set-timezone America/Los_Angeles


# Set up sync'ing for time.
sudo apt-get install -y ntp >/dev/null 2>&1


##
## Additional services that are not needed but may help in managing the server.
## Note that for webmin specifically, a port needs to be forwarded for it to work.
##
## WEBMIN
## ref.: https://doxfer.webmin.com/Webmin/Installation#apt-get_.28Debian.2FUbuntu.2FMint.29
## IMPORTANT: Make sure wget is being installed above. Credentials for webmin should be vagrant/vagrant.
##
# echo -e "\n --> Installing Webmin.\n\n"
# sudo sh -c 'echo "deb http://download.webmin.com/download/repository sarge contrib" > /etc/apt/sources.list.d/webmin.list'
# sudo wget -qO - http://www.webmin.com/jcameron-key.asc | sudo apt-key add -
# sudo apt-get update
# sudo apt-get install -y webmin >/dev/null 2>&1

#
# PHPMYAdmin
# IMPORTANT: access php my admin using /phpmyadmin, and credentials are the same as mysql.
#
echo -e "\n --> Installing PHPMyAdmin. \n\n"
echo -e "phpmyadmin phpmyadmin/dbconfig-install boolean true" | sudo debconf-set-selections >/dev/null 2>&1
echo -e "phpmyadmin phpmyadmin/app-password-confirm password ${ROOT_DB_PASSWORD}" | sudo debconf-set-selections >/dev/null 2>&1
echo -e "phpmyadmin phpmyadmin/mysql/admin-pass password ${ROOT_DB_PASSWORD}" | sudo debconf-set-selections >/dev/null 2>&1
echo -e "phpmyadmin phpmyadmin/mysql/app-pass password ${ROOT_DB_PASSWORD}" | sudo debconf-set-selections >/dev/null 2>&1
echo -e "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | sudo debconf-set-selections >/dev/null 2>&1

sudo apt-get install -y phpmyadmin >/dev/null 2>&1
sudo ln -s /etc/phpmyadmin/apache.conf /etc/apache2/sites-enabled/phpmyadmin.conf

##
## Elastic Search
## IMPORTANT: this may involve some work to get elastic search working correctly.
## see https://www.elastic.co/guide/en/elasticsearch/reference/current/deb.html#deb-repo
##
# echo -e "\n --> Installing Java 8 and ElasticSearch. \n\n"
# sudo apt-add-repository ppa:webupd8team/java >/dev/null 2>&1
# sudo apt-get update >/dev/null 2>&1
# echo oracle-java8-installer shared/accepted-oracle-license-v1-1 select true | /usr/bin/debconf-set-selections
# sudo apt-get install -y -q oracle-java8-installer >/dev/null 2>&1
#
# sudo apt-get install -y apt-transport-https >/dev/null 2>&1
# echo "deb https://artifacts.elastic.co/packages/6.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-6.x.list
# sudo apt-get update >/dev/null 2>&1
# sudo apt-get -y install elasticsearch >/dev/null 2>&1
## Update binding to access elasticsearch from outside the VM
# sudo su -   # switches to root (see last few comments of https://gist.github.com/maxivak/c318fd085231b9ab934e631401c876b1)
# sudo echo "network.host: 0.0.0.0" >> /etc/elasticsearch/elasticsearch.yml # ref. https://qbox.io/blog/qbox-a-vagrant-virtual-machine-for-elasticsearch-2-x.
#
## Enable CORS for elasticsearch: not secure, this basically allows any source to access this instance of elasticsearch.
# sudo echo "http.cors.enabled: true" >> /etc/elasticsearch/elasticsearch.yml
# sudo echo "http.cors.allow-origin: /https?:\/\/.*/" >> /etc/elasticsearch/elasticsearch.yml
#
## restart/start elasticsearch.
# sudo /etc/init.d/elasticsearch restart
#
## Set elasticsearch to start automatically.
# sudo /bin/systemctl daemon-reload
# sudo /bin/systemctl enable elasticsearch.service
# echo -e "Check that elasticsearch is running by trying 'curl http://localhost:9200/?pretty' from guest environ. \n\n"
## Note: this should return some JSON.

# link src under www pages
#if ! [ -L /var/www ]; then
#  ln -fs /src /var/www/html/
#fi

# Configure test page for apache/php
# http://<hostname>:8000/
sudo mkdir /var/www/test/
sudo tee /var/www/test/index.php << EOT
<html>
<pre>
Xdebug test (vardump must be formatted):
</pre>
<?php
  var_dump( array('Xis', 'Ypslon', 'Ze', 'Dabliw') );
?>
<pre>
phpinfo();
</pre>
<?php
  phpinfo();
?>
</html>
EOT

sudo tee -a /etc/apache2/ports.conf << EOT
Listen 8000
EOT

sudo tee /etc/apache2/sites-available/001-test.conf << EOT
<VirtualHost *:8000>

  ServerAdmin webmaster@localhost
  DocumentRoot /var/www/test/

  ErrorLog ${APACHE_LOG_DIR}/error_test.log
  CustomLog ${APACHE_LOG_DIR}/access_test.log combined

</VirtualHost>
EOT
sudo ln -s ../sites-available/001-test.conf /etc/apache2/sites-enabled/

#restart apache
echo -e "\n --> Restarting Apache. \n\n"
sudo systemctl restart apache2 >/dev/null 2>&1

echo -e "\n --> Done \n\n"

echo -e "============================================ \n"
echo -e "Finished provisioning.\n"
echo -e "============================================\n\n"
echo -e "\n\n"