<?php
/*
*       Filename        :       login.php
*/

	require_once("connect.inc");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
    include_once("mobile_detect.php");
    if(MobileCommon::isDesktop())
	{
		header("Location:".$SITE_URL."/static/logoutPage");die;
	}
	$db=connect_db();
	//Added by Rahul Tara to determine number of contacts initiated since last login	
	include("sms_service.inc");
	
	//if only login window to show
	$data=authenticated();
	if($data)
		setcookie ("SULEKHACO", "", time()-3600,"/");
		
	if($SHOW_LOGIN_WINDOW)
	{
		if($data)
		{
			
			$str="<script>$.colorbox.close();var loc_str=document.location.href;loc_str=loc_str.replace('CALL_ME','CALL_ME2');loc_str=loc_str.replace('after_login_call','after_login_call2');document.location=loc_str;</script>";
			echo $str;
			die;
		}
        	
		//$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		$smarty->assign("EMAIL_LAYER",$_COOKIE['CHATUSERNAME']);
		$smarty->assign("AFTER_LOGIN_CALL",$after_login_call);
		$smarty->assign("mem_str",$mem_str);
		$szFormSubmitURL = $SSL_SITE_URL."/profile/login.php?ajaxValidation=1";
		if($_GET['fmPwdReset'])
		{
			$szFormSubmitURL.="&fmPwdReset=1";
		}
		$smarty->assign("szFormSubmitURL",$szFormSubmitURL);
		include_once("include_file_for_login_layer.php");
	        $smarty->display("login_layer.htm");
	        die;
	}

	if($occ=="MN")
	{
		$smarty->assign("REQUESTEDURL","/profile/viewprofile.php?ownview=1&EditWhatNew=EduOcc");
		$smarty->assign("METHOD","");
		$smarty->assign("CAME_FROM_TIMEDOUT","1");
	}

	//if close is clicked on profile-login layer
	if($ajaxValidation==2)
	{
		echo "L";
		die;
	}
	if($page=="successStory")
		$allowDeactive=1;
  if(!$data)
		$data=login($username,$password,$allowDeactive);
	
	if($data[PROFILEID])
		$loginTracking = LoginTracking::getInstance($data[PROFILEID]);


	
//	is_complete_now($data["PROFILEID"]);	
	//if user logins from profile-login layer
	if($ajaxValidation)
	{
		$prof_id=$data["PROFILEID"];
		//tracking #2550
		if($prof_id)
		{
			$loginTracking->loginTracking("loginLayer");
			$phone_flag =getPhoneStatus('',$prof_id);
			if($phone_flag=='I')
				$count ='1';
			/*
			$sql_1="SELECT COUNT(*) AS CNT FROM incentive.INVALID_PHONE WHERE PROFILEID='$prof_id' AND 1=2";
			$res_1=mysql_query_decide($sql_1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_1,"ShowErrTemplate");
			$row_1=mysql_fetch_array($res_1);
			$count=$row_1['CNT'];
			*/
		}

		$ajax_result = "N";
		if($prof_id && $count)
		{
		    $ajax_result = "YI";
		}
		elseif($prof_id)
		{
			$ajax_result = "Y";
		}

		if($prof_id && $_POST["page"]=="searchpage")
		{
			$sql="INSERT INTO MIS.NEWSEARCH_LOGIN(DATE,PROFILEID) VALUES (NOW(),".$prof_id.")";
                  	mysql_query_decide($sql);
		}
		
		$szToUrl = $SITE_URL;
		if($_SERVER['HTTPS'] && strlen($_SERVER['HTTPS']) && $_GET['fmPwdReset'])
		{
			$szToUrl = $SSL_SITE_URL;
		}
		
		$js_function = " <script>	var message = \"\";
		if(window.addEventListener)	
			message ={\"body\":\"$ajax_result\"};
		else
			message = \"$ajax_result\";

		if (typeof parent.postMessage != \"undefined\") {
            parent.postMessage(message, \"$szToUrl\");
        } else {
            window.name = message; //FOR IE7/IE6
            window.location.href = '$szToUrl';
        }
		</script> ";
		
		echo $js_function;
		die;

	}

	$profileid = $data["PROFILEID"];
	$last_login_dt = $data["LAST_LOGIN_DT"];
	$mod_dt= $data["MOD_DT"];
	
	$post1=$_POST;
	$get1=$_GET;
	
	unset($post1["username"]);
	unset($post1["password"]);
	
	$get1=urlencode(serialize($get1)) . "\n";
	$post1=urlencode(serialize($post1));
	
	if(isset($data))
	{
		if($login_from=="H")
		{
			$sql="UPDATE MIS.LOGIN_HITS SET HOME=HOME+1 WHERE LOGIN_DT=CURDATE()";
			if(mysql_query_decide($sql))
			{
				if(mysql_affected_rows_js()==0)
				{
					$sql="INSERT INTO MIS.LOGIN_HITS (HOME,LOGIN_DT) VALUES ('1',CURDATE())";
					mysql_query_decide($sql);
				}
			}
		}

		$checksum=$data["CHECKSUM"];
		if($data["SITE_URL"])
		$SITE_URL=$data["SITE_URL"];

		// code for chat
		$pid = $data['PROFILEID'];
		//by nikhil to set a cookie for user logging in on the same terminal
		//added by pankaj for login tracking for probable duplication check 
    	if ($_COOKIE['TEMP_4_ISEARCH'] != $pid && $_COOKIE['TEMP_4_ISEARCH'] != '') {
        	setLoginTracker($_COOKIE['TEMP_4_ISEARCH'], $pid, "TEMP_4_ISEARCH");
    	}
    	if ($$_COOKIE['ISEARCH'] != $pid && $_COOKIE['TEMP_4_ISEARCH'] != $_COOKIE['ISEARCH'] && $_COOKIE['ISEARCH'] != '' && $_COOKIE['TEMP_4_ISEARCH'] != $pid) {
        	setLoginTracker($_COOKIE['ISEARCH'], $pid, "ISEARCH");
    	}
		//Added by Tanu to set ISEARCH cookie second time, when same user logs in
		if($_COOKIE['TEMP_4_ISEARCH']==$pid)
		{	
			setcookie("ISEARCH",$pid,time()+604800,"/",$domain);
		}
		else
		{
			setcookie("ISEARCH",$_COOKIE['ISEARCH'],time()+604800,"/",$domain);
			//ie was making isearch cookie null, if not set.
		}
		setcookie("TEMP_4_ISEARCH",$pid,time()+604800,"/",$domain);
		//Ends here
				
		$callValidate=1;
		$logindone='Y';
		include_once("login_intermediate_pages.php");
		$return_url=intermediate_page(1);
		if (!$return_url && true === SymfonyFTOFunctions::showOfferPage($profileid)) {
		        $return_url = "$SITE_URL/fto/offer?fromReferer=0";
			
			if($isMobile)
			{
				//tracking logins #2550 by nitesh
				if($loginTracking)
					$loginTracking->loginTracking("/fto/offer");
				if($redirectProperly==1)
				{
					echo "<script type=\"text/javascript\">
						window.parent.location.href='$return_url';
					</script>";
				}
				else
					header("Location: $return_url");
	                        die;
			}
		}
		elseif(!$return_url && !$isMobile && $from_homepage){// Check whether the user has set FILTERS OR NOT

			if(filter_page_redirection($profileid)==="Y"){
				$return_url="$SITE_URL/register/page6?fromPage=loginDeclineRedirect";
			}
		}
		elseif($isMobile)
		{
			if($prev_url)
				$return_url=$prev_url;
			else
			{ 
				if($data['HAVEPHOTO']=='N' || strlen($data['HAVEPHOTO'])==0)
					$return_url="$SITE_URL/profile/viewprofile.php?ownview=1";
				else
				{
					$memchacheObj = new ProfileMemcacheService($data['PROFILEID']);
					$memberToAcceptCount = $memchacheObj->get("AWAITING_RESPONSE");
					if($memberToAcceptCount > 0)
						$return_url="/profile/contacts_made_received.php?page=eoi&filter=R";
					else				 
						$return_url="/search/partnermatches";
				}
			}
			
			if(strpos($return_url,"http") === false)
			{
				$return_url = $SITE_URL."$return_url";
			}
			
			if($loginTracking)
				$loginTracking->loginTracking($return_url);
			
			if($redirectProperly==1)
			{
				echo "<script type=\"text/javascript\">
					window.top.location.href='$return_url';
				</script>";
			}
			else
				header("Location: $return_url");
			die;
		}
		if(!$return_url){
                	$return_url="/profile/mainmenu.php?checksum=$checksum";

			if($loginTracking)
	                	$loginTracking->loginTracking("/profile/mainmenu.php");

				}
		else
		{
			if($loginTracking)
				$loginTracking->loginTracking($return_url);
		}
		
		
		$smarty->assign("REDIRECTURL","$return_url");
		$protect_obj->setchatbarcookie();
		if($redirectProperly==1)
		{
			
			echo "<script type=\"text/javascript\">
			window.top.location.href='$return_url';
			</script>";
		}
		else
			header("Location:$return_url");
		die;
	}
	else
	{
		
		$smarty->assign("WRONGUSER","1");
	
		/*Changes Made for the Logout.php */
		
		/*************Portion of Code added for display of Banners*****************************/
		$smarty->assign("NO_BOTTOM_ADSENSE","1");
		$smarty->assign("data",$data["PROFILEID"]);
		$smarty->assign("bms_topright",11);
		$smarty->assign("bms_middle",12);
		$smarty->assign("bms_bottom",13);
		$smarty->assign("bms_new_win",38);
		/***********************End of Portion of Code*****************************************/

			if($from_homepage ==1)				
				$smarty->assign("newHomePage","1");

		$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
		$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
		$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
		
		$ip=FetchClientIP();
		if(strstr($ip, ","))    
		{
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
					
		
		$smarty->assign("username",htmlspecialchars($username,ENT_QUOTES));	
		if($login2contact)
		{
			$smarty->assign("STATUS",$status);
			$smarty->assign("PROFILECHECKSUM",$profilechecksum);
			$smarty->assign("MARKCC",$markcc);
			$smarty->assign("CONTACTTYPE",$contacttype);

			if($DISPLAY_TEMPLATE=='M')
				$smarty->display("login2contact_male.htm");
			else
				$smarty->display("logout_1.htm");  // Have to Check wheather to change this link or Not
		}
		else if($redirectProperly ==1)
		{
			$sql="INSERT INTO LOGIN_FAILED1(USERNAME,PASSWORD,DATE,USER_AGENT,IP) VALUES ('$username','$password',now(),'".$_SERVER['HTTP_USER_AGENT']."','$ip')";
			$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$smarty->assign("MAIL","Y");
			$smarty->assign("USERNAME","$username");
			$smarty->assign("show_username","1");
			$smarty->assign("WRONGUSER",1);
			$smarty->assign("var_in","1");
			echo "<script type=\"text/javascript\">
			var params='from_homepage_redirect=1';
				window.top.location.href=\"$SITE_URL/profile/login.php?\"+params;
			</script>";
			die;
			//$smarty->display("logout_1.htm");
		}
		else if($from_homepage_redirect ==1)
		{
			
			$smarty->assign("USERNAME","");
			$smarty->assign("show_username","1");
			$smarty->assign("invalidUsernamePassword","1");	
			if($isMobile){
				include_once($_SERVER['DOCUMENT_ROOT']."/jsmb/login_home.php");
				die;
			}
			else	
				$smarty->display("logout_1.htm");
		}
		else
		{
			$smarty->assign("var_in","1"); // Changes done for the Logout.php
			if($profile_archived)
			{
				$smarty->assign("login_mes"," Your account $profile_archived has been activated");
				$smarty->assign("WRONGUSER",0);
				$smarty->assign("var_in",0);
			}
			if($from_month)
			{
				$smarty->assign("login_mes"," Welcome back to Jeevansathi!");
									$smarty->assign("WRONGUSER",0);
									$smarty->assign("var_in",0);
			}		
			//$smarty->display("login_register.htm");
			if($isMobile){
				include_once($_SERVER['DOCUMENT_ROOT']."/jsmb/login_home.php");
				die;
			}
			$smarty->display("logout_1.htm");
		}
	}

	//added by pankaj for login tracking
	function setLoginTracker($cookies, $pid, $source)
	{
	$cookies = substr($cookies,0,8);
    	$sql    = "INSERT INTO newjs.LOGIN_TRACKING VALUES ('$cookies','$pid','$source',now())";
    	$result = mysql_query_decide($sql);
	}
	
?>
