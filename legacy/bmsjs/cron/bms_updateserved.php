<?php

$dirname=dirname(__FILE__);
chdir($dirname);

include("../includes/bms_connect.php");
include("../classes/Memcache.class.php");
$dbbms = getConnectionBms(); 
$memObj=new UserMemcache;

$sql="SELECT BannerId FROM bms2.BANNER WHERE BannerStatus='live'";
$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);

while($row=mysql_fetch_array($res))
{
	$bannerId=$row["BannerId"];
	$impressionAdded=$memObj->resetAndGetBannerImpression($bannerId);		

	if($impressionAdded)
	{
		$sql1="UPDATE bms2.BANNER set BannerServed=BannerServed+$impressionAdded WHERE BannerId=$bannerId";
		mysql_query($sql1,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql1); 
	}
}

/*
$sql="SELECT MAX(TO_DT) AS MAX FROM bms2.DAILYMIS";
if($res=mysql_query($sql,$dbbms))
{
	$myr=mysql_fetch_array($res);
	$currdate=$myr["MAX"];

	$sql="SELECT COUNT(*) as cnt FROM bms2.DAILYMIS WHERE TO_DT='$currdate'";
	$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);
	$myr=mysql_fetch_array($res);
	$cnt1=$myr["cnt"];

	$sql="SELECT COUNT(*) as cnt FROM bms2.BANNERHEAP";
	$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);
	$myr=mysql_fetch_array($res);
	$cnt2=$myr["cnt"];

	if($cnt1<$cnt2)
	{
		$sql="INSERT INTO bms2.DAILYMIS(BannerId,TO_DT)(SELECT BannerId,'$currdate' FROM bms2.BANNERHEAP WHERE BannerId NOT IN (SELECT BannerId FROM bms2.DAILYMIS WHERE TO_DT='$currdate'))";
		mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);
	}

	$sql="UPDATE bms2.DAILYMIS a,bms2.BANNERHEAP b set a.BannerServed=a.BannerServed+b.BannerServed WHERE a.BannerId=b.Bannerid AND a.TO_DT='$currdate'";
	mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);
}
else
	mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved1",mysql_error($dbbms).$sql);
//Ends Here
$sql="Update bms2.BANNER b,bms2.BANNERHEAP h set b.BannerServed=h.BannerCount , h.BannerServed =0 where b.BannerId=h.BannerId";
mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_updateserved",mysql_error($dbbms).$sql);  
*/

?>
