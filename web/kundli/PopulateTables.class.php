<?php
include_once("TrackingFunctions.class.php");
class PopulateTables
{
	private $db;
	private $mysqlObj;
	
	function __construct($db,$mysqlObj)
        {
                $this->db = $db;
                $this->mysqlObj = $mysqlObj;
        }

	public function truncate_table($table_name)
	{
		if($table_name=="KUNDLI_RECEIVER_PAID" || $table_name=="KUNDLI_RECEIVER_UNPAID")
		{
			$insert_statement = "REPLACE INTO kundli_alert.".$table_name."_TEMP(PROFILEID,GENDER,START_DT,END_DT) SELECT PROFILEID,GENDER,START_DT,END_DT FROM kundli_alert.".$table_name;
			$this->mysqlObj->executeQuery($insert_statement,$this->db) or $this->mysqlObj->logError($insert_statement);
		}
	
		$statement = "TRUNCATE TABLE kundli_alert.".$table_name;
		$this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
	}

	public function populate_receiver_table($paid="")
	{
		if($paid)
			$insert_statement = "REPLACE INTO kundli_alert.KUNDLI_RECEIVER_PAID(PROFILEID,GENDER,START_DT,END_DT) SELECT j.PROFILEID AS PROFILEID,j.GENDER AS GENDER,'','' FROM ((newjs.JPROFILE j INNER JOIN newjs.ASTRO_DETAILS a ON j.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE_ALERTS ja ON j.PROFILEID = ja.PROFILEID) WHERE j.ACTIVATED=\"Y\" AND DATE(j.LAST_LOGIN_DT)>=DATE_SUB( now( ) , INTERVAL 5 MONTH) AND (ja.KUNDLI_ALERT_MAILS=\"S\" OR ja.KUNDLI_ALERT_MAILS IS NULL OR ja.KUNDLI_ALERT_MAILS=\"\") AND j.SUBSCRIPTION LIKE \"%A%\"";
		else
			$insert_statement = "REPLACE INTO kundli_alert.KUNDLI_RECEIVER_UNPAID(PROFILEID,GENDER,START_DT,END_DT) SELECT j.PROFILEID AS PROFILEID,j.GENDER AS GENDER,'','' FROM ((newjs.JPROFILE j INNER JOIN newjs.ASTRO_DETAILS a ON j.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE_ALERTS ja ON j.PROFILEID = ja.PROFILEID) WHERE j.ACTIVATED=\"Y\" AND DATE(j.LAST_LOGIN_DT) >=DATE_SUB( now( ) , INTERVAL 5 MONTH) AND (ja.KUNDLI_ALERT_MAILS=\"S\" OR ja.KUNDLI_ALERT_MAILS IS NULL OR ja.KUNDLI_ALERT_MAILS=\"\") AND j.SUBSCRIPTION NOT LIKE \"%A%\"";	
		$this->mysqlObj->executeQuery($insert_statement,$this->db) or $this->mysqlObj->logError($insert_statement);
		
		$count= $this->mysqlObj->affectedRows();
		$trackingFunctionsObj = new TrackingFunctions("",$this->mysqlObj);
        	$trackingFunctionsObj->trackingMis($count,1);

		if($paid)
			$update_statement = "UPDATE kundli_alert.KUNDLI_RECEIVER_PAID p, kundli_alert.KUNDLI_RECEIVER_PAID_TEMP pt SET p.START_DT = pt.START_DT, p.END_DT = pt.END_DT WHERE pt.PROFILEID = p.PROFILEID";
		else
			$update_statement = "UPDATE kundli_alert.KUNDLI_RECEIVER_UNPAID p, kundli_alert.KUNDLI_RECEIVER_UNPAID_TEMP pt SET p.START_DT = pt.START_DT, p.END_DT = pt.END_DT WHERE pt.PROFILEID = p.PROFILEID";
		$this->mysqlObj->executeQuery($update_statement,$this->db) or $this->mysqlObj->logError($update_statement);
		
		if($paid)
			$statement = "TRUNCATE TABLE kundli_alert.KUNDLI_RECEIVER_PAID_TEMP";
		else
			$statement = "TRUNCATE TABLE kundli_alert.KUNDLI_RECEIVER_UNPAID_TEMP";
		$this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
	}

	public function populate_search_table($paid="")
	{
		if($paid)
		{
			$statement1 = "INSERT INTO kundli_alert.SEARCH_MALE_PAID SELECT sm.*,IFNULL(IF(a.DATE=\"0000-00-00 00:00:00\",sm.ENTRY_DT,a.DATE),sm.ENTRY_DT) AS ASTRO_ENTRY_DT FROM ((matchalerts.SEARCH_MALE sm INNER JOIN newjs.ASTRO_DETAILS a ON sm.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE j ON sm.PROFILEID = j.PROFILEID) WHERE j.SHOW_HOROSCOPE!=\"D\"";
			$statement2 = "INSERT INTO kundli_alert.SEARCH_FEMALE_PAID SELECT sm.*,IFNULL(IF(a.DATE=\"0000-00-00 00:00:00\",sm.ENTRY_DT,a.DATE),sm.ENTRY_DT) AS ASTRO_ENTRY_DT FROM ((matchalerts.SEARCH_FEMALE sm INNER JOIN newjs.ASTRO_DETAILS a ON sm.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE j ON sm.PROFILEID = j.PROFILEID) WHERE j.SHOW_HOROSCOPE!=\"D\"";
		}
		else
		{
			$statement1 = "INSERT INTO kundli_alert.SEARCH_MALE_UNPAID SELECT sm.*,IFNULL(IF(a.DATE=\"0000-00-00 00:00:00\",sm.ENTRY_DT,a.DATE),sm.ENTRY_DT) AS ASTRO_ENTRY_DT FROM ((matchalerts.SEARCH_MALE sm INNER JOIN newjs.ASTRO_DETAILS a ON sm.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE j ON sm.PROFILEID = j.PROFILEID) WHERE j.SHOW_HOROSCOPE!=\"D\"";
			$statement2 = "INSERT INTO kundli_alert.SEARCH_FEMALE_UNPAID SELECT sm.*,IFNULL(IF(a.DATE=\"0000-00-00 00:00:00\",sm.ENTRY_DT,a.DATE),sm.ENTRY_DT) AS ASTRO_ENTRY_DT FROM ((matchalerts.SEARCH_FEMALE sm INNER JOIN newjs.ASTRO_DETAILS a ON sm.PROFILEID = a.PROFILEID) LEFT JOIN newjs.JPROFILE j ON sm.PROFILEID = j.PROFILEID) WHERE j.SHOW_HOROSCOPE!=\"D\"";
		}
	
		$this->mysqlObj->executeQuery($statement1,$this->db) or $this->mysqlObj->logError($statement1);
		$this->mysqlObj->executeQuery($statement2,$this->db) or $this->mysqlObj->logError($statement2);
	}

	public function getLock()
	{
		$file = fopen("/tmp/populate_kundli_table.txt","w+");
             	$i=1;
            	while(!flock($file,2))
            	{
                    	sleep(120);
                      	if ($i==30)
                    	{
                            	mail("lavesh.rawat@jeevansathi.com","Error in PopulateTables.class.php for kundli mailers","locking issue");
                          	die;
                    	}
                       	$i++;
             	}
		return $file;
	}

	public function releaseLock($file)
	{
		flock($file,3);
	}
}
?>
