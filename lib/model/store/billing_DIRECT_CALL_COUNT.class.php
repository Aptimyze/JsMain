<?php
class billing_DIRECT_CALL_COUNT extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function getDirectCallCountForServiceArr($serviceArr)
    {
        if(empty($serviceArr)){
            throw new jsException("Empty SERVICEID list passed in getDirectCallCountForServiceArr");    
        }
        if(is_array($serviceArr)){
            $serviceStr = "'".implode("','", $serviceArr)."'";
        } else {
            $serviceStr = "'".$serviceArr."'";
        }
        $serviceStr = str_replace("''", "'", $serviceStr);
        try
        {
            $sql="SELECT SQL_CACHE SERVICEID,COUNT FROM billing.DIRECT_CALL_COUNT WHERE SERVICEID IN ($serviceStr)";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $outputArr[$result['SERVICEID']]= $result['COUNT'];
            }
            return $outputArr;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }
}
?>
