<?php 

$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);

  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


	include("connect.inc");
	connect_db();
	
	$timeval = time();
	$timeval -= "86400";
	//$timeval -= "3600";
	$timeval = date("YmdH0000",$timeval);

	$sql="select PROFILEID from JPROFILE where ACTIVATED in ('D','H') AND TIMESTAMP >= '$timeval' AND GENDER='M'";
	$result=mysql_query($sql) or die("1 ".mysql_error1());
	
	while($myrow=mysql_fetch_array($result))
	{
		$str.=$myrow["PROFILEID"] . ",";
	}
	
	mysql_free_result($result);
	
	$str=substr($str,0,-1);
	
	if($str!="")
	{
		$sql="delete from SEARCH_MALE_FULL where PROFILEID in ($str)";
		mysql_query($sql) or die("2 ".mysql_error1());;
	}
	
	$str="";
	
	$sql = "truncate table SWAP_FULL";
	mysql_query($sql) or die("3 ".mysql_error1());
	
	$sql="alter table SWAP_FULL disable keys";
	mysql_query($sql) or die("4 ".mysql_error1());
	
	$sql = "INSERT INTO SWAP_FULL SELECT PROFILEID , CASTE , MTONGUE , MOD_DT , AGE , SUBCASTE , YOURINFO , FAMILYINFO , SPOUSE , HAVEPHOTO , KEYWORDS , EDUCATION, LAST_LOGIN_DT, ENTRY_DT, PRIVACY, FATHER_INFO, SIBLING_INFO, JOB_INFO, SORT_DT FROM JPROFILE WHERE ACTIVATED = 'Y' AND TIMESTAMP >= '$timeval' AND GENDER='M'";
	
	mysql_query($sql) or die("5 ".mysql_error1());
	
	$sql="alter table SWAP_FULL enable keys";
	mysql_query($sql) or die("6 ".mysql_error1());
	
	$sql="select PROFILEID from SWAP_FULL";

	$result=mysql_query($sql) or die("7 ".mysql_error1());
	
	while($myrow=mysql_fetch_array($result))
	{
	
		$sql = "REPLACE INTO SEARCH_MALE_FULL SELECT * from SWAP_FULL where PROFILEID='" . $myrow["PROFILEID"] . "'";
		
		mysql_query($sql) or die("8 ".mysql_error1());
		
	}
	
	mysql_free_result($result);
	
	$sql="delete from SEARCH_MALE_FULL where PRIVACY='C' or LAST_LOGIN_DT < DATE_SUB(now(),INTERVAL 12 MONTH)";

	mysql_query($sql) or die("9 ".mysql_error1());
	
	$sql="select PROFILEID from JPROFILE where ACTIVATED in ('D','H') AND TIMESTAMP >= '$timeval' AND GENDER='F'";
	$result=mysql_query($sql) or die("10 ".mysql_error1());
	
	while($myrow=mysql_fetch_array($result))
	{
		$str.=$myrow["PROFILEID"] . ",";
	}
	
	mysql_free_result($result);
	
	$str=substr($str,0,-1);
	
	if($str!="")
	{
		$sql="delete from SEARCH_FEMALE_FULL where PROFILEID in ($str)";
		mysql_query($sql) or die("11 ".mysql_error1());
	}
	
	$sql = "truncate table SWAP_FULL";
	mysql_query($sql) or die("12 ".mysql_error1());
	
	$sql="alter table SWAP_FULL disable keys";
	mysql_query($sql) or die("13 ".mysql_error1());
	
	$sql = "INSERT INTO SWAP_FULL SELECT PROFILEID , CASTE , MTONGUE , MOD_DT , AGE , SUBCASTE , YOURINFO , FAMILYINFO , SPOUSE , HAVEPHOTO , KEYWORDS , EDUCATION, LAST_LOGIN_DT, ENTRY_DT, PRIVACY, FATHER_INFO, SIBLING_INFO, JOB_INFO, SORT_DT FROM JPROFILE WHERE ACTIVATED = 'Y' AND TIMESTAMP >= '$timeval' AND GENDER='F'";
	
	mysql_query($sql) or die("14 ".mysql_error1());
	
	$sql="alter table SWAP_FULL enable keys";
	mysql_query($sql) or die("15 ".mysql_error1());
	
	$sql="select PROFILEID from SWAP_FULL";

	$result=mysql_query($sql) or die("16 ".mysql_error1());
	
	while($myrow=mysql_fetch_array($result))
	{
	
		$sql = "REPLACE INTO SEARCH_FEMALE_FULL SELECT * from SWAP_FULL where PROFILEID='" . $myrow["PROFILEID"] . "'";
		
		mysql_query($sql) or die("17 ".mysql_error1());
		
	}
	
	mysql_free_result($result);
	
	$sql="delete from SEARCH_FEMALE_FULL where PRIVACY='C' or LAST_LOGIN_DT < DATE_SUB(now(),INTERVAL 12 MONTH)";

	mysql_query($sql) or die("18 ".mysql_error1());
	
	$sql = "truncate table SWAP_FULL";
	mysql_query($sql) or die("19 ".mysql_error1());

?>
