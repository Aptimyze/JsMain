<?php
class MOBILE_API_NOTIFICATION_OPENED_TRACKING extends TABLE{

    public function __construct($dbname="")
    {
		parent::__construct($dbname);
		$this->MESSAGE_ID_BIND_TYPE = "INT";
		$this->CHANNEL_BIND_TYPE = "STR";
		$this->NOTIFICATION_KEY_BIND_TYPE = "STR";
		$this->CLICKED_DATE_BIND_TYPE = "STR";
		$this->START_CLICKED_DT_BIND_TYPE = "STR";
		$this->END_CLICKED_DT_BIND_TYPE = "STR";
		$this->PROFILEID_BIND_TYPE = "INT";
    }

    /*func insertEntry
    *updates count of sent notifications for profile in table
    *@param : $profileidArr
    */
    public function insertEntry($params)
    {
		try
		{
			if(is_array($params)){
				$sql = "INSERT IGNORE INTO MOBILE_API.NOTIFICATION_OPENED_TRACKING (PROFILEID,MESSAGE_ID, NOTIFICATION_KEY,CHANNEL,CLICKED_DATE) VALUES (:PROFILEID,:MESSAGE_ID, :NOTIFICATION_KEY,:CHANNEL,:CLICKED_DATE)";
				$res=$this->db->prepare($sql);
				foreach ($params as $key => $value) {
					$paramBindValue = $this->{$key."_BIND_TYPE"};
					$res->bindValue(":".$key, $value, constant('PDO::PARAM_'.$paramBindValue));
				}
				$res->execute();
			}
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }

    /*func getEntriesForNotificationKey
    *get details condition based
    *@param : $notificationKey='',$startDt='',$endDt='',$groupByArr
    */
    public function getEntriesForNotificationKey($notificationKey='',$startDt='',$endDt='',$groupByArr='')
    {
		try
		{
			$whereClause = "";
			$groupClause = "";
			$bindParams = array();
			if($notificationKey != ''){
				$whereClause .= " WHERE NOTIFICATION_KEY = :NOTIFICATION_KEY";
				$bindParams["NOTIFICATION_KEY"] = $notificationKey;
			}
			if($startDt != '' && $endDt != ''){
				if($notificationKey){
					$whereClause .= " AND";
				}
				else{
					$whereClause .= " WHERE";
				}
				$whereClause .= " CLICKED_DATE BETWEEN :START_CLICKED_DT AND :END_CLICKED_DT";
				$bindParams["START_CLICKED_DT"] = $startDt;
				$bindParams["END_CLICKED_DT"] = $endDt;
			}
			if(is_array($groupByArr)){
				$groupStr = implode(",", $groupByArr);
				$groupClause = " GROUP BY ".$groupStr;
			}
			else{
				$groupStr = "";
			}
			$sql = "SELECT COUNT(ID) AS CNT,".$groupStr." FROM MOBILE_API.NOTIFICATION_OPENED_TRACKING".$whereClause.$groupClause;
			//var_dump($sql);
			$res=$this->db->prepare($sql);
			
			if(is_array($bindParams)){
				foreach ($bindParams as $key => $value) {
					$paramBindValue = $this->{$key."_BIND_TYPE"};
					$res->bindValue(":".$key, $value, constant('PDO::PARAM_'.$paramBindValue));	
				}
			}
			$res->execute();
			$results = array();
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$results[$row["NOTIFICATION_KEY"]][$row["CHANNEL"]] = $row["CNT"];
			}
			return $results;
		}
		catch(PDOException $e)
		{
		    throw new jsException($e);
		}
    }
    
    /*func truncateTable
    *truncate table
    *@param : none
    */
    public function truncateTable()
    {
		try
		{
			$sql = "TRUNCATE MOBILE_API.NOTIFICATION_OPENED_TRACKING";
			$res=$this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e)
		{
		    throw new jsException($e);
		}
    }

}
?>
