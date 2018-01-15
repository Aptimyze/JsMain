<?php
class NEWJS_CONTACT_MAILERS_NEW extends TABLE
{
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getAgentDetails($city='')
        {
		try 
		{
			if($city)
			{ 
				$sql="SELECT ID,CITY,LOCALITY,AGENT,MOBILE FROM newjs.CONTACT_MAILERS_NEW WHERE CITY = :CITY ORDER BY RAND() LIMIT 1";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":CITY",$city,PDO::PARAM_STR);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}
				return $res;
			}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	public function updateSentCount($id)
	{
		if(!$id)
			throw new jsException("","ID IS BLANK IN updateSentCount() OF NEWJS_CONTACT_MAILERS_NEW.class.php");

		try
		{
			$sql = "UPDATE newjs.CONTACT_MAILERS_NEW SET TIMES_SENT = TIMES_SENT+1 WHERE ID = :ID";
			$prep=$this->db->prepare($sql);
                      	$prep->bindValue(":ID",$id,PDO::PARAM_INT);
                      	$prep->execute();
		}
		catch(PDOException $e)
                {       
                        throw new jsException($e);
                }
	}
}
?>
