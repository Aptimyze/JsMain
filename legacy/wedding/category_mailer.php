<?
/*********************************************************************************************
* FILE NAME     : category_mailer.php
* DESCRIPTION   : Sends mails to all advertisers of a certain category.
* CREATION DATE : 9 September, 2005
* CREATED BY    : SHAKTI SRIVASTAVA
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
include('connect.inc');
include('common_func_inc.php');
include('../profile/comfunc.inc');
include('display_result.inc');
                                                                                                 
$db=connect_db();

$sql="SELECT * FROM wedding_classifieds.CATEGORY_ENQ WHERE SENT='N' AND STATUS='A'";
$res=mysql_query_decide($sql) or logError("Error while fetching data from CATEGORY. ".mysql_error_js(),$sql);
while($row=mysql_fetch_array($res))
{
	$sql2="SELECT * FROM wedding_classifieds.LISTINGS WHERE CATEGORY='".$row['CATEGORY']."' AND STATUS='A'";
	$res2=mysql_query_decide($sql2) or logError("Error while fetching data from LISTINGS. ".mysql_error_js(),$sql2);
	while($row2=mysql_fetch_array($res2))
	{
		$to=$row2['EMAIL'];
		$from="info@jeevansathi.com";

		$subject="Enquiry from JeevanSathi.com customer";

		$smarty->assign("ADV",$row2['CONTACT_PERSON']);
		$smarty->assign("NAME",$row['NAME']);
		$smarty->assign("EMAIL",$row['EMAIL']);
		$smarty->assign("CONTACT_NUM",$row['CONTACT_NUM']);
		$smarty->assign("CONTACT_ADD",$row['ADDRESS']);
		$smarty->assign("ENQ",$row['ENQUIRY']);

		$msg=$smarty->fetch("mailer_enquiry.htm");

//		$smarty->display("mailer_enquiry.htm");

		send_email($to,$msg,$subject,$from);
		
		$sql3="UPDATE wedding_classifieds.CATEGORY_ENQ SET SENT='Y' WHERE ID='".$row['ID']."'";
		$res3=mysql_query_decide($sql3) or logError("Error while updating CATEGORY_ENQ. ".mysql_error_js(),$sql3);
	}
}

?>
