<?php
class newjs_CONNECT extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function updateSubscriptionForId($subscription, $id)
    {
        try
        {
            $sql = "UPDATE newjs.CONNECT SET SUBSCRIPTION=:SUBSCRIPTION WHERE ID=:ID" ;
            $res = $this->db->prepare($sql);
            $res->bindValue(":SUBSCRIPTION", $subscription, PDO::PARAM_STR);
            $res->bindValue(":ID", $id, PDO::PARAM_INT);
            $res->execute();
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
