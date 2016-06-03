<?php
//$db=mysql_connect("localhost","root","") or die ('could not connect to database');
//$db=mysql_connect("localhost:/tmp/mysql.sock","user","CLDLRTa9") or die(mysql_error());
//$db=mysql_connect("localhost:/tmp/mysql.sock","user","CLDLRTa9") or die(mysql_error());	//for prodjs
//$db=mysql_connect("172.16.3.182","user","CLDLRTa9") or die (mysql_error());
//$db=mysql_connect("172.16.3.182:3308","user","CLDLRTa9") or die (mysql_error());
$db=mysql_connect("localhost","user","CLDLRTa9") or die (mysql_error());	//for 259
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$Developer_Token = "zT7sAKIbbXRFNZWe9uIBWA";
$Application_Token = "kIjlB_mJV4hfEQxlUe0S3A";

//jeevansathi starts//
//new account added
$Email[] = "nri@jeevansathi.com";
$Password[] = "123nrius456";
$Client_Email[] = "nri@jeevansathi.com";
$customerId[] = '9051013090';
$database[]='adwords_jeevansathi';

$Email[] = "geetu.ahuja_jsnri@naukri.com";
$Password[] = "geetunri";
$Client_Email[] = "geetu.ahuja_jsnri@naukri.com";
$customerId[] = 7715469394;
$database[]='adwords_jeevansathi';

//dollars //deleted account below one
/*
$Email[] = "geetu.ahuja@naukri.com";
$Password[] = "2007_matrimony";
$Client_Email[] = "geetu.ahuja@naukri.com";
$customerId[] = 9662096480;
$database[]='adwords_jeevansathi';
*/

$Email[] = "madhurima.js@naukri.com";
$Password[] = "gr8possibilities";
$Client_Email[] = "madhurima.js@naukri.com";
$customerId[] = 9223213839;
$database[]='adwords_jeevansathi';
//jeevansathi ends//



//99acres.com starts
$Email[] = "Geetu.ahuja@99acres.com";
$Password[] = "123highjumper";
$Client_Email[] = "Geetu.ahuja@99acres.com";
$customerId[] = 9257994868;
$database[]='adwords_99acres';

$Email[] = "praveen.kodur@99acres.com";
$Password[] = "99realestate";
$Client_Email[] = "praveen.kodur@99acres.com";
$customerId[] = 1486549633;
$database[]='adwords_99acres';
//99acres.com ends


//naukri starts
$Email[] = "Manjari.chauhan@naukri.com";
$Password[] = "infoedgebest123";
$Client_Email[] = "Manjari.chauhan@naukri.com";
$customerId[] = 1439047128;
$database[]='adwords_naukri';

$Email[] = "Madhurima.sil@naukri.com";
$Password[] = "infoedgebest123";
$Client_Email[] = "Madhurima.sil@naukri.com";
$customerId[] = 3921503389;
$database[]='adwords_naukri';

$Email[] = "naukri.new@naukri.com";
$Password[] = "allinone789";
$Client_Email[] = "naukri.new@naukri.com";
$customerId[] = 7879621245;
$database[]='adwords_naukri';

$Email[] = "Naukri.top@naukri.com";
$Password[] = "job456seek";
$Client_Email[] = "Naukri.top@naukri.com";
$customerId[] = 2633795937;
$database[]='adwords_naukri';

$Email[] = "geetu.ahuja+content@naukri.com"; //new added
$Password[] = "infoedgebest123";
$Client_Email[] = "geetu.ahuja+content@naukri.com";
$customerId[] ="9724327282"; 
$database[]='adwords_naukri';
//naukri ends

?>
