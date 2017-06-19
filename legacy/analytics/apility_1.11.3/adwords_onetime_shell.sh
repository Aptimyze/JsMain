!/bin/sh

#x=`date --date="yesterday" +%Y-%m-%d`
x=`date +%Y-%m-%d`

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.11.3/adwords_onetime_log --output-document=/home/developer/htdocs/analytics/apility_1.11.3/logs_onetime/jeevansathi_onetime_$x.htm http://localhost/analytics/apility_1.11.3/infoedge_jeevansathi.php &

/bin/sleep 3600

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.11.3/adwords_onetime_log --output-document=/home/developer/htdocs/analytics/apility_1.11.3/logs_onetime/99acres_onetime_$x.htm http://localhost/analytics/apility_1.11.3/infoedge_99acres.php &

/bin/sleep 3600

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.11.3/adwords_onetime_log --output-document=/home/developer/htdocs/analytics/apility_1.11.3/logs_onetime/naukri_onetime_$x.htm http://localhost/analytics/apility_1.11.3/infoedge_naukri.php &
