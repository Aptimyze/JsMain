<?php
class bounces_DELETED_BOUNCE_MAILS extends TABLE{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);

	}

	public function insertDeletedEntry($name,$dateTime,$email)
	{
		try 
		{	 
			$sql="INSERT INTO bounces.DELETED_BOUNCE_MAILS(NAME,DATETIME,EMAIL) VALUES(:NAME,:DATETIME,:EMAIL)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":NAME", $name, PDO::PARAM_INT);
			$prep->bindValue(":DATETIME", $dateTime, PDO::PARAM_INT);
			$prep->bindValue(":EMAIL", $email, PDO::PARAM_INT);
            $prep->execute();
            
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}
}