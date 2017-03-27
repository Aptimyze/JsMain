<?php
class sugarcrm_campaigns extends TABLE
{
    public function __construct($dbname="")
    {
            parent::__construct($dbname);
    }

    public function getInfo($campaign_id)
    {
    	try{

			$sql = "SELECT name,content FROM sugarcrm.campaigns WHERE id =:CAMPAIGN_ID";
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
