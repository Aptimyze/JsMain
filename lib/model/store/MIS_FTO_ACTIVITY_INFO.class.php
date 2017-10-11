<?php
class FTO_ACTIVITY_INFO extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function getFirstActivities($profileid)
        {
                try
                {
                        $sql ="SELECT * FROM MIS.FTO_ACTIVITY_INFO WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
				return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
