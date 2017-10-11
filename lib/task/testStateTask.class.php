<?php

/*
 * Author: Esha Jain
 * This task takes all the profiles in FTO.FTO_CURRENT_STATE and change the FTO state of the all the profiles(which has completed the FTO eligible/active period) to FTO expire
*/

class testStateTask extends sfBaseTask
{

  protected function configure()
  {

    $this->namespace        = 'cron';
    $this->name             = 'testStateTask';
    $this->briefDescription = 'test and update fto state from terminal';
    $this->detailedDescription = <<<EOF
The [testStateTask|INFO] task gets all profiles in FTO states table and updates the fto state of the profile with action photo accordingly.
Call it with:

  [php symfony cron:testState] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));

  }

  protected function execute($arguments = array(), $options = array())
  {
	sfContext::createInstance($this->configuration);
	$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
	$profiles = $ftoCurrentStateObj->getAllProfiles();
	$count  =1;
	foreach($profiles as $k=> $profileid)
	{
		if($count<5)
		{
			$action = "PHOTO"; // NEW / EDIT
			if($profileid)
			{
				$profile = new Profile('',$profileid);
				$profile->getDetail('', '',"*");
				$profile->getPROFILE_STATE()->updateFTOState($profile, $action);
			}
			$count++;

		}
	}
  }
}
