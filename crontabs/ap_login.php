<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**************************************************************************************************************************
Filename    : ap_login.php
Description : To update the sort date and login date of assisted product users [4586]
Created On  : 21 December 2009
Created By  : Sadaf Alam
***************************************************************************************************************************/
chdir(dirname(__FILE__));
$flag_using_php5=1;
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$mysql=new Mysql;
$db=connect_db();
$objUpdate = JProfileUpdateLib::getInstance();
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName");
}

$sql="select DISTINCT(PROFILEID) from Assisted_Product.AP_PROFILE_INFO WHERE STATUS='LIVE'";
$res=mysql_query($sql,$db) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
       $profileid=$row['PROFILEID'];
       
	//$sql="update newjs.JPROFILE set SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>=SORT_DT,DATE_ADD(SORT_DT,INTERVAL 7 DAY),SORT_DT) where PROFILEID='$profileid'";
	//mysql_query($sql,$db) or logError($sql);

        $affectedRows = $objUpdate->updateSortDateForAPLogin($row[PROFILEID]);
		
	if($affectedRows)
	{
		$sqlup="SELECT SORT_DT FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $resup=mysql_query($sqlup,$db) or logError($sqlup);
                $rowup=mysql_fetch_assoc($resup);
		//$sqlup="update newjs.JPROFILE set LAST_LOGIN_DT='$rowup[SORT_DT]' where PROFILEID='$profileid'";
		//mysql_query($sqlup,$db) or logError($sqlup);
                $arrFields1 = array('LAST_LOGIN_DT'=>CommonUtility::makeTime($rowup[SORT_DT]));
                $objUpdate->editJPROFILE($arrFields1,$row[PROFILEID],"PROFILEID");
		$myDbName=getProfileDatabaseConnectionName($profileid);

		if(!$myDb[$myDbName])
                                $myDb[$myDbName]=$mysql->connect("$myDbName");

		$sqlup="INSERT IGNORE INTO newjs.LOGIN_HISTORY(PROFILEID,LOGIN_DT) VALUES('$profileid','$rowup[SORT_DT]')";
		$mysql->executeQuery($sqlup,$myDb[$myDbName]) or logError($sqlup);
		if($mysql->affectedRows()>0)
		{
			$sql="update newjs.LOGIN_HISTORY_COUNT  set TOTAL_COUNT=TOTAL_COUNT+1 where PROFILEID=".$profileid;
                        $mysql->executeQuery($sql,$myDb[$myDbName]) or logError($sql);

                        if($mysql->affectedRows()<=0)
                        {
                                $sql="replace into newjs.LOGIN_HISTORY_COUNT(PROFILEID,TOTAL_COUNT) values(".$profileid.",1)";
                                mysql_query($sql,$db);

                        }
		}
	}
	
}
mail('nikhil.dhiman@jeevansathi.com',' ap login ',date("y-m-d"));
?>
