<?php
class incentive_LARGE_FILE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function getLargeFileData()
        {
                try
                {
                        $sql ="SELECT DATA_LIMIT,LEAD_ID_SUFFIX FROM incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        return $result; 
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
