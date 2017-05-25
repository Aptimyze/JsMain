<?php
function maileryn($mailer_id)
{
	global $db;	

	$emailsum2="EMAILSUM2".$mailer_id;

	$sql="SELECT PROFILEID FROM $emailsum2 order by PROFILEID";
	$result=mysql_query($sql,$db) or logerror1("came1 of maileryn",$sql);
	while($myrow=mysql_fetch_array($result))
	{
		unset($usercode);		
		unset($usercode_less);
		unset($sender);
		unset($ignored_users);

		$pid=$myrow['PROFILEID'];

		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$pid;
		$typeIn="'I'";
		$timeClause="TIME>=DATE_SUB(CURDATE(), INTERVAL 150 DAY)";
                $contactResult=getResultSet("SENDER,FILTERED",'','',$receiversIn,'',$typeIn,'',$timeClause,'','','','','','Y','','','','',"'Y'");
		
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				if($contactResult[$key]["FILTERED"]!="Y")
					$usercode_less[]=addslashes($contactResult[$key]["SENDER"]);
			}
			unset($contactResult);
		}

		if($usercode_less)
		{	
			$sender=implode("','",$usercode_less);	

			$sql_4="SELECT PROFILEID,if(HAVEPHOTO='Y',0,1) AS PHOTO_CONDITION FROM newjs.JPROFILE WHERE PROFILEID IN ('$sender') and SUBSCRIPTION='' ORDER BY PHOTO_CONDITION asc,LAST_LOGIN_DT desc LIMIT 8";
			$result_4=mysql_query($sql_4,$db) or logerror1("came4 of maileryn",$sql_4);
			while($myrow_4= mysql_fetch_array($result_4))
				$usercode[]=$myrow_4['PROFILEID'];

			$sql_5="SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$pid'";
			$result_5=mysql_query($sql_5,$db) or logerror1("came5 of maileryn",$sql_5);
			while($myrow_5= mysql_fetch_array($result_5))
				$ignored_users[]=$myrow_5['IGNORED_PROFILEID'];

			if(is_array($ignored_users) && is_array($usercode))
				$usercode=array_diff($usercode,$ignored_users);

			$resid=count($usercode);
			if($resid>0)
			{
				$sql_5="INSERT INTO  mmmjs.MAILERYN (RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,COUNTS,DATE) VALUES('$pid','$usercode[0]','$usercode[1]','$usercode[2]','$usercode[3]','$usercode[4]','$usercode[5]','$usercode[6]','$usercode[7]','$resid',now())";
				mysql_query($sql_5,$db) or die(mysql_error($db));
			}
		}
	}
}
?>
