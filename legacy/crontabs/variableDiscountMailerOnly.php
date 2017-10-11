<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : variableDiscountMailer_Sms.php 
* DESCRIPTION   : Cron script to send the email for the profiles whoses discount starts from today
*****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");
$path = $_SERVER['DOCUMENT_ROOT'];
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include(JsConstants::$docRoot."/classes/authentication.class.php");
include_once($path."/classes/NEGATIVE_TREATMENT_LIST.class.php");
include_once($path."/classes/Membership.class.php");
include (JsConstants::$smartyDir);
include_once($_SERVER['DOCUMENT_ROOT']."/../apps/jeevansathi/lib/MembershipHandler.class.php");

// smarty template set
$smarty = new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/mailer/");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

// connection set
$db_slave = connect_slave();
$db_master =connect_db();
$membershipObj =new Membership();

// variable parameters set
$SITE_URL = $IMG_URL =JsConstants::$siteUrl;
$smarty->assign("IMG_URL",$IMG_URL);
$smarty->assign("SITE_URL",$SITE_URL);
$todayDate=date("Y-m-d");

$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT_MAIL FROM billing.VARIABLE_DISCOUNT WHERE SDATE<='$todayDate' AND EDATE >='$todayDate' AND SENT_MAIL='N'";
$res =mysql_query($sql,$db_master) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
        $profileid      =$row["PROFILEID"];
        $discount       =$row["DISCOUNT"];
	$sdate		=$row['SDATE'];
	$edate		=$row["EDATE"];
	$status		=$row['SENT_MAIL'];
	$discountEndDate=date("d-M-Y",strtotime($edate));		
	$subject ="Congratulations! You are selected for a special discount by Jeevansathi.com";	

        $sqlJ ="SELECT PHONE_MOB,EMAIL,PHONE_FLAG,GET_SMS,SUBSCRIPTION,USERNAME,AGE,GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED IN('Y','H')";
	$resJ =mysql_query($sqlJ,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	if($rowJ=mysql_fetch_array($resJ))
	{
		$email		=$rowJ["EMAIL"];
		$subscription	=$rowJ['SUBSCRIPTION'];
		$username	=$rowJ['USERNAME'];
		$subArr		=@explode(",",$subscription);		
                $ageVal         =$rowJ['AGE'];
                $genderVal      =$rowJ['GENDER'];
	
                if($genderVal=='M' && $ageVal<=23)
                        continue;

		// Renewal check        
		$isRenewal =$membershipObj->isRenewable($profileid);
		if($isRenewal && ($isRenewal!=1)){
			$renewalFlag =true;
			continue;
		}
		// currently paid check	
		if(!in_array("F",$subArr) && !in_array("D",$subArr)){

			// negative treatmet filter		
			$negativeFilterReq =negativeTreatmentFilter($profileid,$db_slave);
			if(!$negativeFilterReq){
				if($email && $subject){
						$jAlerts =getJprofileAlerts($profileid,$db_slave);
						$profileName =getUserName($profileid,$db_slave);
						if(!$profileName)	
							$profileName =$username;					
						if($jAlerts!='U'){
							sendMail_dis($profileid,$email,$subject,$discount,$discountEndDate,$profileName);
							updateVD($profileid,$db_master);
						}
				}
			}	
		}
	}
	unset($renewalFlag);
	unset($subArr);
	unset($jAlerts);
	unset($status);
	unset($daySet);
	unset($subject);
}	

// function to send the email to the user 
function sendMail_dis($profileid,$to_email,$subject,$discount,$discountEndDate,$name)
{
	global $smarty,$SITE_URL;
        $from   ="membership@jeevansathi.com";
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
	$checksum = md5($profileid)."i".$profileid;
        $protect=new protect;
        $echecksum = $protect->js_encrypt($checksum);
	$smarty->assign("checksum",$checksum);
        $smarty->assign("echecksum",$echecksum);
	$msgTxt =$smarty->fetch("../mailer/vd_mailer.htm");
        $sendStatus =send_email($to_email,$msgTxt,$subject,$from,'','','','','','',"1",'','Jeevansathi Membership');
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
		return $row_al['MEMB_MAILS'];
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
function updateVD($profileid,$db_master)
{
        $sql_1 ="update billing.VARIABLE_DISCOUNT SET SENT_MAIL='Y' WHERE PROFILEID='$profileid'";
        $resName =mysql_query($sql_1,$db_master) or logError("Due to a temporary problem your request could not be processed.");
}



?>
