<?php
/*
 * Author: Kumar Anand
 * This task fetches legacy profiles which do not have an app photo and bring them in the app photo screening queue
*/

class ScreenLegacyProfilesForAppPhotoTask extends sfBaseTask
{
	private $liveDate = "2014-01-29 00:00:00";
	private $insertThreshold = 4000;		//count of rows in newjs.PICTURE_FOR_SCREEN_APP, below which only legacy entries will be made

 	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'ScreenLegacyProfilesForAppPhoto';
	    $this->briefDescription = 'fetches legacy profiles which do not have an app photo and bring them in the app photo screening queue';
	    $this->detailedDescription = <<<EOF
	This cron runs daily and fetches legacy profiles which do not have an app photo and bring them in the app photo screening queue.
	Call it with:

	  [php symfony cron:ScreenLegacyProfilesForAppPhoto] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

		$nsObj = new NonScreenedPicture;
		$count = $nsObj->getCountFromAppTable();
		unset($nsObj);

		if($count<$this->insertThreshold)
		{
			$npfsalObj = new NEWJS_PICTURE_FOR_SCREEN_APP_LEGACY;
			$selectionThreshold = $this->insertThreshold - $count;
			$profileArr = $npfsalObj->getProfiles(intval($selectionThreshold));
			if($profileArr && is_array($profileArr))
			{
				$nspObj = new NonScreenedPicture;
				$nspObj->insertBulkForLegacyProfiles($profileArr);
				unset($nspObj);

				$npfsalObj->updateStatus($profileArr);
			}
			unset($npfsalObj);
		}
  	}
}
