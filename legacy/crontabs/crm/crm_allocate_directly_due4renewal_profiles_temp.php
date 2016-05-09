<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
	//include_once("/usr/local/scripts/connect_db.php");	//for testing
	include("allocate_functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");

	$db = connect_slave();

	$profileid_str = "806005,4748298,4715602,5084705,5135929,5140608,5117805,5120949,5125093,5097796,5019616,4995614,4996347,5002197,4877240,4880337,4883081,4884694,4958288,4867653,4850516,4807042,658311,4675338,4644996,4622556,4605983,4533824,4508281,4457913,4447877,4394255,4312453,4302511,4287346,4246831,4180885,4139062,4106368,3951005,3829008,3689404,3661552,3581413,3457136,2782285,3286436,3066262,2849185,2840633,2470338,1905461,1474266,1439483,565325,3941994,4884";
	$profileid_arr=explode(",",$profileid_str);
	for($i=0;$i<count($profileid_arr);$i++)
		allocate_due4renewal($profileid_arr[$i],'kanika.seth');


	function allocate_due4renewal($profileid,$allot_to)
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
		$sql1="UPDATE incentive.MAIN_ADMIN SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid'";
                mysql_query($sql1,$db_master) or die("$sql1".mysql_error($db_master));

		$sql2="UPDATE incentive.CRM_DAILY_ALLOT SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid' AND ALLOT_TIME>='2010-01-09 00:00:00' AND ALLOT_TIME<='2010-01-11 23:59:59'";
                mysql_query($sql2,$db_master) or die("$sql2".mysql_error($db_master));

		$sql4="UPDATE incentive.MANUAL_ALLOT SET ALLOTED_TO='$allot_to' WHERE PROFILEID='$profileid' AND ALLOT_TIME>='2010-01-09 00:00:00' AND ALLOT_TIME<='2010-01-11 23:59:59'";
                mysql_query($sql4,$db_master) or die("$sql4".mysql_error($db_master));
        }
?>
