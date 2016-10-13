<?php
class MOBILE_API_DIGEST_NOTIFICATIONS extends TABLE{
    public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

    /*func insertDigestNotification
    *insert new entry for notification in table if not exists, otherwise updates count
    *@param : $profileid,$otherProfileid="",$notificationkey
    */
    public function insertDigestNotification($profileid,$otherProfileid="",$notificationkey)
    {
		try
		{
			
			$sql = "INSERT IGNORE INTO MOBILE_API.DIGEST_NOTIFICATIONS (PROFILEID, OTHER_PROFILEID,NOTIFICATION_KEY,SCHEDULED_DATE) VALUES (:PROFILEID,:OTHER_PROFILEID,:NOTIFICATION_KEY,:SCHEDULED_DATE)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":OTHER_PROFILEID", $otherProfileid, PDO::PARAM_INT);
			$res->bindValue(":NOTIFICATION_KEY", $notificationkey, PDO::PARAM_STR);
			$res->bindValue(":SCHEDULED_DATE", date("Y-m-d H:i:s"), PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }

    /*func isEntryExists
    *check if entry exists for profileid
    *@param : $profileid
    * @return : $result
    */
    public function isEntryExists($profileid)
    {
		try
		{

			$sql = "SELECT * FROM MOBILE_API.DIGEST_NOTIFICATIONS WHERE PROFILEID=:PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->execute();
			if($row=$res->fetch(PDO::FETCH_ASSOC))
			{
				$result = $row;
			}
			else
				$result = null;
			return $result;
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }

    /*func getRowsCount
    *get count of rows with matched scheduled date
    *@param : $SCHEDULED_DATE
    * @return : $result
    */
    public function getRowsCount($SCHEDULED_DATE)
    {
		try
		{

			$sql = "SELECT count(1) AS COUNT FROM MOBILE_API.DIGEST_NOTIFICATIONS WHERE SCHEDULED_DATE=:SCHEDULED_DATE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":SCHEDULED_DATE",$SCHEDULED_DATE,PDO::PARAM_STR);
			$res->execute();
			if($row=$res->fetch(PDO::FETCH_ASSOC))
			{
				$result = $row['COUNT'];
			}
			else
				$result = 0;
			return $result;
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }

    /*function to get digest notification rows with matched conditions
    * @params :$fields="*",$notificationkey="",$limit="",$offset=""
    * @return : $result
    */
    public function getRows($fields="*",$notificationkey="",$limit="",$offset="")
    {
        try{
            $sql = "SELECT $fields FROM MOBILE_API.DIGEST_NOTIFICATIONS";
            if($notificationkey)
            	$sql.= " WHERE NOTIFICATION_KEY = :NOTIFICATION_KEY";
            if($limit){
                $sql.= " LIMIT $limit";
            }
            if($limit && $offset)
            {
                $sql=$sql." OFFSET $offset";
            }          
            $prep = $this->db->prepare($sql);
            if($notificationkey)
            	$prep->bindValue(":NOTIFICATION_KEY", $notificationkey, PDO::PARAM_STR);
           
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    
    /*func removeEntries
    *delete some entries from table if date specified otherwise truncate table
    *@param : $scheduledDate=""
    */
    public function removeEntries($scheduledDate="")
    {
		try
		{
            if($scheduledDate)
                $sql = "DELETE FROM MOBILE_API.DIGEST_NOTIFICATIONS WHERE SCHEDULED_DATE=:SCHEDULED_DATE";
            else
			    $sql = "TRUNCATE TABLE MOBILE_API.DIGEST_NOTIFICATIONS";
			$res=$this->db->prepare($sql);
            if($scheduledDate)
                $res->bindValue(":SCHEDULED_DATE", $scheduledDate, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
		    throw new jsException($e);
		}
    }
    
    public function removeEntriesHavingProfiles($profileStr){
        try{
            $sql = "DELETE from MOBILE_API.DIGEST_NOTIFICATIONS WHERE PROFILEID IN ($profileStr)";
            $res = $this->db->prepare($sql);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

}
?>
