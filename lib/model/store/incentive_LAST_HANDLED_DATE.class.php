<?php
class incentive_LAST_HANDLED_DATE extends TABLE   
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function getHandledDate($processId)
        {
                try
                {
                        $sql="SELECT DATE from incentive.LAST_HANDLED_DATE WHERE SOURCE_ID=:SOURCE_ID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SOURCE_ID",$processId,PDO::PARAM_INT);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $date =$result['DATE'];
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
                return $date;
        }
        public function setHandledDate($processId,$dateSet)
        {
                try
                {
                        $sql="update incentive.LAST_HANDLED_DATE SET DATE=:DATE WHERE SOURCE_ID=:SOURCE_ID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SOURCE_ID",$processId,PDO::PARAM_INT);
			$prep->bindValue(":DATE",$dateSet,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }
}
?>
