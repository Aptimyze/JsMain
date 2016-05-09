<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	//include_once("/usr/local/scripts/connect_db.php");	//for testing
	include("allocate_functions_revamp.php");

	$db_master = connect_db();
	$db = connect_737();
	//$db = connect_db();	//testing
	$count=0;
	$ts = time();
	$ts -= 30*24*60*60;
	$last_day = date("Y-m-d",$ts);
	$filename = "All-LTF-Completed_format.csv";
	$fp = fopen($filename, "r") or exit("Unable to open file!");
	//Output a line of the file until the end is reached
	while(!feof($fp))
	{
        	$line=fgets($fp);
	        $data=explode(",",$line);
        	$username=substr($data[0],1,strlen($data[0])-2);
	        $exename=substr($data[1],1,strlen($data[1])-2);
        	$mobile=trim($data[2]);
	        $phone=trim($data[3]);
		$go=1;
		if($mobile!='' && $username!='')
		{
			$sql1="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME!='$username' AND LAST_LOGIN_DT>='$last_day' AND PHONE_MOB='$mobile'";
	                $res1=mysql_query($sql1,$db) or die("$sql1".mysql_error($db));
        	        if($row1=mysql_fetch_array($res1))
                	        $go = 0;
		}
		if($phone!='' && $username!='')
		{
			$sql2="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME!='$username' AND LAST_LOGIN_DT>='$last_day' AND PHONE_RES='$phone'";
	                $res2=mysql_query($sql2,$db) or die("$sql2".mysql_error($db));
        	        if($row2=mysql_fetch_array($res2))
                	        $go = 0;
		}
		if($go && $username!='')
		{
			$sql3="SELECT PROFILEID,EMAIL FROM newjs.JPROFILE WHERE USERNAME='$username'";
	                $res3=mysql_query($sql3,$db) or die("$sql3".mysql_error($db));
        	        if($row3=mysql_fetch_array($res3))
			{
                	        $profileid = $row3['PROFILEID'];
				$email = $row3['EMAIL'];
			}
			else
				$profileid = '';
			if($profileid!='')
			{
				if(!profile_allocated($profileid))
				{
					if(check_profile($profileid))
					{
						$count++;
						echo $username.",".$exename;echo "\n";
						//allocate_inactive45($profileid,$exename,$mobile,$phone,$email);
					}
				}
			}
		}
	}
        fclose($fp);
	echo $count;

	function allocate_inactive45($profileid,$allot_to,$ph_mob,$ph_res,$email)
        {
		global $db,$db_master;
		
		$sql4="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOT_TIME,ALLOTED_TO,RELAX_DAYS) VALUES ('$profileid',now(),'$allot_to',75)";
		mysql_query($sql4,$db_master) or die("$sql4".mysql_error($db_master));

		$sql5="UPDATE newjs.JPROFILE SET CRM_TEAM='offline' WHERE PROFILEID='$profileid'";
		mysql_query($sql5,$db_master) or die("$sql5".mysql_error($db_master));
		
        }
?>
