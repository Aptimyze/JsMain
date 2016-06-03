<?
include "connect.inc";

$date=date("Y-m-d");
$sql="select MAILER_ID from MAIL_UNSUBSCRIBE where DATE='$date' and MAILER_ID='$mailer_id'";
$result=mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
if(mysql_num_rows($result)>0)
{
	$sql="UPDATE MAIL_UNSUBSCRIBE SET UN_COUNT=(UN_COUNT+1) WHERE MAILER_ID ='$mailer_id' AND DATE='$date'";
	mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
}
else
{
	$sql="INSERT INTO MAIL_UNSUBSCRIBE(DATE,MAILER_ID,UN_COUNT) VALUES('$date','$mailer_id',1)";
	mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
}

$sql = "SELECT MAILER_FOR,SUB_QUERY FROM mmmjs.MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
$res=mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
$row = mysql_fetch_array($res);
if($row['MAILER_FOR']=='J'){
	header("Location:http://www.jeevansathi.com/P/unsubscribe.php?checksum=&mail=Y");
}
else if($row['MAILER_FOR']=='9')
{
	header("Location: http://www.99acres.com/property/unsubscribe_me.php");
}

?>
