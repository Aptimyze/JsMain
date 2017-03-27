<?php
class MIS_OTP_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	

public function insertEntry($id,$phoneNum,$isd,$channel){
                try
                {

                    $sql = "INSERT INTO  MIS.OTP_LOG (  `ID` ,  `DATE` ,  `PHONE_NO` ,  `CHANNEL` ,  `ISD` ) 
							VALUES ( :ID,now(),:PHONE,:CHANNEL,:ISD)";
                    $res = $this->db->prepare($sql);    
                    $res->bindValue(":ID", $id, PDO::PARAM_INT);            
                    $res->bindValue(":PHONE", $phoneNum, PDO::PARAM_STR);        
                    $res->bindValue(":CHANNEL", $channel, PDO::PARAM_STR);            
                    $res->bindValue(":ISD", $isd, PDO::PARAM_STR);   
                    $res->execute();
                    //return $this->db->lastInsertId();
                }
                catch(PDOException $e)
                {
					jsCacheWrapperException::logThis($e);
					return true;
                }
    }



}
?>
