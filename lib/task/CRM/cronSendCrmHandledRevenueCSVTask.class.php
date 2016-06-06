<?php
/* cron to send crm handled revenue mis csv sheet */

class cronSendCrmHandledRevenueCSVTask extends sfBaseTask
{
	/**
	   * 
	   * Configuration details for CRM:cronSendCrmHandledRevenueCSV
	   * 
	   * @access protected
	   * @param none
  	*/
	protected function configure()
	{
		$this->addArguments(array(new sfCommandArgument('fortnight', sfCommandArgument::OPTIONAL, 'My argument')));
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'cronSendCrmHandledRevenueCSV';
		$this->briefDescription = 'send crm handled revenue mis csv sheet';
		$this->detailedDescription = <<<EOF
		The [cronSendCrmHandledRevenueCSV|INFO] task does things.
		Call it with:
		[php symfony CRM:cronSendCrmHandledRevenueCSV|INFO]
EOF;
	}

	/**
	   * 
	   * Function for executing cron. 
	   * 
	   * @access protected
	   * @param $arguments,$options
   */
	protected function execute($arguments = array(), $options = array())
	{	 
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
		$fortnight = $arguments["fortnight"];

		//generate csv if valid fortnight passed
		if($fortnight=="H1" || $fortnight=="H2")
		{
			$fortnightValue = substr($fortnight, 1);
			$file_path = JsConstants::$docRoot."/uploads/crmRevenue.csv";
			$fp = fopen($file_path, "w");
			if($fortnight=="H1")
			{
				$month = date('M');
			}
			else
			{
				$month = date('M',strtotime("- 1 month"));
			}	
			
			//send curl request to mis action to generate csv
			$tuCurl = curl_init();
	        curl_setopt($tuCurl, CURLOPT_URL, JsConstants::$siteUrl."/operations.php/crmMis/crmHandledRevenueCsvGenerate?fromMisCron=1&monthValue=".$month."&yearValue=".date('Y')."&fortnightValue=".$fortnightValue."&report_type=TEAM&report_content=REVENUE&report_format=XLS&dialer_check=1");
	        //curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($tuCurl, CURLOPT_FILE, $fp);
			curl_setopt($tuCurl, CURLOPT_TIMEOUT, 20);
			curl_setopt($tuCurl, CURLOPT_CONNECTTIMEOUT, 20);
	        $tuData = curl_exec($tuCurl);
	        curl_close($tuCurl);
	        
	        //send mail alert in case of problem in fetching mis data
	        if($tuData!==true)
	        {
	        	CRMAlertManager::sendMailAlert("Issue in cronSendCrmHandledRevenueCSVTask while fetching mis data","AgentNotifications");
	        }
	        else
	        {
		        //send csv as mail
		        $to = "ankita.g@jeevansathi.com,jitesh.bhugra@naukri.com,rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,Amit.Malhotra@jeevansathi.com";
		       // $to = "ankita.g@jeevansathi.com";
		        $message = "PFA";
		        $subject = "Crm revenue mis csv";
		        $csvAttachment = file_get_contents($file_path);
		        //print_r($csvAttachment);
		        SendMail::send_email($to,$message,$subject,"","","",$csvAttachment,"","crmHandledRevenue.csv");
		        unset($csvAttachment);
	    	}
	    }
	    else
	    {
	    	echo "Invalid fortnight value provided";
	    	die;
	    }
	}
}
?>
