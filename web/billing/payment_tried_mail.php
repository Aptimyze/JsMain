<?php
/**************************************************************************************************************************
DESCRIPTION	: This file sends mail to those user's who tried online payment but their payment was unsuccessful.
CREATED BY	: Ankit Aggarwal
DATE		:March 29 2009.
**************************************************************************************************************************/
$flag_using_php5 = 1;
if(!$_SERVER['DOCUMENT_ROOT'])
	$_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once(JsConstants::$docRoot.'/jsadmin/connect.inc');
include_once(JsConstants::$docRoot.'/crm/func_sky.php');
//finding date (two days before the current date)
$two_days_before = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
$old_date = date('Y-m-d',$two_days_before);
$db = connect_ddl();
$today = date('Y-m-d');
$serObj = new Services;
$subject = "Unsuccessful Payment? Ensure Success Now!";
$from = "feedback@jeevansathi.com";
$cc = "";

	$sql="create TEMPORARY TABLE billing.failed_tab select MAX(ID) as ID,PROFILEID from billing.ORDERS where ENTRY_DT >='$old_date 00:00:00' AND ENTRY_DT <='$old_date 23:59:59' GROUP BY PROFILEID";

        mysql_query_decide($sql) or logerror_cron($sql);

/*Select those user's who tried online payment but were unsuccessful (from ORDERS table whose STATUS is '' or 'N')*/
$sql_unpd = "SELECT a.PROFILEID, a.SERVICEMAIN, a.ADDON_SERVICEID,a.USERNAME,a.BILL_EMAIL FROM billing.ORDERS as a,billing.failed_tab as b WHERE a.ID=b.ID AND a.STATUS IN('','N') ORDER BY a.ID";
$res_unpd = mysql_query_decide($sql_unpd) or logerror_cron($sql_unpd);
while($row_unpd = mysql_fetch_array($res_unpd))
{
	$pid = $row_unpd['PROFILEID'];
	$to_user = $row_unpd['USERNAME'];
	$to_email = $row_unpd['BILL_EMAIL'];
	$old_date_time=$old_date." 00:00:00";
	/*Query to find whether the user has made payment after trying online payment (from PURCHASES table STATUS is 'DONE' for that user)*/
	$sql_now = "SELECT DISTINCT PROFILEID FROM billing.PURCHASES WHERE PROFILEID='$row_unpd[PROFILEID]' AND ENTRY_DT >= '$old_date_time' AND STATUS='DONE' ORDER BY ENTRY_DT DESC";
	$res_now = mysql_query_decide($sql_now) or logerror_cron($sql_now);

	$row_now=mysql_fetch_array($res_now);
	//if user is still not subscribed to any service.
	if($row_now["PROFILEID"]=="")
	{
		$sql_new = "SELECT * FROM billing.FAILED_PAYMENT_MAILS WHERE PROFILEID='$row_unpd[PROFILEID]'";
		$res_new = mysql_query_decide($sql_new) or logerror_cron($sql_new);
		$row_new = mysql_fetch_array($res_new);
		if($row_new['COUNT'] > 0)
		{
			if($row_new['COUNT'] < 3)
			{
				//if mail initially sent once
				if($row_new['COUNT'] == 1)
					list($yy,$mm,$dd) = explode('-',$row_new['FIRST_MAIL_DT']);
				else
					list($yy,$mm,$dd) = explode('-',$row_new['SECOND_MAIL_DT']);


				//check date difference
				$last_mail_date = mktime(0,0,0,$mm,$dd,$yy);
				$seven_days_back = mktime(0,0,0,date('m'),date('d')-7,date('Y'));
				//if date difference greater than 7
				if($seven_days_back == $last_mail_date)
				{

					//get mail content
					 $mail_content = content_to_send($row_unpd['SERVICEMAIN'],$row_unpd['ADDON_SERVICEID'],$to_user);
						
					//send mail
					$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to_email,"EMAIL_TYPE"=>"29"),$row_unpd['PROFILEID']);
			                $canSend = $canSendObj->canSendIt();
			                if($canSend)
                			{
						send_mail($to_email,$cc,$bcc,$mail_content,$subject,$from);
					}
					if($row_new['COUNT'] == 1)
						$sql_upd = "UPDATE billing.FAILED_PAYMENT_MAILS SET COUNT=COUNT+1, ENTRY_DT=now(),SECOND_MAIL_DT=now() WHERE PROFILEID = '$row_unpd[PROFILEID]'";
					else
						$sql_upd = "UPDATE billing.FAILED_PAYMENT_MAILS SET COUNT=COUNT+1, ENTRY_DT=now(),THIRD_MAIL_DT=now() WHERE PROFILEID = '$row_unpd[PROFILEID]'";

					mysql_query_decide($sql_upd) or logerror_cron($sql_upd);
				}
			}
		}
		else
		{
			//get mail content
			$mail_content = content_to_send($row_unpd['SERVICEMAIN'],$row_unpd['ADDON_SERVICEID'],$to_user);
			//send mail
			$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to_email,"EMAIL_TYPE"=>"29"),$row_unpd['PROFILEID']);
                        $canSend = $canSendObj->canSendIt();
                        if($canSend)
                        {
				send_mail($to_email,$cc,$bcc,$mail_content,$subject,$from);
			}

			$sql_ins = "INSERT INTO billing.FAILED_PAYMENT_MAILS(PROFILEID,ENTRY_DT,COUNT,FIRST_MAIL_DT) VALUES('$row_unpd[PROFILEID]',now(),1,now())";
			mysql_query_decide($sql_ins) or logerror_cron($sql_ins);
		}
	}
}

/*if user tried online payment only once, then send mail to him once in seven days till his COUNT becomes 3*/
$seven_days_before = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
$seven_days_before_time=$seven_days_before." 00:00:00";
$sql_mail = "SELECT * FROM billing.FAILED_PAYMENT_MAILS WHERE COUNT < 3 AND ENTRY_DT = '$seven_days_before'";
$res_mail = mysql_query_decide($sql_mail) or logerror_cron($sql_mail);
while($row_mail = mysql_fetch_array($res_mail))
{
	/*Query to find whether the user has made payment after trying online payment (from PURCHASES table STATUS is 'DONE' for that user)*/
	$sql_now = "SELECT DISTINCT PROFILEID FROM billing.PURCHASES WHERE PROFILEID='$row_mail[PROFILEID]' AND ENTRY_DT >= '$seven_days_before_time' AND STATUS='DONE' ORDER BY ENTRY_DT DESC";
        $res_now = mysql_query_decide($sql_now) or logerror_cron($sql_now);
	if(!mysql_num_rows($res_now))
	{
		$row_now = mysql_fetch_array($res_now);

		//finding the selected service
		$sql_serid = "SELECT SERVICEMAIN, ADDON_SERVICEID,USERNAME,BILL_EMAIL FROM billing.ORDERS WHERE PROFILEID='$row_now[PROFILEID]'";
		$res_serid = mysql_query_decide($sql_serid) or logerror_cron($sql_serid);
		$row_serid = mysql_fetch_array($res_serid);
		//end of - finding the selected service

		$to_user = $row_serid['USERNAME'];
		$to_email = $row_serid['BILL_EMAIL'];

		//get mail content
		$mail_content = content_to_send($row_serid['SERVICEMAIN'],$row_serid['ADDON_SERVICEID'],$to_user);

		//send mail
		$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to_email,"EMAIL_TYPE"=>"29"),$row_now['PROFILEID']);
                $canSend = $canSendObj->canSendIt();
                if($canSend)
                {
			send_mail($to_email,$cc,$bcc,$mail_content,$subject,$from);
		}
		if($row_mail['COUNT']==1)
			$field_to_update = "SECOND_MAIL_DT";
		else
			$field_to_update = "THIRD_MAIL_DT";

		$sql_upd = "UPDATE billing.FAILED_PAYMENT_MAILS SET COUNT=COUNT+1,$field_to_update=now(),ENTRY_DT=now() where PROFILEID='$row_now[PROFILEID]' ";
		mysql_query_decide($sql_upd) or logerror_cron($sql_upd);
	}
}
/*end of - if user tried online payment only once, then send mail to him once in seven days till his COUNT becomes 3*/

function content_to_send($main_service_id,$addon_service_id,$to_user)
{
	global $smarty,$serObj;
	//finding the selected service name
//	echo "main-$main_service_id----add- $addon_service_id---user-$to_user";
	$main_service_id=(substr($main_service_id, -1)==',')?$main_service_id.$addon_service_id:$main_service_id.",".$addon_service_id;	
	$ser_arr=$serObj->getServiceName($main_service_id);
	//end of finding the selected service name
	//if the user selected any addon service
	$url_to_membership_page = "http://www.jeevansathi.com/profile/payment.php?services=$main_service_id";
	$url_to_membership_page1 = "http://www.jeevansathi.com/profile/payment.php?services=$main_service_id&mode=cheque";
    
    $mailerServiceObj = new MailerService();
    $mailerLinks = $mailerServiceObj->getLinks();
    $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];
    
    $smarty->assign("fromEmailId","feedback@jeevansathi.com");
	$smarty->assign("USERNAME",$to_user);
	$smarty->assign("MAIN_SERVICE",$ser_arr);
	$smarty->assign("URL_TO_MEMBERSHIP_PAGE",$url_to_membership_page);
	$smarty->assign("URL_TO_MEMBERSHIP_PAGE1",$url_to_membership_page1);
    $smarty->assign("unsubscribeLink",$unsubscribeLink);
	$content = $smarty->fetch('payment_tried_mail.htm');
	
	return $content;
}
function logerror_cron($query)
{
	$err_msg = "QUERY : ".$query."\n ERROR : ".$query.mysql_error_js()."\nDATE : ".date('Y-m-d G:i:s',time()+37800);
	$error_msg = "echo \"".$err_msg;
        $msg = $error_msg."\" >> ".JsConstants::$docRoot."/billing/log_failed_mailer.txt";
        passthru($msg);
	die();
}
?>
