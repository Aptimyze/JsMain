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

    public function insertInLightningDealDisc($params){
        if(is_array($params)){
            try{
                $sql = "INSERT INTO billing.LIGHTNING_DEAL_DISCOUNT (`PROFILEID`,`DISCOUNT`,`ENTRY_DT`,`STATUS`) VALUES (:PROFILEID, :DISCOUNT, :ENTRY_DT, :STATUS)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $params["PROFILEID"], PDO::PARAM_INT);
                $res->bindValue(":DISCOUNT", $params["DISCOUNT"], PDO::PARAM_INT);
                $res->bindValue(":ENTRY_DT", $params["ENTRY_DT"], PDO::PARAM_STR);
                $res->bindValue(":STATUS", $params["STATUS"], PDO::PARAM_STR);
                $res->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }

    public function fetchDiscountDetails($pid,$currentTime=""){
        if(!$pid){
            throw new Exception("Blank pid passed in fetchDiscountDetails in billing_LIGHTNING_DEAL_DISCOUNT class");
        }
        try{
            $sql = "SELECT DISCOUNT,EDATE FROM billing.LIGHTNING_DEAL_DISCOUNT WHERE PROFILEID = :PROFILEID AND STATUS != :STATUS";
            if($currentTime!=""){
                $sql .= " AND SDATE<=:CURRENT_TIME AND EDATE>=:CURRENT_TIME";
            }
            $res = $this->db->prepare($sql);
            if($currentTime != ""){
                $res->bindValue(":CURRENT_TIME", $currentTime, PDO::PARAM_STR);
            }
            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $res->bindValue(":STATUS", 'A', PDO::PARAM_STR);
            $res->execute();
            $row = $res->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
