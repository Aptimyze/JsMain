<?php
class incentive_FVD_SMS_SENT_LIST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function smsAlreadySent($profileid)
	{
		try
                {
			$sql = "SELECT PROFILEID from incentive.FVD_SMS_SENT_LIST WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
			if($result['PROFILEID']==$profileid)
	                        $sent=1;
			else
				$sent=0;

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $sent;
	}
	public function smsNowSent($profileid)
	{
		try
                {
                        $sql = "INSERT INTO incentive.FVD_SMS_SENT_LIST (PROFILEID) VALUES (:PROFILEID)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
