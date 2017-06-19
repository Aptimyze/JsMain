cd /var/www/html/tieups/google
/usr/bin/php -q google_search.php > jeevan.xml
/usr/bin/php -q changexml.php
var="jeevansathi0`date +\"%u\"`.xml"
mv jeevan1.xml $var
ftp -n uploads.google.com <<-EOF
user vkhare 1garfield
binary
put ./$var
quit
EOF
