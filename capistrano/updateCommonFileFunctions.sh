#!/bin/bash

touch /tmp/jsCssFilelog.txt
chmod 777 /tmp/jsCssFilelog.txt

cd /var/www/html/branches/trunk && git pull


cd /var/www/html/branches/trunk && git diff --name-only 9e64859e292bbc7ec09e954710cc9b42aaf992ef cca018c7452707b78e3c40c58d4fd15e6d1de087 > /tmp/jsCssFilelog.txt

output=$(/usr/bin/php /var/www/html/branches/trunk/capistrano/generate_commonfile_functions_git.php /tmp)

echo $output

if [ $output = "Commit" ]; then
   echo "commiting the code"
   git commit -m "Server side ForceAllow Css/Js" /var/www/html/branches/trunk/web/profile/commonfile_functions.php
   git commit -m "Server side ForceAllow localStorageRevision" /var/www/html/branches/trunk/capistrano/localStorageRevision.txt
   branchName=$(cd /var/www/html/branches/trunk && git rev-parse --abbrev-ref HEAD)
   echo $branchName
   git push origin $branchName
elif [ $output = "NA" ]; then
	echo "NA"
else
	echo "Error"
fi