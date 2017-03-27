<?php
class billing_VARIABLE_DISCOUNT_MAILER_LOG extends TABLE{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function sendMailerToday($entry_dt)
	{
		try
		{
			$sql="SELECT COUNT(1) AS CNT FROM billing.VARIABLE_DISCOUNT_MAILER_LOG WHERE SEND_DT=:ENTRY_DT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			if($result['CNT']>0)
			{
				return 1;
			} else {
				return 0;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	 public function insertVdMailerSchedule($entry_dt){
                try
                {
                                $sql="INSERT INTO billing.`VARIABLE_DISCOUNT_MAILER_LOG` VALUES ('', :ENTRY_DT)";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
                                $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
