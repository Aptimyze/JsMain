<?php

/*
 * Author: Prinka Wadhwa
 * This task gets all the profiles for which duplication check needs to be done and runs duplication checks for these profiles.
*/

class duplicationCheck extends sfBaseTask
{
  protected function configure()
  {

$this->addArguments(array(
	new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
	new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
	));

$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));


    $this->namespace        = 'oneTimeCron';
    $this->name             = 'duplicationCheck';
    $this->briefDescription = 'runs duplication check algorithm one time for legacy profiles';
    $this->detailedDescription = <<<EOF
The [cronDuplication|INFO] task gets all the profiles for which duplication check needs to be done and runs that particular duplication check.
Call it with:

  [php symfony oneTimeCron:duplicationCheck totalNoOfScripts currentScriptNo] 
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	$totalScripts = $arguments["totalScripts"]; // total no of scripts
	$currentScript = $arguments["currentScript"]; // current script number

	$duplicateProfObjSlave = new duplicates_DUPLICATE_CHECKS_FIELDS_LEGACY("newjs_slave");
	$duplicateProfObjMaster = new duplicates_DUPLICATE_CHECKS_FIELDS_LEGACY();

	$profilesToBeChecked = $duplicateProfObjMaster->getProfilesForDuplicationCheck($totalScripts,$currentScript); //where flag=n

	if(is_array($profilesToBeChecked))
	{
		$duplicationChecks = FieldMap::getFieldLabel("duplicationCheck","","1");

		foreach($profilesToBeChecked as $indexVal => $profileData)
		{
			$profileid = $profileData["PROFILEID"];
			$timestamp = $duplicateProfObjSlave->getProfileTimestamp($profileid);
			if($profileData["TIMESTAMP"] == $timestamp)
			{
				unset($dupChecks);

				/* List the duplication logic need to be checked againt the profileid.
				Setting the duplication checks array($dupChecks), this array would be passed to duplicateFinder class. 
				The duplication checks with corresponding values as '1' would be executed.
				*/
				foreach($duplicationChecks as $check=>$params)
				{
					$dupChecks["$check"]=1;
				}

				unset($profileObj);
				$profileObj = LoggedInProfile::getInstance('newjs_master',$profileid);
				$profileObj->getDetail("","","PHOTOSCREEN,SCREENING,USERNAME,PHONE_MOB,PHONE_WITH_STD,MOB_STATUS,HAVE_JCONTACT,ISD,PHONE_RES,STD,".CrawlerConfig::$JprofileFields);

                                if($profileObj->getUSERNAME()=='')
                                {
					$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,'Y');
                                        continue;
                                }


				if($profileObj->getSCREENING() == FieldMap::getFieldLabel("flagval","sum","0"))
				{
					DuplicateFinderLib::findDuplicate($profileObj,$dupChecks);

					$duplicateProfObjMaster->updateEntryAfterDuplicationCheck($profileid,'Y');
				}
			}
		}
	}
  }
}
