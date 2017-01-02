<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class PhoneOpsListTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'ops';
    $this->name             = 'PhoneOpsList';
    $this->briefDescription = 'send verification email to all the activated and unverified profiles';
    $this->detailedDescription = <<<EOF
      The [PhoneOpsListTask|INFO] task collects data and send verification email to all the activated and unverified profiles.
      Call it with:

      [php symfony ops:PhoneOpsList] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'operations'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
/**********Temporary Tracking*************/
$ptime = date("Y-m-d h:i:s");
$fp = fopen("/tmp/phone_ops.txt","a+");
fwrite($fp,$ptime."\n");
fclose($fp);
/*****************************************/
	$fNamesArr      =csvFields::$csvName;
	$fName          =$fNamesArr["PHONE_DIALER"];
	$fileName       =JsConstants::$docRoot."/uploads/csv_files/$fName.dat";
	if(file_exists($fileName)) unlink ($fileName);
	$hour = date("H");
	if(JsConstants::$whichMachine!='local' && JsConstants::$whichMachine!='test')
	{
		$allowedHours = array("21","22","23","00","01","02","03","04","05","06","07","08","09","10");
		if(!in_array($hour,$allowedHours)){
		      successfullDie();
		}
	}
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$this->phoneOpsDialerDataObj = new incentive_PHONE_OPS_DIALER_DATA;
	$noOfMin = 35;
	$this->date = date('Y-m-d H:i:s',mktime(date('H'),date('i')-$noOfMin,date('s'),date('m'),date("d"),date("Y")));
	$this->datePrev = date('Y-m-d H:i:s',mktime(date('H'),date('i')-(2*$noOfMin),date('s'),date('m'),date("d"),date("Y")));
	$lastUpdatedDate = $this->phoneOpsDialerDataObj->getLastDate();
	if($lastUpdatedDate)
	{
		if(strtotime($this->datePrev) > strtotime($lastUpdatedDate))
			$this->date = $lastUpdatedDate;
	}
	$profiles = $this->getUnverifiedProfilesData();
	if(is_array($profiles))
	{
		$profiles = $this->setName($profiles);
	        $orgTZ = date_default_timezone_get();
       	        date_default_timezone_set("Asia/Calcutta");
		$setDate = date('Y-m-d H:i:s');
		date_default_timezone_set($orgTZ);
		$this->phoneOpsDialerDataObj->insert($profiles,$setDate);
		$csvO = new csvGenerationHandler;
		$csvO->generateCSV("PHONE_DIALER",$setDate);

		//Code added to copy phone dialer csv file to Dialer
		usleep(3000000);
		$sourceDir = JsConstants::$docRoot.'/uploads/csv_files/PHONE_DIALER_DATA.dat';
		$destDir = JsConstants::$docRoot.'/uploads/csv_files/fpdialer/';
		passthru("cp $sourceDir $destDir", $return_var);
		if($return_var){
			// error in copy command
			/**********Temporary Tracking*************/
			$ptime = date("Y-m-d h:i:s");
			$fp = fopen("/tmp/phone_ops_copy.txt","a+");
			fwrite($fp,$ptime."\n");
			fclose($fp);
			/*****************************************/
		}
	}
  }
  private function setName($profiles)
  {
	$profileIds = array_keys($profiles);
	$profileidStr['PROFILEID'] = implode(",",$profileIds);
	$nameOfUserObj = new incentive_NAME_OF_USER;
	$names = $nameOfUserObj->getArray($profileidStr,'','',"NAME,PROFILEID");
	if(is_array($names))
	{
		foreach($names as $k=>$v)
			$profiles[$v['PROFILEID']]['NAME']=$v['NAME'];
	}
	return $profiles;
  }
  private function getUnverifiedProfilesData()
  {
	$phoneJunkObj = new PHONE_JUNK;
	$numbers = $phoneJunkObj->getJunkNumbers();
	$jprofileObj = new JPROFILE;
        $mainAdminLogObj =new MAIN_ADMIN_LOG;
        $profilesDetails =$mainAdminLogObj->getUnverifiedActivatedProfiles($this->date,'','',"I",'91',$return="PHONE_MOB,PHONE_RES,STD,EMAIL,SUBMITED_TIME");
	if(!is_array($profilesDetails))
		return false;
	foreach($profilesDetails as $k=>$v)
	{
		$profiles[]=$v['PROFILEID'];
		$profileData[$v['PROFILEID']]=$v;
		if($v['PHONE_MOB']==''||in_array($v['PHONE_MOB'],$numbers))
		{
			$profileData[$v['PROFILEID']]['PHONE_MOB']='';
			if($v["PHONE_RES"]!='')
			{
				$phoneRes = $v['STD'].$v['PHONE_RES'];
				if(!in_array($phoneRes,$numbers))
					$profileData[$v['PROFILEID']]['PHONE_MOB']=$phoneRes;
			}
		}
	}
	$jprofileContactObj = new ProfileContact();
	$valueArray=array();
	$excludeArray=array();
	$neglectedProfiles = array();
	$valueArray['PROFILEID']=implode(",",$profiles);
	$profilesList = $jprofileContactObj->getArray($valueArray,"","","PROFILEID,ALT_MOBILE,ALT_MOB_STATUS");
	foreach($profilesList as $k=>$v)
	{
		if($v['ALT_MOB_STATUS']!='Y')
			$neglectedProfiles[] = $v['PROFILEID'];
		if($profileData[$v['PROFILEID']]['PHONE_MOB']==''&&!in_array($v['ALT_MOBILE'],$numbers))
			$profileData[$v['PROFILEID']]['PHONE_MOB']=$v['ALT_MOBILE'];
	}
	foreach($profiles as $k=>$v)
	{
		if(!in_array($v,$neglectedProfiles)&&$profileData[$v]['PHONE_MOB']!='')
			$finalProfiles[$v] = $profileData[$v];
	}
	return $finalProfiles;
  }
}
