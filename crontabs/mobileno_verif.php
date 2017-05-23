<?php
/**	
	Checks the table MIS.MOB_VERIFY and marks the mobile numbers with 3 continuous failed messages as INVALID
	The 3 most recent msg delivery status are considered for each mobile no 
*/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

/*
$path=realpath(dirname(__FILE__));
$position=stripos($path,"profile");
$includePath=substr($path,0,$position-1);
*/
$includePath=$docRoot."/web";
include($includePath."/sugarcrm/custom/crons/housekeepingConfig.php");
include($includePath."/sugarcrm/include/utils/systemProcessUsersConfig.php");

global $partitionsArray;
global $process_user_mapping;

$processUserId=$process_user_mapping["invalid_mobile_no"];
if(!$processUserId)
        $processUserId='1';

$updateTime=date("Y-m-d H:i:s");

$mysqlObj=new Mysql;
$dbM=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbM);

$date=date("Y-m-d");
/*
$prev_date = strtotime ( '-1 day' , strtotime ( $date ) ) ;
$prev_date = date ( 'Y-m-j' , $prev_date );
*/

//$sql1="SELECT DISTINCT MOBILE_NO FROM MIS.MOB_VERIFY WHERE INVALID='Y' AND DATE_OF_VERIF = '$prev_date'";
$sql1="SELECT DISTINCT MOBILE_NO FROM MIS.MOB_VERIFY WHERE INVALID='Y' AND DATE_OF_VERIF = '$date'";
$res1 = mysql_query($sql1,$dbM) or die(mysql_error($dbM));

while($row1=mysql_fetch_array($res1))
	$mob[]=$row1['MOBILE_NO'];

if(is_array($mob))
{
	foreach($mob as $val)
	{
		unset($valid);
		unset($cnt);
		$sql2="SELECT INVALID FROM MIS.MOB_VERIFY WHERE MOBILE_NO='$val' ORDER BY DATE_OF_ENTRY DESC LIMIT 3";
		$res2 = mysql_query($sql2,$dbM) or die(mysql_error($dbM));
		while($row2=mysql_fetch_array($res2))
		{
			if($row2['INVALID']!='Y')
				$valid=1;
			$cnt++;
		}
		if(!$valid && $cnt==3)
			$inv_nos[]=$val;
	} 
}
if(is_array($inv_nos))
{
	foreach($inv_nos as $val)
	{
		$sql3="INSERT ignore INTO MIS.INVALID_NOS(MOBILE_NO,DATE) VALUES('$val','$date')"; 
		$res3 = mysql_query($sql3,$dbM) or die(mysql_error($dbM));
 
		//Marking leads with this number as number invalid - Trac 410
		if(strlen($val)>10)
			$leadCheck=ltrim($val,"91");
		else
			$leadCheck=$val;
		$sql3="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='17',disposition_c='18',modified_user_id='$processUserId',date_modified='$updateTime' WHERE phone_mobile IN ('0$leadCheck','91$leadCheck','$leadCheck') AND deleted!='1' AND id=id_c";
		mysql_query($sql3,$dbM);
	
		$sql3="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='17',disposition_c='18',modified_user_id='$processUserId',date_modified='$updateTime' WHERE enquirer_mobile_no_c IN ('0$leadCheck','91$leadCheck','$leadCheck') AND deleted!='1' AND id=id_c";
                mysql_query($sql3,$dbM);

		foreach($partitionsArray as $partition=>$partitionArray)
		{
			$leadsTable="sugarcrm_housekeeping.".$partition."_leads";
                        $leadsCstmTable="sugarcrm_housekeeping.".$partition."_leads_cstm";
			$sql3="UPDATE $leadsTable,$leadsCstmTable SET status='17',disposition_c='18',modified_user_id='$processUserId',date_modified='$updateTime' WHERE phone_mobile IN ('0$leadCheck','91$leadCheck','$leadCheck') AND deleted!='1' AND id=id_c";
		        mysql_query($sql3,$dbM);
			$sql3="UPDATE $leadsTable,$leadsCstmTable SET status='17',disposition_c='18',modified_user_id='$processUserId',date_modified='$updateTime' WHERE enquirer_mobile_no_c IN ('0$leadCheck','91$leadCheck','$leadCheck') AND deleted!='1' AND id=id_c";
                        mysql_query($sql3,$dbM);
		}
	}
}
?>
