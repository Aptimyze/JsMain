<?php
//This class is used to execute queries on newjs.SECT table

class NEWJS_SECT extends TABLE
{
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

	/*
	This function is used to get all sect data
	@return - resultset array
	*/	
	public function getAllSects()
	{
		try
		{
			$sql = "SELECT SQL_CACHE LABEL,VALUE,PARENT_RELIGION FROM newjs.SECT ORDER BY PARENT_RELIGION,SORTBY";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row;
                        }
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
}
?>
