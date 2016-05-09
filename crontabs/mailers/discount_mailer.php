<?php

/************************************************************************************************************************
* FILENAME           : discount_mailer.php
* DESCRIPTION        : Mail will be send to the User who have been in the system for more than 150 days and have less than 350 points score.The mail will contain Unique code for that too.
* CREATED BY         : Anurag Gautam
* Date               : 30th August 2008
***********************************************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db = connect_slave();
$db2 = connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$smarty->relative_dir="mailer/"; 

$from='info@jeevansathi.com';
$sub='40% Discount on Jeevansathi.com';

$sql2="SELECT CODE FROM newjs.DISCOUNT_CODE WHERE NAME_OF_CODE IN ('40% discount3')";
$res2=mysql_query($sql2,$db) or die(mysql_error1($db));
while($row=mysql_fetch_array($res2))
{	
	$code_arr[] = $row['CODE'];
}

$sql="SELECT PROFILE_ID FROM mailer.DISCOUNT_MAILER WHERE SENT_MAIL='N'";
$res= mysql_query($sql,$db) or die(mysql_error1($db));
$i=0;

while($row=mysql_fetch_array($res))
{
	$id=$row['PROFILE_ID'];
	$sql1="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$id'";
	$res1=mysql_query($sql1,$db) or die(mysql_error1($db));
        $row=mysql_fetch_array($res1);
        $email=$row['EMAIL'];

    	if($email)
	   {
	   	$code = $code_arr[$i];
		$smarty->assign('CODE',$code);
		$msg=$smarty->fetch('discount_mailer.htm');
		//echo $msg;
	        send_email($email,$msg,$sub,$from);
	 	$sql="UPDATE mailer.DISCOUNT_MAILER SET SENT_MAIL='Y',CODE='$code' WHERE PROFILE_ID='$id'" ;
	        mysql_query($sql,$db2) or die(mysql_error1($db2));
		$i++;

           } 

}

mail("Anurag.Gautam@jeevansathi.com","Script discount_mailer.php ran successfully", date("Y-m-d"));


  function mysql_error1($db)
{
	mail("Anurag.Gautam@jeevansathi.com","Error in discount_ mailer.php ",mysql_error($db));
}


?>
