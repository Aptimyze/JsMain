<?php
class billing_EXTENDED_VARIABLE_DISCOUNT extends TABLE{
       
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }

	public function addVDDurationServiceWise($params,$sendAlert=false)
    {
        $valuesStr = "";
        try
        {
            if(is_array($params) && is_array($params["discounts"]))
            {  
                foreach ($params["discounts"] as $key => $service) {
                    $valuesStr = $valuesStr."(:PROFILEID".$key.",:SERVICE".$key.",:DISCOUNT".$key.",:DISC1".$key.",:DISC2".$key.",:DISC3".$key.",:DISC6".$key.",:DISC12".$key.",:DISCL".$key.",:SDATE".$key.",:EDATE".$key.",:ENTRY_DT".$key."),"; 
                }
                $valuesStr = substr($valuesStr, 0,-1);
                
                $sql = "INSERT IGNORE INTO billing.EXTENDED_VARIABLE_DISCOUNT VALUES ".$valuesStr;
                $res = $this->db->prepare($sql);

                foreach ($params["discounts"] as $key => $details) {
                    $res->bindValue(":PROFILEID".$key, $params["PROFILEID"], PDO::PARAM_INT);
                    $res->bindValue(":SERVICE".$key,$details["SERVICE"], PDO::PARAM_STR);
                    $res->bindValue(":DISCOUNT".$key, $params["DISCOUNT"], PDO::PARAM_INT);
                    $res->bindValue(":DISC1".$key, $details["1_DISCOUNT"], PDO::PARAM_INT);   
                    $res->bindValue(":DISC2".$key, $details["2_DISCOUNT"], PDO::PARAM_INT);   
                    $res->bindValue(":DISC3".$key, $details["3_DISCOUNT"], PDO::PARAM_INT);
                    $res->bindValue(":DISC6".$key, $details["6_DISCOUNT"], PDO::PARAM_INT);
                    $res->bindValue(":DISC12".$key, $details["12_DISCOUNT"], PDO::PARAM_INT);
                    $res->bindValue(":DISCL".$key, $details["L_DISCOUNT"], PDO::PARAM_INT);
                    $res->bindValue(":SDATE".$key, $params["SDATE"], PDO::PARAM_STR);
                    $res->bindValue(":EDATE".$key, $params["EDATE"], PDO::PARAM_STR);
                    $res->bindValue(":ENTRY_DT".$key,$params["ENTRY_DT"], PDO::PARAM_STR);
                }
                $res->execute();
            }
        }
        catch(Exception $e)
        {
            if($sendAlert==true)
            {
                $message = "Error in addVDOfferDurationServiceWise func of billing_EXTENDED_VARIABLE_DISCOUNT.class.php";
                CRMAlertManager::sendMailAlert($message,"VDUploadFromTable");
            }
            throw new jsException($e);
        }
    } 

    /*function to get records from table
    * @params : $fields(","separated list or *),$limit,$offset
    * @return: rows 
    */
    public function fetchAllRecords($fields="*",$entryDate)
    {
        try
        {
            $sql = "SELECT ".$fields." FROM billing.EXTENDED_VARIABLE_DISCOUNT WHERE SDATE = :SDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":SDATE",$entryDate, PDO::PARAM_STR);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC)){
                $vdData[] = $result;
            }
            return $vdData;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    } 
}
?>
