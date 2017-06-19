<?php
/**
*       Filename        :       paymentcontact.php
*       Created By      :       Abhinav
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");
if(authenticated($cid))
{
        $name= getname($cid);
	$center=getcenter($cid,'');
	if($submit)
	{
		$is_error=0;
		$sql="SELECT CITY_RES,PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
		$result=mysql_query_decide($sql);
		if($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];
			$city=$myrow['CITY'];
		}
		else
		{
			$smarty->assign("check_username","Y");
			$is_error++;
		}
		if(!$NAME1)
		{
                        $smarty->assign("check_name","Y");
                        $is_error++;
		}
		if((!$PHONE_RES || !is_numeric($PHONE_RES)) && (!$PHONE_MOB || !is_numeric($PHONE_MOB)))
		{
			$smarty->assign("check_res","Y");
                        $smarty->assign("check_mob","Y");
			$is_error++;
		}
		if(!$EMAIL)
		{
			$smarty->assign("check_email","Y");
                        $is_error++;
		}
		if(!$SERVICE)
		{
			$smarty->assign("check_service","Y");
                        $is_error++;
		}
		if(!$ADDRESS)
		{
                        $smarty->assign("check_address","Y");
                        $is_error++;
		}
		if($is_error>=1)
		{
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("NAME1",$NAME1);
			$smarty->assign("EMAIL",$EMAIL);
			$smarty->assign("PHONE_RES",$PHONE_RES);
			$smarty->assign("PHONE_MOB",$PHONE_MOB);
			$smarty->assign("SERVICE",$SERVICE);
			$smarty->assign("ADDRESS",$ADDRESS);	
			$smarty->assign("cid",$cid);
			$smarty->display("paymentcontact_crm.htm");
		}
		else
		{
			$sql2 = "INSERT INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,ENTRY_DT) VALUES ('$profileid','$USERNAME','$NAME1','$EMAIL','$PHONE_RES','$PHONE_MOB','$SERVICE','$ADDRESS','$city','$pin','Y',now())";
       			mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

			$msg .= "<a href=\"paymentcontact.php?name=$name&cid=$cid\">";
			$msg .= "Continue &gt;&gt;</a>";
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("incentive_msg.tpl");
		}	
	}
	else
	{

        	$smarty->assign("cid",$cid);
	        $smarty->display("paymentcontact_crm.htm");
	}
}
else//user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
