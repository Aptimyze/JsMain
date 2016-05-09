<?php
/*********************************************************************************************
* FILE NAME             : pullback_1min.php
* DESCRIPTION           : script for pulling back profiles of 1 min registration from a user and allocating to a particular user
* CREATION DATE         : 5 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");

if(authenticated($cid))
{
	$name=getname($cid);
	if($CMDAssign)
	{
		if(trim($num)=="" && !is_int($num))
                {
                        $msg="Please check the records to assign";
                }
		else
		{
			$sql="REPLACE INTO PULLBACK_LOG_1MIN(ID,PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME) SELECT ID,PROFILEID,ALLOTED_TO,'$new_allot_to',now() FROM MAIN_ADMIN_1MIN WHERE ALLOTED_TO='$old_alloted' ORDER BY ID DESC LIMIT $num";
			//$sql="INSERT INTO PULLBACK_LOG_1MIN(PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME) SELECT PROFILEID,ALLOTED_TO,'$new_allot_to',now() FROM MAIN_ADMIN_1MIN WHERE ALLOTED_TO='$old_alloted' ORDER BY ID DESC LIMIT $num";
			mysql_query_decide($sql) or die(mysql_error_js());

			$sql="UPDATE MAIN_ADMIN_1MIN SET ALLOTED_TO='$new_allot_to' WHERE ALLOTED_TO='$old_alloted' ORDER BY ID DESC LIMIT $num";
			mysql_query_decide($sql) or die("2".mysql_error_js());

			$msg=" You have successfully assigned $num records to $new_allot_to";
		}

		$msg .= "<a href=\"alternate_1min.php?name=$name&cid=$cid&val=$val\">";
                $msg .= "Continue &gt;&gt;</a>";

                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);

                $smarty->display("jsadmin_msg.tpl");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
