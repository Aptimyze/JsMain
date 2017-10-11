#datestamp=`date '+%Y%m%d'`
#yest=$((datestamp -1))
#echo $yest;

#dt='2003 11 11'
#beta="${dt//' '/}"
#echo $beta;


#dt='2003 11 11'
#echo $dt | tr -d [[:blank:]]

#YESTERDAY=`TZ=aaa24 date +%Y-%m-%d`
#echo $YESTERDAY

#x=`date --date="yesterday" +%Y-%m-%d`
#echo $x
x=2007-12-13

str=`/bin/grep -c 'Overall Consumed Units' /home/developer/htdocs/analytics/apility_1.10.3/logs/99acres_report_2007-12-13.htm`
echo 'str is' $str

if [[ -z "$str" || "$str" = 0  ]]; then
               /bin/sh test2.sh &
	       /bin/sh test2.sh &
		echo 99acres_report_2007-12-13.htm > /home/developer/htdocs/analytics/apility_1.10.3/logs/error/abc_$x.htm
else
               echo "file run successfull"
fi

