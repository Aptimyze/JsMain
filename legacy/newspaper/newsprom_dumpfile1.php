<?php
	chdir('/usr/local/apache/sites/jeevansathi.com/htdocs/live/promotions/datadump/');
	passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t -S /tmp/mysql2.sock --where='DATE_SUB(CURDATE(),INTERVAL 5 DAY)<=ENTRYTIME AND ENTRYTIME<>0000-00-00' jsadmin MAILER_TEST > mailerdump.txt");

	passthru("/bin/gzip -f mailerdump.txt");
	passthru("scp mailerdump.txt.gz alok@192.168.2.206:");
//	passthru("ssh alok@192.168.2.206 \"gunzip -f mailerdump.txt.gz;/usr/local/mysql/bin/mysql -u root --password=Km7Iv80l jsadmin < mailerdump.txt;exit\"");

	echo "Table Populated";
?>

