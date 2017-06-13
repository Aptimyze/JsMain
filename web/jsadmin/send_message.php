<?php
	include("connect.inc");	
	include("contact.inc");

	$db=connect_db();
	$sender_details=authenticated($checksum);	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	if($sender_details)
	{								
		$sender_profileid=$sender_details["PROFILEID"];
		if($profilechecksum!="")
		{
			$arr=explode("i",$profilechecksum);
			if(md5($arr[1])!=$arr[0])
			{
				showProfileError();
			}
			else 
			{
				$receiver_profileid=$arr[1];
			}	
		}
		else 
		{
			showProfileError();
		}
		//check if user can send a message
		if( ( in_array("F",getrights($receiver_profileid)) || in_array("F",getrights($sender_profileid)) ))
		{
			if(!$cust_message_page_shown)
			{	
				$drafts=get_drafts($sender_profileid);
				
				if($drafts)
					$smarty->assign("DRAFTS",$drafts);
				else 
					$smarty->assign("NODRAFT","1");
				$smarty->assign("RETURN_TO","send_message.php");
				$smarty->display("multiple_contact_message.htm");
				die;			
			}				
			send_message($sender_profileid,$receiver_profileid,get_contact_status($sender_profileid,$receiver_profileid),$custmessage,$savedraft,$markcc);
			$msg="Your message has been sent succesfully ";
			$smarty->assign("MSG",$msg);
			$smarty->display("contact_result.htm");													
		}
		else 
		{
			$msg="Upgrade your membership ";
			$smarty->assign("MSG",$msg);
			$smarty->display("contact_result.htm");													
		}			
	}
	else 
	{
		Timedout();
	}		
?>
