<?php

class ExpiringInterestMailerTaskTask extends sfBaseTask
{
	private $noOfActiveServers = 3;
	private $noOfChunksSender = 1000;
	protected function configure()
	{
			$this->addArguments(array(new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),));
			$this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));
			$this->namespace        = 'mailer';
			$this->name             = 'NEWJS_ExpiringMailer';
			$this->briefDescription = 'send the mail to jeevansathi users for expiring interests';
			$this->detailedDescription = <<<EOF
	Call it with:
		[php symfony mailer:NEWJS_ExpiringMailer chunks] 
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		$mailerEIObj = new MAIL_ExpiringInterest("newjs_masterDDL");
		$this->emptyMailer($mailerEIObj);
		$chunk = $arguments["chunks"];
		for($serverId = 0; $serverId < $this->noOfActiveServers; $serverId++)
		{
			$this->profileChunk($chunk, $serverId, $mailerYNObj);
		}
	}

	public function skipProfiles($arranged)
	{
		$skipConditionArray = SkipArrayCondition::$default;
		foreach ($arranged as $key => $value) 
		{
			$skipProfileObj = SkipProfile::getInstance($key);
			$skipProfiles = $skipProfileObj->getSkipProfiles($skipConditionArray);
			$value = explode(',',$value);       
			if(is_array($skipProfiles))
			{
				$temp=array_diff($value,$skipProfiles); 
			}	
			else
			{
				$temp=$value;
			}
			if(count($temp)>0)
			{
				$result[$key]=$temp;
			}	
			$skipProfileObj::unsetInstance($key);
		}
		return $result;
	}

	private function emptyMailer($mailerEIObj)
	{
		$mailerEIObj->EmptyMailerEI();
	}

	private function profileChunk($chunk ,$serverId, $mailerEIObj)
	{
		for($i = 0; $i < $chunk; $i++)
		{
			$dbName = JsDbSharding::getShardNo($serverId,true);
			$Contactsobj = new newjs_CONTACTS($dbName);
			$chunkstr = "AND RECEIVER%".$chunk."=".$i;
			$profilemail = $Contactsobj->getContactsExpiring($serverId,$chunkstr);
			if($profilemail)
			{
				$profileMailChunkArray = array_chunk($profilemail, $this->noOfChunksSender);
				$this->generateContactResult($profileMailChunkArray, $Contactsobj, $mailerEIObj);
			}
		}
	}

	private function generateContactResult($profileMailChunkArray, $Contactsobj, $mailerEIObj)
	{
		foreach ($profileMailChunkArray as $key => $value)
		{
			$profilemailchunk = $value;
			$profilemailchunk = implode(',',$profilemailchunk);
			$contactResult = $Contactsobj->getContactsExpiring($profilemailchunk);
			$contactResult = $this->skipProfiles($contactResult);
			$this->InsertMailer($contactResult, $mailerYNObj);
		}
	}

	private function InsertMailer($contactResult, $mailerEIObj)
	{
		foreach ($contactResult as $key => $usercode)
		{
			$count = count($usercode);
			if(count($usercode)>16)
			{
				$usercode = array_slice($usercode, 0, 16);
			}
			$usercode = implode(',',$usercode);
			$mailerEIObj->InsertMailerEI($key,$usercode,$count);
		}
	}
 }
