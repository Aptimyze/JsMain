<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class reminderRbInterestsTask extends sfBaseTask
 {
	private $maxNoOfTuplesInMail = 10;
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
	     
	    $this->namespace        = 'cron';
	    $this->name             = 'AP_REMINDER';
	    $this->briefDescription = 'send the reminder to jeevansathi Exclusive users after 7 days to already nterest sent profiles';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony cron:AP_REMINDER chunks] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{  
		ini_set('memory_limit','912M');
		ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	        sfContext::createInstance($this->configuration);
        $mailerYNObj = new MAIL_UNRESPONDED_CONTACTS("newjs_masterDDL");
        $mailerYNObj->EmptyMailer();
        echo "Truncated Mailer Table\n\n";
        $chunk=$arguments["chunks"];
        if(!$chunk)$chunk=1;
        echo "CHUNK = ".$chunk."\n\n";
        $resultArray=array();
       	$interestDate =mktime(0, 0, 0, date("m"), date("d")-7, date("Y"));
      	for($i=0;$i<$chunk;$i++)
		{
		   	echo "MEMORY USAGE At the start of loop: ".memory_get_usage() . "\n";
        	echo "MEMORY USAGE At the step 0 of loop: ".memory_get_usage() . "\n";
			for($j=3;$j<6;$j++)
            {

                $dbObShard = JsDbSharding::getShardNo($j,'Y');
				$dbOb=new newjs_CONTACTS($dbObShard);
                $shardRemainder = $j%3;
                $remainderArray=array('divisor'=>$chunk,'remainder'=>$i,'shardRemainder' => $shardRemainder);
                $row=$dbOb->getInterestSentForDuration($interestDate,$remainderArray);
                if(!is_array($row))continue;
                $arranged = array();
				foreach ($row as $key => $value) {
					$channel = "reminderCron";
					$source = "reminderCron";
					
					$senderProfileObj = new Profile();
					$senderProfileId = $value['RECEIVER'];
					$senderProfileObj->getDetail($senderProfileId, "PROFILEID");
					
					$recProfileObj = new Profile();
					$recProfileId = $value['RECEIVER'];
					$recProfileObj->getDetail($recProfileId, "PROFILEID");
					if($recProfileObj && $senderProfileObj)
					{
						$contactObj = new Contacts($this->senderProfileObj, $recProfileObj);
						$contactHandlerObj = new ContactHandler($senderProfileObj,$recProfileObj,"EOI",$contactObj,'R',ContactHandler::POST);
						$contactHandlerObj->setElement("MESSAGE","");
						$contactHandlerObj->setElement("DRAFT_NAME","preset");
						$contactHandlerObj->setElement("STATUS","R");
						$contactEngineObj=ContactFactory::event($this->contactHandlerObj);
						if($contactEngineObj->getComponent()->errorMessage != '')
						{
							//Error
							$mailMes = "AP error -> ".$contactEngineObj->getComponent()->errorMessage." Sender: $senderId Receiver: $receiverId ";
							$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/connectionThreshold".date('Ymd').".txt";
        					file_put_contents($fileName, date("Y m d H:i:s", strtotime("now"))."\n".$mailMes."\n\n", FILE_APPEND);							

						}
						else
						{

						}
					}
				}
			}
		}
	}
}




