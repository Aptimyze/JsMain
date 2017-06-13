<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	include("allocate_functions_revamp.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");

	$db_master = connect_db();
	$db = connect_slave();
	$n=0;
	$i=0;
	$sqlj="SELECT USER FROM jsadmin.RENEWAL_AGENT";
        $resj=mysql_query($sqlj,$db_master) or die(mysql_error($db_master));
        while($rowj = mysql_fetch_array($resj))
                $allot_to_array[] = $rowj['USER'];
	$n=count($allot_to_array);
	$ts = time();
	$ts1 = $ts;
	$ts -= 30*24*60*60;
	$ts1 += 10*24*60*60;
	$last_day = date("Y-m-d",$ts);
	$check_day=date("Y-m-d",$ts1);

 	$sqlj="SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND EXPIRY_DT='$check_day'";
	$resj=mysql_query($sqlj,$db) or die(mysql_error($db)); 
	while($rowj = mysql_fetch_array($resj))
                $profileid_arr[] = $rowj['PROFILEID'];
	if(count($profileid_arr)>1)
		$profileid_str = implode(",",$profileid_arr);
	else
		$profileid_str = $profileid_arr[0];
	$sql_pid = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE ALLOTMENT_AVAIL='Y' AND TIMES_TRIED<3 AND PROFILEID IN ($profileid_str)";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	$row_pid = mysql_fetch_array($res_pid);
	do
	{
		unset($allow);
		unset($offer_case);
		$profileid = $row_pid['PROFILEID'];
		$val = $row_pid['CITY_RES'];
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
			$sqlj="SELECT MAX(EXPIRY_DT) AS EXP_DT FROM billing.SERVICE_STATUS WHERE ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND PROFILEID='$profileid'";
                        $resj=mysql_query($sqlj,$db) or die(mysql_error($db));
                        if($rowj = mysql_fetch_array($resj))
                        {
                                if($rowj['EXP_DT']!=$check_day)
                                        $offer_case=1;
                        }

			if(!check_profile($profileid))
				$allow=0;

			if($allow && !$offer_case)
			{
				$allot_to = $allot_to_array[$i];
				$done=allocate_due4renewal($profileid,$allot_to);
				if($done)
				{
					$i++;
					if($i==$n)
						$i=0;
				}
			}
		}
	}while($row_pid = mysql_fetch_array($res_pid));


	function allocate_due4renewal($profileid,$allot_to)
        {
		global $db;
		$active=0;
		$sql="SELECT PHONE_RES, PHONE_MOB, EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED='Y'";
                $res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
                if($row=mysql_fetch_array($res))
                {
			$active = 1;
			$ph_res=$row['PHONE_RES'];
			$ph_mob=$row['PHONE_MOB'];
			$email=$row['EMAIL'];
		}
		
		if($active)
		{
			$db_master = connect_db();        
			$sql1="INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,RES_NO,MOB_NO,EMAIL,STATUS) VALUES('$profileid',now(),'$allot_to','O','".addslashes($ph_res)."','".addslashes($ph_mob)."','$email','R')";
			mysql_query($sql1,$db_master) or die("$sql1".mysql_error($db_master));

			$sql2="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOT_TIME,ALLOTED_TO,RELAX_DAYS) VALUES ('$profileid',now(),'$allot_to',10)";
			mysql_query($sql2,$db_master) or die("$sql2".mysql_error($db_master));

			$sql3="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='N' WHERE PROFILEID='$profileid'";
			mysql_query($sql3,$db_master) or die("$sql3".mysql_error($db_master));
			
			$sql4="INSERT INTO incentive.MANUAL_ALLOT (PROFILEID, ALLOT_TIME, ALLOTED_TO, ALLOTED_BY, COMMENTS, CALL_SOURCE) VALUES ('$profileid',now(),'$allot_to','jstech','unalloted renewal profile','RC')";
			mysql_query($sql4,$db_master) or die("$sql4".mysql_error($db_master));
			return 1;
		}
		else
			return 0;
        }
	
	function usa_profile($value)
	{
		global $db;
                $sql="SELECT COUNTRY_VALUE FROM newjs.CITY_NEW WHERE VALUE='$value'";
                $res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
                if($row=mysql_fetch_array($res))
                        $usa_s=$row['COUNTRY_VALUE'];
		if($usa_s==128)
			return 1;
		else
			return 0;
	}
?>
