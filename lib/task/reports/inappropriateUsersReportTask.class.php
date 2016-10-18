<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class inappropriateUsersReportTask extends sfBaseTask
{
  
  protected function configure()
  {

      $this->addArguments(array(
    new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),
));

    $this->namespace        = 'mailer';
    $this->name             = 'inappropriateUsersReport';
    $this->briefDescription = 'regular report inappropriate behaviour of Users';
    $this->detailedDescription = <<<EOF
      The task filters out the users who have sent interests to people who are out of their DPP stored. Also sends CSV through a mail.
      Call it with:

      [php symfony mailer:inappropriateUsersReport] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
		ini_set('memory_limit','512M');
                ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	            sfContext::createInstance($this->configuration);
	      //      $mailerYNObj->EmptyMailer();
	            echo "Truncated Mailer Table\n\n";
	            $chunk=$arguments["chunks"];
	            if(!$chunk)$chunk=1;
	            echo "CHUNK = ".$chunk."\n\n";
	            $resultArray=array();
	            $logTable=new MIS_INAPPROPRIATE_USERS_LOG();
                    $stDate=date('Y-m-d',strtotime('-7 days'));
                    $enDate=date('Y-m-d',strtotime('-0 days'));
	          	for($i=0;$i<$chunk;$i++)
				{
					
                            
                                        echo "MEMORY USAGE At the start of loop: ".memory_get_usage() . "\n";
                                    	echo "MEMORY USAGE At the step 0 of loop: ".memory_get_usage() . "\n";
					for($j=3;$j<6;$j++)
                                        {	

                                            $jpartResultArray =array();
                                            $dbObShard = JsDbSharding::getShardNo($j);
                                            $dbOb=new newjs_CONTACTS($dbObShard);
                                            $shardRemainder = $j%3;
                                            $remainderArray=array('divisor'=>$chunk,'remainder'=>$i,'shardRemainder' => $shardRemainder);
                                            $row=$dbOb->getInterestSentForDuration($stDate,$enDate,$remainderArray,'',"SENDER,RECEIVER");
                                            if(!is_array($row))continue;
                                            $arranged = array();

                                            foreach ($row as $key => $value) 
                                                {
                                                    $arranged[$value['SENDER']][]=$value['RECEIVER'];
                                                    $profileString.= ($value['RECEIVER'].",".$value['SENDER'].",");
                                                    $jpartnerArray[]=$value['RECEIVER'];
                                                    unset($row[$key]);
                                                }

                                            $profileString=substr($profileString,0,-1);
                                            $paramArray=array('PROFILEID'=>$profileString);
                                            $jprofileArray = JPROFILE::getInstance()->getArray($paramArray,'','',"RELIGION,MSTATUS,AGE,PROFILEID,USERNAME");

                                            foreach ($jprofileArray as $key => $value) 
                                                {
                                                    $jprofileArray2[$value['PROFILEID']]=$value;
                                                    unset($jprofileArray[$key]);
                                                }

                                            for($shard=3;$shard<6;$shard++)
                                                {
                                                $dbObShard = JsDbSharding::getShardNo($shard);
                                                $jpartnerOb = new newjs_JPARTNER($dbObShard);
                                                $tempArray =$jpartnerOb->getDataForMultipleProfiles($jpartnerArray,"LAGE,HAGE,PARTNER_MSTATUS,PARTNER_RELIGION,PROFILEID");
                                                if(is_array($tempArray))
                                                    $jpartResultArray = $jpartResultArray + $tempArray;
                                                unset($tempArray);
                                                unset($jpartnerOb);

                                                }


                                            foreach ($arranged as $key2 => $value2) 
                                                {
                                                unset($totalScoreArray);
                                                $totalScore=0;
                                                $totalScoreArray=array();
                                                foreach ($value2 as $key3 => $value3) 
                                                    {
                                                        unset($tempScore);
                                                        $tempScore=$this->getScoreForUser($jprofileArray2[$key2], $jprofileArray2[$value3],$jpartResultArray[$value3]);
                                                        $totalScoreArray['R']=$totalScoreArray['R'] + $tempScore['R'];
                                                        $totalScoreArray['A']=$totalScoreArray['A'] + $tempScore['A'];
                                                        $totalScoreArray['M']=$totalScoreArray['M'] + $tempScore['M'];

                                                    }
                                                $currentScore=$logTable->getDataForAUserReported($key2,$stDate);
                                                $totalScore = $totalScoreArray['R'] + $totalScoreArray['M'] + $totalScoreArray['A'];
                                                $totalCurrentScore = $currentScore['TOTAL_SCORE'] ;
                                                if($totalScore && (!$currentScore || ($totalScore > $totalCurrentScore)))
                                                {
                                                    $totalScoreArray['USERNAME']=$jprofileArray2[$key2]['USERNAME'];
                                                    $totalScoreDBArray[$key2]=$totalScoreArray;
                                                }
                                                unset($arranged[$key2]);
                                                }

                                            unset($row);
                                            unset($arranged);
                                            unset($jprofileArray);
                                            unset($profileString);
                                            unset($jpartnerArray);
                                            unset($jpartResultArray);

						echo "MEMORY USAGE At the step 1 of loop: ".memory_get_usage() . "\n";
                                                						
                                        }
				}
                                
                                                foreach ($totalScoreDBArray as $key4 => $value4) {
                                                    $logTable->insert($key4,$value4);
                                                }
                      $this->executeCSVforInappropriateUsers();                          
           
  }
private function getScoreForUser($senderRow,$receiverRow,$receiverDPP)
  {
    $score=array('R'=>0,'A'=>0,'M'=>0);
// RELIGION CHECK
    $religionExclude=array('1','4','7','9');
    if(!(in_array($senderRow['RELIGION'],$religionExclude ) && in_array($receiverRow['RELIGION'],$religionExclude )))
    {
        if($receiverDPP['PARTNER_RELIGION'])
        {
            $relArray=explode(',',$receiverDPP['PARTNER_RELIGION']);
            if(!in_array("'".$senderRow['RELIGION']."'",$relArray))
                $score['R']=1;
        }        
    }
    
 // MARITAL STATUS CHECK
    if($receiverDPP['PARTNER_MSTATUS'])
    {
        $marriedArray=array('S','D','W','A','M');
        $unMarriedArray=array('N');
        if(!( (in_array($senderRow['RELIGION'],$marriedArray) && in_array($receiverRow['RELIGION'],$marriedArray))
        || (in_array($senderRow['RELIGION'],$unMarriedArray) && in_array($receiverRow['RELIGION'],$unMarriedArray))
           ))
        {
            $mStatusArray=explode(',',$receiverDPP['PARTNER_MSTATUS']);
            if(!in_array("'".$senderRow['MSTATUS']."'", $mStatusArray))
                    $score['M']=1;
            
        }     
        
    }
    // AGE DIFFERENCE CHECK
    
    if($receiverDPP['LAGE'] && $receiverDPP['HAGE'] && ($senderRow['AGE']<35 || $receiverRow['AGE']<35))
    {
        $ageDiff = $senderRow['AGE'] - $receiverRow['AGE'];
        if($ageDiff<0)$ageDiff=$ageDiff*(-1);
        if($ageDiff>=10 && ($senderRow['AGE']<$receiverDPP['LAGE'] || $senderRow['AGE']>$receiverDPP['HAGE']))
                    $score['A']=1;
    }
    return $score;
  }
 private function executeCSVforInappropriateUsers()
   {
 
    //This is the function which is executed when csv for report abuse is required.
     $todayDate=date('Y-m-d',strtotime("-0 day"));
     $reportArray=(new MIS_INAPPROPRIATE_USERS_LOG())->getDataForADate($todayDate);
     $data="Username,Outside Religion Contact,Outside Marital Status Contact,Outside Age Bracket Contact,Overall negative score\r\n";
    foreach ($reportArray as $key => $value) 
       {
          $profileArray[]=$value['PROFILEID'];
 
       }
 
      if(is_array($profileArray))
     {
       $profileDetails=(new JPROFILE())->getProfileSelectedDetails($profileArray,"PROFILEID,USERNAME");
       foreach ($reportArray as $key => $value) 
       {
       $totalScore=$value['RELIGION_COUNT']+$value['MSTATUS_COUNT']+$value['AGE_COUNT'];
       $data.="\r\n".$profileDetails[$value['PROFILEID']]['USERNAME'].','.$value['RELIGION_COUNT'].','.$value['MSTATUS_COUNT'].','.$value['AGE_COUNT'].','.$totalScore;
       }
     }
     
       SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report Abuse Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'','reportAbuse_'.$yesterdayDate.".csv");
   }

}