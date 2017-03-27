<?php

include("connect.inc");

$db_slave = connect_slave();
$db_master = connect_db();
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","duplicate_profile_delete.php in USE",$msg);
if(authenticated($cid))
{
	if($del=='true')
	{
		for ($i=0; $i<count($_POST['checkbox']); $i++)
		{
			$pid=$_POST['checkbox'][$i];
			$pidArr[]=$pid;

			$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
			$res=mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
			while($row=mysql_fetch_array($res))
			{
				$username=$row['USERNAME'];
				$usernameArr[]=$username;
				$smarty->assign("USERNAME",$usernameArr);
			}
		}
		$smarty->assign("PIDArr",$pidArr);
	}

	if($delete=='true')
	{
		$date = date("Y-M-d");
		$now = date("Y-m-d G:i:s");

		for ($i=0; $i<count($_POST['pid']); $i++)
		{
			$pid=$_POST['pid'][$i];
			$user=$_POST['username'][$i];

			$userArr[]=$user;
			$smarty->assign("USERNAME",$userArr);
		
			$sql_pre="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID='$pid'";
			mysql_query($sql_pre,$db_master) or die(mysql_error($db_master));

			$sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,USER,TIME) values('$pid','$user','$delete_reason','$operator','$date')";
			mysql_query($sql,$db_master) or die(mysql_error($db_master));
			
			$sql_1="UPDATE newjs.JPROFILE SET MOD_DT = '$now',ACTIVATED = 'D',activatedKey=0 WHERE PROFILEID ='$pid'";
			mysql_query($sql_1,$db_master) or die(mysql_error($db_master));
			
			$sql_2="DELETE FROM jsadmin.DUPLICATE_NUMBER_PROFILE WHERE PROFILEID = '$pid'";
			mysql_query($sql_2,$db_master) or die(mysql_error($db_master));

			$sql_3="INSERT INTO jsadmin.DELETE_PROFILE_DUPLICATE (PROFILEID) VALUES ('$pid')";
			mysql_query($sql_3,$db_master) or die(mysql_error($db_master));
		}

		$path = $_SERVER['DOCUMENT_ROOT']."/jsadmin/intermediate_delete.php > /dev/null &";
	//	$cmd = "php -q ".$path;
		$cmd = "/usr/bin/php -q ".$path;
		passthru($cmd);
		$smarty->assign("message","1");
	}
	
	$name= getname($cid);
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->assign("PID",$pid);
	$smarty->display("duplicate_profile_delete.htm");
}
else
{
          $msg="Your session has been timed out<br>";
	  $msg .="<a href=\"index.htm\">";
	  $msg .="Login again </a>";
	  $smarty->assign("MSG",$msg);
	  $smarty->display("jsadmin_msg.tpl");
}


?>
