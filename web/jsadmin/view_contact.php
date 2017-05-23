<?php
function contact($profileid,$check,$affiliate=0)
{
	if($affiliate)
	{
	$sql="SELECT USERNAME,EMAIL,CONTACT,PHONE_RES,PHONE_MOB FROM jsadmin.AFFILIATE_DATA WHERE PROFILEID='$profileid'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	global $smarty;
	$smarty->assign("USERNAME",$row["USERNAME"]);
        $smarty->assign("EMAIL",$row["EMAIL"]);
        $smarty->assign("PHONE",$row["PHONE_RES"].",".$row["PHONE_MOB"]);
        $smarty->assign("ADDRESS",$row["CONTACT"]);

	}
	else
	{
	$sql="Select USERNAME,MESSENGER_ID,MESSENGER_CHANNEL,EMAIL,PHONE_RES,STD,PHONE_MOB,CONTACT,SCREENING,SOURCE,SHOWPHONE_RES,SHOWPHONE_MOB,SHOWADDRESS,SHOWMESSENGER from newjs.JPROFILE where PROFILEID = '".$profileid."'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	$uname = $row["USERNAME"];
	$contact_add = $row["CONTACT"];
	$msger_id = $row["MESSENGER_ID"];
	$source = $row["SOURCE"];
	$std = $row["STD"];
	$p_res = $row["PHONE_RES"];
	$p_mob = $row["PHONE_MOB"];
	$screening = $row["SCREENING"];
	$sp_res = $row["SHOWPHONE_RES"];
	$sp_mob = $row["SHOWPHONE_MOB"];
	$s_add = $row["SHOWADDRESS"];
	$s_msg = $row["SHOWMESSENGER"]; 
        $msg_chid=$row["MESSENGER_CHANNEL"];
	switch($msg_chid)
        {
                case 1: $msg_ch="Yahoo";
                        break;
                case 2: $msg_ch="MSN";
                        break;
                case 3: $msg_ch="Skype";
                        break;
                case 4: $msg_ch="";
                        break;
                case 5: $msg_ch="ICQ";
                        break;
                case 6: $msg_ch="Google Talk";
                        break;
                case 7: $msg_ch="Rediff Bol";
                        break;

        }

	if(($source == 'OFL_PROF')||($source == 'ofl_prof'))
	{
		//Query modified by Sadaf to avoid sub query
		//$sql_select="SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNLOG WHERE PROFILEID='$profileid' AND ASSIGN_DATE=(SELECT MAX( ASSIGN_DATE) FROM jsadmin.OFFLINE_ASSIGNLOG WHERE PROFILEID = '$profileid')";
		$sql_select="SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNLOG WHERE PROFILEID='$profileid' ORDER BY ASSIGN_DATE DESC LIMIT 1";
		$res_select=mysql_query_decide($sql_select) or logError($sql_select);
		if(mysql_num_rows($res_select))
		{
			$row_select=mysql_fetch_assoc($res_select);
			$operator=$row_select["OPERATOR"];
			mysql_free_result($res_select);
		
			//Operator details displayed for offline profiles : Modified by Sadaf [3280]
			$sql="Select EMAIL,PHONE,CENTER from jsadmin.PSWRDS where USERNAME = '$operator'";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$my2row=mysql_fetch_assoc($result);
			mysql_free_result($result);
			$op_email = $my2row["EMAIL"];
			$op_phone = $my2row["PHONE"];
			$op_center=$my2row["CENTER"];			
			$op_name = $operator;
		}
	}
	else 
	{
		$email = $row["EMAIL"];
	}
	if(isFlagSet("MESSENGER",$screening))
		$msger_id=$row["MESSENGER_ID"];
	else 
		$msger_id="";
	if((isFlagSet("EMAIL",$screening)) && !(strstr($row["EMAIL"],"jeevansathi.com")) )
		$email=$row["EMAIL"];
	else 
		$email="";	
	if(isFlagSet("PHONERES",$screening))
		$p_res=$row["PHONE_RES"];
	else 
		$p_res="";
	if(isFlagSet("PHONEMOB",$screening))
		$p_mob=$row["PHONE_MOB"];
	else 
		$p_mob="";	
	if(isFlagSet("CONTACT",$screening))
		$contact_add=$row["CONTACT"];
	else 
		$contact_add="";			
	if($email == "")
			$email = "Not Mentioned";					
	if($check)
	{
		if($contact_add == "")
			$contact_add = "Not Mentioned";
		else
			if($s_add != 'Y')
				$contact_add = "NA";	
		if($msger_id == "")
			$msger_id = "Not Mentioned";
		else
			if($s_msg != 'Y')
				$msger_id = "NA";
				
		if($p_res == "")
		{
			if($p_mob == "")
			{
				$contact = "Not Mentioned";
				
			}
			else 
				if($sp_mob == 'Y')
					$contact = $p_mob;
				else
				{
					$contact = "NA";
					$icon = "true";
				}					
		}
		else
		{ 
			if($p_mob == "")
				if($sp_res == 'Y')
					$contact = $std.'-'.$p_res;
				else 
				{
					$contact = "NA";
					$icon = "true";
				}		
			else 
				if($sp_mob == 'Y')
					if($sp_res == 'Y')
					{
						$contact = $p_mob." , ".$std.'-'.$p_res;						
					}
					else
						$contact = $p_mob;
				else 
					if($sp_res == 'Y')
						$contact = $std.'-'.$p_res;
					else
					{
						$contact = "NA";
						$icon = "true";
					}
		}				
											
	}
	else 
	{
		if($contact_add == "")
			$contact_add = "Not Mentioned";
		if($msger_id == "")
				$msger_id = "Not Mentioned";	
		if($p_res == "")
				if($p_mob == "")
					$contact = "Not Mentioned";
				else 
					$contact = $p_mob;				
		else 
			if($p_mob == "")
				$contact = $p_res;				
			else 
				$contact = $p_mob." , ".$p_res;
	}
	global $smarty;
	$smarty->assign("icon",$icon);
	if($msger_id=='Not Mentioned' || $msger_id=='NA')
                $smarty->assign("MESNGR",0);
	if($contact_add=='Not Mentioned' || $contact_add=='NA')
                $smarty->assign("ADR_P",0);
	if($contact=='Not Mentioned' || $contact=='NA')
                $smarty->assign("PHN_P",0);

        $smarty->assign("USERNAME",$uname);
	$smarty->assign("MESSENGER_CH",$msg_ch);
	$smarty->assign("MESSENGER_ID",$msger_id);
	$smarty->assign("EMAIL",$email);
	$smarty->assign("PHONE",$contact);
	$smarty->assign("ADDRESS",$contact_add);		
	$smarty->assign("OP_EMAIL",$op_email);
	$smarty->assign("OP_PHONE",$op_phone);
	//Operator details added by Sadaf for Mantis 3280
	$smarty->assign("OP_NAME",$op_name);
	$smarty->assign("OP_CENTER",$op_center);
	}
}
if($from_vp == "no")
{
	include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
	$data=authenticated($cid);
	if(isset($data))
	{	
		$smarty->assign("cid",$cid);
		contact($profileid,$check,$affiliate);
		$smarty->assign("View_cd",$from_vcd);
		$smarty->display("view_contact_layer.htm");
	}
	else 
	{
		$msg="Your session has been timed out  ";
	    $smarty->assign("cid",$cid);
	    $smarty->assign("MSG",$msg);    
	}	
}

?>
