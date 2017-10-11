<?php
/************************
 Author @Esha************/
class SmsDetails
{
	public $profileid;
	private $smsKey;
	private $mobile;
        public function findProfile($mobile,$sms_key)
        {
		$this->smsKey= $sms_key;
		$this->mobile=substr($mobile,-10);
                $sql="SELECT PROFILEID FROM newjs.SMS_DETAIL WHERE PHONE_MOB IN('0".$this->mobile."','".$this->mobile."','+91".$this->mobile."','91".$this->mobile."') AND SMS_TYPE='I' AND SMS_KEY= '".$sms_key."'";
                $res=mysql_query_decide($sql) or $this->errormail($sql,mysql_errno().":".mysql_error());
                if($row=mysql_fetch_array($res))
                {
                        $this->profileid=$row["PROFILEID"];
                        return 1;
                }
                else
                        return 0;
        }

	public function insertSmsConfrm()
	{
		$date = date("Y-m-d");
		$sql="INSERT INTO newjs.SMSCONFIRM(PROFILEID,SMS_KEY,ENTRY_DT) VALUES ('$this->profileid','$this->smsKey','$date')";
	        mysql_query_decide($sql) or $this->errormail($sql,mysql_errno().":".mysql_error());
        }


	public function errormail($sql='',$error='')
	{
		$cc='esha.jain@jeevansathi.com';
		$to='nitesh.s@jeevansathi.com';
		$subject="SMS confirm error mail";
		if($sql || $error)
			$msg= 'error occured:<br/> '.$error.'<br/> while executing: '.$sql.'<br/><br/> Warm Regards';
		else
			$msg='No profileid found in SMS_DETAIL for the number '.$this->mobile.' with the key '.$this->smsKey.'<br/><br/>Warm Regards';
		send_email($to,$msg,$subject,"",$cc);
	}
}
