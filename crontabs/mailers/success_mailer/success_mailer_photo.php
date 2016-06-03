<?php

/************************************************************************************************************************
* FILENAME           : success_mailer_photo.php
* DESCRIPTION        : Mail will be send to the User who haven't already submitted photo for their success story.
* CREATED BY         : Anurag Gautam
* Date               : 29th August 2008
***********************************************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
//include_once("../profile/connect.inc");

$db= connect_slave();
$db2=connect_db();
$smarty->relative_dir="mailer/success_mailer/"; 

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$from='customerservice@jeevansathi.com';
$sub='Your success story needs a photo!';

$sql="SELECT USERNAME FROM mailer.SS_MAILER WHERE SENT_MAIL='N' AND PROFILE_TYPE='1'";
$res= mysql_query($sql,$db) or die(mysql_error($db,$sql));

while($row=mysql_fetch_array($res))
{
        $id=$row['USERNAME'];
	$sql1="SELECT EMAIL FROM newjs.JPROFILE WHERE USERNAME='$id'";
	$res1=mysql_query($sql1,$db) or die($sql1.mysql_error($db,$sql1));
        $row1=mysql_fetch_array($res1);

//        $username=$row['USERNAME'];
        $email=$row1['EMAIL'];

    	if($email)
	   {
		//$smarty->assign('USERNAME',$username);
		$msg=$smarty->fetch('success_mailer_photo.htm');
	        send_email($email,$msg,$sub,$from);
	 	$sql="UPDATE mailer.SS_MAILER SET SENT_MAIL='Y' WHERE USERNAME='$id'" ;
	        mysql_query($sql,$db2) or die(mysql_error($db2,$sql));

           } 

}

?>
