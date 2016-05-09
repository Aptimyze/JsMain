<?php
class billing_FESTIVE_LOG_REVAMP extends TABLE
{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function getActiveOfferDetails()
    {
        try
        {
            $sql ="SELECT * from billing.FESTIVE_LOG_REVAMP WHERE STATUS='Active' ORDER BY ID DESC limit 1";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
                return $result;
            return;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }

    }

    public function deActivateOffer($id)
    {
        try
        {
            $sql="update billing.FESTIVE_LOG_REVAMP SET STATUS='Inactive',DE_ACTIVATION_DT=now() where ID=:ID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ID", $id, PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }

    }
    
    public function getLastActiveServices($billing_dt)
    {
        try
        {
            $sql="SELECT ID FROM billing.`FESTIVE_LOG_REVAMP` WHERE :BILLING_DT >= `ACTIVATION_DT` AND :BILLING_DT <= IF(`DE_ACTIVATION_DT`,`DE_ACTIVATION_DT`,`END_DT`)";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLING_DT", $billing_dt, PDO::PARAM_STR);
            $prep->execute();
            $row=$prep->fetch(PDO::FETCH_ASSOC);
            $res = $row['ID'];
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
        return $res;
    }

    public function getFestiveFlag(){
        try{
            $sql = "SELECT SQL_CACHE STATUS,END_DT FROM billing.FESTIVE_LOG_REVAMP ORDER BY ID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            if($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $status=$row['STATUS'];
                $cur_date=date("Y-m-d");
                if($status=='Active' && $row['END_DT']>=$cur_date)
                    $isFestive='1';
                else
                    $isFestive='0';
            }
        } catch(PDOException $e){
            throw new jsException($e);
        }
        return $isFestive;
    }

    public function getFestivalBanner(){
        try{
            $sql = "SELECT SQL_CACHE STATUS,END_DT,FESTIVAL FROM billing.FESTIVE_LOG_REVAMP ORDER BY ID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            if($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $id = $row['FESTIVAL'];
                $sql2 = "SELECT FESTIVAL ,IMAGE_URL FROM billing.`FESTIVE_BANNER` WHERE ID=:ID";
                $prep2 = $this->db->prepare($sql2);
                $prep2->bindValue(":ID", $id, PDO::PARAM_STR);
                $prep2->execute();
                if($row2 = $prep2->fetch(PDO::FETCH_ASSOC)){
                    $festId['fest_label'] = $row2['FESTIVAL'];
                    $festId['fest_image_url'] = $row2['IMAGE_URL'];
                }
                return $festId;
            }
        } catch(PDOException $e){
            throw new jsException($e);
        }
    }

}   
?>
