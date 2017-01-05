<?php 

$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));

include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	

			$rawDuplicateObj= new RawDuplicate();
		  	$rawDuplicateObj->setReason(REASON::NONE); 
			$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::NO); 
			$rawDuplicateObj->addExtension('MARKED_BY','SYSTEM');
	  		$rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
	  		$rawDuplicateObj->addExtension('IDENTIFIED_ON',date('Y-m-d H:i:s'));
	  		$rawDuplicateObj->setComments("None");

		 $duplicateTempObj=new duplicates_DUPLICATE_TEMP_TABLE('newjs_masterRep');
		 (new DUPLICATE_PROFILE_LOG('newjs_masterRep'))->deleteProbableDuplicates();
	  	for($i=0;;$i+=1000)
	 {	


	 		unset($valueArray);
	 		unset($arr);
		  	unset($jprofileArray);
		  	unset($profileArray);


		  	$arr=(new DUPLICATE_PROFILE_LOG('newjs_slave'))->fetchConfirmedDuplicates(1000,$i);
		  	if(!$arr) break;
		  	$valueArray['PROFILEID']="";
		  	foreach ($arr as $key => $value) {
		  		# code...
		  		$valueArray['PROFILEID'].=($value['PROFILE1'].",".$value['PROFILE2'].",");
		  	}

		  	$valueArray['PROFILEID']=substr($valueArray['PROFILEID'],0, -1);

		  	$jprofileArray=JPROFILE::getInstance('newjs_slave')->getArray($valueArray,"",'',"PROFILEID,ACTIVATED,GENDER,ENTRY_DT");
		  	foreach ($jprofileArray as $key => $value) 
		  		{

		  			$profileArray[$value['PROFILEID']]=$value;

		  		}
                        unset($jprofileArray);
                        $duplicateTempArray = $duplicateTempObj->getProfileArray($valueArray['PROFILEID']);
	foreach ($arr as $key => $value) {

		# code...
			

			unset($timeStamp1);	
			unset($timeStamp2);

			$timeStamp1=JSstrToTime($profileArray[$value['PROFILE1']]['ENTRY_DT']);
			$timeStamp2=JSstrToTime($profileArray[$value['PROFILE2']]['ENTRY_DT']);

                        
			if($timeStamp1 < $timeStamp2)
			{	
				$profile1=$value['PROFILE1'];
				$profile2=$value['PROFILE2'];
			}
			else 
			{
				$profile2=$value['PROFILE1'];
				$profile1=$value['PROFILE2'];
			
			}

                        
		  if(($profileArray[$profile2]['GENDER'] != $profileArray[$profile1]['GENDER']) || ($profileArray[$profile2]['ACTIVATED']=='D') || ($profileArray[$profile1]['ACTIVATED']=='D')  )
		  {

		  	$rawDuplicateObj->setProfileid1($profileArray[$profile1]['PROFILEID']); 			
		  	$rawDuplicateObj->setProfileid2($profileArray[$profile2]['PROFILEID']); 			
			DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
			DuplicateHandler::MarkNotDuplicate($rawDuplicateObj);
                        if(!$duplicateTempArray[$profile2])
                        {
                            
                            $duplicateTempObj->insertEntry($profile2,'Y');
                            $duplicateTempArray[$profile2] = 'Y';
                            
                        }
                        
		}	
		else {
                    if(!$duplicateTempArray[$profile2] || $duplicateTempArray[$profile2]=='Y' )
                        {
                            
                            $duplicateTempObj->insertEntry($profile2,'N');
                            $duplicateTempArray[$profile2] = 'N';
                            
                        }
                        

		}
                    
	}
        unset($duplicateTempArray);
}
			$notDuplicateObj=new DUPLICATES_PROFILES();
			$IntlObj =  new INCENTIVE_NEGATIVE_TREATMENT_LIST;
                        $duplicateTempArray=$duplicateTempObj->getNonDuplicateProfiles();
		foreach ($duplicateTempArray as $key => $value) {
			# code...
                                $notDuplicateObj->removeProfileAsDuplicate($value);
  				$IntlObj->deleteRecord($value);
	  		


		}
