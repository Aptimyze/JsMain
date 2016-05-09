<?php
class EmailTypePool{
	public static $type_pool;
	private static $instance;
	private $email_pdo;
	private function __construct(){
		self::$type_pool = array();
		$this->email_pdo=new jeevansathi_mailer_EMAIL_TYPE();
	}
	public static function getInstance(){
		if (!self::$instance){ 
			self::$instance = new EmailTypePool();
		}
		return self::$instance;
	}
	public function getEmailTpl($mail_id){
		if(in_array($mail_id,self::$type_pool))
			    return self::$type_pool[$mail_id];
		else{
			        $result=$this->email_pdo->getEmailType($mail_id);
					$email_tpl=$this->setAllParamsInEmailType($result);
					 self::$type_pool[$mail_id]=$email_tpl;
					return $email_tpl;
		}
	}

	public function setAllParamsInEmailType($result){
					$email_tp=new EmailType($result[MAIL_ID]);
					$email_tp->setTplLocation($result[TPL_LOCATION]);
					$email_tp->setMailGroup($result[MAIL_GROUP]);
					$email_tp->setCustomCriteria($result[CUSTOM_CRITERIA]);
					$email_tp->setSenderEmailid($result[SENDER_EMAILID]);
					$email_tp->setDescription($result[DESCRIPTION]);
					$email_tp->setMembershipType($result[MEMBERSHIP_TYPE]);
					$email_tp->setRelation($result[RELATION]);
					$email_tp->setGender($result[GENDER]);
					 $email_tp->setPhotoProfile($result[PHOTO_PROFILE]);
					 $email_tp->setReplyToEnabled($result[REPLY_TO_ENABLED]);
					 $email_tp->setFromName($result[FROM_NAME]);
           $email_tp->setReplyToAddress($result[REPLY_TO_ADDRESS]);
					 $email_tp->setMaxCountTobeSent($result[MAX_COUNT_TO_BE_SENT]);
					 $email_tp->setRequireAutologin($result[REQUIRE_AUTOLOGIN]);
					 $email_tp->setHeaderTpl($result[HEADER_TPL]);
					 $email_tp->setFooterTpl($result[FOOTER_TPL]);
					 $email_tp->setPreHeader($result[PRE_HEADER]);
					 $email_tp->setRequireAutologin($result[REQUIRE_AUTOLOGIN]);
					 return $email_tp;
	}
	public function getEmailTypeId($cond){
		$result=$this->email_pdo->getEmailTypeByGroup($cond);
		if(count($result)){

		foreach($result as $key=>$item){
			self::$type_pool[$key]=$this->setAllParamsInEmailType($item);
			if(count($result)==1)
				return $key;
		}
		return $result;
		}else{ throw new MailerException('No email type found for '.$cond[GROUP]." group");
		}
	}
}
