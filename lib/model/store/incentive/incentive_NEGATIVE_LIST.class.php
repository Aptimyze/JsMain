<?php
class incentive_NEGATIVE_LIST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        
        //this function will check whether the email or phone exists in the list or not
        public function checkEmailOrPhone($type,$value){
                try
                {
			if(!$value)
				return false;
                            $sql = "SELECT ID from incentive.NEGATIVE_LIST WHERE $type =:value";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":value",$value,PDO::PARAM_STR);
                        $prep->execute();
                        if($prep->fetch(PDO::FETCH_ASSOC))
                          return true;
                        else
                          return false;

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                
        }

    public function insert($type,$value,$submitID)
    {
        try
        {
            $sql ="INSERT IGNORE INTO incentive.NEGATIVE_LIST(`$type`,`SUBMISSION_ID`) VALUES(:$type,:SUBMISSION_ID)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":$type", $value, PDO::PARAM_STR);
	    $res->bindValue(":SUBMISSION_ID", $submitID, PDO::PARAM_INT);	
            $res->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }


}


