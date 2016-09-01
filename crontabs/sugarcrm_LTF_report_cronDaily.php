<?php
$flag_using_php5=1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include("$docRoot/crontabs/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
ini_set('max_execution_time',0);

$db=connect_db();
//$db_slave=connect_slave();
$db_slave =connect_slave111();
$mysqlObj=new Mysql;
mysql_query("set session wait_timeout=2000",$db);
	//************************************    Condition after submit state  ***************************************
	
	
		$todayDate      =date("Y-m-d H:i:s");
		$duration1	=$todayDate;
		$lastDuration   =date("Y-m-d",strtotime("$todayDate -3 days"));
		$recordsCnt 	=3;
		$typeArr 	=array("REG","EMAIL","ADDR","PHONE","ACT","PHOTO",'EOI');
		$regDurationSet =date("Y-m-d",strtotime("$todayDate -15 days"));

		// Activated Profiles
                $sql_act ="SELECT distinct(PROFILEID),SCREENED_TIME from jsadmin.SCREENING_LOG WHERE SCREENED_TIME>='$lastDuration 00:00:00' ORDER BY ID ASC";
                $act_res =mysql_query_decide($sql_act,$db_slave) or die("$sql_act".mysql_error_js());
                while($act_row=mysql_fetch_array($act_res))
                {
			$pid_act =$act_row['PROFILEID'];
			$screenTime =$act_row['SCREENED_TIME'];
			$sql ="select EXECUTIVE from MIS.LTF WHERE PROFILEID='$pid_act' limit 1";
			$res =mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
			if($row =mysql_fetch_array($res))
			{
				$exec_name =trim($row['EXECUTIVE']);
                        	$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid_act','$exec_name','$typeArr[4]','$screenTime',now())";
                        	mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());
			}
                }

                $duration2 =date("Y-m-d H:i:s");
                mail("manoj.rana@naukri.com","LTF Daily Cron - ACTIVATION", "$duration1 # $duration2");

		// Photo condition
		$sql_photo ="select distinct(PROFILEID),SUBMITED_TIME from jsadmin.MAIN_ADMIN_LOG where SCREENING_TYPE='P' AND STATUS LIKE '%APPROVED%' AND STATUS NOT LIKE '%EDITIING%' AND SUBMITED_TIME>='$lastDuration 00:00:00' ORDER BY ID ASC";
                $photo_res =mysql_query_decide($sql_photo,$db_slave) or die("$sql_photo".mysql_error_js());
                while($photo_row=mysql_fetch_array($photo_res))
                {
                        $pid_photo =$photo_row['PROFILEID'];
			$photoTime =$photo_row['SUBMITED_TIME'];
                        $sql ="select EXECUTIVE from MIS.LTF WHERE PROFILEID='$pid_photo' limit 1";
                        $res =mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                        if($row =mysql_fetch_array($res))
                        {
				$exec_name =trim($row['EXECUTIVE']);
                		$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid_photo','$exec_name','$typeArr[5]','$photoTime',now())";
                		mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());
                	}		
		}
		$duration3 =date("Y-m-d H:i:s");
		mail("manoj.rana@naukri.com","LTF Daily Cron - PHOTO", "$duration2 # $duration3");
	
		// Other Verification Block
		$sql ="select EXECUTIVE,PROFILEID,count(*) as CNT from MIS.LTF WHERE TYPE IN('EMAIL','ADDR','REG') GROUP BY PROFILEID HAVING CNT<'$recordsCnt' ORDER BY DATE DESC";
		$sql_res =mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());	 
		while($row =mysql_fetch_array($sql_res))
		{
			$pid =$row['PROFILEID'];		
			$exec_name =trim($row['EXECUTIVE']);

			// Email verified 							
			$sql_email ="SELECT ENTRY_DT from newjs.VERIFY_EMAIL WHERE STATUS='Y' AND PROFILEID='$pid'";
			$email_res =mysql_query_decide($sql_email,$db_slave) or die("$sql_email".mysql_error_js());
                        if($email_row =mysql_fetch_array($email_res))
			{
				$email_dt  =$email_row['ENTRY_DT'];
				$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid','$exec_name','$typeArr[1]','$email_dt',now())";
				mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());	
			}
						
			// Address verified
			$sql_addr ="SELECT DATE from jsadmin.ADDRESS_VERIFICATION WHERE SCREENED='Y' AND PROFILEID=$pid";
			$addr_res =mysql_query_decide($sql_addr,$db_slave) or die("$sql_addr".mysql_error_js());
			if($addr_row=mysql_fetch_array($addr_res))
			{
				$addr_dt =$addr_row['DATE'];
				$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid','$exec_name','$typeArr[2]','$addr_dt',now())";
				mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());
			}	
		}

                $duration4 =date("Y-m-d H:i:s");
                mail("manoj.rana@naukri.com","LTF Daily Cron - EMAIL_ADDRESS", "$duration3 # $duration4");

                // First EOI sent during 15 days of registration-Block
                $sql ="select EXECUTIVE,PROFILEID,DATE,count(*) as CNT from MIS.LTF WHERE TYPE IN('EOI','REG') GROUP BY PROFILEID HAVING CNT<'2' AND DATE>'$regDurationSet 00:00:00' ORDER BY DATE ASC";
                $sql_res =mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                while($row =mysql_fetch_array($sql_res))
                {
                        $pid 		=$row['PROFILEID'];
                        $exec_name 	=trim($row['EXECUTIVE']);
			$regDate 	=$row['DATE'];
			$regDurDate 	=date("Y-m-d",strtotime("$regDate +15 days"));
			$regDurDateSql=$regDurDate.' 00:00:00';
                        $myDbName=getProfileDatabaseConnectionName($pid,'slave',$mysqlObj);
                        $myDb=$mysqlObj->connect("$myDbName");
                        $pidShard=JsDbSharding::getShardNo($pid,'slave');
                        $dbMessageLogObj=new NEWJS_MESSAGE_LOG($pidShard);
                        $rowEoi=$dbMessageLogObj->sugarcrmCronSelectSender($pid,$regDate,$regDurDateSql);
			//$sqlEoi ="select DATE from newjs.MESSAGE_LOG WHERE SENDER='$pid' AND TYPE='I' AND DATE>='$regDate' AND DATE<'$regDurDate 00:00:00' ORDER BY DATE ASC LIMIT 1";
			//$resEoi =mysql_query_decide($sqlEoi,$myDb) or die("$sqlEoi".mysql_error_js());
			if($rowEoi){
				$eoiSendDate =$rowEoi['DATE'];
                                $sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid','$exec_name','$typeArr[6]','$eoiSendDate',now())";
                                mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());

			}
                }
                $duration6 =date("Y-m-d H:i:s");
                mail("manoj.rana@naukri.com","LTF Daily Cron - FIRST EOI SENT", "$duration4 # $duration6");

                // Phone Verification Block
                $sql_j ="SELECT PROFILEID,MOB_STATUS,LANDL_STATUS from newjs.JPROFILE where MOD_DT>='$lastDuration 00:00:00' AND MOD_DT<='$todayDate' AND activatedKey=1";
                $res_j =mysql_query_decide($sql_j,$db_slave) or die("$sql_j".mysql_error_js());
                while($row_j=mysql_fetch_array($res_j))
                {
                        $pid_ph =$row_j['PROFILEID'];
                        $sql_ph ="select EXECUTIVE from MIS.LTF WHERE PROFILEID='$pid_ph' limit 1";
                        $res_ph =mysql_query_decide($sql_ph,$db_slave) or die("$sql_ph".mysql_error_js());
                        if($row_ph =mysql_fetch_array($res_ph))
                        {
                                $exec_name_ph =trim($row_ph['EXECUTIVE']);
                                if($row_j['MOB_STATUS']=='Y' || $row_j['LANDL_STATUS']=='Y')
                                {
                                        $sql_phone ="SELECT ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE  PROFILEID='$pid_ph' ORDER BY ID DESC LIMIT 1";
                                        $phone_res =mysql_query_decide($sql_phone,$db_slave) or die("$sql_phone".mysql_error_js());
                                        $phone_row =mysql_fetch_array($phone_res);
                                        $phone_dt =$phone_row['ENTRY_DT'];

                                        $sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$pid_ph','$exec_name_ph','$typeArr[3]','$phone_dt',now())";
                                        mysql_query_decide($sql_ins,$db) or die("$sql_ins".mysql_error_js());
                                }
                                else
                                {
                                        $sql ="DELETE FROM MIS.LTF WHERE `PROFILEID`='$pid_ph' AND `TYPE`='PHONE'";
                                        mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                }
                        }
                }

		$duration5 =date("Y-m-d H:i:s");
		mail("manoj.rana@naukri.com","LTF Daily Cron - PHONE", "$duration4 # $duration5");

?>
