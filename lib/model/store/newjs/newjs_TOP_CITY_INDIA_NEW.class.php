<?php

class newjs_TOP_CITY_INDIA_NEW extends TABLE
{
	public function __construct($dbname = "") 
	{
  		parent::__construct($dbname);
 	}

 	public function getTopCitiesIndia()
 	{
 		try
 		{
 			$sql = "SELECT SQL_CACHE ID,VALUE,LABEL,SORTBY FROM newjs.TOP_CITY_INDIA_NEW";
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