<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on daily basis	 
\**********************************************************************************/
//ini_set("memory_limit","64M");
include("../connect.inc");
connect_db();

$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='nidhisharma' AND CONVINCE_TIME=0 AND STATUS NOT IN ('F','C','P')";
$result=mysql_query($sql) or logError($sql);
$cnt= mysql_num_rows($result);
while($myrow=mysql_fetch_array($result))
{
		$proid[]=$myrow['PROFILEID'];
}
mysql_free_result($result);
$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME) AND UPPER(PSWRDS.CENTER)='PUNE'";
$result=mysql_query($sql) or logError($sql);
while($myrow1=mysql_fetch_array($result))
{
	$user[]=$myrow1['USERNAME'];
}
mysql_free_result($result);
$sql="SELECT VALUE from incentive.BRANCHES where 1";
$result=mysql_query($sql) or logError($sql);
while($myrow2=mysql_fetch_array($result))
{
	$cnt_proid=count($proid);	
	$cnt_user=count($user);
	$j=0;
	for($i=0;$i<$cnt_proid;$i++)
	{
		$proid_value=$proid[$i];
		$user_value=$user[$j];

		$sql="UPDATE incentive.MAIN_ADMIN set ALLOTED_TO='$user_value' WHERE PROFILEID='$proid_value'";
		mysql_query($sql) or logError($sql);
		$j=$j+1;
		if($j==$cnt_user)
			$j=0;
	}
}

mysql_free_result($result);
?>
