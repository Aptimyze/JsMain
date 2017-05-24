<?php
	include("connect.inc");	
	include("contact.inc");

	$db=connect_db();
	$sender_details=authenticated($checksum);	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	if($sender_details)
	{								
		$sender_profileid=$sender_details["PROFILEID"];
		//check if user can send a message
		if(in_array("F",getrights($sender_profileid)))
		{			
			if(!$cust_message_page_shown)
			{	
				$drafts=get_drafts($sender_profileid);
				
				if($drafts)
					$smarty->assign("DRAFTS",$drafts);
				else 
					$smarty->assign("NODRAFT","1");
				$check_str=implode($check,",");				
				$smarty->assign("CHECK_STR",$check_str);
				$smarty->assign("ASK_FOR_OVERWRITE",1);	
				$smarty->assign("RETURN_TO","get_cust_message.php");
				$smarty->display("multiple_contact_message.htm");
				die;			
			}	
			$check=explode(",",$check_str);			
			foreach($check as $profilechecksum)
			{
				$arr=explode("i",$profilechecksum);	
				$receiver_profileid=$arr[1];
				$flag_again=1;
				make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,$overwrite);							
			}
			$msg="You have successfully contacted the users ";
			$smarty->assign("MSG",$msg);
			$smarty->display("contact_result.htm");
		}
		else 
		{
			foreach($check as $profilechecksum)
			{
				$arr=explode("i",$profilechecksum);	
				$receiver_profileid=$arr[1];
				$flag_again=1;
				make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,$overwrite);				
			}
			$msg="You have successfully contacted the users ";
			$smarty->assign("MSG",$msg);
			$smarty->display("contact_result.htm");				
		}	
	}
	else 
	{
		Timedout();
	}		
?>
