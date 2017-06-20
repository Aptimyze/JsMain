<?php

/*
 * Author: Reshu Rajput
 * Created: 19 Jun 2017
 * This cron is used to get report db increased log in azkaban servers
*/

class AzkabanDbReportTask extends sfBaseTask
{
 	protected function configure()
  	{
		$this->serverDb= array(167=>"azkaban",153=>"azkaban2",72=>"azkaban72",82=>"azkaban3",63=>"azkaban63");
		
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'AzkabanDbReport';
	    $this->briefDescription = 'mail report of report db increased log in azkaban servers';
	    $this->detailedDescription = <<<EOF
		This cron is used to get report db increased log in azkaban servers
		Call it with:

	  [php symfony cron:AzkabanDbReport] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		try
		{
			 if(!sfContext::hasInstance())
	                        sfContext::createInstance($this->configuration);
	       
			$azkabanExecutionFlows = new AZKABAN_EXECUTION_FLOWS("crm_slave");
			$cur_time = time();
			$time_24hours = ($cur_time - (60*60*24))*1000; // it is given to get 24 hour report
			$sizeInBytes=500000; // 500kb
			
			foreach($this->serverDb as $server=>$db)
			{
				
				$response = $azkabanExecutionFlows->getHeavyLogCrons($db,$time_24hours,$sizeInBytes);
				if(is_array($response))
					$result[$db] = $response;
			}
			
			
			
				$mailContent= "\n<br>Report of heavy log Crons on DBs (more than 500 kb):<br>" ;
				foreach($result as $db=>$flows)
				{
					$mailContent.= "\n<br> On DB ".$db."<br>";
					$mailContent.= "\n<table><tr><th>Execution Id</th><th>Flow Name</th></tr>";
					foreach($flows as $flow=>$values)
					{
						$mailContent.= "\n<tr><td>".$values['exec_id']."</td><td>".$values['flow_id']."</td></tr>";
					}
					$mailContent.= "\n</table>";
				}
				SendMail::send_email("reshu.rajput@jeevansathi.com",$mailContent,"Azkaban DB Increased Report ".date('y-m-d h:i:s'));
                    
				//SendMail::send_email("reshu.rajput@jeevansathi.com,lavesh.rawat@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com",$mailContent,"Azkaban DB Increased Report ".date('y-m-d h:i:s'));
                               
			
		}
		catch(exception $e)
		{
			echo $e;
		}
	}

	

}
