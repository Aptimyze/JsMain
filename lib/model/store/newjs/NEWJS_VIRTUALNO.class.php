<?php
class NEWJS_VIRTUALNO extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function id of last inserted entry into AUTOID table needed to generate a username.
        * @return int id
        **/
	public function getVirtualNumbers()
	{
		try
		{
			$sql = "SELECT ID,VIRTUALNO FROM newjs.VIRTUALNO order by TIME asc";
			$res = $this->db->prepare($sql);
			$res->execute();
			if($result=$res->fetchAll(PDO::FETCH_ASSOC))
			return $result;
			else return null;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
        This function id of last inserted entry into AUTOID table needed to generate a username.
        * @return int id
        **/
	public function getVNoFromVId($vId)
	{
		if (!$vId){
		 throw new jsException('',"no virtual ID passed in arguements in getVNoFromVId in NEWJS_VIRTUALNO", 1);}

		try
		{
			$sql = "SELECT VIRTUALNO FROM newjs.VIRTUALNO WHERE ID=:VID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VID",$vId,PDO::PARAM_STR);
            $res->execute();
			if($result=$res->fetch(PDO::FETCH_ASSOC))
			return $result;
			else return null;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}


	/**
        This function returns the row corresponding to the passed virtual number.
        * @return 
        **/
	public function getVIdFromVNo($vNumber)
	{

		if (!$vNumber){

		 throw new jsException('',"no virtual no passed in arguements IN getVIdFromVNo in NEWJS_VIRTUALNO", 1);}
		
		try
		{
			$sql = "SELECT ID FROM newjs.VIRTUALNO WHERE VIRTUALNO=:VNUM";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VNUM",$vNumber,PDO::PARAM_STR);
            $res->execute();
			if($result=$res->fetch(PDO::FETCH_ASSOC))
			return $result;
			else return null;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}



public function updateVIdAsLatest($vId)
	{

		if (!$vId){

		throw new jsException('',"no virtual ID passed in arguements in updateVIdAsLatest in NEWJS_VIRTUALNO", 1);
	}
		
		try
		{
			$sql = "UPDATE newjs.VIRTUALNO SET `TIME`=now() WHERE ID=:VID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VID",$vId,PDO::PARAM_STR);
            $res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}




}
?>
