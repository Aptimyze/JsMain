<?php

/*
 * Author: Prinka Wadhwa
 * This task gets all the profiles for which duplication check needs to be done and runs duplication checks for these profiles.
*/

class cronDuplicationTask extends sfBaseTask
{
  protected function configure()
  {

$this->addArguments(array(
	new sfCommandArgument('profileType', sfCommandArgument::REQUIRED, 'My argument'),
	new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
	new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
//	new sfCommandArgument('profileType', sfCommandArgument::OPTIONAL, 'My argument', 'NEW'),
	));

$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));


    $this->namespace        = 'cron';
    $this->name             = 'cronDuplication';
    $this->briefDescription = 'runs duplication check algorithm for various profiles';
    $this->detailedDescription = <<<EOF
The [cronDuplication|INFO] task gets all the profiles for which duplication check needs to be done runs that particular duplication check.
Call it with:

  [php symfony cron:cronDuplication NEW/EDIT totalNoOfScripts currentScriptNo] 
Pass the argument as 'NEW' if the cron is to be run for new profiles only and 'EDIT' if the duplication check is to be run for other profiles.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	$profileType = $arguments["profileType"]; // NEW / EDIT
	$totalScripts = $arguments["totalScripts"]; // total no of scripts
	$currentScript = $arguments["currentScript"]; // current script number

        $fp=fopen('/tmp/duplicationCron__'.$profileType.$currentScript . ".lock","w+");

$hhhh = date("H");
if(JsConstants::$whichMachine!='local' && JsConstants::$whichMachine!='test')
{
	if(!in_array($hhhh,array(16,17,18,19,20,21)))
        	die;
}
        if($fp)
        {
                $gotlock=flock($fp,LOCK_EX + LOCK_NB);
                if(!$gotlock)
                {
                        echo "cannot get lock. exiting";
                        fclose($fp);
                        exit;
                }
        }
        else
        {
                echo "cannot get lock. exiting";
                exit;
        }


	$duplicateProfObjSlave = new duplicates_DUPLICATE_CHECKS_FIELDS("newjs_slave");
	$duplicateProfObjMaster = new duplicates_DUPLICATE_CHECKS_FIELDS();

	$profilesToBeChecked = $duplicateProfObjMaster->getProfilesForDuplicationCheck($profileType,$totalScripts,$currentScript);
//die;

	if(is_array($profilesToBeChecked))
	{
		$parameters = FieldMap::getFieldLabel("duplicationFieldsVal","","1");
		$duplicationChecks = FieldMap::getFieldLabel("duplicationCheck","","1");
		$pictureService = new PictureService("");
		$photosScreenedFlag = $pictureService->getPhotosScreenedFlag();
		$photosToBeScreenedFlag = $pictureService->getPhotosToBeScreenedFlag();


		if($profileType == 'EDIT')
		{
			$crawlerFieldsToBeScreened = FieldMap::getFieldLabel("crawlerDuplicationScreeningFields","","1");
			$crawlerFieldsNotToBeScreened = FieldMap::getFieldLabel("crawlerDuplicationFixedFields","","1");
			$phoneFieldsToBeScreened = FieldMap::getFieldLabel("phoneDuplicationScreeningFields","","1");
			$phoneFieldsNotToBeScreened = FieldMap::getFieldLabel("phoneDuplicationFixedFields","","1");
			$allScreeningValues = FieldMap::getFieldLabel("flagval","","1");
		}

		foreach($profilesToBeChecked as $indexVal => $profileData)
		{
$hhhh = date("H");
if(JsConstants::$whichMachine!='local' && JsConstants::$whichMachine!='test')
{
	if(!in_array($hhhh,array(16,17,18,19,20,21)))
  	      die;
}
			$allValuesScreened=0;
			$profileid = $profileData["PROFILEID"];
			$timestamp = $duplicateProfObjSlave->getProfileTimestamp($profileid,$profileType);
			if($profileData["TIMESTAMP"] == $timestamp)
			{
				$value = $profileData["FIELDS_TO_BE_CHECKED"];
				unset($changedBits);
				/*
				getting list of fields editted by the profile which will be used for deciding the logic(s) against which duplication need to be checked => getting the bits that are 'set' in $value. corresponding names are returned from the array $parameters.
				*/
				foreach($parameters as $key=>$val)
				{
					/* list the field edited */
					if($val  < sizeof($parameters)-1)
					{
						if(Flag::isBitSet($val,$value))
						{
							$changedBits["$key"] = "$val";
						}
					}
				}
//print_r($changedBits);
//die;
				unset($dupChecks);

				/* List the duplication logic need to be checked againt the profileid.
				Setting the duplication checks array($dupChecks), this array would be passed to duplicateFinder class. 
				The duplication checks with corresponding values as '1' would be executed.
				*/
				if(is_array($changedBits))
				{
					foreach($changedBits as $changedBit)
					{
						foreach($duplicationChecks as $check=>$params)
						{
							if(strstr($params," ".$changedBit." "))
								$dupChecks["$check"]=1;
						}
					}
				}
//print_r($dupChecks);
//die;
				unset($profileObj);
				$profileObj = LoggedInProfile::getInstance('newjs_master',$profileid);
				$profileObj->getDetail("","","PHOTOSCREEN,SCREENING,USERNAME,PHONE_MOB,PHONE_WITH_STD,MOB_STATUS,HAVE_JCONTACT,ISD,PHONE_RES,STD,".CrawlerConfig::$JprofileFields);
				if($profileObj->getUSERNAME()=='')
				{
					$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,0);
					continue;
				}

				if($profileType == 'NEW')
				{
					//Any duplication check is run for a NEW profile, only if its profile and photo both are fully screened
					if($profileObj->getSCREENING() == FieldMap::getFieldLabel("flagval","sum","0") && $profileObj->getPHOTOSCREEN() == $photosScreenedFlag)
					{
						DuplicateFinderLib::findDuplicate($profileObj,$dupChecks);

						$flagValue = $duplicateProfObjMaster->getFlagValue($profileid,$profileType);
						foreach($changedBits as $changedbit=>$fieldVal)
						{
							 $flagValue = Flag::removeFlag($changedbit,$flagValue,'duplicationFieldsVal');
						}
						$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,0);
					}
				}
				elseif($profileType == 'EDIT')
				{
					//For EDIT profiles, the duplication cron shall run as and when various fields are screened. For instance, if school/college is screened; but photo is not, then the school/college change will make the profile eligible for duplicate checks in the immediate cron cycle. We will not wait for photo to be screened in such scenario. The cron shall do another duplication check once the photo is screened
					if($profileObj->getPHOTOSCREEN() == $photosToBeScreenedFlag)
						$dupChecks["PHOTO"] = 0;
					if($profileObj->getSCREENING() != FieldMap::getFieldLabel("flagval","sum","0"))
					{
						$crawlerScreeningRequired = 0;
						$crawlerScreeningNotRequired = 0;
						$phoneScreeningRequired = 0;
						$phoneScreeningNotRequired = 0;

						unset($crawlerScreenedFields);
						unset($crawlerUnScreenedFields);
						unset($phoneScreenedFields);
						unset($phoneUnScreenedFields);

						foreach($changedBits as $changedField=>$changedVal)
						{
							$bitvalue = array_search($changedVal,$parameters);//bitvalue holds the change paramter in text
							if(in_array($changedVal,$crawlerFieldsToBeScreened))
							{
//echo "---".$changedVal."---".$profileObj->getSCREENING()."---".$allScreeningValues["$bitvalue"]."\n";

								//changedVal holds key of dup array while $allScreeningValues["$bitvalue"] holds key of main screening array
								if(Flag::isBitSet($allScreeningValues["$bitvalue"],$profileObj->getSCREENING()) == 1)
								//field is screened
								{
									$crawlerScreenedFields[$changedVal] = $changedField;
	//								$crawlerScreeningNotRequired = 1;
									$crawlerScreeningNotRequired++;
								}
								else
								/* testing */
								{
									$crawlerUnScreenedFields[$changedVal] = $changedField;
	//								$crawlerScreeningRequired = 1;
									$crawlerScreeningRequired++;
								}
							}
							elseif(in_array($changedVal,$crawlerFieldsNotToBeScreened))
							{
								$crawlerScreenedFields[$changedVal] = $changedField;
	//							$crawlerScreeningNotRequired = 1;
								$crawlerScreeningNotRequired++;
							}
							elseif(in_array($changedVal,$phoneFieldsToBeScreened))
							{
								if(Flag::isBitSet($allScreeningValues["$bitvalue"],$profileObj->getSCREENING()) == 1)
								{
									$phoneScreenedFields[$changedVal] = $changedField;
	//								$phoneScreeningNotRequired = 1;
									$phoneScreeningNotRequired++;
								}
								else
								{
									$phoneUnScreenedFields[$changedVal] = $changedField;
	//								$phoneScreeningRequired = 1;
									$phoneScreeningRequired++;
								}
							}
							elseif(in_array($changedVal,$phoneFieldsNotToBeScreened))
							{
								$phoneScreenedFields[$changedVal] = $changedField;
	//							$phoneScreeningNotRequired = 1;
								$phoneScreeningNotRequired++;
							}
						}

/*
echo "\n\nnot required";
echo $crawlerScreeningNotRequired."---".$phoneScreeningNotRequired;
echo "\n\nrequired";
echo $crawlerScreeningRequired."---".$phoneScreeningRequired;
echo "\n\n";
echo "US";
print_r($crawlerUnScreenedFields);
echo "S";
print_r($crawlerScreenedFields);
echo "S";
print_r($phoneScreenedFields);
echo "US";
print_r($phoneUnScreenedFields);
*/
//die;
						//if there is no changed field which does not require screening, this duplciation check wont be run
						if($crawlerScreeningNotRequired == 0) 
							$dupChecks["CRAWLER"] = 0;	
						if($phoneScreeningNotRequired == 0)
							$dupChecks["PHONE"] = 0;	

					}
					else
					{
						$allValuesScreened=1;
						foreach($changedBits as $changedField=>$changedVal)
						{
							$crawlerScreenedFields[$changedVal] = $changedField;
						}
					}


//print_r($dupChecks);
//die;
					$flagValue = $duplicateProfObjMaster->getFlagValue($profileid,$profileType);

					if(is_array($crawlerScreenedFields))
					{
						foreach($crawlerScreenedFields as $fieldVal)
						{
							 $flagValue = Flag::removeFlag($fieldVal,$flagValue,'duplicationFieldsVal');
						}
					}
					if(is_array($phoneScreenedFields))
					{
						foreach($phoneScreenedFields as $fieldVal)
						{
							 $flagValue = Flag::removeFlag($fieldVal,$flagValue,'duplicationFieldsVal');
						}
					}
					if($dupChecks["PHOTO"]==1)
						$flagValue = Flag::removeFlag('photos',$flagValue,'duplicationFieldsVal');
					//updating flag value in table DUPLICATE_CHECKS_FIELDS
					if($allValuesScreened==1)
						$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,0);
					else
						$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,$flagValue);

					//running the duplication checks
                                        DuplicateFinderLib::findDuplicate($profileObj,$dupChecks,$changedBits);

				}

			}
		}
	}
	//deleting entries where there are no fields which are changed i.e. FIELDS_tO_BE_CHECKED = 0
	$duplicateProfObjMaster->deleteEntryAfterDuplicationCheck($profileType,$currentScript,$totalScripts);

  }
}
