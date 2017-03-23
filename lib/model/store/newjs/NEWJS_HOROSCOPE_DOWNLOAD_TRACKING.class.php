<?php
class NEWJS_HOROSCOPE_DOWNLOAD_TRACKING extends TABLE{
        public function __construct($dbname="")
        {
            parent::__construct($dbname);			
        }

    public function insertDownloadTracking($date,$channel,$byUsername,$ofUsername)
    {
    	try
    	{
    		$sql = "INSERT INTO newjs.HOROSCOPE_DOWNLOAD_TRACKING(DATE,CHANNEL,DOWNLOADED_BY,DOWNLOADED_OF) VALUES(:DATETIME,:CHANNEL,:BYUSERNAME,:OFUSERNAME)";
    		$prep = $this->db->prepare($sql);
            $prep->bindValue(":DATETIME", $date, PDO::PARAM_STR);
            $prep->bindValue(":CHANNEL", $channel, PDO::PARAM_STR);
            $prep->bindValue(":BYUSERNAME", $byUsername, PDO::PARAM_STR);
            $prep->bindValue(":OFUSERNAME", $ofUsername, PDO::PARAM_STR);
            $prep->execute();
    	}
    	catch(Exception $e)
    	{
                jsException::nonCriticalError($e);
        }
    }
}
?>