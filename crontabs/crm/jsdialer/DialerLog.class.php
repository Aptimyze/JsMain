<?php
class DialerLog
{
        public function __construct($db_js_111=''){

		$this->db_js_111 	=$db_js_111;
		$this->logFilePath	='/home/developer/jsdialer/errorLog/';
        }

	public function logError($sql,$campaignName='',$dbConnect='',$ms='',$processName='')
	{
		$today 		=@date("Y-m-d H:m:i");
		$todayDt	=date("Ymd");
		$filename 	=$this->logFilePath."logerror_$todayDt.txt";
		$handle 	=@fopen($filename, 'a');
		if($handle)
		{
			if($ms)
				$string ="\n DATE:$today \t CAMPAIGN:$campaignName \t QUERY:$sql \t ERROR:".mssql_get_last_message();
			else
				$string ="\n DATE:$today \t CAMPAIGN:$campaignName \t QUERY:$sql \t ERROR:".mysql_error($dbConnect);
			fwrite($handle, $string);
			fclose($handle);
		}
		else{
			$string ="The file $filename is not writable";
			if(!chmod($filename, 0777)){
				$string .=" \nCannot change the mode of file ($filename)";
			}
		}

		// Error mail
		if($processName){
			$this->sendMail($string,$processName);
			die();
		}
	}
	public function logOnlinePriority($profileid,$npriority,$dialStatus='',$priorityType='',$campaignName,$sourceType='',$alloted='')
	{
        	$query = "INSERT into js_crm.ONLINE_PRIORITY_LOG (PROFILEID,PRIORITY,DIAL_STATUS,TIME,ACTION,CAMPAIGN,SOURCE_TYPE,ALLOTED) VALUES ('$profileid','$npriority','$dialStatus',now(),'$priorityType','$campaignName','$sourceType','$alloted')";
		mysql_query($query,$this->db_js_111) or die($query.mysql_error($this->db_js_111));	
	}
	public function sendMail($string,$processName)
	{
                $to     ="manoj.rana@naukri.com,vibhor.garg@jeevansathi.com";
                $from   ="From:vibhor.garg@jeevansathi.com";
                $sub	='Failure Dialer Process/Query for '.$processName;
                mail($to,$sub,$string,$from);
	}

}
?>
