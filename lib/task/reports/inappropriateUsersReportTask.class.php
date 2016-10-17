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
	            $dbOb=new NEWJS_BOOKMARKS("newjs_slave");
	            	$startTime =mktime(0, 0, 0, date("m"), date("d")-7, date("Y"));
	           	$endTime =mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        	        $stDate = date('Y-m-d H:i:s',$startTime);
                        $enDate = date('Y-m-d H:i:s',$endTime);

	    $mailerEntryObject = new MAIL_SHORTLISTED_PROFILES("newjs_master");
	          	for($i=0;$i<$chunk;$i++)
				{
					
                            
                                        echo "MEMORY USAGE At the start of loop: ".memory_get_usage() . "\n";
                                    	echo "MEMORY USAGE At the step 0 of loop: ".memory_get_usage() . "\n";
					for($j=3;$j<6;$j++)
                                        {	
                                        unset($row);
                                        unset($arranged);
                                        unset($jprofileArray);
                                        unset($profileString);
                                        unset($jpartnerArray);
                                        unset($jpartResultArray);
                                        
                                        $jpartResultArray =array();
                                        $dbObShard = JsDbSharding::getShardNo($j);
					$dbOb=new newjs_CONTACTS($dbObShard);
                                        $shardRemainder = $j%3;
                                        $remainderArray=array('divisor'=>$chunk,'remainder'=>$i,'shardRemainder' => $shardRemainder);
                                        $row=$dbOb->getInterestSentForDuration($stDate,$enDate,$remainderArray,'',"SENDER,RECEIVER");
                                        if(!is_array($row))continue;
                                        $arranged = array();
                                        
						foreach ($row as $key => $value) {
                                                    $arranged[$value['SENDER']][]=$value['RECEIVER'];
                                                    $profileString.= ($value['RECEIVER'].",".$value['SENDER'].",");
                                                    $jpartnerArray[]=$value['RECEIVER'];
                                                    unset($row[$key]);
						}
                                                $profileString=substr($profileString,0,-1);
						$paramArray=array('PROFILEID'=>$profileString);
						$jprofileArray = JPROFILE::getInstance()->getArray($paramArray,'','',"RELIGION,MSTATUS,AGE,PROFILEID");

                                                foreach ($jprofileArray as $key => $value) {
                                                    $jprofileArray2[$value['PROFILEID']]=$value;
                                                    unset($jprofileArray[$key]);
                                                }

                                                for($shard=3;$shard<6;$shard++)
                                                {
                                                $dbObShard = JsDbSharding::getShardNo($shard);
                                                unset($jpartnerOb);
                                                $jpartnerOb = new newjs_JPARTNER($dbObShard);
                                                $tempArray =$jpartnerOb->getDataForMultipleProfiles($jpartnerArray,"LAGE,HAGE,PARTNER_MSTATUS,PARTNER_RELIGION,PROFILEID");
                                                if(is_array($tempArray))
                                                    $jpartResultArray = $jpartResultArray + $tempArray;
                                                unset($tempArray);
                                                }
                                                
                                                
                                                foreach ($arranged as $key2 => $value2) {
                                                    $totalScore=0;
                                                    foreach ($value2 as $key3 => $value3) {
                                                        $totalScore=$totalScore + $this->getScoreForUser($jprofileArray2[$key2], $jprofileArray2[$value3],$jpartResultArray[$value3]);
                                                    }
                                                    $profileString.= ($value2['RECEIVER'].",");
                                                    unset($row[$key]);
						}   

                                                

                                                echo "Got the Interests Sent array for chunk ".$i."\n\n";
						$row=null;
						unset($row);
						
						echo "MEMORY USAGE At the step 1 of loop: ".memory_get_usage() . "\n";
                                                						
                                                $arranged=$this->skipProfiles($arranged);
						echo "MEMORY USAGE At the step 2 of loop: ".memory_get_usage() . "\n";
						echo "Started entry in Mailer Table for chunk ".$i."\n\n";
						
						$this->makeEntryInMailerTable($arranged,$mailerEntryObject);
						$arranged=null;
						unset($arranged);
						echo "MEMORY USAGE At the end of loop: ".memory_get_usage() . "\n";
                                        }
				}

           
  }
private function getScoreForUser($senderRow,$receiverRow,$receiverDPP)
  {
    $score=0;
print_r($senderRow);print_r($receiverRow);print_r($receiverDPP);die;
// RELIGION CHECK
    $religionExclude=array('1','4','7','9');
    if(!(in_array($senderRow['RELIGION'],$religionExclude ) && in_array($senderRow['RELIGION'],$religionExclude )))
    {
        if($receiverDPP['PARTNER_RELIGION'])
        {
            $relArray=explode(',',$receiverDPP['PARTNER_RELIGION']);
        
        if(!in_array($receiverRow['RELIGION'],$relArray))
                $score++;
        }        
    }
    
 // MARITAL STATUS CHECK
    
    if($receiverDPP['PARTNER_MSTATUS'])
    {
        
        
        
    }
    // AGE DIFFERENCE CHECK
    
    if($senderRow['AGE']<35 || $receiverRow['AGE']<35)
    {
        
        $ageDiff = $senderRow['AGE'] - $receiverRow['AGE'];
        if($ageDiff<0)$ageDiff=$ageDiff*(-1);
        if($ageDiff>=10 && $senderRow['AGE']<$receiverDPP['LAGE'] && $senderRow['AGE']>$receiverDPP['HAGE'])
                    $score++;
    }
    
  }
private function executeCSVforInappropriateUsers()
  {

   //This is the function which is executed when csv for report abuse is required.
    $yesterdayDate=date('Y-m-d',strtotime("-1 day"));
    $reportArray=(new MIS_INAPPROPRIATE_USERS_LOG)->getDataForAUserReported($yesterdayDate,$yesterdayDate);
    $data="Username,Outside Religion,Outside Marital Status,Outside Age Bracket,Overall negative score\r\n";
   foreach ($reportArray as $key => $value) 
      {
         $profileArray[]=$value['PROFILEID'];

      }

     if(is_array($profileArray))
    {
      $profileDetails=(new JPROFILE())->getProfileSelectedDetails($profileArray,"PROFILEID,USERNAME");
      foreach ($reportArray as $key => $value) 
      {

      $data.="\r\n".$profileDetails[$value['PROFILEID']]['USERNAME'].",".$profileDetails[$value['REPORTER']]['USERNAME'].','.$profileDetails[$value['PROFILEID']]['EMAIL'].','.$profileDetails[$value['REPORTER']]['EMAIL'].','.$value['REASON'].','.str_replace('"','""',$value['OTHER_REASON']).','.$value['DATE'];
        
      
      }
    }
  
    SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report Abuse Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'','reportAbuse_'.$yesterdayDate.".csv");
 }
 




}
