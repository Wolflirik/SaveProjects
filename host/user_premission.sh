#!/bin/bash

sudo usermod -a -G $1 www-data
sudo chmod -R 750 $2
