<?php
class EmailSender{
  private $profileid;
  private $mail_group;
  private $custom_criteria;
  private $profile;
  private $fields_str;
  private $mail_id;
  private $no_mail_id;
  private $email_tpl;
  private $_profileArray;
  private $_emailTplArray;
  private $emailAttachment;
  private $emailAttachmentName;
  private $emailAttachmentType;

  /**
   * @uses LRUObjectCache for profile object pooling 
   */
  public function __construct(
      $mail_group,
      $mail_id='',
      $custom_criteria=1)
  {
    $this->mail_group=$mail_group;
    $this->custom_criteria=$custom_criteria;
    $this->mail_id=$mail_id;
    $this->no_mail_id=true;
    $this->_pool = new Cache(LRUObjectCache::getInstance());
    $this->_profileArray = null;
    $this->_emailTplArray = null;//print_r($this);die;
  }

  public function setProfile(Profile $profile){
    $this->profile=$profile;
    $this->_setTemplate();//print_r($this->email_tpl);die;
    return $this->email_tpl;
  }

	public function getProfile(){
    return $this->profile;
  }
  public function setCustomCriteria(CustomCriteria $customCritera1){
    $this->custom_criteria=$customCritera1;
  }
  public function setAttachment($attachment){
    $this->emailAttachment = $attachment;
  }
  public function setAttachmentName($attachmentName){
    $this->emailAttachmentName = $attachmentName;
  }
  public function setAttachmentType($attachmentType){
    $this->emailAttachmentType = $attachmentType;
  }

  /**
   * Mail bulk send 
   *
   * <p>
   * This function sends bulk mail for an array of profileids provided.
   * Caveat, smarty assign currently is not supported for dynamic variable assignment.
   * </p>
   * 
   * @uses ArrayChunkify To break large array into chunks of smaller array. 
   * {@link ArrayChunkify->chunkify()} also removes the chunked values from main profileIds array.
   * @uses _bulkSetProfiles To set profiles in bulk to whom email will be sent.
   * @uses _bulkSetTemplates To set templates in bulk for profileids in question
   * @uses SendMail::send_email To send email to the recipient.
   * 
   * @access public
   * @param $profileids array
   * @param $options string
   * @return boolean
   */
  public function bulkSend($profileids = "", $options = "") {
    if (is_array($profileids)) {

      // Get chunks of profileIds in size of LRUObjectCache chunk size
      $arrayChunkifyObject = new ArrayChunkify($profileids, CONSTANTS::_COUNT, true);

      while($returnArray = $arrayChunkifyObject->chunkify()) {
        /* Since chunks are allocated in size of LRUObjectCache chunk size, 
           flushing pool will remove any scope of thrashing.*/
        $this->_pool->flushCache();
        $this->_bulkSetProfiles($returnArray); // set profiles in bulk
        unset($returnArray);
        unset($profileids);
        $this->_bulkSetTemplates(); // set templates in bulk
        $profileCount = count($this->_profileArray);
        $emailTplCount = count($this->_emailTplArray);

        if (is_array($this->_profileArray)) {
          foreach ($this->_profileArray as $profileid => $profileObj) {
            $to = $profileObj->getEMAIL();
            if (is_array($options)) {
              foreach ($options as $key => $partials) {
                if (is_array($partials)) {
                  $partial = new PartialList;
                  switch (count($partials)) {
                    case 2:
                      $partial->addPartial($partials[0], $partials[1], "", false);
                      break;

                    case 3: 
                      $partial->addPartial($partials[0], $partials[1], $partials[2][$profileid], false);
                      break;

                    case 4: 
                      $partial->addPartial($partials[0], $partials[1], $partials[2][$profileid], $partials[3]);
                      break;

                    default:
                      throw new Exception("Invalid number of arguments specified in partial list.");
                  }
                }
                else {
                  throw new Exception("Partials need to be specified in array format.");
                }
                if ($partial instanceof PartialList) {
                  $this->_emailTplArray[$profileid]->setPartials($partial);
                }
                unset($partial);
              }
            }
            if (is_array($this->_emailTplArray)) {
              $message = $this->_emailTplArray[$profileid]->getMessage();
              $subject = $this->_emailTplArray[$profileid]->getProcessedSubject();
              $from = $this->_emailTplArray[$profileid]->getSenderEMailId();
              $replyToAddress = ($this->_emailTplArray[$profileid]->getReplyToEnabled() === "Y") ? 
                $this->_emailTplArray[$profileid]->getReplyToAddress() : 
                $this->_emailTplArray[$profileid]->getSenderEMailId();
              if(empty($this->emailAttachment)){
              	$this->emailAttachment= '';
              }
              if(empty($this->emailAttachmentName)){
              	$this->emailAttachmentName= '';
              }
              if(empty($this->emailAttachmentType)){
              	$this->emailAttachmentType= '';
              }
              if (SendMail::send_email($to, $message, $subject, $from, '', '', $this->emailAttachment , $this->emailAttachmentType, $this->emailAttachmentName, '', "1", $replyToAddress)) {
                //Sending mail succeeded
              }
              else {
                throw new Exception();
              }
            }
            else {
              throw new Exception("If there are multiple profiles, then same number of emailtpl objs should exist.");
            }
          }
        }
      }
    }
    else {
      throw new Exception(__FUNCTION__ . ":: check argument list");
    }
    return true;
  } // end of bulkSend

  /**
   * Set profiles in bulk
   *
   * <p>
   * This function sets profile in bulk
   * </p>
   *
   * @param $profileids array
   * @access private
   * @return void
   */
  private function _bulkSetProfiles($profileids) {
    $this->_profileArray = null;
    foreach ($profileids as $key => $value) {
      $this->_profileArray[$value] = $this->_pool->get($value);
    }
  } // end of _bulkSetProfiles

  /**
   * Get template for a profile id or get all templates (used with bulkSend)
   *
   * <p>
   * This function is used in conjunction to bulkSend and returns the email template of a profile id if specified, 
   * otherwise the full email template array as per the chunkified profile ids. 
   * </p>
   *
   * @param $profileid integer
   * @access public
   * @return Object
   */
  public function bulkGetTemplate($profileid = "") {
    if (true === is_numeric($profileid)) {
      return $this->_emailTplArray[$profileid];
    }
    else {
      return $this->_emailTplArray;
    }
  } // end of bulkGetTemplate

  /**
   * Set templates in bulk (used with bulkSend)
   *
   * <p>
   * This function sets templates in bulk. It is used in conjunction to bulkSend function 
   * </p>
   *
   * @access public
   * @uses jeevansathi_mailer_EMAIL_TYPE for getting email template id 
   */
  public function _bulkSetTemplates() {
    if (is_array($this->_profileArray)) {

      // Earlier objects were created of this class everytime a new profile comes. now only once.
      $db_obj = new jeevansathi_mailer_EMAIL_TYPE();

      //Memory leak will happen if we don't do this
      unset($this->_emailTplArray);

      $this->_emailTplArray = null;
      foreach ($this->_profileArray as $profileid => $profileObj) {
        if ($profileObj instanceof Profile) {
          if (!$this->mail_id) {
            $conditionalArray['MAIL_GROUP'] = $this->mail_group;
            $conditionalArray['GENDER'] = $profileObj->getGENDER();

            if ($profileObj->getHAVEPHOTO()) {
              $conditionalArray['PHOTO_PROFILE'] = $profileObj->getHAVEPHOTO();
            }
            else {
              $conditionalArray['PHOTO_PROFILE'] = 'N';
            }

            $conditionalArray['CUSTOM_CRITERIA'] = $this->custom_criteria;
            $conditionalArray['FTO_FLAG'] = JsCommon::getProfileState($profileObj);
            $mailTypeID = $db_obj->getEMAIL_ID($conditionalArray);

          }
          else {
            $mailTypeID = $this->mail_id;
          }
          $this->_emailTplArray[$profileid] = new EmailTemplate($mailTypeID);
          $this->_emailTplArray[$profileid]->setSenderProfile($profileObj);
        }
      }
    }
  } // end of _bulkSetTemplates

  public function send($to="", $partialList='',$ccList=''){

    $replyToEnabled = null;
    $replyToAddress = null;
    $message = null;
    $subject = null;
    $from = null;
	global $do_not_send;
	$do_no_send=false;
    if(!$this->no_mail_id){
      if(!$to) {
        $to = $this->profile->getEMAIL();
      }

      if($partialList instanceOf PartialList) {
        $this->email_tpl->setPartials($partialList);
      }

      if($partialList instanceOf PartialList) {
        $this->email_tpl->setPartials($partialList);
      }
      
     // print_r($this->email_tpl); die('lets try');
//Do not send mail to deleted profiles except success story mailers whose group is success_story_photo or success_story_mailer.
	  if($this->profile->getACTIVATED()=='D' && ($this->mail_group!=MailerGroup::SUCCESS_STORY_PHOTO && $this->mail_group!=MailerGroup::SUCCESS_STORY_DELETE))
		  return false;

      $replyToEnabled = ($this->email_tpl->getReplyToEnabled() === "Y") ? 1 : 0;
      if ($replyToEnabled === 1) {
        $replyToAddress = $this->email_tpl->getReplyToAddress();
      }
      else {
        $replyToAddress = $this->email_tpl->getSenderEMailId();
      }
      $from_name = $this->email_tpl->getFromName();

      $from = $this->email_tpl->getSenderEMailId();

      $message = $this->email_tpl->getMessage();
      	$subject = $this->email_tpl->getProcessedSubject();
      
	$canSendObj= canSendFactory::initiateClass($channel=CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$to,"EMAIL_TYPE"=>$this->mail_group),$this->profile->getPROFILEID());
	$canSend = $canSendObj->canSendIt();

	$this->deliveryStatus = $canSendObj->getDeliveryStatus();
	  if(empty($this->emailAttachment)){
      	$this->emailAttachment= '';
      }
      if(empty($this->emailAttachmentName)){
      	$this->emailAttachmentName= '';
      }
      if(empty($this->emailAttachmentType)){
      	$this->emailAttachmentType= '';
      }
      if(true  && SendMail::send_email('palashc2011@gmail.com', $message, $subject, $from,$ccList, '', $this->emailAttachment, $this->emailAttachmentType, $this->emailAttachmentName, '', "1", $replyToAddress,$from_name)) {
        return true;
      }
      else {
        return false;
      }
    }
  }
 
  public function getEmailDeliveryStatus(){
        return $this->deliveryStatus;
  }

  public function setProfileId($profileid){
    $this->profileid = $profileid;
    $this->profile = $this->_pool->get($this->profileid,true);
    $this->_setTemplate();
    return $this->email_tpl;
  }

  public function getTemplate(){
    if($this->email_tpl)
      return $this->email_tpl;
    else{
      throw new jsException('',"Please call setTemplate first");
    }
  }


  private function _setTemplate() {
    if(!$this->profile instanceof Profile){
      if(!$this->profileid)
        throw new ProfileIdNotProvidedException('Please set profileid before calling this function');
      else{
        $this->profile = $this->_pool->get($this->profileid);
      }
    }
    if(!$this->mail_id){
      $conditionalArray['MAIL_GROUP']=$this->mail_group;
      $conditionalArray['GENDER']=$this->profile->getGENDER();

      if($this->profile->getHAVEPHOTO())
        $conditionalArray['PHOTO_PROFILE']=$this->profile->getHAVEPHOTO();
      else
        $conditionalArray['PHOTO_PROFILE']='N';

      $conditionalArray['CUSTOM_CRITERIA']=$this->custom_criteria;	
      $conditionalArray['FTO_FLAG']=JsCommon::getProfileState($this->profile);

      $db_obj= new jeevansathi_mailer_EMAIL_TYPE();
      $mailTypeID= $db_obj->getEMAIL_ID($conditionalArray);

    }
    else
      $mailTypeID=$this->mail_id;
    //$this->mail_id=$mailTypeID;
    $this->email_tpl= new EmailTemplate($mailTypeID);
    $this->email_tpl->setSenderProfile($this->profile);
    if($mailTypeID){
      $this->no_mail_id=false;
    }
    else
      $this->no_mail_id=true;
  }
}

