<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/flag.php");

$myDbS=connect_slave();
$myDbM=connect_db();
mysql_query('set session wait_timeout=50000',$myDbS);
mysql_query('set session wait_timeout=50000',$myDbM);
$sql_main = "SELECT PROFILEID,SCREENING,RELIGION FROM newjs.JPROFILE WHERE RELIGION!=1 AND (SUBCASTE!='' OR GOTHRA!='' OR NAKSHATRA!='')";
$res_main = mysql_query($sql_main,$myDbS) or die($sql_main.mysql_error($myDbS));
while($row = mysql_fetch_array($res_main))
{
	$pid = $row['PROFILEID'];
	$curflag = $row['SCREENING'];
	$Religion = $row['RELIGION'];
	$curflag=setFlag("SUBCASTE",$curflag);
	$curflag=setFlag("NAKSHATRA",$curflag);
	if($Religion == 3)
	{
		$sql_ch="select DIOCESE from JP_CHRISTIAN where PROFILEID='$pid'";
		$result_ch=mysql_query($sql_ch) or die($sql_ch.mysql_error($myDbS));
		if($row_ch=mysql_fetch_array($result_ch))
			$diocese = $row_ch["DIOCESE"];
		if(trim($diocese)=="")
			$curflag=setFlag("GOTHRA",$curflag);
	}	
	else
	{
		$curflag=setFlag("GOTHRA",$curflag);
		$sql_di = "update JP_CHRISTIAN set DIOCESE='' where PROFILEID='$pid'";
                mysql_query($sql_di) or die($sql_di.mysql_error($myDbM));
	}
	$sql_up = "update newjs.JPROFILE set SUBCASTE='',GOTHRA='',NAKSHATRA='',SCREENING='$curflag',MOD_DT=now() where PROFILEID='$pid'";
	mysql_query($sql_up,$myDbM) or die($sql_up.mysql_error($myDbM));
}
?>
