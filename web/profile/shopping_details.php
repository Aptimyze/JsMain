<?php
include "connect.inc";
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include "ads.php";
connect_db();
if($submit)
{
	$today=date("Y-m-d");
	if($all=="Y")
	{
		$PERSONALLOAN="Yes";
		$CREDITCARD="Yes";
		$MISSPLAYER="Yes";
		$JOHNPLAYER="Yes";
		$GLITTERJEWELRY="Yes";
		$ONLINESHOPPING="Yes";
	}
	else
	{
		if($c1=="Y")
			$MISSPLAYER="Yes";
		if($c2=="Y")
			$JOHNPLAYER="Yes";
		if($c3=="Y")
			$GLITTERJEWELRY="Yes";
		if($c4=="Y")
			$ONLINESHOPPING="Yes";
	}
	if($all1=="Y")
	{
		$PERSONALLOAN="Yes";
		$CREDITCARD="Yes";
	}
	else
	{
		if($b1=="Y")
			$PERSONALLOAN="Yes";
		if($b2=="Y")
			$CREDITCARD="Yes";
	}
	if($textfield)
		$NAME=$textfield;

	if($PROFILEID)
	{
		$sql="SELECT *  FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$PROFILEID";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($result);
		$age=$row['AGE'];
		$gender=$row['GENDER'];
		$email=$row['EMAIL'];
		$mobile=$row['PHONE_MOB'];
		$landline=$row['PHONE_RES'];
		$occ=$OCCUPATION_DROP[$row['OCCUPATION']];
		$income=$INCOME_DROP[$row['INCOME']];
		$country=$row['COUNTRY_RES'];
			
		if($row['COUNTRY_RES']=='51')
			$city=$CITY_INDIA_DROP[$row['CITY_RES']];
		if($row['COUNTRY_RES']=='128')
			$city=$CITY_USA_DROP[$row['CITY_RES']];
	}	
	
	$sql="INSERT INTO newjs.SHOPPING_MAILER_DETAILS VALUES('','$PROFILEID','$NAME','$gender','$age','$email','$city','$mobile','$landline','$occ','$income','$PERSONALLOAN','$CREDITCARD','$MISSPLAYER','$JOHNPLAYER','$GLITTERJEWELRY','$ONLINESHOPPING','$today')";
	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	ads("$city","$country");	
	$smarty->display("thankyou.html");	
}
else
{
ads("","");
$smarty->assign("PROFILEID",$PROFILEID);
$smarty->display("shopping_mailer_landing.html");
}
?>

