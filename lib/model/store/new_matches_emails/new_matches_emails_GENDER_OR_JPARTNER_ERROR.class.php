<?php

/* This class is for recording gender related errors
 * @author : Ankit Shukla
 * @created : April 18, 2016
*/
  
class new_matches_emails_GENDER_OR_JPARTNER_ERROR  extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}

        public function insert($pid,$gap)
	{
		try 
		{
                    if($pid && $gap)
                    {
			$sql= "INSERT INTO new_matches_emails.GENDER_OR_JPARTNER_ERROR(PROFILEID,DATE) VALUES(:PROFILEID,:GAP)";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                        $prep->bindValue(":GAP",$gap,PDO::PARAM_INT);
			$prep->execute();
                    }
                    else
                        echo "pid or gap not supplied";
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
 
}
