!/bin/sh

x=`date --date="yesterday" +%Y-%m-%d`

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.10.3/adwords_log --output-document=/home/developer/htdocs/analytics/apility_1.10.3/logs/jeevansathi_report_$x.htm http://localhost/analytics/apility_1.10.3/adwords_jeevansathi_report.php &

/bin/sleep 5

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.10.3/adwords_log --output-document=/home/developer/htdocs/analytics/apility_1.10.3/logs/99acres_report_$x.htm http://localhost/analytics/apility_1.10.3/adwords_99acres_report.php &

/bin/sleep 5

/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.10.3/adwords_log --output-document=/home/developer/htdocs/analytics/apility_1.10.3/logs/naukri_report_$x.htm http://localhost/analytics/apility_1.10.3/adwords_naukri_report.php &

