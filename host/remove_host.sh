#!/bin/bash

if [ -f /etc/apache2/sites-available/$1.conf ];
then
  grep -v "$1" /etc/hosts > /home/ulvbern/Server/host/hosts
  sudo cp /home/ulvbern/Server/host/hosts /etc/hosts
  rm /home/ulvbern/Server/host/hosts
  sudo rm -rf /home/ulvbern/Server/$1
  sudo a2dissite $1.conf > /dev/null 2>&1
  sudo rm /etc/apache2/sites-available/$1.conf
  sudo systemctl restart apache2
  echo "Хост $1 был удален"
else
  echo "Хост $1 не существует"
fi
