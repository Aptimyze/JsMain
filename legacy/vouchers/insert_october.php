<?php
include("../profile/connect.inc");

$db=connect_db();

//Fobaz
for($i=1;$i<=30000;$i++)
{
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FOB55','JEEVANSATHIOFFER','E','2008-01-18')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}


//Right Florists
$temp="RF-JS-";
for($i=1001;$i<=19001;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('RIG57','$number','E','2008-01-18')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Exciting Lives
$temp="2110";
for($i=7001;$i<=9999;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('EXC58','$number','E','2008-01-18')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="211";
for($i=10000;$i<=25000;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('EXC58','$number','E','2008-01-18')";	
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Sparkles
for($i=1;$i<=18000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SPA59','SJ6708','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Yourdesignerwear.com
for($i=1;$i<=18000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('YOU60','YDW1001','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Paridhanlok.com
$temp="PAR0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";        
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PAR61','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PAR000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PAR61','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PAR00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PAR61','$number','E','2008-01-18')";        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PAR0";
for($i=1000;$i<=9999;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PAR61','$number','E','2008-01-18')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PAR";
for($i=10000;$i<=18000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PAR61','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//mall4all.com
for($i=1;$i<=18000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MAL62','M4A0907','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//My Photo Store
$temp="MY0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MY63','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="MY000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MY63','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="MY00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MY63','$number','E','2008-04-18')";        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="MY0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MY63','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="MY";
for($i=10000;$i<=60000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MY63','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Johareez
for($i=1;$i<=10000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('JOH65','JZJVO2PC','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Wedding Store
$temp="WED0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('WED66','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="WED000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('WED66','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="WED00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('WED66','$number','E','2008-04-18')";        
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="WED0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('WED66','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="WED";
for($i=10000;$i<=60000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('WED66','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Flower Plaza
$temp="JS0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO68','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO68','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO68','$number','E','2008-01-18')";        
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO68','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS";
for($i=10000;$i<=10000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO68','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//CatchFlix
for($i=1;$i<=60000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('CAT69','JEESATH346','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//AB Jewels
$temp="AB0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('AB70','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="AB000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('AB70','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="AB00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('AB70','$number','E','2008-01-18')";        
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="AB0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('AB70','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="AB";
for($i=10000;$i<=30000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('AB70','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Shaadionline.com
$temp="SOL107000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SHA72','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SOL10700";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SHA72','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SOL1070";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SHA72','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SOL107";
for($i=1000;$i<=5000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SHA72','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Gyans
$temp="GYA0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('GYA73','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="GYA000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('GYA73','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="GYA00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('GYA73','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="GYA0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('GYA73','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="GYA";
for($i=10000;$i<=30000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('GYA73','$number','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Itasveer.com
for($i=1;$i<=40000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('ITA74','ITV20OFF','E','2008-02-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Printcamp.com
for($i=1;$i<=30000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('PRI75','PCJS15D0710','E','2008-01-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Moviemart.in
$temp="JS0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MOV76','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MOV76','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MOV76','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MOV76','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="JS";
for($i=10000;$i<=10000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('MOV76','$number','E','2008-04-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Utsavsarees.com
for($i=1;$i<=20000;$i++)
{
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('UTA77','JEEVAN','E','2007-12-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Flowerciti.com
$temp="FC0000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO78','$number','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="FC000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO78','$number','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="FC00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO78','$number','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="FC0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO78','$number','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="FC";
for($i=10000;$i<=10000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FLO78','$number','E','2007-11-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}

//Health n Wellness
$temp="HEA00000";
for($i=1;$i<=9;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="HEA0000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="HEA000";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="HEA00";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="HEA0";
for($i=10000;$i<=99999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="HEA";
for($i=100000;$i<=120000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('HEA81','$number','E','2008-10-18')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}



?>
