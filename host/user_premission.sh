#!/bin/bash

usermod -a -G $1 www-data
chmod -R 750 $2
