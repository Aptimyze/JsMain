<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : ats_oneTimeUpdate.php
* DESCRIPTION   : Cron script, One time scheduled
		: Updates the PROFILEID and VISITED_SITE columns in ATS table for old records
		: 3 defined sites considered here: Bharat Matrimony(BM), Shaadi(SH), Simply Marry(SM) 
****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");

$db = connect_db();
$db_slave = connect_slave();

/* Cluster of memberhsip pages for all the defined 3 sites */
$shArr=array("1","2","701");
$bmArr=array("457","458","459","460","461","462","463","464","465","466","467","468","469","509","510","511","512","513","514","515","516","517","518","519","520","521");
$smArr=array("456");

// Query to find the cluster of non membership pages for the 3 defined sites
$sql_ats_max="SELECT ID,GRP FROM MIS.ATS_URL WHERE GRP IN('SH','SM','BM')";
$res_ats_max=mysql_query($sql_ats_max,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ats_max,"ShowErrTemplate");
while($myrow_ats_max=mysql_fetch_array($res_ats_max))
{
	$id=$myrow_ats_max['ID'];
        $group=$myrow_ats_max['GRP'];

        if($group=='SH')
        	$sh_array_nonmem[]=$id;
        elseif($group=='BM')
        	$bm_array_nonmem[]=$id;
        elseif($group=='SM')
        	$sm_array_nonmem[]=$id;
}

/* Query to find the usernames from  ATS table  
   Update the PROFILEID,VISITED_SITE columns of ATS table
*/
$sql ="SELECT USERNAME,VISITED_URL FROM MIS.ATS_LOGGER_1 WHERE USERNAME!=''";
$res =mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
	$username 	=$row['USERNAME'];
	$visitedUrl	=$row['VISITED_URL'];

	$visitedUrlArr =explode("-",$visitedUrl);	
	$visitedUrlArrCnt =count($visitedUrlArr);
	$shArrCnt =count($shArr);
	$bmArrCnt =count($bmArr);
	$smArrCnt =count($smArr);
	
	$shMemPage 	=false;
	$smMemPage 	=false;
        $bmMemPage 	=false;
        $shPage 	=false;
        $smPage 	=false;
        $bmPage 	=false;

	// For visited membership pages
        for($i=0; $i<$visitedUrlArrCnt; $i++)
        {
		if(in_array($visitedUrlArr[$i],$shArr))
                	$shMemPage =true;

		if(in_array($visitedUrlArr[$i],$smArr))
                	$smMemPage =true;

		if(in_array($visitedUrlArr[$i],$bmArr))
                	$bmMemPage =true;
		
		if($shMemPage && $smMemPage && $bmMemPage)
			break;
	}

	// For visited non-membership pages
        for($i=0; $i<$visitedUrlArrCnt; $i++)
        {
                if(in_array($visitedUrlArr[$i],$sh_array_nonmem))
                        $shPage =true;

		if(in_array($visitedUrlArr[$i],$sm_array_nonmem))
                	$smPage =true;

		if(in_array($visitedUrlArr[$i],$bm_array_nonmem))
                	$bmPage =true;

		if($shPage && $smPage && $bmPage)
			break;
        }

	if($shMemPage)
		$visitedSiteArr[] ='SHP';
	if($smMemPage)
		$visitedSiteArr[] ='SMP';
        if($bmMemPage)
                $visitedSiteArr[] ='BMP';
        if($shPage)
                $visitedSiteArr[] ='SH';
        if($smPage)
                $visitedSiteArr[] ='SM';
        if($bmPage)
                $visitedSiteArr[] ='BM';

	$visitedSiteStr ='';
	if($visitedSiteArr)
		$visitedSiteStr =implode("-",$visitedSiteArr);
	unset($visitedSiteArr);
	unset($visitedUrlArr);	

	$sql1 ="select PROFILEID from newjs.JPROFILE WHERE USERNAME='$username'";
	$res1 =mysql_query($sql1,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	$row1=mysql_fetch_array($res1);
	$profileid =$row1['PROFILEID'];

	$sql2 ="update MIS.ATS SET PROFILEID='$profileid',VISITED_SITE='$visitedSiteStr' where USERNAME='$username' AND VISITED_URL='$visitedUrl'";
	mysql_query($sql2,$db) or logError("Due to a temporary problem your request could not be processed.");
}


?>
