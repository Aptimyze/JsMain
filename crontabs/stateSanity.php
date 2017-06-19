<?php
include_once "connect.inc";
connect_db();
// bug 1676
//reverting all profiles which were once marked dplicate to duplicate , except those which were either marked non duplicate or which paid
$sqlA = "SELECT DISTINCT(PROFILEID) FROM FTO.FTO_STATE_LOG WHERE COMMENT IN ('MARK_NON_DUPLICATE','TAKE_MEMBERSHIP')";
$resA=mysql_query_decide($sqlA) or die($sqlA);
while($rowA = mysql_fetch_array($resA))
	$neglectedProfiles[] = $rowA['PROFILEID'];

$sqlB = "SELECT DISTINCT(PROFILEID) FROM FTO.FTO_CURRENT_STATE WHERE STATE_ID = '14'";
$resB=mysql_query_decide($sqlB) or die($sqlB);
while($rowB=mysql_fetch_array($resB))
	$neglectedProfiles[] = $rowB['PROFILEID'];
	
$neglectedProfilesStr = implode("','",$neglectedProfiles);
$neglectedProfilesStr = "'".$neglectedProfilesStr."'";

$sql ="SELECT PROFILEID,min(ENTRY_DATE) AS DATE FROM FTO.FTO_STATE_LOG WHERE COMMENT = 'MARK_DUPLICATE' AND PROFILEID NOT IN ( ".$neglectedProfilesStr." ) GROUP BY PROFILEID";
$res=mysql_query_decide($sql) or die($sql);
while($row=mysql_fetch_array($res))
	$profileDetails[$row['PROFILEID']] = $row['DATE'];
$profileids = array_keys($profileDetails);
$profiles = implode(",",$profileids);

$sqlUp = "UPDATE FTO.FTO_CURRENT_STATE SET `STATE_ID` = '14' WHERE PROFILEID IN (".$profiles.")";
$resUp=mysql_query_decide($sqlUp) or die(mysql_error());

foreach($profileDetails as $k =>$v)
{
	
//	$sqlX = "DELETE FROM FTO.FTO_STATE_LOG WHERE PROFILEID = ".$k."  AND ENTRY_DATE>'".$v."'";
//	$resX=mysql_query_decide($sqlX) or die($sqlX);
	$sqlX = "INSERT INTO FTO.FTO_STATE_LOG ( `ID` , `PROFILEID` , `STATE_ID` , `ENTRY_DATE` , `COMMENT` ) VALUES ('', '".$k."', '14', now(), 'BUG_FIX_1676')";
	$resX=mysql_query_decide($sqlX) or die($sqlX);
}
