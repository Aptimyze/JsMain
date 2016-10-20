<?php
class sugarcrm_leads_cstm extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function getUsernameFromLead($leadIdArr=''){
                try
                {
			$str='';
                        $res=null;
			$count=count($leadIdArr);
			for($i=0;$i<$count;$i++)
			{
				if($i!=0)
					$str.=", ";
				$str.=":id".$i;
			}
                        if($leadIdArr)
                        {
                                $sql="SELECT jsprofileid_c,id_c FROM sugarcrm.leads_cstm WHERE id_c IN (".$str.")";
                                $prep=$this->db->prepare($sql);
				$i=0;
				foreach($leadIdArr as $k=>$idArr)
				{
                                	$prep->bindValue(":id".$i,$k,PDO::PARAM_STR);	
					$i++;
				}
                                $prep->execute();
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $leadIdArr[$result['id_c']]['USERNAME']=$result['jsprofileid_c'];
                                }

                        }
                        else
                                throw new jsException("No leadId as Input paramter");

                        return $leadIdArr;

                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}


        public function getDetails($profileid)
        {
                try{

                    $sql = "SELECT age_c,date_birth_c,gender_c,height_c,marital_status_c,religion_c,mother_tongue_c,caste_c,education_c,occupation_c,income_c,manglik_c,source_c,enquirer_mobile_no_c,enquirer_landline_c,score_c,std_c,std_enquirer_c,jsprofileid_c FROM sugarcrm.leads_cstm WHERE id_c =:PROFILEID";
               
                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                    $prep->execute();
                    $res=$prep->fetch(PDO::FETCH_ASSOC);
                }

                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }

        public function getMaxMinScore()
        {
                try{

                    $sql = "SELECT max(score_c) as max,min(score_c) as min FROM sugarcrm.leads_cstm";
                    $prep = $this->db->prepare($sql);
                    $prep->execute();
                    $res=$prep->fetch(PDO::FETCH_ASSOC);
                }

                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }
        public function updateLeadSource($leadIdArr,$sourceid)
        {
                try
                {
                        foreach($leadIdArr as $k=>$v)
                        {
                                $queryArr[]=":LEAD_ID".$k;
                        }
                        $queryStr = implode(",",$queryArr);
                        $sql = "UPDATE sugarcrm.leads_cstm c set source_c =:source_c where c.id_c IN (".$queryStr.")";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":source_c", $sourceid, PDO::PARAM_STR);
                        foreach($leadIdArr as $k=>$v)
                                $res->bindValue(":LEAD_ID".$k, $v, PDO::PARAM_STR);
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
