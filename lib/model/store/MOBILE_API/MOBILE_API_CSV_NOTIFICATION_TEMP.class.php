<?php
class MOBILE_API_CSV_NOTIFICATION_TEMP extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->tableName ='MOBILE_API.CSV_NOTIFICATION_TEMP';
        }
        public function truncate()
        {
                $sql = "TRUNCATE TABLE ". $this->tableName;
                $res = $this->db->prepare($sql);
                $res->execute();
        }
	public function insertRecord($fileName)
	{
		if(!$fileName)
			throw new jsException("","Upload file does not exist");
		try{
			$sqlInsert = "LOAD DATA LOCAL INFILE '".$fileName."' INTO TABLE MOBILE_API.CSV_NOTIFICATION_TEMP FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->execute();
			return true;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
        public function getData()
        {
                try
                {
                        $sql="SELECT * FROM MOBILE_API.CSV_NOTIFICATION_TEMP";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                                $data[]=$res;
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $data;
        }
}
?>
