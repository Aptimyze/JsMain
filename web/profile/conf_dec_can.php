<?php

include_once("search.inc");
include_once("connect.inc");
include_once("contact.inc");
$db=connect_db();
$dt=date("Y-m-d H:i:s");

	
	
	$data=authenticated();

	if(isset($data))
	{
			$smarty->assign("USERNAME",$other_username);
			if($status=='C')
			{
				$smarty->assign("MESSAGE","Are you sure you want to cancel your interest in $other_username");
				$smarty->assign("HEADLINE","Cancel");
			}
			else
			{
				$smarty->assign("MESSAGE","Are you sure you want to decline further communication with $other_username");
				$smarty->assign("HEADLINE","Decline");
			}
			if(isset($index))
				$smarty->assign("CALL_SUBMIT_FORM","submit_form('DECLINE','$index');");
			else
				$smarty->assign("CALL_SUBMIT_FORM","submit_form('DECLINE');$.colorbox.close();");
                        $smarty->display("confirm_cancel_decline.htm");	
	}
	else
	{
		$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
        	$smarty->display("login_layer.htm");
	        die;
	}	

?>
