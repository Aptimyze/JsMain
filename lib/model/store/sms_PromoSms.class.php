<?php
class sms_PromoSms extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function getCount($mobile)
	{
		$sql = "SELECT Count FROM sms.PromoSms WHERE PHONE=:PHONE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PHONE", $mobile, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row["Count"];
	}

	public function Insert($phone,$count,$source)
	        {
	                try
	                {//print_r($phone);die;
							$sql = "INSERT INTO  sms.PromoSms (PHONE,Count,Source,DATE) VALUES(:PHONE,:COUNT,:SOURCE,now())";
							$res = $this->db->prepare($sql);
				            $res->bindValue(":PHONE", $phone, PDO::PARAM_INT);
				            $res->bindValue(":SOURCE", $source, PDO::PARAM_STR);
				            $res->bindValue(":COUNT", $count, PDO::PARAM_INT);
	                		$res->execute();    
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }
	public function Update($phone,$count)
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "UPDATE sms.PromoSms SET COUNT='$count' WHERE PHONE=:PHONE";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PHONE", $phone, PDO::PARAM_INT);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }
}
?>
