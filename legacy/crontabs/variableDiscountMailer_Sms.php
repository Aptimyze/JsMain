<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : variableDiscountMailer_Sms.php 
* DESCRIPTION   : Cron script to send the email and sms for the profiles whoses discount starts from today
*****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");
$path = $_SERVER['DOCUMENT_ROOT'];
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once($path."/classes/NEGATIVE_TREATMENT_LIST.class.php");
include (JsConstants::$smartyDir);

// smarty template set
$smarty = new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/mailer/");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

// connection set
$db_slave = connect_slave();
$db_master =connect_db();

// variable parameters set
$SITE_URL = $IMG_URL =JsConstants::$siteUrl;
$smarty->assign("IMG_URL",$IMG_URL);
$smarty->assign("SITE_URL",$SITE_URL);
$todayDate=date("Y-m-d");

//$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT WHERE SDATE='$todayDate' OR EDATE='$todayDate' OR DATE_SUB(`EDATE`, INTERVAL 3 DAY)='$todayDate' OR DATE_SUB(`EDATE`,INTERVAL 2 DAY)='$todayDate'";
$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT";
$res =mysql_query($sql,$db_master) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
        $profileid      =$row["PROFILEID"];
        $discount       =$row["DISCOUNT"];
	$sdate		=$row['SDATE'];
	$edate		=$row["EDATE"];
	$status		=$row['SENT'];
	$last4thDay	=date("Y-m-d",strtotime("$edate -3 days"));
	$last3rdDay	=date("Y-m-d",strtotime("$edate -2 days"));
	$discountEndDate=date("d-M-Y",strtotime($edate));		

	if(($sdate==$todayDate) && $status=='N' ){
		$msgTxt ="Congratulations! You are selected for a special discount of $discount% by Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500 before $discountEndDate";
		$subject ="Congratulations! You are selected for a special discount by Jeevansathi.com";	
		$daySet=1;
		$status='Y';
	}
	elseif(($last4thDay==$todayDate) && ($status=='N' || $status=='Y')){
                $msgTxt ="Last 4 days left to avail your exclusive discount of $discount% on Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500";
		$status='Y1';
	}
	elseif(($edate==$todayDate) && ($status=='N' || $status=='Y' ||$status=='Y1' || $status=='Y2')){
                $msgTxt ="Don't Miss it!! Your special discount of $discount% on Jeevansathi.com is ending today. Avail it online or call us at 18004196299/0120-4393500";
		$status='Y3';
	}
        elseif(($last3rdDay==$todayDate) && ($status=='N' || $status=='Y' || $status=='Y1')){
                $subject ="Hurry, last 3 days left to avail your exclusive discount at Jeevansathi.com";
		$status='Y2';
        }
	if(!$status)
		continue;

        $sqlJ ="SELECT PHONE_MOB,EMAIL,PHONE_FLAG,GET_SMS,SUBSCRIPTION,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED IN('Y','H')";
	$resJ =mysql_query($sqlJ,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	if($rowJ=mysql_fetch_array($resJ))
	{
		$phoneMob 	=$rowJ["PHONE_MOB"];
		$email		=$rowJ["EMAIL"];
		$phoneFlag	=$rowJ["PHONE_FLAG"];
		$getSms		=$rowJ["GET_SMS"];
		$subscription	=$rowJ['SUBSCRIPTION'];
		$username	=$rowJ['USERNAME'];
		$subArr		=@explode(",",$subscription);		
	
		// currently paid check	
		if(!in_array("F",$subArr) && !in_array("D",$subArr)){

			// negative treatmet filter		
			$negativeFilterReq =negativeTreatmentFilter($profileid,$db_slave);
			if(!$negativeFilterReq){
				if($phoneMob && $msgTxt && $phoneFlag!='I' && $getSms!='N'){
					$fieldVal =filterProfile($profileid,$phoneMob,$db_slave);
					if($fieldVal){
						sendSms_dis($phoneMob,$msgTxt,$profileid);
						updateVD($profileid,$status,$db_master);	
					}	
					unset($fieldVal);
				}
				if($email && $subject){
						$jAlertsArr =getJprofileAlerts($profileid,$db_slave);
						$profileName =getUserName($profileid,$db_slave);
						if(!$profileName)	
							$profileName =$username;					
						if($jAlertsArr['MEMB_MAILS']!='U'){
							sendMail_dis($profileid,$email,$subject,$discount,$discountEndDate,$profileName,$daySet);
							updateVD($profileid,$status,$db_master);
						}
				}
			}	
		}
	}
	unset($subArr);
	unset($jAlertsArr);
	unset($msgTxt);
	unset($subject);
	unset($status);
	unset($daySet);
	unset($profileid);
	//die('success');	
}	

// function to send the email to the user 
function sendMail_dis($profileid,$to_email,$subject,$discount,$discountEndDate,$name,$daySet='')
{
	global $smarty,$SITE_URL;
        $from   ="matchpoint@jeevansathi.com";
	if($daySet==1){
		$discountEndDate=date("j-S-F-Y",strtotime($discountEndDate));
		$datesParamArr =explode("-",$discountEndDate);
		$smarty->assign("day",$datesParamArr[0]);
		$smarty->assign("suffix",$datesParamArr[1]);
		$smarty->assign("month",$datesParamArr[2]);
		$smarty->assign("year",$datesParamArr[3]);
		$smarty->assign("profileName",$name);

		$source ="VDM1a".$discount;
		$smarty->assign("source",$source);
		$smarty->assign("discount",$discount);
		$msgTxt =$smarty->fetch("../mailer/vd_mailer.htm");
	}
	else{
		$source ="VDM2a".$discount;
        	$msgTxt="Hi, <br><br>
                Thank you for being a registered member on Jeevansathi.com. It is our prime focus to help you find a  suitable match.<br><br>

                Becoming a paid member will allow you to connect with your future Jeevansathi through the medium of your choice - Phone, E-mail, and Chat. You can view verified phone numbers, send personalized messages, and initiate unlimited chats.<br><br>

                As a token of appreciation for being a loyal member of our website, we are offering you a special discount of <b>$discount%</b> on Jeevansathi.You may avail the offer by visiting our <a href='$SITE_URL/profile/mem_comparison.php?from_source=$source' target='_blank'> paid membership page </a> or by calling one of our executives at 18004196299/0120-4393500 before <b>$discountEndDate</b>. <br><br>

		Don't miss it, this offer is going to expire in just 3 days !!<br><br>Thanks,<br>Team Jeevansathi<br>";
	}
        $sendStatus =send_email($to_email,$msgTxt,$subject,$from);
}

// function to send the sms to the user
function sendSms_dis($mobile,$msgTxt,$profileid)
{
        $from="9870803838";
        $message=rawurlencode($msgTxt);
        $table="newjs.SENT_VERIFICATION_SMS";
	$sms_type ='D';
	$sms_key ='VARIABLE_DISCOUNT';
        send_sms($message,$from,$mobile,$profileid,$table,'',$sms_type,$sms_key);
}

// Validations added for the mobile numbers 
function mobileNumberChecks($number,$db_slave='')
{
	if(!is_numeric($number))
		return false;

	$number =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/","",$number),-10);
	if(strlen($number)!='10')
		return false;

	if($number<7000000000)
		return false;

	$sqlJunk ="select count(*) cnt from newjs.PHONE_JUNK WHERE PHONE_NUM='$number'";
        $resJunk = mysql_query($sqlJunk,$db_slave) or die("$sqlJunk".mysql_error($db_slave));
        $rowJunk = mysql_fetch_array($resJunk);
        if($rowJunk['cnt']>0)
                return false;
	return $number;
}

// other conditions to filter the profile
function filterProfile($profileid,$number,$db_slave='')
{
	$number =mobileNumberChecks($number,$db_slave);	
	if(!$number)
		return false;
	
	/*$sql_al = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
        $res_al = mysql_query($sql_al,$db_slave) or die("$sql_al".mysql_error($db_slave));
        if($row_al = mysql_fetch_array($res_al)){
        	if($row_al["MEMB_CALLS"]=='U' || $row_al["OFFER_CALLS"]=='U')
			return false;
        }*/

        $sqlNegative = "SELECT PROFILEID FROM incentive.NEGATIVE_PROFILE_LIST WHERE MOBILE IN('$number','0$number','91$number')";
        $resNegative = mysql_query($sqlNegative,$db_slave) or die("$sqlNegative".mysql_error($db_slave));
       	$rowCnt =mysql_num_rows($resNegative); 
	if($rowCnt>0)
		return false;
	return $number;	
}	

// Negative Treatment Filter
function negativeTreatmentFilter($profileid,$db_slave='')
{		
	$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db_slave);
	$spamParamaters['FLAG_OUTBOUND_CALL']=1;
	if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($profileid,$spamParamaters))
		return true;
	return false;
}

// function to get JPROFILE alerts
function getJprofileAlerts($profileid,$db_slave='')
{
        $sql_al = "SELECT MEMB_MAILS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
        $res_al = mysql_query($sql_al,$db_slave) or die("$sql_al".mysql_error($db_slave));
        if($row_al = mysql_fetch_array($res_al))
		return $row_al;
	return;
}

// function to get the profile name
function getUserName($profileid,$db_slave='')
{
	$sqlName ="select NAME from incentive.NAME_OF_USER where PROFILEID='$profileid'";
	$resName =mysql_query($sqlName,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	if($rowName=mysql_fetch_array($resName))
		return trim($rowName['NAME']);
	return;

}

// function to get the profile name
function updateVD($profileid,$status,$db_master)
{
        $sql_1 ="update billing.VARIABLE_DISCOUNT SET SENT='$status' WHERE PROFILEID='$profileid'";
        $resName =mysql_query($sql_1,$db_master) or logError("Due to a temporary problem your request could not be processed.");
}



?>
