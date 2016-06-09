<?php
class MOBILE_API_DIGEST_NOTIFICATIONS extends TABLE{
    public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

    /*func insertDigestNotification
    *insert entry for notification in table
    *@param : $profileid,$notificationkey
    */
    public function insertDigestNotification($profileid,$notificationkey)
    {
		try
		{
			
			$sql = "INSERT INTO MOBILE_API.DIGEST_NOTIFICATIONS (PROFILEID, NOTIFICATION_KEY,SCHEDULED_DATE) VALUES (:PROFILEID,:NOTIFICATION_KEY,:SCHEDULED_DATE)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":NOTIFICATION_KEY", $notificationkey, PDO::PARAM_STR);
			$res->bindValue(":SCHEDULED_DATE", date("Y-m-d"), PDO::PARAM_STR);
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
    
    /*func truncateEntries
    *truncate table
    *@param : none
    */
    public function truncateEntries()
    {
		try
		{
			
			$sql = "TRUNCATE MOBILE_API.DIGEST_NOTIFICATIONS";
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
