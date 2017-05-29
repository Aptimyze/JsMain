<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("../connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

connect_db();

$sql="SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND PAGE>1";
$res=mysql_query($sql) or logError($sql);
if($row=mysql_fetch_array($res))
{
        do
        {
                $profileid=$row['PROFILEID'];
		if($profileid)
		{
			$sql1="SELECT COUNT(*) AS CNT from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$res1=mysql_query($sql1) or die("$sql1".mysql_error());
			$row1=mysql_fetch_array($res1);
			if($row1['CNT']==0)
			{
				$sql_check="SELECT COUNT(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
				$res_check=mysql_query($sql_check) or logError($sql_check);
				$row_check=mysql_fetch_array($res_check);
				if($row_check['cnt']==0 && $profileid)
				{
					$sql="SELECT incentive.BRANCHES.NAME AS LABEL from newjs.JPROFILE, incentive.BRANCHES, incentive.BRANCH_CITY where PROFILEID='$profileid' and COUNTRY_RES=51 and newjs.JPROFILE.CITY_RES=incentive.BRANCH_CITY.VALUE AND incentive.BRANCHES.VALUE=incentive.BRANCH_CITY.NEAR_BRANCH AND left(incentive.BRANCH_CITY.PRIORITY,4)<>'UP25' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND SUBSCRIPTION=''";
				
					$result=mysql_query($sql) or logError($sql);                
					if($myrow=mysql_fetch_array($result))
					{
						$data_populate++;
						$city=$myrow['LABEL'];

						$sql="SELECT COUNT(*) as cnt FROM incentive.UNALLOTED_FAILED_PAYMENT WHERE PROFILEID='$profileid'";
						$res2=mysql_query($sql) or die(mysql_error().$sql);
						$row2=mysql_fetch_array($res2);
						if($row2['cnt'])
							$sql="UPDATE incentive.UNALLOTED_FAILED_PAYMENT SET CITY='$city', ALLOCATED='N' WHERE PROFILEID='$profileid'";
						else
							$sql="INSERT INTO incentive.UNALLOTED_FAILED_PAYMENT VALUES('','$profileid','$city','N','')";
						mysql_query($sql) or die(mysql_error().$sql);	
					}	
				}
			}
		}
        }while($row=mysql_fetch_array($res));
}

//Modified By lavesh on 1 sep 2006
send_email("shiv.narayan@jeevansathi.com"," $data_populate records Populated in incemtive.UNALLOTED_FAILED_PAYMENT","CRM-UNALLOTED FAILED PAYMENT",$from,'','');
?>
