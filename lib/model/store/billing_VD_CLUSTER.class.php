<?php
class billing_VD_CLUSTER extends TABLE{
       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getClusterDetails()
        {
                try{
	                $sql="SELECT * from billing.VD_CLUSTER";
                      	$prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$cluster	=$result['CLUSTER'];
				$criteria	=$result['CRITERIA'];
                        	$dataSet[$cluster][$criteria]['VALUE1'] =$result['VALUE1'];
				$dataSet[$cluster][$criteria]['VALUE2'] =$result['VALUE2'];
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $dataSet;
        }
	public function addCluster($cluster,$criteria,$value1,$value2)
	{
                try{
                        $sql = "INSERT IGNORE INTO billing.VD_CLUSTER (CLUSTER,CRITERIA,VALUE1,VALUE2,ENTRY_DT) VALUES(:CLUSTER,:CRITERIA,:VALUE1,:VALUE2,now()) ";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":CLUSTER", $cluster, PDO::PARAM_STR);
                        $res->bindValue(":CRITERIA", $criteria, PDO::PARAM_STR);
                        $res->bindValue(":VALUE1", $value1, PDO::PARAM_STR);
                        $res->bindValue(":VALUE2", $value2, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(Exception $e)
                {
                    throw new jsException($e);
                }
	}
        public function deleteCluster($clusterName)
        {
                try{
                        $sql="DELETE from billing.VD_CLUSTER WHERE CLUSTER=:CLUSTER";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":CLUSTER", $clusterName, PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }

}
?>
