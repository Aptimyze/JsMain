<?php

/****************************************************************************************************************************
*       FILENAME        : 	change_template.php 
	CREATED By 	: 	Gaurav Arora on 12 May 2005       
	INCLUDED        : 	connect.inc
*                              functions used :authenticated
* *       DESCRIPTION     : this file is used to change the template for a particular source.
*
****************************************************************************************************************************/
include("connect.inc");
if(authenticated($cid))
{
	$sql = "Update BANNER_TEMPLATE SET STATUS='I' where STATUS = 'A' and SOURCEID= '$source'";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());
	
	if($change_template==1)		// if request is to change the template for a source 
	{
                $sql1 = "Update BANNER_TEMPLATE SET STATUS='A' where ID = '$radio1'" ;
		mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
        }                                                     
                                $msg="Record Updated.<br>  ";
                                $msg .="<a href=\"manage_template_input.php?cid=$cid&name=$user\">";
                                $msg .="Continue </a>";
				$smarty->assign("cid",$cid);
				$smarty->assign("name",$user);
				$smarty->assign("HEAD",$smarty->fetch("head.htm"));
                                $smarty->assign("MSG",$msg);
                                $smarty->display("jsadmin_msg.tpl");

}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
