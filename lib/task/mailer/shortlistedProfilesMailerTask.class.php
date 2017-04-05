<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class shortlistedProfilesMailerTask extends sfBaseTask
 {
	private $maxNoOfTuplesInMail = 16;
	private $noOfChunksSender = 1000;
	private $sizeOfChunksToMailerTable = 30;

	protected function configure()
  	{
  		$this->addArguments(array(
                	new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),
		));
  		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'NEWJS_SHORTLISTED_PROFILES';
	    $this->briefDescription = 'send the mail to jeevansathi users for the shortlisted profiles in the last ';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:NEWJS_SHORTLISTED_PROFILES chunks] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','912M');
                ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	            sfContext::createInstance($this->configuration);
	            $mailerYNObj = new MAIL_SHORTLISTED_PROFILES("newjs_masterDDL");
	            $mailerYNObj->EmptyMailer();
	            echo "Truncated Mailer Table\n\n";
	            $chunk=$arguments["chunks"];
	            if(!$chunk)$chunk=1;
	            echo "CHUNK = ".$chunk."\n\n";
	            $resultArray=array();
	            $dbOb=new NEWJS_BOOKMARKS("newjs_slave");
	            
	    $mailerEntryObject = new MAIL_SHORTLISTED_PROFILES("newjs_master");
	          	for($i=0;$i<$chunk;$i++)
				{
					echo "MEMORY USAGE At the start of loop: ".memory_get_usage() . "\n";
						$remainderArray=array('divisor'=>$chunk,'remainder'=>$i);
						$row=$dbOb->getBookmarkedAllForAPeriod(30,$remainderArray);
						echo "MEMORY USAGE At the step 0 of loop: ".memory_get_usage() . "\n";
						foreach ($row as $key => $value) {
			
								$arranged[$value['BOOKMARKER']][]=$value['BOOKMARKEE'];
						}
						echo "Got the Bookmarks array for chunk ".$i."\n\n";
						$row=null;
						unset($row);
						
						echo "MEMORY USAGE At the step 1 of loop: ".memory_get_usage() . "\n";
						$skipConditionArray = SkipArrayCondition::$SkippedAll;

						$arranged=$this->skipProfiles($arranged);
						
						echo "MEMORY USAGE At the step 2 of loop: ".memory_get_usage() . "\n";
						echo "Statred entry in Mailer Table for chunk ".$i."\n\n";
						
						$this->makeEntryInMailerTable($arranged,$mailerEntryObject);
						$arranged=null;
						unset($arranged);
						echo "MEMORY USAGE At the end of loop: ".memory_get_usage() . "\n";
				}
	}

public function skipProfiles($arranged)
{
	foreach ($arranged as $key => $value) 
	{
		$skipProfileObj     = new SkipProfile($key);
		$skipConditionArray = SkipArrayCondition::$SkippedAll;			
                $skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
		if(is_array($skipProfiles))
			$temp=array_diff($value,$skipProfiles); 
		else
			$temp=$value;
		if(count($temp)>0)
			$result[$key]=$temp;
		
	}
	return $result;
}

public function makeEntryInMailerTable($arranged,$mailerEntryObject)
{
	
	foreach ($arranged as $key => $value) 
	{
		$currentRow=array_slice($value, 0,16);
		$writeArray['profileId']=$key;
		$writeArray['users']=$currentRow;
		$mailerEntryObject->makeEntry($writeArray);
	}
	
}

}




