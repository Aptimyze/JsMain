<?php
//        chdir('/usr/local/apache/sites/jeevansathi.com/htdocs/live/promotions/datadump/');
	chdir('/home/ops/affiliate_dump');
	//chdir('/usr/local/apache/sites/jeevansathi.com/htdocs/shobha/promotions/');
        passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t --where='CURDATE()<=ENTRYTIME AND ENTRYTIME<>0000-00-00 ' jsadmin AFFILIATE_MAIN > mailerdump.txt");
	passthru("/usr/local/mysql/bin/mysqldump -u user --password=Km7Iv80l -t --where='CURDATE()<=ENTRYTIME AND ENTRYTIME<>0000-00-00 ' jsadmin AFFILIATE_MAIN > mailerdump.txt");
                                                                                                                            
        passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t --where='CURDATE()<=ENTRY_DT AND ENTRY_DT<>0000-00-00 ' jsadmin AFFILIATE_DATA >> mailerdump.txt");
	
        passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t   newjs OLDEMAIL > mailerdump1.txt");
	passthru("/usr/local/mysql/bin/mysqldump -u root --password=Km7Iv80l -t   --where='CURDATE()<=ENTRY_DT AND ENTRY_DT<>0000-00-00 OR CURDATE()<=MOD_DT' newjs JPROFILE >> mailerdump1.txt");
	passthru("/bin/gzip -f mailerdump.txt");
	passthru("/bin/gzip -f mailerdump1.txt");
        passthru("scp mailerdump.txt.gz mailerdump1.txt.gz shobha@192.168.2.220:affiliate_dump/");
        passthru("ssh shobha@192.168.2.220 \"cd affiliate_dump/;/bin/gunzip -f mailerdump.txt.gz;/usr/local/mysql/bin/mysql -u root --password=Km7Iv80l jsadmin < mailerdump.txt;exit\"");
	passthru("ssh shobha@192.168.2.220 \"cd affiliate_dump/;/bin/gunzip -f mailerdump1.txt.gz;/usr/local/mysql/bin/mysql -u root --password=Km7Iv80l newjs < mailerdump1.txt;exit\"");
       echo "Table Populated";
?>

