<?php
/**
 * 
 */
class CriticalInformationMailer
{
	private $profileid;
	private $skipProfiles;
        private $mailerType = array("INTEREST_RECEIVED","ACCEPTANCES_RECEIVED","INTEREST_SENT");

	public function __construct($profileid,$formData)
	{
		$this->profileid = $profileid;
		$this->formData = $formData;
	}

	public function sendMailer()
	{
                $loggedInProfileObj = LoggedInProfile::getInstance();
                $loggedInProfileObj->getDetail($this->profileid,"PROFILEID","*");
                $InouObj = new NameOfUser();
                $name = $InouObj->getNameData($this->profileid);
                if(empty($name) && !is_array($name)){
                        $name =  $loggedInProfileObj->getUSERNAME();
                }else{
                        $name = $name[$this->profileid]["NAME"];
                }
                unset($loggedInProfileObj);unset($InouObj);
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
                }
	}

	public function getSkipProfiles()
	{

		$memcacheServiceObj = new ProfileMemcacheService($this->profileid);
		$memcacheServiceObj->setSKIP_PROFILES();
		$skipConditionArray = SkipArrayCondition::$default;
                $skipProfileObj     = SkipProfile::getInstance($this->profileid);
                $this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
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

}
