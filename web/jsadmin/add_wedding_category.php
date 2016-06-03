<?php
/**************************************************************************************************************************
* FILE NAME	: add_wedding_category.php
* DESCRIPTION	: Allows people at the backend to add a new Category which are set Disapproved
* CREATION DATE	: 9 September, 2005
* CREATED BY	: Nikhil Tandon
* Copyright  2005, InfoEdge India Pvt. Ltd.
**************************************************************************************************************************/

include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{
	if($submit)
	{	

		$sql="INSERT INTO wedding_classifieds.CATEGORY VALUES ('','$LABEL','Y')";
		$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
		$msg="The New category has been created, set as Approved <br>  ";
	       	$msg .="<a href=\"mainAds.php?cid=$cid\">";
		$msg .="Go to Main Page </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$smarty->display("add_wedding_category.htm");
	}
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
