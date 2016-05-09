<?php
/*********************************************************************************************
* FILE NAME	: newAds.php
* DESCRIPTION	: Displays records for Approved or New Advertisers in the Wedding Directory
* CREATION DATE	: 2 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{

	if(!$fl)
		$fl='N';

	$sql_data="SELECT ADV_ID,NAME,ADDRESS,STATUS,CATEGORY,CONTACT_PERSON from wedding_classifieds.LISTINGS WHERE STATUS='$fl' ORDER BY APPROVE_DT DESC,NAME";
	$res=mysql_query_decide($sql_data) or die(mysql_error_js()."<BR>".$sql_data);

	while($row=mysql_fetch_array($res))
	{
			$sql2="SELECT LABEL FROM wedding_classifieds.CATEGORY WHERE ID='".$row['CATEGORY']."'";
			$res2=mysql_query_decide($sql2) or die(mysql_error_js()."<BR>".$sql2);
			$row2=mysql_fetch_array($res2);

			$det[]=array(	"ADV_ID"=>$row['ADV_ID'],
					"NAME"=>$row['NAME'],
					"ADDRESS"=>$row['ADDRESS'],
					"STATUS"=>$row['STATUS'],
					"CATEGORY"=>get_wedding_category($row['CATEGORY']),
					"CONTACT_PERSON"=>$row['CONTACT_PERSON']);
	}

	$smarty->assign("det",$det);
	$smarty->assign("fl",$fl);
	$smarty->display("newAds.htm");
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
