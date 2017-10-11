<?php
ini_set('max_execution_time','0');
include "connect.inc";
include "ads.php";
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
connect_slave();
$sql="SELECT PROFILEID,EMAIL,COUNTRY_RES,CITY_RES FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND  PROMO_MAILS='S' AND AGE BETWEEN 18 AND 40 AND CITY_RES IN ('DE00','MH04','KA02','AP03','TN02','MH08','WB05','GU01','PH00','UP19','MH05','OR01','RA07','MP08','MP02')";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
//mysql_close();
connect_db();
$from="info@jeevansathi.com";
$subject="Get smart this Shopping Season";
while($row=mysql_fetch_array($result))
{
	if($row["EMAIL"])
	{
		$smarty->assign("PROFILEID",$row["PROFILEID"]);
		$country=$row['COUNTRY_RES'];
		if($row['COUNTRY_RES']=='51')
                        $city=$CITY_INDIA_DROP[$row['CITY_RES']];
                if($row['COUNTRY_RES']=='128')
                        $city=$CITY_USA_DROP[$row['CITY_RES']];
		ads("$city","$country");
		$email_to= $row["EMAIL"];
		//$email_to="tapan_arora00@yahoo.co.in";
		$msg=$smarty->fetch("shopping_mailer.html");
		//$msg.="<a href=\"http://www.jeewansathi.com/profile/unsubscribe.php \" target=\"_blank \" >unsubscribe</a>";
		$email_to="vikas@jeevansathi.com";
		send_email($email_to,$msg,$subject,$from,"","","","","","Y");
		break;
		unset($email_to);
		unset($msg);
	}
}

?>
