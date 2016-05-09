<?php
/* 
 * Used to format mobile number and message received in reponse to a SMS sent to a customer
 *
 * */
class SmsResponse{
	private $mobile;
	private $msg;
	//Error flag. It will be set whenever an error is found in mobile number or message
	public $err=0;
	function __construct($mobile,$msg){
		if(empty($mobile)){
			$this->err=1;
		}else{
			$this->mobile=$this->formatMobileNumber($mobile);
			$this->msg=strtolower(addslashes(stripslashes($msg)));
			
		}
	}
	private function formatMobileNumber($mobile){
		if(strlen($mobile)!=10)
		{       
		   if(strlen($mobile)==11)
			   $mobile=substr($mobile,1,10);
		   else
		   if(strlen($mobile)==12)
			   $mobile=substr($mobile,2,10);
		   else
			   $this->err=2;
		}
		return $mobile;
	}
	public function getMobile(){
		return $this->mobile;
	}
	/*
	 * Returns sms message received in all lowercase
	 * */
	public function getMessage(){
		return $this->msg;

}
}
