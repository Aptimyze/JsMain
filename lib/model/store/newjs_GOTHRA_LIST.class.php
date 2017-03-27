<?php

class newjs_GOTHRA_LIST extends TABLE implements AutoSuggestor {

	public function __construct($dbname="")
	{	
	    parent::__construct($dbname);
  	}

	public function viewRecords ($like,$limit)
	{
        try {

            $sql = "Select SQL_CACHE DISTINCT(GOTHRA) from newjs.GOTHRA_LIST where GOTHRA like :LIKE limit :LIMIT";

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
        public function match ($GOTHRA)
        {
                try 
                {

                        $sql = "Select GOTHRA from newjs.GOTHRA_LIST where GOTHRA=:GOTHRA";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":GOTHRA",$GOTHRA, PDO::PARAM_STR);
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
