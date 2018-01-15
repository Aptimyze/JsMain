<?php
class NEWJS_AUTOID extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function id of last inserted entry into AUTOID table needed to generate a username.
        * @return int id
        **/
	public function getId()
	{
		try
		{
			$sql = "INSERT INTO newjs.AUTOID VALUES ('')";
			$res = $this->db->prepare($sql);
			$res->execute();
			return $this->db->lastInsertId();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
