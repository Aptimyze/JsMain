<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	include("connect.inc");
	include_once("mobile_detect.php");
	$lang=$_COOKIE["JS_LANG"];

	$db=connect_db();
        $data=authenticated($checksum);
        login_relogin_auth($data);//added for contact details on leftpanel.

	/*************************************Portion of Code added for display of Banners*******************************/
	if($case1)
		$smarty->assign("case1",$case1);
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	include_once("sphinx_search_function.php");//to be tested later
	savesearch_onsubheader($data["PROFILEID"]);//to be tested later
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	if($isMobile){
	assignHamburgerSmartyVariables($data[PROFILEID]);
	}
        /************************************************End of Portion of Code*****************************************/
        $db=connect_db();
        $smarty->assign("CHECKSUM",$checksum);
	
	if($data)	
	{
		$USERNAME_FIELD=$data['USERNAME'];
		if($email=="")
		{
			$sql_email="select EMAIL from JPROFILE where  activatedKey=1 and PROFILEID='".$data['PROFILEID']."'";
			$res_email=mysql_query_decide($sql_email);
			$row_email=mysql_fetch_row($res_email);
			$email=$row_email[0];
		}
		if($name=="")
		{
			$sql_name="select NAME from incentive.NAME_OF_USER where PROFILEID='".$data['PROFILEID']."'";
			$res_name=mysql_query_decide($sql_name);
			$row_name=mysql_fetch_row($res_name);
			$name=$row_name[0];
			$smarty->assign("name",$name);	
		}
		
	}
	$smarty->assign("USERNAME_FIELD",$USERNAME_FIELD);
	$smarty->assign("email",$email);
	

	//Added by lavesh
	$category=array("Profile Deletion","Contact initiation","Edit Basic information","Login to jeevansathi.com","Retrieve username/password","Search for perfect match","Photo Upload","Membership/Payment Related Queries","Report Abuse","Suggestions","Others","Mobile site");
	$category_value=array("delete","initiate","edit","login","retrieve","search","Photo","Payment","Abuse","Suggestion","Other","wapsite");
	$smarty->assign("category",$category);
	$smarty->assign("category_value",$category_value);
	//Ends Here.
	
	$com_category=array("0"=>"Retrieve username/password","15"=>"Profile Deletion","19"=>"Contact initiation","18"=>"Login to jeevansathi.com","60"=>"Search for perfect match","92"=>"Edit Basic information");
	$com_faq=array("0"=>"retrieve","15"=>"delete","19"=>"initiate","18"=>"login","60"=>"search","92"=>"edit");
	if($com_faq[$_GET['id']]!='' && $CMDSubmit=="")
	{
		$today=date("Y-m-d");
		$category=$com_category[$_GET['id']];
		if($category=="")
			$category=$com_category[$allcategory];
		$sql="insert into MIS.`FEEDBACK_RESULT` (`CATEGORY`,`DATE`) values('$category',now())";
		mysql_query_decide($sql);
		$insert_id=mysql_insert_id_js();
		$smarty->assign("abuse",$abuse);
	//	$smarty->assign("SHOW_HELP","inline");
	//	$smarty->assign("SHOW_FORM","none");
		$smarty->assign("FEEDBACK_ID",$insert_id);
		$smarty->assign("question",$com_faq[$_GET['id']]);	
		$smarty->assign("questiontext",$com_category[$_GET['id']]);
		$smarty->assign("tracepath",$tracepath);
		$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
		$smarty->assign("allcategory",$allcategory);
	//	$smarty->assign("javascript","onclick=\"javascript:{document.getElementById('SATISFIED').style.display='none';document.getElementById('DISPLAYFORM').style.display='inline';document.form1.name.focus();}\"");
	//	$smarty->assign("NOT_SATISFY",$smarty->fetch("feedback/cont_per.htm"));
		$smarty->display("feedback/feed_".$com_faq[$_GET['id']].".htm");
		exit;
	}
		
	if($CMDSubmit || $FEEDBACK_ID!='')
	{
		if(!$tracepath)
			$tracepath="0";
		$error=0;
		if(trim($email)=='' || checkemail($email,'N')==1)
		{
			$error++;
		//	$smarty->assign("ER_EMAIL","Y");
		}
		if(trim($message)=='')
		{
				$error++;
		//	$smarty->assign("ER_MESSAGE","Y");
		}
		if($usecategory)
		{
			if(trim($allcategory)=='')
			{
				$error++;
		//		$smarty->assign("ER_CAT","Y");
			}
		}
	
		if($error)
		{
			$smarty->assign("name",$name);
			$smarty->assign("FEEDBACK_ID",$FEEDBACK_ID);
			$smarty->assign("question",$question);
			$smarty->assign("username",$username);
			$smarty->assign("email",$email);
			$smarty->assign("address",$address);
			$smarty->assign("subject",$subject);
			$smarty->assign("message",$message);
			$smarty->assign("abuse",$abuse);
			$smarty->assign("tracepath",$tracepath);
			$smarty->assign("allcategory",$allcategory);
			$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
			$smarty->assign("select_category",$allcategory);
			$smarty->assign("ERROR",1);

			if($FEEDBACK_ID)
				$smarty->display("feedback/feed_".$question.".htm");
		//	elseif($NO_NAVIGATION)
			else{
				if($isMobile)
					$smarty->display("mobilejs/feedback_mobile.html");
				else
					$smarty->display("profile_edit_basicinfo_spec.htm");
			}
			exit;
//			$smarty->display("faqs_feedback.htm");
			//else
			//	$smarty->display("faqs_form.htm");
		}
		else
		{
			$today=date("Y-m-d");
			if(in_array($allcategory,$com_faq) && $tracepath=='0' && $FEEDBACK_ID=="")
			{
				$name=htmlspecialchars($name,ENT_QUOTES);
				$username=htmlspecialchars($username,ENT_QUOTES);
				$email=htmlspecialchars($email,ENT_QUOTES);
				$address=htmlspecialchars($address,ENT_QUOTES);
				$message=htmlspecialchars($message,ENT_QUOTES);
				$category=$com_category[array_search($allcategory,$com_faq)];
				$sqldup="SELECT ID FROM feedback.TICKETS WHERE (USERNAME='$username' OR EMAIL='$email') AND ENTRY_DT LIKE '$today%'";
				$resdup=mysql_query_decide($sqldup) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqldup,"ShowErrTemplate");
				if(mysql_num_rows($resdup))
				{
					while($rowdup=mysql_fetch_assoc($resdup))
					{
						$sqlquery="SELECT CATEGORY FROM MIS.FEEDBACK_RESULT WHERE TICKETID='$rowdup[ID]'";
						$resquery=mysql_query_decide($sqlquery) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlquery,"ShowErrTemplate");
						$rowquery=mysql_fetch_assoc($resquery);
						if($rowquery["CATEGORY"]==$category)
						{
							$sqlquery="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$rowdup[ID]'";
							$resquery=mysql_query_decide($sqlquery) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlquery,"ShowErrTemplate");
							$rowquery=mysql_fetch_assoc($resquery);
							if($rowquery["QUERY"]==addslashes(stripslashes($message)))
							{
								$no_insert=1;
								break;
							}
						}
					}
				}
				if(!$no_insert)
				{
					$sql="INSERT INTO feedback.TICKETS(NAME,USERNAME,EMAIL,ADDRESS,CATEGORY,STATUS,ENTRY_DT,FIRST_ENTRY_DT,COUNTER,ABUSE) VALUES('$name','$username','$email','$address','$tracepath','REPLIED',now(),now(),'1','$abuse')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
					$id=mysql_insert_id_js();

					$sql="INSERT INTO feedback.TICKET_MESSAGES(TICKETID,ENTRY_DT,QUERY,IP) VALUES('$id',now(),'".addslashes(stripslashes($message))."','$ip')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
					
					$sql="insert into MIS.`FEEDBACK_RESULT` (`CATEGORY`,`DATE`,`TICKETID`) values('$category',now(),$id)";
					mysql_query_decide($sql);
					$insert_id=mysql_insert_id_js();
				}
				$smarty->assign("name",$name);
				if(!$no_insert)
				$smarty->assign("FEEDBACK_ID",$insert_id);
				else
				$smarty->assign("FEEDBACK_ID","repeat");
				$smarty->assign("username",$username);
				$smarty->assign("email",$email);
				$smarty->assign("address",$address);
				$smarty->assign("subject",$subject);
				$smarty->assign("message",$message);
				$smarty->assign("abuse",$abuse);
				$smarty->assign("tracepath",$tracepath);
				$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
				$smarty->assign("select_category",$allcategory);

				$smarty->assign("abuse",$abuse);
				$smarty->assign("SHOW_HELP","inline");
				$smarty->assign("SHOW_FORM","none");
				$smarty->assign("question",$category);	
				$smarty->assign("tracepath",$tracepath);
				$smarty->assign("allcategory",$category);
			//	$smarty->assign("javascript","onclick=\"javascript:{document.form1.submit()}\"");
		//		$smarty->assign("NOT_SATISFY",$smarty->fetch("feedback/cont_per.htm"));
				$smarty->display("feedback/feed_".$allcategory.".htm");
				exit;

			}
			$ip=FetchClientIP();//Gets ipaddress of user
                        if(strstr($ip, ","))
                        {
                                $ip_new = explode(",",$ip);
                                $ip = $ip_new[1];
                        }

			
			$name=htmlspecialchars($name,ENT_QUOTES);
			$username=htmlspecialchars($username,ENT_QUOTES);
			$email=htmlspecialchars($email,ENT_QUOTES);
			$address=htmlspecialchars($address,ENT_QUOTES);
			$message=htmlspecialchars($message,ENT_QUOTES);
			


			$smarty->assign("thanks","1");
			$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
			
			$labelid=explode(".",$tracepath);
			
			$sql="select QUESTION from feedback.QADATA where ID='" . $labelid[1] . "'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
			
			$myrow=mysql_fetch_array($res);
			
			if($FEEDBACK_ID!='')
			{
				if($FEEDBACK_ID!="repeat")
				{
					$sql="select TICKETID from MIS.FEEDBACK_RESULT where ID=$FEEDBACK_ID";
					$res=mysql_query_decide($sql);
					$row=mysql_fetch_row($res);
					if($row[0]>0)
					{
						$sql="update feedback.TICKETS set STATUS='OPEN' where ID='".$row[0]."'";
						mysql_query_decide($sql);
						$sql="update MIS.FEEDBACK_RESULT set RESOLVED='N',TICKETID='".$row[0]."' where ID=$FEEDBACK_ID";
						mysql_query_decide($sql) or die(mysql_error_js());
						if(mysql_affected_rows_js()==0)
						$no_insert=1;
					}
					else
					{
						$sqlfeed="SELECT TICKETID FROM MIS.FEEDBACK_RESULT WHERE DATE LIKE '$today%' AND CATEGORY='$questiontext' AND TICKETID!=0";
						$resfeed=mysql_query_decide($sqlfeed) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlfeed,"ShowErrTemplate");
						if(mysql_num_rows($resfeed))
						{
							while($rowfeed=mysql_fetch_assoc($resfeed))
							{
								$sqldup="SELECT ID FROM feedback.TICKETS WHERE (USERNAME='$username' OR EMAIL='$email') AND ID='$rowfeed[TICKETID]'";
								$resdup=mysql_query_decide($sqldup) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes");
								if(mysql_num_rows($resdup))
								{
									$rowdup=mysql_fetch_assoc($resdup);
									$sqlquery="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$rowdup[ID]'";
									$resquery=mysql_query_decide($sqlquery) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlquery,"ShowErrTemplate");
									$rowquery=mysql_fetch_assoc($resquery);
									if($rowquery["QUERY"]==addslashes(stripslashes($message)))
									{
									$no_insert=1;
									break;

									}
								}
							}
						}
						if(!$no_insert)
						{
							$sql="INSERT INTO feedback.TICKETS(NAME,USERNAME,EMAIL,ADDRESS,CATEGORY,STATUS,ENTRY_DT,FIRST_ENTRY_DT,COUNTER,ABUSE) VALUES('$name','$username','$email','$address','$tracepath','OPEN',now(),now(),'1','$abuse')";
							mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
							$id=mysql_insert_id_js();

							$sql="INSERT INTO feedback.TICKET_MESSAGES(TICKETID,ENTRY_DT,QUERY,IP) VALUES('$id',now(),'".addslashes(stripslashes($message))."','$ip')";
							 mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");

							  $sql="update MIS.FEEDBACK_RESULT set RESOLVED='N',TICKETID=$id where ID=$FEEDBACK_ID";
							  mysql_query_decide($sql) or die(mysql_error_js());
						}
					
					}	
					$unresolved=" 'UNRESOLVED' ";
				}
				else
				{
					$no_insert=1;
				}
			}
			else
			{
				if($allcategory=="")
					$allcategory=$myrow['QUESTION'];		
				$today=date("Y-m-d");
				$sqldup="SELECT ID FROM feedback.TICKETS WHERE (USERNAME='$username' OR EMAIL='$email') AND CATEGORY='$tracepath' AND ENTRY_DT LIKE '$today%'";				
				$resdup=mysql_query_decide($sqldup) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqldup,"ShowErrTemplate");
				if(mysql_num_rows($resdup))		
				{
					while($rowdup=mysql_fetch_assoc($resdup))
					{
						$sqlquery="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID='$rowdup[ID]'";
						$resquery=mysql_query_decide($sqlquery) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$resquery,"ShowErrTemplate");
						$rowquery=mysql_fetch_assoc($resquery);
						if($rowquery["QUERY"]==addslashes(stripslashes($message)))
						{
							$no_insert=1;
							break;
						}
					}

				}
				if(!$no_insert)
				{	
					$sql="INSERT INTO feedback.TICKETS(NAME,USERNAME,EMAIL,ADDRESS,CATEGORY,STATUS,ENTRY_DT,FIRST_ENTRY_DT,COUNTER,ABUSE) VALUES('$name','$username','$email','$address','$tracepath','OPEN',now(),now(),'1','$abuse')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
					$id=mysql_insert_id_js();

					$sql="INSERT INTO feedback.TICKET_MESSAGES(TICKETID,ENTRY_DT,QUERY,IP) VALUES('$id',now(),'".addslashes(stripslashes($message))."','$ip')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
					
					$sql="insert into MIS.FEEDBACK_RESULT (CATEGORY,`DATE`,`TICKETID`) values('$allcategory',now(),$id)";
					mysql_query_decide($sql);
				}
			}

			$msg="Name: $name<BR>Username: $username<BR>Message: " .stripslashes($message);

			//added by lavesh
		
			if($abuse=='Y')
				$allcategory='abuse';
			if($allcategory)
				$subject="Jeevansathi Feedback [$allcategory] $unresolved";
			else
				$subject="Jeevansathi Feedback [".$myrow['QUESTION']."] $unresolved";
		//	if(!$no_insert)
			send_email("bug@jeevansathi.com",$msg,$subject,$email);
			//Ends Here
			if($isMobile){
				$smarty->assign("MESSAGE_SHOW","Thank you for your valuable feedback.<BR/> If required, we will get in touch with you.");
				$smarty->assign("pageTITLE","Feedback sent - Jeevansathi");
				$smarty->display("mobilejs/confirmation_template.html");
				die;
			}
			else
			die('Hi');
		/*
			if($NO_NAVIGATION)
				$smarty->display("faqs_confirmation.htm");
			else
				$smarty->display("faqs_thanks.htm");
		*/
		}
	}
	else
	{
		$smarty->assign("abuse",$abuse);
		$smarty->assign("tracepath",$tracepath);
		$smarty->assign("NO_NAVIGATION",$NO_NAVIGATION);
		$smarty->assign("setOption",$setOption);
		//if coming from registration page, then preselect retrieve profile option.
		if($retrieve_profile)
			$smarty->assign("retrieve_preselect","retrieve");
//		if($NO_NAVIGATION)
//			$smarty->display("faqs_feedback.htm");
		else
			if($isMobile)
				$smarty->display("mobilejs/feedback_mobile.html");
			else
				$smarty->display("profile_edit_basicinfo_spec.htm");
		exit ;
	//	else
	//		$smarty->display("faqs_form.htm");
	}

	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
