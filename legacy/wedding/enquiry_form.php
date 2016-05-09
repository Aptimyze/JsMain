<?
/*********************************************************************************************
* FILE NAME     : enquiry_form.php
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
$smarty->assign('FOOT',$smarty->fetch('foot.htm'));

maStripVARS("stripslashes");
if(!$submit)
{
	$sql_id="SELECT NAME,ADV_ID FROM wedding_classifieds.LISTINGS where ADV_ID='$ADV_ID'";
	$res_id=mysql_query_decide($sql_id,$db) or logError("Error while selecting from LISTINGS ".mysql_error_js(),$sql_id,"ShowErrTemplate");
	$row_id=mysql_fetch_array($res_id);
	$result=array(
               	        "adv_id"=>$row_id['ADV_ID'],
                       	"name"=>$row_id['NAME'],
			);
	$smarty->assign('result',$result);
	maStripVARS("addslashes");
	$smarty->display('enquiry_form.htm');
	
}
else
{	
	$k=validation();
	if($k)
	{
		$sql_id="INSERT INTO wedding_classifieds.ENQUIRY
			 VALUES('','$ADV_ID','$NAME','$CONTACTNUMBER','$CONTACTADDRESS','$EMAIL','$REQUIREMENT','','N')";
		$res_id=mysql_query_decide($sql_id,$db) or logError("Error while inserting into ENQUIRY ".mysql_error_js(),$sql_id);

		$msg="Your enquiry has been sent to the advertiser. <br>Thank you for using JeevanSathi.com's Wedding Gallery";
		$smarty->assign("MSG",$msg);
		
		maStripVARS("addslashes");
		$smarty->display("message.htm");
	}
	else
	{
		$sql_id="SELECT NAME FROM wedding_classifieds.LISTINGS where ADV_ID='$flag'";
	        $res_id=mysql_query_decide($sql_id,$db) or logError("Error while seleting from LISTINGS ".mysql_error_js(),$sql_id);
        	$row_id=mysql_fetch_array($res_id);

		 $incomplete=array(	"NAME"=>$NAME,
					"CONTACTNUMBER"=>$CONTACTNUMBER,
					"CONTACTADDRESS"=>$CONTACTADDRESS,
					"CITY"=>$CITY,
					"EMAIL"=>$EMAIL,
					"REQUIREMENT"=>$REQUIREMENT,
				  );
		$smarty->assign('incomplete',$incomplete);
		$result=array( "adv_id"=>$flag,
				"name"=>$row_id['NAME'],
				);
		$smarty->assign('result',$result);
		maStripVARS("addslashes");
		$smarty->display('enquiry_form.htm');
	}

}
?>
