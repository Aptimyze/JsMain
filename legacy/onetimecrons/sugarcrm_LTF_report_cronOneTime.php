<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("../connect.inc");

$db2=connect_db();
$db=connect_slave();

	//************************************    Condition after submit state  ***************************************
		$recordsCnt 	=6;
		$exec_id_arr	=array();
		$exec_name_arr	=array();
		$uname_arr	=array();
		$uname_str	='';
		$typeArr 	=array("REG","EMAIL","ADDR","PHONE","ACT","PHOTO");
	
                $sql_unames = "SELECT USERNAME,ACTIVE FROM jsadmin.PSWRDS";
                $res_unames = mysql_query_decide($sql_unames,$db) or die($sql_unames.mysql_error_js());
                while($row_unames = mysql_fetch_array($res_unames))
		{
			if($row_unames['ACTIVE']=='Y')
                        	$uname_arr[] = $row_unames['USERNAME'];
		}
		$uname_arr =array_unique($uname_arr);
                $uname_str = "'".@implode("','",$uname_arr)."'";

		$sql1 ="SELECT id,user_name from sugarcrm.users where user_name in($uname_str) and id!='1'";
		$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
		while($row1=mysql_fetch_array($res1))
		{
			$exec_id_arr[] =$row1['id'];
			$exec_name_arr[] =$row1['user_name'];
		}		
		$total_exec =count($exec_id_arr);

		for($i=0; $i<$total_exec; $i++)
		{			
			$exec_id 	=$exec_id_arr[$i];
			$exec_name	=$exec_name_arr[$i];
			$usernameArr 	=array();
			$newProfileArr	=array();
			$usernameStr 	='';

			// Connected database
                        $sql_con ="SELECT l_cstm.jsprofileid_c from sugarcrm_housekeeping.connected_leads_cstm l_cstm,sugarcrm_housekeeping.connected_leads l where l.id=l_cstm.id_c and l.assigned_user_id='$exec_id' AND l.status='26' AND l_cstm.jsprofileid_c!=''";
                        $res_con =mysql_query_decide($sql_con,$db) or die("$sql_con".mysql_error_js()); 
                        while($row_con=mysql_fetch_array($res_con))
                        {
                                $usernameArr[] =$row_con['jsprofileid_c'];
                        }
			$usernameArr =array_unique($usernameArr);		

			if(count($usernameArr)>0)
			{		
				$usernameStr = "'".@implode("','",$usernameArr)."'";
				//$usernameStr ='\'vikkujain\'';

				if($usernameStr)
				{	
					$sqlj ="SELECT PROFILEID,ENTRY_DT from newjs.JPROFILE where USERNAME IN($usernameStr)";
                        		$resj =mysql_query_decide($sqlj,$db) or die("$sqlj".mysql_error_js());
                        		while($rowj=mysql_fetch_array($resj))
                       			{ 
                			        $newProfileArr[$rowj['PROFILEID']] =$rowj['ENTRY_DT'];
                        		}
				}

				// Registration new				
				foreach($newProfileArr as $key=>$val)	
				{	
					$sql_ins="INSERT IGNORE into MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$key','$exec_name','$typeArr[0]','$val')";
					mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
				}
			}
		}

		$sql ="select EXECUTIVE,PROFILEID,count(*) as CNT from MIS.LTF GROUP BY PROFILEID HAVING CNT<'$recordsCnt'";
		$sql =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());	 
		while($row =mysql_fetch_array($sql))
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

			// Phone Verified
			$sql_phone ="SELECT ENTRY_DT from jsadmin.PHONE_VERIFIED_LOG WHERE  PROFILEID='$pid' ORDER BY ID ASC LIMIT 1";
                	$phone_res =mysql_query_decide($sql_phone,$db) or die("$sql_phone".mysql_error_js());
                	if($phone_row=mysql_fetch_array($phone_res))
			{
				$phone_dt =$phone_row['ENTRY_DT'];
				$sql_ins ="INSERT IGNORE INTO MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$pid','$exec_name','$typeArr[3]','$phone_dt')";
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
		} //while loop condition ends

?>
