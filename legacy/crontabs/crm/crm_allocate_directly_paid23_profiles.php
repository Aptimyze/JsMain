<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	//include_once("/usr/local/scripts/connect_db.php");	//for testing
	include("allocate_functions_revamp.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");

	$db = connect_slave();
	$allot_to_array = array('dayanidhi');

        $last_day = date("Y-m-d",time()-30*24*60*60);
	$yesterday = date("Y-m-d",time()-86400);
	
 	$sqlj="SELECT DISTINCT(p.PROFILEID) FROM billing.PURCHASES p JOIN billing.SERVICE_STATUS s ON p.BILLID = s.BILLID WHERE STATUS = 'DONE' AND p.MEMBERSHIP='Y' AND ENTRY_DT >= '$yesterday 00:00:00' AND ENTRY_DT <= '$yesterday 23:59:59' AND s.SERVICEID IN ('P2','C2','P3','C3')";
	$resj=mysql_query($sqlj,$db) or die(mysql_error($db)); 
	while($rowj = mysql_fetch_array($resj))
                $profileid_arr[] = $rowj['PROFILEID'];
	if(count($profileid_arr)>1)
		$profileid_str = implode(",",$profileid_arr);
	else
		$profileid_str = $profileid_arr[0];
	
	if($profileid_str !='')
	{
		$sql_pid = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3 AND PROFILEID IN ($profileid_str)";
		$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
		$row_pid = mysql_fetch_array($res_pid);
		do
		{
			unset($allow);
			$profileid = $row_pid['PROFILEID'];
			if(!profile_allocated($profileid))
			{
				$mtongue = $row_pid['MTONGUE'];
				$score = $row_pid['SCORE'];

				$sql_history = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
				$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error($db));
				if($row_history = mysql_fetch_array($res_history))
				{
					// profile has been handled once
					if($row_history['ENTRY_DT']<=$last_day)
						$allow=1;
				}
				else
				{
					// new profile
					$allow=1;
				}

				if(!check_profile($profileid,"",1))
					$allow=0;

				if($allow)
				{
					$allot_to = $allot_to_array[0];
					allocate_paid23($profileid,$allot_to);
				}
			}
		}while($row_pid = mysql_fetch_array($res_pid));
	}


	function allocate_paid23($profileid,$allot_to)
        {
		global $db;
		$sql="SELECT PHONE_RES, PHONE_MOB, EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
                if($row=mysql_fetch_array($res))
                {
			$ph_res=$row['PHONE_RES'];
			$ph_mob=$row['PHONE_MOB'];
			$email=$row['EMAIL'];
		}
		
        	$db_master = connect_db();        
		$sql1="INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,RES_NO,MOB_NO,EMAIL,STATUS) VALUES('$profileid',now(),'$allot_to','O','".addslashes($ph_res)."','".addslashes($ph_mob)."','$email','A')";
                mysql_query($sql1,$db_master) or die("$sql1".mysql_error($db_master));

                $sql2="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOT_TIME,ALLOTED_TO) SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
                mysql_query($sql2,$db_master) or die("$sql2".mysql_error($db_master));

                $sql3="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='N' WHERE PROFILEID='$profileid'";
                mysql_query($sql3,$db_master) or die("$sql3".mysql_error($db_master));
		
		$sql4="INSERT INTO incentive.MANUAL_ALLOT (PROFILEID, ALLOT_TIME, ALLOTED_TO, ALLOTED_BY, COMMENTS) VALUES ('$profileid',now(),'$allot_to','jstech','unalloted paid profile')";
                mysql_query($sql4,$db_master) or die("$sql4".mysql_error($db_master));
        }
?>
