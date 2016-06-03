<?php
class MAILER_SS_MAILER extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

	public function insert($username,$ptype)
	{
		try
                {
			$sql="INSERT IGNORE INTO mailer.SS_MAILER (USERNAME,PROFILE_TYPE) VALUES (:username,:ptype)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":username",$username,PDO::PARAM_STR);
			$prep->bindValue(":ptype",$ptype,PDO::PARAM_INT);
			return $prep->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	
	public function insertSent($username)
	{
		try
                {
			$sql="INSERT IGNORE INTO mailer.SS_MAILER (USERNAME,SENT_MAIL,PROFILE_TYPE) VALUES(:username,'Y','2')";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":username",$username,PDO::PARAM_STR);
			return $prep->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	
	public function update($username)
        {
                try
                {
                        $sql="UPDATE mailer.SS_MAILER SET SENT_MAIL='Y' WHERE USERNAME=:username";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":username",$username,PDO::PARAM_STR);
                        return $prep->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

        }
        public function getUnsentProfiles($ptype)
        {
        	try
           {
             	$sql="SELECT USERNAME FROM mailer.SS_MAILER WHERE SENT_MAIL='N' AND PROFILE_TYPE=:PROFILE_TYPE";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILE_TYPE",$ptype,PDO::PARAM_INT);
                $prep->execute();
                while($row=$prep->fetch(PDO::FETCH_ASSOC))
                {
                	$result[] =$row['USERNAME'];
                }
                return $result;
             }
             catch(PDOException $e)
             {
             	throw new jsException($e);
             }
        	
        }
}
?>
