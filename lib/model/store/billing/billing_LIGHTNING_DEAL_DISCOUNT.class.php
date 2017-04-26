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

    public function filterDiscountActivatedProfiles($pid="",$viewed='V',$offsetDt="")
    {
        try {
            if (is_array($pid))
                $str = "(" . implode(",", $pid) . ")";
            else if($pid!="")
                $str = $pid;
            $sql = "SELECT DISTINCT PROFILEID FROM billing.LIGHTNING_DEAL_DISCOUNT WHERE";
            $addAnd = false;
            if (is_array($pid)){
                $sql = $sql . " PROFILEID IN " . $str;
                $addAnd = true;
            }
            else if($pid!=""){
                $sql = $sql . " PROFILEID = " . $str;
                $addAnd = true;
            }
            if($addAnd == true){
                $sql .= " AND";
            }
           	if(!empty($offsetDt)){
           		$sql .= " ENTRY_DT>=:ENTRY_DT";
           	}
           	$sql .= " AND STATUS=:STATUS";
            $prep = $this->db->prepare($sql);
            if(!empty($offsetDt)){
            	$prep->bindValue(":ENTRY_DT", $offsetDt,PDO::PARAM_STR);
            }
            $prep->bindValue(":STATUS", $viewed,PDO::PARAM_STR);
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
