<?php
class EmailType{
	private $id;
	private $tpl_location;
	private $mail_group;
	private $custom_criteria;
	private $sender_emailid;
	private $description;
	private $membership_type;
	private $relation;
	private $source;
	private $gender;
	private $photo_profile;
	private $reply_to_enabled;
	private $from_name;
  private $reply_to_address;
	private $max_count_to_be_sent;
	private $require_autologin;
	private $header_tpl;
	private $footer_tpl;
	private $pre_header;
	public function __construct($mail_id){
		$this->id=$mail_id;
	}
	public function setTplLocation($tpl_location){$this->tpl_location=$tpl_location;}
	public function setMailGroup($mail_group){$this->mail_group=$mail_group;}
	public function setCustomCriteria($custom_criteria){$this->custom_criteria=$custom_criteria;}
	public function setSenderEmailid($sender_emailid){$this->sender_emailid=$sender_emailid;}
	public function setDescription($description){$this->description=$description;}
	public function setMembershipType($membership_type){$this->membership_type=$membership_type;}
	public function setRelation($relation){$this->relation=$relation;}
	public function setGender($gender){$this->gender=$gender;}
	public function setPhotoProfile($photo_profile){$this->photo_profile=$photo_profile;}
	public function setReplyToEnabled($reply_to_enabled){$this->reply_to_enabled=$reply_to_enabled;}
	public function setFromName($from_name){$this->from_name=$from_name;}
  public function setReplyToAddress($reply_to_address) {$this->reply_to_address = $reply_to_address;}
	public function setMaxCountTobeSent($max_count_to_be_sent){$this->max_count_to_be_sent=$max_count_to_be_sent;}
	public function setRequireAutologin($require_autologin){ $this->require_autologin=$require_autologin;}
	public function setHeaderTpl($header_tpl){ $this->header_tpl=$header_tpl;}
	public function setFooterTpl($footer_tpl){$this->footer_tpl=$footer_tpl;}
	public function setPreHeader($pre_header){$this->pre_header=$pre_header;}
	public function getTplLocation(){return $this->tpl_location;}
	public function getMailGroup(){return $this->mail_group;}
	public function getCustomCriteria(){return $this->custom_criteria;}
	public function getSenderEmailid(){return $this->sender_emailid;}
	public function getDescription(){return $this->description;}
	public function getMembershipType(){return $this->membership_type;}
	public function getRelation(){return $this->relation;}
	public function getGender(){return $this->gender;}
	public function getPhotoProfile(){return $this->photo_profile;}
	public function getReplyToEnabled(){return $this->reply_to_enabled;}
	public function getFromName(){return $this->from_name;}
  public function getReplyToAddress() {return $this->reply_to_address;}
	public function getMaxCountTobeSent(){return $this->max_count_to_be_sent;}
	public function getHeaderTpl(){return $this->header_tpl;}
	public function getFooterTpl(){return $this->footer_tpl;}
	public function getRequireAutologin(){return $this->require_autologin;}
	public function getPreHeader(){return $this->pre_header;}
	public function getEmailID(){return $this->id;}
}
