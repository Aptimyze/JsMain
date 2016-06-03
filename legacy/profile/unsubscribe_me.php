<?php
	include("connect.inc");
	
	$ex=explode("i",$profilechecksum);
	
	if($profilechecksum=="" || $ex[0]!=md5($ex[1]))
	{
		echo "Your request could not be processed";
		exit;
	}
	
	$db=connect_db();
	
	$sql="select USERNAME,PASSWORD from JPROFILE where PROFILEID='" . $ex[1] . "'";
	$result=mysql_query_decide($sql);
	
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		if($data=login($myrow["USERNAME"],$myrow["PASSWORD"]))
		{
			$checksum=$data["CHECKSUM"];
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=http://www.jeevansathi.com/profile/unsubscribe.php?checksum=$checksum\"></body></html>";
		}
		else 
		{
			echo "Your request could not be processed";
			exit;
		}
	}
	else 
	{
		echo "Your request could not be processed";
		exit;
	}
?>
