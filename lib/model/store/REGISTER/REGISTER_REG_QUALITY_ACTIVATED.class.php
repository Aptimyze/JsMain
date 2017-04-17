<?php
class REGISTER_REG_QUALITY_ACTIVATED extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insert($profile_ids,$date)
	{
		try 
		{
			foreach ($profile_ids as $profile_id) 
			{
			   $sql = "REPLACE INTO  REGISTER.REG_QUALITY_ACTIVATED ( PROFILE_ID ,  DATE_OF_ENTRY ) VALUES ( :PROFILE_ID,  :DATE_ENTRY);";
			   $prep=$this->db->prepare($sql);
			   $prep->bindValue(":PROFILE_ID",$profile_id,PDO::PARAM_STR); 
			   $prep->bindValue(":DATE_ENTRY",$date,PDO::PARAM_STR); 
			   $prep->execute(); 
		    }
	    } 
	    catch (Exception $e)
	    {
			throw new jsException($e);
	    }

	}
}
?>

