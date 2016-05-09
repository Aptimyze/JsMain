<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

class TrackingFunctions
{	
	private $db;
        private $mysqlObj;

        function __construct($db="",$mysqlObj="")
        {
		if(!$mysqlObj)
			$this->mysqlObj = new Mysql;
		else
                	$this->mysqlObj = $mysqlObj;
		
		if($db)
                	$this->db = $db;
		else
			$this->db = $this->mysqlObj->connect("master") or $this->mysqlObj->logError("Unable to connect to master","ShowErrTemplate");
        }

	public function trackingMis($count,$param)
        {
                $date = date("Y-m-d");
		if($param==1)
		{
			$column_name = "PROFILES_CONSIDERED";
		}
		elseif($param==2)
		{
			$column_name = "PROFILES_MAIL_SENT";
        	}
		elseif($param==3)
		{
			$column_name = "MAIL_OPEN";
		}
		elseif($param==4)
		{
			$column_name = "UNSUBSCRIPTION";
		}

		if($count)
		{
                	$statement = "UPDATE MIS.KUNDLI_MAILER_TRACKING SET ".$column_name." = ".$column_name."+".$count." WHERE DATE = \"".$date."\"";
                	$result = $this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
                	if($this->mysqlObj->affectedRows()==0)
                	{
                         	$statement = "INSERT INTO MIS.KUNDLI_MAILER_TRACKING(DATE,".$column_name.") VALUES (\"".$date."\",".$count.")";
                        	$this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
                	}
		}
	}
}
?>
