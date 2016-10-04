<?php 

/**
* 
*/
class newjs_SMS_DETAIL extends TABLE
{
	public function __construct($dbname="")
    {
    	parent::__construct($dbname);
    }

	public function getCount($key, $ProfileID)    
	{
		try
		{
			$todayDate = date('Y-m-d');
            $sql = "select count(*) as SmsCount from newjs.SMS_DETAIL WHERE DATE(`ADD_DATE`) = '$todayDate' and SMS_KEY = :key and PROFILEID = :ProfileID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":key", $key, PDO::PARAM_STR);
            $prep->bindValue(":ProfileID", $ProfileID, PDO::PARAM_INT);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result['SmsCount'];
		}
		catch(PDOException $e) {
            throw new jsException($e);
        }
	}
}

?>