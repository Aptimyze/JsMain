#!/bin/bash

#cd /home/nikhil/download/svn-line/realsvn/branches/sms_sep23/dvd/FinalWork
cd /var/www/testjs4/dvd/FinalWork
#cd /var/www/testjs3/dvd/FinalWork
for file in csvs/*.csv
do
k=`echo "$file" |sed 's/csvs\///' | sed 's/\([0-9]*\).*/\1/'`
echo $k;
php -q test.php $k
cur_dt=`date +%F`
cp "csvs/$k.csv" "csvs/logs/$k-$cur_dt.csv"
done
