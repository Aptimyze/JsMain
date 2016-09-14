<?php
class EmailTemplate implements MessageTemplate{
  private $subject;
  private $id;
  private $htmlCode;
  private $embeddedProfiles;
  private $email_type;
  private $email_type_pool;
  private $parser;
  private $profile;
  private $suggested_profiles;
  private $jeevansathi_contact_address;
  private $dpp_matches_template;
  private $smarty;
  private $partial_list;
  public function __construct($mail_id){
    $this->id=$mail_id;
    $this->email_type_pool=EmailTypePool::getInstance();
    $this->email_type=$this->email_type_pool->getEmailTpl($mail_id);
    $this->parser=new ParseMail();
    $this->smarty=new sfSmarty();
    $this->smarty->template_dir=sfConfig::get('sf_root_dir')."/web/smarty/templates/mailer/";
    $this->smarty->compile_dir=sfConfig::get('sf_root_dir')."/web/smarty/templates_c/";
    //Modified by Tanu
    $this->smarty->compile_check=true;
    //	global $smarty=$this->smarty;
  }
  //Returns the actual mail content after handling all variables
  public function getMessage(){
    $this->smarty->assign('profileid',$this->profile->getPROFILEID());
    $this->smarty->assign('GENDER',$this->profile->getGENDER());
	if($this->profile->getPHONE_MOB() || $this->profile->getPHONE_RES())
		$this->smarty->assign('HAVE_CONTACT_NO',1);
    $agentArr=CommonFunction::getJsCenterDetails($this->profile->getCITY_RES());
    if(is_array($agentArr))
      $this->smarty->assign('AGENT','Y');
    else
      $this->smarty->assign('AGENT','N');
    $this->smarty->assign('SITE_URL', sfConfig::get('app_site_url'));
    $this->smarty->assign('IMG_URL', sfConfig::get('app_img_url'));
    if(is_array($this->partial_list))
    foreach ($this->partial_list as $partialKey => $partialDetails) {
      if($partialDetails instanceof PartialList){
        while($partial = $partialDetails->fetchPartial()){
          if(is_array($partial->inputs)){
            if($partial->in_loop)
              $this->smarty->assign($partial->tpl."_inputs",$partial->inputs);
            else{
              $i=1;
              foreach($partial->inputs as $input){
                $this->smarty->assign($partial->tpl."_input$i",$input);
                $i++;
              }
            }
          }
          else {
            $this->smarty->assign($partial->tpl."_inputs",$partial->inputs);
          }
          $this->smarty->assign($partial->name,$this->smarty->fetch($partial->tpl.".tpl"));
        }
      }
    }
    if($this->email_type->getPreHeader())
    {
      $preHeader=$this->email_type->getPreHeader();
      if(strpos($this->email_type->getPreHeader(),'~')){
        $regex='/~\$(.*)`/';
        preg_match($regex,$this->email_type->getPreHeader(),$matches);
        $preHeader=str_replace($matches[0],$this->smarty->get_template_vars($matches[1]),$this->email_type->getPreHeader());

      }
      $this->smarty->assign('PREHEADER',$preHeader);

    }
    if($this->email_type->getHeaderTpl())
      $this->smarty->assign('HEADER',$this->smarty->fetch($this->email_type->getHeaderTpl()));
    if($this->email_type->getFooterTpl())
      $this->smarty->assign('FOOTER',$this->smarty->fetch($this->email_type->getFooterTpl()));
    $this->htmlCode=$this->getHtmlCode();
    $this->processHtml();
    return ($this->htmlCode);
  }
  public function getSenderEMailId(){
    $senderEmail = $this->email_type->getSenderEmailId();
    //search for dynamic values in from email id if any
    if(strpos($senderEmail,'~') !== false){
        $regex='/~\$(.*)`/';
        preg_match($regex,$senderEmail,$matches);
        $senderEmail=str_replace($matches[0],$this->smarty->get_template_vars($matches[1]),$senderEmail);

    }
    return $senderEmail;
  }

  public function getReplyToEnabled() {
    return $this->email_type->getReplyToEnabled();
  }

  public function getReplyToAddress() {
    return $this->email_type->getReplyToAddress();
  }

  public function getFromName() {
    $fromName = $this->email_type->getFromName();
    if(strpos($fromName,'~') !== false){
        $regex='/~\$(.*)`/';
        preg_match($regex,$fromName,$matches);
        $fromName=str_replace($matches[0],$this->smarty->get_template_vars($matches[1]),$fromName);

    }
    return $fromName;
  }

  public function getProcessedSubject(){
  	$subject=$this->getSubject();
  	if($subject)
  		$subjectCode=$subject;
  	else{
	    $subjectHandler= new SubjectHandler($this);
	    $subjectCode=$subjectHandler->getSubjectCode();
		}
    $this->parser->load($subjectCode,true);
    return $this->parser->replaceTags();
  }
  public function setEmbeddedProfiles($profileIdList){
    $this->embeddedProfiles=$profileIdList;
  }
  /* This functional will make available all the partials needed for email tpl
   * It takes input as associative array of partial_name and array of profileids as name value pair
   * In tpl, input profile ids will be avaiable in <partial_name>_inputs array over which loop can be run
   * A tpl with <partial_name>.tpl name should exist in mailer template directory
   *
   * */
  public function setPartials($partialList){
    static $i = 0;
    $this->partial_list[$i++]=$partialList;
  }
  public function setSenderProfile($profile){
    $this->profile=$profile;
  }
  public function getSmarty(){
    return $this->smarty;
  }
  public function getEmailType(){
    return $this->email_type;
  }

public function setSubject($subject){
	$this->subject=$subject;
  }

public function getSubject(){

    return $this->subject;
  }

  public function getSenderProfile(){
    return $this->profile;
  }
  private function getHtmlCode(){
    if($this->id){
      $tpl_location=$this->email_type->getTplLocation();
      $html_code=$this->smarty->fetch($tpl_location);
      return $html_code;
    }
	else
		jsException::log("Kindly provide correct mail id or define new id in EMAIL_TYPE table:". $_SERVER[PHP_SELF]);
    //  throw new TemplateNotExistException("Kindly provide correct mail id or define new id in EMAIL_TYPE table");
  }
  private function processHtml(){
    $this->parser->load($this->htmlCode);
    $this->htmlCode=$this->parser->replaceTags($this->email_type->getMailGroup());
  }
}
