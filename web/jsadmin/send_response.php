
<?php
	include("connect.inc");	
	include("contact.inc");
	$db=connect_db();
	$sender_details=authenticated($checksum);	
	//if the sender is authenticated
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
		if($SELaction)
		{
			$flag_response=$SELaction;			
		}	
		elseif($accept)
			$flag_response="A";
				
		if( ( in_array("F",getrights($sender_profileid)) || in_array("F",getrights($receiver_profileid)) ))
			$paid=1;
		else 
			$paid=0;
			
		if($paid)
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
				$smarty->assign("ACTION",$SELaction);
				$smarty->assign("FLAG_RESPONSE",$flag_response);
				$smarty->assign("RETURN_TO","send_response.php");
				$smarty->display("multiple_contact_message.htm");
				die;			
			}						
		}
		$check=explode(",",$check_str);
		foreach ($check as $profilechecksum)
		{
			$arr=explode("i",$profilechecksum);	
			$receiver_profileid=$arr[1];
			$contact_status=get_contact_status($sender_profileid,$receiver_profileid);																	
			send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc);
		}
		$msg="Your response has been sent succesfully to the users ";
		$smarty->assign("MSG",$msg);
		$smarty->display("contact_result.htm");																		
	}	
	else 
	{
		Timedout();
	}		
?>
