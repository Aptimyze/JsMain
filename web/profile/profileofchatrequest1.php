<?php
/*********************************************************************************************
* FILE NAME     : profileofchatrequest.php
* DESCRIPTION   : Derives Data of Profile for chat window
* CREATION DATE : 1st December, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include ("../profile/connect.inc");
$db=connect_db();
//$sendersid=136580;
$sql="SELECT j.USERNAME,j.AGE, h.LABEL HEIGHT, c.LABEL CASTE, o.LABEL OCCUPATION FROM newjs.JPROFILE j, newjs.HEIGHT h, newjs.CASTE c, newjs.OCCUPATION o WHERE j.PROFILEID ='$sendersid' AND h.VALUE = j.HEIGHT AND j.CASTE = c.VALUE AND o.VALUE = j.OCCUPATION";
$res=mysql_query_decide($sql,$db) or logError("Error while getting profile details,messenger ".mysql_error_js(),$sql_id);
if($row=mysql_fetch_array($res))
{
	$age_r=$row['AGE'];
	$username_r=$row['USERNAME'];
	$caste_r=$row['CASTE'];
	$occupation_r=$row['OCCUPATION'];
	$height_r=$row['HEIGHT'];
	echo "&age_r=$age_r&username_r=$username_r&caste_r=$caste_r&occupation_r=$occupation_r&height_r=$height_r";
}
else
	echo "&age_r=$age_r&username_r=$username_r&caste_r=$caste_r&occupation_r=$occupation_r&height_r=$height_r";
?>
