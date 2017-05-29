<?php
class PROFILE_SHARE_PROFILE extends TABLE{
	

	public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

    public function insertData($profileid,$dateTime,$count){
    	try 
		{	 
			$sql="INSERT INTO PROFILE.SHARE_PROFILE(PROFILEID,TIME,COUNT) VALUES(:PROFILEID,:DATETIME,:COUNT)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindValue(":DATETIME", $dateTime, PDO::PARAM_INT);
			$prep->bindValue(":COUNT", $count, PDO::PARAM_INT);
            $prep->execute();
            
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}
	public function selectData($profileid){
    	try 
		{
			$sql="SELECT TIME,COUNT FROM PROFILE.SHARE_PROFILE WHERE PROFILEID=:PROFILEID";
			$prep=$this->db->prepare($sql);
            $prep->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
	        return $row;
            
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
    }

    public function updateData($profileid,$dateTime="",$count){
    	try 
		{	if($dateTime==""){
				$sql="UPDATE PROFILE.SHARE_PROFILE SET COUNT=:COUNT WHERE PROFILEID=:PROFILEID";
				$prep=$this->db->prepare($sql);
			}
			else{
				$sql="UPDATE PROFILE.SHARE_PROFILE SET TIME=:DATETIME,COUNT=:COUNT WHERE PROFILEID=:PROFILEID";
				$prep=$this->db->prepare($sql);
				$prep->bindParam(":DATETIME", $dateTime, PDO::PARAM_INT);
			}
			$prep->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindParam(":COUNT", $count, PDO::PARAM_INT);
			$prep->execute();	
            
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
    }

}