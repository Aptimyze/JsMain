<?
/*********************************************************************************************
* FILE NAME     : chat_others_profile.php
* DESCRIPTION   : Derives Data of Profile for chat window
* CREATION DATE : 1st December, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include ("connect.inc");
$db=connect_db();
//$sendersid=136580;
//$sql="SELECT j.USERNAME,j.AGE, h.LABEL HEIGHT, c.LABEL CASTE, o.LABEL OCCUPATION FROM newjs.JPROFILE j, newjs.HEIGHT h, newjs.CASTE c, newjs.OCCUPATION o WHERE j.PROFILEID ='$sendersid' AND h.ID = j.HEIGHT AND j.CASTE = c.ID AND o.ID = j.OCCUPATION";
$sql="SELECT USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,MTONGUE,EDU_LEVEL,INCOME,GENDER FROM JPROFILE WHERE PROFILEID ='$sendersid' ";
$res=mysql_query_decide($sql,$db) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
if($row=mysql_fetch_array($res))
{
	$age_r=$row['AGE'];
        $username_r=$row['USERNAME'];
        $caste=$row['CASTE'];
        $occupation=$row['OCCUPATION'];
        $height=$row['HEIGHT'];
	$mtongue=$row['MTONGUE'];
	$degree=$row['DEGREE'];
	$income=$row['INCOME'];
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	$caste_r=$CASTE_DROP[$caste];
        $occupation_r=$OCCUPATION_DROP[$occupation];
        $height_r=$HEIGHT_DROP[$height];
	$sql="SELECT SQL_CACHE LABEL FROM MTONGUE WHERE ID ='$mtongue' ";
	$res=mysql_query_decide($sql,$db) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
	if($row=mysql_fetch_array($res))
	{
		$mtongue_r=$row['LABEL'];
	}
	$sql="SELECT SQL_CACHE LABEL FROM EDUCATION_LEVEL_NEW WHERE ID ='$degree' ";
	$res=mysql_query_decide($sql,$db) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
	if($row=mysql_fetch_array($res))
	{
		$degree_r=$row['LABEL'];
	}
	$sql="SELECT SQL_CACHE LABEL FROM INCOME where ID ='$income' ";
	$res=mysql_query_decide($sql,$db) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
	if($row=mysql_fetch_array($res))
	{
		$income_r=$row['LABEL'];
	}
	$flag=1;
	mysql_select_db_js('userplane');
	$sql="UPDATE userplane.SITE_CHAT_MIS SET COUNT=COUNT+1 WHERE DATE_SUB(CURDATE(),INTERVAL 0 DAY) = DAYZ";
	$res=mysql_query_decide($sql) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
	if(mysql_affected_rows_js()<1)
	{
		$sql="INSERT INTO userplane.SITE_CHAT_MIS(COUNT,DAYZ) values('1',now())";
		$res=mysql_query_decide($sql) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql_id);
	}
}
//mysql_close();
if($flag==1)
{
	$whattodisplay=$age_r.",".$height_r.",".$caste_r.",".$mtongue_r;
	if($degree_r)
	$whattodisplay.=",".$degree_r;
if($occupation_r)
	$whattodisplay.=",".$occupation_r;
if($gender=='M')
	$whattodisplay.=",".$income;
if($residence!="")
	$whattodisplay.=",".$residence;
	echo "<font style='font-size:12px' align=center>$whattodisplay</font>";
}
else
	echo "Click here to view Profile";
?>
