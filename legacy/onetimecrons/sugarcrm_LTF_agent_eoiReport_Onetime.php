<?php

// Live Connection Param

$dbHostMaster         ="localhost:3306";
$dbUserMaster         ="user_sel";
$dbPasswdMaster       ="CLDLRTa9";
$dbName               ="newjs";

$dbHostShard[0]         ="localhost:/tmp/mysql5-2.sock";
$dbUserShard[0]         ="user_sel";
$dbPasswdShard[0]       ="Km7Iv80l";

$dbHostShard[1]         ="172.16.3.187:3308";
$dbUserShard[1]         ="user_sel";
$dbPasswdShard[1]       ="Km7Iv80l";

$dbHostShard[2]         ="192.168.2.240:3313";
$dbUserShard[2]         ="user_sel";
$dbPasswdShard[2]       ="Km7Iv80l";
// Ends

// Test Connection Param
/*
$dbHostMaster         ="172.16.3.185:3306";
$dbUserMaster         ="localuser";
$dbPasswdMaster       ="Km7Iv80l";
$dbName               ="newjs";

$dbHostShard[0]         ="172.16.3.185:3307";
$dbUserShard[0]         ="localuser";
$dbPasswdShard[0]       ="Km7Iv80l";

$dbHostShard[1]         ="172.16.3.185:3308";
$dbUserShard[1]         ="localuser";
$dbPasswdShard[1]       ="Km7Iv80l";

$dbHostShard[2]         ="172.16.3.185:3309";
$dbUserShard[2]         ="localuser";
$dbPasswdShard[2]       ="Km7Iv80l";
*/
// Ends
$db =@mysql_connect("$dbHostMaster","$dbUserMaster","$dbPasswdMaster") or die("master connection failed");

	$exec_name_arr 	=array();
	$uname_arr      =array();
	$todayDate      =@date("Y-m-d H:i:s"); 
	//$startDt	=@date("Y-m-d",@strtotime("$todayDate -1 days"));
	$last15Days	=@date("Y-m-d",@strtotime("$todayDate -15 days"));	
	$ip1		='115.249.243.194';
	$ip2		='121.243.22.130';
	$ip3		='115.254.79.170';

	$sql ="select MAX(DATE) AS DATE from test.IP_ALERT";
	$res =mysql_query($sql,$db) or die("$sql".mysql_error());
	$row =mysql_fetch_array($res);
	$lastDate =$row['DATE'];

	//$sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE LAST_LOGIN_DT>='$last15Days'";
	$sql_unames ="SELECT USERNAME FROM jsadmin.PSWRDS";
	$res_unames = mysql_query($sql_unames,$db) or die($sql_unames.mysql_error());
	while($row_unames = mysql_fetch_array($res_unames))
		$uname_arr[] = $row_unames['USERNAME'];

	$uname_arr =array_unique($uname_arr);
	$uname_str = "'".@implode("','",$uname_arr)."'";
	if($uname_str){
		$sql1 ="SELECT id,user_name from sugarcrm.users where user_name in($uname_str) and id!='1'";
		$res1 =mysql_query($sql1,$db) or die("$sql1".mysql_error());
		while($row1=mysql_fetch_array($res1)){
			$exec_id 		=$row1['id'];
			$exec_name_arr[$exec_id]=trim($row1['user_name']);
		}
	}

	$profileidArr =array();

for($k=1;$k<=3;$k++)
{
	$profileidArr =array();
	$dateArr =array();

	if($k<10)
		$k="0".$k;
	$startDt ="2013-04-".$k;
		
	for($j=0;$j<3;$j++){
		$dbHost 	=$dbHostShard[$j];
		$dbUser		=$dbUserShard[$j];
		$dbPasswd	=$dbPasswdShard[$j];
		$shard =mysql_connect("$dbHost","$dbUser","$dbPasswd") or die("shard connection failed");
		$sql1="select SENDER,DATE from newjs.MESSAGE_LOG where DATE>='$startDt 00:00:00' AND DATE<='$startDt 23:59:59' AND TYPE='I' AND IP IN(INET_ATON('$ip1'),INET_ATON('$ip2'),INET_ATON('$ip3')) ORDER BY DATE DESC";100;
		//$sql1="select SENDER,DATE from newjs.MESSAGE_LOG where DATE>'$lastDate' AND TYPE='I' AND IP IN(INET_ATON('$ip1'),INET_ATON('$ip2'),INET_ATON('$ip3')) ORDER BY DATE DESC";

		$res1=mysql_query($sql1,$shard);
		while($row1=mysql_fetch_array($res1)){
			$profileid =$row1['SENDER'];
			if(!in_array($profileid,$profileidArr))
				$profileidArr[]		=$profileid;
				$dateArr[$profileid] 	=$row1['DATE'];
		}
		mysql_close($shard);
	}

	// Get the username for the given profileid
	for($i=0;$i<count($profileidArr);$i++){
		$profileid =$profileidArr[$i];
		$sql2 ="select USERNAME from newjs.JPROFILE where PROFILEID='$profileid'";
		$res2 =mysql_query($sql2,$db) or die("$sql2".mysql_error());
		$row2=mysql_fetch_array($res2);	
		$username =trim($row2['USERNAME']);
		$assigned_userid='';
		$allotedTo	='';

		// Check the agent to which the username is assigned
		if($username){
			$dateVal =$dateArr[$profileid];
			$sqlAss ="select ALLOTED_TO from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOT_TIME<='$dateVal' AND DE_ALLOCATION_DT>='$dateVal' ORDER BY ID DESC LIMIT 1";
			//$sqlAss ="select ALLOTED_TO from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$resAss =mysql_query($sqlAss,$db) or die("$sqlAss".mysql_error());
			$rowAss=mysql_fetch_array($resAss);
			$allotedTo =$rowAss['ALLOTED_TO'];	

			$sql_con ="SELECT l.assigned_user_id from sugarcrm.leads_cstm l_cstm,sugarcrm.leads l where l.id=l_cstm.id_c and l_cstm.jsprofileid_c='$username'";
			$res_con =mysql_query($sql_con,$db) or die("$sql_con".mysql_error());
			if($row_con=mysql_fetch_array($res_con))
				$assigned_userid =$row_con['assigned_user_id'];
			else{
				$sql_con ="SELECT l.assigned_user_id from sugarcrm_housekeeping.connected_leads_cstm l_cstm,sugarcrm_housekeeping.connected_leads l where l.id=l_cstm.id_c and l_cstm.jsprofileid_c='$username'";
				$res_con =mysql_query($sql_con,$db) or die("$sql_con".mysql_error());
				if($row_con=mysql_fetch_array($res_con))
					$assigned_userid =$row_con['assigned_user_id'];
			}
			$agentName =@$exec_name_arr[$assigned_userid];
			
			$sqlIns ="insert into test.IP_ALERT (`USERNAME`,`AGENT`,`ALLOTED_TO`,`DATE`) VALUES('$username','$agentName','$allotedTo','$dateVal')";
			mysql_query($sqlIns,$db);
		}
	}
}


?>
