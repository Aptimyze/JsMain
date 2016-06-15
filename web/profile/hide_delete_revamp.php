<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
header("Location:".$SITE_URL."/settings/jspcSettings?hideDelete=1");die;
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	include("connect.inc");
	include("sphinx_search_function.php");
	$db=connect_db();

	if($from_search_error)
		$smarty->assign("from_search_error",1);
	$data=authenticated($checksum);
	if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
        {
                $fromprofilepage=1;
                mysql_select_db_js('marriage_bureau');
                include('../marriage_bureau/connectmb.inc');
                $mbdata=authenticatedmb($mbchecksum);
                if(!$mbdata)timeoutmb();
                $smarty->assign("source",$mbdata["SOURCE"]);
                $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
                mysql_select_db_js('newjs');
                //$data=login_every_user($profileid);
                $mbureau="bureau1";
        }
	
	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",671);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",672);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	//$regionstr=8;
	//$zonestr="18";
	//include("../bmsjs/bms_display.php");
	/************************************************End of Portion of Code*****************************************/
	//$db=connect_db();

	/********************Added By Shakti for link tracking*******************************/
	link_track("deleteprofile.php");
	/*************************************************************************************/
	$smarty->assign("head_tab","my jeevansathi");
	if($data)
	{
		login_relogin_auth($data);//added for contact details on leftpanel.
		
		//This is required since we have to fill the username,email and name field for success story
		if(!$CMDunhide && !$CMDhide)
		{
		if(true)
		{
			$smarty->assign("GENDER",$data['GENDER']);
			$smarty->assign("TEMPLATES_DIR",$smarty->template_dir);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("hide_delete",1);
			$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
			$smarty->assign("profileid",$data["PROFILEID"]);
			savesearch_onsubheader($data["PROFILEID"]);
			$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
			//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
			$smarty->assign("bms_right","");
			rightpanel($data);
			$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
			$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
			if($show)
				$smarty->assign("SHOW",$show);
			
			$sql="select EMAIL,ACTIVATED,ACTIVATE_ON from newjs.JPROFILE where  PROFILEID=".$data['PROFILEID'];
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
			
			if($row=mysql_fetch_assoc($res))
			{
				$email=$row["EMAIL"];
				if($row["ACTIVATED"]=="H")
				{
					$smarty->assign("UNHIDE",1);
					list($year,$month,$day)=explode("-",$row["ACTIVATE_ON"]);
					$smarty->assign("UNHIDE_DATE",my_format_date($day,$month,$year,4));
				}
				else
					$smarty->assign("UNHIDE",0);
			}

		}

		$del_reason_arr = array("I found my match on Jeevansathi.com","I found my match on another matrimonial site","I found my match elsewhere","I am unhappy with Jeevansathi.com services","Other reasons");
		$smarty->assign("del_reason_arr",$del_reason_arr);

		$hide_duration_arr = array("7","15","30");
                $smarty->assign("hide_duration_arr",$hide_duration_arr);
		}

		$profileid=$data["PROFILEID"];

		if($CMDhide)
                {
                        if(!is_numeric($hide_duration))
                        {
				include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
				ValidationHandler::getValidationHandler("","white listing handling : web/profile/hide_delete_revamp.php");
				$hide_duration = 7;
                        }
                        $sql="update JPROFILE set PREACTIVATED=if(ACTIVATED<>'H',ACTIVATED,PREACTIVATED), ACTIVATED='H', ACTIVATE_ON=DATE_ADD(CURDATE(), INTERVAL $hide_duration DAY) where PROFILEID='$profileid'";
                        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        $data["ACTIVATED"]='H';
                        $protect_obj->setcookies($data);

			$dateStamp=mktime(0,0,0,date("m"),date("d")+$hide_duration,date("Y"));
			$date=date("Y-m-d",$dateStamp);
			list($year,$month,$day)=explode("-",$date);
			$unhide_date=my_format_date($day,$month,$year,4);
			echo "You have chosen to hide your profile till $unhide_date, after which it will be visible to other users again. Use this feature to unhide your profile now.";
			callDeleteCronBasedOnId($profileid);
			exit;
                }
		elseif($CMDunhide)
                {
				$sql="update JPROFILE set ACTIVATED=PREACTIVATED where PROFILEID='$profileid'";
                                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                                $sql="select PREACTIVATED from JPROFILE where  PROFILEID='$profileid'";
                                $act_result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $act_row=mysql_fetch_row($act_result);

                                $preactivated=$act_row[0];

                                $data["ACTIVATED"]=$preactivated;
                                $protect_obj->setcookies($data);
				die;
                }
		elseif($CMDdelete)
		{
				if($from_ss==1)
					$delete_reason="I found my match on Jeevansathi.com";
				if($delete_reason=="I found my match on another matrimonial site")
					$specify_reason="www.".trim($specify_reason).".com";
				if($delete_reason=="I am unhappy with Jeevansathi.com services")
				{
					$msg.="Date  :  ".date("d-m-Y")."<br>";
					$msg.="Username  :  ".$data["USERNAME"]."<br>";
					$msg.="Email id  :  ".$email."<br>";
					if(trim($specify_reason))
						$msg.="Reason   : ".trim($specify_reason)."<br>";
					else
						$msg.="Reason   : No reason specified<br>";
					send_email("feedback@jeevansathi.com",$msg,"Unhappy user deletes profile","info@jeevansathi.com");
				}
				delete_profile($data["PROFILEID"],$delete_reason,$specify_reason);
				callDeleteCronBasedOnId($data["PROFILEID"]);
				header("Location: $SITE_URL/profile/logout.php?checksum=$checksum");
				die;
		}	
		if($mbureau=="bureau1")
		{
			$smarty->assign("mb_username_profile",$data["USERNAME"]);
			$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        
		}
		$leftpanel_settings=$smarty->fetch("leftpanel_settings.htm");
		$smarty->assign("LEFTPANEL_SETTINGS",$leftpanel_settings);
		$smarty->display("hide_delete_revamp.htm");
	}
	else 
	{
		if($CMDunhide || $CMDhide)
			echo "Login";
		else
			TimedOut();
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
