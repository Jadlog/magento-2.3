#!/usr/bin/env bash

echo -e "\n\n============================================\n"
echo -e "Begin installing sSMTP.\n"
echo -e "============================================\n\n"

export DEBIAN_FRONTEND=noninteractive

export SSMTP_TEST_RECIPIENT=$1
export SSMTP_ROOT=$2
export SSMTP_MAILHUB=$3
export SSMTP_AUTHUSER=$4
export SSMTP_AUTHPASS=$5
export SSMTP_USESTARTTLS=$6
export SSMTP_REWRITEDOMAIN=$7
export SSMTP_FROMLINEOVERRIDE=$8
export SSMTP_REVALIASES=$9


echo -e " --> Updating the server.\n\n"
sudo apt-get update -y >/dev/null 2>&1
sudo apt-get upgrade -y >/dev/null 2>&1

#install sSMTP
echo -e " --> Installing sSMTP.\n\n"
sudo apt-get install -y ssmtp >/dev/null 2>&1

#configure sSMTP
echo -e " --> Configuring sSMTP.\n\n"
sudo tee /etc/ssmtp/ssmtp.conf >/dev/null << EOT
root=${SSMTP_ROOT}
mailhub=${SSMTP_MAILHUB}
AuthUser=${SSMTP_AUTHUSER}
AuthPass=${SSMTP_AUTHPASS}
UseSTARTTLS=${SSMTP_USESTARTTLS}
RewriteDomain=${SSMTP_REWRITEDOMAIN}
FromLineOverride=${SSMTP_FROMLINEOVERRIDE}
EOT

#configure revaliases
sudo tee /etc/ssmtp/revaliases >/dev/null << EOT
${SSMTP_REVALIASES}
EOT

#send test email
echo "Subject: This is a test message from ${USER}@${HOSTNAME} on $(date)" | sendmail -vvv ${SSMTP_TEST_RECIPIENT}

echo -e "\n\n============================================\n"
echo -e "Finished installing sSMTP.\n"
echo -e "============================================\n\n"

