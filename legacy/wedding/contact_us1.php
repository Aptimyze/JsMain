<?
/*********************************************************************************************
* FILE NAME     : contact_us1.php
* DESCRIPTION   : Stores Enquiry details into table ENQUIRY
* CREATION DATE : 3 September, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
include('connect.inc');
include('common_func_inc.php');
include('display_result.inc');
                                                                                                 
$db=connect_db();
                                                                                                 
populate_head();
populate_left();
$smarty->assign('WED_HEAD',$smarty->fetch('wedding_head.htm'));
$smarty->assign('WED_LEFT',$smarty->fetch('wedding_left.htm'));
$smarty->assign('WED_RIGHT',$smarty->fetch('wedding_right.htm'));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

if(!$flag)
{
        $smarty->display('contact_us1.htm');
}
else
{
	maStripVARS("addslashes");
	$k=validation();

	if($k)
	{
		$sql_id="INSERT INTO wedding_classifieds.CONTACTUS VALUES('','$NAME','$EMAIL','$CONTACTNUMBER','$CONTACTADDRESS','$REQUIREMENT',NOW())";
		$res_id=mysql_query_decide($sql_id,$db) or logError("Error while populating category ".mysql_error_js(),$sql_id);
		$smarty->assign('WED_HEAD',$smarty->fetch('wedding_head.htm'));
		$smarty->assign('WED_LEFT',$smarty->fetch('wedding_left.htm'));
//		$smarty->display('index.htm');
		$msg="Thank You for submitting your enquiry/feedback.<BR>We will get back to you at the earliest.";
		$smarty->assign("MSG",$msg);
		$smarty->display('message.htm');
	}
	else
	{
		maStripVARS("stripslashes");
		$incomplete=array(      "NAME"=>$NAME,
					"CONTACTNUMBER"=>$CONTACTNUMBER,
					"CONTACTADDRESS"=>$CONTACTADDRESS,
					"EMAIL"=>$EMAIL,	
					"REQUIREMENT"=>$REQUIREMENT);

		$smarty->assign('incomplete',$incomplete);
		$smarty->assign('WED_HEAD',$smarty->fetch('wedding_head.htm'));
		$smarty->assign('WED_LEFT',$smarty->fetch('wedding_left.htm'));
		$smarty->display('contact_us1.htm');
	}
}
											 
?>
