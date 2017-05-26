<?php   

class mmm99_RESPONSE extends TABLE
{
    public function  __construct($dbname="99_master")
    {   
        parent::__construct($dbname);
    }   
        
    public function fetchResponse($mailerIdList,$mailerDataArr)
    {       
        $sql = "SELECT MAILER_ID,COUNT(*) AS CNT from property.MMM_RESPONSE where MAILER_ID IN ($mailerIdList) GROUP BY MAILER_ID";
        $res = $this->db->prepare($sql);
        $res->execute();

        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $mailerDataArr[$row['MAILER_ID']]['RESPONSE'] = $row['CNT'];
        }
        
        return $mailerDataArr;

    }   

	public function mapCityIdToCity()
    {
        $sql = "select VALUE,LABEL from locations.LOCATION where ACTIVATED = 'Y'";
        $res = $this->db->prepare($sql);
        $res->execute();

        $cityMapArr = array();
         while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $cityMapArr[$row['VALUE']] = $row['LABEL'];
        }

        return $cityMapArr;
    }
        
}
        
?>      

