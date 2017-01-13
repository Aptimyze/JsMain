<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class YesNoMailerTask extends sfBaseTask
 {
	private $noOfActiveServers = 3;
	private $noOfChunksSender = 1000;
	protected function configure()
  	{
  		$this->addArguments(array(
                	new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),
		));
  		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'NEWJS_YesNoMailer';
	    $this->briefDescription = 'send the mail to jeevansathi users for pending interests';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:NEWJS_YesNoMailer chunks] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','912M');
                ini_set('max_execution_time', 0);

            if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		$mailerYNObj = new MAIL_YesNoMail("newjs_masterDDL");
		$this->emptyMailer($mailerYNObj);
		$chunk = $arguments["chunks"];
        for($serverId = 0; $serverId < $this->noOfActiveServers; $serverId++)
        {
			$this->profileChunk($chunk, $serverId, $mailerYNObj);
		}
	
	}

	public function skipProfiles($arranged)
	{
            $skipProfileObj     = new newjs_IGNORE_PROFILE('newjs_slave');

		foreach ($arranged as $key => $value) 
		{
        	        $skipProfiles       = $skipProfileObj->listIgnoredProfile($key);
			if(is_array($skipProfiles))
				$temp=array_diff($value,$skipProfiles); 
			else
				$temp=$value;
			if(count($temp)>0)
				$result[$key]=$temp;
                        $arranged[$key] = null;
                        $skipProfiles = null;
		}
		return $result;
	}

	private function emptyMailer($mailerYNObj)
	{
		$mailerYNObj->EmptyMailerYN();
	}

	private function profileChunk($chunk ,$serverId, $mailerYNObj)
	{
		for($i = 0; $i < $chunk; $i++)
		{
			$dbName = JsDbSharding::getShardNo($serverId,true);
			$Contactsobj = new newjs_CONTACTS($dbName);
			$chunkstr = "AND RECEIVER % ".$chunk."=".$i. " AND RECEIVER % 3 = $serverId ";
			$profilemail = $Contactsobj->getSendersPending($chunkstr);
			if($profilemail)
			{
				$this->generateContactResult($profilemail, $mailerYNObj);
			}
		}
	}

	private function generateContactResult($profileMailChunkArray, $mailerYNObj)
	{
			$contactResult = $this->skipProfiles($profileMailChunkArray);
			$this->InsertMailer($contactResult, $mailerYNObj);
	}

	private function InsertMailer($contactResult, $mailerYNObj)
	{
		foreach ($contactResult as $key => $usercode)
		{
			$count = count($usercode);
			if(count($usercode)>16)
				$usercode = array_slice($usercode, 0, 16);
			$usercode = implode(',',$usercode);
			$mailerYNObj->InsertMailerYN($key,$usercode,$count);
                        $contactResult[$key] = null;
		}
	}
 }



