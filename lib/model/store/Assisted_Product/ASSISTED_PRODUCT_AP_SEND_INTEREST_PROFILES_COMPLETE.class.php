<?php
/*
This class is used to send queries to AP_SEND_INTEREST_PROFILES table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES_COMPLETE extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function inserts the profile id's of sender and receiver  
	@param sender id ,receiver id
	*/
	public function getNotInProfilesForSender($sender)
	{
		try{
			$sql="select RECEIVER from Assisted_Product.AP_SEND_INTEREST_PROFILES_COMPLETE where SENDER = :sender";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":sender",$sender,PDO::PARAM_INT);
                        $prep->execute();
                        $result = "";
                        while($row = $prep->fetch(PDO::FETCH_ASSOC))
                            $result.= " ".$row[RECEIVER];
                        return $result;
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
}

