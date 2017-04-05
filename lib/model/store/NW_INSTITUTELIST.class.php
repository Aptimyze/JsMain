<?php

class NW_INSTITUTELIST extends TABLE implements AutoSuggestor {

	public function __construct($dbname="")
	{	
	    parent::__construct($dbname);
  	}

	public function viewRecords ($like,$limit)
	{
        try {
            $sql = "Select SQL_CACHE DISTINCT(Institute) from newjs.NW_INSTITUTELIST where INSTITUTE like :LIKE or ABBR like :LIKE order by INSTITUTEID DESC LIMIT :RANGE ";
                
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LIKE",$like, PDO::PARAM_STR);
            $prep->bindValue(":RANGE", $limit, PDO::PARAM_INT);
			$prep->execute();
			$records = array();
            while ($result = $prep->fetch(PDO::FETCH_NUM)) {
                $records[]=$result[0];
			}
			return $records;			
		}
		catch (Exception $e) {
            throw new jsException($e);
		}
	}
        public function match ($Institute)
        {
                try 
                {

                        $sql = "Select INSTITUTE from newjs.NW_INSTITUTELIST where INSTITUTE=:Institute or ABBR=:Institute";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":Institute",$Institute, PDO::PARAM_STR);
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
