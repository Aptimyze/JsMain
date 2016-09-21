<?php
class sugarcrm_email_addresses extends TABLE
{
    public function __construct($dbname="")
    {
            parent::__construct($dbname);
    }

    public function getEmailAddress($email_address_id)
    {
    	try{

			$sql = "SELECT email_address FROM sugarcrm.email_addresses where id=:EMAIL_ID";
		    $prep = $this->db->prepare($sql);
		    $prep->bindValue(":EMAIL_ID",$email_address_id,PDO::PARAM_STR);
		    $prep->execute();
		    $res=$prep->fetch(PDO::FETCH_ASSOC);
            $email_address = $res['email_address'];

    	}

        catch(Exception $e){
                throw new jsException($e);
        }
        return $email_address;
    }

    public function getEmailAddressLeadId($email_address_id_arr)
    {
        try{
                        foreach($email_address_id_arr as $k=>$v)
                        {
                                $queryArr[]=":EMAIL_ID".$k;
                        }
			$queryStr = implode(",",$queryArr);
                        $sql = "SELECT R.bean_id as leadId FROM sugarcrm.email_addresses E JOIN sugarcrm.email_addr_bean_rel R ON E.id=R.email_address_id where E.email_address IN (".$queryStr.")";
                    $prep = $this->db->prepare($sql);
			
		foreach($email_address_id_arr as $k=>$v)
		{
                    $prep->bindValue(":EMAIL_ID".$k,$v,PDO::PARAM_STR);
		}
                    $prep->execute();
                    while($res=$prep->fetch(PDO::FETCH_ASSOC))
			$leadIdArr[] = $res['leadId'];
		return $leadIdArr;
        }

        catch(Exception $e){
                throw new jsException($e);
        }
        return $email_address;
    }
        public function getLeadsWithEmails($emailStr)
        {
                try
                {
                        $emailArr = explode(",",$emailStr);
                        foreach($emailArr as $k=>$v)
                                $qArr[]=":EMAIL".$k;
                        $qStr = implode(",",$qArr);
                        $sql = "SELECT email_address FROM sugarcrm.email_addresses where email_address IN (".$qStr.");";
                        $prep = $this->db->prepare($sql);
                        foreach($emailArr as $k=>$v)
                                $prep->bindValue(":EMAIL".$k,$v,PDO::PARAM_STR);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                        $matchEmailArr[] =$res['email_address'];
                        }
                }
                catch(Exception $e)
                {
                    throw new jsException($e);
                }
                return $matchEmailArr;
        }
}
?>
