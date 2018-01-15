<?php

/*
 * Author: Reshu Rajput
 * Created: Jan 5, 2015
 * This cron is used to get images which are from photos from mail and make entry in picture for screen new
 * web/uploads/MailImages/ 
*/

class PhotoScreen_PhotoFromMailTask extends sfBaseTask
{
	private $limit = 1000;
 	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'PhotoScreen_PhotoFromMail';
	    $this->briefDescription = 'Photos from mail screening';
	    $this->detailedDescription = <<<EOF
	This cron is used to get images which are from photos from mail and make entry in picture for screen new.
	Call it with:

	  [php symfony cron:PhotoScreen_PhotoFromMail] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
          if(CommonUtility::hideFeaturesForUptime())
                        successfullDie();

		$paramArr["LIMIT"] = $this->limit;
		$screenPhotosFromMailObj = new SCREEN_PHOTOS_FROM_MAIL();
                $picturesForLogic = $screenPhotosFromMailObj->getAllUnallottedProfiles($paramArr);
                if(is_array($picturesForLogic))
		{
			foreach($picturesForLogic as $index=>$value)
			{
				try{
					$paramArr = array();
					$paramArr["MAILID"] = $value["ID"];
					$paramArr["NAME"] = "jstech"; // hardcoded as no more relevance
					$paramArr["RECEIVE_TIME"] = $value["DATE"];
					$screen_time = sfConfig::get("app_screentime");
					$paramArr["SUBMIT_TIME"] = timeFunctions::newtime($value["DATE"],0,$screen_time,0);
					// Allotment of profiles to jstech
					$screenPhotosFromMailObj->allotProfile($paramArr);
					$subject = trim($value["SUBJECT"]," ");
					$username = preg_replace('/[^a-zA-Z0-9\-_. ]/', '', $subject);
					if($username!="")
					{
						$profileObj = Operator::getInstance();
						$profileObj->getDetail($username, 'USERNAME', 'PROFILEID');
					}
					//If invalid username provided
					if (!$profileObj || $profileObj->getPROFILEID() == NULL || $profileObj->getPROFILEID() == '')
					{ 
						SendMail::send_email("reshu.rajput@jeevansathi.com","ProfileId not found for mailId:".$value["ID"],"Photos from mail no profile","reshu.rajput@jeevansathi.com");
					}
					else
					{
						//If valid profile id found then move to picture for screen new
						$profileId = $profileObj->getPROFILEID();
						$screenPhotosFromMailObj->updateScreeningStatus("jstech", $value["ID"], $profileId);		
						$pictureServiceObj = new PictureService($profileObj);	
						$output = $pictureServiceObj->saveAlbumFromMail($value["ID"]);
						$screenPhotosFromMailObj->logScreeningAction($profileId, $value["ID"],"NA","NA","1");
					}
					unset($profileObj);
					unset($paramArr);
					unset($profileId);
					
				}
				catch(exception $e)
				{
					echo $e;
				}
			}
		}
  	}

}
