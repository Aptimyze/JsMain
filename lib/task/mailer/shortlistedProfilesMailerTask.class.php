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

		if(!sfContext::hasInstance())
	            sfContext::createInstance($this->configuration);
	            $mailerYNObj = new MAIL_SHORTLISTED_PROFILES("newjs_master");
	            $mailerYNObj->EmptyMailer();
	            $chunk=$arguments["chunks"];
	            if(!$chunk)$chunk=1;
	            $resultArray=array();
	            $dbOb=new NEWJS_BOOKMARKS("newjs_slave");
	          	for($i=0;$i<$chunk;$i++)
				{
						$remainderArray=array('divisor'=>$chunk,'remainder'=>$i);
						$row=$dbOb->getBookmarkedAllForAPeriod(30,$remainderArray);
						$resultArray=$this->filterOutContactedProfiles($row);
						unset($row);
						$this->makeEntryInMailerTable($resultArray);
						empty($resultArray);
						

				}
	}
 
protected function makeEntryInMailerTable($row){
	$mailerEntryObject = new MAIL_SHORTLISTED_PROFILES("newjs_master");
	foreach ($row as $key => $value) 
	{
		$currentRow=array_slice($value, 0,16);
		$writeArray['profileId']=$key;
		$writeArray['users']=$currentRow;
		$mailerEntryObject->makeEntry($writeArray);

	}

}



protected function truncateTable(){



}



	protected function filterOutContactedProfiles($row)
{
		
		foreach ($row as $key => $value) {
			
			$arranged[$value['BOOKMARKER']][]=$value['BOOKMARKEE'];
		}
		$skipConditionArray = SkipArrayCondition::$SkippedAll;

		foreach ($arranged as $key => $value) 
		{
 			$skipProfileObj     = SkipProfile::getInstance($key);
			$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
			$temp=array_diff($value,$skipProfiles); 
			if(count($temp)>0)
				$result[$key]=$temp;
			empty($skipProfiles);
			empty($temp);

		}
		return $result;

}
 


}



