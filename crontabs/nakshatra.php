<?php 
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","crontabs/nakshatra.php in USE",$msg);
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	ini_set('max_execution_time','0');
	include("connect.inc");
	$db=connect_db();

	$sql="select max(PROFILEID) as MAX1 from JPROFILE";
	$result=mysql_query($sql) or die(mysql_error());

	$myrow=mysql_fetch_array($result);

	$count=$myrow["MAX1"];

	$sql="select ORIGINAL,FINAL from NAKSHATRA_PORT";
	$result=mysql_query($sql) or die(mysql_error());

	while($myrow=mysql_fetch_array($result))
	{
		$temp=strtolower($myrow["ORIGINAL"]);
		$gothra_arr["$temp"]=strtoupper(substr($myrow["FINAL"],0,1)) . strtolower(substr($myrow["FINAL"],1));
	}

	for($i=1;$i<=$count;$i++)
	{
		$sql="select NAKSHATRA from JPROFILE where PROFILEID=$i";
		$res=mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($res)>0)
		{
			$myrow=mysql_fetch_array($res);

			$origstr=strtolower($myrow["NAKSHATRA"]);

			$finalstr=$gothra_arr["$origstr"];

			$sql="update JPROFILE set NAKSHATRA='" . addslashes($finalstr) . "',TIMESTAMP=TIMESTAMP where PROFILEID=$i";
			mysql_query($sql) or die(mysql_error());
			JProfileUpdateLib::getInstance()->removeCache($i);
		}
	}

?>
