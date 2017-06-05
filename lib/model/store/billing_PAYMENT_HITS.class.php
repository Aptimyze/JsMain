<?php
class BILLING_PAYMENT_HITS extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getMembershiptHitCount($profileid,$entryDt)
        {
                try
                {
                        if($profileid && $entryDt)
                        {
                                $sql="SELECT count(*) cnt FROM billing.PAYMENT_HITS WHERE PROFILEID = :PROFILEID AND ENTRY_DT>:ENTRY_DT";
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                                $prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
                                $prep->execute();
                                if($result = $prep->fetch(PDO::FETCH_ASSOC)){
                                        $totCnt= $result['cnt'];
                                        return $totCnt;
                                }
                                return;
                        }
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

        public function addPaymentHits($profileid, $pgNo, $tabNo, $user_agent)
        {
        	try
        	{
        		$dt = date("Y-m-d H:i:s");
        		if($profileid){
        			$sql="INSERT INTO billing.PAYMENT_HITS(PROFILEID,PAGE,TAB_BUTTON,USER_AGENT,ENTRY_DT) VALUES(:PROFILEID,:PAGE,:TAB_BUTTON,:USER_AGENT,:ENTRY_DT)";
        		} else {
        			$sql="INSERT INTO billing.PAYMENT_HITS(PAGE,TAB_BUTTON,USER_AGENT,ENTRY_DT) VALUES(:PAGE,:TAB_BUTTON,:USER_AGENT,:ENTRY_DT)";
        		}
        		$prep=$this->db->prepare($sql);
        		if($profileid){
	        		$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
        		}
        		$prep->bindValue(":PAGE",$pgNo,PDO::PARAM_INT);
        		$prep->bindValue(":TAB_BUTTON",$tabNo,PDO::PARAM_INT);
        		$prep->bindValue(":USER_AGENT",$user_agent,PDO::PARAM_STR);
        		$prep->bindValue(":ENTRY_DT",$dt,PDO::PARAM_STR);
        		$prep->execute();
        	}
        	catch(PDOException $e)
        	{
        		/*** echo the sql statement and error message ***/
        		throw new jsException($e);
        	}
        }
}
?>
