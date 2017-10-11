<?
/*********************************************************************************************
* FILE NAME     : cat_enquiry_form.php
* DESCRIPTION   : Stores Enquiry details into table CATEGORY_ENQ
* CREATION DATE : 3 September, 2005
* CREATED BY    : SHAKTI SRIVASTAVA
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
include('connect.inc');
include('common_func_inc.php');
include('display_result.inc');
                                                                                                 
$db=connect_db();
                                                                                                 
populate_head();
populate_left();

$smarty->assign("WED_HEAD",$smarty->fetch("wedding_head.htm"));
$smarty->assign("WED_LEFT",$smarty->fetch("wedding_left.htm"));
$smarty->assign('WED_RIGHT',$smarty->fetch('wedding_right.htm'));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

if($submit)
{
	$ENQR_NAME=trim($ENQR_NAME);
	$ENQR_MAIL=trim($ENQR_MAIL);
	$ENQR_CONTACT_NUM=trim($ENQR_CONTACT_NUM);
	$ENQR_CONTACT_ADD=trim($ENQR_CONTACT_ADD);
	$ENQ=trim($ENQ);

	$msg="";
	maStripVARS("addslashes");
	$iserror=0;

	if($ENQR_NAME=="")
	{
		$iserror++;
		$smarty->assign("ENQR_NAME_ERR","1");
	}
	if($ENQR_MAIL=="")
	{
		$iserror++;
                $smarty->assign("ENQR_MAIL_ERR","1");
	}
	else if(checkemail($ENQR_MAIL))
	{
		$iserror++;
                $smarty->assign("ENQR_MAIL_ERR","1");
	}

	if($ENQR_CONTACT_ADD=="")
	{
		$iserror++;
		$smarty->assign("ENQR_ADD_ERR","1");
	}
	if($ENQR_CONTACT_NUM=="")
	{
		$iserror++;
		$smarty->assign("ENQR_NUM_ERR","1");
	}
	else if(checkphone($ENQR_CONTACT_NUM))
	{
		$iserror++;
                $smarty->assign("ENQR_NUM_ERR","1");
	}
	if($ENQ=="")
	{
		$iserror++;
		$smarty->assign("ENQ_ERR","1");
	}

	if($iserror>0)
	{
		maStripVARS("stripslashes");
		$smarty->assign("ENQR_NAME",$ENQR_NAME);
		$smarty->assign("ENQR_MAIL",$ENQR_MAIL);
		$smarty->assign("ENQR_CONTACT_ADD",$ENQR_CONTACT_ADD);
		$smarty->assign("ENQR_CONTACT_NUM",$ENQR_CONTACT_NUM);
		$smarty->assign("CATEGORY",$CATEGORY);
		$smarty->assign("error",$iserror);
		$smarty->assign("ENQ",$ENQ);
		$smarty->display("cat_enquiry_form.htm");
	}
	else
	{
		$sql="INSERT INTO wedding_classifieds.CATEGORY_ENQ VALUES('','$ENQR_NAME','$ENQR_CONTACT_NUM','$ENQR_CONTACT_ADD','$ENQR_MAIL','$CATEGORY',now(),'$ENQ','N','')";
		$res=mysql_query_decide($sql) or logError("Error while storing data. ".mysql_error_js(),$sql);

		$smarty->assign("MSG","Your query has been sent to all the advertisers in the category chosen by you.");
		$smarty->display("message.htm");
	}
		
}
else
{
	$smarty->display("cat_enquiry_form.htm");
}
?>
