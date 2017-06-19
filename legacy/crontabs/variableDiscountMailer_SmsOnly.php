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
//$todayDate='2013-11-25';

// new csv definition
$fileArr =array("0"=>"fp0","10"=>"fp1","15"=>"fp2","20"=>"fp3","25"=>"fp4","30"=>"fp5","35"=>"fp6","40"=>"fp7","45"=>"fp8","50"=>"fp9","55"=>"fp10","60"=>"fp11");
foreach($fileArr as $key=>$val){	
	$filePath		="/uploads/csv_files/vd_discount_sms_"."$key"."_".date("d-m-Y").".csv";
	$filename      		=JsConstants::$docRoot.$filePath;
	$fileNameArr[$key]   	=fopen($filename,"w+");
	if(!$fileNameArr[$key])
	        die("no file pointer");
	$filePathArr[$key]	=$filePath;
}
// ends

// script start
$sql ="SELECT PROFILEID,DISCOUNT,SDATE,EDATE,SENT FROM billing.VARIABLE_DISCOUNT WHERE SDATE='$todayDate'";
$res =mysql_query($sql,$db_master) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
        $profileid      =$row["PROFILEID"];
        $discount       =$row["DISCOUNT"];
	$sdate		=$row['SDATE'];
	$edate		=$row["EDATE"];
	$last4thDay	=date("Y-m-d",strtotime("$edate -3 days"));
	$discountEndDate=date("d-M-Y",strtotime($edate));		

	if($sdate==$todayDate){
		$msgTxt ="Congratulations! You are selected for a special discount of $discount% by Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500 before $discountEndDate";
	}
	elseif($last4thDay==$todayDate){
                $msgTxt ="Last 4 days left to avail your exclusive discount of $discount% on Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500";
	}
	elseif($edate==$todayDate){
                $msgTxt ="Don't Miss it!! Your special discount of $discount% on Jeevansathi.com is ending today. Avail it online or call us at 18004196299/0120-4393500";
	}
	if(!$msgTxt)
		continue;

        $sqlJ ="SELECT PHONE_MOB,EMAIL,PHONE_FLAG,GET_SMS,SUBSCRIPTION,AGE,GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND ACTIVATED IN('Y','H')";
	$resJ =mysql_query($sqlJ,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	if($rowJ=mysql_fetch_array($resJ))
	{
		$phoneMob 	=$rowJ["PHONE_MOB"];
		$email		=$rowJ["EMAIL"];
		$phoneFlag	=$rowJ["PHONE_FLAG"];
		$getSms		=$rowJ["GET_SMS"];
		$subscription	=$rowJ['SUBSCRIPTION'];
		$ageVal         =$rowJ['AGE'];
		$genderVal      =$rowJ['GENDER'];
		$subArr		=@explode(",",$subscription);		
	
		// currently paid check
		if($genderVal=='M' && $ageVal<=23)
			continue;		
		if(!in_array("F",$subArr) && !in_array("D",$subArr)){

			// negative treatmet filter		
			$negativeFilterReq =negativeTreatmentFilter($profileid,$db_slave);
			if(!$negativeFilterReq){
				if($phoneMob && $msgTxt && $phoneFlag!='I' && $getSms!='N'){
					$fieldVal =filterProfile($profileid,$phoneMob,$db_slave);
					if($fieldVal){
						sendSms_dis($phoneMob,$msgTxt,$profileid,$discount);
					}	
					unset($fieldVal);
				}
			}	
		}
	}
	unset($subArr);
	unset($msgTxt);
}
// close of all files	
foreach($fileNameArr as $key=>$val)
	fclose($val);

//script ends 

// function to send the sms to the user
function sendSms_dis($mobile,$msgTxt,$profileid,$discount)
{
	global $fileNameArr;
	$line="$mobile"."\n";
	fwrite($fileNameArr[$discount],$line);
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

?>
