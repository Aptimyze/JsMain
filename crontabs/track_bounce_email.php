<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/********************************************************************************************************************
Filename    : track_bounce_email.php
Description : Track the bounced email id list. 
Created By  : Vibhor Garg
Created On  : 04 Sep 2008
********************************************************************************************************************/
ini_set('memory_limit',-1);

include_once("connect.inc");
$db_select=connect_bouncelog();

$ts = time()-2*86400;
$mod_dt=date("Y-m-d",$ts);

$sql="select email_id,ecelerity_Errcode from bouncelog.bouncelog_js where modified_date >= '$mod_dt'";
$result=mysql_query($sql,$db_select) or die(mysql_error($db_select));
$db_update=connect_db();
while($myrow=mysql_fetch_array($result))
{
	$email=$myrow["email_id"];
	$ecelerity_Errcode=$myrow["ecelerity_Errcode"];
	if($ecelerity_Errcode == '10' || $ecelerity_Errcode == '1')
	{
	        $sql="insert ignore into bounces.BOUNCED_MAILS (EMAIL) VALUES ('" . mysql_real_escape_string($email) . "')";
        	mysql_query($sql,$db_update) or die(mysql_error($db_update));
	}
	else
	{
		$sql="delete from bounces.BOUNCED_MAILS where EMAIL='" . mysql_real_escape_string($email) . "'";
	        mysql_query($sql,$db_update) or die(mysql_error($db_update));
	}
}
?>
