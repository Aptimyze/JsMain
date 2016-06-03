<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : atsDiscountMailer_Sms.php 
* DESCRIPTION   : Cron script to send the email and sms for the profiles whoses discount starts from today
*****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");
$path = $_SERVER['DOCUMENT_ROOT'];
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once($path."/classes/NEGATIVE_TREATMENT_LIST.class.php");

$db_slave = connect_slave();

// SMS Dates
$todayDate=date("Y-m-d");

$day1Date =date("Y-m-d",strtotime("$todayDate +6 days"));
$day4Date =date("Y-m-d",strtotime("$todayDate +3 days"));
$day7Date =$todayDate;

// Mailer Dates
$day2Date =date("Y-m-d",strtotime("$todayDate +5 days"));
$day5Date =date("Y-m-d",strtotime("$todayDate +2 days"));

$sql ="SELECT vb.PROFILEID,vb.DISCOUNT,vb.EDATE FROM billing.VARIABLE_DISCOUNT vb,MIS.ATS_DISCOUNT ats WHERE vb.EDATE IN('$day1Date','$day4Date','$day7Date','$day2Date','$day5Date') AND vb.PROFILEID=ats.PROFILEID";
$res =mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
        $profileid      =$row["PROFILEID"];
        $discount       =$row["DISCOUNT"];
	//$sdate	=$row["SDATE"];
	$edate		=$row["EDATE"];
	$discountEndDate=date("d-m-Y",strtotime($edate));	

	if($edate==$day1Date){
		$msgTxt ="Congratulations! You are selected for a special discount of $discount% by Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500 before $discountEndDate";
		$phoneSet =true;
	}
	elseif($edate==$day4Date){
                $msgTxt ="Last 4 days left to avail your exclusive discount of $discount% on Jeevansathi.com. Avail it online or call us at 18004196299/0120-4393500";
		$phoneSet =true;
	}
	elseif($edate==$day7Date){
                $msgTxt ="Don't Miss it!! Your special discount of $discount% on Jeevansathi.com is ending today. Avail it online or call us at 18004196299/0120-4393500";
                $phoneSet =true;
	}
        elseif($edate==$day2Date){
		$subject ="Congratulations! You are selected for a special discount by Jeevansathi.com";
                $phoneSet =false;
        }
        elseif($edate==$day5Date){
		$subject ="Hurry, last 3 days left to avail your exclusive discount at Jeevansathi.com";
		$extraMsg="Don't miss it, this offer is going to expire in just 3 days !!";
                $phoneSet =false;
        }

	if($phoneSet)
		$fieldName='PHONE_MOB';
	else
		$fieldName='EMAIL';		
	
       $sqlJ ="SELECT ".$fieldName." FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	if($fieldName=='PHONE_MOB')
		$sqlJ .=" AND PHONE_FLAG!='I' AND GET_SMS!='N' AND SERVICE_MESSAGES!='U'";
	else
		$sqlJ .=" AND PROMO_MAILS!='U'";
       	$resJ =mysql_query($sqlJ,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	while($rowJ=mysql_fetch_array($resJ))
	{
		$fieldVal 	=$rowJ["$fieldName"];
		if($fieldVal && $fieldName=='PHONE_MOB'){
			$fieldVal =mobileNumberChecks($profileid,$db_slave,$fieldVal);
			if($fieldVal && $msgTxt && $profileid){
				//sendSms_dis($fieldVal,$msgTxt,$profileid);
			}
		}
		elseif($fieldVal){
			sendMail_dis($fieldVal,$subject,$discount,$discountEndDate,$extraMsg);
		}
	}
}

// function to send the email to the user 
function sendMail_dis($to_email,$subject,$discount,$discountEndDate,$extraMsg)
{
	//$to_email ="manoj.rana@naukri.com";
        $from   ="matchpoint@jeevansathi.com";
	$msgTxt="Hi, <br><br>
		Thank you for being a registered member on Jeevansathi.com. It is our prime focus to help you find a  suitable match.<br><br>

		Becoming a paid member will allow you to connect with your future Jeevansathi through the medium of your choice - Phone, E-mail, and Chat.
		You can view verified phone numbers, send personalized ,$profileidmessages, and initiate unlimited chats.<br><br>

		As a token of appreciation for being a loyal member of our website, we are offering you a special discount of <b>$discount%</b> on Jeevansathi.You may avail the offer by visiting our <a href='http://www.jeevansathi.com/profile/mem_comparison.php' target='_blank'> paid membership page </a> or by calling one of our executives at 18004196299/0120-4393500 before <b>$discountEndDate</b>. <br><br>";
	if($extraMsg)
		$msgTxt .=$extraMsg."<br><br>";
	$msgTxt .="Thanks,<br>Team Jeevansathi<br>";
        send_email($to_email,$msgTxt,$subject,$from);
}

// function to send the sms to the user
function sendSms_dis($mobile,$msgTxt,$profileid)
{
        $from="9870803838";
        $message=rawurlencode($msgTxt);
        $table="newjs.SENT_VERIFICATION_SMS";
        send_sms($message,$from,$mobile,$profileid,$table);
}

// Validations added for the mobile numbers 
function mobileNumberChecks($profileid,$db_slave,$number='')
{
	if(!$number)
		return false;

	if(!is_numeric($number))
		return false;

	$number =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/","",$number),-10);
	if(strlen($number)!='10')
		return false;

	$sql_al = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
        $res_al = mysql_query($sql_al,$db_slave) or die("$sql_al".mysql_error($db_slave));
        if($row_al = mysql_fetch_array($res_al))
        {
        	if($row_al["MEMB_CALLS"]=='U' || $row_al["OFFER_CALLS"]=='U')
			return false;
        }

	$sql_dnt_call = "SELECT COUNT(*) AS COUNT FROM incentive.DO_NOT_CALL WHERE PROFILEID='$profileid' AND REMOVED='N'";
        $res_dnt_call = mysql_query($sql_dnt_call,$db_slave) or die("$sql_dnt_call".mysql_error($db_slave));
        $row_dnt_call = mysql_fetch_array($res_dnt_call);
	if($row_dnt_call['COUNT']>0)
		return false;

	$NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db_slave);
	$spamParamaters['FLAG_INBOUND_CALL']=1;
	if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($profileid,$spamParamaters))
		return false;

	return $number;
}

?>
