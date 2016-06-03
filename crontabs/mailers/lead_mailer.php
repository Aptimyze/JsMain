<?php

/************************************************************************************************************************
  * FILENAME           : lead_mailer.php
  * DESCRIPTION        : Mail will be send to the User who are in the REG_LEAD Table for conversion of lead.
  * Mantis ID          : 4514
  * CREATED BY         : Anurag Gautam
  * Date               : 6th August 2009
  ***********************************************************************************************************************/

include("../profile/connect.inc");

$db_slave = connect_slave();
$db_master = connect_db();

$smarty->relative_dir="mailer/";

$from='info@jeevansathi.com';
$sub='Complete your registration to find the right life partner';

$sql="SELECT LEADID,EMAIL FROM MIS.REG_LEAD WHERE LEAD_CONVERSION='N' AND UNSUB_LEADMAIL!='Y' AND TYPE=''";
$res= mysql_query($sql,$db_slave) or die(mysql_error1($db_slave));

while($row=mysql_fetch_array($res))
{
	$leadid=$row['LEADID'];
	$email=$row['EMAIL'];
	
	if($email)
	{
		$smarty->assign('LEADID',$leadid);
 		$msg=$smarty->fetch('lead_mailer.htm');
		send_email($email,$msg,$sub,$from);
		$sql="UPDATE MIS.REG_LEAD SET SENT_MAIL='Y' WHERE EMAIL='$email'";
		mysql_query($sql,$db_master) or die(mysql_error1($db_master));
	}
}

mail("anurag.gautam@jeevansathi.com","Registration lead mailer ran successfully", date("Y-m-d"));

function mysql_error1($db)
{
	mail("anurag.gautam@jeevansathi.com","Error in Registration lead_mailer.php",mysql_error($db));
}

?>
