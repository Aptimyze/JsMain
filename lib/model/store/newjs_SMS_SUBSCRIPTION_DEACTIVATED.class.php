<?php

class newjs_SMS_SUBSCRIPTION_DEACTIVATED extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function getCount($profileid) {
        try {
            $sql = "SELECT COUNT(*) as CNT FROM newjs.SMS_SUBSCRIPTION_DEACTIVATED WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
            if($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				return $result['CNT'];
			}
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
