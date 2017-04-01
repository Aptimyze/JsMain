<?php
/**
*       Filename        :       contact.inc
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done for that.
**/
	include_once("arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	//include_once("functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function is_archive($sender,$receiver)
{
	//Sharding On Contacts done by Lavesh Rawat
	$contactResult=getResultSet("SENDER",$sender,"",$receiver,"","","","DATEDIFF(now(),TIME)>30");
	if($contactResult[0]["SENDER"])
	{
		$sql="select count(*) as cnt from newjs.VIEW_LOG where VIEWER=$receiver and VIEWED=$sender";
		$result_new=mysql_query_decide($sql,'',1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($result_new))
			return true;
	}
	return false;
}
//function to calculate only Initiated contacts of Last 30 Days Added By Tapan Arora
function calc_last_30_open_contacts($profileid)
{
        $yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));    // To get the time for previous day
        $back_30_days=date("Y-m-d",$yday);

	//Sharding On Contacts done by Lavesh Rawat
	$contactResult=getResultSet("count(*) as CNT","","",$profileid,"","'I'","","TIME > '$back_30_days 00:00:00'");
	$last_30_open_contacts=$contactResult[0]['CNT'];
        return $last_30_open_contacts;

}

function calc_last_30_90_open_contacts($profileid)
{
        $b31=mktime(0,0,0,date("m"),date("d")-31,date("Y"));
        $back_31_days=date("Y-m-d",$b31);
        $b90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
        $back_90_days=date("Y-m-d",$b90);

	//Sharding On Contacts done by Lavesh Rawat
	$contactResult=getResultSet("count(*) as CNT","","",$profileid,"","'I'","","TIME BETWEEN '$back_90_days 00:00:00' AND '$back_31_days 23:59:59'");
	$last_30_90_open_contacts=$contactResult[0]['CNT'];
        return $last_30_90_open_contacts;;
}

	//function to send accept or decline or cancel response 
	function send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc,$resend=0,$draft_name='',$drafts='')
	{
		global $smarty,$SITE_URL,$mbureau,$sender_details,$receiver_details,$NO_SENDMAIL,$income_map;
		//Sharding On Contacts done by Lavesh Rawat
		$contactResult=getResultSet("TYPE,CONTACTID,FILTERED",$sender_profileid,'',$receiver_profileid);
		if($contactResult[0]['TYPE']=='')
			$contactResult=getResultSet("TYPE,CONTACTID,FILTERED",$receiver_profileid,'',$sender_profileid);
		$previousFlag=$contactResult[0]["TYPE"];
		$row_prev_status['TYPE']=$previousFlag;
		$contact_id=$contactResult[0]["CONTACTID"];
		$filtered=$contactResult[0]["FILTERED"];
		unset($contactResult);
		//Sharding On Contacts done by Lavesh Rawat
		
		if($flag_response=='A')
		{
			if($row_prev_status['TYPE'] == 'I')
			{
				$flag_for_status1=1;//flag is set to change in contact_status table after change in contact table
				//Creating array elements that need to be updated.
				$CONTACT_STATUS_FIELD['ACC_BY_ME']=1;
				$CONTACT_STATUS_FIELD['OPEN_CONTACTS']=-1;
				updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);

				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1, OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$sender_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");

				unset($CONTACT_STATUS_FIELD);
				$CONTACT_STATUS_FIELD['NOT_REP']=-1;
				$CONTACT_STATUS_FIELD['ACC_ME']=1;
				updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);

                                //$sql_resp="UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1,ACC_ME=ACC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                //mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
			elseif($row_prev_status['TYPE'] == 'D')
			{
				//Creating array elements that need to be updated.
                                $CONTACT_STATUS_FIELD['ACC_BY_ME']=1;
                                $CONTACT_STATUS_FIELD['DEC_BY_ME']=-1;
                                updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
	
                               // $sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1,DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$sender_profileid'";
                                //mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				
				unset($CONTACT_STATUS_FIELD);
				$CONTACT_STATUS_FIELD['DEC_ME']=-1;
                                $CONTACT_STATUS_FIELD['ACC_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);

				//$sql_resp="UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1,ACC_ME=ACC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                //mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
			elseif($row_prev_status['TYPE'] == 'C')
			{
				$CONTACT_STATUS_FIELD['ACC_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME+1 WHERE PROFILEID='$sender_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				
				unset($CONTACT_STATUS_FIELD);
				$CONTACT_STATUS_FIELD['ACC_BY_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1 WHERE PROFILEID='$receiver_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
		}
		elseif($flag_response=='D')
		{
			if($row_prev_status['TYPE'] == 'I')
			{
				$flag_for_status2=1;//flag is set to change in contact_status table after change in contact table
				//Creating array elements that need to be updated.
                                $CONTACT_STATUS_FIELD['OPEN_CONTACTS']=-1;
                                $CONTACT_STATUS_FIELD['DEC_BY_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1,DEC_BY_ME=DEC_BY_ME+1 WHERE PROFILEID='$sender_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				

				unset($CONTACT_STATUS_FIELD);
                                $CONTACT_STATUS_FIELD['NOT_REP']=-1;
                                $CONTACT_STATUS_FIELD['DEC_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1,DEC_ME=DEC_ME+1 WHERE PROFILEID='$receiver_profileid'";
                                //mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
				
			}
			elseif($row_prev_status['TYPE'] == 'A')
			{
				//Creating array elements that need to be updated.
                                $CONTACT_STATUS_FIELD['ACC_BY_ME']=-1;
                                $CONTACT_STATUS_FIELD['DEC_BY_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1,DEC_BY_ME=DEC_BY_ME+1 WHERE PROFILEID='$sender_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");

				unset($CONTACT_STATUS_FIELD);
                                $CONTACT_STATUS_FIELD['ACC_ME']=-1;
                                $CONTACT_STATUS_FIELD['DEC_ME']=1;
                                updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1,DEC_ME=DEC_ME+1 WHERE PROFILEID='$receiver_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
			}
		}
		elseif($flag_response=='C')
		{
			//if cancelled after acceptance then decrement those who accepted me of sender and those who I accepted of the receiver.
			if($row_prev_status['TYPE'] == 'A')
			{
				$CONTACT_STATUS_FIELD['ACC_ME']=-1;
                                updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$sender_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");

				unset($CONTACT_STATUS_FIELD);
                                $CONTACT_STATUS_FIELD['ACC_BY_ME']=-1;
                                updatememcache($CONTACT_STATUS_FIELD,$receiver_profileid,1);

				//$sql_resp = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$receiver_profileid'";
				//mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
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
			//Sharding On Contacts done by Lavesh Rawat
			updateContactsTable($sender_profileid,$receiver_profileid,$contact_id,$flag_response,'Y','','','',$receiver_details["SUBSCRIPTION"],$sender_details["SUBSCRIPTION"]);
			//Sharding On Contacts done by Lavesh Rawat


			$is_archive=is_archive($receiver_profileid,$sender_profileid);

                        //$last_30_open_contacts=calc_last_30_open_contacts($sender_profileid);
                        //$last_30_90_open_contacts=calc_last_30_90_open_contacts($sender_profileid);

                        if($flag_for_status1 || $flag_for_status2)
                        {
				unset($CONTACT_STATUS_FIELD);
				if($is_archive)
					$CONTACT_STATUS_FIELD['LAST_30_90_OPEN_CONTACTS']=-1;
					//$str_arc=" LAST_30_90_OPEN_CONTACTS=LAST_30_90_OPEN_CONTACTS-1 ";
				else
					$CONTACT_STATUS_FIELD['LAST_30_OPEN_CONTACTS']=-1;
					//$str_arc=" LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1 ";

				updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
                               // $sql_resp = "UPDATE newjs.CONTACTS_STATUS SET $str_arc  WHERE PROFILEID='$sender_profileid'";
                               // mysql_query_decide($sql_resp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_resp,"ShowErrTemplate");
                        }
                        if($flag_response=='A' || $flag_response=='D')
                        {
                                //for Match Alert by Tapan Arora
                                $sql_alert="REPLACE INTO newjs.MATCHALERT_PROFILEID(ID,PROFILEID) VALUES('','$receiver_profileid')";
                                mysql_query_decide($sql_alert) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_alert,"ShowErrTemplate");
                        }
                        elseif($flag_response=='C')
                        {
                                $sql_alert="REPLACE INTO newjs.MATCHALERT_PROFILEID(ID,PROFILEID) VALUES('','$sender_profileid')";
                                mysql_query_decide($sql_alert) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_alert,"ShowErrTemplate");
                        }

			/*compatibility code starts*/
			/**********Not needed now******	
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
			
			mysql_query_decide($sql_3d) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_3d,"ShowErrTemplate");
			*****************/
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
		$sqlnew="SELECT GENDER,COUNTRY_RES,SUBSCRIPTION,USERNAME,EMAIL,SERVICE_MESSAGES FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$receiver_profileid'";
		$resnew=mysql_query_decide($sqlnew) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
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
		$sqlnew="SELECT SUBSCRIPTION,USERNAME,EMAIL FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender_profileid'";
		$resnew=mysql_query_decide($sqlnew) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
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
		
/*              include_once("/usr/local/apache/sites/jeevansathi.com/htdocs/profile/snoopy/Snoopy.class.php");
                //include_once("../shakti/bm2/Snoopy.class.php");
                $snooper = new Snoopy;                 
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

			$sql="SELECT GENDER,AGE,HEIGHT,CASTE,SUBCASTE,MTONGUE,CITY_RES,COUNTRY_RES,OCCUPATION,EDU_LEVEL_NEW,YOURINFO,USERNAME,HAVEPHOTO,GOTHRA,NAKSHATRA,PHOTO_DISPLAY,INCOME,SCREENING FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender_profileid'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($res);
                        $havephoto=$row['HAVEPHOTO'];
                        $photo_display=$row['PHOTO_DISPLAY'];
			$gender_sender = $row["GENDER"];
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
					//Symfony Photo Modification
        				$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($sender_profileid);
        				$profilePicObj = $profilePicObjs[$sender_profileid];
        				if ($profilePicObj)
        				{
                				$thumbnailUrl = $profilePicObj->getThumbailUrl($flag_response);
        				}
        				else
        				{
                				$thumbnailUrl = null;
        				}
					$smarty->assign("thumbnailUrl",$thumbnailUrl);
        				//Symfony Photo Modification

                                        $photochecksum = md5($sender_profileid+5)."i".($sender_profileid+5);
                                        $smarty->assign("PHOTOCHECKSUM",$photochecksum);
	
					//$photochecksum_new=intval(intval($sender_profileid)/1000) . "/" . md5($sender_profileid+5);
					//$smarty->assign("PHOTOCHECKSUM_NEW",$photochecksum_new);
					$smarty->assign("SENDER_HAVEPHOTO","Y");
				}
				else
				{
					$smarty->assign("SENDER_HAVEPHOTO","H");
				}
			}
			elseif($havephoto=="U"){
				$smarty->assign("SENDER_HAVEPHOTO","Y");//Quick fix for make all mailer work
				global $IMG_URL;
				if($gender_sender=="M")
					$smarty->assign("thumbnailUrl","$IMG_URL/profile/images/ph_cmgsoon_sm_b.gif");
				elseif($gender_sender=="F")
					$smarty->assign("thumbnailUrl","$IMG_URL/profile/images/ph_cmgsoon_sm_g.gif");
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
			$sender["SUBCASTE"]=$row['SUBCASTE'];
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
				/* $income_map=array("1" => "< Rs. 50K",
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
				*/
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
				$sqlnew="SELECT GENDER,USERNAME FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender_profileid'";
				$resnew=mysql_query_decide($sqlnew) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnew,"ShowErrTemplate");
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
				{
                                        //$from=get_email_id($sender_profileid); by lavesh
                                         //$from=$sen_email;
                                         $reply_to=$sen_email;
                                }
			}
			elseif(in_array("F",$sender_rights))
			{
                                $reply_to=$sen_email;
                        }
					//ends here
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
		if($flag_response=="D")
		{
			$NO_SENDMAIL=0;
			$custmessage=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$flag_response,$drafts);
			$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has declined your contact.<br>";
		}
		if($flag_response=="A")
		{
			$NO_SENDMAIL=0;
			$custmessage=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$flag_response,$drafts);
			
			if($custmessage)
			{
				$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has accepted your contact.<br>";
				$message.="To view the sender's profile, <span class=\"class6\"><a href=\"$SITE_URL/profile/viewprofile.php?username=$sendername\">click here</a></span><br>";
				if(in_array("F",$receiver_rights))
				{
					$message.="To view this user's contact details log on to <a href=\"$SITE_URL/profile/mainmenu.php\">My JeevanSathi</a><br>";
				}
				else
				{
					$message.="To view this user's contact details <a href=\"$SITE_URL/membership/jspc\">upgrade your membership</a><br>";
				}
			}
			else
			{
                                $message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has accepted your contact.<br>To view the sender's profile, <span class=\"class6\"><a href=\"$SITE_URL/profile/viewprofile.php?username=$sendername\">click here</a></span><br>To view this user's contact details <a href=\"$SITE_URL/membership/jspc\">upgrade your membership</a><br>";
                        }
		}
		if($flag_response=='C')
		{
			$custmessage=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$flag_response,$drafts);
			$message="JeevanSathi Member <strong>\"".$sendername."\"</strong> has cancelled the contact made with you.<br>";
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
                                $res_contact=mysql_query_decide($sql);
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
				$sql="select EMAIL,CONTACT,PARENTS_CONTACT,PHONE_RES,PHONE_MOB,SHOW_PARENTS_CONTACT,SHOWADDRESS,SHOWPHONE_MOB,SHOWPHONE_RES from JPROFILE where  activatedKey=1 and PROFILEID='$sender_profileid'";
				$res_contact=mysql_query_decide($sql);
				
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
		//$to="test11qa@yahoo.com,test1qa@gmail.com,test1qa@hotmail.com,test1qa@rediffmail.com,test1qa@sify.com";	
		//if($filtered=='Y')
		//	$NO_SENDMAIL=1;
		if($NO_SENDMAIL!=1 && $to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
			send_email($to,$response_msg,$subject,$from,"","","","","","",1,$reply_to);

			
		//a copy of the mail is to be sent to the logged in user
		if($markcc)
		{	
			//Added By lavesh
			unset($URI);
			 $URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$sender_profileid."&subzone=1&isTextLink=Y";
			/*$snooper->fetch($URI);
			$textlink =  $snooper->results;*/
			$smarty->assign("textlink1",$textlink);

			$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$sender_profileid."&subzone=2&isTextLink=Y";
			/*$snooper->fetch($URI);
			$textlink =  $snooper->results;*/
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
	
	function insert_into_message_log($sender_profileid,$receiver_profileid,$is_msg='N',$obscene='N',$id_obscene='0',$type='R',$custmessage='')
	{
		if($is_msg=='')
			$is_msg='N';
		if($type=='')
			$type='R';
		if($obscene=='')
			$obscene='N';
		//$mysql=new Mysql;
		
		$senderShard=JsDbSharding::getShardNo($sender_profileid);
		$receiverShard=JsDbSharding::getShardNo($receiver_profileid);
        //	$myDbName1=getProfileDatabaseConnectionName($sender_profileid,'',$mysql);
		//$myDbName2=getProfileDatabaseConnectionName($receiver_profileid,'',$mysql);
		if($senderShard==$receiverShard){
			//$myDb=$mysql->connect("$myDbName1");
			$dbMessageLogObj1=new NEWJS_MESSAGE_LOG($senderShard);
		}
		else
		{
			$dbMessageLogObj1=new NEWJS_MESSAGE_LOG($senderShard);
			$dbMessageLogObj2=new NEWJS_MESSAGE_LOG($receiverShard);
		//	$myDb1=$mysql->connect("$myDbName1");
		//	$myDb2=$mysql->connect("$myDbName2");
		}
		//To synchronize time on both the sharded servers we are setting date through php instead of using now()
		$date=date("Y-m-d H:i:s");
		
		$ip=FetchClientIP();
		if(strstr($ip, ","))    
		{                       
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		//added by sriram
		
		//$generated_msg_log_id = generate_id_from_table("MESSAGE_LOG");
		$dbObj = new newjs_MESSAGE_LOG_GET_ID();
		$generated_msg_log_id = $dbObj->getAutoIncrementMessageId();

		//$sql="insert into MESSAGE_LOG (ID,SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE,MSG_OBS_ID,TYPE) values ('$generated_msg_log_id','$sender_profileid','$receiver_profileid','$date',INET_ATON('$ip'),'$is_msg','$obscene','$id_obscene','$type')";
		if($senderShard==$receiverShard)
		{
			$dbMessageLogObj1->insertIntoMessageLog($generated_msg_log_id,$sender_profileid,$receiver_profileid,$is_msg,$obscene,$id_obscene,$type);
			//$mysql->ExecuteQuery($sql,$myDb);
			if($is_msg=='Y')
				insert_into_messages($generated_msg_log_id,$custmessage,$senderShard);
		}
		else
		{
			$dbMessageLogObj1->insertIntoMessageLog($generated_msg_log_id,$sender_profileid,$receiver_profileid,$is_msg,$obscene,$id_obscene,$type);
			$dbMessageLogObj2->insertIntoMessageLog($generated_msg_log_id,$sender_profileid,$receiver_profileid,$is_msg,$obscene,$id_obscene,$type);
			//$mysql->ExecuteQuery($sql,$myDb1);
			//$mysql->ExecuteQuery($sql,$myDb2);
			if($is_msg=='Y')
                        {
			        insert_into_messages($generated_msg_log_id,$custmessage,$senderShard);
				insert_into_messages($generated_msg_log_id,$custmessage,$receiverShard);
			}
		}
		//mysql_query_decide($sql,"",1);

		
		//added by Nikhil --> Update the new message field counter by 1
		if($obscene=='N')
		{		
			unset($CONTACT_STATUS_FIELD);
		}
		return $generated_msg_log_id;
	}

	function insert_into_messages($msg_id,$custmessage,$myDb)
	{
		//$mysql=new Mysql;
		$dbMessageObj=new NEWJS_MESSAGES($myDb);
			$dbMessageObj->insertSingleMessage($msg_id,addslashes(stripslashes($custmessage)));
		//$sql="insert into MESSAGES (ID,MESSAGE) values ('$msg_id','" . addslashes(stripslashes($custmessage)) . "')";
		//$mysql->ExecuteQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		//mysql_query_decide($sql,'',1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}	
	
	function insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$flag_response,$markcc="")
	{
		$ip=FetchClientIP();
		$sql="insert into OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE,MARKCC) values ('$sender_profileid','$receiver_profileid',now(),'$ip','" . addslashes(stripslashes($custmessage)) . "','$flag_response','$markcc')";
		mysql_query_decide($sql);
		return mysql_insert_id_js();
	}
function get_nudge_status($match_id,$profileid)
{
	$sql="select STATUS from jsadmin.OFFLINE_MATCHES where MATCH_ID='$match_id' and PROFILEID='$profileid'";
	$res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");
	if($row=mysql_fetch_array($res))
		return $row[0];
	
}
	
function set_nudge($match_id,$profileid,$nudge_type,$request)
{
	unset($CONTACT_STATUS_FIELD);
	if($nudge_type=='N')
	{
		if($request=='Y')
		{
			$sql="update jsadmin.OFFLINE_MATCHES set STATUS='NACC',CATEGORY=1,MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$string=" NOT_REP=NOT_REP+1,LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1,TODAY_INI_BY_ME=TODAY_INI_BY_ME+1,MONTH_INI_BY_ME=MONTH_INI_BY_ME+1,TOTAL_CONTACTS_MADE=TOTAL_CONTACTS_MADE+1 ";
			
			$status='NACC';
			$sender=$match_id;
			$receiver=$profileid;
			$CONTACT_STATUS_FIELD['NOT_REP']=1;
			$CONTACT_STATUS_FIELD['TODAY_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['MONTH_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=1;
		}
		elseif($request=='N')
		{
			$sql="update jsadmin.OFFLINE_MATCHES set STATUS='NREJ',CATEGORY='',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['DEC_BY_ME']=1;	
			//$string=" LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1,DEC_BY_ME=DEC_BY_ME+1 ";
			$status='NREJ';
                        $sender=$match_id;
                        $receiver=$profileid;
		}	
			
	}
	elseif($nudge_type=='NNOW')
	{
		if($request=='Y')
                {
                        $sql="update jsadmin.OFFLINE_MATCHES set STATUS='ACC',CATEGORY=1,MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['ACC_ME']=1;
			$CONTACT_STATUS_FIELD['TODAY_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['MONTH_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=1;
			
                        //$string=" ACC_ME=ACC_ME+1,LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1,TODAY_INI_BY_ME=TODAY_INI_BY_ME+1,MONTH_INI_BY_ME=MONTH_INI_BY_ME+1,TOTAL_CONTACTS_MADE=TOTAL_CONTACTS_MADE+1 ";
			$status='ACC';
                        $sender=$match_id;
                        $receiver=$profileid;
                }
                elseif($request=='N')
                {
                        $sql="update jsadmin.OFFLINE_MATCHES set STATUS='NNREJ',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['DEC_BY_ME']=1;
                        //$string=" LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1,DEC_BY_ME=DEC_BY_ME+1 ";
			$status='NNREJ';
                        $sender=$match_id;
                        $receiver=$profileid;
                }
	}
	elseif($nudge_type=='NACC')
	{
		if($request=='N')
		{
			$sql="update jsadmin.OFFLINE_MATCHES set STATUS='NREJ',CATEGORY='',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN=''  where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['NOT_REP']=-1;
                        $CONTACT_STATUS_FIELD['DEC_ME']=1;
			//$string=" NOT_REP=NOT_REP-1,DEC_ME=DEC_ME+1 ";
			$no_log=1;
			
		}
		if($request=='Y')
		{
			$sql="update jsadmin.OFFLINE_MATCHES set MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$no_log=1;
		}	
	}
	elseif($nudge_type=='ACC')
        {
                if($request=='N')
                {
                        $sql="update jsadmin.OFFLINE_MATCHES set STATUS='NNREJ',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN=''  where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['NOT_REP']=-1;
                        $CONTACT_STATUS_FIELD['DEC_ME']=1;
                        $string=" NOT_REP=NOT_REP-1,DEC_ME=DEC_ME+1 ";
			$no_log=1;
                }
                if($request=='Y')
                {
                       $sql="update jsadmin.OFFLINE_MATCHES set MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$no_log=1;
                }
        }
	elseif($nudge_type=='NREJ')
	{
		 if($request=='Y')
                 {
		       $sql="update jsadmin.OFFLINE_MATCHES set STATUS='NACC',CATEGORY=1,MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['NOT_REP']=1;
			$CONTACT_STATUS_FIELD['DEC_BY_ME']=-1;
			$CONTACT_STATUS_FIELD['TODAY_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['MONTH_INI_BY_ME']=1;
			$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=1;
			//$string=" NOT_REP=NOT_REP+1,DEC_BY_ME=DEC_BY_ME-1,TODAY_INI_BY_ME=TODAY_INI_BY_ME+1,MONTH_INI_BY_ME=MONTH_INI_BY_ME+1,TOTAL_CONTACTS_MADE=TOTAL_CONTACTS_MADE+1 ";
			$status='NACC';
                        $sender=$match_id;
                        $receiver=$profileid;
		}
	}
	elseif($nudge_type=='NNREJ')
	{
		if($request=='Y')
                 {
                       $sql="update jsadmin.OFFLINE_MATCHES set STATUS='ACC',CATEGORY=1,MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
			$CONTACT_STATUS_FIELD['NOT_REP']=1;
                        $CONTACT_STATUS_FIELD['DEC_BY_ME']=-1;
                        $CONTACT_STATUS_FIELD['TODAY_INI_BY_ME']=1;
                        $CONTACT_STATUS_FIELD['MONTH_INI_BY_ME']=1;
                        $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=1;
                        //$string=" NOT_REP=NOT_REP+1,DEC_BY_ME=DEC_BY_ME-1,TODAY_INI_BY_ME=TODAY_INI_BY_ME+1,MONTH_INI_BY_ME=MONTH_INI_BY_ME+1,TOTAL_CONTACTS_MADE=TOTAL_CONTACTS_MADE+1 ";
			$status='ACC';
                        $sender=$match_id;
                        $receiver=$profileid;
                }	
	}
	elseif($nudge_type=='NI')
	{
		$sql="insert ignore into jsadmin.OFFLINE_MATCHES (STATUS,CATEGORY,SHOW_ONLINE,MATCH_ID,PROFILEID,MATCH_DATE,MOD_DATE) values('NACC',2,'N','$match_id','$profileid',now(),now())";
		//$CONTACT_STATUS_FIELD['NOT_REP']=1;
		//$string=" NOT_REP=NOT_REP+1 ";
		$status='NACC';
                $sender=$match_id;
                $receiver=$profileid;
		$v_con='Y';
	}
	elseif($nudge_type=='NR')
	{
		$sql="update jsadmin.OFFLINE_MATCHES set STATUS='NREJ',CATEGORY='',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN='' where MATCH_ID='$match_id' and PROFILEID='$profileid'";
		 $status='NREJ';
                $sender=$match_id;
                $receiver=$profileid;	
	}
	elseif($nudge_type=='NA')
	{
		$sql="update jsadmin.OFFLINE_MATCHES set STATUS='NACC',CATEGORY=2,SHOW_ONLINE='Y',MATCH_DATE=now(),MOD_DATE=NOW(),SEEN=''  where MATCH_ID='$match_id' and PROFILEID='$profileid'";
		$CONTACT_STATUS_FIELD['NOT_REP']=1;
		//$string=" NOT_REP=NOT_REP+1 ";
		 $status='NACC';
                $sender=$match_id;
                $receiver=$profileid;
	}
	
	
	if($sql)
	{
		$res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_affected_rows_js()>=1 && !$no_log)
		{
			if(!$v_con)
				$v_con='N';

			$sql="insert into jsadmin.OFFLINE_NUDGE_LOG(`SENDER`,`RECEIVER`,`DATE`,`TYPE`,`V_CON`) values('$sender','$receiver',now(),'$status','$v_con')";
			mysql_query_decide($sql)   or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		if($CONTACT_STATUS_FIELD)
		{
			updatememcache($CONTACT_STATUS_FIELD,$match_id);
			//$sql="update newjs.CONTACTS_STATUS set $string where PROFILEID='$match_id'";
			//mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}

	}
	
}	
	function get_source($receiver_profileid)
	{
		$sql="select count(*) from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$receiver_profileid' and SOURCE='ofl_prof'";
		$res=mysql_query_decide($sql) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
			return $row[0];
	}
	//function to make initial contact from the user
	function make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,$resend=0,$stype="",$matchalert_mis_variable="",$MITR='',$filtered='',$recSub='',$senSub='',$autoContact='')
	{
		global $smarty,$SITE_URL,$data,$mysqlObj;
		// if message is not being resent after being unblocked	
		if (!$resend)
		{
			//make an entry in the contacts log
			if(!$flag_again)
			{
				/* Checking if user is trying to hit/exceed the total contacts that the user can make in day/month , if user is not paid than we check for overall limit .*/
				if($data['TODAY_INI_TOTAL']>=$data['DAY_LIMIT'] )
					contact_hit_limit($data['PROFILEID'],'T');
				else if($data['MONTH_INI_TOTAL']>=$data['MONTH_LIMIT']  && $data['GENDER']=='M')
					contact_hit_limit($data['PROFILEID'],'M');
				else if(!stristr($data['SUBSCRIPTION'],'F') && $data['TOTAL_CONTACTS_MADE']>=$data['OVERALL_LIMIT'])
					contact_hit_limit($data['PROFILEID'],'O');
				
		
				//Increment the counter for total contacts daywise, monthwise and overall since one contact is made
				$data['MONTH_INI_TOTAL']+=1;
				$data['TODAY_INI_TOTAL']+=1; 
				$data['TOTAL_CONTACTS_MADE']+=1;
				
				//Log the profileid if contacts limit day/month or overall match with the actuall contacts made by this user
				if($data['TODAY_INI_TOTAL']==$data['DAY_LIMIT'])
					contact_hit_limit($data['PROFILEID'],'T');
				else if($data['MONTH_INI_TOTAL']==$data['MONTH_LIMIT']  && $data['GENDER']=='M')
					contact_hit_limit($data['PROFILEID'],'M');
				else if(!stristr($data['SUBSCRIPTION'],'F') && $data['TOTAL_CONTACTS_MADE']==$data['OVERALL_LIMIT'])
					contact_hit_limit($data['PROFILEID'],'O');
				if($autoContact)
					$autoProfile=$sender_profileid;
				//If sender is offline he is not allowed to make contact
				$sen_source=get_source($sender_profileid);
				if($sen_source)
				{
					if($MITR=='NEW_CONTACT')
						return 1;
					$error_msg=disable_offline_contacts($data["PROFILEID"]);
					echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">$error_msg</div></div>');$.colorbox.close();</script>";die;	
				}

				
				//Sharding On Contacts done by Lavesh Rawat
				$generated_contact_id=insertIntoContacts($sender_profileid,$receiver_profileid,'I','Y',1,$filtered,$recSub,$senSub);
//update fto state
				SymfonyFTOFunctions::updateFTOState($profileid,FTOStateUpdateReason::EOI_SENT);
				updateMemcacheForContactsMade($sender_profileid, $receiver_profileid);
				//Sharding On Contacts done by Lavesh Rawat

				/********************Nudges commented with FTO release********
				$n_source=get_source($receiver_profileid);
				if($n_source)
					set_nudge($sender_profileid,$receiver_profileid,"NI","Y");
				***********************Ends here**************/
			}
			else 	
			{
				//We have to update the filtered field of contact table if not filtered now.
				$whereCon="";
				if($filtered!='Y')
					$whereCon=" FILTERED='' ";
				updateContactsTable($sender_profileid,$receiver_profileid,$contact_id,'','Y',1,'',$whereCon);
			}

		}
		if(!$generated_contact_id)
			$generated_contact_id=get_contact_id($sender_profileid,$receiver_profileid);
		//script and function to track search to contact flow
		include_once("search_contact_flow_tracking.php");
		search_contact_flow_tracking($sender_profileid,$stype,$generated_contact_id,$matchalert_mis_variable,$mysqlObj);
		
		/* Code Added for the user who are less than 30 days old in our site, we are sending them instant mail */
		/**********Instant mailer not to be fired with FTO********/
		$today=date("Y-m-d");
		$sql="SELECT ENTRY_DT FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$receiver_profileid'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$day_diff=DayDiff_1($row["ENTRY_DT"],$today);
		if($day_diff<30)
			$instant_email=1;
		//$offlineMember = isOfflineMember($recSub);
		if($instant_email)
			contact_email_less_than_month($generated_contact_id,$receiver_profileid,$sender_profileid,$custmessage,$filtered);
		/************Ends here**********************/
	}

// New Function Added for sending email to the user who are  30 days older.

/*
function contact_email_less_than_month($contact_id,$receiver_profileid,$sender_profileid,$custmessage,$filtered='')
{
	//include_once("ads.php");
	include_once("sphinx_search_function.php");

	global $noOfActiveServers,$activeServers,$slave_activeServers,$smarty,$_SERVER,$CITY_INDIA_DROP,$COUNTRY_DROP,$EDUCATION_LEVEL_NEW_DROP,$HEIGHT_DROP,$OCCUPATION_DROP,$CASTE_DROP,$MTONGUE_DROP,$CITY_USA_DROP,$INCOME_DROP,$RELIGIONS,$income_map;	

	$protect_obj=new protect;
	$mysqlObj=new Mysql;

	for($i=0;$i<$noOfActiveServers;$i++)
	{
		$myDbName=$activeServers[$i];
		$myDb_arr[$myDbName]=$mysqlObj->connect("$myDbName");
	}

	$db=connect_db();
	$db2=connect_737();

	$subject="Initial contact made";
	$total_mail_sent=0;
	
	//Select No. Of Receiver To Whom Mail Need To Send.
	
	{
		$i=0;$k=0;
		$recr=$receiver_profileid;
		//Added Here To check if receiver is replied to initial contact before sending him initial contact mail.
		//If he had replied to one sender then that sender will not be send in initial contact message.if he had replied to all sender than mail will not send to him.
		$sen=$sender_profileid;
		$conarr=$contact_id;
		
		if($sen)
		{
			$SEN=$sen;
			//Sharding of CONTACTS done by Neha Verma
			$myDbName=getProfileDatabaseConnectionName($recr,'',$mysqlObj);
			@mysql_ping($myDb_arr[$myDbName]);
	 		$myDb=$myDb_arr[$myDbName];
		
			$yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));
		        $back_30_days=date("Y-m-d",$yday);
		        $time_clause="TIME>='$back_30_days 00:00:00'";
		        $sql01="SELECT COUNT(SENDER) as CNT FROM newjs.CONTACTS WHERE RECEIVER='$recr' and TYPE='I' and FILTERED<>'Y' and  $time_clause ";
		        $result=mysql_query($sql01,$myDb) or die(mysql_error($myDb));
		        $total=mysql_fetch_array($result);
		        $smarty->assign("total_eoi",$total['CNT']);
	
			unset($sen);
			unset($conarr);
		}
		//Ends here

		if(1)
		{
			$CNT=1;
			if($CNT)
			{
				$from="contacts@jeevansathi.com";
				$j=0;$i=0;
				$smarty->assign("count",$CNT);
				if($CNT==1)
				{
					$smarty->assign("numberofsender",'A');
					$smarty->assign("text",'Jeevansathi.com Member has');
					$oldrecords=1;
					$smarty->assign("old",$oldrecords);
				}
				else
				{
					$smarty->assign("numberofsender",$CNT);
					$smarty->assign("old",'');
					$smarty->assign("text","Jeevansathi.com's Members have");
					$subject = "$CNT jeevansathi members have contacted you today";
				}
				if($CNT>10)
					$smarty->assign("more_than_limit",'Y');
				else
					$smarty->assign("more_than_limit",'N');

				$sql="SELECT USERNAME,AGE,EMAIL,GENDER,HEIGHT,CASTE,OCCUPATION,CITY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$recr'";
				$res2=mysql_query($sql,$db2) or logError($sql,$db2);
				$row2=mysql_fetch_array($res2);
				mysql_free_result($res2);

				$to=$row2['EMAIL'];
				$recname=$row2['USERNAME'];
				if($row2['SUBSCRIPTION'])
				{
					$rec_rights=explode(",",$row2['SUBSCRIPTION']);
					$smarty->assign("RECEIVER_IS_PAID","1");
				}
				else
				{
					$rec_rights=array();
					$smarty->assign("RECEIVER_IS_PAID","0");
				}
				$GENDER=$row2['GENDER'];

				$COUNTRY=$row2['COUNTRY_RES'];
				$CITY=$row2['CITY_RES'];
				//$ values will not be displayed in templates if COUNTRY is India.
				if($row2['COUNTRY_RES']=='51')
					$smarty->assign("COUNTRY",'I');
			
				$checksum=md5($recr)."i".$recr;
				$smarty->assign("CHECKSUM",$checksum);
				$echecksum=$protect_obj->js_encrypt($checksum,$to);
				$smarty->assign("echecksum",$echecksum);
				{
					//unset($contact_id);
					$rec=$recr;
					$contact_id=$contact_id;
					$message=$custmessage;
					$rec_sender=$receiver_profileid;
					$custmessage=stripslashes(trim($custmessage));
					
					if($i<5)
						// Maximum Of 10 Senders will be Selected For One Reciever.
					{
					//	unset($sender);
						$list_contactid[$i]=$contact_id;
						$sender=$sender_profileid;
						$sender_rights=get_rights($sender);
						$custum_message=$custmessage;

						$sql="SELECT USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender'";
						$res=mysql_query($sql,$db2) or logError($sql,$db2);
						$row=mysql_fetch_array($res);
						$havephoto=$row['HAVEPHOTO'];
						$photo_display=$row['PHOTO_DISPLAY'];
						$newsenders[$i]["profilechecksum"]=md5($sender) . "i" . $sender;
						$newsenders[$i]["HAVEPHOTO"]=$row['HAVEPHOTO'];
						$newsenders[$i]["photo_display"]=$row['PHOTO_DISPLAY'];	
						$newsenders[$i]["privacy"]=$row['PRIVACY'];
						$is_album=0;
						if($havephoto=='Y')
						{
							//Symfony Photo Modification
							$is_album = SymfonyPictureFunctions::checkMorePhotos($sender,1,$db);

							//if(($photo_display=='H')||($photo_display=='C'))
							if($photo_display=='H')
							{
								$newsenders[$i]["newsenders_HAVEPHOTO"]="H";
							}
							else
							{
								//Symfony Photo Modification
								$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($sender);
        							$profilePicObj = $profilePicObjs[$sender];
        							if ($profilePicObj)
        							{
                							$thumbnailUrl = $profilePicObj->getThumbailUrl();
        							}
        							else
        							{
                							$thumbnailUrl = null;
        							}
								$newsenders[$i]['ThumbnailUrl'] =$thumbnailUrl;
								//Symfony Photo Modification

								$photochecksum = md5($sender+5)."i".($sender+5);
								$newsenders[$i]["PHOTOCHECKSUM"]=$photochecksum;

								//$photochecksum_new=intval(intval($sender)/1000) . "/" . md5($sender+5);
								//$newsenders[$i]["PHOTOCHECKSUM_NEW"]=$photochecksum_new;
								$newsenders[$i]["newsenders_HAVEPHOTO"]="Y";
							}
						}
						else
							$is_album=0;
						$newsenders[$i]['album']=$is_album;
						$newsenders[$i]["album_link"] = urlencode($SITE_URL.'/profile/layer_photocheck.php?checksum=&profilechecksum='.$newsenders[$i]['profilechecksum'].'&seq=1');
						$newsenders[$i]["INCOME"]=$INCOME_DROP[$row['INCOME']];
						$newsenders[$i]["RELIGION"]=$RELIGIONS[$row['RELIGION']];
						$newsenders[$i]["SENDER"]=$sender;
						$newsenders[$i]["NAME"]=$row['USERNAME'];
					
						//modified as 1st three words of yourinfo will be bold.
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
							$newsenders[$i]["INFO"]=substr("<b>".$subyourinfo."</b>"." $yourinfo",0,150);
						}
						else
							$newsenders[$i]["INFO"]='';
						unset($subyourinfo);
						unset($yourinfo);
						//$newsenders[$i]["INFO"]=substr($row['YOURINFO'],0,150);
						$newsenders[$i]["AGE"]=$row['AGE'];
						$newsenders[$i]["GOTHRA"]=$row['GOTHRA'];
						$newsenders[$i]["NAKSHATRA"]=$row['NAKSHATRA'];
						$newsenders[$i]["CITY_RES"]=$row['CITY_RES'];
						$temp=label_select("HEIGHT",$row['HEIGHT']);
						if(is_array($temp))
							$newsendersHEIGHT2=implode(",",$temp);
						else
							$newsendersHEIGHT2=$temp;
						$newsendersHEIGHT=explode("(",$newsendersHEIGHT2);
						$newsenders[$i]["HEIGHT"]=$newsendersHEIGHT[0];
						unset($temp);
						
						$temp=small_label_select("CASTE",$row['CASTE']);
						$temp=label_select("CASTE",$row['CASTE']);
						$tempcaste = $temp[0]; 
						$pos = strpos($tempcaste, ":");
						if($pos==0)
						{
							$caste = $tempcaste;
						}
						else 
						{
							$pos = $pos+1;
							$castelen = strlen($tempcaste);
							$caste = substr($tempcaste,$pos,$castelen-$pos);
						}
						$newsenders[$i]["CASTE"]=$caste;
						unset($temp);

						$temp=label_select("MTONGUE",$row['MTONGUE']);
						$newsenders[$i]["MTONGUE"]=$temp[0];
						unset($temp);

						$temp=label_select("EDUCATION_LEVEL_NEW",$row['EDU_LEVEL_NEW']);
						$newsenders[$i]["EDUCATION"]=$temp[0];
						unset($temp);

						$newsenders[$i]["SUBCASTE"]=$row['SUBCASTE'];
						$temp=label_select("COUNTRY",$row['COUNTRY_RES']);
						$newsenders[$i]["COUNTRY"]=$temp[0];
						unset($temp);
						if(is_numeric($newsenders[$i]['CITY_RES']))
						{
							$newsenders[$i]['CITY_RES']=$CITY_USA_DROP[$newsenders[$i]['CITY_RES']];
						}
						else
						{
							$newsenders[$i]['CITY_RES']=$CITY_INDIA_DROP[$newsenders[$i]['CITY_RES']];
						}
						if($row['COUNTRY_RES']=='51')
						{
							$temp=label_select("CITY_INDIA",$row['CITY_RES']);
						}
						else
						{
							$temp=label_select("CITY_USA",$row['CITY_RES']);
						}

						//$newsenders[$i]["CITY"]=$temp[0];
						unset($temp);

						$temp=label_select("OCCUPATION",$row['OCCUPATION']);
						$newsenders[$i]["OCCUPATION"]=$temp[0];
						unset($temp);
						
						// If City Record Not Found Then RESIDENCE Of Sender Is Indiacated By Its Country.
						if(!$newsenders[$i]["CITY_RES"]=="")
							$newsenders[$i]["RESIDENCE"]=$newsenders[$i]["CITY_RES"];
						else
							$newsenders[$i]["RESIDENCE"]=$newsenders[$i]["COUNTRY"];
							$newsenders[$i]["INCOME"]=$income_map[$row['INCOME']];


						$smarty->assign("GENDER",$GENDER);
						$smarty->assign("RECEIVERNAME",$recname);
						
						$delim="...";
						$length = 160;
						$len = strlen($custum_message);
   					if ($len > $length) {
       				preg_match('/(.{' . $length . '}.*?)\b/', $custum_message, $matches);
       				$custum_message =  rtrim($matches[1]) . $delim;
   					}
   					
						$newsenders[$i]["CUSTMESSAGE"]=trim($custum_message);

						//print_r($newsenders);
						if($CNT==1)
						{
							$subject=$row['AGE'].", ".str_replace("&quot;","\"",$newsenders[$i]["HEIGHT"]).", ".$newsenders[$i]["MTONGUE"].", ".$newsenders[$i]["CASTE"];
							if($newsenders[$i]["OCCUPATION"]!="")
						 		$subject.=", ".$newsenders[$i]["OCCUPATION"];
							$subject.=" has contacted you";
						}

						// condition is there as $sender is required if Only 1 record is fetched from CONTACTS_ONCE
						if(!$oldrecords)
							unset($sender);
					}
					$check_contact_recommendation[$rec_sender]=$contact_id;
					$cnt_sub=$rec_sender;
					$i++;
				}

				if(is_array($check_contact_recommendation))
				{
					$start=0;
					foreach($check_contact_recommendation as $key=>$val)
					{
						if($start==0)
						{
							$score_sen=$key;
							$rec_str=$val;
						}
						else
						{
							$score_sen.="','".$key;
							$rec_str.="','".$val;
						}
						$start++;

					}	

					//$rec_str=implode("','",$check_contact_recommendation);
					$sqlss="select CONTACTID,STATUS from MIS.CONTACT_CATEGORY where CONTACTID IN('$rec_str')";
					$resss=mysql_query($sqlss,$db) or logError($sqlss,$db);
					while($rowss=mysql_fetch_array($resss))
					{
						$CONTACT_CATEGORY[$rowss['CONTACTID']]=$rowss['STATUS'];
					}

					foreach($check_contact_recommendation as $key=>$val)
					{
						$CONTACT_STATUS[$key]=$CONTACT_CATEGORY[$val];
					}

					//updating subject line if only one contact received
					if($CNT==1)
					{

						if($CONTACT_STATUS[$cnt_sub]=='H')
							$subject="Highly Recommended-$subject";
						elseif($CONTACT_STATUS[$cnt_sub]=='R')
							$subject="Recommended-$subject";
					}
				}

				$output = CommonFunction::getJsCenterDetails($CITY);
                                $smarty->assign("Agtadd",$output["LOCALITY"]);
                                $smarty->assign("Agtname",$output["AGENT"]);
                                $smarty->assign("Agtmob",$output["MOBILE"]);

				$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$recr."&subzone=1&isTextLink=Y";
				//$snooper->fetch($URI);
				//$textlink =  $snooper->results;
				$smarty->assign("textlink1",$textlink);

				$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$recr."&subzone=2&isTextLink=Y";
				//$snooper->fetch($URI);
				//$textlink =  $snooper->results;
				$smarty->assign("textlink2",$textlink);

				$smarty->assign("PAY_ERISHTA",$pay_erishta);
				$smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
				$smarty->assign("PAY_EVALUE",$pay_evalue);	
				$smarty->assign("newsenders",$newsenders);
				$smarty->assign("senderinfo",$senderinfo);
				$smarty->assign("CONTACT_STATUS",$CONTACT_STATUS);

				unset($senderinfo);
				unset($newsenders);
				//ads($CITY,$COUNTRY);
				
				$initial_contact_msg=$smarty->fetch("Contact_Alert_MultipleProfile.html"); 
		                //echo "-------------------$initial_contact_msg-----------------";die;
				//$to="prachi.mittal@naukri.com,gautam.anurag@gmail.com";
				//$to = "priyanka.rathee@gmail.com,spamcheck@naukri.com,krishnan.ramaswami@naukri.com";	
				$total_mail_sent++;
				//	send_email($to,$initial_contact_msg,$subject,$from);			
				
				//$filtered='Y';

				if($to)
				      save_email($contact_id,$sender_profileid,$receiver_profileid,$custmessage,$NO_SENDMAIL,$group_email);

				if($filtered=='')
					send_email($to,$initial_contact_msg,$subject,$from,"","","","","","","1");
				
				if($contact_id)
				{
					$db=connect_db();
					//$sent_contactid=implode(",",$list_contactid);
					$sqlflag="UPDATE CONTACTS_ONCE SET SENT='Y' WHERE CONTACTID = '$contact_id'";
					mysql_query($sqlflag,$db) or logError($sqlflag,$db);
				}
			}
		}

		if($check_contact_recommendation)
			unset($check_contact_recommendation);
		if($CONTACT_STATUS)
			unset($CONTACT_STATUS);
		if($CONTACT_CATEGORY)
			unset($CONTACT_CATEGORY);
		if($REM_TREND_CONTACTID)
			unset($REM_TREND_CONTACTID);

		$not_allow='';
		unset($list_contactid);
		unset($sent_contactid);
		$i++;
		$db=connect_db();
	}

	$sql="update MIS.CONTACT_MAILER_CNT set MAIL_SENT=MAIL_SENT+$total_mail_sent";
	mysql_query($sql,$db)  or logError($sql,$db);
		
}
*/

	function insert_into_recommended_table($generated_contact_id,$sender_profileid,$receiver_profileid)
	{
		include_once("thumb_identification_array.inc");
		$status='U';
		$per_score=0;
		$profile_score=0;
		$sql="select SCORE from incentive.MAIN_ADMIN_POOL where PROFILEID=$sender_profileid";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
			$profile_score=$row['SCORE'];

		$sql="SELECT PROFILEID,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME from newjs.JPROFILE  where  activatedKey=1 and PROFILEID=$sender_profileid";
		$res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row1=mysql_fetch_array($res))
		{
			
			$my_profileid=$row1['PROFILEID'];
			$my_age=$row1['AGE'];
			$my_height=$row1['HEIGHT'];
			$my_mtongue=$row1['MTONGUE'];
			$my_caste=$row1['CASTE'];
			$my_manglik=$row1['MANGLIK'];
			$my_city=$row1['CITY_RES'];
			$my_country=$row1['COUNTRY_RES'];
			$my_education=$row1['EDU_LEVEL_NEW'];
			$my_occupation=$row1['OCCUPATION'];
			$my_mstatus=$row1['MSTATUS'];
			$my_income=$row1['INCOME'];
			$my_income=getSortByIncome($my_income);
	/*		if($my_income==15)
				$my_income=0;
			elseif($my_income==8)
				$my_income=4;
			elseif($my_income==9)
				$my_income=5;
			elseif($my_income==10)
				$my_income=6;
			elseif($my_income==11)
				$my_income=8;
			elseif($my_income==12)
				$my_income=9;
			elseif($my_income==13)
				$my_income=9;
			elseif($my_income==14)
				$my_income=9;
			elseif($my_income==16)
				$my_income=7;
			elseif($my_income==17)
				$my_income=8;
			elseif($my_income==18)
				$my_income=9;
	*/		$sql4="select * FROM twowaymatch.TRENDS where PROFILEID=$receiver_profileid";
			$res4=mysql_query_decide($sql4)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($row4=mysql_fetch_array($res4))
			{
				$sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";

                                $sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";

                                $sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";

                                if($my_mstatus!='N')
                                        $sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
                                else
                                        $sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

                                if($my_manglik=='M')
                                        $sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
                                else
                                        $sql_array[]="( W_MANGLIK * MANGLIK_N_P)";

                                if($my_country=='51')
                                        $sql_array[]="( W_NRI * NRI_N_P)";
                                else
                                        $sql_array[]="( W_NRI * NRI_M_P)";

                                $sql_final="(".implode("+",$sql_array).")";
                                $sql_final2=" ".implode(" , ",$sql_array)." ";
                                unset($sql_array);
				 $sql5=" SELECT $sql_final as score,MAX_SCORE from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";

                                $result5=mysql_query_decide($sql5) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql5,"ShowErrTemplate");
                                if($row5=mysql_fetch_array($result5))
                                {
                                        $score=$row5['score'];
					$max_score=$row5['MAX_SCORE'];
					if($max_score)
						$per_score=round(($score/$max_score)*100);
					else
						$per_score=0;
					//This function returns the  recommendation b/w users
                                        $recommendation_flag=map_admin_reverse_logic($profile_score,$per_score);
                                       	if($recommendation_flag)
						$status=$recommendation_flag;

                                }	
			}

		}
		$sql="insert ignore into MIS.`CONTACT_CATEGORY`(CONTACTID,STATUS,SCORE,PROFILE_SCORE) values('$generated_contact_id','$status','$per_score','$profile_score')";	
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}	

	//function to send a personalised message 
	function send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc,$resend=0)
	{	
		global $smarty,$SITE_URL,$NO_SENDMAIL,$sender_details,$receiver_details;
		
		$status='';
		$NO_SENDMAIL=0;
		$contactResult=getResultSet("TYPE,FILTERED",$sender_profileid,'',$receiver_profileid);
                if($contactResult[0]['TYPE']=='')
                        $contactResult=getResultSet("TYPE,FILTERED",$receiver_profileid,'',$sender_profileid);
                $filtered=$contactResult[0]["FILTERED"];
		//if($filtered=='Y')
		//	$NO_SENDMAIL=1;
		$custmessage=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$status,$drafts);
		/***********************Update seen while writing message from any tuple************************/		
		updateContactsSeen($sender_profileid, $receiver_profileid, $_REQUEST['who'], $contact_status, $_REQUEST['viewed_profile'] , 'Y');
		/************************Ends here**************************************************************/


                //added by lavesh on dec to reduce 6 query on jprofile. 
                //global $jprofile_contact will store desired deatils of both sender and receiver.
                include_once("reduce_jprofilequery.php");
                limiting_contact_jprofile_query($sender_profileid,$receiver_profileid);

                global $jprofile_contact;
                if($jprofile_contact[$receiver_profileid]["SERVICE_MESSAGES"]!='S')
                        $SERVICE_MESSAGE_UNSUBSCRIBED=1;
                if($jprofile_contact[$receiver_profileid]["ACTIVATED"]=='Y')
                        $to=$jprofile_contact[$receiver_profileid]["EMAIL"];
                if($jprofile_contact[$sender_profileid]["ACTIVATED"]=='Y')
                        $from=$jprofile_contact[$sender_profileid]["EMAIL"];

		$smarty->assign("RECEIVERNAME",$jprofile_contact[$receiver_profileid]["USERNAME"]);
                $smarty->assign("REC_GENDER",$jprofile_contact[$receiver_profileid]["GENDER"]);
                $sendername=$jprofile_contact[$sender_profileid]["USERNAME"];
                $receivername=$jprofile_contact[$receiver_profileid]["USERNAME"];
		$sender_email=$jprofile_contact[$sendername]["EMAIL"];
                $smarty->assign("SENDERNAME",$sendername);
                $smarty->assign("RECEIVERNAME",$receivername);
                $smarty->assign("SENDERNAME",$sendername);
                $smarty->assign("SEND_PROF",$sender_profileid);
                $smarty->assign("SUBJECT","Message from $sendername");

                $subject="Message from a jeevansathi member";
		$photo_display=$jprofile_contact[$sender_profileid]['PHOTO_DISPLAY'];
		$sender_gender=$jprofile_contact[$sender_profileid]["GENDER"];
		$displayPhoto = displayPhoto($sender_profileid, $sender_gender, $jprofile_contact[$sender_profileid]['HAVEPHOTO'], $jprofile_contact[$sender_profileid]['PHOTO_DISPLAY'], $jprofile_contact[$sender_profileid]['PRIVACY']);
		$smarty->assign("DISPLAY_PHOTO",$displayPhoto);

		/*if($photo_display=='H')
		{
			if($sender_gender=='M')
				$hide_img="$IMG_URL/profile/ser4_images/photo_hidden_sm_b.gif";
			else
				$hide_img="$IMG_URL/profile/ser4_images/photo_hidden_sm_g.gif";
			$smarty->assign("HIDE_IMG",$hide_img);
		}*/

                $smarty->assign("CUST_MESSAGE",htmlspecialchars(trim($custmessage),ENT_QUOTES));
		

		$send_msg=$smarty->fetch("message_mailer.htm");
		$from_actual="contacts@jeevansathi.com";
		
		if($NO_SENDMAIL!=1 && $to && $SERVICE_MESSAGE_UNSUBSCRIBED!=1)
			send_email($to,$send_msg,$subject,$from_actual,"","","","","","",1,$sender_email);
			//send_email($to,$send_msg,$subject,$from);
		
		if($markcc)
		{
			$to=$from;
			$from="contacts@jeevansathi.com";
			$subject="CC:$subject";
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
				$sql3="update DRAFTS set MESSAGE='".addslashes(stripslashes($custmessage))."',CREATE_TIME=NOW() where PROFILEID='$profileid'and DRAFTNAME='$savedraft'";	
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
					$sql2="insert into DRAFTS(PROFILEID,DRAFTNAME,MESSAGE,CREATE_TIME) values('$profileid','".addslashes(stripslashes($savedraft))."','".addslashes(stripslashes($custmessage))."',NOW())";
					mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
				}	
				else//The limit for allowed no. of drafts has been exceeded
				{
					$error_msg="<br>&#8226; You are allowed to store at most 5 drafts";
					$error_msg.="<a href=\"\"></a>";

					//modifed by sriram
					if($receiver_profileid)
					{
						$sql_usrname = "SELECT USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$receiver_profileid'";
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
		$sql="select USERNAME from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
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
		//Sharding On Contacts done by Lavesh Rawat
		$contactResult=getResultSet("CONTACTID",$sender_profileid,'',$receiver_profileid);
		if($contactResult[0]['CONTACTID']=='')
			$contactResult=getResultSet("CONTACTID",$receiver_profileid,'',$sender_profileid);
		$contact_id=$contactResult[0]["CONTACTID"];
		unset($contactResult);
		return($contact_id);
	}
	
	//function to get the email id of a person given the profile id	
	function get_email_id($profileid)
	{
		$sql="select EMAIL from JPROFILE where  activatedKey=1 and PROFILEID='$profileid' and ACTIVATED='Y'";
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
	function can_contact($sender_profileid,$receiver_profileid,$sender_details,$status,$checksum,$paid)
	{
		$error_msg = "";
		//Check the expiry limit
		if(free_contact_limit_expired($sender_profileid))
		{
			$sql="insert into CONTACT_LIMIT(PROFILEID,DATE,REASON) values ('" . $sender_profileid . "',now(),'FREE')";
			mysql_query_decide($sql);
			$error_msg = "You cannot contact this Profile as you have reached your contact limit";
			return $error_msg;
		}
		//check if the user is only trying to make initial contact
		if(!$status)
			$status = "I";
		if($status!="I")
		{
			$error_msg="This operation is not allowed";
			return $error_msg;
		}
		//male -> female or female -> male only		
		$sender_gender=$sender_details["GENDER"];
		$sql="select GENDER,USERNAME,ACTIVATED from JPROFILE where  activatedKey=1 and PROFILEID='$receiver_profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		if(!$myrow)
		{
			$sql="select GENDER,USERNAME,ACTIVATED from JPROFILE where  PROFILEID='$receiver_profileid' and activatedKey=0";
                	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	                $myrow=mysql_fetch_array($result);
		}
		$receiver_gender=$myrow["GENDER"];
		if($sender_gender==$receiver_gender)	
		{
			$error_msg="You cannot initiate contact with profile(s) of the same gender";
			return $error_msg;
		}	

		//second check : Is the profile to be contacted activated
		if($myrow["ACTIVATED"]!='Y')
		{			
			if($myrow["ACTIVATED"]=='H')
				$error_msg=$myrow["USERNAME"]."&#039;s profile is currently hidden. You can contact this person once he activates his profile. ";
			elseif($myrow["ACTIVATED"]=='D')
				$error_msg=$myrow["USERNAME"]."&#039;s profile has been deleted ";		
			elseif($myrow["ACTIVATED"]=='N'||$myrow["ACTIVATED"]=='U'||$myrow["ACTIVATED"]=='P')
				$error_msg=$myrow["USERNAME"]."&#039;s profile is currently under screening. Please try again after 24 hours. ";		
			elseif(!$myrow)
				$error_msg="Profile not found";
			return $error_msg;
		}	
		
		mysql_free_result($result);
		
		$sql="select ACTIVATED,INCOMPLETE from JPROFILE where  activatedKey=1 and PROFILEID='$sender_profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);

		//If record not found in active partition , then search in entire table.
		if(!$myrow)
		{
			$sql="select ACTIVATED,INCOMPLETE from JPROFILE where  PROFILEID='$sender_profileid' and activatedKey=0";
	                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        	        $myrow=mysql_fetch_array($result);
		}
		
		if($myrow["ACTIVATED"]!='Y')
		{			
			if($myrow["ACTIVATED"]=='H')
				$error_msg="Your profile is currently hidden. Please activate your profile before contacting Jeevansathi members.<br><br><a href=\"hide_delete_revamp.php?checksum=$checksum&from_search_error=1\">Unhide your profile</a>";
			elseif($myrow["ACTIVATED"]=='D')
				$error_msg="Since your profile has been deleted, you cannot initiate contact with Jeevansathi members.";
			elseif(!$myrow)
				$error_msg="Since your profile has been deleted, you cannot initiate contact with Jeevansathi members.";
			return $error_msg;
		}	
		
		mysql_free_result($result);
		return $error_msg;		
	}		
	//function to get the contact status for a pair of users from the contact table 
	function get_contact_status($sender_profileid,$receiver_profileid)
	{
		//Sharding On Contacts done by Lavesh Rawat
		if($sender_profileid && $receiver_profileid)
		{
			$contactResult=getResultSet("TYPE",$sender_profileid,'',$receiver_profileid);
			$type=$contactResult[0]["TYPE"];

			if($contactResult[0]['TYPE']=='')
			{
				$contactResult=getResultSet("TYPE",$receiver_profileid,'',$sender_profileid);
				if($contactResult[0]['TYPE']!='')
					$type="R".$contactResult[0]["TYPE"];
			}
		}
		return($type);
		unset($contactResult);
	}	

	function get_contact_status_rev($sender_profileid,$receiver_profileid)
        {
                //Sharding On Contacts done by Lavesh Rawat
                if($sender_profileid && $receiver_profileid)
                {
                        $contactResult=getResultSet("TYPE,FILTER",$sender_profileid,'',$receiver_profileid);
                        $type=$contactResult[0]["TYPE"];
			$filter=$contactResult[0]["FILTER"];
			$sender='';
                        if($contactResult[0]['TYPE']=='')
                        {
                                $contactResult=getResultSet("TYPE,FILTER",$receiver_profileid,'',$sender_profileid);
				$type=$contactResult[0]["TYPE"];
				$filter=$contactResult[0]["FILTER"];
				$sender='R';
                        }
                }
		if($type)
		{
			$contact['TYPE']=$type;
			$contact['FILTER']=$filter;
			$contact['SENDER']=$sender;
		}
		else
			return array();

                unset($contactResult);
        }      
	//function to get the contact status for a pair of users from the contact table 
        function get_contact_status_using_in($sender_profileid,$receiver_profileid)
        {
                if(is_array($sender_profileid))
		{	
			//$sen_str=implode($sender_profileid,"','");
			$sen_str=implode($sender_profileid,",");
               		$count_sender=count($sender_profileid);
			for($i=0;$i<$count_sender;$i++)
				$sender_cnt_arr[$sender_profileid[$i]]++;
			$rec_str=$receiver_profileid;
		}
		if(is_array($receiver_profileid))
		{	
			//$rec_str=implode($receiver_profileid,"','");
			$rec_str=implode($receiver_profileid,",");
               		$count_receiver=count($receiver_profileid);
			for($i=0;$i<$count_receiver;$i++)
				$receiver_cnt_arr[$receiver_profileid[$i]]++;
			$sen_str=$sender_profileid;
		}
		
		if(is_array($sender_profileid) || is_array($receiver_profileid))
		{
			//Sharding On Contacts done by Lavesh Rawat
			if(is_array($sender_profileid))
				$contactResult=getResultSet("TYPE,SENDER as profileid","$sen_str","",$rec_str);
			else
				$contactResult=getResultSet("TYPE,RECEIVER as profileid","$sen_str","",$rec_str);

                        if(is_array($contactResult))
                        {
                                foreach($contactResult as $key=>$value)
                                {
                                        $contact_status[$contactResult[$key]["profileid"]]=$contactResult[$key]["TYPE"];
                                }
                        }
                        unset($contactResult);
			
			if(is_array($sender_profileid))
				$contactResult=getResultSet("TYPE,RECEIVER as profileid","$rec_str","",$sen_str);
			else
				$contactResult=getResultSet("TYPE,SENDER as profileid","$rec_str","",$sen_str);

                        if(is_array($contactResult))
                        {
                                foreach($contactResult as $key=>$value)
                                {
					if((is_array($contact_status) && !array_key_exists($contact_status[$contactResult[$key]["profileid"]],$contact_status)) || (!is_array($contact_status)))
	                                        $contact_status[$contactResult[$key]["profileid"]]="R".$contactResult[$key]["TYPE"];
                                }
			}

			if(is_array($contact_status))	
			{	
				foreach ($contact_status as $key => $value)
				{
					$contact_status2[]=array('PROFILEID'=>$key,'TYPE'=>$value);
				}

				if(is_array($sender_cnt_arr))
				{	
					$arr_to_use=$sender_cnt_arr;	
					$arr_to_use2=$sender_profileid;	
				}
				else
				{	
					$arr_to_use=$receiver_cnt_arr;	
					$arr_to_use2=$receiver_profileid;	
				}
				
				foreach ($arr_to_use as $key => $value)
				{
					if($value>1 && array_key_exists($key,$contact_status))
					{
						for($i=1;$i<$value;$i++)		
							$contact_status2[]=array('PROFILEID'=>$key,'TYPE'=>$contact_status[$key]);
					}
				}
				
				foreach ($contact_status2 as $key => $value)
				{
					foreach ($arr_to_use2 as $key2 => $value2)
					{	
						if($value['PROFILEID']==$value2)
						{	
							if(!$contact_status3[$key2]['PROFILEID'])		
							{	
								$contact_status3[$key2]=array('PROFILEID'=>$value['PROFILEID'],'TYPE'=>$value['TYPE']);
								break;
							}
						}
					}
				}	
			}
		}
        	return $contact_status3;
	}
	
	function showProfileError($hidden="") 
	{
		if (function_exists('showProfileError_DP'))
                {
	               showProfileError_DP();
			return 1;
		}
                      
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
	
	//This function is required in contacts_made_received file , since we run a single query to fetch record from JPROFILE

	function get_profile_details_all($data_3d,$start,$jsadmin=0, $showAll=0)
	{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		$i=1;
		$total=0;
		global $trend,$income_map,$PAGELEN;
		if(!$PAGELEN) $PAGELEN=10;
		 if(is_array($data_3d))
		foreach($data_3d as $key=>$val)
		{
			if($i>$start && $total<$PAGELEN+2)
			{
				$profileids.="'$val[PROFILEID]',";
				$total++;
			}
			$i++;
		}
	
		$profileids=substr($profileids,0,strlen($profileids)-1);

		$lang=$_COOKIE['JS_LANG'];
	
/*		$income_map=array("1" => "< Rs. 50K",
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
*/		
		/* Extra fields were added By lavesh for making chat icon on Home Page on 12 may 2006 */
		if($profileids=="")
			$profileids="''";
		
		$sql="select PROFILEID,SOURCE,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,FAMILYINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_RES,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,ACTIVATED,RELIGION,SHOWPHONE_MOB,MSTATUS,MANGLIK,SHOWPHONE_RES,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER, CONTACT,SHOWADDRESS,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,PHOTOSCREEN,TIME_TO_CALL_START,TIME_TO_CALL_END,STD,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,HIV,ISD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,RELATION,LAST_LOGIN_DT from newjs.JPROFILE where ";
		if(!$showAll)
			$sql=$sql." activatedKey=1 and ";
		$sql=$sql."PROFILEID IN ($profileids)";
		
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;

		//code added by Tapan Arora to display info about whether mobile no of particular profile has been verified or not.
		$i=0;
		while($myrow=mysql_fetch_array($result))
		{
			$profileid_str.=$myrow['PROFILEID'].",";
                        $show_horoscope_arr[$myrow['PROFILEID']]=array("SHOW_HOROSCOPE"=>$myrow["SHOW_HOROSCOPE"],
                                                                        "GENDER"=>$myrow["GENDER"]);
			if(!$jsadmin)
				$trend[$i]=calculate_user_trend($myrow);
			$i++;
		}
		$i=0;

		$profileid_str=substr($profileid_str,0,strlen($profileid_str)-1);
                if($profileid_str && !$jsadmin)
                        check_astro_details($profileid_str,$show_horoscope_arr);

		// Symfony Photo Modification getPhotoVersion($profileids) removed
				
		@mysql_data_seek($result,0);
		//code addition ended By Tapan Arora

		while($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];

			//code added by Tapan Arora to display info about whether mobile no of particular profile has been verified or not.
                         $mobile=$myrow["PHONE_MOB"];

                         if(($myrow["SHOWPHONE_MOB"]=='Y'|| $myrow["SHOWPHONE_RES"]=='Y'))
                                $show_mob="Y";
                         else
                                $show_mob="N";

			unset($mobile);

                //code addition ended By Tapan Arora

			$country_code=$myrow["COUNTRY_RES"];		
			if($country_code==128)
			{
				$city=$myrow["CITY_RES"];
				$city=$CITY_DROP["$city"];
				$country="USA";
				if($city!="")
					$residence=$city;
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
				$mtongue2 = label_select("MTONGUE_S",$myrow['MTONGUE']);
				$edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
			}
			$mtongue = $mtongue1[0];	
			$mtongue_s = $mtongue2[0];	
			$edu_level=$edu_leveln[0];
			$screening=$myrow["SCREENING"];
				if(isFlagSet("YOURINFO",$screening))
					$yourinfo=$myrow["YOURINFO"];
				else
					$yourinfo="";
			/*if(isFlagSet("FATHER_INFO",$screening))
                                $fatherinfo=$myrow["FATHER_INFO"];
                        else
                                $fatherinfo="";
                                                                                                                             
                        if(isFlagSet("SIBLING_INFO",$screening))
                                $siblinginfo=$myrow["SIBLING_INFO"];
                        else
                                $siblinginfo="";*/

			if(isFlagSet("FAMILYINFO",$screening))
                                $familyinfo=$myrow["FAMILYINFO"];
                        else
                                $familyinfo="";

                        if(isFlagSet("SPOUSE",$screening))
                                $spouseinfo=$myrow["SPOUSE"];
                        else
                                $spouseinfo="";

			if($spouseinfo!="")
                                $spouseinfo="Looking for: " . $spouseinfo;

                                                                                                                             
			$yourinfo=substr($yourinfo . " " . $familyinfo . " " . $spouseinfo,0,247)."...";
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
		/*if(check_astro_details($myrow['PROFILEID'],$myrow['SHOW_HOROSCOPE']))
		{
			$horo_link="horoscope_astro.php";
			$horoscope="Y";
		}
		else
			$horoscope="N";*/

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
		$lastLogin = JsCommon::getLastLogin($myrow["LAST_LOGIN_DT"]);
		$profile_details[$profileid]=array ( "NAME" => $myrow["USERNAME"],
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
					//"HOROSCOPE"=>$horoscope,
					//"HORO_LINK" =>$horo_link,
					"MARRIAGE_BUREAU"=>$mb,
					"ACTIVATED" => $myrow['ACTIVATED'],
                                        "SOURCE" => $myrow['SOURCE'],
                                        "SHOW_PHONE"=>$show_mob,
                                        "PHONE"=>$myrow['PHONE_RES'],
                                        "SHOWPHONE" => $myrow['SHOWPHONE_RES'],
                                        "SHOWMOBILE" => $myrow['SHOWPHONE_MOB'],
                                        "MESSENGER_ID" => $myrow['MESSENGER_ID'],
                                        "MESSENGER_CHANNEL" => $myrow["MESSENGER_CHANNEL"],
                                        "CONTACT" => $myrow['CONTACT'],
                                        "SHOWADDRESS" => $myrow['SHOWADDRESS'],
                                        "PARENTS_CONTACT" => $myrow['PARENTS_CONTACT'],
                                        "SHOW_PARENTS_CONTACT" => $myrow['SHOW_PARENTS_CONTACT'],
					"PHOTOSCREEN" => $myrow['PHOTOSCREEN'],
					"SCREENING" => $myrow['SCREENING'],
					"MTONGUE_S" => $mtongue_s,
					"SHOWPHONE_MOB"=>$myrow["SHOWPHONE_MOB"],
					"SHOWPHONE_RES"=>$myrow["SHOWPHONE_RES"],
					"PHONE_RES"=>$myrow["PHONE_RES"],
					"MOBILE_NUMBER_OWNER"=>$myrow["MOBILE_NUMBER_OWNER"],
					"MOBILE_OWNER_NAME"=>$myrow["MOBILE_OWNER_NAME"],
					"TIME_TO_CALL_START"=>$myrow["TIME_TO_CALL_START"],
					"TIME_TO_CALL_END"=>$myrow["TIME_TO_CALL_END"],
					"RELATION"=>$myrow["RELATION"],
					"SHOWMESSENGER"=>$myrow["SHOWMESSENGER"],
					"PHONE_NUMBER_OWNER"=>$myrow["PHONE_NUMBER_OWNER"],
					"PHONE_OWNER_NAME"=>$myrow["PHONE_OWNER_NAME"],
					"STD"=>$myrow["STD"],
					"HIV"=>$myrow["HIV"],
					"ISD"=>$myrow["ISD"],
					"MOB_STATUS"=>$myrow["MOB_STATUS"],
					"LANDL_STATUS"=>$myrow["LANDL_STATUS"],
					"PHONE_FLAG"=>$myrow["PHONE_FLAG"],
					"LAST_LOGIN_DT"=>$lastLogin
				);
			unset($show_mob);
			$start++;
		}

		return $profile_details;
	}



	//function to get the profile details such as username,age etc. that are to be displayed while showing contacts made or received.
	//Changed by Shakti Srivastava on 9 Feb 2006 to get gender of the user alongwith other details
	//Changed by Shakti Srivastava on 4 April 2006 to get phone number of the user alongwith other details
	// Changed By Lavesh Rawat on May 2006 to Replace photochecksum by photochecksumnew. 
	function get_profile_details($profileid)
	{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		$lang=$_COOKIE['JS_LANG'];
		global $income_map;
	
	/*	$income_map=array("1" => "< Rs. 50K",
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
	*/	
		/* Extra fields were added By lavesh for making chat icon on Home Page on 12 may 2006 */

                $sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,ACTIVATED,INCOMPLETE,RELIGION,SERVICE_MESSAGES,ENTRY_DT from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
		$myrow=mysql_fetch_array($result);

		//If record not found in active partition, then query on entire table.
		if(!$myrow)
		{
			$sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBSCRIPTION,INCOME,SCREENING,FATHER_INFO,SIBLING_INFO,SPOUSE,YOURINFO,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY,MTONGUE,EDU_LEVEL_NEW,GENDER,PHONE_MOB,EMAIL,SUBCASTE,GOTHRA,NAKSHATRA,SOURCE,GET_SMS,PHONE_MOB,SHOW_HOROSCOPE,COUNTRY_BIRTH,CITY_BIRTH,BTIME,ACTIVATED,INCOMPLETE,RELIGION,SERVICE_MESSAGES,ENTRY_DT from JPROFILE where  PROFILEID='$profileid'";
        	        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
	                $myrow=mysql_fetch_array($result);
		}

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
		// Symfony Photo Modification -getPhotoVersion() removed

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
					"ACTIVATED" => $myrow['ACTIVATED'],
					"SERVICE_MESSAGES"=>$myrow['SERVICE_MESSAGES'],
					"ENTRY_DT"=>$myrow["ENTRY_DT"],
					"SOURCE"=>$myrow["SOURCE"],
					"SCREENING"=>$screening
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
		//Sharding On Contacts done by Lavesh Rawat
		$contactResult=getResultSet("SENDER","","$receiver_profileid","$profileid","","'I'","","","",$orderBy="TIME DESC","","",$max_members);	
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$waiting_members[]=$contactResult[$key]["SENDER"];
			}
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
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

		$sql="SELECT AGE,HEIGHT,CASTE,CITY_RES,COUNTRY_RES,OCCUPATION,YOURINFO,SCREENING,HAVEPHOTO,PHOTO_DISPLAY,USERNAME,INCOME FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
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
		global $data,$status;
		
	        if($status!='I')
        		return false;

		$current_time=date("Y-m-d");
		$current_date_start=$current_time." 00:00:00";
		$current_date_end=$current_time." 23:59:59";		
		
		/* Below value is set in login_relogin_auth function or if this function is not called than value is set in the parent php*/
		if($data['TODAY_INI_TOTAL']>=0)
			$myrow['count']=$data['TODAY_INI_TOTAL'];
		else	
		{
			$sql="select count(*) as count from CONTACTS_ONCE where SENDER='$profileid' and TIME > '$current_date_start'";
		
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");	
			$myrow=mysql_fetch_array($result);
		}
			
		if($myrow["count"]<$allowed_limit)
			return false;
		else 	
			return true;	
	}

        //function to check whether the user has run out of his limit of contacting users for a particular month
        function contact_limit_expired_for_month($profileid,$allowed_limit)
        {

		//No need to check for month limit.
		return false;
        	global $data,$status;
        	
        	if($data['GENDER']=='F' || $status!='I' || stristr($data['SUBSCRIPTION'],'F'))
        		return false;
        		
                $current_time=date("Y-m-d");
		$DOB=explode("-",$current_time);
		$day=$DOB[2];
		$month=$DOB[1];
		$year=$DOB[0];
		$current_date_start=$year."-".$month."-01 00:00:00";
		
		/* Below value is set in login_relogin_auth function or if this function is not called than value is set in the parent php*/
		if($data['MONTH_INI_TOTAL']>=0)
			$myrow['count']=$data['MONTH_INI_TOTAL'];
		else 
		{	
			//Sharding On Contacts done by Lavesh Rawat
			$contactResult=getResultSet("count(*) as count",$profileid,"","","",$typeIn="'I'","",$timeClause="TIME > '$current_date_start'");
			$myrow["count"]=$contactResult[0]['count'];
			//Sharding On Contacts done by Lavesh Rawat
		}       
                if($myrow["count"]<$allowed_limit)
                        return false;
                else
                        return true;
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
	
		$ip=FetchClientIP();
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
		$sql="select PROFILEID from JPROFILE where  activatedKey=1 and PROFILEID='$profileid' and SERVICE_MESSAGES='S'";
		$result=mysql_query_decide($sql);
		
		if(mysql_num_rows($result)>0)
			return true;
		else 
			return false;
	}
	
	function free_contact_limit_expired($profileid)
	{
		global $data;
		$allowed_limit=500;

		/* Below value is set is authenticate function*/
		if($data['OVERALL_LIMIT']>=0)
			$allowed_limit=$data['OVERALL_LIMIT'];
		/* Below value is set in login_relogin_auth function or if this function is not called than value is set in the parent php*/
		if($data['TOTAL_CONTACTS_MADE']>=0)
			 $myrow["count"]=$data['TOTAL_CONTACTS_MADE'];
		else 
		{
			//Sharding On Contacts done by Lavesh Rawat
			$contactResult=getResultSet("count(*) as count",$profileid);
			$myrow["count"]=$contactResult[0]['count'];
			//Sharding On Contacts done by Lavesh Rawat
		}	

		if($myrow["count"]<$allowed_limit)
			return false;
		else
		{
			return true;
		}	
	}

/************************************************************************************************************************
*    FUNCTIONNAME       : save_email
*    DESCRIPTION        : save initial contact info in database newjs.CONTACTS_ONCE
*    CREATED BY         : lavesh
***********************************************************************************************************************/
        function save_email($contactid,$sender_profileid,$receiver_profileid,$custmessage,$option,$group_email)
        {
                if($option)
                        $mail_sent='Y';
                else
                        $mail_sent='N';
		$custmessage=addslashes(stripslashes($custmessage));
                if($group_email==1)
                {
                        $sql="INSERT IGNORE INTO newjs.CONTACTS_ONCE (CONTACTID,SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('$contactid','$sender_profileid', '$receiver_profileid',now(),'$custmessage','$mail_sent')";
                        mysql_query_decide($sql);
                }
                else
                {
                        $sql="INSERT IGNORE INTO newjs.CONTACTS_ONCE (CONTACTID,SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('$contactid','$sender_profileid', '$receiver_profileid',now(),'$custmessage','Y')";
                        mysql_query_decide($sql);

                }
        }
	
	function contact_personal_error($error_msg,$checksum,$profilechecksum)
	{
		global $smarty;
		global $invoke_layer;
		global $SITE_URL;
		if($error_msg=="Your profile is currently hidden. You need to unhide your profile before contacting this user.")
		{
			$my_error=1;
			$MSG="Your profile is currently hidden. Please activate your profile before contacting Jeevansathi members.<br><br><a href=\"hide_delete_revamp.php?checksum=$checksum&from_search_error=1\">Unhide your profile</a>";
		}
		elseif($error_msg=="Your profile has been deleted")
		{
			$my_error=1;
			$MSG="Since your profile has been deleted, you cannot initiate contact with Jeevansathi members.";
		}
		elseif($error_msg)
		{
			$my_error=1;
			$MSG=$error_msg;
		}
		if($my_error==1)
		{
			if($invoke_layer)
			{
				$smarty->assign("ERROR_MESSAGE",$MSG);
				$smarty->display("congrats_contact.htm");
			}
			else
				echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">$MSG</div></div>',1);$.colorbox.close();</script>";
			die;
		}
	}

	function DayDiff_1($StartDate, $StopDate)
	{
   		return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
	}

	//function added by sriram.
	//this table is used to generate unique id for CONTACTS and MESSAGE_LOG table (to make use of merge table concept).
	function generate_id_from_table($tablename)
	{
		$tablename .= "_GET_ID";
		$sql_ins_id = "REPLACE INTO  newjs.$tablename (ID,NO_USE_VARIABLE) VALUES('','X')";
		mysql_query_decide($sql_ins_id) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins_id,"ShowErrTemplate");

		$generated_id = mysql_insert_id_js();
		/*$sql_get_id = "SELECT ID FROM newjs.$tablename";
		$res_get_id = mysql_query_decide($sql_get_id) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins_id,"ShowErrTemplate");
		$row_get_id = mysql_fetch_array($res_get_id);

		$generated_id = $row_get_id['ID'];*/


		return $generated_id;
	}


/**********************************************************************************************************
Function    :  disable_offline_contacts 
Description :  to restrict offline user from making contacts

Parameters  :
Profileid of offline profile(used from profileid captured in data array)

Return Value
returns error message 


Author:
Neha verma

***********************************************************************************************************/
function disable_offline_contacts($profileid)
{
	$sql1="SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$profileid'";
	$res1= mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate"); 
	$row=mysql_fetch_array($res1);
	$op=$row["OPERATOR"];

	$sql2="SELECT PHONE FROM jsadmin.PSWRDS WHERE USERNAME='$op'";
	$res2= mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
        $row2=mysql_fetch_array($res2);
	$ph= $row2["PHONE"];
	if($ph)
		$error_msg="You cannot make online contacts. Please contact $op at $ph";
	else
		$error_msg="You cannot make online contacts. Please contact $op";
	return $error_msg;
}
function get_dpp_parameters_for_contact($profileid)
{
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
	$DPP_PARAMETERS='';
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
	{
		if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                {
                        $DPP_PARAMETERS["LAGE"]=$jpartnerObj->getLAGE();
                        $DPP_PARAMETERS["HAGE"]=$jpartnerObj->getHAGE();
		}
		if($jpartnerObj->getPARTNER_CASTE()!='')
		{
			$PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
			$DPP_PARAMETERS["CASTE"]=get_all_caste($PARTNER_CASTE);
		}
		if($jpartnerObj->getPARTNER_COUNTRYRES()!='')
			$DPP_PARAMETERS["COUNTRY_RES"]=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
		if($jpartnerObj->getPARTNER_CITYRES()!='')
		{
			$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());
			$DPP_PARAMETERS["CITY_RES"]=get_all_cities($PARTNER_CITYRES);
		}
		if($jpartnerObj->getPARTNER_MSTATUS()!="")
			$DPP_PARAMETERS["MSTATUS"]=display_format($jpartnerObj->getPARTNER_MSTATUS());
		if($jpartnerObj->getPARTNER_MTONGUE()!="")
                        $DPP_PARAMETERS["MTONGUE"]=display_format($jpartnerObj->getPARTNER_MTONGUE());
		if($jpartnerObj->getPARTNER_RELIGION()!="")
			$DPP_PARAMETERS["RELIGION"]=display_format($jpartnerObj->getPARTNER_RELIGION());
		if($jpartnerObj->getPARTNER_INCOME()!="")
			$DPP_PARAMETERS["INCOME"]=display_format($jpartnerObj->getPARTNER_INCOME());

		if($jpartnerObj->getLINCOME()>=0 || $jpartnerObj->getLINCOME_DOL()>=0)
		{
			include_once("functions_edit_dpp.php");
			if($jpartnerObj->getLINCOME()>=0)
			{
				if($jpartnerObj->getLINCOME_DOL()>=0)
				{
					$incomes=incomeMapping($jpartnerObj->getLINCOME(),19,$jpartnerObj->getLINCOME_DOL(),19);
				}
				else
					$incomes=incomeMapping($jpartnerObj->getLINCOME(),19,null,null);
			}
			else
				if($jpartnerObj->getLINCOME_DOL()>=0)
					$incomes=incomeMapping(null,null,$jpartnerObj->getLINCOME_DOL(),19);
			if($incomes[istr])
				$DPP_PARAMETERS["INCOME"]=explode(",",str_replace("'","",$incomes[istr]));
			
		}
	}
	return $DPP_PARAMETERS;
}
function get_my_parameters_for_dpp_match($profileid)
{
	$MY_PARAMETERS='';
	$sql="SELECT AGE,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MSTATUS,MTONGUE,INCOME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_assoc($res);
	$MY_PARAMETERS=array("AGE"=>$row["AGE"],
				"RELIGION"=>$row["RELIGION"],
				"CASTE"=>$row["CASTE"],
				"COUNTRY_RES"=>$row["COUNTRY_RES"],
				"CITY_RES"=>$row["CITY_RES"],
				"MSTATUS"=>$row["MSTATUS"],
				"INCOME"=>$row["INCOME"],
				"MTONGUE"=>$row["MTONGUE"]);
	return $MY_PARAMETERS;
}
function check_dpp_for_contact($dpp_parameters='',$my_parameters='',$filter_check='N',$filter_parameters='',$sender='',$receiver='')
{
	if($filter_check=="Y")
	{
		if(!is_array($filter_parameters))
			return true;	
	}
	if($dpp_parameters=='')
		return true;
	if($filter_check=="Y")
	{
		
		if($filter_parameters["AGE"]=="Y")
			$check_age=1;
		else
			$check_age=0;
		

		if($filter_parameters["CASTE"]=="Y")
			$check_caste=1;
		else
			$check_caste=0;
		if($filter_parameters["MTONGUE"]=="Y")
			$check_mtongue=1;
		else
			$check_mtongue=0;
		if($filter_parameters["CITY_RES"]=="Y")
			$check_city=1;
		else
			$check_city=0;
		if($filter_parameters["COUNTRY_RES"]=="Y")
			$check_country=1;
		else
			$check_country=0;
		if($filter_parameters["RELIGION"]=="Y")
			$check_religion=1;
		else
			$check_religion=0;
		if($filter_parameters["MSTATUS"]=="Y")
			$check_mstatus=1;
		else
			$check_mstatus=0;

		if($filter_parameters["INCOME"]=="Y")
                        $check_income=1;
                else
                        $check_income=0;

	}
	else
	{
		$check_age=0;
		$check_caste=1;
		$check_mtongue=1;
		$check_city=1;
		$check_country=1;
		$check_mstatus=0;
		$check_religion=0;
		$check_income=0;
	}
	if($check_age && $dpp_parameters["LAGE"] && $dpp_parameters["HAGE"])
		if($my_parameters["AGE"]<$dpp_parameters["LAGE"] || $my_parameters["AGE"]>$dpp_parameters["HAGE"])
		{
			$str_filter="$my_parameters[AGE] > $dpp_parameters[LAGE] --- $my_parameters[AGE] > $dpp_parameters[HAGE] ";
			why_filter("AGE",$str_filter,$sender,$receiver,$filter_check);
			return false;
		}
	if($check_caste && is_array($dpp_parameters["CASTE"]))
		if(!in_array($my_parameters["CASTE"],$dpp_parameters["CASTE"]))
		{
			$str_filter=$my_parameters["CASTE"];
			$str_filter=$str_filter."----".implode(",",$dpp_parameters["CASTE"]);
			why_filter("CASTE",$str_filter,$sender,$receiver,$filter_check);
			return false;
		}
	if($check_mtongue && is_array($dpp_parameters["MTONGUE"]))
                if(!in_array($my_parameters["MTONGUE"],$dpp_parameters["MTONGUE"]))
                {
			$str_filter=$my_parameters["MTONGUE"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["MTONGUE"]);
			why_filter("MTONGUE",$str_filter,$sender,$receiver,$filter_check);
		       return false;
		}
	if($check_city && is_array($dpp_parameters["CITY_RES"]))
                if(!in_array($my_parameters["CITY_RES"],$dpp_parameters["CITY_RES"]))
                {
			$str_filter=$my_parameters["CITY_RES"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["CITY_RES"]);
                        why_filter("CITY_RES",$str_filter,$sender,$receiver,$filter_check);
			return false;
		}
	if($check_country && is_array($dpp_parameters["COUNTRY_RES"]))
                if(!in_array($my_parameters["COUNTRY_RES"],$dpp_parameters["COUNTRY_RES"]))
                {
			$str_filter=$my_parameters["COUNTRY_RES"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["COUNTRY_RES"]);
                        why_filter("COUNTRY_RES",$str_filter,$sender,$receiver,$filter_check);
		        return false;
		}
	if($check_mstatus && is_array($dpp_parameters["MSTATUS"]))
                if(!in_array($my_parameters["MSTATUS"],$dpp_parameters["MSTATUS"]))
                {
			$str_filter=$my_parameters["MSTATUS"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["MSTATUS"]);
                        why_filter("MSTATUS",$str_filter,$sender,$receiver,$filter_check);
		        return false;
		}
	if($check_religion && is_array($dpp_parameters["RELIGION"]))
                if(!in_array($my_parameters["RELIGION"],$dpp_parameters["RELIGION"]))
                {
			$str_filter=$my_parameters["RELIGION"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["RELIGION"]);
                        why_filter("RELIGION",$str_filter,$sender,$receiver,$filter_check);
		        return false;
		}

	if($check_income && is_array($dpp_parameters["INCOME"]))
                if(!in_array($my_parameters["INCOME"],$dpp_parameters["INCOME"]))
                {
			//print_r($dpp_parameters[INCOME]);
			//print_r($my_parameters);die;
			
			$str_filter=$my_parameters["INCOME"];
                        $str_filter=$str_filter."----".implode(",",$dpp_parameters["INCOME"]);
                        why_filter("INCOME",$str_filter,$sender,$receiver,$filter_check);
		        return false;
		}
	//print_r($my_parameters["INCOME"]);
	//print_r($dpp_parameters["INCOME"]);die;
	return true;
}
function why_filter($type,$data,$sender,$receiver,$spam='N')
{
	//Stored only those records that don't have below url's
        $php_self=$_SERVER['PHP_SELF'];
        $file_check[0]='/search/perform';
        $file_check[1]='/profile/simprofile_search.php';
        $file_check[2]='/profile/contacts_made_received.php';
        $file_check[3]='/profile/view_similar_profile.php';

        if(!in_array($php_self,$file_check))
        if($type && $data && $sender && $receiver)
        {
                $sql="insert ignore into MIS.WHY_FILTER(SENDER,RECEIVER,TYPE,DATA,SPAM,`DATE`) values('$sender','$receiver','$type','$data','$spam',now())";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql,"continue");
        }
}

function get_filter_parameters($profileid)
{
	$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");	
	
	if(mysql_num_rows($res))
	{
		$row=mysql_fetch_assoc($res);
		if($row["AGE"]=="Y" || $row["MTONGUE"]=="Y" || $row["MSTATUS"]=="Y" || $row["COUNTRY_RES"]=="Y" || $row["CITY_RES"]=="Y" || $row["RELIGION"]=="Y" || $row["CASTE"]=="Y" || $row["INCOME"]=="Y" || $row["COUNT"] || $row["HARDSOFT"]=="Y")
		{
			$filter_parameters=array("AGE"=>$row["AGE"],
						"MSTATUS"=>$row["MSTATUS"],
						"MTONGUE"=>$row["MTONGUE"],
						"COUNTRY_RES"=>$row["COUNTRY_RES"],
						"CITY_RES"=>$row["CITY_RES"],
						"RELIGION"=>$row["RELIGION"],
						"INCOME"=>$row["INCOME"],
						"CASTE"=>$row["CASTE"],
						"COUNT"=>$row["COUNT"],
						"HARDSOFT"=>$row["HARDSOFT"]);
			return $filter_parameters;
		}
		else
			return '';
	}
	else
		return '';

}
function get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$status,$drafts,$autoContact=0,$isFilter='')
{
	
	global $smarty,$data,$NO_SENDMAIL,$invoke_layer,$filtered_contact;
	$paid = isPaid($sender_details["SUBSCRIPTION"]);
	/*if($sender_details["SUBSCRIPTION"]!='')
	{
		$sub=explode(",",$sender_details["SUBSCRIPTION"]);
		if(in_array("F",$sub))
			$paid=1;
	}*/
	$sender_profileid=$sender_details["PROFILEID"];
	$receiver_profileid=$receiver_details["PROFILEID"];
	$entry_dt=$receiver_details["ENTRY_DT"];
	if($receiver_details["SERVICE_MESSAGES"]!="S")
		$SERVICE_MESSAGE_UNSUBSCRIBED=1;
	$to=$receiver_details["EMAIL"];
	$draft_message=$drafts[$draft_name];
	$custmessage=stripslashes(trim($custmessage));
	if(!$draft_message && !$autoContact)
	{
		$sql="SELECT MESSAGE FROM newjs.DRAFTS WHERE PROFILEID='$sender_profileid' AND DRAFTID='$draft_name'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			$draft_message=$row["MESSAGE"];
		}
	}
	if($paid==1)
	{
		if($custmessage)
		{
			if($custmessage!=$draft_message)
			{
				$new_message=1;
				$smarty->assign("CUST_MESSAGE",nl2br(htmlspecialchars($custmessage,ENT_QUOTES)));
				if($status!='')
					$smarty->assign("SAVE_MESSAGE",1);
				$smarty->assign("PAID",1);
				$smarty->assign("draft_status",$status);
				if(!$autoContact)
					get_user_drafts($custmessage,$status);
			}					
			else
			{
				$smarty->assign("CUST_MESSAGE",nl2br(htmlspecialchars($custmessage,ENT_QUOTES)));
			}
		}
	}
	else
	{
		if($custmessage && $draft_message)
			$smarty->assign("CUST_MESSAGE",nl2br(htmlspecialchars($draft_message,ENT_QUOTES)));
		elseif($custmessage)
		{
			$smarty->assign("CUST_MESSAGE",nl2br(htmlspecialchars($custmessage,ENT_QUOTES)));
			$new_message=1;
		}
	}
	$contact_id=get_contact_id($sender_profileid,$receiver_profileid);
	if($status=='')
		$obscene_status='M';
	else
		$obscene_status=$status;
	if($new_message==1)
	{
		
		if(obscene_message($custmessage,$sender_profileid,$receiver_profileid))
		{
			$NO_SENDMAIL=1;
			$msg_del='D';
			$id_obscene=insert_into_obscene_msg($sender_profileid,$receiver_profileid,$custmessage,$obscene_status);

			$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','Y',$id_obscene,$status,$custmessage);
		}
		else
		{
			$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0',$status,$custmessage);
		}
		
		if($status=='I')
		{
			updateContactsTable($sender_profileid,$receiver_profileid,$contact_id,'','Y','',$msg_del);
		}	
	}
	elseif($custmessage)
	{
		$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'Y','N','0',$status,$draft_message);
	}
	else
	{
		$msg_id=insert_into_message_log($sender_profileid,$receiver_profileid,'N','N','0',$status);
	}
	if($SERVICE_MESSAGE_UNSUBSCRIBED==1 || $filtered_contact=='Y')
	        $NO_SENDMAIL=1;
	/*$today=date("Y-m-d");
	$day_diff=DayDiff_1($entry_dt,$today);
	if($day_diff>30)
		$group_email=1;*/
	//$offlineMember = isOfflineMember($receiver_details["SUBSCRIPTION"]);
	if($to && $status!='D' && $status!='A' && $status!='' && $status!='C' && $isFilter!='Y')
		save_email($contact_id,$sender_profileid,$receiver_profileid,$custmessage,$NO_SENDMAIL,1);
	return $custmessage;
}
function insertMatchAlertContacts($sender_profileid, $receiver_profileid, $clicksource, $stype)
{
	$insert = false;
	if($clicksource == "matchalert1")
	{
		$type = 1;
		$insert=true;
	}
	elseif($clicksource == "matchalert2")
	{
		$type = 2;
		$insert = true;
	}
	elseif($clicksource == "matchalert")
	{
		$type="";
		$insert = true;
	}
	if($insert)
	{
		$sql="insert into MATCHALERT_CONTACTS(SENDER,RECEIVER,DATE,TYPE) values ('$sender_profileid','$receiver_profileid',now(),'$type')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$stype='B';
	}
	return $stype;
}

/****
$sender_details = array('PROFILEID','GENDER','SUBSCRIPTION','INCOMPLETE','ACTIVATED','SOURCE');
$receiver_details = array('PROFILEID','ENTRY_DT','SERVICE_MESSAGE','EMAIL');

****/
function initiateContact($sender_profileid, $receiver_profileId, $sender_details, $receiver_details, $status,$checksum, $draft_name, $DRA_MES, $custmessage, $stype, $matchalert_mis_variable,$clicksource,$autoContact=0)
{
	global $filtered_contact,$isMobile;
	global $invoke_layer;
	$paid = isPaid($sender_details["SUBSCRIPTION"]); 
	/*if(in_array("F",get_rights($sender_profileid)))
		$paid=true;*/
	if(!is_array($receiver_profileId))
	{
		$single = true;
		$receiver_profileId = array($receiver_profileId);
	}
	$cnt = 0;
	$error_msg=check_all_contact_limit($sender_profileid,count($receiver_profileId),$invoke_layer);
	if(!$error_msg)
	{
		foreach($receiver_profileId as $key=>$val)
		{
			$receiver_profileid = $val;
			$error_msg = can_contact($sender_profileid,$receiver_profileid,$sender_details,$status,$checksum, $paid);
			if(!$error_msg)
			{
				if(!$single)
					$receiver_details=get_profile_details($receiver_profileid);
				$profilename = $receiver_details["NAME"];
				if(!$autoContact)
				{
					if(!$sender_details["INCOMPLETE"] || !$sender_details["ACTIVATED"])
					{
						$tempStatus = getIncompleteUnscreenedStatus($sender_profileid);
						$sender_details["INCOMPLETE"] = $tempStatus["INCOMPLETE"];
						$sender_details["ACTIVATED"] = $tempStatus["ACTIVATED"];
					}
					$tempParam = temporaryInterestSuccess($sender_details["INCOMPLETE"], $sender_details["ACTIVATED"]);
				}
				if($tempParam)
				{
					$tempDetail = initiateTemporaryInterest($sender_details, $receiver_details, $draft_name, $custmessage, $DRA_MES, $status, $paid, $tempParam, $stype);
					$error_msg = $tempDetail["ERROR_MESSAGE"];
					$layer_msg = $tempDetail["LAYER_MESSAGE"];
					$show_similar = true;
				}
				else
				{
					$actualContact = true;
					$flag_again=0;
					// log the contacts made through matchalert
					$stype = insertMatchAlertContacts($sender_profileid, $receiver_profileid, $clicksource, $stype);
					$filtered_contact = "";
					$filtered_contact = getFilteredContact($sender_profileid, $receiver_profileid,'','','sendContact');
					make_initial_contact($sender_profileid,$receiver_profileid,"",$custmessage,$flag_again,"","",$stype,$matchalert_mis_variable,"",$filtered_contact,$receiver_details["SUBSCRIPTION"],$sender_details["SUBSCRIPTION"],$autoContact);
					get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$status,$DRA_MES,$autoContact);
					if($isMobile)
						$layer_msg="Congratulations! You have expressed interest in <b>".$profilename."</b>.";
					else{
						$layer_msg="<img src='images/grn_tck.gif' align='absmiddle' />&nbsp;<span class='b grn' style='display:inline;'>Congratulations!</span> You have expressed interest in <b>".$profilename."</b>.";
					if($filtered_contact)
					{
						$show_similar = false;
						$filtered_pid = $receiver_profileid; 
						$filteredProfilenameArr[] = $receiver_details["NAME"];
				        $filteredProfilenames = implode(",", $filteredProfilenameArr);
						$layer_msg.="<br>You do not fit the selection criteria for <b>".$filteredProfilenames."</b>. Hence your interest has been sent to the filtered folder of the recipient(s).";
					}
					else
						$profilenameArr[] = $receiver_details["NAME"];
					}
				}
			}
			else
				$error_mess[] = $error_msg;
			$layer_mess[] = $layer_msg;
			$filtered_profileid_arr[] = $filtered_pid;
			$cnt++;
		}
	}
	if($cnt>1)
	{
		$show_similar = false;
		if($error_mess)
		{
			$error_message = implode("<br>", $error_mess);
		}
		if($actualContact)
		{
			$profilenames = implode(" ,",$profilenameArr);
			$layer_message = "<img src='images/grn_tck.gif' align='absmiddle' />&nbsp;<span class='b grn' style='display:inline;'>Congratulations!</span> You have expressed interest in <b>".$profilenames."</b>.";
			if($filteredProfilenameArr)
			{
				$filteredProfilenames = implode(",", $filteredProfilenameArr);
				$layer_message .= "<br>You do not fit the selection criteria for <b>".$filteredProfilenames."</b>. Hence your interest has been sent to the filtered folder of the recipient(s)";
			}
		}
		else
		{
			if($layer_mess)
				$layer_message = implode("<br>", $layer_mess);
		}
		if($filtered_profileid_arr)
			$filtered_profileid = $filtered_profileid_arr;
	}
	else
	{
		$error_message = $error_msg;
		$layer_message = $layer_msg;
		$filtered_profileid = $filtered_pid;
		$show_similar = true;
	}
	if($error_message)
		$show_similar = false;
	if($layer_message)
		if(!$paid && !$show_similar){
			if($isMobile)
				$layer_message.="<br><br><div class=\"t11\" style=\"float:left;\">Please note that that the message did not include your contact details.<br>To include contact details and edit message <br/><b> <a href=\"tel:18004196299\">Call us</a> to become a Paid Member</b></div>";
			else
				$layer_message.="<br><br><div class=\"t11\" style=\"float:left;\">Please note that that the message did not include your contact details.<br>To include contact details and edit message <b><a href=\"$SITE_URL/profile/mem_comparison.php\" class=\"blink b\">Become a Paid Member Now</a></b></div>";
		}
	$contactDetail["ERROR_MESSAGE"] = $error_message;
	$contactDetail["FILTERED_CONTACT"] = $filtered_contact;
	$contactDetail["LAYER_MESSAGE"] = $layer_message;
	$contactDetail["SHOW_SIMILAR"] = $show_similar;
	$contactDetail["CONTACTED_PROFILES"] = $profilename;
	return $contactDetail;
}

function getFilteredContact($sender_profileid, $receiver_profileid,$is_spam="",$my_parameters="",$action="")
{
	/*
	if(!$is_spam)
		$is_spam=is_spam($sender_profileid);
	*/
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
	$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST();
	if($action=='sendContact')
		$spamParamaters['FLAG_INBOX_EOI']=1;
	elseif($action=='viewContactDetails')
		$spamParamaters['FLAG_CONTACT_DETAIL']=1;
	if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($sender_profileid,$spamParamaters))
	{
		$filtered_contact="Y";
		why_filter('SPAMMER','SPAMMER'.$action,$sender_profileid,$receiver_profileid,'N');
	}
	else
	{	
		if(!$my_parameters)
			$my_parameters=get_my_parameters_for_dpp_match($sender_profileid);

		$filter_parameters=get_filter_parameters($receiver_profileid);
		$dpp_parameters=get_dpp_parameters_for_contact($receiver_profileid);
		if(is_array($filter_parameters))
		{
			$check_dpp=check_dpp_for_contact($dpp_parameters,$my_parameters,'Y',$filter_parameters,$sender_profileid,$receiver_profileid);
			if(!$check_dpp)
				$filtered_contact="Y";
			else
			{
				/*
				if($is_spam)
					$spam_check=1;
				else
				*/
					$filtered_contact="";
			}
		}
		else
		{
			/*
			if($is_spam)
				$spam_check=1;
			else
			*/
				$filtered_contact="";
		}
		$hardSoft="";
		if($filter_parameters)
                {
                    if ($filter_parameters['HARDSOFT']=='Y')
						$hardSoft=true;
					else
					{
						if($filter_parameters['COUNT']>3)
						{
							if($gender='M')
								$hardSoft=true;
							else 
								$hardSoft=false;
						}
						else
							$hardSoft=false;
					}
				}
				else
					$hardSoft=false;	
				



		/*
		if($spam_check)
		{
			$check_dpp=check_dpp_for_contact($dpp_parameters,$my_parameters,'N','',$sender_profileid,$receiver_profileid);
			if(!$check_dpp)
				$filtered_contact="Y";
			else
				$filtered_contact="";
		}
		*/
	        //TRENDS SPAM CONTACT
	       	if($filtered_contact!='Y' && $hardSoft===false)
        	{
                	$spamscore=calculate_spam_score($sender_profileid,$receiver_profileid);
	                if($spamscore)
        	                $filtered_contact='Y';
	        }
	}
        //TRENDS SPAM CONTACT

	return $filtered_contact;
}

function getIntroCallHistory1($sender_profileid,$receiver_profileid,$fields="*")
{
        if($sender_profileid && $receiver_profileid)
        {
                $sql="SELECT $fields FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$sender_profileid' AND MATCH_ID='$receiver_profileid'";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
                if(mysql_num_rows($res))
                {
                        $row=mysql_fetch_assoc($res);
                        return $row;
                }
                else
                {
                        $sql="SELECT $fields FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$sender_profileid' AND MATCH_ID='$receiver_profileid'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
                        if(mysql_num_rows($res))
                        {
                                $row=mysql_fetch_assoc($res);
                                return $row;
                        }
                        else
                                return '';
                }
        }
        else
                return '';
}

function calculate_spam_score($senderid,$receiverid,$db="",$needScore="")
{
	$tScore=0;

	$sql1="select W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_BUCKET,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,MANGLIK_A_P,W_INCOME,INCOME_VALUE_PERCENTILE,I_VAL FROM twowaymatch.TRENDS_FOR_SPAM  where PROFILEID='$receiverid'";
	if($db)
		$res1=mysql_query($sql1,$db) or die(mysql_error().$sql);
	else
		$res1=mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql1,"ShowErrTemplate");
	$row1=mysql_fetch_array($res1);
	if(!$row1)
		return 0;

	$sql2="SELECT AGE,INCOME,HEIGHT FROM newjs.JPROFILE WHERE PROFILEID='$receiverid'";
	if($db)
		$res2=mysql_query($sql2,$db) or die(mysql_error().$sql2);
	else
		$res2=mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql2,"ShowErrTemplate");
	$row2=mysql_fetch_array($res2);

	$sql="SELECT CASTE,MTONGUE,EDU_LEVEL_NEW,OCCUPATION,CASE MANGLIK WHEN 'M' THEN 'M' WHEN 'A' THEN 'A' ELSE 'N' END 'MANGLIK',HEIGHT,CASE MSTATUS WHEN 'N' THEN 'N' ELSE 'M' END 'MSTATUS',COUNTRY_RES,AGE,INCOME FROM newjs.JPROFILE WHERE PROFILEID='$senderid'";
	if($db)
		$res=mysql_query($sql,$db) or die(mysql_error().$sql);
	else
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);

	$i_val=$row1["I_VAL"];

//----1----
	$caste=$row["CASTE"];
	$W_CASTE=$row1['W_CASTE'];
	$CASTE_VALUE_PERCENTILE=$row1['CASTE_VALUE_PERCENTILE'];
	$casteScore=getTrendsScore($caste,$CASTE_VALUE_PERCENTILE,$W_CASTE);
	$tScore+=$casteScore;
//----1----


//----2----
	$mtongue=$row["MTONGUE"];
	$W_MTONGUE=$row1['W_MTONGUE'];
	$MTONGUE_VALUE_PERCENTILE=$row1['MTONGUE_VALUE_PERCENTILE'];
	$mtongueScore=getTrendsScore($mtongue,$MTONGUE_VALUE_PERCENTILE,$W_MTONGUE);
	$tScore+=$mtongueScore;
//----2----

//----3----
        $edu_level_new=$row["EDU_LEVEL_NEW"];
        $W_EDUCATION=$row1['W_EDUCATION'];
        $EDUCATION_VALUE_PERCENTILE=$row1['EDUCATION_VALUE_PERCENTILE'];
        $edu_level_newScore=getTrendsScore($edu_level_new,$EDUCATION_VALUE_PERCENTILE,$W_EDUCATION);
	$tScore+=$edu_level_newScore;
//----3----

//----4----
        $occ=$row["OCCUPATION"];
        $W_OCCUPATION=$row1['W_OCCUPATION'];
        $OCCUPATION_VALUE_PERCENTILE=$row1['OCCUPATION_VALUE_PERCENTILE'];
        $occScore=getTrendsScore($occ,$OCCUPATION_VALUE_PERCENTILE,$W_OCCUPATION);
	$tScore+=$occScore;
//----4----

//----5----
        $manglik=$row["MANGLIK"];
        $W_MANGLIK=$row1['W_MANGLIK'];
	$manglikMP=$row1["MANGLIK_M_P"];
	$manglikNP=$row1["MANGLIK_N_P"];
	$manglikAP=$row1["MANGLIK_A_P"];
	if($manglik=='M')
		$manglikScore=$W_MANGLIK*$manglikMP;
	elseif($manglik=='A')
		$manglikScore=$W_MANGLIK*$manglikAP;
	else
		$manglikScore=$W_MANGLIK*$manglikNP;
	$tScore+=$manglikScore;
//----5----

//----6----
        $H1=$row["HEIGHT"];
        $H2=$row2["HEIGHT"];
        $height=getTrendsBucket($H2,$H1);
	/*
        $height=$row["HEIGHT"];
	*/
        $W_HEIGHT=$row1['W_HEIGHT'];
        $HEIGHT_VALUE_PERCENTILE=$row1['HEIGHT_VALUE_PERCENTILE'];
        $heightScore=getTrendsScore($height,$HEIGHT_VALUE_PERCENTILE,$W_HEIGHT);
	$tScore+=$heightScore;
//----6----

//----7----
        $mstatus=$row["MSTATUS"];
        $W_MSTATUS=$row1['W_MSTATUS'];
	$mstatusMP=$row1["MSTATUS_M_P"];
	$mstatusNP=$row1["MSTATUS_N_P"];
	if($mstatus=='M')
		$mstatusScore=$W_MSTATUS*$mstatusMP;
	else
		$mstatusScore=$W_MSTATUS*$mstatusNP;
	$tScore+=$mstatusScore;

//----8----
        $nri=$row["MSTATUS"];
        $W_NRI=$row1['W_NRI'];
        $nriMP=$row1["NRI_M_P"];
        $nriNP=$row1["NRI_N_P"];
        if($nri=='51')
                $nriScore=$W_NRI*$nriMP;
        else
                $nriScore=$W_NRI*$nriNP;
	$tScore+=$nriScore;
//----8----

//----9----
        $age1=$row["AGE"];
        $age2=$row2["AGE"];
	$age=getTrendsBucket($age2,$age1);
        $W_AGE=$row1['W_AGE'];
        $AGE_VALUE_PERCENTILE=$row1['AGE_BUCKET'];
        $ageScore=getTrendsScore($age,$AGE_VALUE_PERCENTILE,$W_AGE);
	$tScore+=$ageScore;
//----9----

//----10---
	$inc1=getSortByIncome($row["INCOME"]);
	$inc2=getSortByIncome($row2["INCOME"]);
	$inc=getTrendsBucket($inc2,$inc1);
        $W_INCOME=$row1['W_INCOME'];
        $INCOME_VALUE_PERCENTILE=$row1['INCOME_VALUE_PERCENTILE'];
	$incScore=getTrendsScore($inc,$INCOME_VALUE_PERCENTILE,$W_INCOME);
	$tScore+=$incScore;
	if($needScore)
		return $tScore;
//----10---
	if($tScore<0)	
	{
		if($i_val<0 && $tScore<$i_val || $i_val>=0)
		{
			$data="i_val=".$i_val."---score=".$tScore;
			$type="TRENDS";
			why_filter($type,$data,$senderid,$receiverid,'Y');//as discussed with nikhil =='Y' means contact is filtered and no sender is non spammer.
			return 'Y';
		}
	}
	return 0;
}

function getTrendsScore($MYVAL,$PERCENTILE,$weight)
{
	if(!strstr($PERCENTILE,"|$MYVAL#"))
		return 0;
	$temp=strpos($PERCENTILE,"|$MYVAL#");
	$len=strlen($MYVAL)+2;
	$end=strpos($PERCENTILE,"#",$temp);
	$len=strlen($MYVAL)+2;
	$len=$len+$temp-1;
	$temp=strpos($PERCENTILE,"|",$len);
	$lenOfValue=$temp-$len-1;
	$value=substr($PERCENTILE,$len+1,$lenOfValue);
	return $value*$weight;
}

function getTrendsBucket($sender_age,$receiver_age)
{
        if (($sender_age-$receiver_age) <-10)
                $age_diff='SY10';
        else if (($sender_age-$receiver_age)>=-10 && ($sender_age-$receiver_age)<-7)
                $age_diff='SY7t10';
        else if (($sender_age-$receiver_age)>=-7 && ($sender_age-$receiver_age)<-4)
                $age_diff='SY4t7';
        else if (($sender_age-$receiver_age)>=-4 && ($sender_age-$receiver_age)<-2)
                $age_diff='SY2t4';
        else if (($sender_age-$receiver_age)>=-2 && ($sender_age-$receiver_age)<0)
                $age_diff='SY0t2';

        else if($sender_age-$receiver_age==0)
                $age_diff='equal';

        else if (($sender_age-$receiver_age)>=0 && ($sender_age-$receiver_age)<2)
                $age_diff='SE0t2';
        else if (($sender_age-$receiver_age)>=2 && ($sender_age-$receiver_age)<4)
                $age_diff='SE2t4';
        else if (($sender_age-$receiver_age)>=4 && ($sender_age-$receiver_age)<7)
                $age_diff='SE4t7';
        else if (($sender_age-$receiver_age)>=7 && ($sender_age-$receiver_age)<10)
                $age_diff='SE7t10';
        else if ($sender_age-$receiver_age>=10)
                $age_diff='SEe10';
        else
                $age_diff='others';
        return $age_diff;
}
?>
