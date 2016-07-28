<?php
class sugarcrm_campaigns_cstm extends TABLE
{
    public function __construct($dbname="")
    {
            parent::__construct($dbname);
    }

    public function getInfo($campaign_id)
    {
    	try{

		    $sql = "SELECT newspaper_c,edition_c,newspaper_edition_c,email_id_c,mobile_no_c FROM sugarcrm.campaigns_cstm WHERE id_c =:CAMPAIGN_ID";
		    $prep = $this->db->prepare($sql);			
		    $prep->bindValue(":CAMPAIGN_ID",$campaign_id,PDO::PARAM_STR);
		    $prep->execute();
		    $res=$prep->fetch(PDO::FETCH_ASSOC);
    	}

        catch(Exception $e){
                throw new jsException($e);
        }
        return $res;
    }
}
?>
