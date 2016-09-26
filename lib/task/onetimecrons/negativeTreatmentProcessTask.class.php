<?php

/*
 * This task performs negative treatment for legacy profiles.
 */

class negativeTreatmentProcessTask extends sfBaseTask
{
  protected function configure()
  {
	/*$this->addArguments(array(new sfCommandArgument('notificationKey', sfCommandArgument::REQUIRED, 'My argument')));
	$this->addArguments(array(new sfCommandArgument('noOfScripts', sfCommandArgument::REQUIRED, 'My argument')));
	$this->addArguments(array(new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument')));*/

    	$this->namespace        = 'CRM';
    	$this->name             = 'negativeTreatmentProcess';
    	$this->briefDescription = '';
    	$this->detailedDescription = <<<EOF
      The [negativeTreatmentProcess|INFO] task.
      Call it with:

      [php symfony CRM:negativeTreatmentProcess] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

		$id1 ='';
		$id2 ='';
		$typeArr =array('Abusive Chat with other members','Criminal','Detective','Escorts','Fraud','Massage Parlor','Spammer');
		$typeStr ="'".implode("','",$typeArr)."'";
		$pidArr =array();
		$phoneArr =array();
		$emailArr =array();	
	
		$negativeProfileObj =new incentive_NEGATIVE_PROFILE_LIST('newjs_slave');
		$dataArr =$negativeProfileObj->getProfileDetails($typeStr,$id1,$id2);				

		foreach($dataArr as $key=>$dataVal){

			$negativeTreatmentObj   =new negativeTreatment();
			$profileid	=$dataVal['PROFILEID'];
			$email		=$dataVal['EMAIL'];
			$isd		=$dataVal['ISD'];
			$mobile 	=$dataVal['MOBILE'];
			$stdCode	=$dataVal['STD_CODE'];
			$landline	=$dataVal['LANDLINE'];
			$comment	=$dataVal['COMMENTS'];
			$type		=$dataVal['TYPE'];
			if(!$comment)
				$comment=$type;
			
			if($isd)
				$isd    =ltrim($isd,0);
			if($stdCode)
				$stdCode	=ltrim($stdCode,0);
			if($mobile){
				$mobile    	=ltrim($mobile,0);
				$phoneNum       =$isd.$mobile;
			}
			/*
			if($landline){
				$landline    	=ltrim($landline,0);
				$landlineNum	=$isd.$stdCode.$landline;
			}*/
			if($profileid){
				if(!in_array($profileid, $pidArr)){
					$negativeTreatmentObj->addToNegative('PROFILEID',$profileid,$comment);
					$pidArr[] =$profileid;
				}
			}
			if($email){
				if(!in_array($email, $emailArr)){
					$negativeTreatmentObj->addToNegative('EMAIL',$email,$comment);
					$emailArr[] =$email;
				}
			}
			if($phoneNum){
				if(!in_array($phoneNum, $phoneArr)){
					$negativeTreatmentObj->addToNegative('PHONE_NUM',$phoneNum,$comment);
					$phoneArr[] =$phoneNum;
				}
			}
			/*
			if($landlineNum){
				$negativeTreatmentObj->addToNegative('PHONE_NUM',$landlineNum,$comment);
			}*/
			unset($negativeTreatmentObj);
		}
  }
}
