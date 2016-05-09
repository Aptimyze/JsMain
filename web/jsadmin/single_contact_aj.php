<?php

	include("connect.inc");	
	include("contact.inc");
	$db=connect_db();
	$sender_details=authenticated($checksum);	
	//if the sender is authenticated
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
		//Get the contact status between the sender and the receiver
		$contact_status=get_contact_status($sender_profileid,$receiver_profileid);					
		//There has been some contact already so no need to check whether contact can be made
		if($contact_status)							   
		{
			switch($status)//status comes from the previous htm or tpl 
			{
				case "I":	//It is an initial contact
							//It means that in the contact log the contact status should only be initial contact
							if($contact_status=="I")
							{															
								$flag_again=1;
								make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc);
								$msg="You have successfully contacted this user ";								  
								$smarty->assign("MSG",$msg);
								$smarty->display("contact_result.htm");
							}
							elseif($contact_status=="RD") 
							{															
								$error_msg="You have been declined by this user earlier ";								
								$smarty->assign("MSG",$error_msg);
								$smarty->display("contact_result.htm");
							}		
							else 
							{
								$error_msg="This operation is not allowed ";
								$smarty->assign("MSG",$error_msg);
								$smarty->display("contact_result.htm");									
							}											
							break;
				case "A":	//The sender accepts the receiver's initial contact
							if($contact_status=="RI")//to check if the sender has received an initial contact
							{
								$flag_response="A";//accept
								send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc);
								$msg="Your response has been sent succesfully ";
								$smarty->assign("MSG",$msg);
								$smarty->display("contact_result.htm");								
							}
							else 
							{
								$error_msg="This operation is not allowed ";
								$smarty->assign("MSG",$error_msg);
								$smarty->display("contact_result.htm");									
							}		
							break;								
				case "D":	//The sender declines the receiver's initial contact
							if($contact_status=="RI")//to check if the sender has received an initial contact	
							{
								$flag_response="D";//Decline
								send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc);
								$msg="Your response has been sent succesfully ";
								$smarty->assign("MSG",$msg);
								$smarty->display("contact_result.htm");								
							}	
							else 
							{
								$error_msg="This operation is not allowed ";
								$smarty->assign("MSG",$error_msg);
								$smarty->display("contact_result.htm");									
							}	
							break;
				case "M":	//send message
							if($contact_status=="A" || $contact_status=="RA")
							{
								send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
								$msg="Your message has been sent succesfully ";
								$smarty->assign("MSG",$msg);
								$smarty->display("contact_result.htm");								
							}
							else 
							{
								$error_msg="This operation is not allowed ";
								$smarty->assign("MSG",$error_msg);
								$smarty->display("contact_result.htm");									
							}									
			}
		}
		//no contact has been made earlier so it is necessary to check whether contact can be made
		//it also means that only initial contact can be made
		else 
		{	
			if(can_contact($sender_profileid,$receiver_profileid,$sender_details,$error_msg))
			{
				//check if the user is only trying to make initial contact
				if($status="I")
				{
					$flag_again=0;
					make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc);
					$msg="You have successfully contacted this user ";
					$smarty->assign("MSG",$msg);
					$smarty->display("contact_result.htm");
				}
				else 
				{
					$error_msg="This operation is not allowed ";
					$smarty->assign("MSG",$error_msg);
					$smarty->display("contact_result.htm");									
				}	
			}	
			else 
			{
				$smarty->assign("MSG",$error_msg);
				$smarty->display("contact_result.htm");									
			}	
		}												
	}																		
	else
	{
		Timedout();
	}
?>