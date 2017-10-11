<?php
/** 
* Hits of banner is recorded and action page is dispalyed from here.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/
include_once("/usr/local/scripts/bms_config.php");
include_once("classes/Mysql.class.php");
include_once("classes/Banner.class.php");
include_once("includes/commonIPfunctions.php");
$mysqlObj=new Mysql;
$mysqlObj->connect();
	
if ($multiurl)
	list($null , $banner , $landing_url) = explode("|",$multiurl);

if ($mailer)
{
	$bannerObj=new Banner;

	if($data)
		$extraCondition=" BannerDefault<>'Y' ";
	$banner=$bannerObj->bannerIdonStatus($zonestr,'live',$subzone,'',$extraCondition);
}

if($banner)
{
	if(!$mysqlObj)
	{
		$mysqlObj=new Mysql;
		$mysqlObj->connect();
	}

	$dt=Date('Y-m-d');

	$sql="Update bms2.BANNERMIS set Clicks=Clicks+1 where BannerId='$banner' and Date='$dt'";
	$result = $mysqlObj->query($sql);

	if(mysql_affected_rows()==0)
	{
		$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
		$result = $mysqlObj->query($sql);
	}
	$source = getCountrySource()=='IND'?'INDIA':'NRI';
	$sql="UPDATE bms2.SOURCE_CLICK_COUNTS SET COUNT=COUNT+1 WHERE BANNER_ID='$banner' AND DATE='$dt' AND SOURCE='$source'";
	$result = $mysqlObj->query($sql);
	if(mysql_affected_rows()==0)
	{
		$sql="INSERT INTO bms2.SOURCE_CLICK_COUNTS (BANNER_ID,COUNT,SOURCE,DATE) values ('$banner','1','$source','$dt')";
		$result = $mysqlObj->query($sql);
	}

	if ($landing_url)
	{
		echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=$landing_url\">";
	}
	else
	{
		$bannerObj=new Banner;
		$bannerObj->setBannerDetails(" BannerUrl","BannerId='$banner'");
		$url=$bannerObj->getBannerUrl();
	
		if($url)	
		{
			
			if($from_src) {
				if(strstr($url,"?")) $url .= "&from_src=".$from_src;
				else $url .= "?from_src=".$from_src;
			}
			if($othersrcp && trim($othersrcp)!='')
				$url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
			if($url) 
				header("Location: $url");
		}
		else
		{
			$mysqlObj->logMessages("Error in fetching Banner URL",$sql);
		}
	}
}
?>
