#!/bin/sh
cd /var/www/html/tpadmin/

sudo chmod -R 777 runtime/
php think TypeList index
