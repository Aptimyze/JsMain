<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once("search.inc");
	include_once("mobile_detect.php");
	// connect to database
	$db=connect_db();

	
	include("payment_array.php");

	$smarty->assign("PAY_ERISHTA",$pay_erishta);
	$smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
	$smarty->assign("PAY_EVALUE",$pay_evalue);
	
	global $ajax_error;
	$ajax_error=4;

        if($logic_used && !$matchalert_mis_variable && $is_user_active)
                $matchalert_mis_variable=$logic_used."###".$recomending."###".$is_user_active;

	//added by sriram
	$smarty->assign("dontshow_msg",$dontshow_msg);

	$smarty->assign("STYPE",$stype);	
	//added by sriram
	if($bookmark)
	{
		$date = date('Y-m-d');
		$data = authenticated($checksum);
		if($data)
		{
		    $smarty->assign("LOGGEDIN", 1);	
			assign_contact_history($data['PROFILEID']);

			$myid = $data["PROFILEID"];
			$bookmark_id = explode("i",$profilechecksum);
			if(!is_numeric($bookmark_id[1]) || $bookmark_id[1]=="")
			{
				$http_msg = "User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
				$http_msg .= implode(",",$_POST);
			}
			if($bookmark_id[0] == md5($bookmark_id[1]))
			{
				//insert into BOOKMARKS table
				$sql = "REPLACE INTO BOOKMARKS(BOOKMARKER,BOOKMARKEE,BKDATE) VALUES('$myid','$bookmark_id[1]','$date')";
				$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
			}
			$msg = "Profile Successfully bookmarked";
			$smarty->assign("MSG",$msg);
			$smarty->assign("NO_FURTHER_DISPLAY","1");
			$smarty->display('contact_result.htm');
		}
		else
			TimedOut();
	}
	elseif($ignore)//added by sriram
	{
		$data = authenticated($checksum);
		if($data)
		{
		    $smarty->assign("LOGGEDIN", 1);	
			$myid = $data["PROFILEID"];
			
			assign_contact_history($data['PROFILEID']);
			
			$ignore_id = explode("i",$profilechecksum);
			if(!is_numeric($ignore_id[1]) || $ignore_id[1]=="")
			{
				$http_msg = "User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
				$http_msg .= implode(",",$_POST);
			}
			//insert into IGNORE_PROFILE
			$sql_insert="REPLACE INTO IGNORE_PROFILE(PROFILEID,IGNORED_PROFILEID,DATE) VALUES ('$myid','$ignore_id[1]',now())";
			mysql_query_decide($sql_insert) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_insert,"ShowErrTemplate");
			$msg = "Profile Successfully ignored";
			$msg .="<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ignored profiles will be removed from your search after 24 hours";
			$smarty->assign("MSG",$msg);
			$smarty->assign("NO_FURTHER_DISPLAY","1");
			$smarty->assign("FROM_IGNORE",1);
			$smarty->display('contact_result.htm');
			
		}
		else
			TimedOut();
	}
	else
	{
		//added by sriram
		//$profileid=$data['PROFILEID'];

		$smarty->assign("redirect",$redirect);
		reload_on_layer1($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage);
		if($CAME_FROM_CONTACT_MAIL)
		{
			$data=login($username,$password);
			if(!$data)
			{
				$smarty->assign("STATUS",$status);
				$smarty->assign("WRONG_USER","1");
				$dont_zip_now=1;
				include("viewprofile.php");
				exit;
			}
			if($status=='A')
                                $type_CNT=" ACCEPT=ACCEPT+1 " ;
                        if($status=='D')
                                $type_CNT=" DECLINE=DECLINE+1 ";

                        if($type_CNT)
                        {
                                $sql="update MIS.CONTACT_MAILER_CNT set $type_CNT";
                                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        }
		}
		elseif ($crmback == "admin")
		{
			$data = infovision_auth($inf_checksum);
			$smarty->assign("crmback",$crmback);
			$smarty->assign("cid",$cid);
		}
		else
		{
				 $data=authenticated();
                 if ($data)
                    $smarty->assign("LOGGEDIN", 1);	
				assign_contact_history($data['PROFILEID']);
				
		}
		if(!stristr($data['SUBSCRIPTION'],'F'))
                        check_profile_percent();
		include_once("contact.inc");
		
		/*********************Portion of Code added for display of Banners*****************/
		$SITE_URL=$data["SITE_URL"];
		$smarty->assign("data",$data["PROFILEID"]);
		$smarty->assign("bms_topright",14);
		$smarty->assign("bms_left",16);
		$smarty->assign("bms_bottom",23);
		$smarty->assign("bms_middle",15);
		$smarty->assign("bms_new_win",39);
		//$regionstr=6;
		//$zonestr="23";
		//include("../bmsjs/bms_display.php");
		/********************End of Portion of Code*****************************************/

		// don't want to display search option in left panel on profile page in order to reduce page size
		$smarty->assign("NO_SEARCH_OPTION","1");
		// check whether the person is logged in
		//$sender_details=authenticated($checksum);
		$sender_details=$data;
		 //Added By lavesh for counting number of user that accept initial contact through suggested Profile.
		if($suggest_profile)
		{
			$sql="UPDATE newjs.CONTACTS_AFTER_SUGGESTION set COUNT=COUNT+1 WHERE DATE=NOW()";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(!mysql_affected_rows_js())
			{
				$sql="INSERT IGNORE INTO newjs.CONTACTS_AFTER_SUGGESTION(COUNT,DATE) VALUES ('1',now())";
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}			
		}
		 //Ends here

		leftpanel_membership();	
		if ($crmback!="admin")
		{
			if($isMobile){
				navigation($nav_type,"","");
				$header=$smarty->fetch("mobilejs/jsmb_header.html");
				$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
				$smarty->assign("HEADER",$header);
				$smarty->assign("FOOTER",$footer);
			}
			else
			{
				$smarty->assign("CHECKSUM",$checksum);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("pr_view",$pr_view);//added by sriram
				$smarty->assign("pr_view",1);//forcefully assigning variable
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			}
		}
	
	        //If posted data, then converting into GET
	        if(count($_GET)<=0 && count($_POST)>0)
                $_GET=$_POST;	
		//if the sender is authenticated i.e. logged in
		//if($sender_details && (!$_COOKIE["JS_AUTOLOGIN"] || $_COOKIE["JSLOGIN"]))
		//if(($sender_details && !$_COOKIE["JS_AUTOLOGIN"]) || ($_COOKIE["JSLOGIN"]))
		if($sender_details)
		{
			
	/***********************************************************************************************************************
			Added By	:	Shakti Srivastava
			Reason		:	For similar profile search
	***********************************************************************************************************************/
			$PAGELEN=1;

			////mysql_close($db);
			//$db=connect_slave();
			if($my_profilechecksum)
				$profilechecksum=$my_profilechecksum;
			if($profilechecksum && !$multiple)
			{
				$arrsh=explode("i",$profilechecksum);
				if(md5($arrsh[1])==$arrsh[0])
				{
					if ($crmback!= "admin")
					{
						if($status!="D" && $status!="C" && $D_NUDGE==""  && $status!='M' && $Submit!="Send Reminder")
						{
						    if($countlogic)
						    {
							$scriptname="single_contact_aj.php";
                                                        if($sender_details['PROFILEID'] && !in_array($stype,array('CN','CO','L','V','CN2','')))
                                                                updateSimProfileLog($sender_details['PROFILEID']);
							//if(!$filter_profile)
							//	revamp_get_other_relevant_pro($sender_details['PROFILEID'],$profilechecksum,"single_contact_aj",$scriptname);
						     }
			
						     else
						     {
							if($after_contact)
							{
								//if(!$filter_profile)
								//	get_other_relevant_pro($sender_details['PROFILEID'],$profilechecksum,"single_contact_aj",$scriptname,1);
							}
							else
							{
								//if(!$filter_profile)
								//	get_other_relevant_pro($sender_details['PROFILEID'],$profilechecksum,"single_contact_aj",$scriptname);
							}
						     }
						}
						else 
						{
							$contactedby=$sender_details['PROFILEID'];
                                        		//Sharding On Contacts done by Lavesh Rawat
							$contactResult=getResultSet("RECEIVER",$contactedby);
							if(is_array($contactResult))
							{
								foreach($contactResult as $key=>$value)
								{
									$receivers[]=$contactResult[$key]["RECEIVER"];
								}
							}

							$contactResult=getResultSet("SENDER","","",$contactedby,'',"'A'");
							if(is_array($contactResult))
							{
								foreach($contactResult as $key=>$value)
								{
									$receivers[]=$contactResult[$key]["SENDER"];
								}
							}
							//Sharding On Contacts done by Lavesh Rawat
										

							}
						}
					}
					unset($arrsh);
				}
			
				//mysql_close($db);
				$db=connect_db();
		/***********************************************************************************************************************/

				if($_COOKIE["JS_REMEMBER"])
				{
					$sql="INSERT IGNORE INTO newjs.AUTOLOGIN_CONTACTS (PROFILEID,ENTRY_DT) VALUES('".$sender_details["PROFILEID"]."',CURDATE())";
					mysql_query_decide($sql);
				}

				$sender_profileid=$sender_details["PROFILEID"];		
				$allowed_limit=100;
				$allowed_limit_for_month=500;
				
				/* Below 2 values are set in authenticate function*/
				if($data['DAY_LIMIT']>=0)
					$allowed_limit=$data['DAY_LIMIT'];
				if($data['MONTH_LIMIT']>=0)
					$allowed_limit_for_month=$data['MONTH_LIMIT'];
				
				//check whether the user has contacted 500 times already in a month
				if(true)
				{
					
					if(true)
					{
					if(!$multiple)
					{
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
						$receiver_details=get_profile_details($receiver_profileid);
						//navigation("CVS","searchid__-1@j__1@contact__$receiver_profileid@SIM_USERNAME__$receiver_details[NAME]@stype__$stypes@NAVIGATOR__$_GET[NAVIGATOR]@",$receiver_details["NAME"]);
						//navigation("CVS","",$receiver_details["NAME"]);
						//Get the contact status between the sender and the receiver
						$contact_status=get_contact_status($sender_profileid,$receiver_profileid);
						$profilename=get_name($receiver_profileid);
						$smarty->assign("RECEIVER_USERNAME",$profilename);
					}
					else
					{
						if($status!="I")
						{
							$contact_status="RI";
							$nudge_type="N";
						}
					}
					$paid=isPaid($data["SUBSCRIPTION"]);
					$isReceiverEvalue=isEvalueMember($receiver_details["SUBSCRIPTION"]);
					$isReceiverPaid=isErishtaMember($receiver_details["SUBSCRIPTION"]);
					if($status=="M" && !$isReceiverPaid && !$paid)
						$custmessage="";
					elseif(!$paid && !$isReceiverEvalue && $status!='M')
					{
						$customMessageNotAllowed=1;
						$custmessage="";
					}
					$smarty->assign("PAID",$paid);
					if($status=="I" || $status=="D"  || $status=="A")
					{
						$sql_sender_relation="SELECT RELATION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender_profileid'";
						$res_sender_relation=mysql_query_decide($sql_sender_relation) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql_sender_relation,"ShowErrTemplate");
						$row_sender_relation=mysql_fetch_assoc($res_sender_relation);
						$relation=$row_sender_relation["RELATION"];
						if($status=="I")
							$show_what="EOI";
						if($status=="D" || $status=="A")
							$show_what="DECLINE";
						$username=$data["USERNAME"];
						if($data['GENDER']=='M')
						{
							$who_status="her";
							if($relation==2)
								$rel_with_me="son's";
							if($relation==3)
								$rel_with_me="brother's";
							if($relation==4)
								$rel_with_me="friend's";
						}
						else
						{
							$who_status="his";
							if($relation==2)
								$rel_with_me="daughter's";
							if($relation==3)
								$rel_with_me="sister's";
							if($relation==4)
								$rel_with_me="friend's";
						}
						include_once("preset_message.php");
						if($customMessageNotAllowed)
						{
							if($status=="I")
								$custmessage=$DRA_MES["PRE_1"];
							if($status=="A")
                                                                $custmessage=$DRA_MES["PRE_2"];
							if($status=="D")
                                                                $custmessage=$DRA_MES["D1"];
						}
						
					}
					//There has been some contact already so no need to check whether contact can be made
					if($contact_status || $status=='N')						   
					{
						switch($status)//status comes from the previous htm or tpl 
						{
							case "N":
								if($_GET['ACC_NUDGE'])
								{
									$request='Y';
									//$layer_message="Your expression of interest has been sent to $profilename.";
									if($isMobile)
										$layer_message="<p  style=\"float:left;\">Congratulations! You have expressed interest in <span class='b'>".$profilename."</span></p>";
									else
										$layer_message="<img src='images/grn_tck_v1.gif' align='absmiddle' />&nbsp;<span class='b grn' style='display:inline;'>Congratulations!</span> You have expressed interest in <span class='b'>".$profilename."</span>";
								}
								elseif($_GET['DEC_NUDGE'])
								{
									$request='N';
									$smarty->assign("N_DECLINE",1);
									$layer_message="You have declined <B>$profilename</B><BR>";
								}
								$NUDGE_STATUS=get_nudge_status($sender_profileid,$receiver_profileid);
								if($NUDGE_STATUS=='NACC')
									$layer_message="Your reminder has been sent to $profilename";
								if($NUDGE_STATUS==$nudge_type)
									set_nudge($sender_profileid,$receiver_profileid,$nudge_type,$request);
								  send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
								if($invoke_layer)
								{
									if($NUDGE_STATUS!='NACC' && $NUDGE_STATUS!='ACC')
										$smarty->assign("RELOAD",1);
									$smarty->assign("NUDGE",1);
								}
								$smarty->assign("LAYER_MESSAGE",$layer_message);
								$smarty->assign("CONTACTED_PROFILES",$profilename);
								if($isMobile)
									$smarty->display("mobilejs/jsmb_confirmation.html");
								else
									$smarty->display("congrats_contact.htm");
								if(!$invoke_layer)
								{
									if($data['GENDER']=='M')
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for expressing interest in $profilename. We will inform you as soon as she responds</div></div>');</script>";
									else
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for expressing interest in $profilename. We will inform you as soon as he responds</div></div>');</script>";
								}
								die;
							
							  
							  $msg=$n_mes;
							  $smarty->assign("MSG",$msg);
							  $smarty->assign("status",$status);
							  $smarty->assign("profilechecksum",$profilechecksum);
							  $smarty->display("contact_result.htm");
							break;	
						case "I":	//It is an initial contact
							//It means that in the contact log the contact status should only be initial contact
							if($contact_status=="I")
							{
								$filtered_contact = getFilteredContact($sender_profileid, $receiver_profileid,'','','sendContact');
								$flag_again=1;
								if(!$chat_form_mobile)
								{
									if(!$redirect && !$dontshow_msg)
									{
										make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,"",$stype,'','',$filtered_contact,$receiver_details["SUBSCRIPTION"]);
										if(!$isMobile)
											$redirect_to_view_similar=1;
									}
								}
								
								
								$message=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,$status,$DRA_MES,'',$filtered_contact);
								
								$layer_message="Your reminder has been sent to $profilename";
								$smarty->assign("LAYER_MESSAGE",$layer_message);
								$smarty->assign("CONTACTED_PROFILES",$profilename);
								if($invoke_layer)
									$smarty->assign("REMINDER",1);
								$smarty->assign("SAVE_MESSAGE",0);
								
								//Redirecting to view similar page, if succesfull contact
								if($redirect_to_view_similar)
									die("<script>red_view_similar('REDIRECT:$receiver_profileid:sendrem:I');</script>");
								if($isMobile)
									$smarty->display("mobilejs/jsmb_confirmation.html");
								else
									$smarty->display("congrats_contact.htm");
								if ($crmback == "admin")
								{
									$exec_name=getname($cid);
									$comments= "Repeat Contact initiated by ".$sender_details["USERNAME"]." with ".$profilename;
									//$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','C')";
									$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','CI')";

									mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
								}
								if(!$invoke_layer)
								{
									if($data['GENDER']=='M')
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for expressing interest in $profilename. We will inform you as soon as she responds</div></div>');</script>";
									else
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for sending a reminder to $profilename. We will inform you as soon as he responds</div></div>');</script>";
								}
								else
								{
									echo "<script>change_div('R',$index);</script>";
								}
								die;
							 }		
							 else 
							 {
									if($invoke_layer)
                                                                        {
                                                                                $smarty->assign("ERROR_MESSAGE","This operation is not allowed");
																				if($isMobile)
																					$smarty->display("mobilejs/jsmb_confirmation.html");
																				else
																					$smarty->display("congrats_contact.htm");
                                                                        }
                                                                        else
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">This operation is not allowed</div></div>',1);$.colorbox.close();</script>";
									die;
							 }
							break;
									
						case "A":	//The sender accepts the receiver's initial contact
							if($data["SOURCE"]=="ofl_prof")
							{
								$error_msg=disable_offline_contacts($data["PROFILEID"]);
								$smarty->assign("ERROR_MSG",$error_msg);
								$smarty->assign("status",$status);
								$smarty->assign("profilechecksum",$profilechecksum);
								$smarty->assign("msg",$error_msg);
								$smarty->assign("offline","1");
								$smarty->display("contact_result.htm");
								exit;	
							}
							if($contact_status=="RI" || $contact_status=="RD" || $contact_status=="C")//to check if the sender has received an initial contact
							{
								$flag_response="A";//accept
								if($multiple)
								{
									$cnt=0;
									$profilechecksumArray=explode(",",$profilechecksum);
									if(is_array($profilechecksumArray))
									{
										foreach($profilechecksumArray as $key=>$value)
										{
											$profileArray=explode("i",$value);
											if(md5($profileArray[1])==$profileArray[0])
											{
												$cnt++;
												$receiver_profileid=$profileArray[1];												
												$receiver_details=get_profile_details($receiver_profileid);
												if($receiver_details["SOURCE"]=="ofl_prof")
												{
													$request='Y';
													$offlineprofilename.=get_name($receiver_profileid).",";
													
													$NUDGE_STATUS=get_nudge_status($sender_profileid,$receiver_profileid);
													if($NUDGE_STATUS==$nudge_type)
														set_nudge($sender_profileid,$receiver_profileid,$nudge_type,$request);
													send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
												}
												else
												{
													send_response($sender_profileid,$profileArray[1],$flag_response,$custmessage,$savedraft,$markcc,$resend,$draft_name,$drafts);
													$profilename.=get_name($profileArray[1])."'s,";
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
													$parameters = array("KEY"=>"AI_ACCEPT","PROFILEID"=>$receiver_profileid,"OTHER_PROFILEID"=>$sender_profileid,"DATA"=>$sender_profileid);
													sendSingleInstantSms($parameters);
												}
											}
										}
										if($cnt!=0)
										{
											if($cnt==1)
											{
												if($invoke_layer)
												{
													$show_contacts = false;
													if($data["SUBSCRIPTION"])
													{
														if(in_array("F",explode(",",$data["SUBSCRIPTION"])))
															$show_contacts = true;	
													}
													echo "<script>check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'view_contact','$show_contacts',true);$.colorbox.close();change_div('A',$index);</script>";die;
												}
												else
												{
													$smarty->assign("CONTACTED_PROFILES",$profilename);
													$smarty->assign("LAYER_MESSAGE","You have accepted $profilename's expression of interest");
												}
											}
											if($invoke_layer)
												echo "<script>change_div('MA','$index');</script>";
											$profilename=trim($profilename,",");
											$offlineprofilename=trim($offlineprofilename,",");
											if($profilename)
												$layer_message="<div class='fl'><img align=\"absmiddle\" src=\"images/grn_tck_v1.gif\"/> &nbsp;</div><div style=\"width: 648px;\" class=\"fl b\">Congratulations! You have accepted $profilename expression of interest</div>";
											else
												$smarty->assign("CUST_MESSAGE","");
											$smarty->assign("multipleAccept",true);
											if($offlineprofilename)
												$layer_message.="Your expression of interest has been sent to $offlineprofilename";
											$smarty->assign("CONTACTED_PROFILES",1);
											$smarty->assign("LAYER_MESSAGE",$layer_message);

										}
									}
								}
								else
								{
									send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc,$resend,$draft_name,$drafts);
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
									$parameters = array("KEY"=>"AI_ACCEPT","PROFILEID"=>$receiver_profileid,"OTHER_PROFILEID"=>$sender_profileid,"DATA"=>$sender_profileid);
									sendSingleInstantSms($parameters);
									if($invoke_layer&&!$isMobile)
									{
										$show_contacts = false;
										if($data["SUBSCRIPTION"])
										{
											if(in_array("F",explode(",",$data["SUBSCRIPTION"])))
												$show_contacts = true;	
										}
										echo "<script>check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'view_contact','$show_contacts',true);$.colorbox.close();change_div('A',$index);</script>";die;
									}
									else
									{
										//die("<script>red_view_similar('REDIRECT:$receiver_profileid:nikhil:I');</script>");
										$smarty->assign("CONTACTED_PROFILES",$profilename);
										$smarty->assign("LAYER_MESSAGE","You have accepted $profilename's expression of interest");
									}
								}
								if(!isPaid($data["SUBSCRIPTION"]))
								{
									$BUY_MESSAGE_EOI="To view contact details of members <a class=\"paid_mem f_16 b\" href=\"mem_comparison.php\">Become a Paid Member Now</a>";
									$smarty->assign("CUST_MESSAGE","");

									//Showing message written by free user
									if(!$invoke_layer)
										$smarty->assign("FREE_MESSAGE",stripslashes($custmessage));
									$smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
								}
								if(!$invoke_layer)
									$smarty->assign("ACC_MES_CH",1);
								if($isMobile)
									$smarty->display("mobilejs/jsmb_confirmation.html");
								else
									$smarty->display("congrats_contact.htm");
								if ($crmback == "admin")
								{
									$exec_name=getname($cid);
									$comments= "Contact accepted by ".$sender_details["USERNAME"]." which was made by ".$profilename;
									//$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','C')";
									$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','CA')";
									mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
								}
								if(!$invoke_layer)
								{
									echo "<script>hide_loader_in_exp('reload');</script>";
								}
								die;
							}
							else 
							{
								if($invoke_layer)
								{
									$smarty->assign("ERROR_MESSAGE","This operation is not allowed");
									if($isMobile)
										$smarty->display("mobilejs/jsmb_confirmation.html");
									else
										$smarty->display("congrats_contact.htm");
								}
								else
									echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">This operation is not allowed</div></div>',1);$.colorbox.close();</script>";
								die;
							}		
							break;								
						case "D":	//The sender declines the receiver's initial contact
                            if($data["SOURCE"]=="ofl_prof")
							{
								$error_msg=disable_offline_contacts($data["PROFILEID"]);
								$smarty->assign("ERROR_MSG",$error_msg);
								$smarty->assign("status",$status);
								$smarty->assign("profilechecksum",$profilechecksum);
								$smarty->assign("msg",$error_msg);
								$smarty->assign("offline","1");
								$smarty->display("contact_result.htm");
								exit;
							}
							if($contact_status=="RI")//to check if the sender has received an initial contact	
							{
								$flag_response="D";//Decline
								if($multiple)
                                {
									$cnt=0;
									$profilechecksumArray=explode(",",$profilechecksum);
									if(is_array($profilechecksumArray))
									{
											foreach($profilechecksumArray as $key=>$value)
											{
													$profileArray=explode("i",$value);
													if(md5($profileArray[1])==$profileArray[0])
													{
															$cnt++;
															$receiver_profileid=$profileArray[1];
															$receiver_details=get_profile_details($receiver_profileid);
															if($receiver_details["SOURCE"]=="ofl_prof")
															{
																	$request='N';
																	$offlineprofilename.=get_name($receiver_profileid).",";

																	$NUDGE_STATUS=get_nudge_status($sender_profileid,$receiver_profileid);
																	if($NUDGE_STATUS==$nudge_type)                                                                                                                set_nudge($sender_profileid,$receiver_profileid,$nudge_type,$request);
																	send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
															}
															else
															{
																	send_response($sender_profileid,$profileArray[1],$flag_response,$custmessage,$savedraft,$markcc,$resend,$draft_name,$drafts);
																	$profilename.=get_name($profileArray[1])."'s,";
															}
													}
											}
											if($cnt!=0)
											{
													$profilename=trim($profilename,",");
													$offlineprofilename=trim($offlineprofilename,",");
													if($profilename)
															$layer_message="You have declined $profilename expression of interest.";
													else
															$smarty->assign("CUST_MESSAGE","");
													if($offlineprofilename)
															$layer_message.="You have declined $offlineprofilename";
													$smarty->assign("CONTACTED_PROFILES",1);
													$smarty->assign("LAYER_MESSAGE",$layer_message);
													if($invoke_layer)
														echo "<script>change_div('MD','$index');</script>";

											}
										}
                                }
								else
								{
									send_response($sender_profileid,$receiver_profileid,$flag_response,$custmessage,$savedraft,$markcc,$resend,$draft_name,$DRA_MES);
									$smarty->assign("CONTACTED_PROFILES",$profilename);
									$smarty->assign("LAYER_MESSAGE","You have declined $profilename's expression of interest");
									if($invoke_layer)
									{
										if($layer_action!="accept_contact_message" && $layer_action!="message")
										{
											//$smarty->assign("RELOAD",1);
											echo "<script>change_div('D',$index);</script>";
										}
									}
								}
								if(filter_page_redirection_decline($sender_profileid)=="Y")
									$smarty->assign("filter_redirect",1);
								$smarty->assign("DECLINE",1);
								declineRedirection($isMobile,$sender_profileid,$receiver_profileid,$invoke_layer);								
								die;
							}
							elseif($contact_status=="RA")
							{
								$flag_response="D";//Decline
								
								send_response($sender_profileid,$receiver_profileid,$flag_response,'',$savedraft,$markcc);
								$smarty->assign("CONTACTED_PROFILES",$profilename);
								$smarty->assign("LAYER_MESSAGE","You have declined $profilename's expression of interest");
								if($invoke_layer)
								{
									if($layer_action!="accept_contact_message" && $layer_action!="message")
										$smarty->assign("RELOAD",1);
								}
								$smarty->assign("DECLINE",1);
								declineRedirection($isMobile,$sender_profileid,$receiver_profileid,$invoke_layer);								
								die;
							}
							else 
							{
								if($invoke_layer)
								{
									$smarty->assign("ERROR_MESSAGE","This operation is not allowed");
									if($isMobile)
										$smarty->display("mobilejs/jsmb_confirmation.html");
									else
										$smarty->display("congrats_contact.htm");
								}
								else
									echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">This operation is not allowed</div></div>',1);$.colorbox.close();</script>";die;
							}	
							break;
								
						case "C": // the sender cancels the contact after the receiver accepted it
							if($data["SOURCE"]=="ofl_prof")
							{
								$error_msg=disable_offline_contacts($data["PROFILEID"]);
								$smarty->assign("ERROR_MSG",$error_msg);
								$smarty->assign("status",$status);
								$smarty->assign("profilechecksum",$profilechecksum);
								$smarty->assign("msg",$error_msg);
								$smarty->assign("offline","1");
								$smarty->display("contact_result.htm");
								exit;
							}
							if($contact_status=="A")
							{
								$flag_response="C";//Cancel
								
								send_response($sender_profileid,$receiver_profileid,$flag_response,'',$savedraft,$markcc);

								if ($crmback == "admin")
								{
									$exec_name=getname($cid);
									$comments= "Contact cancelled by ".$sender_details["USERNAME"]." which was initiated by ".$profilename;
									//$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','C')";
									$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','CC')";
									mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
								}
								$smarty->assign("CONTACTED_PROFILES",$profilename);
								$smarty->assign("LAYER_MESSAGE","You have successfully cancelled the contact made with Jeevansathi member <b>$profilename</b>");
								if($invoke_layer)
									$smarty->assign("RELOAD",1);
								$smarty->assign("CANCEL",1);
								if($isMobile)
									$smarty->display("mobilejs/jsmb_confirmation.html");
								else
									$smarty->display("congrats_contact.htm");
								if(!$invoke_layer)
								{
									echo "<script>hide_loader_in_exp('reload');</script>";
								}
								die;
							}
							else 
							{
								if($invoke_layer)
								{
									$smarty->assign("ERROR_MESSAGE","This operation is not allowed");
									if($isMobile)
										$smarty->display("mobilejs/jsmb_confirmation.html");
									else
										$smarty->display("congrats_contact.htm");
								}
								else
									echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">This operation is not allowed</div></div>',1);$.colorbox.close();</script>";die;
							}
							break;
								
						case "M":	//send message
							if($data["SOURCE"]=="ofl_prof")
							{
								$error_msg=disable_offline_contacts($data["PROFILEID"]);
								$smarty->assign("ERROR_MSG",$error_msg);
								$smarty->assign("status",$status);
								$smarty->assign("profilechecksum",$profilechecksum);
								$smarty->assign("msg",$error_msg);
								$smarty->assign("offline","1");
								$smarty->display("contact_result.htm");
								exit;
							}
							if($contact_status=="A" || $contact_status=="RA")
							{
								if($send=='send message')
								{
									if($custmessage)
									{
										send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
										if ($crmback == "admin")
										{
											$exec_name=getname($cid);
											$comments= "Message sent by ".$sender_details["USERNAME"]." to ".$profilename;
											//$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','C')";
											$sql_ins = "INSERT INTO infovision.INFOVISION_ADMIN (PROFILEID,EXEC_NAME,ENTRY_DT,COMMENTS,TYPE) VALUES ('$sender_details[PROFILEID]','$exec_name',NOW(),'$comments','CM')";
											mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
										}
										$msg="Your message has been sent successfully.";
										$smarty->assign("MSG",$msg);
									}
									//changed by Gaurav on 29 Sept for showing error if empty message if being tried to send
									else
									{
										header("Location: $SITE_URL/profile/viewprofile.php?checksum=$checksum&username=$profilename&CMDsubmit.x=0&CMDsubmit.y=0&CMDsubmit=Go&err=1");
									}
									$smarty->assign("status",$status);
									$smarty->assign("profilechecksum",$profilechecksum);
									echo "yes";
									$smarty->display("contact_result.htm");
								}
								else
								{
									send_message($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc);
									if($invoke_layer)
									{
										$smarty->assign("MSG_SENT",1);
										if($isMobile)
											$smarty->display("mobilejs/jsmb_confirmation.html");
										else{
											$smarty->display("congrats_contact.htm");
											echo "<script>change_div('M',$index);</script>";
										}
									}
									else
									{
										if($CONTACT_FROM_ALBUM)
											echo '<div class="pink" style="width:753px; padding-bottom:15px;"><div class="top_bg pd_3"> <a href="#" class="fr blink b" onClick="$.colorbox.close();return false;">Close [X]</a><div class="clr"></div></div><div class="gry_bdr fl t12 pd_5" style="background:#fff; margin:5px 20px; display:inline;width:700px"><div class="fl t14 b">Message Sent</div></div><div class="clr"></div></div>';
										else
											echo "<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Message sent!<br><a href=\"#\" onClick=\"hide_loader_in_exp('reload');return false;\">Send another message...</a></div></div>";
									}
									die;
								}
							}
							else 
							{
								if($invoke_layer)
								{
									$smarty->assign("ERROR_MESSAGE","This operation is not allowed");
										if($isMobile)
											$smarty->display("mobilejs/jsmb_confirmation.html");
										else
											$smarty->display("congrats_contact.htm");
								}
								else
									echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">This operation is not allowed</div></div>',1);$.colorbox.close();</script>";die;
							}
						}
					}
					//no contact has been made earlier so it is necessary to check whether contact can be made
					//it also means that only initial contact can be made
					else 
					{
						if($multiple)
						{
							$profilechecksumArray=explode(",",$profilechecksum);
							if(is_array($profilechecksumArray))
							{
								foreach($profilechecksumArray as $key=>$value)
								{
									$profileArray=explode("i",$value);
									if(md5($profileArray[1])==$profileArray[0])
									{
										$receiver_profileid[]=$profileArray[1];        
									}
								}
							}
						}
						$contactDetail = initiateContact($sender_profileid, $receiver_profileid, $sender_details, $receiver_details, $status,$checksum, $draft_name, $DRA_MES, $custmessage, $stype, $matchalert_mis_variable, $clicksource);
						$error_msg = $contactDetail["ERROR_MESSAGE"];
						if(!$error_msg)
						{
							$filtered_contact = $contactDetail["FILTERED_CONTACT"];
							$layer_message = $contactDetail["LAYER_MESSAGE"];
							$show_similar = $contactDetail["SHOW_SIMILAR"];
							$contacted_profiles = $contactDetail["CONTACTED_PROFILES"];
							if($show_similar && !$isMobile)
							{
								if(is_array($receiver_profileid))
									$receiver_profileid = $receiver_profileid[0];
								//Redirect page to view similar .
								die("<script>red_view_similar('REDIRECT:$receiver_profileid:nikhil:I');</script>");
							}
							else
							{
								$smarty->assign("LAYER_MESSAGE",$layer_message);
								$smarty->assign("CONTACTED_PROFILES",$contacted_profiles);
								if($isMobile)
								{
									$threshold_message=get_limit_message($data[PROFILEID],$data[SUBSCRIPTION]);
									$smarty->assign("THRESHOLD_MESSAGE",$threshold_message);
									$smarty->display("mobilejs/jsmb_confirmation.html");
								}
								else
									$smarty->display("congrats_contact.htm");
								if(!$invoke_layer)//For displaying message in profile page
								{
									if($data['GENDER']=='M')
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for expressing interest in $profilename. We will inform you as soon as she responds</div></div>');</script>";
									else
										echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">Thank you for expressing interest in $profilename. We will inform you as soon as he responds</div></div>');</script>";
								}
								else//For contact center
								{
									if($multiple)
										$smarty->assign("RELOAD",1);
									else
										echo "<script>change_div('I',$index);</script>";
								}
							}
						}
						else 
						{
							if($invoke_layer)
							{
								$smarty->assign("ERROR_MESSAGE",$error_msg);
								if($isMobile)
									$smarty->display("mobilejs/jsmb_confirmation.html");
								else
									$smarty->display("congrats_contact.htm");
							}
							else
								echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">$error_msg</div></div>',1);$.colorbox.close();</script>";
							die;
						}	
					}
				}
				else 
				{
					$sql="insert into CONTACT_LIMIT(PROFILEID,DATE,REASON) values ('" . $data['PROFILEID'] . "',now(),'DAILY')";
                                        mysql_query_decide($sql);

					/*$error_msg="You have exceeded the limit of the No. of contacts that you can make in a day.<br>";
					$error_msg.="The limit is ".$allowed_limit;

					$show_popup_page = 1;*/
					if($invoke_layer)
					{
						$smarty->assign("ERROR_MESSAGE","You cannot contact this profile as you have reached your contact limit");
						$smarty->display("congrats_contact.htm");
					}
					else
						echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">You cannot contact this Profile as you have reached your contact limit</div></div>');$.colorbox.close();</script>";die;
				}		
			}
			else
			{
				$sql="insert into CONTACT_LIMIT(PROFILEID,DATE,REASON) values ('" . $data['PROFILEID'] . "',now(),'MONTHLY')";
                                mysql_query_decide($sql);

				/*$error_msg="You have exceeded the limit of the No. of contacts that you can make in a month.<br>";
				$error_msg.="The limit is ".$allowed_limit_for_month;

				$show_popup_page = 1;*/
				if($invoke_layer)
				{
	 	                       $smarty->assign("ERROR_MESSAGE","You cannot contact this profile as you have reached your contact limit");
                                       $smarty->display("congrats_contact.htm");
                                }
				else
					echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\">You cannot contact this Profile as you have reached your contact limit</div></div>');$.colorbox.close();</script>";
				die;
			}
			//added by lavesh
			if($show_popup_page)
			{
				if($popup_page)
				{
					if(strstr($scriptname,'/P/'))
					{
						$scriptname1=explode('/P/',$scriptname);
						$scriptname=$scriptname1[1];
					}
					if($scriptname=='contacts_made_received.php')
					{                                       
						header("Location: ".$SITE_URL."/P/$scriptname?checksum=$checksum&redirect=1&nmessage=$error_msg&action=IG&j=$pageno&searchorder=$searchorder&flag=$flag&type=$type&sortorder=$sortorder");
						exit;
					}
												     
					if($scriptname=='simcontacts_search.php')
							$from_index=1;
												     
					header("Location: ".$SITE_URL."/P/$scriptname?checksum=$checksum&redirect=1&nmessage=$error_msg&action=IG&j=$pageno&contact=$contact&searchorder=$searchorder&searchchecksum=$searchchecksum&label_select_no=$label_select_no&from_index=$from_index");
					exit;
				}
				//ends here
				 if($profilechecksum!="")
                                 {
					$arr=explode("i",$profilechecksum);
					$receiver_profileid=$arr[1];
					//Message to show when contact limit expired and similar contact are shown
					$receiver_details=get_profile_details($receiver_profileid);
					$sim_message="Check out these profiles similar to $receiver_details[NAME]";
					$smarty->assign("SIM_MESSAGE",$sim_message);
				}
				$smarty->assign("ERROR_MSG",$error_msg);
				$smarty->assign("status",$status);
				$smarty->assign("profilechecksum",$profilechecksum);
				
				$smarty->display("contact_result.htm");
			}
		}			
		else
		{
			if($CMDSubmit)
			{
				if($source)
				{
					include_once("hits.php");
					savehit($source,$_SERVER['PHP_SELF']);
				}
				$smarty->assign("TIEUP_SOURCE",$source);
			}
			$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
			
			/** Code for wap site" ***/

			if($isMobile){
				$header=$smarty->fetch("mobilejs/jsmb_header.html");
				$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
				$smarty->assign("HEADER",$header);
				$smarty->assign("FOOTER",$footer);
				$smarty->display("mobilejs/jsmb_login.html");
			}
			else{
				include_once("include_file_for_login_layer.php");
				$smarty->display("login_layer.htm");
				die;
			}
		}
	}
	// flush the buffer
	if($zipIt)
		ob_end_flush();

function user_get_name1($profileid)
{
                $sql="SELECT USERNAME from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if(mysql_num_rows($result)>0)
                {
                        $myrow=mysql_fetch_array($result);
                        return $myrow["USERNAME"];
                }
                else
                        return "";
}

function retrive_contact_details($id,$name)
{
	$addr = "<b>Contact details of ".$name."</b>\n";
	$sql_addr = "SELECT EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB, SHOWMESSENGER, CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS, MESSENGER_CHANNEL, MESSENGER_ID, SUBSCRIPTION, PARENTS_CONTACT, SHOW_PARENTS_CONTACT FROM newjs.JPROFILE where  activatedKey=1 and PROFILEID='$id'";
	$res_addr = mysql_query_decide($sql_addr) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_addr,"ShowErrTemplate");
	$row_addr = mysql_fetch_array($res_addr);

	if(!strstr($row_addr['SUBSCRIPTION'],"S"))
        {
		if($row_addr["SHOWMESSENGER"]=='Y')
		{
			include_once('arrays.php');
			$mymessenger = $row_addr['MESSENGER_CHANNEL'];
			$addr .= $MESSENGER_CHANNEL["$mymessenger"]." Messenger Id: ".$row_addr['MESSENGER_ID']."\n";
		}
	}
	$addr.="Email : ".$row_addr["EMAIL"]."\n";
	if(!strstr($row_addr['SUBSCRIPTION'],"S"))
        {
		if($row_addr["SHOWPHONE_RES"]=="Y" && $row_addr["PHONE_RES"]!="")
			$phone=$row_addr["PHONE_RES"];
		if($row_addr["SHOWPHONE_MOB"]=="Y" && $row_addr["PHONE_MOB"]!="")
		{
			if(trim($phone)=="")
				$phone=$row_addr["PHONE_MOB"];
			else
				$phone.=", " . $row_addr["PHONE_MOB"];
		}
		if($phone)
		$addr .= "Phone : ".$phone."\n";
		if($row_addr["CONTACT"]!="" && $row_addr["SHOWADDRESS"]=="Y")                                                                        $addr .= "Address : ".nl2br($row_addr["CONTACT"]);
		if($row_addr["PARENTS_CONTACT"]!="" && $row_addr["SHOW_PARENTS_CONTACT"]=="Y")                                                                        $addr .= "\nParents Address : ".nl2br($row_addr["PARENTS_CONTACT"]);
	}
	return nl2br($addr);
}

function reload_on_layer1($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage)
{
	global $smarty;
	if($redirect)
	{
		$smarty->assign('layer',1);
		if($action=='C')
		{
			if($nmessage_profileid)
			{
				$nmessage1=explode(',',$nmessage_profileid);
				for($i=0;$i<count($nmessage1);$i++)
				{
					$profilechecksum=md5($nmessage1[$i])."i".$nmessage1[$i];
					$receivers_with_history=user_get_name1($nmessage1[$i]);
					$nmessage.="<a  href=\"#\" onClick=\"MM_openProfileWindow('/profile/viewprofile.php?profilechecksum=$profilechecksum','','')\">$receivers_with_history</a></strong>".' , ';
					$ncount++;
				}
				$nmessage=rtrim($nmessage,' , ');
				$smarty->assign("unfavourable_response",'Y');
				if($ncount==1)
					$smarty->assign('error_msg',"$error_msg");
														     
				$smarty->assign("nmessage","Can not initiate contact with $nmessage");
			}
			if(trim($ymessage))
			{
				$smarty->assign("favourable_response",'Y');
				$smarty->assign("ymessage","You have successfully contacted $ymessage");
			}
		}
		elseif($action=='IG')
		{
			if(trim($ymessage))
			{
				$smarty->assign("favourable_response",'Y');
				if(strstr($ymessage,'ignored'))
				       $smarty->assign("ymessage","$ymessage<br>Ignored profiles will be removed from your search after 24 hours");
				else
					$smarty->assign("ymessage","$ymessage");
			}
			if(trim($nmessage))
			{
				$smarty->assign("unfavourable_response",'Y');
				$smarty->assign("nmessage","$nmessage");
			}
		}
		else
		{
			$smarty->assign("favourable_response",'Y');
			$smarty->assign("ymessage","$ymessage");
		}
	}
}
function showProfileErrorlocal($gender="",$hidden="")
{
                global $checksum,$smarty;
		if($gender=="Y")
			$smarty->assign("MESSAGE","Contact cannot be initiated with the same gender");
		else
		{
                                                                                         
                if($hidden=="N" || $hidden=="U" || $hidden=="P")
                        $smarty->assign("MESSAGE","This profile is currently being Screened. Kindly view this profile after 24 hours");
                elseif($hidden=="H")
                        $smarty->assign("MESSAGE","This profile is currently hidden. Please check after a couple of weeks");
                elseif($hidden=="D")
                        $smarty->assign("MESSAGE","This profile has been deleted");
                                                                                                                             
                /*elseif($muslim_check=="M")
                        $smarty->assign("MESSAGE","Sorry, you cannot view a non muslim profile");
                elseif($muslim_check=="H")
                        $smarty->assign("MESSAGE","Sorry, you cannot view a muslim profile of foreign origin");*/
		}                                                                                                 
                if($mbureau=="bureau1")
                {
                        $smarty->assign("pid",$pid);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("MBHEAD",$smarty->fetch("top_band.htm"));
 			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                }
                else
                {
                       	$smarty->assign("FROM","singleaction"); 
                        $smarty->assign("CHECKSUM",$checksum);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                }
                $smarty->display("1_profile_notfound.htm");
                exit;
}
//Decline on Profile Page - Redirection to Members awaiting my response/Match Alerts/My matches
function declineRedirection($isMobile,$sender_profileid,$receiver_profileid,$invoke_layer)
{
	global $smarty,$from_viewprofile;
	$b90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
    $back_90_days=date("Y-m-d",$b90);
	if($isMobile)
	{
		$smarty->display("mobilejs/jsmb_confirmation.html");
		return;
	}
	if(!$from_viewprofile)
	{
		if($isMobile)
			$smarty->display("mobilejs/jsmb_confirmation.html");
		else
			$smarty->display("congrats_contact.htm");
	}
	else
		$countAwaiting = getResultSet("count(*) as CNT","","$receiver_profileid","$sender_profileid","","'I'","","TIME > '$back_90_days 00:00:00'","","","","","","","","","","","'Y'");
	if(!$invoke_layer)
	{
		if($from_viewprofile=='Y')
		{
			if($countAwaiting[0]['CNT']>0)
				$toPage = "awaiting";
			else
			{
				$dbslave=connect_slave81();
				$sql_match_alerts = "SELECT COUNT(USER) as CNT from matchalerts.LOG where RECEIVER = '$sender_profileid'";
				$result=mysql_query_decide($sql_match_alerts,$dbslave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_match_alerts,"ShowErrTemplate");
				$myrow=mysql_fetch_array($result);
				if($myrow['CNT'] >0)
					$toPage = "alerts";	
				else
					$toPage = "matches";
			}
			echo "<script>redirectPage('$toPage');</script>";
		}
		else 
			echo "<script>hide_loader_in_exp('<div style=\"width:267px;\"><div class=\"dark_orange t14 b\"></div></div>');</script>";
	}
}
?>
