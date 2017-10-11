<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include_once("../connect.inc");
include_once("../comfunc.inc");
$db=connect_db();
$sql = "SELECT MAX( ENTRY_DT )  AS ENTRY_DT , PROFILEID,TYPE FROM newjs.ASTRO_PULLING_REQUEST WHERE TYPE = 'P' GROUP BY PROFILEID";
$res = mysql_query($sql) or logError($sql,"ShowErrTemplate");

while($row = mysql_fetch_array($res))
{
	$profileid = $row['PROFILEID'];
	$entry_dt = $row['ENTRY_DT'];

	//pulling data from matchstro's database.
	$url = "http://www.matchstro.com/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?Delete_AstroData?JS_UniqueID=".$profileid;
	//reading the data from matchstro's database into a file.
	$f = @fopen($url,"r");
	//reading the entire file into an array.
	$fp = file($url);
	echo $fp[0];
}
?>
