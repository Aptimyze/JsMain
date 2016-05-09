<?php
$path=realpath(dirname(__FILE__)."/../../..");
include_once("numberOfmatch.class.php");
include_once("$path/profile/connect.inc");
  class JsSuccessAutoRegEmail extends NumberofMatch{
	 public $lead_id='';
	 private $username='';
	 private $password='';
	 private $email='';
	 private $mobile='';
	 private $landline='';
	 function __construct($leadid,$username_var,$password_var,$email_var,$mobile_var,$landline_var){
	//	 parent::__construct();
		 $this->lead_id=$leadid;
		 $this->username=$username_var;
		 $this->password=$password_var;
		 $this->email=$email_var;
		 $this->mobile=$mobile_var;
		 $this->landline=$landline_var;
	 }
	 function createMessage($propertyArr){
		 global $smarty,$SITE_URL;
        // $smarty->template_dir="../smarty/templates/jeevansathi";
		 if($propertyArr['lname']){
			 $name=$propertyArr['fname']." ".$propertyArr['lname'];
			 //If it is a proper name then only assign name to variable
			 if(preg_match("/^[a-zA-Z ]+$/",$name))
			  $smarty->assign("name",$name);
		 }
		 
		 $smarty->assign("username",$this->username);
		 $smarty->assign("password",$this->password); 
		 $smarty->assign("email",$this->email);
		 $smarty->assign("mobile",$this->mobile);
		 $smarty->assign("landline",$this->landline);
		 $short_url=substr($SITE_URL,7);
		 $smarty->assign("short_url",$short_url);
		 if($propertyArr['count']>=100)
		  	$smarty->assign("count",$propertyArr['count']);
		 $message=$smarty->fetch("sugarcrm_registration/autoregmailer.htm");
		 return $message;
	 }
	 function sendMessage(){
		 $subject="Your account has been successfully created on Jeevansathi.com";
		 $db=connect_db();
		$lead_id=$this->lead_id;
		$lead_query_string="select l.first_name as fname, l.last_name as lname, lc.age_c, lc.gender_c, lc.religion_c,lc.caste_c,lc.enquirer_email_id_c,lc.date_birth_c from sugarcrm.leads as l, sugarcrm.leads_cstm as lc where l.id = '$lead_id' AND lc.id_c=l.id and lc.do_not_email_c='0'";
//		echo "<br>$lead_query_string";
		$lead_res=mysql_query_decide($lead_query_string);
		if($lead_res){
			$lead_fields=mysql_fetch_assoc($lead_res);
			$lead_fields['lead_id']=$lead_id;
			$lead_fields['count']=$this->calculateNoOfMatches($lead_fields);
			$messageToSend=$this->createMessage($lead_fields);
		    //echo $messageToSend;
			send_email($this->email,$messageToSend, $subject);
		}
	 }
 }
?>
