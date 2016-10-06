<?php
function sendForgotPasswordLink($myrow)
{

        global $smarty;
        if(!$myrow['EMAIL'] ||!$myrow['PROFILEID']||!$myrow['ACTIVATED'])
        {
                if(!$profileid&& !$myrow['PROFILEID'])
                        return;
                if($myrow['PROFILEID'] && !$profileid)
                        $profileid = $myrow['PROFILEID'];
                if($profileid)
                {
                        $sql = "select EMAIL,PROFILEID,ACTIVATED from JPROFILE where PROFILEID = '".$profileid."'";;
                        $res = mysql_query ($sql) or die("Could not process request at this time.");
                        $myrow = mysql_fetch_array($res);
                }
        }
        if(!$profileid)
                $profileid = $myrow['PROFILEID'];
        if($myrow['ACTIVATED']=="D")
                return;
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	$forgotPasswordStr = ResetPasswordAuthentication::getResetLoginStr($myrow['PROFILEID']);
	$forgotPasswordUrl = JsConstants::$siteUrl."/common/resetPassword?".$forgotPasswordStr;
	if($_SERVER['HTTP_BURP'] == "burp") return true;
	$email_sender = new EmailSender(MailerGroup::FORGOT_PASSWORD,1778);
	$emailTpl = $email_sender->setProfileId($myrow[PROFILEID]);
	$emailTpl->getSmarty()->assign("forgotPasswordUrl",$forgotPasswordUrl);
	$email_sender->send($myrow[EMAIL]);
        if($myrow['SmsCount'] < 5)
        {
        	include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
        	$sms = new InstantSMS("FORGOT_PASSWORD", $myrow["PROFILEID"]);
                $sms->send('OTP');
        }
}
