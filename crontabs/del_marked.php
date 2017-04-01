<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
	include("connect.inc");
	include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	$db= connect_db();

	$sql="set session wait_timeout=10000";
        mysql_query($sql);

	$today= date("Y-m-d H:i:s");
	$profiles= array();
	$sql1= "SELECT DATEDIFF(NOW(),M_DATE) AS dy_lft, PROFILEID FROM jsadmin.MARK_DELETE WHERE STATUS= 'M'";
	$res1= mysql_query_decide($sql1) or die(logError($sql1,$db));
	while($row1= mysql_fetch_array($res1))
	{
		$dy_lft= $row1['dy_lft'];
		$profile= $row1['PROFILEID'];
		if($dy_lft>=5)
		{
			$profiles[]=$profile;
		}		
	}
	if(count($profiles)>0)
	{
		$jprofileUpdateObj = JProfileUpdateLib::getInstance();
		$pid= implode(",",$profiles);
		//$sql2="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID IN ($pid)";
		//mysql_query_decide($sql2) or die(logError($sql2,$db));
		$jprofileUpdateObj->deactivateProfiles($profiles);
		$sql3="UPDATE jsadmin.MARK_DELETE SET STATUS='D',DATE='$today' where PROFILEID IN ($pid)";
                mysql_query_decide($sql3) or die(logError($sql3,$db));
		$sql="SELECT REASON,ENTRY_BY,PROFILEID,COMMENTS FROM jsadmin.MARK_DELETE WHERE PROFILEID IN ($pid)";
		$res= mysql_query_decide($sql) or die(logError($sql,$db));
		$j=0;
		while($row=mysql_fetch_array($res))
		{
			$profileids[$j]=$row['PROFILEID'];
			$users[$j]=$row['ENTRY_BY'];
			$reasons[$j]=$row['REASON'];
			$comms[$j]=$row['COMMENTS'];
			$j++;
		}
		$tm = date("Y-M-d");
		do
		{
			$j--;
			$pid=$profileids[$j];
			$user=$users[$j];
			$reason=$reasons[$j];
			$comments=$comms[$j];
			$sql1= "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID=$pid";
			$res1=mysql_query_decide($sql1) or die(logError($sql1,$db));
			$row1=mysql_fetch_assoc($res1);
			$username=$row1['USERNAME'];
		 	$sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($pid,'$username','$reason','$comments','$user','$tm')";
                	mysql_query_decide($sql) or die(logError($sql,$db));
			$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $pid > /dev/null &";
                        $cmd = JsConstants::$php5path." -q ".$path;
                        passthru($cmd);
		}while($j);
	}
mysql_close($db);
?>
