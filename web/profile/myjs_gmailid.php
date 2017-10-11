<?php
include("connect.inc");
include("registration_functions.inc");
connect_db();
$data=authenticated();
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");
if($email || $submit)
{
	if($data["PROFILEID"])
	{
		$profileid=$data["PROFILEID"];
		$email_flag = checkemail($email);
		$old_email_flag = checkoldemail($email);
		$af_email_flag = checkemail_af($email);

		if($email=="")
		{
			echo "E1";
			die;
		}
		elseif($email_flag == 1 || $af_email_flag == 1)
		{
			echo "E1";
			die;
		}
		elseif($email_flag == 2 || $old_email_flag == 2 || $af_email_flag == 2) // For Existing email
		{
			$activated = get_profile_active_status($email);
$activated='X';
			if($activated == "D")
				$link = "<a href=\"".$SITE_URL."/profile/faq_other.php?retrieve_profile=1&email=$email\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\" target=\"_blank\">";
			//else
			  	//$link = "<a href=\"forgot\" name=\"forgot_password_link\" id=\"forgot_password_link\">";
			if($activated=='D')
			{
				echo "E5D";
				die;
			}
			echo "E5";
			die;
		}
		elseif($email_flag == 3 || $old_email_flag == 3)
		{
			echo "E2";
			die;
		}
		elseif($email_flag == 4)
		{
			echo "E3";
			die;
		}

		$sql_s="SELECT SOURCE,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,EMAIL FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_s=mysql_query_decide($sql_s) or logError($errorMsg,"$sql_s","ShowErrTemplate");
		$row_s=mysql_fetch_array($res_s);
		$oldemail=$row_s['EMAIL'];

		$sql="INSERT IGNORE INTO OLDEMAIL VALUES('$profileid','$oldemail')";
		mysql_query_decide($sql) or logError($errorMsg,"$sql","ShowErrTemplate");
		$date_now=date("Y-m-d H:i:s");
		$ip=FetchClientIP();//Gets ipaddress of user
		if(strstr($ip, ","))
		{
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		if($email!=$oldemail)
		{
			$sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
			$res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
			if(mysql_num_rows($res_search)>0)
			{
				$row_search=mysql_fetch_assoc($res_search);
				$changeid=$row_search['CHANGEID'];
				$sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$oldemail','$email') ";
				$res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
			}
			else
			{
				$sql_insert= "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'EMAIL')";
				$res_insert= mysql_query_decide($sql_insert) or die(mysql_error_js());
				$sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
				$res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
				$row_search=mysql_fetch_assoc($res_search);
				$changeid=$row_search['CHANGEID'];
				$sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$oldemail','$email') ";
				$res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
			 }
			//Insert into autoexpiry table, to expire all autologin url coming before date
                $expireDt=date("Y-m-d H:i:s");
                $bRes = ProfileReplaceLib::getInstance()->replaceAUTOEXPIRY($profileid, 'E', $expireDt);
                if(false === $bRes) {
                    $sqlExpire="replace into jsadmin.AUTO_EXPIRY set PROFILEID='$profileid',TYPE='E',DATE='$expireDt'";
                    logError($errorMsg,"$sqlExpire","ShowErrTemplate");
                }
                         //end

		}
		if(strstr($email,"@gmail"))
		{
			$Email=$email;
			$profileid=$data['PROFILEID'];
			$username=$data["USERNAME"];
			$sql="delete from bot_jeevansathi.user_info where  profileID='$profileid' OR gmail_ID='$Email'";
			mysql_query_decide($sql) or logError($sql);

			$sql="delete from bot_jeevansathi.user_online where  USER='$profileid'";
			mysql_query_decide($sql) or logError($sql);

			$sql_invite_entry="insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('$username','$Email')";
			mysql_query_decide($sql_invite_entry);

			$sql_invite_entry="insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('$profileid','$Email')";
			mysql_query_decide($sql_invite_entry);

			$sql_bot_entry="insert ignore into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('$Email',0,1,'$profileid','$username')";
			mysql_query_decide($sql_bot_entry) or logError($sql);

			//send_chat_request_email($profileid,$Email,$username);

		}
		$sql="UPDATE JPROFILE SET EMAIL='$email',MOD_DT=NOW() WHERE PROFILEID='$data[PROFILEID]'  AND activatedKey=1";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		JProfileUpdateLib::getInstance()->removeCache($data[PROFILEID]);
		echo "1";
	}
	else
		echo "EL";
	die;
}
else
{
	if(!$data)
	{
		$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
		$smarty->display("login_layer.htm");
		die;
	}
	$smarty->display("myjs_gmailid.htm");
}
?>
