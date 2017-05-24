<?php
class billing_SERVICE_ACTIVATION_LOG extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function addLog($type,$entryBy, $profileid, $billid, $serviceid, $activated='', $status='',$activatedOn='', $activateOn='', $expiryDt='')
    {
        try
        {
            $now=date('Y-m-d',time());
            $sql = "INSERT INTO billing.SERVICE_ACTIVATION_LOG (PROFILEID,BILLID,SERVICEID,ACTIVATED,ACTIVE,ACTIVATED_ON,ACTIVATE_ON,EXPIRY_DT,ENTRY_BY,ENTRY_DT,TYPE) VALUES(:PROFILEID,:BILLID,:SERVICEID,:ACTIVATED,:ACTIVE,:ACTIVATED_ON,:ACTIVATE_ON,:EXPIRY_DT,:ENTRY_BY,now(),:TYPE)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_STR);
	    $prep->bindValue(":SERVICEID",$serviceid,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVATED",$activated,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVE",$status,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVATED_ON",$activatedOn,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVATE_ON",$activateOn,PDO::PARAM_STR);
            $prep->bindValue(":EXPIRY_DT",$expiryDt,PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_BY",$entryBy,PDO::PARAM_STR);
	    $prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
            $prep->execute();
        }catch (Exception $ex)
        {
            throw new jsException($e);
        }
    }
}
