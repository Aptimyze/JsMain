<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the fraud alert mail to the users
 */
 class FilterMailerEOITask extends sfBaseTask
{
	private $noOfActiveServers = 3;
	protected function configure()
  	{
  		$this->addArguments(array(
                	new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),
		));     
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'FilterEOIMail';
	    $this->briefDescription = 'send the mail to jeevansathi users for filtered interest they have recieved.';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:FilterEOIMail] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
	        sfContext::createInstance($this->configuration);
	    $mailerEOIFilterObj = new MAIL_FilterEOI("newjs_masterDDL");
	    $mailerEOIFilterObj->EmptyFilterEOI();
	    for($serverId=0;$serverId<$this->noOfActiveServers;$serverId++){
	            $dbName = JsDbSharding::getShardNo($serverId,true);
	            $Contactsobj = new newjs_CONTACTS($dbName);
	            $chunkstr="RECEIVER%".$this->noOfActiveServers."=".$serverId;
	            $profilemail=$Contactsobj->getFilterContacts($serverId,$chunkstr);

	            foreach ($profilemail as $key => $value) {			            		
				    $usercode = explode(',',$value);
				    $usercode = $this->skipProfiles($usercode,$key);
				    $count=count($usercode);
				    if(count($usercode)>10)
				        $usercode = array_slice($usercode, 0, 10);
				    $usercode = implode(',',$usercode);
				    if($key!=0 && $count >= 1)
					$mailerEOIFilterObj->InsertFilterEOI($key,$usercode,$count);
	        }
	    }
	}

		public function skipProfiles($arranged,$key)
	{
            $skipProfileObj     = new newjs_IGNORE_PROFILE('newjs_slave');
        	$skipProfiles       = $skipProfileObj->listIgnoredProfile($key);

			if(is_array($skipProfiles))
				$temp=array_diff($arranged,$skipProfiles); 
			else
				$temp=$arranged;      
		return $temp;
	}
}



