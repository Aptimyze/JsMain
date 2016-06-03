<?php
class billing_VOUCHER_MARKING extends TABLE{

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function getVoucherCodeForProfileid($profileid)
    {
        try 
        {
            $sql="SELECT VOUCHER_CODE FROM billing.VOUCHER_MARKING WHERE PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            if($res=$prep->execute())
            {
                $result = $res['VOUCHER_CODE'];
            }
        }
        catch(PDOException $e)
        {
            
            throw new jsException($e);
        }
        return $result;
    }
}
?>
