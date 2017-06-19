<?php
	chdir(JsConstants::$docRoot.'/newspaper/datadump/');

	//passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t  --where='CURDATE()<=ENTRYTIME AND ENTRYTIME<>0000-00-00 ' jsadmin AFFILIATE_MAIN > mailerdump.txt");
	$val1 = shell_exec("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t  --where=\"ENTRYTIME = '2006-12-19' AND ENTRYTIME<>0000-00-00 \" jsadmin AFFILIATE_MAIN > mailerdump.txt");

	//passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t  --where='CURDATE()<=ENTRY_DT AND ENTRY_DT<>0000-00-00 ' jsadmin AFFILIATE_DATA >> mailerdump.txt");
	$val2 = shell_exec("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t  --where=\"ENTRY_DT = '2006-12-19' AND ENTRY_DT<>0000-00-00 \" jsadmin AFFILIATE_DATA >> mailerdump.txt");

	$val3 = shell_exec("/bin/gzip -f mailerdump.txt");
	$val4 = shell_exec("scp mailerdump.txt.gz ops@198.65.157.169:affiliate_dump/");
	$val5 = shell_exec("ssh ops@198.65.157.169 \"cd affiliate_dump/;/bin/gunzip -f mailerdump.txt.gz;/usr/local/mysql/bin/mysql -u user --password=CLDLRTa9 jsadmin < mailerdump.txt;/usr/local/bin/php -q /home/ops/affiliate/filter_tables.php;exit\"");

	//mail("shobha.solanki@gmail.com","newspaper datadump","done.\nval1 : ".$val1."\nval2 : ".$val2."\nval3 : ".$val3."\nval4 : ".$val4."\nval5 : ".$val5);
	mail("shiv.narayan@jeevansathi.com,alok@jeevansathi.com","newspaper datadump","done.\nval1 : ".$val1."\nval2 : ".$val2."\nval3 : ".$val3."\nval4 : ".$val4."\nval5 : ".$val5);
?>
