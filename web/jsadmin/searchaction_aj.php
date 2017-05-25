
<?php
	include("connect.inc");	
	include("contact.inc");
	$db=connect_db();
	$sender_details=authenticated($checksum);	
	//if the sender is authenticated
	if($SELaction1)
		$SELaction=$SELaction1;
	else
		$SELaction=$SELaction2;
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->assign("SCRIPTNAME",$scriptname);
	$smarty->assign("PAGENO",$pageno);
	if($sender_details)
	{								
		switch ($SELaction)
		{
			case "C":	//contact
						$sender_profileid=$sender_details["PROFILEID"];
						if(in_array("F",getrights($sender_profileid)))
							$paid=1;
						else 
							$paid=0;	
						$smarty->assign("SEARCHCHECKSUM",$searchchecksum);
						if($paid)
						{
							if(!$cust_message_page_shown)
							{	
								check_str=implode($check,",");				
								$smarty->assign("CHECK_STR",$check_str);								
								$smarty->assign("ACTION",$SELaction);
								$smarty->assign("RETURN_TO","searchaction_aj.php");
								$smarty->display("multiple_contact_message.htm");
								die;			
							}						
						}
						$check=explode(",",$check_str);
						foreach ($check as $receiver_profileid)
						{
							$contact_status=get_contact_status($sender_profileid,$receiver_profileid);						
							if($contact_status)//There has been some contact 
							{
								if($contact_status=="I")
								{														
									$flag_again=1;
									make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc);
									$receivers_contacted[] = array("NAME" => get_name($receiver_profileid),
															 "MESSAGE" => "Initial contact made");					 					 								   									
								}	
								else 
								{
									$receivers_with_history[]=array("NAME" => get_name($receiver_profileid),					 
					 								   	   "PROFILECHECKSUM" => md5($receiver_profileid)."i".$receiver_profileid);
									//show message that accept or decline individually
								}	
							}
							else //There has been no contact earlier so only initial contact is possible
							{
								//check if contact can be made
								if(can_contact($sender_profileid,$receiver_profileid,$sender_details,$error_msg))
								{
									$flag_again=0;
									make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc);
									$receivers_contacted[] = array("NAME" => get_name($receiver_profileid),
															 "MESSAGE" => "Initial contact made");											
								}
								else 
								{
									$receivers_contacted[] = array("NAME" => get_name($receiver_profileid),
															 "MESSAGE" => $error_msg);					 					  
								}		
							}	
							$smarty->assign("RECEIVERS_CONTACTED",$receivers_contacted);
							$smarty->assign("RECEIVERS_WITH_HISTORY",$receivers_with_history);
							$smarty->display("multiple_contact_results.htm");
						}								
						break;		
			case "B"://Bookmark
						break;
			case "O"://open in new widow
						break;
		}	
	}
	else 
	{
		Timedout();
	}		
?>
