echo -e "\n\n============================================\n"
echo -e "Begin bind mount config.\n"
echo -e "============================================\n\n"

tee /etc/systemd/system/var-www-html-extensions-Jadlog_Embarcador.automount  << EOT
[Unit]
Description=Automount /var/www/html/extensions/Jadlog_Embarcador
Before=apache2.service

[Automount]
Where=/var/www/html/extensions/Jadlog_Embarcador

[Install]
WantedBy=multi-user.target
EOT


tee vim /etc/systemd/system/var-www-html-extensions-Jadlog_Embarcador.mount << EOT
[Unit]
Description=Mount /var/www/html/extensions/Jadlog_Embarcador

[Mount]
What=/src/app/code/Jadlog/Embarcador
Where=/var/www/html/extensions/Jadlog_Embarcador
Type=none
Options=bind

[Install]
WantedBy=multi-user.target
EOT

#disable mount, but enable automount
systemctl daemon-reload
#sudo systemctl start var-www-html-extensions-Jadlog_Embarcador.automount
#ls -lah /var/www/html/extensions/Jadlog_Embarcador/
#sudo systemctl stop var-www-html-extensions-Jadlog_Embarcador.automount
#ls -lah /var/www/html/extensions/Jadlog_Embarcador/
systemctl --now enable var-www-html-extensions-Jadlog_Embarcador.automount
ls -lah /var/www/html/extensions/Jadlog_Embarcador/

#list status
systemctl is-enabled var-www-html-extensions-Jadlog_Embarcador.automount
#--> enabled
systemctl is-enabled var-www-html-extensions-Jadlog_Embarcador.mount
#--> disabled

echo -e "\n\n============================================\n"
echo -e "Finished bind mount config.\n"
echo -e "============================================\n\n"
