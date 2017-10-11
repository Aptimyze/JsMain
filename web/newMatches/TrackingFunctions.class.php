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
			$this->db = $this->mysqlObj->connect("master");
        }

	public function trackingMis($paramArr,$dt='')
        {
		if($dt)
			$date = $dt;
		else
                	$date = date("Y-m-d");

		if($paramArr && is_array($paramArr))
		{
			$column_name = "";
			$valueStr = "";
			$updateStr = "";
			foreach($paramArr as $k=>$v)
			{
				$column_name = $column_name.$k.",";
				$valueStr = $valueStr.$v.",";
				$updateStr = $updateStr.$k." = ".$k." + ".$v.",";
			}
			$column_name = rtrim($column_name,",");
			$valueStr = rtrim($valueStr,",");
			$updateStr = rtrim($updateStr,",");

			$statement = "INSERT INTO MATCHALERT_TRACKING.NEW_MATCHES_EMAILS_TRACKING (DATE,".$column_name.") VALUES ('".$date."',".$valueStr.") ON DUPLICATE KEY UPDATE ".$updateStr;
			return $this->mysqlObj->executeQuery($statement,$this->db);
		}
	}
}
?>
