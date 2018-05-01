#!/bin/bash

cp /etc/hosts /home/ulvbern/Server/host/hosts
echo "127.0.0.1   $1" >> /home/ulvbern/Server/host/hosts
sudo cp /home/ulvbern/Server/host/hosts /etc/hosts
rm /home/ulvbern/Server/host/hosts
mkdir /home/ulvbern/Server/$1
mkdir /home/ulvbern/Server/$1/www
mkdir /home/ulvbern/Server/$1/logs
touch /home/ulvbern/Server/host/$1.conf
echo "<VirtualHost *:80>
ServerName $1
ServerAlias www.$1
ServerAdmin wolflirik@gmail.com
DocumentRoot /home/ulvbern/Server/$1/www
<Directory /home/ulvbern/Server/$1/www>
  Require all granted
  AllowOverride All
</Directory>
ErrorLog /home/ulvbern/Server/$1/logs/error.log
LogLevel warn
ServerSignature On
</VirtualHost>" >> /home/ulvbern/Server/host/$1.conf
sudo cp /home/ulvbern/Server/host/$1.conf /etc/apache2/sites-available/$1.conf
rm /home/ulvbern/Server/host/$1.conf
sudo a2ensite $1.conf
sudo systemctl restart apache2
