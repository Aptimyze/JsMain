#!/bin/bash

cd /var/www/html_public/ && find -type f ! -path "./crmSync.sh" ! -path "./commonConfig/*" ! -path "./.svn/*" ! -path "./cache/*" ! -path "./log/*" ! -path "./web/smarty/templates_c/*" -print > /home/developer/tempupload/crmSync.txt
rsync --files-from=/home/developer/tempupload/crmSync.txt /var/www/html/ /var/www/html_public/
/usr/local/php/bin/php /var/www/html_public/symfony cc --type=minify > /home/developer/tempupload/symfony_cc.txt
