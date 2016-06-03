<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");

$db2=connect_737();

$array_fields=array('SUBCASTE','YOURINFO','FAMILYINFO','SPOUSE','EDUCATION','FATHER_INFO','JOB_INFO','SIBLING_INFO');

foreach ($array_fields as $field)
{	
	$sql="select PROFILEID from newjs.SEARCH_MALE_FULL where ($field REGEXP '9[0-9]{9,}')";
	$result = mysql_query($sql,$db2) or logError($sql);
	while($myrow=mysql_fetch_array($result))
	{	$j=count($profileid[$myrow['PROFILEID']]);
        	$profileid[$myrow['PROFILEID']][$j]=$field;
	}
}	

$profile_id=implode(",",array_keys($profileid));
$profile_arr=explode(",",$profile_id);

$sql="select SCREENING from newjs.JPROFILE where PROFILEID IN ($profile_id) ";
$res=mysql_query($sql,$db2) or logError($sql);
mysql_close($db2);
$db=connect_db();
$i=0;
while($myrow1=mysql_fetch_array($res))
{
	$screen=$myrow1["SCREENING"];
	$profile=$profile_arr[$i];
	$j=0;
	while($profileid[$profile][$j]!='')
	{
		if(isFlagSet($profileid[$profile][$j],$screen))
			$screen=removeFlag($profileid[$profile][$j],$screen);
		$j++;
	}
	$sql="update JPROFILE set SCREENING='$screen' where PROFILEID='$profile'";
	mysql_query($sql,$db) or logError($sql);
	$i++;
}

?>
