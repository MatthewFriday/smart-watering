#!/bin/bash

echo "Fuggosegek telepitese..."
apt -y install apache2 php libapache2-mod-php mariadb-server mariadb-client php-mysql php-curl python3 python3-pip

echo "Python csomagok telepitese..."
curl -sL https://github.com/Seeed-Studio/grove.py/raw/master/install.sh | bash -s -
pip3 install seeed-python-dht
pip3 install mysql-connector-python
pip3 install python-pushover

echo "Controller beallitasa..."
mkdir /opt/controller
cp -r smart-watering/controller/* /opt/controller
cp smart-watering/controller/smart-watering-controller.service /etc/systemd/system/
systemctl daemon-reload
systemctl enable smart-watering-controller

echo "Weboldal beallitasa..."
sed -i "/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride all/" /etc/apache2/apache2.conf
rm -r /var/www/html/*
cp -r smart-watering/web/* /var/www/html

echo "Szolgaltatasok ujrainditasa..."
systemctl restart apache2 smart-watering-controller