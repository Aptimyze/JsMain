<?php

/*********************************************************************************************
* FILE NAME     : bank_mod.php
* DESCRIPTION   : Adds and Modifies Banks' names, etc
* CREATION DATE : 7 June, 2005
* CREATEDED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("../jsadmin/connect.inc");
//$db=connect_db();

$data=authenticated($checksum);

if(isset($data))
{
	$resid=$data["USER"];
	
	$sql_user="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$resid'";
	$res_user=mysql_query_decide($sql_user) or die("Error while selecting USERNAME. ".mysql_error_js());
	$row_user=mysql_fetch_array($res_user);
	$user=$row_user['USERNAME'];

	if(trim($bank_name))
	{
		if($submit_add)
		{
			$sql_ins="INSERT INTO billing.BANK VALUES('','$bank_name','$user',now())";
			$res_ins=mysql_query_decide($sql_ins) or die("Error while adding record. ".mysql_error_js());
			$msg="Record Added Successfully<br>Go Back to <a href=\"bank_main.php?checksum=$checksum\">Main Page</a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
		else if($submit_mod)
		{
			$sql_mod="UPDATE billing.BANK SET NAME='$bank_name',UPDATED_BY='$user',UPDATED_ON=NOW() WHERE ID='$ID'";
			$res_mod=mysql_query_decide($sql_mod) or die("Error while updating data. ".mysql_error_js());
			$msg="Record Updated Succesfully<br>Go Back to <a href=\"bank_main.php?checksum=$checksum\">Main Page</a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$msg="Bank Name is not Given.<br>Go Back to <a href=\"bank_main.php?checksum=$checksum\">Main Page</a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
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
