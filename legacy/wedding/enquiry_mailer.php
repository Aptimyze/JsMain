<?php
/*********************************************************************************************
* FILE NAME     : enquiry_mailer.php
* DESCRIPTION   : To send a mail of inquiry from the client to our advertiser
* CREATION DATE : 9 September, 2005
* CREATED BY    : SHAKTI SRIVASTAVA
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
ini_set("max_execution_time","0");
include('connect.inc');
include('common_func_inc.php');
include('../profile/comfunc.inc');
include('display_result.inc');
                                                                                                 
$db=connect_db();

$sql_id="SELECT ENQUIRY.ID , ENQUIRY.NAME , ENQUIRY.EMAIL , ENQUIRY.REQUIREMENT , ENQUIRY.CONTACT_ADD , ENQUIRY.CONTACT_NUM , LISTINGS.EMAIL AS LEMAIL , LISTINGS.CONTACT_PERSON FROM wedding_classifieds.ENQUIRY,wedding_classifieds.LISTINGS WHERE ENQUIRY.ADV_ID=LISTINGS.ADV_ID AND ENQUIRY.SENT='N'";
$res_id=mysql_query_decide($sql_id) or logError("Error while populating category ".mysql_error_js(),$sql_id);

while($row_id=mysql_fetch_array($res_id))
{
	$to=$row_id['LEMAIL'];
	$subject="Enquiry from JeevanSathi.com customer.";
	$from="info@jeevansathi.com";
	
	$smarty->assign("ADV",$row_id['CONTACT_PERSON']);
	$smarty->assign("NAME",$row_id['NAME']);
	$smarty->assign("EMAIL",$row_id['EMAIL']);
	$smarty->assign("CONTACT_NUM",$row_id['CONTACT_NUM']);
	$smarty->assign("CONTACT_ADD",$row_id['CONTACT_ADD']);
	
	$smarty->assign("ENQ",$row_id['REQUIREMENT']);
	$msg=$smarty->fetch("mailer_enquiry.htm");

	send_email($to,$msg,$subject,$from);

	$sql_up="UPDATE wedding_classifieds.ENQUIRY SET SENT_DATE=now(),SENT='Y' WHERE ID='".$row_id['ID']."'";
	$res_up=mysql_query_decide($sql_up,$db) or logError("Error while populating category ".mysql_error_js(),$sql_up);
}	

?>
