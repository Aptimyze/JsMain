<?php
define('sugarEntry',true);
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER['DOCUMENT_ROOT']."/sugarcrm");
require_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
require_once('../profile/config.php');
require_once('custom/crons/JsMessage.php');
class JsEmail_no_contact extends JsMessage{
	function sendMessage(){
	global $db;
	$subject="Access over 6 lakh profiles on Jeevansathi.com";
	$from="";
	$reply_to="";
	$timeConditionStr=$this->createTimeBoundQuery('l.date_entered',array(14,21,28,35,42));
	$lead_id_query="select id as lead_id from sugarcrm.leads as l,sugarcrm.leads_cstm as lc where l.id=lc.id_c AND lc.do_not_email_c='0' AND l.status IN (13,24,14,15,11,12,17) AND ($timeConditionStr) AND l.deleted='0' AND lc.source_c<>11"; 
//	echo "$lead_id_query<br>";
	$result_lead=$db->query($lead_id_query,true); 
	while($row=$db->fetchByAssoc($result_lead)){
		$lead_id=$row['lead_id'];
		$lead_query_string="select leads.date_entered as entry_date,leads.first_name as fname, leads.last_name as lname, lc.source_c as lead_source, leads.status, lc.age_c, lc.gender_c, lc.religion_c,lc.caste_c,lc.enquirer_email_id_c,lc.date_birth_c,lc.mother_tongue_c,leads.phone_home,leads.phone_mobile,lc.enquirer_mobile_no_c, enquirer_landline_c from sugarcrm.leads, sugarcrm.leads_cstm as lc where leads.id = '$lead_id' AND lc.id_c='$lead_id'";
		$lead_fields=$db->requireSingleRow($lead_query_string);
		//Trac 202, autofollowup mail will go to every lead that is still in auto followup status
	//	if(!$lead_fields['phone_home'] && !$lead_fields['phone_mobile'] && !$lead_fields['enquirer_mobile_no_c'] && !$lead_fields['enquirer_landline_c'])
	//	{
			$lead_fields['lead_id']=$lead_id;
			$lead_fields['count']=$this->calculateNoOfMatches($lead_fields);
			//If match count is less than 228 then make it default 228 
			if($lead_fields['count']<228)
				$lead_fields['count']=228;
			$messageToSend=$this->createMessage($lead_fields);
			if(empty($lead_fields['enquirer_email_id_c'])){
				$email_sql="select ea.email_address as email from email_addresses as ea, email_addr_bean_rel as eabr where eabr.email_address_id=ea.id AND eabr.bean_id='".$lead_id."' AND ea.invalid_email=0 AND ea.opt_out=0 AND ea.deleted = 0";
				$email=$db->getOne($email_sql);
			}else 
				$email=$lead_fields['enquirer_email_id_c'];
			if($email){
				send_email($email,$messageToSend, $subject,$from,"","","","","","",1,$reply_to);
			}
	//	}
	}
	}

function createMessage($propertyArr){
	global $RELIGIONS,$CASTE_DROP_SMALL,$MTONGUE_DROP_SMALL,$SITE_URL;
	$source_arr=array(
		 1 => 'from you',
	     2 => 'from You',
	     3 => 'from a website',
	     4 => 'from Newspaper classified listing',
	     5 => 'from your email',
	     6 => 'from Samaj/Societies Booklet',
	     7 => 'from Samaj / Societies Online',
	     8 => 'from a website',
	     9 => 'from a website',
	    10 => '',
	);
	$entry_dateArr=explode(" ",$propertyArr['entry_date']);
	$entry_date=$entry_dateArr[0];
	$message="Hello,<BR><br>
		      We have received your details ".$source_arr[$propertyArr['lead_source']]." on ".$entry_date.".<BR><br>
			  On the basis of profile details provided for you, we have searched the <a href='http://www.jeevansathi.com'>Jeevansathi.com</a> database<br> for ";
	if($propertyArr['religion_c']){ 
		    $religion=$RELIGIONS[$propertyArr['religion_c']];
			$message.=$religion;
			if($propertyArr['caste_c']){
				$casteArr=explode("_",$propertyArr['caste_c']);
				$caste=$CASTE_DROP_SMALL[$casteArr[1]];
				$caste=str_replace("-","",$caste);
				$message.="/".$caste;
			}
	}
	if($propertyArr['gender_c']=='F')
		$opgender="male";
    else
		$opgender="female";
	if($propertyArr['date_birth_c']!='')
		$age=$this->getAge($propertyArr['date_birth_c']);
	else 
		$age=$propertyArr['age_c'];
	if($propertyArr['gender_c']=='F'){
		if($age)
		$message.=" male profiles above the age of ".$age;
		else $message.=" male profiles";
	}
	else{ 
		if($age)
		$message.=" female profiles below the age of ".$age;
	    else $message.=" female profiles";
	}
	if($SITE_URL==JsConstants::$ser6Url)
		$SITE_URL=JsConstants::$siteUrl;
	if($propertyArr['status']=='24')
		$urlToSend="<a href=\"".$SITE_URL."/P/sugarcrm_registration/registration_page1.php?source=ofl_prof&record_id=".$propertyArr['lead_id']."&sugar_incomplete=Y\">complete registration";
	else
		$urlToSend="<a href=\"".$SITE_URL."/P/sugarcrm_registration/registration_page1.php?source=ofl_prof&record_id=".$propertyArr['lead_id']."\">register";
	if($propertyArr['mother_tongue_c'])
		$message.=" from ".$MTONGUE_DROP_SMALL[$propertyArr['mother_tongue_c']]." community";
	$message.=".<br><br>
		We are happy to inform you that we have found ".$propertyArr['count']." ".$opgender." profiles that match your criteria.<br><br>
		To get the details of these profiles, please $urlToSend with Jeevansathi.com.</a><br><br>
		Once you register, you will get profiles (Daily Recommendations) in your inbox which will be as per your preferences.
		<br> You will also be able to search and express interest to profiles on Jeevansathi.com.<br><br>
		   If you need any assistance, please do get in touch with us.<br><br>
			Best regards,<br>
			Jeevansathi.com Team<br><br><br>
			To unsubscribe please click <a href=\"".$SITE_URL."/sugarcrm/unsubscribe.php?id=".$propertyArr['lead_id']."&source=lma\">here</a>";
	return $message;
}
}

$jsemail=new JsEmail_no_contact();
$jsemail->sendMessage();
?>
