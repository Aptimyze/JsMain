<?php
class MOBILE_API_SENT_NOTIFICATIONS_COUNT extends TABLE{
    public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

    /*func updateNotificationsCountForProfile
    *updates count of sent notifications for profile in table
    *@param : $profileidArr
    */
    public function incrementNotificationsCountForProfile($profileid,$count)
    {
		try
		{
			
			$sql = "INSERT INTO MOBILE_API.SENT_NOTIFICATIONS_COUNT (PROFILEID, COUNT) VALUES (:PROFILEID,1) ON DUPLICATE KEY UPDATE COUNT=:COUNT";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":COUNT", $count, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }

    /*func getCountForProfile
    *get count of sent notifications for profile in table
    *@param : $profileid
    */
    public function getCountForProfile($profileid)
    {
		try
		{

			$sql = "SELECT COUNT FROM MOBILE_API.SENT_NOTIFICATIONS_COUNT WHERE PROFILEID=:PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->execute();
			if($row=$res->fetch(PDO::FETCH_ASSOC))
			{
				return $row['COUNT'];
			}
			else
				return 0;
		}
		catch(PDOException $e)
		{
		        throw new jsException($e);
		}
    }
    /*func getCountForProfile
    *get count of sent notifications for profile in table
    *@param : $profileid
    */
    public function getCountGroupByProfile($profileidStr)
    {
                try
                {

                        $sql = "SELECT PROFILEID,COUNT(*) FROM MOBILE_API.SENT_NOTIFICATIONS_COUNT WHERE PROFILEID IN (:PROFILEID_STR) GROUP BY PROFILEID";
                        $res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID_STR",$profileidStr,PDO::PARAM_STR);
                        $res->execute();
                        while($row=$res->fetch(PDO::FETCH_ASSOC))
				$row['PROFILEID'] = $row['COUNT'];
			return $row;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
    }
    /*func truncateCountEntries
    *truncate table
    *@param : none
    */
    public function truncateCountEntries()
    {
		try
		{
			
			$sql = "TRUNCATE MOBILE_API.SENT_NOTIFICATIONS_COUNT";
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
