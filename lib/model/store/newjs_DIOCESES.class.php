<?php

class newjs_DIOCESES extends TABLE implements AutoSuggestor {

	public function __construct($dbname="")
	{	
	    parent::__construct($dbname);
  	}

	public function viewRecords ($like,$limit)
	{
        try {

            $sql = "Select SQL_CACHE DISTINCT(DIOCESES) from newjs.DIOCESES where DIOCESES like :LIKE limit :LIMIT";

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
        public function match ($DIOCESES)
        {
                try
                {

                        $sql = "Select DIOCESES from newjs.DIOCESES where DIOCESES=:DIOCESES";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DIOCESES",$DIOCESES, PDO::PARAM_STR);
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
