<?php
/**
 * Description of MaxOutMonitoringTask
 * Cron job for computing max out of application servers(Jeevansathi)
 * <code>
 * To execute : $ symfony monitoring:MaxOutMonitoring"
 * </code>
 * @author Esha Jain
 * @created 10th July 2017
 */
class MaxOutMonitoringTask extends sfBaseTask
{
  
  
  protected function configure()
  {

    $this->namespace        = 'monitoring';
    $this->name             = 'MaxOutMonitoring';
    $this->briefDescription = 'To monitor registration number in every hour and looks for count of complete registered user in last two and fire email  & sms as per threshold given in registrationThreshold.csv';
    $this->detailedDescription = <<<EOF
The [monitoring:RegistrationMonitor|INFO] task runs in every hour and looks for count of complete registered user in last two and fire email  & sms as per threshold given in registrationThreshold.csv
Call it with:

  [php symfony monitoring:MaxOutMonitoring|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	date_default_timezone_set('Asia/Kolkata');

	while(1)
	{
		$issue = 0;
		$serverStatusObj = new ServerStatus;
		$serverstatus = $serverStatusObj->getStatus();
		$str = date("Y-m-d H:i:s").":\n";
		foreach($serverstatus as $serverid=>$serverData)
		{
			$str.= $serverid."::".$serverData['idle']."\n";
			if($serverData['flag']==0)
			{
				$issue = 1;
			}
		}
//			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/maxout.txt",var_export($serverstatus,true)."\n\n",FILE_APPEND);
		if($issue==1)
		{
			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/maxout.txt",$str."\n\n",FILE_APPEND);
			$str="Idle Workers ".$str;
			CommonUtility::sendSlackmessage($str,"apache");
		}
		sleep(5);
	}

  }
  
}
