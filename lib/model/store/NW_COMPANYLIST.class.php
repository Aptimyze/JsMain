<?php

class NW_COMPANYLIST extends TABLE implements AutoSuggestor {

	public function __construct($dbname="")
	{	
	    parent::__construct($dbname);
  	}

	public function viewRecords ($like,$limit)
	{
        try {

            $sql = "Select SQL_CACHE DISTINCT(Company) from newjs.NW_COMPANYLIST where company like :LIKE limit :LIMIT";

			$prep = $this->db->prepare($sql);
			$prep->bindValue(":LIKE", $like, PDO::PARAM_STR);
            $prep->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
			$prep->execute();
			$records = array();
			while ($result = $prep->fetch(PDO::FETCH_NUM)) {
				$records[] = $result[0];
			}
			return $records;
		}
		catch (Exception $e) {
          throw new jsException($e);
		}
	}
        public function match ($Company)
        {
                try 
                {

                        $sql = "Select Company from newjs.NW_COMPANYLIST where Company=:Company";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":Company",$Company, PDO::PARAM_STR);
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
