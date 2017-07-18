<?php
/*
This class is used to send queries to AP_SEND_INTEREST_PROFILES table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function inserts the profile id's of sender and receiver  
	@param sender id ,receiver id
	*/
	public function insertProfiles($sender,$receiver)
	{
		try{
			$sql="INSERT IGNORE INTO Assisted_Product.AP_SEND_INTEREST_PROFILES values ('',:sender,:receiver,NOW())";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":sender",$sender,PDO::PARAM_INT);
                        $prep->bindValue(":receiver",$receiver,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
        
        public function deleteEntry($sender,$receiver)
	{
		try{
			$sql="DELETE FROM Assisted_Product.AP_SEND_INTEREST_PROFILES WHERE SENDER=:sender AND RECEIVER=:receiver";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":sender",$sender,PDO::PARAM_INT);
                        $prep->bindValue(":receiver",$receiver,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
        
        /*this functions selects records which have date after a passed date
        * @param- $afterDate- date after which records have to be fetched
        */
        public function getCountAfterDate($afterDate) {
            if ($afterDate) {
                try {
                    $sql = "SELECT COUNT(*) AS CNT from Assisted_Product.AP_SEND_INTEREST_PROFILES where ENTRY_DATE > :AFTER_DATE";
                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":AFTER_DATE",$afterDate, PDO::PARAM_STR);
                    $prep->execute();
                    $row = $prep->fetch(PDO::FETCH_ASSOC);
                    return $row[CNT];
                } 
                catch (PDOException $e) {
                    throw new jsException($e);
                } 
            }
        }

    /*this function returns pog records which have date after a passed date and corresponds to passed pg profileid
    * @param- $pgId,$afterDate=""
    */
    public function getPOGInterestEligibleProfiles($pgId,$afterDate="") {
        if ($pgId) {
            try {
                $sql = "SELECT DISTINCT(RECEIVER) AS RECEIVER from Assisted_Product.AP_SEND_INTEREST_PROFILES where SENDER=:SENDER";
                if(!empty($afterDate)){
                    $sql .= " AND ENTRY_DATE > :AFTER_DATE";  
                }
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":SENDER",$pgId, PDO::PARAM_INT);
                if(!empty($afterDate)){
                    $prep->bindValue(":AFTER_DATE",$afterDate, PDO::PARAM_STR);
                }
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $detailArr[] = $row["RECEIVER"];
                }
                return $detailArr;
            } 
            catch (PDOException $e) {
                throw new jsException($e);
            } 
        }
        else{
            return null;
        }
    }   
}
