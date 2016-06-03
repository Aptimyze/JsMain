<?php

$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");

include_once("connect.inc");
include_once("contact.inc");

$db=connect_db();

$mysqlObj= new Mysql;
$db=$mysqlObj->connect();

$data=authenticated($checksum);
$sender_profileid=$data["PROFILEID"];

$sql="select PROFILEID,SUBSCRIPTION FROM JPROFILE where  activatedKey=1 and USERNAME = '" .mysql_real_escape_string($username). "'";
$result=$mysqlObj->executeQuery($sql,$db);
$myrow =$mysqlObj->fetchAssoc($result);	
$receiver_profileid=$myrow["PROFILEID"];
$profilechecksum=md5($receiver_profileid) . "i" . $receiver_profileid;

$lavesh=get_contact_status($sender_profileid,$receiver_profileid);

if( strstr($data["SUBSCRIPTION"],'F') || strstr($myrow["SUBSCRIPTION"],'D')) 
	$flag="profilepage";
else
{
	if(!$lavesh || $lavesh=='I')
		$flag='contact';
	else
		$flag="profilepage";
}
		
if($flag=='contact')
{
	$pr_view=1;
	$CMDsubmit='Express Interest - Free';
	$countlogic=1;
	$CURRENTUSERNAME=$data["CURRENTUSERNAME"];
	if($CURRENTUSERNAME)
		$CURRENTUSERNAME=$USERNAME_RECEIVER;	
	
	$logic_used=$_GET["logic_used"];
	$recomending=$_GET["recomending"];
	$is_user_active=$_GET["is_user_active"];
	
	header("Location: $SITE_URL/profile/single_contact_aj.php?logic_used=$logic_used&recomending=$recomending&is_user_active=$is_user_active&profilechecksum=$profilechecksum&CMDsubmit=$CMDsubmit&pr_view=$pr_view&countlogic=$countlogic&CURRENTUSERNAME=$USERNAME_RECEIVER&status=I&checksum=$checksum");
}
else
{
	foreach ($_GET as $var => $value)
			$passingvar.="$var=$value".'&';
	header("Location: $SITE_URL/profile/viewprofile.php?$passingvar&matchalertlogin=1");	
}
?>
