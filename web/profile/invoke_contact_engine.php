<?php
	$zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt && !$dont_zip_now)
                ob_start("ob_gzhandler");
        //end of it
	
	header("Cache-Control: no-cache, must-revalidate");
        //Sharding+Combining
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
        $mysqlObj=new Mysql;
        $jpartnerObj=new Jpartner;
        //Sharding+Combining

        // common include file
        include_once("connect.inc");
	include_once("contact.inc");
	include_once("connect_functions.inc");
	include_once("arrays.php");
	include_once("mobile_detect.php");
        // contains array definitions
        //added by sriram.
        // connect to database
        $db=connect_db();
        $data=authenticated($checksum);
		if($isMobile){
				if(checkPhoneVerificationLayerCondition($data[PROFILEID]))
				{
					include_once("../ivr/knowlarityFunctions.php");
					$vNumber=getAllProfileVirtualNumbers($data[PROFILEID]);
					if($to_do=='view_contact' || $to_do=='view_contact_message' || $to_do=='view_contact_ignore')
					{
						phoneLayerTracking($data[PROFILEID],'CONTACT',"Y");
						$smarty->assign("PHONE_INVALID",1);
					}
					if($to_do=='eoi')
					{
						$overall_cont=get_dup_overall_cnt($data[PROFILEID]);
						$limits=set_contact_limit($data[SUBSCRIPTION]);
						$notvalidnumber_limit=$limits[4];
						if($overall_cont>=$notvalidnumber_limit)
						{
							phoneLayerTracking($data[PROFILEID],'EOI',"Y");
							$smarty->assign("PHONE_INVALID",1);
						}
					}
					$smarty->assign("vNumber",$vNumber);
				}
				
				$header=$smarty->fetch("mobilejs/jsmb_header.html");
				$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
				$smarty->assign("HEADER",$header);
				$smarty->assign("FOOTER",$footer);
		}
	//Required in logout case as well
	$smarty->assign("FROM_VIEW",$FROM_VIEW);
	if($data)
	{
        $smarty->assign("LOGGEDIN", 1);
		global $ajax_error;
		$ajax_error=3;
		$paid=0;
		if($data['SUBSCRIPTION'])
			$paid=1;
		$pid=$data['PROFILEID'];
		$username=$data['USERNAME'];
		$invoke_layer=1;
		if($multiple==1)
		{
			$profilechecksumArray=explode(",",$profilechecksum);
			$snoArray=explode(",",$index);
		}
		if($profilechecksum)
		{
			if($to_do=='remove_favourite')
			{
				$smarty->assign('senders_data',$profilechecksum);
				$smarty->assign('multiple', true);
				$reloadFirstPage=false;
				if($total_profile-count($profilechecksumArray)==0){
					$reloadFirstPage=true;
				}
				$smarty->assign('reloadFirstPage',$reloadFirstPage);
				$smarty->display("remove_bookmark.htm");
				die;				
			}
			if(!$multiple)
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
				
				$sql="SELECT PROFILEID,USERNAME,GENDER,SUBSCRIPTION,SOURCE,SHOWPHONE_RES,PHONE_RES,STD,ISD,SHOWPHONE_MOB,PHONE_MOB,CONTACT,SHOWADDRESS,EMAIL,RELATION,SHOW_PARENTS_CONTACT,PARENTS_CONTACT,TIME_TO_CALL_START,TIME_TO_CALL_END,SHOWMESSENGER,MESSENGER_CHANNEL,MESSENGER_ID,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,COUNTRY_RES,ACTIVATED,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$receiver_profileid'";
				$res=@mysql_query_decide($sql) or logError("",$sql);
				$row=@mysql_fetch_assoc($res);
				$jprofile_result["viewed"]=$row;
				$smarty->assign("PROFILENAME",$row["USERNAME"]);
				@mysql_free_result($res);
			}
			if($to_do=='add_intro' || $to_do=="remove_intro")
			{
				if($to_do=="add_intro")
				{
					off_call_history();
				}
				$smarty->assign("to_do",$to_do);
				$smarty->assign('senders_data',$profilechecksum);
				$smarty->display("handle_intro_call.htm");
				die;				
			}
			

			$sql="SELECT ISD,PROFILEID,GENDER,USERNAME,ACTIVATED,INCOMPLETE,SUBSCRIPTION,RELATION,COUNTRY_RES,PHONE_RES,PHONE_MOB,STD,EMAIL,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
			$res=@mysql_query_decide($sql) or logError("",$sql);;
			$row=@mysql_fetch_assoc($res);
			$jprofile_result["viewer"]=$row;
			@mysql_free_result($res);
			if(!$multiple)
			{
				$NUDGES=array();
				$n_source=$jprofile_result["viewed"]["SOURCE"];
				$contact_status_new=get_contact_status_dp($receiver_profileid,$pid);
				if($contact_limit_check)
				{
					$allowed_limit=100;
					$allowed_limit_for_month=500;
					
					/* Below 2 values are set in authenticate function*/
					if($data['DAY_LIMIT']>=0)
						$allowed_limit=$data['DAY_LIMIT'];
					if($data['MONTH_LIMIT']>=0)
						$allowed_limit_for_month=$data['MONTH_LIMIT'];

					if(contact_limit_expired_for_month($pid,$allowed_limit_for_month))
						$contact_limit_reached=1;
					else
					{
						if(contact_limit_expired($sender_profileid,$allowed_limit))
						$contact_limit_reached=1;
						else
						{
							if(!$paid)
							{
								if(free_contact_limit_expired($sender_profileid))
									$contact_limit_reached=1;
								else
									$contact_limit_reached='';
							}
							else
							$contact_limit_reached='';
						}
					}
				}
				else
					$contact_limit_reached='';

				if($jprofile_result["viewed"]["GENDER"]==$data["GENDER"])
					$samegender=1;
				else
					$samegender='';
			}
			if($multiple)
			{
				if($to_do=="accept")
					$contact_status_new["TYPE"]="I";
				if($to_do=="decline")
					$contact_status_new["TYPE"]="I";
			}
			if($contact_status_new["TYPE"]=="C" && $to_do!="view_contact" && $to_do!="view_contact_message" && $to_do!="view_contact_ignore" && $contact_status_new["R_TYPE"]!="RC")
				$smarty->assign("ERROR_MESSAGE","A JeevanSathi member has cancelled further communication with you");
			else
			{
				if($to_do=='view_contact')
				{
/*added to add phone verification layer on contact details page trac 886 and 990 by esha
					include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
					$PH_LAYER_STATUS=get_phoneVerifyLayer($row);
					if($PH_LAYER_STATUS!='')
					{
					        $url=$SITE_URL.'/profile/myjs_verify_phoneno.php?flag='.$PH_LAYER_STATUS.'&width=700';
						//                        $smarty->assign("PH_LAYER_STATUS",$ph_layer_status);
						$smarty->assign("VERIFY_PHONE","Your phone number is not verified. <a href=\"/profile/myjs_verify_phoneno.php?sourcePage='CONTACT'&width=700\" class=\"thickbox\" onclick=\"javascript:{close_all_con_layer();$.colorbox({href:'/profile/myjs_verify_phoneno.php'});return hide_exp_layer()}\">Click here</a> to Verify your phone number. Or call 18004196299 for help.");
						$smarty->display("invoke_contact_engine.htm");
			                }
end trac 886 and 990*/
					if($jprofile_result['viewer']['GENDER']==$jprofile_result['viewed']['GENDER'])
                                                $samegender=1;
					else if(check_spammer_filter($jprofile_result,'viewContactDetails'))
						$filter=1;
				}
				if($redirect_to_contact)
					$jprofile_result['REDIRECT_TO_CONTACT'] = true;
			if($isMobile)
				navigation($nav_type,"","");
			//For preventing global scope in generate_express_form() and generate_express_form_fixed() function
			$show_contacts = false;
			express_page($jprofile_result,$data,$contact_status_new,$NUDGES,$spammer,$filter,$contact_limit_reached,$samegender,1,$to_do);
				
			}
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("PROFILECHECKSUM",$profilechecksum);
			$smarty->assign("INVOKE_LAYER",$invoke_layer);
			$smarty->assign("INDEX",$index);
			if($to_do!='remove_favourite')
				$smarty->assign("ACTION",$to_do);
			$smarty->assign("MULTIPLE",$multiple);
			$smarty->assign("close_func",$close_func);
			$smarty->assign("STYPE",$STYPE);
			if($to_do=="view_contact" || $to_do=="view_contact_ignore" || $to_do=="view_contact_message")
			{
				if($redirect_to_contact)
					$smarty->assign("SHOW_SHORT_CONTACT",1);
				else
					$smarty->assign("SHOW_CONTACT",1);
			}
			if($isMobile)
			{
				//Mobile/lanline number formatting to make it work for adding to phonebook
				$mob_num="+".str_replace("-","",$smarty->_tpl_vars['SHOW_MOBILE']);
				$landl_num="+".str_replace("-","",$smarty->_tpl_vars['PHONE_NO']);
				if(substr($landl_num,3,1) ==='0')
					$landl_num=substr($landl_num,0,3).substr($landl_num,4);
				$smarty->assign("MOB_NUM",$mob_num);
				$smarty->assign("LANDL_NUM",$landl_num);
			    $mob_cookie_arr=explode(",",preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE['JS_MOBILE']));
				$smarty->assign("phoneBook_support",$mob_cookie_arr[1]);
				$smarty->assign("call_support",$mob_cookie_arr[2]);
				$smarty->assign("NAV_TYPE",$nav_type);
				$smarty->display("mobilejs/jsmb_express-interest.html");
			}
			elseif($to_do="callnow_layer" && $FROM_VIEW)
				$smarty->display("dp_express_interest_layer_fixed.htm");
			else{
				if($fromNewSearch)
					$smarty->display("search_contact_layer.html");
				else
				{
					$smarty->assign("EXPRESS_LAYER",$smarty->fetch("dp_express_interest_layer_fixed.htm"));
					$smarty->display("invoke_contact_engine.htm");
				}	
			}
		}
	}
	else
	{
		if($isMobile){
			$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
			$smarty->display("mobilejs/jsmb_login.html");
			die;
		}
		if($to_do=='remove_favourite')
		{
			include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
			$smarty->display("login_layer.htm");
			die;
		}
	else{
if($to_do=="callnow_layer")
			{
				$CALLNOW_ERROR["MESSAGE"]="You need to be logged in to use Call now feature";
				$CALLNOW_ERROR["LINK"]='<div align="center" style="padding:5px"><input type="button"  style="width: 60px;" value="Login" class="b  green_btn  en_btn_clr_alb" onclick="javascript:show_login_layer(\'call\')"></div>';
				$smarty->assign("CALLNOW_ERROR",$CALLNOW_ERROR);
				$smarty->display("callnow_layer.htm");
				
			}
		else
			echo "Login";die;
}
	}
?>
