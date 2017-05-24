<?php
$flag_using_php5=1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db=connect_db();

	//$todayDate	=date("Y-m-d");
	//$last15Days     =date("Y-m-d H:i:s",strtotime("$todayDate -15 days"));

	$sql_phone ="select PROFILEID from MIS.FTO_ACTIVITY_INFO";
	$phone_res =mysql_query_decide($sql_phone,$db) or die("$sql_phone".mysql_error_js());
	while($phone_row=mysql_fetch_array($phone_res))
	{
		$pid_phone              =$phone_row['PROFILEID'];

		$i=0;
		$sqlInfo ="select ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID='$pid_phone' ORDER BY ENTRY_DT DESC limit 2";
		$resInfo =mysql_query_decide($sqlInfo,$db) or die("$sqlInfo".mysql_error_js());
		while($rowInfo=mysql_fetch_array($resInfo))
		{	
			$phoneVeifyDt[$i] =$rowInfo['ENTRY_DT'];
			if(!$i){
				$i++;	
				continue;
			}
			$sqlFto ="select PROFILEID from FTO.FTO_STATE_LOG WHERE PROFILEID='$pid_phone' AND STATE_ID IN(1,3) AND ENTRY_DATE>'$phoneVeifyDt[1]' AND ENTRY_DATE<'$phoneVeifyDt[0]' ORDER BY ENTRY_DATE DESC limit 1";
			$resFto =mysql_query_decide($sqlFto,$db) or die("$sqlFto".mysql_error_js());
			$rowFto=mysql_fetch_array($resFto);
			if(!$rowFto['PROFILEID'])
			{
				$sql ="update MIS.FTO_ACTIVITY_INFO SET PHONE_VERIFY_DT='$phoneVeifyDt[1]' WHERE PROFILEID='$pid_phone'";
				mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			}
		}
		unset($sqlUpdateParamArr);
	}
	

?>
