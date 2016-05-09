<?php
class incentive_CAMPAIGN 
{       		
        public function getCompaigns()
        {
                try
                {
						$sql="SELECT CAMPAIGN FROM incentive.CAMPAIGN WHERE ACTIVE='Y'";
						$prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
						{
							$campaigns[]=$res["CAMPAIGN"];
						}
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $campaigns;
        }	
}
?>
