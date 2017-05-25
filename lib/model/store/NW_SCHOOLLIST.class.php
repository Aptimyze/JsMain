<?php

class NW_SCHOOLLIST extends TABLE implements AutoSuggestor {

	public function __construct($dbname="")
	{	
	    parent::__construct($dbname);
  	}

	public function viewRecords ($like,$limit)
	{
        try {
            
            $sql = "Select SQL_CACHE DISTINCT(School) from newjs.NW_SCHOOLLIST where SCHOOL LIKE :LIKE LIMIT :LIMIT ";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LIKE",$like, PDO::PARAM_STR);
            $prep->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
			$prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_NUM)) {
                $records[]=$result[0];
			}
			return $records;			
		}
		catch (Exception $e) {
            throw new jsException($e);
		}
	}
        public function match ($school)
        {
		try 
		{

			$sql = "Select School from newjs.NW_SCHOOLLIST where SCHOOL=:SCHOOL";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SCHOOL",$school, PDO::PARAM_STR);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_NUM)) 
			{
				$records[]=$result[0];
			}
			return $records;
		}
		catch (Exception $e) {
			throw new jsException($e);
		}
	}
} 	

?>
