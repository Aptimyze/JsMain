<?php

/*********************************************************************************************
* FILE NAME     : kharidari_LEFT.php
* DESCRIPTION   : Sends mails to paid members with the Kharidari coupons according to their membership
* CREATION DATE : 2 June, 2005
* CREATEDED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("../jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$today_morn=date("Y-m-d")."00:00:00";
$today_night=date("Y-m-d")."23:59:59";
$FROM="kharidari_mailer";
$TO="shakti.srivastava@jeevansathi.com";
$CC="kush.asthana@jeevansathi.com";
$SUB="Error occurred in kharidari_mailer.php";
$MSG="";

$sql_sel1="SELECT distinct a.BILLID,a.SERVICEID,a.PROFILEID,a.ENTRY_DT FROM billing.PURCHASES AS a LEFT JOIN billing.KHARIDARI AS b ON (a.PROFILEID=b.PROFILEID) WHERE a.ENTRY_DT > '2005-06-16 00:00:00' AND a.ENTRY_DT<'2005-07-05' AND a.STATUS='DONE'AND b.PROFILEID IS NULL";
$res_sql_sel=mysql_query_decide($sql_sel1) or $MSG=$MSG."\n".mysql_error_js();
$a=0;
while($row_sql_sel=mysql_fetch_array($res_sql_sel))
{
	$arr[$a][]=$row_sql_sel['PROFILEID'];
	$arr[$a][]=$row_sql_sel['SERVICEID'];
	$arr[$a][]=$row_sql_sel['BILLID'];
	$arr[$a][]=$row_sql_sel['ENTRY_DT'];
	$a++;
}


for($a=0;$a<count($arr);$a++)
{
	if($arr[$a][1]=='P2'||$arr[$a][1]=='P3'||$arr[$a][1]=='P4')
	{
		$sql_sel="SELECT COUPON_NO FROM billing.KHARIDARI WHERE SENT<>'Y' AND PROFILEID=0 AND COUPON_TYPE=10";
//		$res_sql=mysql_query_decide($sql_sel) or $MSG=$MSG."\n".mysql_error_js();
		if($res_sql=mysql_query_decide($sql_sel))
		{
			$row=mysql_fetch_array($res_sql);
			$coupon=$row['COUPON_NO'];
			$arr[$a][]=$coupon;
		
			$sql_updt="UPDATE billing.KHARIDARI SET PROFILEID='".$arr[$a][0]."',SERVICEID='".$arr[$a][1]."',BILLID='".$arr[$a][2]."' WHERE COUPON_NO='$coupon'";
			$res=mysql_query_decide($sql_updt) or $MSG=$MSG."\n".mysql_error_js();
		}
		else
		{
			$MSG=$MSG."\n".mysql_error_js();
		}
	}
	else if($arr[$a][1]=='P5'||$arr[$a][1]=='P6'||$arr[$a][1]=='P12')
	{
		$sql_sel2="SELECT COUPON_NO,COUPON_TYPE FROM billing.KHARIDARI WHERE SENT<>'Y' AND PROFILEID=0 AND COUPON_TYPE=12";
//                $res_sql2=mysql_query_decide($sql_sel2) or $MSG=$MSG."\n".mysql_error_js();
		if($res_sql2=mysql_query_decide($sql_sel2))
		{
        	        $row=mysql_fetch_array($res_sql2);
	                $coupon=$row['COUPON_NO'];
        	        $arr[$a][]=$coupon;
                                                                                                                            
	                $sql_updt2="UPDATE billing.KHARIDARI SET PROFILEID='".$arr[$a][0]."',SERVICEID='".$arr[$a][1]."',BILLID='".$arr[$a][2]."' WHERE COUPON_NO='$coupon'";
        	        $res=mysql_query_decide($sql_updt2) or $MSG=$MSG."\n".mysql_error_js();
		}
		else
		{
			$MSG=$MSG."\n".mysql_error_js();
		}
	}
}



$sql_sel_mail="SELECT PROFILEID,COUPON_NO FROM billing.KHARIDARI WHERE SENT<>'Y' AND PROFILEID<>0";
$res_sel_mail=mysql_query_decide($sql_sel_mail) or $MSG=$MSG."\n".mysql_error_js();
while($row_sel_mail=mysql_fetch_array($res_sel_mail))
{
	$profile=$row_sel_mail['PROFILEID'];
	$coupon=$row_sel_mail['COUPON_NO'];
	$sql_mail="SELECT EMAIL,USERNAME,SERVICEID FROM billing.PURCHASES WHERE PROFILEID='$profile'";
//	$res_mail=mysql_query_decide($sql_mail) or $MSG=$MSG."\n".mysql_error_js();
	if($res_mail=mysql_query_decide($sql_mail))
	{
		$row_mail=mysql_fetch_array($res_mail);
		$email=$row_mail['EMAIL'];
		$username=$row_mail['USERNAME'];
		$serviceid=$row_mail['SERVICEID'];
		if($serviceid=="P2"||$serviceid=="P3"||$serviceid=="P4")
		{
			$sid="10";
		}
		else
		{
			$sid="12";
		}
	
		$smarty->assign("USERNAME",$username);
		$smarty->assign("SERVICE",$sid);
		$smarty->assign("COUPON",$coupon);
		$msg=$smarty->fetch("discount_mailer.html");

		$from="webmaster@jeevansathi.com";
		$subject="Your Discount at Kharidari.com";
		send_email($email,$msg,$subject,$from,'kush.asthana@jeevansathi.com');
//		send_email('kush.asthana@gmail.com',$msg,$subject,$from,'kush.asthana@jeevansathi.com');
	
		$sql_updt3="UPDATE billing.KHARIDARI SET SENT='Y',SEND_DT=NOW() WHERE PROFILEID='$profile' AND COUPON_NO='$coupon'";
		$res_updt3=mysql_query_decide($sql_updt3) or $MSG=$MSG."\n".mysql_error_js();
	}
	else
	{
		$MSG=$MSG."\n".mysql_error_js();
	}
}

if($MSG!="")
{
	send_email($TO,$MSG,$SUB,$FROM,$CC);
}
?>
