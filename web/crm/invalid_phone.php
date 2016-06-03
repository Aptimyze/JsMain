<?php

	include('connect.inc');
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
	if(authenticated($cid))
	{
		$entryby = getname($cid);
		$message ='OPS';

		if($flag=='NEW_PROFILES')
		{
			$sql = "UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED ='Y' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		elseif($flag=='FIELD_SALES'){
			$sql = "UPDATE incentive.MAIN_ADMIN SET STATUS='C' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());	
		}
		$smarty->display("invalid_phone.htm");

                 // Unverify phone numbers when marked Invalid
                 if($profileid){

			$actionStatus='I';
			phoneUpdateProcess($profileid,'','',$actionStatus,$message,$entryby);
                  }
                  // End
	}
	else //user timed out
	{
		$msg="Your session has been timed out  ";
		$msg .="<a href=\"index.php\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
?>
