<?php
//Mail Builder test.

include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(16, new lime_output_color());
$inputM2=array (2077642,967319,696927,2132920);
//EmailSender class testing code

$profileId=144111;
$gender="F";
$maritalStatus="F";
$agentName="shiv";
$pswrdsDbObj=new jsadmin_PSWRDS();
$agentDetails=$pswrdsDbObj->getExecutiveDetails($agentName);

$emailSender = new EmailSender(MailerGroup::SCREENING_KYC,1776);
$emailTpl=$emailSender->setProfileId($profileId);
$smartyObj = $emailTpl->getSmarty();

$smartyObj->assign("relationManagerPicUrl",$agentDetails["PHOTO_URL"]);
$smartyObj->assign("relationManagerNumber",$agentDetails["PHONE"]);
$smartyObj->assign("relationManagerName",$agentDetails["FIRST_NAME"]." ".$agentDetails["LAST_NAME"]);
$smartyObj->assign("gender",$gender);
$smartyObj->assign("maritalStatus",$maritalStatus);
//remaining toll number and link name 

//$tpl=$email_sender->getTemplate($inputM2);
//$tpl->setSuggestedProfiles();
$t->is($emailSender->send('nitesh.s@jeevansathi.com'),1,"Email sender test: checking template id");
//testProfileIdNotProvidedException();

function testProfileIdNotProvidedException(){
	global $t;
     try{
		 $email_sender=new EmailSender(1,1701);
		 $email_sender->getTemplate($inputM2);
	 }catch(ProfileIdNotProvidedException $ex){
		 $t->ok(1,1,"ProfileIdNotProvidedException Thrown");
	 }
}

