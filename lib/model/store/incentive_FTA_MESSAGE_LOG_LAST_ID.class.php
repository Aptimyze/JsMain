<?php
class incentive_FTA_MESSAGE_LOG_LAST_ID extends TABLE   
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function getAllIds()
        {
                try
                {
                        $sql="SELECT LAST_ID,SHARD from incentive.FTA_MESSAGE_LOG_LAST_ID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
                        	$lastId_arr[$result['SHARD']]= $result["LAST_ID"];
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
                return $lastId_arr;
        }
        public function setId($key,$maxId)
        {
                try
                {
                        $sql="update incentive.FTA_MESSAGE_LOG_LAST_ID SET LAST_ID=:LAST_ID WHERE SHARD=:SHARD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SHARD",$key,PDO::PARAM_INT);
			$prep->bindValue(":LAST_ID",$maxId,PDO::PARAM_INT);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }
}
?>
