<?php
//This class is used to execute queries on newjs.RELIGION table

class NEWJS_RELIGION extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	/**
        This function fetches the MAPPED_MIN_VAL corresponding MIN_VALUE or MAPPED_MAX_VAL corresponding to MAX_VALUE from INCOME table.
        * @param  income value and type = 1 for MIN or type = 2 for MAX
        * @return MAPPED_MIN_VAL or MAPPED_MAX_VAL
        **/
	public function getDATA()
	{
		try
		{
			$sql = "SELECT SQL_CACHE VALUE,LABEL FROM newjs.RELIGION ORDER BY SORTBY";
			$res=$this->db->prepare($sql);
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[]=$row;
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
