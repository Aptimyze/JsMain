<?php
class incentive_UNALLOTED_FAILED_PAYMENT extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        public function editFailedPayment($profileId)
        {
                if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN incentive_UNALLOTED_FAILED_PAYMENT.class.php");
                try
                {
                        $sql = "UPDATE incentive.UNALLOTED_FAILED_PAYMENT SET ALLOCATED='Y' WHERE PROFILEID = :PROFILEID";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }
}
