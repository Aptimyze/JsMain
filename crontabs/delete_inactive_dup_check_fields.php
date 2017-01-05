<?php
$curFilePath = dirname(__FILE__)."/";

include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
$flag_using_php5=1;

include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php");

$dbM = connect_db();
$dbS = $dbM;
$day = date('Y-m-d', strtotime('-2 days'));

$sql_main="SELECT PROFILEID FROM duplicates.DUPLICATE_CHECKS_FIELDS";
$res_main = mysql_query($sql_main,$dbS) or die($sql_main.mysql_error($dbS));
//echo $sql_main;
while($row = mysql_fetch_array($res_main))
{
	/*
	if($lacs++%500==0)
	echo $lacs.":::";
	*/
	$pid = $row['PROFILEID'];
	$sql="SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID=$pid AND ENTRY_DT<'$day'";
	$res = mysql_query($sql,$dbM) or die($sql.mysql_error($dbM));
	$row1 = mysql_fetch_array($res);
	$act = $row1["ACTIVATED"];
	//$arr[$act]++;

	if($act=='N' || $act=='D')
	{
		$sql = "DELETE FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE PROFILEID='$pid'";
		mysql_query($sql,$dbM) or die($sql.mysql_error($dbM));
	}
}
SendMail::send_email('palashchordia@jeevansathi.com', 'delete_inactive_dup_check_fields', 'cron Alert', '', '', '', '', $this->emailAttachmentType, $this->emailAttachmentName, '', "1", $replyToAddress,$from_name);
//print_r($arr);
?>
