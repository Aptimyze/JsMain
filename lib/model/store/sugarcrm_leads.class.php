<?php
class sugarcrm_leads extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function checkByPhoneType($numberArray='',$isd=''){
                try
                {
                        $res=null;
                        $str='';
                        if($numberArray)
                        {
                                foreach($numberArray as $k=>$num)
                                {
                                        if($k!=0)
                                                $str.=", ";
                                        $str.=":mob".$k.", :mob0".$k.", :mobIsd".$k.", :mobIsdA".$k.", :mobIsd0".$k;
                                }
                        }

                        if($str)
                        {
                                $sql="SELECT id,phone_mobile FROM sugarcrm.leads WHERE phone_mobile IN (".$str.")";
                                $prep=$this->db->prepare($sql);
                                if($numberArray)
                                {
                                        foreach($numberArray as $k=>$num)
                                        {

                                                $prep->bindValue(":mob".$k,$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob0".$k,'0'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd".$k,$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsdA".$k,'+'.$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd0".$k,'0'.$isd.$num,PDO::PARAM_STR);
                                        }
                                }
                                $prep->execute();
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $res[$result['id']]["NUMBER"]=$result['phone_mobile'];
                                        $res[$result['id']]["TYPE"]="CRM_MOBILE";
                                }
                                $sql="SELECT id,phone_home FROM sugarcrm.leads WHERE phone_home IN (".$str.")";
                                $prep=$this->db->prepare($sql);
                                if($numberArray)
                                {
                                        foreach($numberArray as $k=>$num)
                                        {

                                                $prep->bindValue(":mob".$k,$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob0".$k,'0'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob91".$k,'91'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob91A".$k,'+91'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd0".$k,'0'.$isd.$num,PDO::PARAM_STR);
                                        }
                                }
                                $prep->execute();
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $res[$result['id']]['NUMBER']=$result['phone_home'];
                                        $res[$result['id']]['TYPE']="CRM_LANDLINE";
                                }

                        }
                        else
                                throw new jsException("No phone number as Input paramter");

                        return $res;

                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	/** Function insert added by Nitesh
        This function is used to insert record in the sugarcrm.leads table.
        * @param  $profileId Int
        * 
        **/
	public function updateSugarRegitrationCompletiton($username,$lead){
               
                if(!$username || !$lead)
                        throw new jsException("","username or lead IS BLANK IN insert() OF sugarcrm_leads.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set username_c=:username,converted=1,status=6,refered_by='Registration done by self' where l.id=lc.id_c and l.id=:lead";
					$res = $this->db->prepare($sql);
					
				  	$res->bindValue(":username", $username, PDO::PARAM_STR);	
				  	$res->bindValue(":lead", $lead, PDO::PARAM_STR);	
				  	
					$res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	/** Function insert added by Nitesh
        This function is used to select username from sugarcrm.leads table.
        * @param  $username String
        * @param  $lead String
        * 
        **/
	public function selectUsername($lead){
               
                if(!$lead)
                        throw new jsException("","lead IS BLANK IN selectUsername() OF sugarcrm_leads.class.php");

                try
                {
					$now = date("Y-m-d G:i:s");
					$sql = "SELECT user_name FROM sugarcrm.leads l, sugarcrm.users as u where l.assigned_user_id=u.id  and l.id=:lead";
					$prep = $this->db->prepare($sql);
					
				  	$prep->bindValue(":lead", $lead, PDO::PARAM_STR);	
				  	
					$prep->execute();
					$result=$prep->fetch(PDO::FETCH_ASSOC);
					return $result;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	public function getLead_CstmDataById($leadid){
                if(!$leadid)
                        throw new jsException("","lead IS BLANK IN getLead_CstmDataById of sugarcrm_leads.class.php");

                try
                {
					$sql_cstm = "SELECT * FROM sugarcrm.leads_cstm l where l.id_c=:leadid";
					$prep = $this->db->prepare($sql_cstm);
					
				  	$prep->bindValue(":leadid", $leadid, PDO::PARAM_STR);	
				  	
					$prep->execute();
					$result=$prep->fetch(PDO::FETCH_ASSOC);
					return $result;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}
	public function getLeadDataById($leadid){
                if(!$leadid)
                        throw new jsException("","lead IS BLANK IN getLeadDataById of sugarcrm_leads.class.php");

                try
                {
					$sql = "SELECT * FROM sugarcrm.leads l where l.id=:leadid";
					$prep = $this->db->prepare($sql);
					
				  	$prep->bindValue(":leadid", $leadid, PDO::PARAM_STR);	
				  	
					$prep->execute();
					$result=$prep->fetch(PDO::FETCH_ASSOC);
					return $result;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}

        public function getLeadDetailById($leadid){
                if(!$leadid)
                        throw new jsException("","lead IS BLANK IN getLeadDetailById of sugarcrm_leads.class.php");

                try
                {
                        $sql = "SELECT assistant,campaign_id,last_name,lead_source,phone_home,phone_mobile,date_entered,status FROM sugarcrm.leads WHERE id =:LEADID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":LEADID", $leadid, PDO::PARAM_INT);   
                        $prep->execute();
                        $res=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        
                return $res;
        }
        
	public function getMobileLeads()
	{
        	try
                {
                	//$todayStartTime = date("Y-m-d")." 00:00:00";
                	$todayEndTime = date("Y-m-d")." 23:59:59";
                        $sql = "SELECT l.id,l.phone_home,l.phone_mobile,c.enquirer_mobile_no_c,c.enquirer_landline_c FROM sugarcrm.leads as l JOIN sugarcrm.leads_cstm as c ON l.id=c.id_c WHERE l.assigned_user_id='' AND l.date_entered<=:TODAY_END_TIME AND l.deleted=0 and l.status IN ('13','24') and c.source_c='12'";
            		$prep = $this->db->prepare($sql);
           	 	//$prep->bindValue(":TODAY_START_TIME",$todayStartTime,PDO::PARAM_STR);
            		$prep->bindValue(":TODAY_END_TIME",$todayEndTime,PDO::PARAM_STR);
            		$prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
            		{
                	        if($res['phone_home'] || $res['phone_mobile'] || $res['enquirer_mobile_no_c'] || $res['enquirer_landline_c'])
                	                $profiles[] =$res['id'];
            		}
        	}
        	catch(Exception $e)
        	{
        	        throw new jsException($e);
        	}
        	return $profiles;
    	}
        
        public function getOtherLeads()
    	{
        	try
                {
        	        $dt_6day = date("Y-m-d",time()-6*86400)." 23:59:59";
                        $sql = "SELECT l.id,l.phone_home,l.phone_mobile,c.enquirer_mobile_no_c,c.enquirer_landline_c FROM sugarcrm.leads as l JOIN sugarcrm.leads_cstm as c ON l.id=c.id_c WHERE l.assigned_user_id='' AND l.date_entered<=:DT_6DAY AND l.deleted=0 and l.status IN ('13','24') and c.source_c!='12'";
            		$prep = $this->db->prepare($sql);
            		$prep->bindValue(":DT_6DAY",$dt_6day,PDO::PARAM_STR);
            		$prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
            		{
                	        if($res['phone_home'] || $res['phone_mobile'] || $res['enquirer_mobile_no_c'] || $res['enquirer_landline_c'])
                		        $profiles[] =$res['id'];
            		}
        	}
        	catch(Exception $e)
        	{
        	    throw new jsException($e);
        	}
        	return $profiles;
	}
	public function getLeadsWithPhone($phoneStr)
	{
                try
                {
                        $phoneArr = explode(",",$phoneStr);
                        foreach($phoneArr as $k=>$v)
                                $qArr[]=":PHONE".$k;
                        $qStr = implode(",",$qArr);
                        $sql = "SELECT phone_mobile FROM sugarcrm.leads where phone_mobile IN (".$qStr.");";
                        $prep = $this->db->prepare($sql);
                        foreach($phoneArr as $k=>$v)
                                $prep->bindValue(":PHONE".$k,$v,PDO::PARAM_STR);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                        $matchPhoneArr[] =$res['phone_mobile'];
                        }
                }
                catch(Exception $e)
                {
                    throw new jsException($e);
                }
                return $matchPhoneArr;
	}
	public function updateLeadCampaign($leadIdArr,$campaign_id)
	{
                try
                {
			foreach($leadIdArr as $k=>$v)
			{
				$queryArr[]=":LEAD_ID".$k;
			}
			$queryStr = implode(",",$queryArr);
			$sql = "UPDATE sugarcrm.leads l set campaign_id =:campaign_id where l.id IN (".$queryStr.")";
			$res = $this->db->prepare($sql);
			$res->bindValue(":campaign_id", $campaign_id, PDO::PARAM_STR);
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
