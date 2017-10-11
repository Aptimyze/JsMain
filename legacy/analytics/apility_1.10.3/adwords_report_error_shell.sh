!/bin/sh

x=`date --date="yesterday" +%Y-%m-%d`

str=`/bin/grep -c 'Overall Consumed Units' /home/developer/htdocs/analytics/apility_1.10.3/logs/jeevansathi_report_$x.htm`

if [[ -z "$str" || "$str" = 0  ]]; then
		/usr/bin/php -q /home/developer/htdocs/analytics/apility_1.10.3/adwords_error.php?date=$x&table=jeevansathi &

		echo jeevansathi_report_$x.htm > /home/developer/htdocs/analytics/apility_1.10.3/logs/error/jeevansathi_error_report_$x.htm &	
else
		echo "file run successfull"
fi




str=`/bin/grep -c 'Overall Consumed Units' /home/developer/htdocs/analytics/apility_1.10.3/logs/99acres_report_$x.htm`

if [[ -z "$str" || "$str" = 0  ]]; then
		/usr/bin/php -q /home/developer/htdocs/analytics/apility_1.10.3/adwords_error.php?date=$x&table=99acres &
		
		echo 99acres_report_$x.htm > /home/developer/htdocs/analytics/apility_1.10.3/logs/error/99acres_error_report_$x.htm &	
else
		echo "file run successfull"
fi




str=`/bin/grep -c 'Overall Consumed Units' /home/developer/htdocs/analytics/apility_1.10.3/logs/naukri_report_$x.htm`

if [[ -z "$str" || "$str" = 0  ]]; then
		/usr/bin/php -q /home/developer/htdocs/analytics/apility_1.10.3/adwords_error.php?date=$x&table=naukri &
		
		echo naukri_report_$x.htm > /home/developer/htdocs/analytics/apility_1.10.3/logs/error/naukri_error_report_$x.htm &	
else
		echo "file run successfull"
fi
