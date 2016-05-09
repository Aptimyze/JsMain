<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");

	$db = connect_db();

 	$sqlj="SELECT PROFILEID FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME = '0000-00-00 00:00:00'";
	$resj=mysql_query($sqlj,$db) or die(mysql_error($db)); 
	while($rowj = mysql_fetch_array($resj))
                $profileid_arr[] = $rowj['PROFILEID'];
	echo count($profileid_arr);die;
	for($i=0;$i<count($profileid_arr);$i++)
	{
		$profileid = $profileid_arr[$i];
		put_allot_time($profileid);
	}


	function put_allot_time($profileid)
        {
		global $db;
		$sql4="select ALLOT_TIME from incentive.MANUAL_ALLOT where PROFILEID='$profileid' AND COMMENTS='inactive 45 days profile'";
		$res4=mysql_query($sql4,$db) or die("$sql4".mysql_error($db));
		if($row4 = mysql_fetch_array($res4))
			$allot_time = $row4['ALLOT_TIME'];
		$sql2="update incentive.CRM_DAILY_ALLOT set ALLOT_TIME='$allot_time' where PROFILEID='$profileid' AND ALLOT_TIME='0000-00-00 00:00:00'";
		mysql_query($sql2,$db) or die("$sql2".mysql_error($db));

        }
?>
