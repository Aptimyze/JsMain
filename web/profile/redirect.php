<?php

	include("connect.inc");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	 if(MobileCommon::isDesktop())
	{
		header("Location:".$SITE_URL."/static/logoutPage?fromSignout=1");die;
	}
	$db=connect_db();

	if($occ=="123")
	{
		$METHOD="";
	}

	//  If requested URL from the mailer is to be targeted
	if(($MAIL=='Y') || $REQUESTEDURL)
	{	
		if($REGISTER)
		{
			$password=$reg_password;
			$_POST['password']=$password;
		}
		$status=login($username,$password);

		//If coming from register page.
		if($REGISTER && !$status)
		{
			$redirect_url=$REQUESTEDURL;
			$LOGIN_ERR=1;
			include("registration_new.php");
			die;
		}
		//added by Puneet Makkar to check whether new user is same or different than earlier user
		if($PROFILEID==$status['PROFILEID'] || $PROFILEID=='')
		{	
      $REQUESTEDURL = ereg_replace("fromReferer=1", "fromReferer=0", $REQUESTEDURL);
			$REQUESTEDURL = ereg_replace("profilechecksum=","profilecheck=",$REQUESTEDURL);
			$REQUESTEDURL = ereg_replace("searchchecksum=","searchcheck=",$REQUESTEDURL);
			$REQUESTEDURL = ereg_replace("checksum=","checksum=$status[CHECKSUM]&",$REQUESTEDURL);
			$REQUESTEDURL = ereg_replace("profilecheck=","profilechecksum=",$REQUESTEDURL);
			$REQUESTEDURL = ereg_replace("searchcheck=","searchchecksum=",$REQUESTEDURL);
		}
		else
			$REQUESTEDURL='';

		if($REQUESTEDURL=='')	
		{	
			$data=$status;
			include("login.php");
			exit;
		}
		//end of code added by Puneet Makkar to check whether new user is same or different than earlier user
	
	}
	else
	{	
		$status=login($username,$password);
		//added by Puneet Makkar to check whether new user is same or different than earlier user
		if($PROFILEID!=$status['PROFILEID'] && $PROFILEID!='')
                {
			$data=$status;
                        include("login.php");
                        exit;
                }
		//end of code added by Puneet Makkar to check whether new user is same or different than earlier user

	}
	if(isset($status))
	{
		$pid = $status['PROFILEID'];
		$uni_id=uniqid("abcd");
		$username_new=$status["USERNAME"];

		if($METHOD=="POST")
		{
			if($redirectProperly==1)
			{
				if($REQUESTEDURL)
				{
					if($REQUESTEDURL[0]=='/')
						$return_url=$SITE_URL.$REQUESTEDURL;
					else
						$return_url=$REQUESTEDURL;
				}
				else
					$return_url="$SITE_URL/profile/mainmenu.php?checksum=$checksum";
				if(strpos($REQUESTEDURL,"redirect.php")!==-1)
				{
					$return_url="$SITE_URL/profile/mainmenu.php?checksum=$checksum";
				}
				echo "<script type=\"text/javascript\">
							window.top.location.href='$return_url';
						</script>";die;
			}

			echo("<html> <script type=\"text/javascript\" language=\"javascript\">function loadpopunder(url,name,winfeatures){win2=window.open(url,name,winfeatures);if(win2){win2.blur();window.focus();}}</script><body onload=\"javascript:document.form1.submit();\">");
			echo "<center><b>Please Wait while you are being redirected</b></center>";

			if($ACTION=='' || $ACTION=='/profile/redirect.php')
				$ACTION='/profile/mainmenu.php';

			$tempvar .= "<form name=\"form1\" action=\"$ACTION\" method=\"$METHOD\">";
			foreach($_POST as $key => $value)
			{
				//remove the variables passed by session_over.htm
				if($key!="username" && $key!="password" && $key!="SESSIONsubmit")
				{
					if(is_array($value))
					{
						foreach($value as $val)
		                		{
			                		$tempvar .= "<input type=\"hidden\" name=\"".htmlspecialchars($key, ENT_QUOTES, false)."[]\" value=\"" . htmlspecialchars($val, ENT_QUOTES, false)."\">";
		        			}
					}
					else
						$tempvar .= "<input type=\"hidden\" name=\"" . htmlspecialchars($key, ENT_QUOTES, false) . "\" value=\"" . htmlspecialchars($value, ENT_QUOTES, false) . "\">";
				}
			}
			$tempvar .= "</form>";
			$tempvar .= "</body></html>";
			echo $tempvar;
			exit();
		}
		else //redirect to the requsted page by GET
		{
			//header("Location: http://".$_SERVER['HTTP_HOST'].$REQUESTEDURL);
if($redirectProperly==1)
{
	if($REQUESTEDURL)
	{
		if($REQUESTEDURL[0]=='/')
			$return_url=$SITE_URL.$REQUESTEDURL;
		else
			$return_url=$REQUESTEDURL;
	}
	else
		$return_url="$SITE_URL/profile/mainmenu.php?checksum=$checksum";
		
	if(strpos($REQUESTEDURL,"redirect.php"))
	{
		$return_url="$SITE_URL/profile/mainmenu.php?checksum=$checksum";
	}
	echo "<script type=\"text/javascript\">
				window.top.location.href='$return_url';
			</script>";die;
}
$smarty->assign("chat_hide","");
$smarty->assign("REDIRECTURL",htmlspecialchars($REQUESTEDURL, ENT_QUOTES, false));
$protect_obj->setchatbarcookie();
$smarty->display("login_redirect.htm");

//			echo "<html><script type=\"text/javascript\" language=\"javascript\">function loadpopunder(url,name,winfeatures){win2=window.open(url,name,winfeatures);if(win2){win2.blur();window.focus();}}</script><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL" . "$REQUESTEDURL\"></body></html>";
			//header("Location: ".$SITE_URL.$REQUESTEDURL);
//			exit;
		}
	}
	else 
	{
		if($redirectProperly==1)
		{
			echo "<script type=\"text/javascript\">
				window.top.location.href='$SITE_URL/profile/login.php?from_homepage_redirect=1';
			</script>";
			die;
		}
		else
		{
			$smarty->assign('WRONGUSER',1);
			TimedOut();
		}
	}
	
?>
