<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");

$db2=connect_db();
$db=connect_slave();

	//************************************    Condition after submit state  ***************************************
		$duration1	=date("Y-m-d H:i:s");
		$recordsCnt 	=5;
		$typeArr 	=array("REG","EMAIL","ADDR","PHONE","ACT","PHOTO");

		// Other Verification Block
		$sql ="select EXECUTIVE,PROFILEID,count(*) as CNT from MIS.LTF WHERE TYPE IN('EMAIL','ADDR','PHOTO','ACT','REG') GROUP BY PROFILEID HAVING CNT<'$recordsCnt' ORDER BY DATE DESC";
		$sql_res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());	 
		while($row =mysql_fetch_array($sql_res))
		{
			$pid =$row['PROFILEID'];		
			$exec_name =$row['EXECUTIVE'];

			// Email verified 							
			$sql_email ="SELECT ENTRY_DT from newjs.VERIFY_EMAIL WHERE STATUS='Y' AND PROFILEID='$pid'";
			$email_res =mysql_query_decide($sql_email,$db) or die("$sql_email".mysql_error_js());
                        if($email_row =mysql_fetch_array($email_res))
			{
				$email_dt  =$email_row['ENTRY_DT'];
				$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$pid','$exec_name','$typeArr[1]','$email_dt')";
				mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());	
			}
						
			// Address verified
			$sql_addr ="SELECT DATE from jsadmin.ADDRESS_VERIFICATION WHERE SCREENED='Y' AND PROFILEID=$pid";
			$addr_res =mysql_query_decide($sql_addr,$db) or die("$sql_addr".mysql_error_js());
			if($addr_row=mysql_fetch_array($addr_res))
			{
				$addr_dt =$addr_row['DATE'];
				$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$pid','$exec_name','$typeArr[2]','$addr_dt')";
				mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
			}	

			// Activated Profiles
			$sql_act ="SELECT SCREENED_TIME from jsadmin.SCREENING_LOG WHERE PROFILEID='$pid' ORDER BY ID ASC LIMIT 1";
			$act_res =mysql_query_decide($sql_act,$db) or die("$sql_act".mysql_error_js());
			if($act_row=mysql_fetch_array($act_res))
			{
				$screenTime =$act_row['SCREENED_TIME'];
                        	$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$pid','$exec_name','$typeArr[4]','$screenTime')";
                                mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
					
			}

                        // Photo condition
                        $sql_photo ="select SUBMITED_TIME from jsadmin.MAIN_ADMIN_LOG where SCREENING_TYPE='P' and STATUS ='APPROVED' and PROFILEID='$pid' ORDER BY ID ASC LIMIT 1";
                        $photo_res =mysql_query_decide($sql_photo,$db) or die("$sql_photo".mysql_error_js());
                        if($photo_row=mysql_fetch_array($photo_res))
			{
                        	$submitTime =$photo_row['SUBMITED_TIME'];
                        	$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$pid','$exec_name','$typeArr[5]','$submitTime')";
                        	mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
                      	} 
		} 
		//while loop condition ends
		$duration2 =date("Y-m-d H:i:s");

mail("manoj.rana@naukri.com","LTF OneTime Cron", "$duration1 # $duration2");


?>
