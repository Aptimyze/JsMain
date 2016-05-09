<?php
/****************************************************bms_hits.php*********************************************************/  /*
        *       Created By         :    Abhinav Katiyar
        *       Last Modified By   :    Abhinav Katiyar
        *       Description        :    used to capture clicks 
        *       Includes/Libraries :    ./includes/bms_display_include.php
****************************************************************************************************************************/
include("./includes/bms_display_include.php");


$dbbms = mysql_connect(MysqlDbConstants::$bms['HOST'].":".MysqlDbConstants::$bms['PORT'],MysqlDbConstants::$bms['USER'],MysqlDbConstants::$bms['PASS']) or logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
mysql_select_db("bms2",$dbbms);

if(!$popup)
{
	$sql_bannerid = "SELECT b.BannerId FROM bms2.BANNER m LEFT JOIN bms2.HITS b ON b.BannerId = m.BannerId WHERE b.Bust = '$bust' AND m.BannerStatus = 'live'";
	$res = mysql_query($sql_bannerid) or logErrorBms("bms_hits.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql_bannerid,"continue","YES");
	$row = mysql_fetch_array($res);
	$banner = $row['BannerId'];

	$sql = "Delete from bms2.HITS where Bust = '$bust'";
	$res = mysql_query($sql) or logErrorBms("bms_hits.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
}
if($banner)
{	
	$dt=Date('Y-m-d');
 	$sql="Select * from bms2.BANNERMIS where BannerId='$banner' and Date='$dt'";
 	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_hits.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
 	if($myrow=mysql_fetch_array($result))
	{
 		$id=$myrow["ID"];
 		$val=$myrow["Clicks"];
 		$val=$val+1;
 		$sql="Update bms2.BANNERMIS set Clicks='$val' where ID='$id'";
 		mysql_query($sql,$dbbms) or logErrorBms("bms_hits.php:2: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");

 	}
 	else
	{
 		$sql="Insert into bms2.BANNERMIS (BannerId,Date,Clicks) values ('$banner','$dt','1')";
 		mysql_query($sql,$dbbms) or logErrorBms("bms_hits.php:3: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");
 
 	}
 	
 	$sql="Select BannerUrl from bms2.BANNER where BannerId='$banner'";
 	$result=mysql_query($sql,$dbbms) or logErrorBms("bms_hits.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");

 	if($myrow=mysql_fetch_array($result))
	{
 		$url=$myrow["BannerUrl"];
		if($othersrcp && trim($othersrcp)!='')
                $url=preg_replace("/othersrcp=[^\&]*/","othersrcp=$othersrcp",$url);
 		if($url) echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=$url\">";
 	} 
 	
 }
?>
