<?php
class NEWJS_ADVERTISE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	/**
	* this insert the vlaues of avdertise with us
	**/
	public function insertAdvertiseData($arradver)
	{
	       try 
               {
                 $now = date("Y-m-d");
                 $sql="INSERT INTO newjs.ADVERTISE(ORGANISATION,NAME,BUSINESS,ADDRESS,PHONE,EMAIL,DETAILS,DTOFREQ) VALUES(:ORGANISATION,:NAME,:BUSINESS,:ADDRESS,:PHONE,:EMAIL,:DETAILS,:DTOFREQ)";
                 $prep=$this->db->prepare($sql);
                 $prep->bindValue(":ORGANISATION",$arradver['organisation'],PDO::PARAM_STR);   
                 $prep->bindValue(":NAME",$arradver['name'],PDO::PARAM_STR);   
                 $prep->bindValue(":BUSINESS",$arradver['business'],PDO::PARAM_STR);  
                 $prep->bindValue(":ADDRESS",$arradver['address'],PDO::PARAM_STR);  
                 $prep->bindValue(":PHONE",$arradver['phone'],PDO::PARAM_INT); 
                 $prep->bindValue(":EMAIL",$arradver['email'],PDO::PARAM_STR); 
                 $prep->bindValue(":DETAILS",$arradver['details'],PDO::PARAM_STR); 
                 $prep->bindValue(":DTOFREQ",$now,PDO::PARAM_STR); 
                 $prep->execute();  

               }
               catch(PDOException $e)
               {
                    throw new jsException($e);
               }
	}		

}
?>
