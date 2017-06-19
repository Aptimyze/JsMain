<?php
//This class is use to get general table information from information schema or queries which are not related to any specific database

class GeneralStore extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function is used to execute SHOW TABLE STATUS query
	$param - db name, table name array, if updated time of table is needed (optional)
	@return - result set array
	*/
	public function getTablesInformation($db,$tableArr,$updatedTime='')
	{
		if(!$db || !$tableArr || !is_array($tableArr))
			throw new jsException("","Database name OR table name IS BLANK IN getTablesInformation() OF GeneralStore.class.php");

		try
		{
			$i=0;
			foreach($tableArr as $k=>$v)
			{
				$param[] = ":PARAM".$i;
				$i++;
			}
			
			$sql = "SHOW TABLE STATUS FROM ".$db." WHERE Name IN (".implode(",",$param).")";	//BINDING OF DATABASE NAME IS NOT REQUIRED AS THE SYNTAX OF THIS QUERY DOES NOT CONSIDER DATABASE NAME AS A STRING
			$res = $this->db->prepare($sql);
			//$res->bindValue(":DATABASE", $db, PDO::PARAM_STR);
			$i=0;
			foreach($tableArr as $k=>$v)
			{
				$res->bindValue(":PARAM".$i, $v, PDO::PARAM_STR);
				$i++;
			}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				if($updatedTime)
					$output[$row["Name"]] = $row["Update_time"];
				else
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
