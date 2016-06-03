<?php
if (JsConstants::$whichMachine != 'matchAlert') {
	include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
}

class IncompleteMailerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));
$this->addArguments(array(
       new sfCommandArgument('taskNumber', sfCommandArgument::REQUIRED, 'My argument'),));
    // // add your own options here
	$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));
    $this->namespace        = 'cron';
    $this->name             = 'IncompleteMailer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [IncompleteMailer|INFO] task does things.
Call it with:

  [php symfony IncompleteMailer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$taskNumber = $arguments["taskNumber"];
	if($taskNumber==0)
	 {
		$date1=date('Y-m-d',time() + (7 * 24 * 60 * 60));
		$date2=date('Y-m-d',time() + (4* 24 * 60 * 60));
		 $fto_array=FTOStateHandler::getProfilesInSubstateArray(array(FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD,FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD,FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD,FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD,FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT),array($date1,$date2));
		 $fields="PROFILEID,USERNAME,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,ENTRY_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,INCOME,CITY_BIRTH,BTIME,GOTHRA,FATHER_INFO,MOTHER_OCC,PARENT_CITY_SAME,FAMILY_INCOME,T_BROTHER,T_SISTER,WEIGHT,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
		 if(is_array($fto_array)){
			 foreach($fto_array as $profileid){
				$profileObj=new LoggedInProfile('',$profileid);
				$profileObj->getDetail('','',$fields);
				$unfilled_layers_obj=new EditOnFtoContactConfirmation($profileObj,1);
				$unfilled_layers=$unfilled_layers_obj->getUnfilledLayers();				
				if(is_array($unfilled_layers)){
				if(in_array('CUH',$unfilled_layers)){
						$mail_id="1763";//Upload horoscope mailer
				}
				else
					$mail_id="1760";//Complete your profile lifestyle mailer
				}
				if($mail_id){
				$email_sender=new EmailSender(3,$mail_id);
				$email_sender->setProfile($profileObj);
				$p_list=new PartialList;
				$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
				$email_sender->send("",$p_list);
				}
			 }
		 }
	 $date_arr=array(1,3,5);
	}
	elseif ($taskNumber==1)
	{
		$date_arr=array(9,11,13,15,20);
	}
	elseif ($taskNumber==2)
	{
		$date_arr=array(27,34,41,48,7);
	}
	elseif ($taskNumber==3)
	{
		$date_arr=array(55,62,69,76,83);
	}
     $entry_dt_cond= $this->createDateCondition($date_arr);
	 $jprofile_pdo=new JPROFILE('newjs_slave');
	 $profileid_arr= $jprofile_pdo->getProfileIdsThatSatisfyConditions(array('INCOMPLETE'=>"'Y'"),$entry_dt_cond);
	 $email_sender=new EmailSender(3,1774);//Mailer to incomplete profiles
	if(is_array($profileid_arr) && count($profileid_arr)){
		foreach($profileid_arr as $profileid){
			unset($outputInputM2);
		  	$tpl= $email_sender->setProfileId($profileid);
		   	$outputInputM2 = SearchCommonFunctions::getDppMatches($profileid,'fto_offer',SearchSortTypesEnums::popularSortFlag);
			$inputM2 = $outputInputM2["SEARCH_RESULTS"];
			$p_list=new PartialList;
			$p_list->addPartial('suggested_profiles1','suggested_profiles1',$inputM2,false);
			$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
			$email_sender->send('',$p_list);
			if($taskNumber==0)
			{
				$profileObj=$email_sender->getProfile();
				$date_str=date('Y-m-d',time() - (1* 24 * 60 * 60));
				if(substr($profileObj->getENTRY_DT(),0,10)==$date_str)
				{
					$sms= new InstantSMS("INCOMPLETE_TASK0",$profileid);
					$sms->send();
				}
			}
		}
	}
	//SendMail::send_email("nitesh.s@jeevansathi.com,nikhil.dhiman@jeevansathi.com","$count1 mail sent out.","Incomplete Mailer $taskNumber cron completed");
  }
  private function createDateCondition($date_arr){
	  $condition_str="( ";
	  foreach($date_arr as $day){
		  $date_str=date('Y-m-d',time() - ($day* 24 * 60 * 60));
		  $condition_str.="(ENTRY_DT BETWEEN '$date_str 00:00:00' AND '$date_str 23:59:59') OR ";
	  }
	  $condition_str=substr($condition_str,0,-3);
	  $condition_str.=" ) ";
	  return $condition_str;
  }
}
