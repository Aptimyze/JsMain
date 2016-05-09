<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


ini_set("max_execution_time","0");
include("allocate_functions_revamp.php");
include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db = connect_db();


$sql = "SELECT STD_CODE,LANDLINE,MOBILE from incentive.NEGATIVE_PROFILE_LIST ";
$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
while($myrow=mysql_fetch_array($myres))
{
	$std 	=$myrow['STD_CODE'];
	$mobile =$myrow['MOBILE']; 
	$landline =$myrow['LANDLINE'];

	$mobile =checkNumber($mobile);
	$landline =checkNumber($landline);
	$landline_num =$std.$landline;			
	$landline_num =checkNumber($landline_num);	

	if($landline_num){	
        	$sql1= "INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES ('$landline_num')";
        	mysql_query($sql1,$db) or die($sql1.mysql_error($db_js));
	}

	if($mobile){
        	$sql1= "INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES ('$mobile')";
        	mysql_query($sql1,$db) or die($sql1.mysql_error($db_js));
	}
}

function checkNumber($number='')
{
	if(!$number)
		return;
	$rep_values =array(" ", "-", "(", ")" ,"+" ,"." ,",", "#");
        $number =str_replace($rep_values,'',$number);
        if(substr($number,0,1)=='0')
        	$number = substr($number,1);
	if(is_numeric($number))
        	return $number;
	return false;
}



?>
