<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_more)
{
	$dont_zip_more=1;
	ob_start("ob_gzhandler");
}
//end of it

$path = $_SERVER['DOCUMENT_ROOT'];

include_once($path."/profile/connect.inc");
include_once($path."/profile/screening_functions.php");
include_once($path."/profile/cuafunction.php");
include_once($path."/profile/hits.php");
include_once($path."/profile/registration_functions.inc");
include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
$db = connect_db();
$protect_obj=new protect;
$reg_auth=$protect_obj->authenticate_reg1($reg_checksum,$from_p1);
if(!$reg_auth){
	header("Location: ".$SITE_URL."/profile/registration_page1.php");
	exit;
}
$reg_id=$reg_auth['REGID'];
$LIVE_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/images_try/liveperson";
$smarty->assign("LIVE_CHAT_URL",$LIVE_CHAT_URL);
$smarty->assign("TIEUP_SOURCE",$source);
$smarty->assign("REGISTRATION",1);
if($submit){
				$sql="select * from newjs.REGISTRATION_PAGE1 where REGID=".$reg_id;
				$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$row=mysql_fetch_assoc($res);
				$height=$row['HEIGHT'];
				$city_residence=$row['CITY_RES'];
				$gender=$row['GENDER'];
				$relationship=$row["RELATION"];
				$mstatus=$row['MSTATUS'];
				$mtongue=$row['MTONGUE'];
				$has_children=$row['HAVECHILD'];
				$promo=$row['PROMO'];
				$email=$row['EMAIL'];
				$secondary_source=$row['SEC_SOURCE'];
				$religion_val=$row['RELIGION'];
				$tieup_source=$row['SOURCE'];
				$date_of_birth=$row['DTOFBIRTH'];
				$ip=$row['IPADD'];
				$dob_arr=explode("-",$date_of_birth);
				$year=$dob_arr[0];
				$month=$dob_arr[1];
				$day=$dob_arr[2];
				$caste=$row['CASTE'];
				$password=$row['PASSWORD'];
				$country_res=$row['COUNTRY_RES'];
				$country_residence=$row['COUNTRY_RES'];
				$converted=$row['CONVERTED'];
				$source=$row['SOURCE'];
				$showmobile='Y';
				$service_email='S';
				$service_sms='S';
				$service_call='S';
				$memb_mails='S';
				$memb_sms='S';
				$memb_ivr='S';
				$now = date("Y-m-d G:i:s");
				$today=date("Y-m-d");
				$age = getAge($date_of_birth);
				$is_error=0;
				if(!$country_code_mob)
					$country_code_mob=$country_code_mobile;
                        if(!is_numeric($mobile))
                        $mobile = "";
					if(!$mobile)
                        {
							$is_error++;
                        }
                        else
                        {
                                if($country_code_mob=="")
								{
                                        $is_error++;
					$smarty->assign("COUNTRY_CODE_MOBILE_ERR",'1');
                                }
                                elseif(check_country_code($country_code_mob,'ISD'))
								{
                                        $is_error++;
					$smarty->assign("COUNTRY_CODE_MOBILE_ERR",'1');
                                }
 				elseif($mobile && checkrphone($mobile))
                                {
                                        $is_error++;
					$smarty->assign("MOBILE_ERR",'2');
                                }
                        }
						if($is_error){
							$smarty->assign("phone_err",'1');
							$smarty->assign("COUNTRY_CODE",$country_code_mob);
							$smarty->assign("MOBILE",$mobile);
						}
						else{
				if($converted=='Y'){
					$data=authenticated();
					if($data['PROFILEID']){
						$profileid=$data['PROFILEID'];
					    $username=$data['USERNAME'];
						$country_code = explode('+',$country_code_mob);
						$country_code = $country_code[1];
						$sql1="update newjs.JPROFILE set PHONE_MOB='$mobile', ISD='$country_code' where PROFILEID=$profileid";
						$res=mysql_query_decide($sql1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					    include('registration_page2.php');
die;
					}
					else{
						header("Location: ".$SITE_URL."/profile/registration_page1.php?source=$source");
						exit;
					}
				}
							$sql="update newjs.REGISTRATION_PAGE1 set CONVERTED='Y' where REGID=$reg_id";
							$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
							$jpartnerObj=new Jpartner;
							$mysqlObj=new Mysql;
							include_once("registration_submit.inc");
						}
}
if(!$is_error){
				$sql="select COUNTRY_RES,ISD from newjs.REGISTRATION_PAGE1 where REGID=".$reg_id;
				$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$row=mysql_fetch_assoc($res);
				if($row['COUNTRY_RES']==51)
					$smarty->assign("COUNTRY_INDIA",1);
				$smarty->assign("COUNTRY_CODE",$row['ISD']);
}

$smarty->display("registration_pg1b.htm");
if($zipIt && !$dont_zip_now)
	ob_end_flush();
?>
