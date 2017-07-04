<?php

/*
 * Author: Reshu Rajput
 * Created: March 10, 2014
 * This cron is used to get report of failed or stuck processing crons on both the servers
*/

class AzkabanCronsReportTask extends sfBaseTask
{
 	protected function configure()
  	{
		$this->serverDb= array(167=>"azkaban",153=>"azkaban2",72=>"azkaban72",82=>"azkaban3",63=>"azkaban63");
		$this->reportType = array(1=>"failed",2=>"longPreparing",3=>"executing");
		$this->addArguments(array(
        		new sfCommandArgument('server', sfCommandArgument::REQUIRED, 'My argument'),
        		new sfCommandArgument('reportType', sfCommandArgument::REQUIRED, 'My argument'),
        ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'AzkabanCronsReport';
	    $this->briefDescription = 'mail report of failed or struck processing crons on both the servers';
	    $this->detailedDescription = <<<EOF
	This cron runs for two report types .First for failed crons on daily purpose and secondly for stuck processing thrice a day . This mails the report of both the servers.
	Call it with:

	  [php symfony cron:AzkabanCronsReport server reportType] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		try
		{
			 if(!sfContext::hasInstance())
	                        sfContext::createInstance($this->configuration);
	                $server= $arguments["server"]; // reportType
			$reportName= $arguments["reportType"]; // reportType
			if(!$reportName || !$server)
				SendMail::send_email("reshu.rajput@jeevansathi.com,lavesh.rawat@gmail.com","No reportname or server given in cron:AzkabanCronsReport","Azkaban Crons Report ".date('y-m-d h:i:s'));
			$azkabanExecutionFlows = new AZKABAN_EXECUTION_FLOWS("crm_slave");
			$db = $this->serverDb[$server];
			$response = $azkabanExecutionFlows->getExecutionStatus($db, $this->reportType[$reportName]);
			if(is_array($response))
				$result[$server] = $response;
			
			if(($reportName==1 || $reportName==2) && is_array($result))
			{
				$mailContent.= "\n<br>Report of ". $this->reportType[$reportName]." Crons on Servers:<br>" ;
				foreach($result as $server=>$flows)
				{
					$mailContent.= "\n<br> On Server ".$server."<br>";
					$mailContent.= "\n<table><tr><th>Execution Id</th><th>Flow Name</th></tr>";
					foreach($flows as $flow=>$values)
					{
						$mailContent.= "\n<tr><td>".$values['exec_id']."</td><td>".$values['flow_id']."</td></tr>";
					}
				}
				if($reportName==1)
				{
					SendMail::send_email("reshu.rajput@jeevansathi.com,lavesh.rawat@jeevansathi.com,nitesh.s@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com",$mailContent,"Azkaban Crons Report ".date('y-m-d h:i:s'),'reshu.rajput@jeevansathi.com');
				}
				elseif($reportName==2)
				{
					SendMail::send_email("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,reshu.rajput@jeevansathi.com,lavesh.rawat@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com",$mailContent,"Azkaban Crons Report ".date('y-m-d h:i:s'));
					include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
					$mobile         = "9818424749";
					$date = date("Y-m-d h");
					$message        = "Mysql Error Count have reached azkaban $date within 5 minutes";
					$from           = "JSSRVR";
					$profileid      = "144111";
					$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
					$mobile         = "9873639543";
					$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
					$mobile         = "9868673709";
					$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
					$mobile         = "9999216910";
                                        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                                        $this->manageRestartAzkaban();
                                        
                                        
				}
			}
			elseif($reportName==3 && !is_array($result))
			{
				$mailContent = "\n<br>Report of ". $this->reportType[$reportName]." Crons not executing on Server ".$server ;
				
				SendMail::send_email("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,reshu.rajput@jeevansathi.com,lavesh.rawat@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com",$mailContent,"Azkaban Crons Report ".date('y-m-d h:i:s'));
                                include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
                                $mobile         = "9818424749";
                                $date = date("Y-m-d h");
                                $message        = "Mysql Error Count have reached azkaban2 $date within 5 minutes";
                                $from           = "JSSRVR";
                                $profileid      = "144111";
                                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                                $mobile         = "9873639543";
                                $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                                $mobile         = "9868673709";
				$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
				$mobile         = "9999216910";
				$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                                $this->manageRestartAzkaban();
			}
		}
		catch(exception $e)
		{
			echo $e;
		}
	}

	protected function manageRestartAzkaban()
	{
		$count=1;
		$fileName = JsConstants::$docRoot."/uploads/azkabanRestartFlag.txt";
		if(file_exists($fileName))
		{
			$timeStamp = filemtime($fileName);
			$myfile = fopen($fileName, "r");
			$savedCount = fgetc($myfile);
			fclose($myfile);
			if((time() - $timeStamp) <  3600)
				$count+= intval($savedCount);
		}
		if($count>=3 )
		{
			passthru("cd /usr/local/azkaban/azkaban-web-server-2.1; sh bin/azkaban-web-shutdown.sh");
			passthru("cd /usr/local/azkaban/azkaban-executor-server-2.1; sh bin/azkaban-executor-shutdown.sh");
			passthru("cd /usr/local/azkaban/azkaban-executor-server-2.1; sh bin/azkaban-executor-start.sh > azkaban-access.log");
			passthru("cd /usr/local/azkaban/azkaban-web-server-2.1; sh bin/azkaban-web-start.sh > azkaban-access.log");
			SendMail::send_email("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,reshu.rajput@jeevansathi.com,lavesh.rawat@jeevansathi.com,,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com","restarted azkaban","Azkaban Restarted ".date('y-m-d h:i:s'));
                                
			$count=0;
		}
		
		$myfile = fopen($fileName, "w+");
		fwrite($myfile, $count);
		fclose($myfile);
	}

}
