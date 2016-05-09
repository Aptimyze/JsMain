<?php

ini_set('max_execution_time',0 );
ini_set('memory_limit',-1);

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

$db = connect_slave();
$db2 = connect_db();
$i=0;

$sql="SELECT DISTINCT (A.PROFILE_ID) FROM DISCOUNT_MAILER AS A LEFT JOIN billing.PAYMENT_DETAIL AS B ON A.PROFILE_ID = B.PROFILEID WHERE B.STATUS = 'DONE'";
$res= mysql_query($sql,$db) or die(mysql_error($db));
while($row=mysql_fetch_array($res))
{
	$id=$row['PROFILE_ID'];

	$sql="DELETE FROM `DISCOUNT_MAILER_SMS` WHERE PROFILE_ID ='$id' AND SENT_SMS!='Y'";
	mysql_query($sql,$db2) or die(mysql_error($db2));

	$i++;
}

echo "--".$i;

?>
