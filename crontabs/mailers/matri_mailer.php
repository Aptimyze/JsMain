<?php

/************************************************************************************************************************
* FILENAME           : matri_mailer.php
* DESCRIPTION        : Mail will be send to the User who Registerd there profile 7 Days back and written less than 200 words in Registration Page infrotn of About me Column && Registerd 7 days back and People who are older than 7 days and edited their profile today and have written less than 200 characters and haven't got the mail
* CREATED BY         : Anurag Gautam
* Date               : 25th August 2008
***********************************************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db = connect_slave();
$db2 = connect_db();

$smarty->relative_dir="mailer/"; 

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$from='matriprofile@jeevansathi.com';
$sub='Increase your profile views by 10 times';

$sql="SELECT PROFILE_ID FROM mailer.MATRI_MAILER WHERE SENT_MAIL='N'";
$res= mysql_query($sql,$db) or die(mysql_error1($db));

while($row=mysql_fetch_array($res))
{
	$id=$row['PROFILE_ID'];
	$sql1="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$id'";
	$res1=mysql_query($sql1,$db) or die(mysql_error1($db));
        $row=mysql_fetch_array($res1);

        $username=$row['USERNAME'];
        $email=$row['EMAIL'];

    	if($email)
	   {
		$smarty->assign('USERNAME',$username);
		$msg=$smarty->fetch('matri_profile.htm');
		//echo $msg;
	        send_email($email,$msg,$sub,$from);
	 	$sql="UPDATE mailer.MATRI_MAILER SET SENT_MAIL='Y' WHERE PROFILE_ID=$id" ;
	        mysql_query($sql,$db2) or die(mysql_error1($db2));

           } 

}

mail("anurag.gautam@jeevansathi.com","Script matri_mailer.php ran successfully", date("Y-m-d"));


  function mysql_error1($db)
{
	mail("anurag.gautam@jeevansathi.com","Error in matri mailer matri_mailer.php ",mysql_error($db));
}


?>
