<?php
/**
 * 
 */
class CriticalInformationMailer
{
	private $profileid;
	private $skipProfiles;
        private $mailerType = array("INTEREST_RECEIVED","ACCEPTANCES_RECEIVED","INTEREST_SENT");
        private $formData = array();

	public function __construct($profileid,$formData= array())
	{
		$this->profileid = $profileid;
		$this->formData = $formData;
	}
        public function getName(){
                $loggedInProfileObj = LoggedInProfile::getInstance();
                $loggedInProfileObj->getDetail($this->profileid,"PROFILEID","*");
                $name =  $loggedInProfileObj->getUSERNAME();
                unset($loggedInProfileObj);
                return $name;
        }
        public function getFields(){
                $fieldList = array();
                $fields = array();
                if(isset($this->formData["MSTATUS"]) && $this->formData["MSTATUS"] !=""){
                        $fieldList[] = "Marital Status";
                        $fields["MSTATUS"] = array("field"=>"Marital Status","oldVal"=>FieldMap::getFieldLabel("marital_status",$this->formData["PREV_MSTATUS"]),"newVal"=>FieldMap::getFieldLabel("marital_status",$this->formData["MSTATUS"]));
                }
                if(isset($this->formData["DTOFBIRTH"]) && $this->formData["DTOFBIRTH"] !=""){
                        $fieldList[] = "Date of Birth";
                        $fields["DTOFBIRTH"] = array("field"=>"Date of Birth","oldVal"=>$this->formData["PREV_DTOFBIRTH"],"newVal"=>$this->formData["DTOFBIRTH"]);
                }
                return array("fields"=>$fields,"fieldList"=>$fieldList);
        }
	public function sendMailer()
	{
                if(!$this->profileid){
                        return true;
                }
                $name = $this->getName();
                $fieldData = $this->getFields();
                $fieldList = $fieldData["fieldList"];
                $fields = $fieldData["fields"];
                $fieldLables = preg_replace('~,(?!.*,)~', 'and', implode(" , ",$fieldList));
		$skipArray = $this->getSkipProfiles();
                $email_sender = new EmailSender(MailerGroup::CRITICAL_INFO_EMAIL,1851);
                foreach($this->mailerType as $type){
                        $infoTypeAdapter = new InformationTypeAdapter($type, $this->profileid);
                        $conditions = $this->getConditions($type);
                        $profilelists = $infoTypeAdapter->getProfiles($conditions,$skipArray);
                        if(is_array($profilelists))
                        {
                                foreach($profilelists as $key=>$value)
                                {
                                        $tpl = $email_sender->setProfileId($key);
                                        $smartyObj = $tpl->getSmarty();
                                        $smartyObj->assign("fieldList",$fieldLables);
                                        $smartyObj->assign("fields",$fields);
                                        $smartyObj->assign("namePG",$name);
                                        $email_sender->send();
                                }
                        }
                        unset($infoTypeAdapter);
                        unset($conditions);
                }
	}

	public function getSkipProfiles()
	{
                $this->skipProfiles       = SkipProfile::getInstance($this->profileid)->getSkipProfiles(SkipArrayCondition::$default);
                if(!$this->skipProfiles || empty($this->skipProfiles) || $this->skipProfiles == null){
                        $memcacheServiceObj = new ProfileMemcacheService($this->profileid);
                        $memcacheServiceObj->setSKIP_PROFILES();
                }
		return $this->skipProfiles;
	}

	public function getConditions($type,$limit = 0)
	{
		if ($type == "INTEREST_RECEIVED") {
			$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
			$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
			$back_90_days                                     = date("Y-m-d", $yday);
			$condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
		}
		if ($limit) $condition["LIMIT"] = "$limit";
		$condition["ORDER"] = "TIME";
		return $condition;
	}
        public function sendSuccessFailMailer($status){
                $longURL = "";
                if($status == "Y"){
                        $email_sender = new EmailSender(MailerGroup::CRITICAL_INFO_EMAIL,1852);
                }else{
                        $email_sender = new EmailSender(MailerGroup::CRITICAL_INFO_EMAIL,1853);
                                include_once JsConstants::$docRoot . "/classes/authentication.class.php";
                                $protect   = new protect();
                                $checksum  = md5($this->profileid) . "i" . $this->profileid;
                                $echecksum = $protect->js_encrypt($checksum);
                                $longURL   = sfConfig::get('app_site_url')."/common/uploadDocumentProof?" . "&echecksum=" . $echecksum . "&checksum=" . $checksum;
                }
                $tpl = $email_sender->setProfileId($this->profileid);
                $smartyObj = $tpl->getSmarty();
                $fields["MSTATUS"] = array("field"=>"Marital Status","oldVal"=>FieldMap::getFieldLabel("marital_status",$this->formData["PREV_MSTATUS"]),"newVal"=>FieldMap::getFieldLabel("marital_status","D"));
                $smartyObj->assign("fields",$fields);
                $smartyObj->assign("hereLink",$longURL);
                $email_sender->send();
        }

}
