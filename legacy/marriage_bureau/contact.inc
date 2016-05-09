<?php
/**
*       Filename        :       contact.inc
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done for that.
**/

	include_once("flag.php");
	//include_once("functions.inc");
	//FILE NOT IN USE
	include_once($_SERVER["DOCUMENT_ROOT"]."/profile/contacts_functions.php");

	mail('lavesh.rawat@jeevansathi.com,puneet.makkar@jeevansathi.com','checking whether this file is used or not','contact.inc of marriage bureau');

		
	//function to send accept or decline or cancel response 
	function send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc,$resend=0)
	{
		global $smarty,$SITE_URL,$mbureau,$sender_details,$db;
		
		//Get the contact id
		//$contact_id=get_contact_id($receiver_profileid,$sender_profileid);

		//added by sriram,Lavesh for updation of fields in leftpanel,view all stats.

		//Sharding of CONTACTS done by Sadaf
		$sendersIn=$sender_profileid;
		$receiversIn=$receiver_profileid;
		$contactResult=getResultSet("CONTACTID,TYPE",$sendersIn,'',$receiversIn);
		$type=$contactResult[0]["TYPE"];
		$contact_id=$contactResult[0]["CONTACTID"];
		unset($contactResult);
		
		if($flag_response=='A')
		{
			if($type=="I")
			{
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1, OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");

                                $sql_resp="UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1,ACC_ME=ACC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
			elseif($type == 'D')
			{
                                $sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1,DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$sender_profileid'";
                                mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				$sql_resp="UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1,ACC_ME=ACC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
			elseif($type == 'C')
			{
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME+1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1 WHERE PROFILEID='$receiver_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");	
			}
		}
		elseif($flag_response=='D')
		{
			if($type == 'I')
			{
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1,DEC_BY_ME=DEC_BY_ME+1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");

				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1,DEC_ME=DEC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				
			}
			elseif($type == 'A')
			{
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1,DEC_BY_ME=DEC_BY_ME+1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1,DEC_ME=DEC_ME+1 WHERE PROFILEID='$receiver_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
		}
		elseif($flag_response=='C')
		{
			//if cancelled after acceptance then decrement those who accepted me of sender and those who I accepted of the receiver.
			if($type == 'A')
			{
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$sender_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$receiver_profileid'";
				mysql_query_decide($sql_resp,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
		}
		//added by sriram,lavesh for updation of fields in leftpanel

		$custmessage=trim($custmessage);

		//Commented by lavesh(for limiting query on Jprofile)
		//if(!get_mail_preference($receiver_profileid))
			//$SERVICE_MESSAGE_UNSUBSCRIBED=1;
			
		// if message is not being resent after being unblocked	
		if (!$resend)
		{
			//make an entry in the contact log

			updateContactsTable($sender_profileid,$receiver_profileid,$contact_id,$flag_response,"Y");
			
			/*compatibility code starts*/
			
			if($flag_response!='C')
				$receiver_3d=get_variables_for_3d($receiver_profileid,1);
			else
				$sender_3d=get_variables_for_3d($sender_profileid);
			if($sender_details['GENDER']=='F')
				$receiver_gender='M';
			else
				$receiver_gender='F';
			if($flag_response!='C')
				$points=calculate_3d_points($receiver_3d['CASTE'],$receiver_3d['RELIGION'],$receiver_3d['MTONGUE'],$receiver_3d['INCOME'],$sender_details['GENDER'],$sender_profileid);
			else
				$points=calculate_3d_points($sender_3d['CASTE'],$sender_3d['RELIGION'],$sender_3d['MTONGUE'],$sender_3d['INCOME'],$receiver_gender,$receiver_profileid,1);
			
			if($points>265)
				$compatibility='A';
			elseif($points>150)
				$compatibility='B';
			else
				$compatibility='C';
			
			if($flag_response!='C')
				$sql_3d="INSERT INTO newjs.COMPATIBILITY (SENDER,RECEIVER,TYPE,COMPATIBILITY,DATE) values ('$receiver_profileid','$sender_profileid','$flag_response','$compatibility',now())";
			else
				$sql_3d="INSERT INTO newjs.COMPATIBILITY (SENDER,RECEIVER,TYPE,COMPATIBILITY,DATE) values ('$sender_profileid','$receiver_profileid','$flag_response','$compatibility',now())";
			
			mysql_query_decide($sql_3d,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_3d,"ShowErrTemplate");
			/*compatibility code ends*/

		}

		//mailing part
		//form the content of the mail

		//Modified by lavesh(for limiting query on Jprofile)

		//$smarty->assign("RECEIVERNAME",get_name($receiver_profileid));
		/*Added By Shakti on 9 Feb,2006 since gender of the receiver is required in the template
		$receivers_det=get_profile_details($receiver_profileid);
		$smarty->assign("REC_GENDER",$receivers_det['GENDER']);
		//End of additions*/
		$sqlnew="SELECT GENDER,COUNTRY_RES,SUBSCRIPTION,USERNAME,EMAIL,SERVICE_MESSAGES FROM JPROFILE WHERE PROFILEID='$receiver_profileid'";
		$resnew=mysql_query_decide($sqlnew,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
		$rownew=mysql_fetch_array($resnew);
		$gender_reciever=$rownew['GENDER'];
		$smarty->assign("REC_GENDER",$gender_reciever);
		if($rownew['COUNTRY_RES']=='51')
			$smarty->assign("COUNTRY",'I');

		if($rownew["SERVICE_MESSAGES"]!='S')
			$SERVICE_MESSAGE_UNSUBSCRIBED=1;

		if($rownew["SUBSCRIPTION"]!='')
		{
			$smarty->assign("RECEIVER_IS_PAID",'1');
                        $receiver_rights=explode(",",$rownew["SUBSCRIPTION"]);
		}
                else
                        $receiver_rights=array();
		$rec_email=$rownew["EMAIL"];
		$smarty->assign("RECEIVERNAME",$rownew['USERNAME']);
		//$sendername=get_name($sender_profileid);
		//$smarty->assign("SENDERNAME",$sendername);
		//$sender_rights=get_rights($sender_profileid);
		//$receiver_rights=get_rights($receiver_profileid);
		//if(in_array("F",$sender_rights))
			//$smarty->assign("SENDER_IS_PAID","1");
		$sqlnew="SELECT SUBSCRIPTION,USERNAME,EMAIL FROM JPROFILE WHERE PROFILEID='$sender_profileid'";
		$resnew=mysql_query_decide($sqlnew,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
                $rownew=mysql_fetch_array($resnew);
		if($rownew["SUBSCRIPTION"]!='')
                {
                        $smarty->assign("SENDER_IS_PAID",'1');
                        $sender_rights=explode(",",$rownew["SUBSCRIPTION"]);
                }
		else
			$sender_rights=array();
		
		$sen_email=$rownew["EMAIL"];
		$sendername=$rownew["USERNAME"];
		$smarty->assign("SENDERNAME",$sendername);
		//Modification Ends Here.

		if(in_array("D",$receiver_rights))
                        $RECEIVER_IS_ECLASSFIED=1;
			
		//showing contact details if "D" is included in sender_rights   NEW CHANGES
		if(in_array("D",$sender_rights) && !in_array("S",$sender_rights))
		{
			$show_contact_details=1;
			$smarty->assign("SHOW_CONTACT_DETAILS","1");
		}
			
		if(in_array("F",$receiver_rights))
		{
			$receiver_is_paid=1;
			//$smarty->assign("RECEIVER_IS_PAID","1");
		}
		//Added By lavesh For Managing new templates for initial contact decline
		
		//Added  By lavesh for ad-links
		include_once("/usr/local/snoopy/Snoopy.class.php");
		$snooper = new Snoopy;
/*              include_once("/usr/local/apache/sites/jeevansathi.com/htdocs/profile/snoopy/Snoopy.class.php");
                //include_once("../shakti/bm2/Snoopy.class.php");
                $snooper = new Snoopy;                 
		//$URI="http://192.168.2.220/bmsjs/bms_display_final.php?zonestr=18&data=".$receiver_profileid."&subzone=1&isTextLink=Y";
                $URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$receiver_profileid."&subzone=1&isTextLink=Y";
                $snooper->fetch($URI);
                $textlink =  $snooper->results;
                $smarty->assign("textlink1",$textlink);
		$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$receiver_profileid."&subzone=2&isTextLink=Y";                 
		$snooper->fetch($URI);
                $textlink =  $snooper->results;
*/		$smarty->assign("textlink2",$textlink);
		//end here
	
		if(!$mbureau)
                {
			//Added by lavesh on 19june 2006 for google adsense
			include_once("random_kw_for_google.php");
			$random_kw=random_kw_generator();
			$smarty->assign("keywords_for_adsense",$random_kw);
			unset($random_kw);
			//ends here

			$sql="SELECT AGE,HEIGHT,CASTE,SUBCASTE,MTONGUE,CITY_RES,COUNTRY_RES,OCCUPATION,EDU_LEVEL_NEW,YOURINFO,USERNAME,HAVEPHOTO,GOTHRA,NAKSHATRA,PHOTO_DISPLAY,INCOME,SCREENING FROM JPROFILE WHERE PROFILEID='$sender_profileid'";
                        $res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($res);
                        $havephoto=$row['HAVEPHOTO'];
                        $photo_display=$row['PHOTO_DISPLAY'];
			//Changed By Lavesh
                        if($havephoto=='Y')
                        {
				$showimage=1;
				if($photo_display=='H')
				{
					$showimage=0;
				}
				elseif($photo_display=='C')
				{
					
					if($flag_response=="A")
						$showimage=1;
					else
						$showimage=0;
				}
				if($showimage==1)
				{	
					$photochecksum_new=intval(intval($sender_profileid)/1000) . "/" . md5($sender_profileid+5);
					$smarty->assign("PHOTOCHECKSUM_NEW",$photochecksum_new);
					$smarty->assign("SENDER_HAVEPHOTO","Y");
				}
				else
				{
					$smarty->assign("SENDER_HAVEPHOTO","H");
				}
			}
                        $sender["NAME"]=$row['USERNAME'];
			//modified by lavesh as 1st three words of yourinfo will be bold and screening should be there.
                        $screening=$row["SCREENING"];
                        if(isFlagSet("YOURINFO",$screening))
                        {
				if(trim($row["YOURINFO"]))
				{
					$yourinfo1=trim($row["YOURINFO"]);
					$len=strlen($yourinfo1);
					$flag=0;
					for($k=0;$k<$len;$k++)
					{
						if($yourinfo1[$k]==' ')
						{
							$flag++;
						}
						if($flag<3)
						{
							$subyourinfo.=$yourinfo1[$k];
						}
						else
						{
							$yourinfo.=$yourinfo1[$k];
							$flag++;
						}
					}
				}	
				//150 character limit is there.
				$sender["INFO"]=substr("<b>".$subyourinfo."</b>"." $yourinfo",0,150);
			}
			else
				$sender["INFO"]='';

			unset($subyourinfo);
			unset($yourinfo);
                        //$sender["INFO"]=substr($row['YOURINFO'],0,150);
			//Ends Here.
                        $sender["AGE"]=$row['AGE'];
			$sender["GOTHRA"]=$row['GOTHRA'];
                       	$sender["NAKSHATRA"]=$row['NAKSHATRA'];
			$sender["PROFILECHECKSUM"]=md5($sender_profileid) . "i" . $sender_profileid;
                        $temp=label_select("HEIGHT",$row['HEIGHT']);
                        if($temp)
				$senderHEIGHT2=implode(",",$temp);
                        $senderHEIGHT=explode("(",$senderHEIGHT2);
                        $sender["HEIGHT"]=trim($senderHEIGHT[0]);
                        unset($temp);
                        $temp=label_select("CASTE",$row['CASTE']);
                        $sender["CASTE"]=$temp[0];
                        unset($temp);
			$temp=label_select("MTONGUE",$row['MTONGUE']);
			$sender["MTONGUE"]=$temp[0];
			unset($temp);
			$temp=label_select("EDUCATION_LEVEL_NEW",$row['EDU_LEVEL_NEW']);
			$sender["EDUCATION"]=$temp[0];
			unset($temp);
                        $temp=label_select("COUNTRY",$row['COUNTRY_RES']);
                        $sender["COUNTRY"]=$temp[0];
                        unset($temp);
                        if($row['COUNTRY_RES']=='51')
                        {
                                $temp=label_select("CITY_INDIA",$row['CITY_RES']);
				$country_markcc='I'; //Used in case of markcc.
                        }
                        else
                        {
                                $temp=label_select("CITY_USA",$row['CITY_RES']);
                        }
                        $sender["CITY"]=$temp[0];
                        unset($temp);
			$temp=label_select("OCCUPATION",$row['OCCUPATION']);
                        $sender["OCCUPATION"]=$temp[0];
                        unset($temp);
                        if(!$sender["CITY"]=="")
				$sender["RESIDENCE"]=$sender["CITY"];
			else
				$sender["RESIDENCE"]=$sender["COUNTRY"];
		
			if($gender_reciever=='F')
			{
				 $income_map=array("1" => "< Rs. 50K",
                                                        "2" => "Rs. 50K - 1Lac",
                                                        "3" => "Rs. 1 - 2Lac",
                                                        "4" => "Rs. 2 - 3Lac",
                                                        "5" => "Rs. 3 - 4Lac",
                                                        "6" => "Rs. 4 - 5Lac",
                                                        "7" => "> Rs. 5Lac",
                                                        "8" => "< $ 25K",
                                                        "9" => "$ 25 - 50K",
                                                        "10" => "$ 50 - 75K",
                                                        "11" => "$ 75K - 1Lac",
                                                        "12" => "$ 1 - 1.5Lac",
                                                        "13" => "$ 1.5 - 2Lac",
                                                        "14" => "> $ 2Lac",
                                                        "15" => "No Income",
                                                        "16" => "Rs. 5 - 7.5Lac",
                                                        "17" => "Rs. 7.5 - 10Lac",
                                                        "18" => "> Rs. 10Lac");
				$sender["INCOME"]=$income_map[$row['INCOME']];
				unset($income_map);
			}
				
			//if(in_array("F",$receiver_rights))
                        	//$smarty->assign("RECEIVER_IS_PAID","1");
                        //$smarty->assign("CUSTMESSAGE",$custmessage);
                        //if($custum_message && in_array("F",$sender_rights))
                                //$smarty->assign("PAID","Y");
			$smarty->assign("sender",$sender);
	
		}
		// Addition Ends Here
		if($flag_response=="A")
		{
			//Modified By lavesh
			include_once("payment_array.php");
			//$smarty->assign("PAY_ERISHTA",$pay_erishta);
			//$smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
			//$smarty->assign("PAY_EVALUE",$pay_evalue);
			// Ends Here
			$smarty->assign("SUBJECT","Contact accepted");
			$subject="Contact accepted by jeevansathi member ".$sender["NAME"];
			
			//Added By lavesh for marriage-bureau as Gender Is Required in for calling one of the 2 templates
			if($mbureau==1)
			{
				$sqlnew="SELECT GENDER,USERNAME FROM JPROFILE WHERE PROFILEID='$sender_profileid'";
				$resnew=mysql_query_decide($sqlnew,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
				$rownew=mysql_fetch_array($resnew);
				$sender["NAME"]=$rownew['USERNAME'];
				$Gen=$rownew['GENDER'];
				if($Gen=='F')
					$gender_reciever='M';
                                else
                                        $gender_reciever='F';
				unset($Gen);
				$smarty->assign("sender",$sender);
			}
			//end of addition

			if(in_array("F",$receiver_rights))
			{
				if($mbureau==1)
                                {
                                        $fromprofilepage=1;
					include_once('../marriage_bureau/connectmb.inc');
                                        $mbprofile=get_email_id_mb($sender_profileid);
                                        $from=$mbprofile["EMAIL"];
                                        if(!$from)$from="contacts@jeevansathi.com";
                                }
                                else
					//$from=get_email_id($sender_profileid); by lavesh
					$from=$sen_email;	
			}
			elseif(in_array("F",$sender_rights))
				$from=$sen_email;
					//ends here
			else 
				$from="contacts@jeevansathi.com";		
		}	
		elseif($flag_response=="D")	
		{	
			$smarty->assign("SUBJECT","Contact declined");
			$subject="Contact declined";
			$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has declined your initial contact.<br>";					
			$from="contacts@jeevansathi.com";		
		}
		elseif($flag_response=="C")	
		{	
			$smarty->assign("SUBJECT","Contact cancelled");
			$subject="Contact cancelled";
			$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has cancelled the contact made with you.<br>";					
			$from="contacts@jeevansathi.com";		
		}
		
		//$to=get_email_id($receiver_profileid);  //lavesh
		$to=$rec_email;
		//added by Gaurav on 29 Sep to set TYPE field of MESSAGE_LOG to be used for summary part of MMM
		if($flag_response)
			$type=$flag_response;
		else
			$type='R';
	
		// end of code	
		//if any of sender or receiver is a paid subscriber and there is a customised message
		//if(in_array("F",$receiver_rights) || in_array("F",$sender_rights) || $RECEIVER_IS_ECLASSFIED)
		if(trim($custmessage))
		{
			if($flag_response=="A")
			{
				$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has accepted your contact.<br>";
				if($custmessage)
				{
					$smarty->assign("PAID","Y");
					$smarty->assign("CUSTMESSAGE",nl2br($custmessage));

					if (!$resend)
					{
						//$ip=getenv('REMOTE_ADDR');
						if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
						{
							//$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','$flag_response','$markcc')";
							//mysql_query_decide($sql);
							//$id_obscene=mysql_insert_id_js();
							$NO_SENDMAIL=1;
							$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$flag_response,$markcc);
							
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,MSG_OBS_ID,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','Y','$id_obscene','$type')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene,$type);
						}
						else
						{	
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','N','$type')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0',$type);
						}
						insert_into_messages($msg_id,$custmessage);
						
						$custmessage1=stripslashes(addslashes($custmessage));
						//save_draft($custmessage1,$savedraft,$sender_profileid);
						save_draft($custmessage1,$savedraft,$sender_profileid,$receiver_profileid);
					
					}

					$message.="To view the sender's profile, <span class=\"class6\"><a href=\"$SITE_URL/profile/viewprofile.php?username=$sendername\">click here</a></span><br>";
					if(in_array("F",$receiver_rights))
					{
						$message.="To view this user's contact details log on to <a href=\"$SITE_URL/profile/mainmenu.php\">My JeevanSathi</a><br>";
					}
					else 
					{
						$message.="To view this user's contact details <a href=\"$SITE_URL/profile/memberships.php\">upgrade your membership</a><br>";
					}		
					
				}
				else
                                {
                                        //$msg_accept='';
                                        //$msg_accept="flag_mmm_acc_default";
                                        //$ip=getenv('REMOTE_ADDR');
					//$sql="INSERT INTO MESSAGE_LOG(SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE) VALUES ('$sender_profileid','$receiver_profileid',NOW(),'$ip','$msg_accept','$type')";
                                        //mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0',$type);
				}
			
			}	
			elseif($flag_response=="D")
			{
				$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has declined your contact.<br>";
				if($custmessage)
				{
                                        $smarty->assign("PAID","Y");
                                        $smarty->assign("CUSTMESSAGE",nl2br($custmessage));
                                        if (!$resend)                                                                                    			     {
						//$ip=getenv('REMOTE_ADDR');
						if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
						{
							//$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','$flag_response','$markcc')";
							//mysql_query_decide($sql);
							//$id_obscene=mysql_insert_id_js();
							$NO_SENDMAIL=1;
							$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$flag_response,$markcc);
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,MSG_OBS_ID,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','Y','$id_obscene','$type')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene,$type);
						}
						else
						{
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','N','$type')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0',$type);
						}
						insert_into_messages($msg_id,$custmessage);

						$custmessage1=stripslashes(addslashes($custmessage));
						//save_draft($custmessage1,$savedraft,$sender_profileid);
						save_draft($custmessage1,$savedraft,$sender_profileid,$receiver_profileid);
						$smarty->assign("CUSTMESSAGE",nl2br($custmessage));
					}
				}
				else
				{
					//$msg_decline="flag_mmm_decline_default";
					//$msg_decline='';
					//$ip=getenv('REMOTE_ADDR');
					//$sql="INSERT INTO MESSAGE_LOG(SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE) VALUES ('$sender_profileid','$receiver_profileid',NOW(),'$ip','$msg_decline','$type')";
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0',$type);
				}
			}	
			elseif($flag_response=="C")
			{
				$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has cancelled the contact made with you.<br>";
				if($custmessage)
				{
                                        $smarty->assign("PAID","Y");
                                        $smarty->assign("CUSTMESSAGE",nl2br($custmessage));
                                        if (!$resend)
					{
						//$ip=getenv('REMOTE_ADDR');
						if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
						{
							//$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','$flag_response','$markcc')";
							//mysql_query_decide($sql);
							//$id_obscene=mysql_insert_id_js();
							$NO_SENDMAIL=1;
							$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$flag_response,$markcc);
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,MSG_OBS_ID) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','Y','$id_obscene')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene,$type);
						}
						else
						{	
							//$ip=getenv('REMOTE_ADDR');
							//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','N','$type')";
							//mysql_query_decide($sql);
							$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0',$type);
						}
						insert_into_messages($msg_id,$custmessage);

						$custmessage1=stripslashes(addslashes($custmessage));
						//save_draft($custmessage1,$savedraft,$sender_profileid);
						save_draft($custmessage1,$savedraft,$sender_profileid,$receiver_profileid);
						$smarty->assign("CUSTMESSAGE",nl2br($custmessage));
					}
				}
				else
				{
					//$msg_cancel="flag_mmm_cancel_default";
					//$msg_cancel='';
					//$ip=getenv('REMOTE_ADDR');
					//$sql="INSERT INTO MESSAGE_LOG(SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE) VALUES ('$sender_profileid','$receiver_profileid',NOW(),'$ip','$msg_cancel','$type')";
					//mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0',$type);
				}
			}	
		}
		else 
		{
			if($flag_response=="A")
			{
				$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has accepted your contact.<br>To view the sender's profile, <span class=\"class6\"><a href=\"$SITE_URL/profile/viewprofile.php?username=$sendername\">click here</a></span><br>To view this user's contact details <a href=\"$SITE_URL/profile/memberships.php\">upgrade your membership</a><br>";
			}
			/*elseif($flag_response=="D")
			{
			}	
                        elseif($flag_response=="C")
                        {
                        }*/
			$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0',$type);
		}							 															
		$message=stripslashes($message);
		$smarty->assign("MESSAGE",$message);
		
		/*if ($flag_response=="A")
		{
			$max_members=5;
			$members_waiting=get_waiting_members($receiver_profileid,$max_members,$sender_profileid);
			if(!is_array($members_waiting))
				$smarty->assign("COUNT_MEMBERS_WAITING",0);
			else
				$smarty->assign("COUNT_MEMBERS_WAITING",count($members_waiting));
			
			for($i=0;$i<count($members_waiting);$i++)
			{
				$member_details[]=get_waiting_members_details($members_waiting[$i]);
			}
			
			$smarty->assign("MEMBERS_WAITING",$member_details);
		}*/
		
		$smarty->assign("HEAD_MAILER",$smarty->fetch("head_mailer.htm"));
		
		$smarty->assign("MARKCC","N");

		if($flag_response=="A")
		{	
			// mail will be send if the user is paid or show_contact_details is true NEW CHANGES
			if($mbureau==1)
                        {
                                mysql_select_db_js('marriage_bureau');
                                $profileofbureau=$mbprofile['PROFILEID'];
                                $sql="select NAME,ADDRESS,CITY,STATE,COUNTRY,PIN,TELEPHONE1,TELEPHONE2,FAX,EMAIL from marriage_bureau.BUREAU_PROFILE where PROFILEID='$profileofbureau'";
                                $res_contact=mysql_query_decide($sql,$db);
                                $mycontact=mysql_fetch_array($res_contact);
                                if($mycontact["NAME"]!="")
                                        $smarty->assign("BUREAUNAME",nl2br($mycontact["BUREAUNAME"]));
                                if($mycontact["ADDRESS"]!="")
                                        $smarty->assign("ADDRESS_OF_BUREAU",nl2br($mycontact["ADDRESS"]));
                                if($mycontact["CITY"]!="")
                                        $smarty->assign("CITY_OF_BUREAU",nl2br($mycontact["CITY"]));
                                if($mycontact["STATE"]!="")
                                        $smarty->assign("STATE_OF_BUREAU",nl2br($mycontact["STATE"]));
                                if($mycontact["COUNTRY"]!="")
                                        $smarty->assign("COUNTRY_OF_BUREAU",nl2br($mycontact["COUNTRY"]));
                                if($mycontact["TELEPHONE1"]!="")
                                        $smarty->assign("PHONE1",$mycontact["TELEPHONE1"]);
                                if($mycontact["TELEPHONE2"]!="")
                                        $smarty->assign("PHONE2",$mycontact["TELEPHONE2"]);
                                if($mycontact["FAX"]!="")
				         $smarty->assign("FAX",$mycontact["FAX"]);
                                $smarty->assign("EMAIL",$mycontact["EMAIL"]);
                                $smarty->assign("SENDER_IS_BUREAU","1");
                                //$response_msg=$smarty->fetch("initial_contact_accepted.htm");
                        }
                        else if($receiver_is_paid || $show_contact_details)
			{
				//Check if this can be commnted(lavesh)
				$sql="select EMAIL,CONTACT,PARENTS_CONTACT,PHONE_RES,PHONE_MOB,SHOW_PARENTS_CONTACT,SHOWADDRESS,SHOWPHONE_MOB,SHOWPHONE_RES from JPROFILE where PROFILEID='$sender_profileid'";
				$res_contact=mysql_query_decide($sql,$db);
				
				$mycontact=mysql_fetch_array($res_contact);
				
				if($mycontact["CONTACT"]!="" && $mycontact["SHOWADDRESS"]=="Y")
					$smarty->assign("ADDRESS_OF_SENDER",nl2br($mycontact["CONTACT"]));
					
				if($mycontact["PARENTS_CONTACT"]!="" && $mycontact["SHOW_PARENTS_CONTACT"]=="Y")
					$smarty->assign("PARENTS_ADDRESS_OF_SENDER",nl2br($mycontact["PARENTS_CONTACT"]));
					
				if($mycontact["PHONE_RES"]!="" && $mycontact["SHOWPHONE_RES"]=="Y")
					$smarty->assign("RES_PHONE_OF_SENDER",$mycontact["PHONE_RES"]);
					
				if($mycontact["PHONE_MOB"]!="" && $mycontact["SHOWPHONE_MOB"]=="Y")
					$smarty->assign("MOB_PHONE_OF_SENDER",$mycontact["PHONE_MOB"]);
					
				$smarty->assign("EMAIL_OF_SENDER",$mycontact["EMAIL"]);
			}

			//Modification Done By lavesh to call template on basis of Gender
			//$response_msg=$smarty->fetch("initial_contact_accepted.htm");
			if($gender_reciever=='M')
				$response_msg=$smarty->fetch("initial_contact_accepted_female.htm");
			else
				$response_msg=$smarty->fetch("initial_contact_accepted_male.htm");
			//Modification ends here
		}
		elseif($flag_response=="D")
		{
			//Modification Done By lavesh to call template on basis of Gender
                        //$response_msg=$smarty->fetch("initial_contact_declined.htm");
                        if($gender_reciever=='M')
				$smarty->assign("gender_sender","F");
			else
				$smarty->assign("gender_sender","M");
			$response_msg=$smarty->fetch("initial_contact_declined_final.htm");
                        //$response_msg=$smarty->fetch("initial_contact_declined_female_change.htm");
                        // Modification ends here
		}
		elseif($flag_response=="C")
		{
			$response_msg=$smarty->fetch("cancel_contact.htm");
		}
		if($NO_SENDMAIL!=1 && $to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
			send_email($to,$response_msg,$subject,$from);
			
		//a copy of the mail is to be sent to the logged in user
		if($markcc)
		{	
			//Added By lavesh
			unset($URI);
			//$URI="http://192.168.2.220/bmsjs/bms_display_final.php?zonestr=18&data=".$sender_profileid."&subzone=1&isTextLink=Y";
			 $URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$sender_profileid."&subzone=1&isTextLink=Y";
			$snooper->fetch($URI);
			$textlink =  $snooper->results;
			$smarty->assign("textlink1",$textlink);

			$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$sender_profileid."&subzone=2&isTextLink=Y";
			$snooper->fetch($URI);
			$textlink =  $snooper->results;
			$smarty->assign("textlink2",$textlink);
			//end here

			$smarty->assign("MARKCC","Y");
		
			if($flag_response=="A")
			{
				$smarty->assign("COUNTRY",$country_markcc);

				/*$smarty->assign("SUBJECT","CC: Contact accepted.");
				if($mbureau==1)
                                        $response_msg=$smarty->fetch("initial_contact_accepted.htm");
                                else
	                        {*/	
					$smarty->assign("SUBJECT","CC: Contact accepted.");
					
	                                //Modification Done By lavesh to call template on basis of Gender
        	                        //$response_msg=$smarty->fetch("initial_contact_accepted.htm");
                	                if($gender_reciever=='M')
                        	                 $response_msg=$smarty->fetch("initial_contact_accepted_female.htm");
                               		else
                                        	 $response_msg=$smarty->fetch("initial_contact_accepted_male.htm");
	                                // Modification ends here
				//}	
			}
			elseif($flag_response=="D")
			{
				$smarty->assign("SUBJECT","CC: Contact declined.");
				//Modification Done By lavesh to call template on basis of Gender
				//$response_msg=$smarty->fetch("initial_contact_declined.htm");
				if($gender_reciever=='M')
					$smarty->assign("gender_sender","F");
				else
					$smarty->assign("gender_sender","M");
				$response_msg=$smarty->fetch("initial_contact_declined_final.htm");
				//$response_msg=$smarty->fetch("initial_contact_declined_female_change.htm");
				// Modification ends here
			}
			elseif($flag_response=="C")
			{
	                        $smarty->assign("SUBJECT","CC: Contact cancelled.");
				$response_msg=$smarty->fetch("cancel_contact.htm");
			}
			if($mbureau==1)
                                $to=$mbprofile["EMAIL"];
                        else
				$to=get_email_id($sender_profileid);
			$from="contacts@jeevansathi.com";
			if($NO_SENDMAIL!=1 && $to)
				send_email($to,$response_msg,$subject,$from);
		}
	}
	
	function insert_into_message_log($sender_profileid,$receiver_profileid,$is_msg='N',$obscene='N',$id_obscene='0',$type='R')
	{
		$ip=getenv('REMOTE_ADDR');
		$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE,MSG_OBS_ID,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','$is_msg','$obscene','$id_obscene','$type')";
		mysql_query_decide($sql,"",1);
		return mysql_insert_id_js();
	}

	function insert_into_messages($msg_id,$custmessage)
	{
		$sql="insert into MESSAGES (ID,MESSAGE) values ('$msg_id','" . addslashes($custmessage) . "')";
		mysql_query_decide($sql,'',1);
	}	
	
	function insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$flag_response,$markcc)
	{
		$ip=getenv('REMOTE_ADDR');
		$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','$flag_response','$markcc')";
		mysql_query_decide($sql);
		return mysql_insert_id_js();
	}
	
	//function to make initial contact with the user
	function make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,$resend=0,$stype="")
	{
		global $smarty,$SITE_URL,$data;
                //script and function to track search to contact flow
                include_once("search_contact_flow_tracking.php");
                search_contact_flow_tracking($sender_profileid,$stype);

		// if message is not being resent after being unblocked	
		if (!$resend)
		{
			//make an entry in the contacts log
			if(!$flag_again)
			{
				//added by Lavesh,sriram for updation of fields in leftpanel
				$sql_init = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP+1 WHERE PROFILEID='$sender_profileid'";
                                mysql_query_decide($sql_init) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");

				$sql_init = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS+1 WHERE PROFILEID='$receiver_profileid'";
				mysql_query_decide($sql_init) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");
				
				//ends here.

				//Sharding of CONTACTS done by Sadaf
				insertIntoContacts($sender_profileid,$receiver_profileid,"I","NOW()",1);
				
				/*compatibility code starts*/
				
				$sender_3d=get_variables_for_3d($sender_profileid);		
				if($data['GENDER']=='F')
					$receiver_gender='M';
				else
					$receiver_gender='F';
				$points=calculate_3d_points($sender_3d['CASTE'],$sender_3d['RELIGION'],$sender_3d['MTONGUE'],$sender_3d['INCOME'],$receiver_gender,$receiver_profileid,1);
				
				if($points>265)
					$compatibility='A';
				elseif($points>150)
					$compatibility='B';
				else
					$compatibility='C';
				
				$sql_3d="INSERT INTO newjs.COMPATIBILITY (SENDER,RECEIVER,TYPE,COMPATIBILITY,DATE) values ('$sender_profileid','$receiver_profileid','I','$compatibility',now())";
				mysql_query_decide($sql_3d) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_3d,"ShowErrTemplate");
				
				/*compatibility code ends*/
			}
			else 	
			{
				//Sharding of CONTACTS done by Sadaf
				$time="NOW()";
				$count_increment_by=1;
				updateContactsTable($sender_profileid,$receiver_profileid,'','',$time,$count_increment_by);
			}
			
		}
		
		/*if(!get_mail_preference($receiver_profileid))
			$SERVICE_MESSAGE_UNSUBSCRIBED=1;*/
		//Get the contact id		
		$contact_id=get_contact_id($sender_profileid,$receiver_profileid);

		//added by lavesh.
		//$rights=get_rights($sender_profileid);
		$sql="SELECT SUBSCRIPTION,EMAIL FROM JPROFILE WHERE PROFILEID='$sender_profileid'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_array($res);
		
		$sen_to=$row["EMAIL"];	

		if($row["SUBSCRIPTION"]!='')
			$smarty->assign("SENDER_IS_PAID","1");

		$sql="SELECT ENTRY_DT,EMAIL,SERVICE_MESSAGES FROM JPROFILE WHERE PROFILEID='$receiver_profileid'";
                $res=mysql_query_decide($sql) or logError($sql);
                $row=mysql_fetch_array($res);
		
		if($row["SERVICE_MESSAGES"]!='S')
			$SERVICE_MESSAGE_UNSUBSCRIBED=1;			

		$today=date("Y-m-d");
		$day_diff=DayDiff_1($row["ENTRY_DT"],$today);
		if($day_diff>30)
			$group_email=1;
		$to=$row["EMAIL"];	

		//$to=get_email_id($receiver_profileid);
		//ends here.

		//if paid subscriber then customised message is saved in the Message log
		if($custmessage == 'Click here to write message')
			$custmessage='';
	
		$custmessage=trim($custmessage);
		//if($custmessage && in_array("F",$rights))		
		if($custmessage)
		{
			$smarty->assign("PAID","Y");
			$smarty->assign("CUSTMESSAGE",nl2br($custmessage));
			
			if (!$resend)
			{	
				$ip=getenv('REMOTE_ADDR');
				if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
				{
					//$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','I','$markcc')";
					//mysql_query_decide($sql);
					//$id_obscene=mysql_insert_id_js();
					$NO_SENDMAIL=1;
					$msg_del="D";
					$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,'I',$markcc);
					//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,MSG_OBS_ID,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','Y','$id_obscene','I')";
					//mysql_query_decide($sql);
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene,'I');
				}
				else
				{
					//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','N','I')";
					//mysql_query_decide($sql);
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0','I');
				}
				insert_into_messages($msg_id,$custmessage);
				
				//save the customised message in the database
				//query changed by Gaurav on 29 May 2006 so as to remove MESSAGE from CONTACTS tables
				//Sharding of CONTACTS done by Sadaf
				$time="NOW()";
				$msg_del=$msg_del;
				updateContactsTable($sender_profileid,$receiver_profileid,$contact_id,'',$time,'',$msg_del);
				//save the draft
				$custmessage1=stripslashes(addslashes($custmessage));
				//save_draft($custmessage1,$savedraft,$sender_profileid);
				save_draft($custmessage1,$savedraft,$sender_profileid,$receiver_profileid);
			}
		}		
		else	
		{
			//$msg_initial="flag_mmm_initial_default";
			/*if($custmessage)
				$msg_initial=addslashes($custmessage);
			else
				$msg_initial='';*/
			//$ip=getenv('REMOTE_ADDR');	
			//$sql="INSERT INTO MESSAGE_LOG(SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE) VALUES ('$sender_profileid','$receiver_profileid',NOW(),'$ip','$msg_initial','I')";
			//mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0','I');
		}
		
		$smarty->assign("MARKCC","N");
		//In any case the initial message from the contacts email id is sent					
		//if($NO_SENDMAIL!=1 && $to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
		//if($to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
		if($SERVICE_MESSAGE_UNSUBSCRIBED==1)
                        $NO_SENDMAIL=1;
		if($to)
			//Modification BY lavesh
                        //send_email($to,$response_msg,$subject,$from);
                        save_email($sender_profileid,$receiver_profileid,$custmessage,$NO_SENDMAIL,$group_email);
                        //Ends Here
				
		//a copy of the mail is to be sent to the logged in user
		if($markcc || !$group_email)//or cond. added  by lavesh
		{
			//Added by lavesh on 19june 2006 for google adsense
                        include_once("random_kw_for_google.php");
                        $random_kw=random_kw_generator();
                        $smarty->assign("keywords_for_adsense",$random_kw);
                        unset($random_kw);
                        //ends here
			$sender_details=get_waiting_members_details($sender_profileid);
			
			//changed by lavesh.
			if($sender_details['RESIDENCE']=='Others')
				$subject=$sender_details['AGE'].", ".str_replace("&quot;","\"",$sender_details['HEIGHT']).", ".$sender_details['RELIGION'].", ".$sender_details['CASTE'].", ".$sender_details['OCCUPATION']." has contacted you";
			else
				$subject=$sender_details['AGE'].", ".str_replace("&quot;","\"",$sender_details['HEIGHT']).", ".$sender_details['RELIGION'].", ".$sender_details['CASTE'].", ".$sender_details['RESIDENCE'].", ".$sender_details['OCCUPATION']." has contacted you";
			if($markcc && !$group_email)
			{
				$to_1=$to;
				$subject_1=$subject;
			}

			if($markcc)
			{
				$subject="CC : ".$sender_details['AGE'].", ".str_replace("&quot;","\"",$sender_details['HEIGHT']).", ".$sender_details['RELIGION'].", ".$sender_details['CASTE'].", ".$sender_details['RESIDENCE'].", ".$sender_details['OCCUPATION']." has contacted you";
				$smarty->assign("MARKCC","Y");				
				//$to=get_email_id($sender_profileid);
				$to=$sen_to;
			}
			//Ends Here.

			//Replacing get_name(),get_profile_details() with below 5 lines.
			$sqlnew="SELECT GENDER,USERNAME,SUBSCRIPTION FROM JPROFILE WHERE PROFILEID='$receiver_profileid'";
			$resnew=mysql_query_decide($sqlnew) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
			$rownew=mysql_fetch_array($resnew);
			$smarty->assign("RECEIVERNAME",$rownew['USERNAME']);
			$smarty->assign("REC_GENDER",$rownew['GENDER']);
			if($rownew['SUBSCRIPTION']!='')
				$smarty->assign("RECEIVER_IS_PAID","1");

			//Replacement ends here
			include_once("payment_array.php");
			$smarty->assign("numberofsender",'A');
                        $sql="SELECT GENDER,AGE,HEIGHT,CASTE,SUBCASTE,MTONGUE,CITY_RES,COUNTRY_RES,OCCUPATION,EDU_LEVEL_NEW,YOURINFO,USERNAME,HAVEPHOTO,GOTHRA,NAKSHATRA,COUNTRY_RES,PHOTO_DISPLAY,INCOME,SCREENING FROM JPROFILE WHERE PROFILEID='$sender_profileid'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($res);
                        $havephoto=$row['HAVEPHOTO'];
                        $photo_display=$row['PHOTO_DISPLAY'];
			$GENDER=$row['GENDER'];
			$newsenders[0]["GOTHRA"]=$row['GOTHRA'];
                        $newsenders[0]["NAKSHATRA"]=$row['NAKSHATRA'];
			if($row['COUNTRY_RES']=='51')
				$smarty->assign("COUNTRY",'I');
                        $sender[0]["PROFILECHECKSUM"]=md5($sender_profileid) . "i" . $sender_profileid;

			if($havephoto=='Y')
			{
				//if(($photo_display=='H')||($photo_display=='C'))
				if($photo_display=='H')
				{
					$newsenders[0]["newsenders_HAVEPHOTO"]="H";
				}
				else
				{
					$photochecksum_new=intval(intval($sender_profileid)/1000) . "/" . md5($sender_profileid+5);
					$newsenders[0]["PHOTOCHECKSUM_NEW"]=$photochecksum_new;
					$newsenders[0]["newsenders_HAVEPHOTO"]="Y";
				}
			}
                        $newsenders[0]["NAME"]=$row['USERNAME'];

			//modified by lavesh as 1st three words of yourinfo will be bold and screening should be there.
			$screening=$row["SCREENING"];
                        if(isFlagSet("YOURINFO",$screening))
			{
				if(trim($row["YOURINFO"]))
				{
					$yourinfo1=trim($row["YOURINFO"]);
					$len=strlen($yourinfo1);
					$flag=0;
					for($k=0;$k<$len;$k++)
					{
						if($yourinfo1[$k]==' ')
						{
							$flag++;
						}
						if($flag<3)
						{
							$subyourinfo.=$yourinfo1[$k];
						}
						else
						{
							$yourinfo.=$yourinfo1[$k];
							$flag++;
						}
					}
				}
				//150 character limit is there.
				$newsenders[0]["INFO"]=substr("<b>".$subyourinfo."</b>"." $yourinfo",0,150);
			}
			else
				$newsenders[0]["INFO"]='';

                        unset($subyourinfo);
                        unset($yourinfo);
                        //$newsenders[0]["INFO"]=substr($row['YOURINFO'],0,150);
                        $newsenders[0]["AGE"]=$row['AGE'];
                        $temp=label_select("HEIGHT",$row['HEIGHT']);
                        $newsendersHEIGHT2=implode(",",$temp);
                        $newsendersHEIGHT=explode("(",$newsendersHEIGHT2);
                        $newsenders[0]["HEIGHT"]=$newsendersHEIGHT[0];
                        unset($temp);
                        $temp=label_select("CASTE",$row['CASTE']);
                        $newsenders[0]["CASTE"]=$temp[0];
                        unset($temp);
                        $temp=label_select("MTONGUE",$row['MTONGUE']);
                        $newsenders[0]["MTONGUE"]=$temp[0];
                        unset($temp);
                        $temp=label_select("EDUCATION_LEVEL_NEW",$row['EDU_LEVEL_NEW']);
                        $newsenders[0]["EDUCATION"]=$temp[0];
                        unset($temp);
                        //$newsenders[0]["SUBCASTE"]=$row['SUBCASTE'];
                        $temp=label_select("COUNTRY",$row['COUNTRY_RES']);
			$temp=label_select("COUNTRY",$row['COUNTRY_RES']);
                        $newsenders[0]["COUNTRY"]=$temp[0];
                        unset($temp);
                        if($row['COUNTRY_RES']=='51')
                        {
                                $temp=label_select("CITY_INDIA",$row['CITY_RES']);
                        }
                        else
                        {
                                $temp=label_select("CITY_USA",$row['CITY_RES']);
                        }
                        $newsenders[0]["CITY"]=$temp[0];
                        unset($temp);
                        $temp=label_select("OCCUPATION",$row['OCCUPATION']);
                        $newsenders[0]["OCCUPATION"]=$temp[0];
                        unset($temp);
                        // If City Record Not Found Then RESIDENCE Of Sender Is Indiacated By Its Country.
                        if(!$newsenders[0]["CITY"]=="")
                                $newsenders[0]["RESIDENCE"]=$newsenders[0]["CITY"];
                        else
	                        $newsenders[0]["RESIDENCE"]=$newsenders[0]["COUNTRY"];
			
                        $newsenders[0]["CUSTMESSAGE"]=trim($custmessage);
			if($GENDER=='M')
                        {
                                 $income_map=array("1" => "< Rs. 50K",
                                                        "2" => "Rs. 50K - 1Lac",
                                                        "3" => "Rs. 1 - 2Lac",
                                                        "4" => "Rs. 2 - 3Lac",
							"5" => "Rs. 3 - 4Lac",
                                                        "6" => "Rs. 4 - 5Lac",
                                                        "7" => "> Rs. 5Lac",
                                                        "8" => "< $ 25K",
                                                        "9" => "$ 25 - 50K",
                                                        "10" => "$ 50 - 75K",
                                                        "11" => "$ 75K - 1Lac",
                                                        "12" => "$ 1 - 1.5Lac",
							"13" => "$ 1.5 - 2Lac",
                                                        "14" => "> $ 2Lac",
                                                        "15" => "No Income",
                                                        "16" => "Rs. 5 - 7.5Lac",
                                                        "17" => "Rs. 7.5 - 10Lac",
							"18" => "> Rs. 10Lac");
                                $newsenders[0]["INCOME"]=$income_map[$row['INCOME']];
                                unset($income_map);
                        }
                        /*if($custmessage && in_array("F",$rights))
                        {
                                $newsenders[0]["PAID"]="Y";
                        }*/
			/*if(in_array("F",$rights))
			{
				$smarty->assign("SENDER_IS_PAID","1");
			}*/
			$smarty->assign("newsenders",$newsenders);
			$smarty->assign("my_cnt",1);
			//$response_msg=$smarty->fetch("initial_contact.htm");
			if($GENDER=='M')
				$initial_contact_msg=$smarty->fetch("initial_contact_male.htm");
			else
				$initial_contact_msg=$smarty->fetch("initial_contact_female.htm");

			//added + modified by lavesh
			if($markcc && !$group_email)
			{
				$smarty->assign("MARKCC","");
				if($GENDER=='M')
        	                        $initial_contact_msg1=$smarty->fetch("initial_contact_male.htm");
                	        else
                        	        $initial_contact_msg1=$smarty->fetch("initial_contact_female.htm");
			}
			$from="contacts@jeevansathi.com";
			if($NO_SENDMAIL!=1 && $to)
			{
				send_email($to,$initial_contact_msg,$subject,$from);
				if($to_1)
					send_email($to_1,$initial_contact_msg1,$subject_1,$from);
			}
			elseif($markcc) //sending cc mail everytime even receiver has SERVICE_MESSAGES!='S'
			{
				send_email($to,$initial_contact_msg,$subject,$from);
			}
			//end here
			//echo $initial_contact_msg1.'--'."<br>".$initial_contact_msg;die;
		}										
	}	
	
	//function to send a personalised message 
	function send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc,$resend=0)
	{	
		global $smarty,$SITE_URL;

		if($contact_status=="A")
			$contact_id=get_contact_id($sender_profileid,$receiver_profileid);
		elseif($contact_status=="RA")
			$contact_id=get_contact_id($receiver_profileid,$sender_profileid);
			
		if(!get_mail_preference($receiver_profileid))
			$SERVICE_MESSAGE_UNSUBSCRIBED=1;

		// if message is not being resent after being unblocked		
		if(!$resend)	
		{
			if($custmessage)
			{
				if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
				{
					//$ip=getenv('REMOTE_ADDR');
					//$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','M','$markcc')";
					//mysql_query_decide($sql);
					//$id_obscene=mysql_insert_id_js();
					$NO_SENDMAIL=1;
					$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,'M',$markcc);
					//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE,MSG_OBS_ID) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','Y','$id_obscene')";
					//mysql_query_decide($sql);
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene);
				}
				else
				{
					//$ip=getenv('REMOTE_ADDR');
					//$sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE,OBSCENE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "','N')";
					//mysql_query_decide($sql);
					$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y');
				}
				insert_into_messages($msg_id,$custmessage);
			}
			else
				 $msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N');
			//$sql="update CONTACTS set TIME=NOW() where CONTACTID='$contact_id'";
			//mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");			
			
			//echo $sql="insert into MESSAGE_LOG (SENDER,RECEIVER,DATE,IP,MESSAGE) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes($custmessage) . "')";
			//mysql_query_decide($sql);
					
			$custmessage1=stripslashes(addslashes($custmessage));
			//save_draft($custmessage1,$savedraft,$sender_profileid);
			save_draft($custmessage1,$savedraft,$sender_profileid,$receiver_profileid);
		}
		
		$to=get_email_id($receiver_profileid);
		$from=get_email_id($sender_profileid);

		$smarty->assign("RECEIVERNAME",get_name($receiver_profileid));

		//Added By Shakti on 9 Feb,2006 since gender of the receiver is required in the template
                $receivers_det=get_profile_details($receiver_profileid);
                $smarty->assign("REC_GENDER",$receivers_det['GENDER']);
                //End of additions

		$sendername=get_name($sender_profileid);
		$smarty->assign("SENDERNAME",$sendername);
		$smarty->assign("SUBJECT","Message from $sendername");
		$subject="Message from a jeevansathi member";		

		$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has sent you a message.<br>";
		$message.="Message from $sendername:<br>".nl2br($custmessage)."<br>";
		$message.="To view the sender's profile, <span class=\"class6\"><a href=\"http://www.jeevansathi.com/profile/viewprofile.php?username=$sendername\">click here</a></span><br>";	
				
		$message=stripslashes($message);
		$smarty->assign("MESSAGE",$message);
		
		$max_members=5;
		$members_waiting=get_waiting_members($receiver_profileid,$max_members,$sender_profileid);
		if(!is_array($members_waiting))
			$smarty->assign("COUNT_MEMBERS_WAITING",0);
		else
			$smarty->assign("COUNT_MEMBERS_WAITING",count($members_waiting));
			
		for($i=0;$i<count($members_waiting);$i++)
		{
			$member_details[]=get_waiting_members_details($members_waiting[$i]);
		}
		
		$smarty->assign("MEMBERS_WAITING",$member_details);

		$smarty->assign("MARKCC","N");
		$smarty->assign("HEAD_MAILER",$smarty->fetch("head_mailer.htm"));
		$send_msg=$smarty->fetch("contact_mail.htm");
		
		if($NO_SENDMAIL!=1 && $to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
			send_email($to,$send_msg,$subject,$from);
		
		if($markcc)
		{	
			$smarty->assign("MARKCC","Y");
	                $smarty->assign("SUBJECT","CC: Message from $sendername");
			$send_msg=$smarty->fetch("contact_mail.htm");
			$to=$from;
			$from="contacts@jeevansathi.com";
			
			if($NO_SENDMAIL!=1 && $to)
				send_email($to,$send_msg,$subject,$from);
		}
	}
	
	//function to check if a message can be saved as a draft and save it as draft if possible 	
	//function save_draft($custmessage,$savedraft,$profileid)
	function save_draft($custmessage,$savedraft,$profileid,$receiver_profileid="")
	{
		global $smarty;
		//if the draft is requested to be saved	
		if($savedraft)
		{

			//check whether draft is to be inserted or updated
			$sql="select count(*) as count from DRAFTS where PROFILEID='$profileid' and DRAFTNAME='$savedraft'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow_count=mysql_fetch_array($result);
			if($myrow_count["count"]>0)//this shows that a draft with this name already exists
			{
				$sql3="update DRAFTS set MESSAGE='$custmessage',CREATE_TIME=NOW() where PROFILEID='$profileid'and DRAFTNAME='$savedraft'";	
				$result3=mysql_query_decide($sql3) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql3,"ShowErrTemplate");
			}	
			else 
			{
				$sql1="select count(*) as COUNT from DRAFTS where PROFILEID='$profileid'";
				$result1=mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
				$myrow1=mysql_fetch_array($result1);
				$count=$myrow1["COUNT"];
				if($count<5)//The max. number of drafts one can store is 5
				{		
					$sql2="insert into DRAFTS(PROFILEID,DRAFTNAME,MESSAGE,CREATE_TIME) values('$profileid','$savedraft','$custmessage',NOW())";
					mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
				}	
				else//The limit for allowed no. of drafts has been exceeded
				{
					$error_msg="<br>&#8226; You are allowed to store at most 5 drafts";
					$error_msg.="<a href=\"\"></a>";

					//modifed by sriram
					if($receiver_profileid)
					{
						$sql_usrname = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$receiver_profileid'";
						$res_usrname = mysql_query_decide($sql_usrname) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_usrname,"ShowErrTemplate");
						$row_usrname = mysql_fetch_array($res_usrname);
						$profilename = $row_usrname['USERNAME'];


						$error_msg .= "<br>&#8226; You have successfully initiated contact with Jeevansathi member <b>$profilename</b> (revealing your contact details) to <b> $profilename </b> notifying your interest.";
						$error_msg .= "<br>&#8226; The personalised message written by you has also been sent in the e-mail. <br> (Note: Since you are allowed to store at most 5 drafts, the new draft message has not been saved)";
					}

					$smarty->assign("ERROR_MSG",$error_msg);
					$smarty->display("contact_result.htm");
					die;									
				}	
			}
		}	
	}
	//function to get the username from the profile id
	function get_name($profileid)
	{
		$sql="select USERNAME from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result)>0)
		{
			$myrow=mysql_fetch_array($result);
			return $myrow["USERNAME"];
		}
		else
			return "";
	}
	//function to get the contact id, the unique number that identifies a pair of users in the contact table	
	function get_contact_id($sender_profileid,$receiver_profileid)
	{
		//Sharding of CONTACTS done by Sadaf
		/*$sql="select CONTACTID from CONTACTS where SENDER='$sender_profileid' and RECEIVER='$receiver_profileid'"; 
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");*/
		$sendersIn=$sender_profileid;
		$receiversIn=$receiver_profileid;
		$contactResult=getResultSet("CONTACTID",$sendersIn,'',$receiversIn);
		if(!is_array($contactResult))
		{
			unset($contactResult);
			
			$sendersIn=$receiver_profileid;
			$receiversIn=$sender_profileid;
			$contactResult=getResultSet("CONTACTID",$sendersIn,'',$receiversIn);
			if(!is_array($contactResult))
			{
				unset($contactResult);
				return null;
			}
			else 
			{
				$contact_id=$contactResult[0]["CONTACTID"];
				unset($contactResult);
				return $contact_id;
			}
		}
		else 
		{
			$contact_id=$contactResult[0]["CONTACTID"];
			unset($contactResult);
			return $contact_id;
		}
	}
	
	//function to get the email id of a person given the profile id	
	function get_email_id($profileid)
	{
		$sql="select EMAIL from JPROFILE where PROFILEID='$profileid' and ACTIVATED='Y'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
		
		if(mysql_num_rows($result) > 0)
		{
			$myrow=mysql_fetch_array($result);
		    	return $myrow["EMAIL"];
		}
		else 
			return null;
	}
	
	//function to check whether a user can contact another user
	function can_contact($sender_profileid,$receiver_profileid,$sender_details,&$error_msg)
	{	
		//first check : male -> female or female -> male only		

		$sender_gender=$sender_details["GENDER"];
		$sql="select GENDER,ACTIVATED from JPROFILE where PROFILEID='$receiver_profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		$receiver_gender=$myrow["GENDER"];
		if($sender_gender==$receiver_gender)	
		{
			$error_msg="Gender is not opposite ";
			return false;
		}	

		//second check : Is the profile to be contacted activated
		if($myrow["ACTIVATED"]!='Y')
		{			
			if($myrow["ACTIVATED"]=='H')
				$error_msg="This member's profile is currently hidden. You can contact this person once he activates his profile. ";		
			elseif($myrow["ACTIVATED"]=='D')
				$error_msg="This member's profile has been deleted ";		
			elseif($myrow["ACTIVATED"]=='N'||$myrow["ACTIVATED"]=='U'||$myrow["ACTIVATED"]=='P')
				$error_msg="This member's profile is currently under screening. Please try again after 24 hours. ";		
			return false;
		}	
		
		mysql_free_result($result);
		
		$sql="select ACTIVATED,INCOMPLETE from JPROFILE where PROFILEID='$sender_profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		
		if($myrow["ACTIVATED"]!='Y')
		{			
			if($myrow["ACTIVATED"]=='H')
				$error_msg="Your profile is currenly hidden. You need to unhide your profile before contacting this user.";		
			elseif($myrow["ACTIVATED"]=='D')
				$error_msg="Your profile has been deleted";
			elseif($myrow["INCOMPLETE"]=='Y')
				$error_msg="Some of your profile details are missing. Please complete your profile before contacting any user. <a href=\"http://www.jeevansathi.com/profile/editprofile1.php?checksum=$checksum&callValidate=1\">Click here</a> to complete your profile.";
			elseif($myrow["ACTIVATED"]=='N'||$myrow["ACTIVATED"]=='U'||$myrow["ACTIVATED"]=='P')
				$error_msg="Your profile is currently being screened. Please contact this user after your profile has been screened ";		
			return false;
		}	
		
		mysql_free_result($result);
		
		//third check : Check whether the sender is filtered by the to be receiver	
		$sql="select * from FILTERS where PROFILEID='$receiver_profileid'";
		$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");									
		//if the user to be contacted has set filters
		if(mysql_num_rows($resultfilter) > 0)
		{
			$filterrow=mysql_fetch_array($resultfilter);							
			if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y")
			{
				$sqlfilter="select count(*) from JPROFILE where PROFILEID='$sender_profileid'";

				//Sharding Concept added by Vibhor Garg on table JPARTNER
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
			        $mysqlObj=new Mysql;
                        	$jpartnerObj=new Jpartner;
                        	$myDbName=getProfileDatabaseConnectionName($receiver_profileid,$mysqlObj);
                        	$myDb=$mysqlObj->connect("$myDbName");
				$jpartnerObj->setPartnerDetails($hisprofileid,$myDb,$mysqlObj);
				if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$receiver_profileid))
				{				
					//first filter : age
					if($filterrow["AGE"]=="Y")
                                	{
                                               	if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                                        	{
                                        	        $sqlfilter.= " and AGE between '" . $jpartnerObj->getLAGE() . "' and '" . $jpartnerObj->getHAGE() ."' ";                                                                                                   
                                        	}
                                	}

					//second filter : religion
					if($filterrow["RELIGION"]=="Y")
                                	{
                                        	$PARTNER_CASTE=$jpartnerObj->getPARTNER_CASTE();
                                                if($PARTNER_CASTE != "" )
                                        	{
                                                	$sqlfilter.=" and CASTE in ('" . get_all_caste($PARTNER_CASTE,1) . "')";
                                        	}
                                	}
					
					//third filter : country of residence
					if($filterrow["COUNTRY_RES"]=="Y")
                                	{
                                                $PARTNER_COUNTRYRES=$jpartnerObj->getPARTNER_COUNTRYRES();
                                                if($PARTNER_COUNTRYRES != "")
                                        	{
                                                	$sqlfilter.=" and COUNTRY_RES in ('" . $PARTNER_COUNTRYRES . "')";
                                        	}
                                	}
						
					//fourth filter : marital status
					if($filterrow["MSTATUS"]=="Y")
                                	{
 						$FILTER_MSTATUS=$jpartnerObj->getPARTNER_MSTATUS();
                                                if($FILTER_MSTATUS != "")
                                        	{
                                                	$sqlfilter.=" and MSTATUS in ('" . $FILTER_MSTATUS . "')";
                                        	}
                                	}
					
					//fifth filter : community
					if($filterrow["MTONGUE"]=="Y")
                                        {
                                        	$PARTNER_MTONGUE=$jpartnerObj->getPARTNER_MTONGUE();   
						if($PARTNER_MTONGUE != "")
                                                {
                                                        $sqlfilter.=" and MTONGUE in ('" . $PARTNER_MTONGUE . "')";
                                                }
					}

				//Sharding Concept added by Vibhor Garg on table JPARTNER

					$resfil=mysql_query_decide($sqlfilter) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlfilter,"ShowErrTemplate");
		
					$finalfilterrow=mysql_fetch_row($resfil);				
					mysql_free_result($resfil);
					if($finalfilterrow[0] <= 0)
					{				
						$error_msg="Person has set filters ";
						return false;
					}
				}
			}	 
		}				
		return true;		
	}		
	//function to get the contact status for a pair of users from the contact table 
	function get_contact_status($sender_profileid,$receiver_profileid)
	{
		//Sharding of CONTACTS done by Sadaf
		$sendersIn=$sender_profileid;
		$receiversIn=$receiver_profileid;
		$contactResult=getResultSet("TYPE",$sendersIn,'',$receiversIn);
		if(!is_array($contactResult))
		{ 
			$sendersIn=$receiver_profileid;
			$receiversIn=$sender_profileid;
			$contactResult=getResultSet("TYPE",$sendersIn,'',$receiversIn);
			if(is_array($contactResult))
			{
				$type=$contactResult[0]["TYPE"];
				unset($contactResult);
				return "R".$type;
			}			
		}		
		else 
		{
			$type=$contactResult[0]["TYPE"];
			unset($contactResult);
			return $type;
		}	
	}	
	
	function showProfileError($hidden="") 
	{
		global $checksum,$smarty;
		
		if($hidden=="N" || $hidden=="U" || $hidden=="P")
			$smarty->assign("MESSAGE","This profile is currently being Screened. Kindly view this profile after 24 hours");
		elseif($hidden=="H")
			$smarty->assign("MESSAGE","This profile is currently hidden. Please check after a couple of weeks");
		$smarty->assign("PROFILE_HIDDEN",$hidden);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
		$smarty->display("errorprofile.htm");		
		exit;
	}
	
	//function to get the profile details such as username,age etc. that are to be displayed while showing contacts made or received.
	//Changed by Shakti Srivastava on 9 Feb 2006 to get gender of the user alongwith other details
	//Changed by Shakti Srivastava on 4 April 2006 to get phone number of the user alongwith other details
	// Changed By Lavesh Rawat on May 2006 to Replace photochecksum by photochecksumnew. 
	function get_profile_details($profileid)
	{
		include("dropdowns.php");
		$lang=$_COOKIE['JS_LANG'];
	
		$income_map=array("1" => "< Rs. 50K",
							"2" => "Rs. 50K - 1Lac",
							"3" => "Rs. 1 - 2Lac",
							"4" => "Rs. 2 - 3Lac",
							"5" => "Rs. 3 - 4Lac", 
							"6" => "Rs. 4 - 5Lac",
							"7" => "> Rs. 5Lac",
							"8" => "< $ 25K",
							"9" => "$ 25 - 50K",
							"10" => "$ 50 - 75K",
							"11" => "$ 75K - 1Lac",
							"12" => "$ 1 - 1.5Lac",
							"13" => "$ 1.5 - 2Lac",
							"14" => "> $ 2Lac",
							"15" => "No Income",
							"16" => "Rs. 5 - 7.5Lac",
							"17" => "Rs. 7.5 - 10Lac",
							"18" => "> Rs. 10Lac");
		
		/* Extra fields were added By lavesh for making chat icon on Home Page on 12 may 2006 */

                $sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,ACTIVATED,RELIGION from JPROFILE where PROFILEID='$profileid'";
		//$sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
		$myrow=mysql_fetch_array($result);
		$country_code=$myrow["COUNTRY_RES"];		
		if($country_code==128)
		{
			$city=$myrow["CITY_RES"];
			$city=$CITY_USA_DROP["$city"];
			$country="USA";
			if($city!="")
				$residence=$city.", ".$country;
			else 
				$residence=$country;
		}	
		elseif ($country_code==51)
		{
			$city=$myrow["CITY_RES"];
	 		if($lang)
			{
				$residence_temp=label_select("HIN_CITY_INDIA",$city);
				$city=$residence_temp[0];
				unset($residence_temp);
			}
			else
				$city=$CITY_INDIA_DROP["$city"];
			
			$residence=$city;
			/*$country="India";
			if($city!="")
				$residence=$city.", ".$country;
			else 
				$residence=$country;
			*/
		}
		else 
		{	 
			if($lang)
			{
				$residence_temp=label_select("HIN_COUNTRY",$country_code);
				$residence=$residence_temp[0];
				unset($residence_temp);
			}
			else
				$residence=$COUNTRY_DROP["$country_code"];
		}
		
		$caste=$myrow["CASTE"];
		if($lang)
		{
			$caste_temp=label_select("HIN_CASTE",$caste);
			$caste=$caste_temp[0];
			unset($caste_temp);
		}
		else
		{
			$caste=$CASTE_DROP["$caste"];
		}
		
		$caste1=explode(":",$caste);
		
		$religion='';
		if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)	
			$religion=$RELIGIONS[$myrow["RELIGION"]];
		if(trim($caste1[1])=="")
			$mycaste=$caste1[0];
		else 
			$mycaste=$caste1[1];
				
		$height=$myrow["HEIGHT"];
		$height=$HEIGHT_DROP["$height"];
		
		$height1=explode("(",$height);
		
		$occupation=$myrow["OCCUPATION"];
		if($lang)
		{
			$occupation_temp=label_select("HIN_OCCUPATION",$occupation);
			$occupation=$occupation_temp[0];
			unset($occupation_temp);
		}
		else
		{
			$occupation=$OCCUPATION_DROP["$occupation"];
		}

		$income=$myrow["INCOME"];
	
		//added by Gaurav on 14 Nov 2005
                if($lang)
		{
			$mtongue1 = label_select("HIN_MTONGUE",$myrow['MTONGUE']);
			$edu_leveln=label_select("HIN_EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
		}
		else
		{
			$mtongue1 = label_select("MTONGUE",$myrow['MTONGUE']);
			$edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
		}

                $mtongue = $mtongue1[0];	
                $edu_level=$edu_leveln[0];

		 $screening=$myrow["SCREENING"];
                        if(isFlagSet("YOURINFO",$screening))
                                $yourinfo=$myrow["YOURINFO"];
                        else
                                $yourinfo="";
                                                                                                                             
                        if(isFlagSet("FATHER_INFO",$screening))
                                $fatherinfo=$myrow["FATHER_INFO"];
                        else
                                $fatherinfo="";
                                                                                                                             
                        if(isFlagSet("SIBLING_INFO",$screening))
                                $siblinginfo=$myrow["SIBLING_INFO"];
                        else
                                $siblinginfo="";
                                                                                                                             
                        if(isFlagSet("SPOUSE",$screening))
                                $spouseinfo=$myrow["SPOUSE"];
                        else
                                $spouseinfo="";
                                                                                                                             
                        if($spouseinfo!="")
                                $spouseinfo="Looking for: " . $spouseinfo;


			$yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,300);
                        //$yourinfo=str_replace('0','',$yourinfo);
                        $yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
                        $yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
                        $yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
                        $yourinfo=str_replace('  ',' ',$yourinfo);      
		
			/**********************added for static name for profilepages**********/	
			$sumProfileid=0;
                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $sumProfileid=$sumProfileid+$myrow["PROFILEID"]{$tempcnt};      //sum of profileid digits
                        }
                        $rotator=$sumProfileid%(strlen($myrow["PROFILEID"]));           //sum mod length of profileid rotator                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $newpos=($tempcnt+$rotator)%strlen($myrow["PROFILEID"]);
                                $rotatedProfileidArr[$newpos]=$myrow["PROFILEID"]{$tempcnt};    //rotated profileid
                        }
                        
                                                                                                                             
                        if(count($rotatedProfileidArr)>1)
			{	
				ksort($rotatedProfileidArr);
                                $rotatedProfileid=implode("",$rotatedProfileidArr);
                        }
			else
                                $rotatedProfileid=$rotatedProfileidArr[0];
                                                                                                                             
                        unset($rotatedProfileidArr);
                        unset($sumProfileid);
                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["USERNAME"]);$tempcnt++)
                        {
                                $asciiChr=ord($myrow["USERNAME"]{$tempcnt});
                                                                                                                             
                                if($asciiChr>=33 && $asciiChr<=126)
                                {
                                        $stat_uname=$rotatedProfileid.$myrow["USERNAME"]{$tempcnt}.$rotator;
                                        break;
                                }
                        }
                        unset($rotatedProfileid);
                        unset($rotator);
			/**********************added for static name for profilepages**********/	
		$photochecksum = md5($profileid+5)."i".($profileid+5);
		// ADDED by lavesh on 2 may 2006.
		$photochecksum_new=intval(intval($profileid)/1000) . "/" . md5($profileid+5);
		//end here
		if(substr($myrow["SOURCE"],0,2)=='mb')
                        $mb='Y';
                else
                        $mb='';

		//added by sriram.
		if(check_astro_details($myrow['PROFILEID'],$myrow['SHOW_HOROSCOPE']))
		{
			$horo_link="horoscope_astro.php";
			$horoscope="Y";
		}
		else
			$horoscope="N";

		/*
		//Correcting the logic of horoscope icon on search/favourite/ignore/photo req page
		if($myrow['SHOW_HOROSCOPE']=='Y')
		{
			if($myrow['COUNTRY_BIRTH']!='0' && $myrow['CITY_BIRTH']!='' && $myrow['BTIME']!='')
			{
				$horo_link="horoscope_astro.php";
				$horoscope="Y";
			}
			else
				$horoscope="N";
		}
		else
			$horoscope="N";
		*/

		$profile_details=array ( "NAME" => $myrow["USERNAME"],
					"AGE" => $myrow["AGE"],
					"HEIGHT" => $height1[0],
					"CASTE" => $mycaste,
					"RELIGION" => $religion,
					"MTONGUE" => $mtongue,
					"OCCUPATION" => $occupation,
					"RESIDENCE" => $residence,
					"SUBSCRIPTION" => $myrow["SUBSCRIPTION"],
					"YOURINFO" => $yourinfo,
					"HAVEPHOTO" => $myrow["HAVEPHOTO"],
					"PHOTO_DISPLAY" => $myrow["PHOTO_DISPLAY"],
					"PRIVACY" => $myrow["PRIVACY"],
					"INCOME" => $income_map["$income"],
					"INCOME_ID" => $income,
					"PHOTOCHECKSUM" => $photochecksum,
					"PHOTOCHECKSUMNEW" =>$photochecksum_new,
					"EDUCATION"=>$edu_level,
                                        "STAT_UNAME"=>$stat_uname,
                                        "GENDER"=>$myrow['GENDER'],
                                        "PHONE"=>$myrow['PHONE_MOB'],
					"PROFILEID"=>$myrow['PROFILEID'],
					"EMAIL"=>$myrow["EMAIL"],
					"SUBCASTE"=>$myrow['SUBCASTE'],
                                        "GOTHRA"=>$myrow['GOTHRA'],
                                        "NAKSHATRA"=>$myrow['NAKSHATRA'],
                                        "PHOTOCHECKSUM_NEW" => $photochecksum_new,
					"PHONE_MOB"=>$myrow['PHONE_MOB'],
                                        "GET_SMS"=>$myrow['GET_SMS'],
					"COUNTRY_RES"=>$myrow['COUNTRY_RES'],
					"HOROSCOPE"=>$horoscope,
					"HORO_LINK" =>$horo_link,
					"MARRIAGE_BUREAU"=>$mb,
					"ACTIVATED" => $myrow['ACTIVATED']
				);

		return $profile_details;
	}
	//function to get the drafts for a particular user given the profile id
	function get_drafts($profileid)
	{
		global $smarty;
		$sql="select DRAFTID,DRAFTNAME,MESSAGE from DRAFTS where PROFILEID='" . $profileid . "'";
		$resultdraft=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		if(mysql_num_rows($resultdraft) > 0)
		{
			while($mydraft=mysql_fetch_array($resultdraft))
			{
				$mymessage=$mydraft["MESSAGE"];
				
				while($mymessage!=ereg_replace("\r\n|\n\r|\n|\r","#n#",str_replace("\"","'",$mymessage)))
					$mymessage=ereg_replace("\r\n|\n\r|\n|\r","#n#",str_replace("\"","'",$mymessage));
					
				$drafts[]=array("DRAFTID" => $mydraft["DRAFTID"],
						"DRAFTNAME" => $mydraft["DRAFTNAME"],
						"MESSAGE" => $mymessage);
			}			
		}
		$no_of_draft=count($drafts);
		if($no_of_draft>4)
			$smarty->assign("max_draft",1);
		mysql_free_result($resultdraft);	
		return $drafts;
	}
	
	//function to get the members that are waiting for a response from a particular user
	function get_waiting_members($profileid,$max_members,$receiver_profileid)
	{
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$profileid;
		$sendersNotIn=$receiver_profileid;
		$typeIn="'I'";
		$contactResult=getResultSet("SENDER",'',$sendersNotIn,$receiversIn,'',$typeIn,'','','',"TIME DESC",'','',$max_members);
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
				$waiting_members[]=$contactResult[$key]["SENDER"];
			unset($contactResult);
		}
		
		return $waiting_members;
	}

	//function to get the details of the members that are waiting for a response from a particular user

	/*************************************************************************************************
		Change Date	:	9 February, 2006
		Changed By	:	Shakti Srivastava
		Reason		:	Income of the users is to be retrieved along with other details
	*************************************************************************************************/
	function get_waiting_members_details($profileid)
	{
		include("dropdowns.php");

		$sql="SELECT AGE,HEIGHT,CASTE,CITY_RES,COUNTRY_RES,OCCUPATION,YOURINFO,SCREENING,HAVEPHOTO,PHOTO_DISPLAY,USERNAME,INCOME FROM JPROFILE WHERE PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please
 try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
		{
			$screening=$row['SCREENING'];
			$val=isFlagSet("YOURINFO",$screening);
			if($val==1)
			{
				$yourinfo=$row['YOURINFO'];
			}
			else
			{
				$yourinfo="-";
			}
			$country_code=$row["COUNTRY_RES"];		
			if($country_code==128)
			{
				$city=$row["CITY_RES"];
				$city=$CITY_USA_DROP["$city"];
				$country="USA";
				if($city!="")
					$residence=$city.", ".$country;
				else 
					$residence=$country;
			}	
			elseif ($country_code==51)
			{
				$city=$row["CITY_RES"];
				$city=$CITY_INDIA_DROP["$city"];
				$country="India";
				if($city!="")
					$residence=$city.", ".$country;
				else 
					$residence=$country;
			}
			else 
			{
				$residence=$COUNTRY_DROP["$country_code"];
			}
			$caste=$row["CASTE"];
			$caste=$CASTE_DROP["$caste"];
			
			$caste1=explode(":",$caste);
			
			if(trim($caste1[1])=="")
			{
				$myrel=$caste1[0];
				$mycaste=$caste1[0];
			}
			else 
			{
				$mycaste=$caste1[1];
				$myrel=$caste1[0];
			}
				
			$height=$row["HEIGHT"];
			$height=$HEIGHT_DROP["$height"];
			
			$height1=explode("(",$height);
			$occupation=$row["OCCUPATION"];
			$occupation=$OCCUPATION_DROP["$occupation"];
			
			if($row["PHOTO_DISPLAY"]!="H" && $row["HAVEPHOTO"]=="Y")
			{
				$havephoto="Y";
				$photochecksum=md5($profileid+5)."i".($profileid+5);
				//Added By Lavesh
				$photochecksum_new=intval(intval($profileid)/1000) . "/" . md5($profileid+5);
				//Ends Here
			}
			else 
			{
				$havephoto="N";
				$photochecksum="";
				$photochecksum_new="";
			}

			//Added By Shakti Srivastava on 9 February, 2006
			$income=label_select("newjs.INCOME",$row['INCOME']);	

			$waiting_members_details=array( "AGE" => $row["AGE"],
                                                        "HEIGHT" => $height1[0],
							"RELIGION" => $myrel,
                                                        "CASTE" => $mycaste,
                                                        "OCCUPATION" => $occupation,
                                                        "RESIDENCE" => $residence,
							"YOURINFO" => $yourinfo,
							"HAVEPHOTO" => $havephoto,
							"USERNAME" => $row["USERNAME"],
							"INCOME" => $income[0],
							"PHOTOCHECKSUMNEW" => $photochecksum_new,
							"PHOTOCHECKSUM" => $photochecksum
							);
		}

		return $waiting_members_details;
	}

	//function to check whether the user has run out of his limit of contacting users for a particular day
	function contact_limit_expired($profileid,$allowed_limit)	
	{
		$current_time=date("Y-m-d");
		$current_date_start=$current_time." 00:00:00";
		$current_date_end=$current_time." 23:59:59";		
		//$sql="select count(*) as count from CONTACTS where SENDER='$profileid' and TYPE='I' and TIME > '$current_date_start'";
		$sql="select count(*) as count from CONTACTS_ONCE where SENDER='$profileid' and TIME > '$current_date_start'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		if($myrow["count"]<$allowed_limit)
			return false;
		else 	
			return true;
		
	}

        //function to check whether the user has run out of his limit of contacting users for a particular month
        function contact_limit_expired_for_month($profileid,$allowed_limit)
        {
                $current_time=date("Y-m-d");
		$DOB=explode("-",$current_time);
		$day=$DOB[2];
		$month=$DOB[1];
		$year=$DOB[0];
		$current_date_start=$year."-".$month."-01 00:00:00";
		//Sharding of CONTACTS done by Sadaf

		$sendersIn=$profileid;
                $typeIn="'I'";
                $timeClause="TIME > '$current_date_start'";
                $contactResult=getResultSet("count(*) as count",$sendersIn,'','','',$typeIn,'',$timeClause);

		if($contactResult[0]["count"]<$allowed_limit)
		{
			unset($contactResult);
			return false;
		}
		else
		{
			unset($contactResult);
			return true;
		}
        }
		
	/*function getMonthName($month)
	{
		switch ($month)
		{
			case "01":
			case "1":
				return "Jan";
			case "02":
			case "2":
				return "Feb";
			case "03":
			case "3":
				return "Mar";
			case "04":
			case "4":
				return "Apr";
			case "05":
			case "5":
				return "May";
			case "06":
			case "6":
				return "Jun";
			case "07":
			case "7":
				return "Jul";
			case "08":
			case "8":
				return "Aug";
			case "09":
			case "9":
				return "Sep";
			case "10":
				return "Oct";
			case "11":
				return "Nov";
			case "12":
				return "Dec";
		}
	}*/
	
	function obscene_message($message,$sender,$receiver)
	{
		$orgmessage=$message;
		
		$message=formatMessage($message);
		
		$sql="select SQL_CACHE WORD from OBSCENE_WORDS";
		$result=mysql_query_decide($sql);
		
		while($myrow=mysql_fetch_array($result))
		{
			if(strpos($message," " . $myrow["WORD"] . " ")!==false)
			{
				//mail("vivek@jeevansathi.com,vikas@jeevansathi.com","obscene message","Jeevansathi member $sender has sent an obscene message to Jeevansathi member $receiver\nMessage:\n\n$orgmessage");
				return true;
			}
		}
	
		$ip=getenv("REMOTE_ADDR");
		$path=$_SERVER["DOCUMENT_ROOT"];
		include_once($path."/profile/suspected_ip.php");

		$suspected_check=doubtfull_ip("$ip");
		if($suspected_check)
			return true;	

		return false;
	}
	
	function formatMessage($str)
	{
		$str=strtolower($str);
		$len=strlen($str);
		for($i=0;$i<$len;$i++)
		{
			$ch=$str{$i};
			if($ch==" " || $ch=="\n" || $ch=="\t" || $ch=="(" || $ch==")" || $ch==":" || $ch=="\r" || $ch=="." || $ch==",")
			{
				if($isBlank==0)
				{
					$wordstr.="$word ";
					$word="";
					$isBlank=1;
				}
			}
			else
			{ 
				$word.=$ch;
				$isBlank=0;
			}
		}
		
		if($word!="")
			$wordstr.=$word;
			
		$wordstr=" " . $wordstr . " ";
		
		return $wordstr;
	}
	
	function get_mail_preference($profileid)
	{
		$sql="select PROFILEID from JPROFILE where PROFILEID='$profileid' and SERVICE_MESSAGES='S'";
		$result=mysql_query_decide($sql);
		
		if(mysql_num_rows($result)>0)
			return true;
		else 
			return false;
	}
	
	function free_contact_limit_expired($profileid)
	{
		$allowed_limit=500;
		
		//Sharding of CONTACTS done by Sadaf
		
		$sendersIn=$profileid;
		$contactResult=getResultSet("count(*) as count",$sendersIn);

		if($contactResult[0]["count"]<$allowed_limit)
		{
			unset($contactResult);
			return false;
		}
		else
		{
			unset($contactResult);
			
			$sql="select count(*) as CNT from billing.PAYMENT_DETAIL where PROFILEID='$profileid' and STATUS='DONE'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

			$myrow=mysql_fetch_array($result);

			if($myrow["CNT"]>0)
				return false;
			else
				return true;
		}	
	}

/************************************************************************************************************************
*    FUNCTIONNAME       : save_email
*    DESCRIPTION        : save initial contact info in database newjs.CONTACTS_ONCE
*    CREATED BY         : lavesh
***********************************************************************************************************************/
        function save_email($sender_profileid,$receiver_profileid,$custmessage,$option,$group_email)
        {
		if($option)
			$mail_sent='Y';
		else
			$mail_sent='N';

		if($group_email==1)
		{
			$sql="INSERT IGNORE INTO newjs.CONTACTS_ONCE (SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('$sender_profileid', '$receiver_profileid',now(),'$custmessage','$mail_sent')";
			mysql_query_decide($sql); 
		}
		else
		{
			$sql="INSERT IGNORE INTO newjs.CONTACTS_ONCE (SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('$sender_profileid', '$receiver_profileid',now(),'$custmessage','Y')";
			mysql_query_decide($sql);
			
		}
	}
	
	function contact_personal_error($error_msg,$checksum,$profilechecksum)
	{
		global $smarty;
		if($error_msg=="Your profile is currently being screened. Please contact this user after your profile has been screened ")
		{
			$my_error=1;
			$MSG="Your profile is currently under screening. You can contact Jeevansathi member once your profile is screened and made live.<br>Screening can take up to 24 hrs from the time of registration.";
			
		}
		elseif($error_msg=="Your profile is currenly hidden. You need to unhide your profile before contacting this user.")
		{
			$my_error=1;
			//$MSG="Your profile is currently hidden. Please activate your profile before contacting Jeevansathi members.<br>To activate your profile, click on Hide / delete link in left navigation";
			$MSG="Your profile is currently hidden. Please activate your profile before contacting Jeevansathi members.";
			$lnk="<a href=\"deleteprofile.php?checksum=$checksum&from_search_error=1\">Unhide your profile</a>";
		}
		elseif(substr($error_msg,0,40)=="Some of your profile details are missing")
		{
			$my_error=1;
			$MSG="Your profile is incomplete. Please complete your profile before contacting Jeevansathi members.";
			$lnk="<a href=\"page1_complete.php?checksum=$checksum\">Complete your profile</a>";
		}
		elseif($error_msg=="Your profile has been deleted")
		{
			$my_error=1;
			$MSG="Since your profile has been deleted, you cannot initiate contact with Jeevansathi members.";
		}

		if($my_error)
		{
			if($lnk)
			{
				$smarty->assign("url",1);
        	                $smarty->assign("link",$lnk);
			}
			$data = authenticated($checksum);
			login_relogin_auth($data);
                        $smarty->assign("msg",$MSG);
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->display("error.htm");
			exit;
		}
	}
	function get_profile_details2($profileid_arr)
        {
                include("dropdowns.php");
                $lang=$_COOKIE['JS_LANG'];
                $profileid_arr_unique=array_unique($profileid_arr);
                $profileid_str="'".implode($profileid_arr_unique,"','")."'";
                $income_map=array("1" => "< Rs. 50K",
                                                        "2" => "Rs. 50K - 1Lac",
                                                        "3" => "Rs. 1 - 2Lac",
                                                        "4" => "Rs. 2 - 3Lac",
                                                        "5" => "Rs. 3 - 4Lac",
                                                        "6" => "Rs. 4 - 5Lac",
                                                        "7" => "> Rs. 5Lac",
                                                        "8" => "< $ 25K",
                                                        "9" => "$ 25 - 50K",
                                                        "10" => "$ 50 - 75K",
                                                        "11" => "$ 75K - 1Lac",
                                                        "12" => "$ 1 - 1.5Lac",
                                                        "13" => "$ 1.5 - 2Lac",
                                                        "14" => "> $ 2Lac",
                                                        "15" => "No Income",
                                                        "16" => "Rs. 5 - 7.5Lac",
                                                        "17" => "Rs. 7.5 - 10Lac",
                                                        "18" => "> Rs. 10Lac");
                                                                                                                             
                /* Extra fields were added By lavesh for making chat icon on Home Page on 12 may 2006 */
                                                                                                                             
                $sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,ACTIVATED,RELIGION from JPROFILE where PROFILEID IN ($profileid_str)";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
                while($myrow=mysql_fetch_array($result))
                {
                        $country_code=$myrow["COUNTRY_RES"];
                        if($country_code==128)
                        {
                                $city=$myrow["CITY_RES"];
                                $city=$CITY_USA_DROP["$city"];
                                $country="USA";
                                if($city!="")
                                        $residence=$city.", ".$country;
                                else
                                        $residence=$country;
                        }
                        elseif($country_code==51)
                        {
                                $city=$myrow["CITY_RES"];
                                if($lang)
                                {
                                        $residence_temp=label_select("HIN_CITY_INDIA",$city);
                                        $city=$residence_temp[0];
                                        unset($residence_temp);
                                }
                                else
                                        $city=$CITY_INDIA_DROP["$city"];
                                                                                                                             
                                $residence=$city;
                                /*$country="India";
                                if($city!="")
                                        $residence=$city.", ".$country;
                                else
                                        $residence=$country;
                                */
                        }
                        else
                        {
                                if($lang)
                                {
                                        $residence_temp=label_select("HIN_COUNTRY",$country_code);
                                        $residence=$residence_temp[0];
                                        unset($residence_temp);
                                }
                                else
                                        $residence=$COUNTRY_DROP["$country_code"];
                        }
                                                                                                                             
                        $caste=$myrow["CASTE"];
                        if($lang)
                        {
                                $caste_temp=label_select("HIN_CASTE",$caste);
                                $caste=$caste_temp[0];
                                unset($caste_temp);
                        }
                        else
                        {
                                $caste=$CASTE_DROP["$caste"];
                        }
                                                                                                                             
                        $caste1=explode(":",$caste);
			
			$religion='';
			if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)
				$religion=$RELIGIONS[$myrow["RELIGION"]];
                                                                                                
                        if(trim($caste1[1])=="")
                                $mycaste=$caste1[0];
                        else
                                $mycaste=$caste1[1];
                                                                                                                             
                        $height=$myrow["HEIGHT"];
                        $height=$HEIGHT_DROP["$height"];
                                                                                                                             
                        $height1=explode("(",$height);
                                                                                                                             
                        $occupation=$myrow["OCCUPATION"];
                        if($lang)
                        {
                                $occupation_temp=label_select("HIN_OCCUPATION",$occupation);
                                $occupation=$occupation_temp[0];
                                unset($occupation_temp);
                        }
                        else
                        {
                                $occupation=$OCCUPATION_DROP["$occupation"];
                        }
                                                                                                                             
                        $income=$myrow["INCOME"];
                        //added by Gaurav on 14 Nov 2005
                        if($lang)
                        {
                                $mtongue1 = label_select("HIN_MTONGUE",$myrow['MTONGUE']);
                                $edu_leveln=label_select("HIN_EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
                        }
                        else
                        {
                                $mtongue1 = label_select("MTONGUE",$myrow['MTONGUE']);
                                $edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
                        }
                                                                                                                             
                        $mtongue = $mtongue1[0];
                        $edu_level=$edu_leveln[0];
                                                                                                                             
                        $screening=$myrow["SCREENING"];
                        if(isFlagSet("YOURINFO",$screening))
                                $yourinfo=$myrow["YOURINFO"];
                        else
                                $yourinfo="";
                                                                                                                             
                        if(isFlagSet("FATHER_INFO",$screening))
                                $fatherinfo=$myrow["FATHER_INFO"];
                        else
                                $fatherinfo="";
                                                                                                                             
                        if(isFlagSet("SIBLING_INFO",$screening))
                                $siblinginfo=$myrow["SIBLING_INFO"];
                        else
                                $siblinginfo="";
                                                                                                                             
                        if(isFlagSet("SPOUSE",$screening))
                                $spouseinfo=$myrow["SPOUSE"];
                        else
                                $spouseinfo="";
                                                                                                                             
                        if($spouseinfo!="")
                                $spouseinfo="Looking for: " . $spouseinfo;
                        $yourinfo=substr($yourinfo . " " . $siblinginfo . " " . $fatherinfo . " " . $spouseinfo,0,300);
                        //$yourinfo=str_replace('0','',$yourinfo);
                        $yourinfo=str_replace(',',', ',$yourinfo);      //replace ',' with ', '(comma, space)
                        $yourinfo=str_replace(' ,',', ',$yourinfo);     //replace ' ,' (space, comma) with ', '(comma, space);
                        $yourinfo=str_replace('/','/ ',$yourinfo);      //replace '/' with '/ ' (slash, space)
                        $yourinfo=str_replace('  ',' ',$yourinfo);
                        /**********************added for static name for profilepages**********/
                        $sumProfileid=0;
                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $sumProfileid=$sumProfileid+$myrow["PROFILEID"]{$tempcnt};      //sum of profileid digits
                        }
                        $rotator=$sumProfileid%(strlen($myrow["PROFILEID"]));           //sum mod length of profileid rotator                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["PROFILEID"]);$tempcnt++)
                        {
                                $newpos=($tempcnt+$rotator)%strlen($myrow["PROFILEID"]);
                                $rotatedProfileidArr[$newpos]=$myrow["PROFILEID"]{$tempcnt};    //rotated profileid
                        }
                                                                                                                             
                                                                                                                             
                        if(count($rotatedProfileidArr)>1)
			{	
				ksort($rotatedProfileidArr);
                                $rotatedProfileid=implode("",$rotatedProfileidArr);
                        }
			else
                                $rotatedProfileid=$rotatedProfileidArr[0];
                                                                                                                             
                        unset($rotatedProfileidArr);
                        unset($sumProfileid);
                                                                                                                             
                        for($tempcnt=0;$tempcnt<strlen($myrow["USERNAME"]);$tempcnt++)
                        {
                                $asciiChr=ord($myrow["USERNAME"]{$tempcnt});
                                                                                                                             
                                if($asciiChr>=33 && $asciiChr<=126)
                                {
                                        $stat_uname=$rotatedProfileid.$myrow["USERNAME"]{$tempcnt}.$rotator;
                                        break;
                                }
                        }
                        unset($rotatedProfileid);
                        unset($rotator);
                        /**********************added for static name for profilepages**********/
                        $photochecksum = md5($myrow['PROFILEID']+5)."i".($myrow['PROFILEID']+5);
                        // ADDED by lavesh on 2 may 2006.
                        $photochecksum_new=intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow['PROFILEID']+5);
                        //end here
                        if(substr($myrow["SOURCE"],0,2)=='mb')
                                $mb='Y';
                        else
                                $mb='';
                                                                                                                             
                        //Correcting the logic of horoscope icon on search/favourite/ignore/photo req page
                        if($myrow['SHOW_HOROSCOPE']=='Y')
                        {
                                if($myrow['COUNTRY_BIRTH']!='0' && $myrow['CITY_BIRTH']!='' && $myrow['BTIME']!='')
                                {
                                        $horo_link="horoscope_astro.php";
                                        $horoscope="Y";
                                }
                                else
                                        $horoscope="N";
                        }
                        else
                                $horoscope="N";
                                                                                                                             
                        $profile_details[$myrow['PROFILEID']]=array ( "NAME" => $myrow["USERNAME"],
                                                "AGE" => $myrow["AGE"],
                                                "HEIGHT" => $height1[0],
                                                "CASTE" => $mycaste,
                                                "RELIGION" => $religion,
                                                "MTONGUE" => $mtongue,
                                                "OCCUPATION" => $occupation,
                                                "RESIDENCE" => $residence,
                                                "SUBSCRIPTION" => $myrow["SUBSCRIPTION"],
                                                "YOURINFO" => $yourinfo,
                                                "HAVEPHOTO" => $myrow["HAVEPHOTO"],
                                                "PHOTO_DISPLAY" => $myrow["PHOTO_DISPLAY"],
                                                "PRIVACY" => $myrow["PRIVACY"],
                                                "INCOME" => $income_map["$income"],
                                                "PHOTOCHECKSUM" => $photochecksum,
                                                "PHOTOCHECKSUMNEW" =>$photochecksum_new,
                                                "EDUCATION"=>$edu_level,
                                                "STAT_UNAME"=>$stat_uname,
                                                "GENDER"=>$myrow['GENDER'],
                                                "PHONE"=>$myrow['PHONE_MOB'],
                                                "PROFILEID"=>$myrow['PROFILEID'],
                                                "EMAIL"=>$myrow["EMAIL"],
                                                "SUBCASTE"=>$myrow['SUBCASTE'],
                                                "GOTHRA"=>$myrow['GOTHRA'],
                                                "NAKSHATRA"=>$myrow['NAKSHATRA'],
                                                "PHOTOCHECKSUM_NEW" => $photochecksum_new,
                                                "PHONE_MOB"=>$myrow['PHONE_MOB'],
                                                "GET_SMS"=>$myrow['GET_SMS'],
                                                "COUNTRY_RES"=>$myrow['COUNTRY_RES'],
                                                "HOROSCOPE"=>$horoscope,
                                                "HORO_LINK" =>$horo_link,
                                                "MARRIAGE_BUREAU"=>$mb,
                                                "ACTIVATED"=>$myrow['ACTIVATED']
                                        );
                                                                                                                             
                }
                $count_arr=count($profileid_arr);
		for($z=0;$z<$count_arr;$z++)
                        $profile_details_final[]=$profile_details[$profileid_arr[$z]];
                return $profile_details_final;
        }
	function DayDiff_1($StartDate, $StopDate)
	{
   		return (date('U', strtotime($StopDate)) - date('U', strtotime($StartDate))) / 86400; //seconds a day
	}
?>
