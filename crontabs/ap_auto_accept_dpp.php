<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

$db=connect_db();

$sql="SELECT SE,DPP_ID,ONLINE,CREATED_BY,AP_DPP_FILTER_ARCHIVE.STATUS,AP_DPP_FILTER_ARCHIVE.PROFILEID,DATEDIFF(NOW(),DATE) AS DIFF,DATE,AP_PROFILE_INFO.STATUS AS PROFILE_STATUS,3 AS DAYS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE JOIN Assisted_Product.AP_PROFILE_INFO ON AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_PROFILE_INFO.PROFILEID WHERE DATEDIFF(NOW(),DATE)>=3 AND AP_DPP_FILTER_ARCHIVE.STATUS='SE' AND AP_PROFILE_INFO.STATUS!='LIVE' AND AP_PROFILE_INFO.SE!='default.se' UNION SELECT SE,DPP_ID,ONLINE,CREATED_BY,AP_DPP_FILTER_ARCHIVE.STATUS,AP_DPP_FILTER_ARCHIVE.PROFILEID,DATEDIFF(NOW(),DATE) AS DIFF,DATE,AP_PROFILE_INFO.STATUS AS PROFILE_STATUS,1 AS DAYS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE JOIN Assisted_Product.AP_PROFILE_INFO ON AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_PROFILE_INFO.PROFILEID WHERE DATEDIFF(NOW(),DATE)>=1 AND AP_DPP_FILTER_ARCHIVE.STATUS='SE' AND AP_PROFILE_INFO.SE='default.se' UNION SELECT SE,DPP_ID,ONLINE,CREATED_BY,AP_DPP_FILTER_ARCHIVE.STATUS,AP_DPP_FILTER_ARCHIVE.PROFILEID,DATEDIFF(NOW(),DATE) AS DIFF,DATE,AP_PROFILE_INFO.STATUS AS PROFILE_STATUS,1 AS DAYS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE JOIN Assisted_Product.AP_PROFILE_INFO ON AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_PROFILE_INFO.PROFILEID WHERE DATEDIFF(NOW(),DATE)>=1 AND AP_DPP_FILTER_ARCHIVE.STATUS='SE' AND AP_PROFILE_INFO.SE!='default.se' AND AP_PROFILE_INFO.STATUS='LIVE'";
$res=mysql_query_decide($sql,$db) or die("Error while fetching profiles   ".$sql."  ".mysql_error($db));
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		$startDate=$row["DATE"];
		$sqlCenter="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$row[SE]'";
		$resCenter=mysql_query_decide($sqlCenter,$db) or die("Error while fetching center   ".$sqlCenter."  ".mysql_error($db));
		$rowCenter=mysql_fetch_assoc($resCenter);
		$sqlCount="SELECT COUNT(*) AS COUNT FROM jsadmin.HOLIDAY WHERE DATE>DATE('$startDate') AND DATE<=CURDATE()";
		if($rowCenter["CENTER"])
			$sqlCount.=" AND BRANCH='$rowCenter[CENTER]'";
		$resCount=mysql_query_decide($sqlCount,$db) or die("Error while calculating holidays in between   ".$sqlCount."  ".mysql_error($db));
		$rowCount=mysql_fetch_assoc($resCount);
		if($row["DIFF"]-$rowCount["COUNT"]>=$row["DAYS"])
		{
			$dppArray[]=array("PROFILEID"=>$row["PROFILEID"],
					"DPP_ID"=>$row["DPP_ID"],
					"ONLINE"=>$row["ONLINE"],
					"CREATED_BY"=>$row["CREATED_BY"],
					"STATUS"=>$row["STATUS"],
					"PROFILE_STATUS"=>$row["PROFILE_STATUS"]);
		}
	}
	if(is_array($dppArray))
	{
		foreach($dppArray as $key=>$value)
		{
			makeDPPLive($value["PROFILEID"],$value["DPP_ID"],"AUTO_ACCEPT_DPP",$value["CREATED_BY"],$value["ONLINE"],$value["STATUS"]);
			if($value["PROFILE_STATUS"]!="LIVE")
				makeProfileLive($value["PROFILEID"]);
		}
	}
}
mail('nikhil.dhiman@jeevansathi.com','ap_auto_accept_dpp',date("y-m-d"));
?>
