<?php

class billing_VARIABLE_DISCOUNT_OFFER_DURATION extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function deleteVariableDiscount($pid)
    {
        try{
            $todayDate = date("Y-m-d");
            $sql ="DELETE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getDiscountDetailsForProfile($profileid)
    {
        try
        {
            $sql="SELECT * FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION WHERE PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output[] = $result;
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }

        return $output;
    }

    public function getDiscountDetails($profileStr)
    {
        if(!$profileStr)
            throw new jsException("","profiles blank passed in");
        try
        {
            $profileStr=trim($profileStr);
            $sql="SELECT * from billing.VARIABLE_DISCOUNT_OFFER_DURATION WHERE PROFILEID IN($profileStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $dataset[$result['PROFILEID']]=$result;
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $dataset;
    }

    public function addVdOfferDuration($profileid)
    {
        try
        {
            $sql ="INSERT IGNORE INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION SELECT * FROM billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    
    
    // Delete records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts
    public function deleteExpiredDiscount($todayDate){
        try{
            $sql ="delete billing.VARIABLE_DISCOUNT_OFFER_DURATION.* from billing.VARIABLE_DISCOUNT_OFFER_DURATION, billing.VARIABLE_DISCOUNT_BACKUP_1DAY b where billing.VARIABLE_DISCOUNT_OFFER_DURATION.PROFILEID=b.PROFILEID AND b.EDATE<:EDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":EDATE", $todayDate, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    // Delete records from VARIABLE_DISCOUNT_OFFER_DURATION 
    public function deleteDiscountRecord(){
        try{
        	$sql = "SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT";
        	$prep = $this->db->prepare($sql);
        	$prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles1[]=$result['PROFILEID'];
            }
            if(is_array($profiles1) & !empty($profiles1)){
	            $sql2="SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION";
	            $res = $this->db->prepare($sql2);
	            $res->execute();
	            while($result2=$res->fetch(PDO::FETCH_ASSOC))
	            {
	                $profiles2[]=$result2['PROFILEID'];
	            }
	        }

	        $diffArr = array_diff($profiles2, $profiles1);
	        $diffStr = implode(",",$diffArr);

	        if(!empty($diffStr)){
	        	$sql3="DELETE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION WHERE PROFILEID IN ($diffStr)";
	            $res = $this->db->prepare($sql3);
	            $res->execute();
	        }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
       * Function to add entry in billing.VARIABLE_DISCOUNT_OFFER_DURATION for VD
       *
       * @param  $params,$sendAlert
       * @return none
       */ 
    public function addVDOfferDurationServiceWise($params,$sendAlert=false)
    {
        $valuesStr = "";
        try
        {
            
            if(is_array($params["SERVICE"]) && $params["SERVICE"])
            {
                if(count($params["SERVICE"])==1)
                    $valuesStr = $valuesStr."(:PROFILEID0,:SERVICE0,:DISC10,:DISC20,:DISC30,:DISC60,:DISC120,:DISCL0)";
                else
                {
                    foreach ($params["SERVICE"] as $key => $service) {
                        $valuesStr = $valuesStr."(:PROFILEID".$key.",:SERVICE".$key.",:DISC1".$key.",:DISC2".$key.",:DISC3".$key.",:DISC6".$key.",:DISC12".$key.",:DISCL".$key."),"; 
                    }
                    $valuesStr = substr($valuesStr, 0,-1);
                }
                $sql = "INSERT IGNORE INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION VALUES ".$valuesStr;
                $res = $this->db->prepare($sql);

                foreach ($params["SERVICE"] as $key => $service) {
                    $res->bindValue(":PROFILEID".$key, $params["PROFILEID"], PDO::PARAM_INT);
                    $res->bindValue(":SERVICE".$key,$service, PDO::PARAM_STR);
		    $res->bindValue(":DISC1".$key, $params["DISC1"], PDO::PARAM_INT);	
		    $res->bindValue(":DISC2".$key, $params["DISC2"], PDO::PARAM_INT);	
                    $res->bindValue(":DISC3".$key, $params["DISC3"], PDO::PARAM_INT);
                    $res->bindValue(":DISC6".$key, $params["DISC6"], PDO::PARAM_INT);
                    $res->bindValue(":DISC12".$key, $params["DISC12"], PDO::PARAM_INT);
                    $res->bindValue(":DISCL".$key, $params["DISCL"], PDO::PARAM_INT);
                }

                $res->execute();
              
            }
            
        }
        catch(Exception $e)
        {
            if($sendAlert==true)
            {
                $message = "Error in running populateVDEntriesFromTempTable cron in addVDOfferDurationServiceWise func of billing_VARIABLE_DISCOUNT_OFFER_DURATION.class.php";
                CRMAlertManager::sendMailAlert($message,"VDUploadFromTable");
            }
            throw new jsException($e);
        }
    }
}   
?>
