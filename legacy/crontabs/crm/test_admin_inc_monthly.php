<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on monthly basis	 
\**********************************************************************************/

ini_set("max_execution_time","0");
include("../connect.inc");

$db=connect_db();

/*$sql="SELECT USERNAME FROM jsadmin.PSWRDS where PRIVILAGE LIKE '%IUO%' AND UPPER(CENTER)<>'NOIDA'";
$res=mysql_query($sql) or die("$sql".mysql_error());
while($row=mysql_fetch_array($res))
{
	$userarr[]=$row['USERNAME'];
}

$alloted_to=implode("','",$userarr);
$sql="DELETE FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN ('$alloted_to')";
mysql_query($sql) or die("$sql".mysql_error());

$sql="DELETE FROM incentive.MAIN_ADMIN WHERE STATUS NOT IN ('F','C','P')";*/
$sql="OPTIMIZE TABLE incentive.MAIN_ADMIN";
mysql_query($sql) or die("$sql".mysql_error());
?>
