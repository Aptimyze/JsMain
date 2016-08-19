<?php

	include_once("connect.inc");
	$db=connect_db();
	$sql="SELECT PROFILEID FROM jsadmin.DEL_STATUS WHERE STATUS='N'";
	$res=mysql_query_decide($sql) or die(mysql_error());
	while($row=mysql_fetch_array($res))
	{
		$profile=$row['PROFILEID'];
		$producerObj=new Producer();
		if($producerObj->getRabbitMQServerConnected())
		{
			$sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$profile)), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($sendMailData);
			$sendMailData = array('process' =>'USER_DELETE','data' => ($profile), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($sendMailData);
		}
		else
		{
			$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profile > /dev/null ";
			$cmd = JsConstants::$php5path." -q ".$path;
			//$cmd = "php -q ".$path;
			passthru($cmd);
		}

		$sql_up="UPDATE jsadmin.DEL_STATUS SET STATUS='Y' WHERE PROFILEID=$profile";
		$res_up=mysql_query_decide($sql_up) or die(mysql_error());
	}
?>
