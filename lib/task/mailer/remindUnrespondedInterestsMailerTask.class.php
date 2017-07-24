<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class remindUnrespondedInterestsMailerTask extends sfBaseTask
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
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'MAIL_REMIND_UNRESPONDED_PROFILES';
	    $this->briefDescription = 'send the mail to jeevansathi users regarding the deletion of unresponded profiles';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:MAIL_REMIND_UNRESPONDED_PROFILES chunks] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{  
		ini_set('memory_limit','912M');
                ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	            sfContext::createInstance($this->configuration);
	            $mailerYNObj = new MAIL_UNRESPONDED_CONTACTS("newjs_master");
	            $mailerYNObj->EmptyMailer();
	            echo "Truncated Mailer Table\n\n";
	            $chunk=$arguments["chunks"];
	            if(!$chunk)$chunk=1;
	            echo "CHUNK = ".$chunk."\n\n";
	            $resultArray=array();
	            
	           	$startTime =mktime(0, 0, 0, date("m"), date("d")-13, date("Y"));
	           	$endTime =mktime(0, 0, 0, date("m"), date("d")-6, date("Y"));
        	        $stDate = date('Y-m-d H:i:s',$startTime);
                        $enDate = date('Y-m-d H:i:s',$endTime);
                        $mailerEntryObject = new MAIL_UNRESPONDED_CONTACTS('newjs_master');
	          	for($i=0;$i<$chunk;$i++)
				{
					
                            
                                        echo "MEMORY USAGE At the start of loop: ".memory_get_usage() . "\n";
                                    	echo "MEMORY USAGE At the step 0 of loop: ".memory_get_usage() . "\n";
					for($j=3;$j<6;$j++)
                                        {	
                                        unset($row);
                                        unset($arranged);
                                        unset($jprofileArray);
                                        unset($jprofileArray2);
                                        unset($profileString);
                                        $dbObShard = JsDbSharding::getShardNo($j,'Y');
					$dbOb=new newjs_CONTACTS($dbObShard);
                                        $shardRemainder = $j%3;
                                        $remainderArray=array('divisor'=>$chunk,'remainder'=>$i,'shardRemainder' => $shardRemainder);
                                        $row=$dbOb->getInterestSentForDuration($stDate,$enDate,$remainderArray);
                                        if(!is_array($row))continue;
                                        $arranged = array();
						foreach ($row as $key => $value) {
			
								$arranged[$value['SENDER']][]=$value['RECEIVER'];
                                                                $profileString.= ($value['RECEIVER'].",");
                                                                unset($row[$key]);
						}
                                                $profileString=substr($profileString,0,-1);
						$paramArray=array('PROFILEID'=>$profileString);
						$jprofileArray= JPROFILE::getInstance()->getArray($paramArray);
                                                foreach ($jprofileArray as $key => $value) {
                                                    if(!($value['HAVEPHOTO']!='Y' || $value['ACTIVATED']!='Y') )
                                                        $jprofileArray2[$value['PROFILEID']]='Y';
                                                    
                                                }
                                                if(!is_array($jprofileArray2))continue;
                                                echo "Got the Interests Sent array for chunk ".$i."\n\n";
						$row=null;
						unset($row);
						
						echo "MEMORY USAGE At the step 1 of loop: ".memory_get_usage() . "\n";
						$skipConditionArray = SkipArrayCondition::$default;
                                                foreach ($arranged as $key => $value) {
                                                    foreach ($value as $key2 => $value2) {
                                                        if(!isset($jprofileArray2[$value2]))unset($arranged[$key][$key2]);
                                                    }
                                                }
                                                						
                                                $arranged=$this->skipProfiles($arranged);
						echo "MEMORY USAGE At the step 2 of loop: ".memory_get_usage() . "\n";
						echo "Statred entry in Mailer Table for chunk ".$i."\n\n";
						
						$this->makeEntryInMailerTable($arranged,$mailerEntryObject);
						$arranged=null;
						unset($arranged);
						echo "MEMORY USAGE At the end of loop: ".memory_get_usage() . "\n";
                                        }
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

public function makeEntryInMailerTable($arranged,$mailerEntryObject)
{
	
	foreach ($arranged as $key => $value) 
	{
		$currentRow=array_slice($value, 0,10);
		$writeArray['sendersProfileId']=$key;
		$writeArray['recieversProfileId']=$currentRow;
		$mailerEntryObject->makeEntry($writeArray);
	}
	
}



}




