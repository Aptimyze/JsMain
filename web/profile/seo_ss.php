<?php

include_once("connect.inc");
$db_master=connect_db();
$db_slave=connect_slave();

$sql="SELECT A.STORYID AS SID, B.ID, B.EMAIL FROM newjs.INDIVIDUAL_STORIES AS A, newjs.SUCCESS_STORIES AS B WHERE A.MAIN_PIC_URL != '' AND A.STATUS = 'A' AND
A.STORYID = B.ID";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($db_slave));
while($row=mysql_fetch_array($res))
{
	$email=$row['EMAIL'];
	$sid=$row['SID'];
	
	$sql_1="SELECT CASTE,MTONGUE,CITY_RES,RELIGION FROM newjs.JPROFILE WHERE EMAIL='$email'";
	$res_1=mysql_query($sql_1,$db_slave) or die(mysql_error1($db_slave));
	if($row_1=mysql_fetch_array($res_1))
	{
		$caste=$row_1['CASTE'];
		$rel=$row_1['RELIGION'];
		$city=$row_1['CITY_RES'];
		$comm=$row_1['MTONGUE'];

		$main_array["CASTE"][$caste][]=$sid;
		$main_array["RELIGION"][$rel][]=$sid;
		$main_array["CITY"][$city][]=$sid;
		$main_array["COMMUNITY"][$comm][]=$sid;

	}
}

foreach($main_array as $key => $i)
{
	$name=$key;
	foreach($i as $keyy=>$value)
	{	
		if($value)
		{
			$sql_2="UPDATE newjs.SEO SET MAP_SS='".implode("-",$value)."' WHERE VALUE='$keyy' AND FIELD='$name'";
			mysql_query($sql_2,$db_master) or die(mysql_error1($db_master));
		}
	}
}

function mysql_error1($db)
{
	  mail("Anurag.Gautam@jeevansathi.com","Error in profile/seo_ss.php ",mysql_error($db));
}

?>

