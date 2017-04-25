<?php
class billing_LIGHTNING_DEAL_DISCOUNT extends TABLE{
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }

    
    /*func truncateTable
    *truncate table
    *@param : none
    */
    public function truncateTable()
    {
		try{
			
			$sql = "TRUNCATE billing.LIGHTNING_DEAL_DISCOUNT";
			$res=$this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e){
		        throw new jsException($e);
		}
    }

    public function filterDiscountActivatedProfiles($pid,$viewed='Y',$offsetDt="")
    {
        try {
            if (is_array($pid))
                $str = "(" . implode(",", $pid) . ")";
            else
                $str = $pid;
            $sql = "SELECT DISTINCT PROFILEID FROM billing.LIGHTNING_DEAL_DISCOUNT WHERE PROFILEID";
            if (is_array($pid))
                $sql = $sql . " IN " . $str;
            else
                $sql = $sql . " = " . $str;
           	if(!empty($offsetDt)){
           		$sql .= " AND SDATE>=:SDATE";
           	}
           	$sql .= " AND VIEWED=:VIEWED";
            $prep = $this->db->prepare($sql);
            if(!empty($offsetDt)){
            	$prep->bindValue(":SDATE", $offsetDt,PDO::PARAM_STR);
            }
            $prep->bindValue(":VIEWED", $viewed,PDO::PARAM_STR);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profilesArr[] = $res['PROFILEID'];
            return $profilesArr;
        } catch (Exception $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }

    }
}
?>
