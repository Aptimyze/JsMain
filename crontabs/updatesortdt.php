<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*************************************************************************************************************************//*
        *       Created By         :    Shobha Kumari
        *       Created On   	   :    11.11.2005
        *       Description        :    This file update the sort date for paid members to today's date ; this is done in or					    der to push paid members on top (in case of search results).
        *       Includes/Libraries :    connect.inc
***************************************************************************************************************************/
include_once('connect.inc');

$db=connect_db();

$dt = date("Y-m-d");

// query to find the profile id of paid members
$sql = "SELECT DISTINCT PROFILEID  FROM billing.SERVICE_STATUS WHERE ACTIVATED = 'Y' AND EXPIRY_DT >='$dt'";
if($result= mysql_query($sql))
{
	while($row = mysql_fetch_array($result))
	{
		$profileid = $row["PROFILEID"];

		// query to update the SORT_DT to the date when script is executed

		$sql = "UPDATE newjs.JPROFILE SET SORT_DT ='$dt' WHERE PROFILEID ='$profileid'";
		$res= mysql_query($sql) or die("$sql".mysql_error());//logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	}
}
else
{
	die("$sql".mysql_error());//logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
}
?>
