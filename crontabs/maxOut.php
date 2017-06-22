<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
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
        if($issue==1)
        {
	        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/maxout.txt",$str."\n\n",FILE_APPEND);
		$str="Idle Workers ".$str;
		CommonUtility::sendSlackmessage($str,"apache");
	}
        sleep(5);
}

