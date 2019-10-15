#!/usr/bin/env bash

echo -e "\n\n============================================\n"
echo -e "Begin installing magento2.\n"
echo -e "============================================\n\n"

export DEBIAN_FRONTEND=noninteractive
export BASE_URL=$1
export ROOT_DB_PASSWORD=$2
export PUBLIC_KEY=$3
export PRIVATE_KEY=$4
export ADMIN_FIRSTNAME=$5
export ADMIN_LASTNAME=$6
export ADMIN_EMAIL=$7
export ADMIN_USER=$8
export ADMIN_PASSWORD=$9
export LANGUAGE=${10}
export CURRENCY=${11}
export TIMEZONE=${12}
#export COMPOSER_AUTH="{\"http-basic\": {\"repo.magento.com\": {\"username\": \"${PUBLIC_KEY}\", \"password\": \"${PRIVATE_KEY}\"}}}"


echo -e "\n --> Setting up repository credentials for Magento 2.\n\n"
mkdir -p /home/vagrant/.composer
tee -a /home/vagrant/.composer/auth.json << EOT
{
  "http-basic": {
    "repo.magento.com": {
      "username": "${PUBLIC_KEY}",
      "password": "${PRIVATE_KEY}"
    }
  }
}
EOT

function install {
  echo -e "\n --> Installing Magento 2 using composer.\n\n"
  composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition /var/www/html/

  echo -e "\n --> Creating Magento 2 MySQL database.\n\n"
  SQL="create database magento2;"
  mysql -uroot -p${ROOT_DB_PASSWORD} -e "${SQL}" >/dev/null 2>&1
}

function setup {
  echo -e "\n --> Setting up Magento 2.\n\n"
  echo "Parameters:"
  echo "--base-url=\"${BASE_URL}\""
  echo "--db-host=\"localhost\""
  echo "--db-name=\"magento2\""
  echo "--db-user=\"root\""
  echo "--db-password=\"${ROOT_DB_PASSWORD}\""
  echo "--admin-firstname=\"${ADMIN_FIRSTNAME}\""
  echo "--admin-lastname=\"${ADMIN_LASTNAME}\""
  echo "--admin-email=\"${ADMIN_EMAIL}\""
  echo "--admin-user=\"${ADMIN_USER}\""
  echo "--admin-password=\"${ADMIN_PASSWORD}\""
  echo "--language=\"${LANGUAGE}\""
  echo "--currency=\"${CURRENCY}\""
  echo "--timezone=\"${TIMEZONE}\""
  echo "--use-rewrites=\"1\""
  echo "--backend-frontname=\"admin\""

  php /var/www/html/bin/magento setup:install \
  --base-url="${BASE_URL}" \
  --db-host="localhost" \
  --db-name="magento2" \
  --db-user="root" \
  --db-password="${ROOT_DB_PASSWORD}" \
  --admin-firstname="${ADMIN_FIRSTNAME}" \
  --admin-lastname="${ADMIN_LASTNAME}" \
  --admin-email="${ADMIN_EMAIL}" \
  --admin-user="${ADMIN_USER}" \
  --admin-password="${ADMIN_PASSWORD}" \
  --language="${LANGUAGE}" \
  --currency="${CURRENCY}" \
  --timezone="${TIMEZONE}" \
  --use-rewrites="1" \
  --backend-frontname="admin" \

  echo -e "\n --> Copying credentials.\n\n"
  cp /home/vagrant/.composer/auth.json /var/www/html/

  echo -e "\n --> Setting up Magento 2 on developer mode.\n\n"
  php /var/www/html/bin/magento deploy:mode:set developer
  php /var/www/html/bin/magento deploy:mode:show

}

function sample_data {
  echo -e "\n --> Deploying Magento 2 Sample Data.\n\n"
  php /var/www/html/bin/magento sampledata:deploy
  php /var/www/html/bin/magento setup:upgrade
}

install
setup
sample_data

echo -e "\n\n============================================\n"
echo -e "Finished installing magento2.\n"
echo -e "============================================\n\n"
