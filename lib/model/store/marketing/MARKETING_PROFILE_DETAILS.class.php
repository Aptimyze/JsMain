<?php

class MARKETING_PROFILE_DETAILS extends TABLE
{
	public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

    public function insertProfileDetails($profileDetailsArr)
    {
    	try
    	{
    		$sql = "INSERT INTO MARKETING.PROFILE_DETAILS (";
    		foreach($profileDetailsArr as $key=>$values)
    		{
    			$sqlColumns .= $key.",";
    			$sqlAppend .= ":".$key.",";
    		}
    		$sql .= rtrim($sqlColumns,",").") VALUES (".rtrim($sqlAppend,",").")";
			$res = $this->db->prepare($sql);
			foreach($profileDetailsArr as $key=>$values)
			{
				if($key=="AGE")
				{
					$res->bindValue(":".$key, $values, PDO::PARAM_INT);
				}
				else
				{
					$res->bindValue(":".$key, $values, PDO::PARAM_STR);
				}
			}
			$res->execute();
    	}
    	catch (PDOException $e)
            {
                throw new jsException($e);
            }
    }
}