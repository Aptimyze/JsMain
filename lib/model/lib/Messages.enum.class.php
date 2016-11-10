<?php
/**
 * Messages enum class
 * Creates dynamic text out of constant declared in messages class
 *
 * Below is the demonstration on how to use this class
 * <code>
 * //if want to fetch preset Messages
 * Messages::getMessage(MESSAGES::EOI_PRESET_PAID_PARENT,array[TYPE_OF_POST=>"HE",USERNAME=>"VIKKUJAIN")
 * </code>
 * 
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
 */
class Messages
{
  const NO_TYPE="Message donot have the type variable as const ";
  const NO_MESSAGE="Message doesnt exist in Messages class ";
  const NO_PROFILE_OBJ = "Object is not profile Object";
  const ENGINE_ERROR="ContactHandler Engine type not correct";
  const ACTION_ERROR="ContactHandler Action flag not correct";
  const TYPE_ERROR="ContactHandler Contact type is not correct";
  const ELEMENT_ERROR="ContactHandler Element is not present";
  //const PROFILE_ERROR="ContactHandler is not profile obj";

  public static  $userChecksum;
  public static  $viewerChecksum;
  public static  $viewedChecksum;
  //Preset messages start here
  const EOI_PRESET_PAID_PARENT="We liked your profile on Jeevansathi. Please 'accept' our interest if you want us to contact you further. My {{TYPE_OF_POST}} id is {{USERNAME}}";
  const EOI_PRESET_PAID_SELF="I liked your profile on Jeevansathi. Please 'accept' my interest if you want me to contact you further. My id is {{USERNAME}}.";
  const EOI_PRESET_FREE="Jeevansathi member with profile id {{USERNAME}} likes your profile. Please 'Accept' to show that you like this profile.";
  const EOI_SYSTEM_PRESET_FREE="This is a system generated reminder. This member had expressed interest to you earlier but had no photograph. Please consider the profile again as photo(s) have been uploaded by this member.";
  const ACCEPT_PRESET_PAID_PARENT="We like your profile and accept your expression of interest. Do call/email us to let us know how you would like to take things forward.";
  const ACCEPT_PRESET_PAID_SELF="I like your profile and accept your expression of interest. Do call/email me to let me know how you would like to take things forward.";
  const ACCEPT_PRESET_FREE="Jeevansathi user '{{USERNAME}}' has accepted your expression of interest.";
  const DECLINE_PRESET_PAID_PARENT="We are sorry, we do not think our {{TYPE_OF_POST}} the right match for you. Wish you luck in your search for a Jeevansathi.";
  const DECLINE_PRESET_FREE_PAID="I am sorry, I do not think I am the right match for you. Wish you luck in your search for a Jeevansathi.";
  //Preset messages ends here
  const PHONE_EMAIL_ACCEPT="See Phone/Email of this member after you accept ";
  const PHONE_EMAIL_ACCEPT_MULTI="See Phone/Email of these members after you accept them.";
  const EOI_MESSAGE_MULTI = "These members have Expressed Interest.";
  const EOI_MESSAGE = " has Expressed Interest in you.";
  const POSTACCEPT_MESSAGE = "You have accepted interest from ";
  const POSTACCEPT_MESSAGE_MULTI = "You have accepted their interest";
  const POSTDECLINE_MESSAGE = "You have declined interest from ";
  const POSTDELCINE_MESSAGE_MULTI = "You have declined their interest";
  const HIM="him";
  const HER="her";

  const CONTACT_ERROR = "Object is not contact obj";
  const AP_MESSAGE = "This is an Interest sent by Jeevansathi on behalf of {{USERNAME}} as part of the 'Response Booster' service. Your profile strictly matches {{USERNAME}} Desired Partner preferences. You may accept the interest by clicking on the 'Accept' button.";
  const IGNORED_MESSAGE = "You cannot perform this action as {{USERNAME}} has blocked you.";
  const I_IGNORE_MESSAGE = "You cannot perform this action as you blocked {{USERNAME}}.";
  const SAMEGENDER = "You cannot initiate contact with profile(s) of the same gender.";
  const FILTERED = "You cannot see the contact details of this profile as the profile has filtered you.";
  const DAY_LIMIT = "Contact cannot be initiated. You have reached your daily contact limit.";
  const MON_LIMIT ="Contact cannot be initiated. You have reached your monthly contact limit.";
  const WEEK_LIMIT ="Contact cannot be initiated. You have reached your weekly contact limit.";
  const FREE_OVERALL_LIMIT = "Your contact limit as a free member has been reached. To make more contacts, <BR><BR>{{BUTTON}}";
  const PAID_OVERALL_LIMIT = "Contact cannot be initiated. Your contact limit as a paid member has been reached.";
  const UNVERIFY_PHONE_LIMIT = "Your phone number is not verified. {{ALINK}} to Verify your phone number. Or call 18004196299 for help.";

  const CONT_VIEW_LIMIT = "You have 0 contacts left to view.";
  const CONT_VIEWED_LIMIT = "The profile has been viewed more than the allotted number of times for the day. Please try again tomorrow.";
  const EOI_INCOMPLETE = "You need to complete your profile before sending the EOI";
  const EOI_UNDERSCREENING = "You can send the EOI only after your profile is screened.";
  const MULTI_EOI_SPAMMER = " You cannot express interest in multiple users at present. Please try later.";
  const CONTACT_UNDERSCREENING="Your interest will be delivered once your profile goes live.";
  const CONTACT_INCOMPLETE="Your interest will be delivered once you complete your profile.<BR><BR>{{BUTTON}}";
  const DETAILS_INCOMPLETE = "You need to complete your profile before viewing the user's contact details.<BR><BR>{{BUTTON}}";
  const ANY_INCOMPLETE_EXP="To further communicate with this person,<BR><BR>{{BUTTON}}";
  const MOBILE_INCOMPLETE="Switch to desktop version to complete your profile";
  const PHONE_HIDDEN = "Phone number hidden";
  const PHONE_VISIBLE_ON_ACCEPT="Phone number visible on accept";
  const DETAILS_UNDERSCREENING_FTO="To view contact details of the user – {{MEMBERSHIPLINK}} or take {{FREETRIALOFFER}}<BR><BR><BR><B>Note</B>: Free Trial can be availed once profile is screened. Profile screening takes place on Mon-Sat, from 7AM - 7PM.";
  const DETAILS_UNDERSCREENING="You can view the contact details of the user only after your profile is screened.";
  const DECLINED = "You cannot see the contact details of this profile as the profile has declined any further contacts with you.";
  const REMINDER_LIMIT_PAID="You can not send more than two reminders. However, you can talk to this member directly by viewing contact details.";
  const REMINDER_LIMIT_FREE="You can not send more than two reminders. <a href=/profile/mem_comparison.php>Buy paid membership </a> to talk to this member directly.";
  const SELF_HIDDEN = "Your profile is currently hidden. Please activate your profile before contacting Jeevansathi members.";
  const OTHER_HIDDEN = "{{USERNAME}}'s profile is currently hidden.";
  const SELF_DELETED = "Your profile is currently deleted. Please activate your profile before contacting Jeevansathi members.";
  const OTHER_DELETED = "{{USERNAME}}'s profile has been deleted.";
  const OTHER_INCOMPLETE="{{USERNAME}}'s profile is currently under screening. Please try again after 24 hours.";
  const OTHER_SCREENING = "This Profile is currently being Screened. Kindly view this profile after 24 hours.";
  //privilege error message starts here
  const ACTION_NOT_ALLOWED = "You cannot perform this action.";
  const HIDDEN_ERROR = "You cannot perform this action as {{POGID}}'s profile is hidden"; 
  const DROP_DOWN = "please <a href='/profile/mem_comparison.php'>pay</a>, to avail this feature";
  const MESSAGE = "Please <a href='/profile/mem_comparison.php'>pay</a>, to avail this feature";
  const MESSAGE_BOX_VISIBILE = "please <a href='/profile/mem_comparison.php'>pay</a>, to avail this feature";
  const VISIBILITY = "please <a href='/profile/mem_comparison.php'>pay</a>";
  const ALLOWED = "Your quota to view contacts directly has been reached, please <a href='/profile/mem_comparison.php'>pay</a> to avail more.";
  const APPLICABLE = "Your quota to view contacts directly has been reached, please <a href='/profile/mem_comparison.php'>pay</a> to avail more.";
  const PAID_UNVERIFY = "You cannot see contact details as your phone is not verified.<BR><BR>{{BUTTON}}";

	const JSExNoPhoMes="We have liked your profile. If you are interested in taking things forward then please indicate by \"Accepting\". 
	Also would request you to please forward some of your photos to our mail id {{EMAIL}} .";

  //privilege error message ends here

  const PHONE_UPLOAD_MES="Send your photos to <BR><a href='mailto:photos@jeevansathi.com?subject={{USERNAME}} : My Photos' {{CLASS}}>photos@jeevansathi.com</a>";	
  const FTO_URL="/fto/offer?profilechecksum={{PROFILECHECKSUM}}&fromReferer=1&FROMPOST={{FROMPOST}}&{{NAVIGATOR}}";
  const BUTTON='<input {{CLASS}} type="button"  {{VALUE}} {{ONCLICK}} {{HREF}} {{ID}}  {{NAME}}>';
  const MOB_BUTTON='<input {{CLASS}} type="Submit"  {{VALUE}}  {{ID}}  {{NAME}}>';
  const LINK='<a {{CLASS}} {{ONCLICK}} {{HREF}} {{STYLE}} {{ID}}> {{LINK}}</a> ';
  const MOB_BUTTON_CLASS="pull-left btn pre-next-btn auto-width";	
  //Yes or No
  const YES='Y';
  const NO='N';


  // Profile Communication Related Messsages added by Reshu, all the constants will be prefixed by PC_
  
  const PC_EOI ="{{GENDER}} expressed interest {{TIME}}.";
  const PC_ACCEPTANCES = "{{GENDER}} has accepted your interest {{TIME}}.";
  const PC_MESSAGE = "{{GENDER}} has sent you message {{TIME}}.";
  const PC_PHOTO_REQ = "{{GENDER}} requested for your photo {{TIME}}.";
  const PC_HOROSCOPE_REQ = "{{GENDER}} requested for your horoscope {{TIME}}.";
  const PC_VISITORS = "{{GENDER}} visited your profile {{TIME}}.";

  // New Messages added for Filtered Paid Members . 

  const PAID_FILTERED_INTEREST_NOT_SENT = "You cannot directly see contact details as your profile doesn't match {{UNAME}}'s filter criteria. However, you can send an interest, and when your interest is accepted, their contact details will be made visible to you.";  
  const PAID_FILTERED_INTEREST_SENT = "You cannot directly see contact details as your profile doesn't match {{UNAME}}'s filter criteria. Their contact details will be visible when your interest is accepted.";
  
  public static $contactEngineCalled;
  /**
   * set user checksum, used when button require checksum of user
   * @param String $userChecksum
   * <format>
   * $userchecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */
  public static function setUserChecksum($userChecksum)
  {
    Messages::$userChecksum=$userChecksum;
  }
  /**
   * return user checksum that was set by setUserChecksum()
   * @return String Messages::$userChecksum
   * <format>
   * $userchecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */
  public static function getUserChecksum()
  {
    return Messages::$userChecksum;
  }
  /**
   * set viewer checksum, used when button require checksum of user
   * @param String $viewerChecksum
   * <format>
   * $viewerChecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */	
  public static function setViewerChecksum($userChecksum)
  {
    Messages::$viewerChecksum=$userChecksum;
  }
  /**
   * return viewer  checksum that was set by setViewerChecksum()
   * @return String Messages::$viewerChecksum
   * <format>
   * $viewerChecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */
  public static function getViewerChecksum()
  {
    return Messages::$viewerChecksum;
  }
  /**
   * set viewed checksum, used when button require checksum of user
   * @param String $viewedChecksum
   * <format>
   * $viewedChecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */
  public static function setViewedChecksum($userChecksum)
  {
    Messages::$viewedChecksum=$userChecksum;
  }
  /**
   * return viewer  checksum that was set by setViewedChecksum()
   * @return String Messages::$viewedChecksum
   * <format>
   * $viewedChecksum=22c23ca68ecf851ee88c5c40542d40c3i365232
   * </format>
   */
  public static function getViewedChecksum()
  {
    return Messages::$viewedChecksum;
  }
  /**
   * Set by contactEvent class
   */
  public static function setCeCalled()
  {
    Messages::$contactEngineCalled=1;
  }
  /**
   *  jsException to check if exception thrown is by contact engine lib.
   * @return int Messages::$contactEngineCalled
   * <format>
   * $contactEngineCalled=0/1
   * </format>
   */
  public static function getCeCalled()
  {
    if(Messages::$contactEngineCalled)
      return Messages::$contactEngineCalled;
    else
      return 0;
  }

  /**
   * Form express buttons required to show on templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getExpressButton($replaceArr=array(), $layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $defaultArray=array("CLASS"=>"fto-btn-green sprite-new cp","ONCLICK"=>"onExpressInterest('$layerid')","HREF"=>"","ID"=>"EXPRESS_BUTTON","VALUE"=>"Express Interest");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON,$defaultArray);
    else	
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }
  public static function getSendEmailButton($replaceArr=array(),$layerid='')
  {
	  $layerid=Messages::$userChecksum;
    $defaultArray=array("CLASS"=>"actived-btn","ONCLICK"=>"onSendEmail('$layerid')","HREF"=>"","ID"=>"SEND_EMAIL","VALUE"=>"Send Message");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON,$defaultArray);
    else	
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }
  
  /**
   * Form express intereset link required on template
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getExpressLink($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $defaultArray=array("CLASS"=>"b underline fs14 cp","ONCLICK"=>"onExpressInterest('$layerid')","Link"=>"Express Interest");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
    {
      $defaultArray[VALUE]=$defaultArray[Link];
      unset($defaultArray[ONCLICK]);
      //unset($defaultArray[CLASS]);
      $defaultArray["CLASS"]=Messages::MOB_BUTTON_CLASS;
      return Messages::getMessage(Messages::MOB_BUTTON,$defaultArray);
    }
    else
      return Messages::getMessage(Messages::LINK,$defaultArray);

  }
  /**
   * Form upload photo button required by html
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getUploadPhotoButton($replaceArr = array()) {

    $defaultArray = array("CLASS" => "fto-btn-green sprite-new cp", "ONCLICK" => "RedirectFromCE('/social/addPhotos')","ID" => "UPLOAD_PHOTO_BUTTON", "VALUE" => "Upload Photo");
    $defaultArray = array_merge($defaultArray, $replaceArr);
    if (MobileCommon::isMobile()) {
      return self::getUploadPhotoLink($replaceArr);
    }
    else {
      return Messages::getMessage(Messages::BUTTON, $defaultArray);
    }
  }		

  /**
   * Form verify phone button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getVerifyPhoneButton($replaceArr = array()) {

    $defaultArray = array("CLASS" => "fto-btn-green sprite-new cp", "ONCLICK" => "verify_layer_dp()","ID" => "VERIFY_PHONE_BUTTON", "VALUE" => "Verify Phone");
    $defaultArray = array_merge($defaultArray, $replaceArr);
    if(MobileCommon::isMobile())
    {   
      $defaultArray['HREF']=Messages::getKnowlarityNumber();
      return Messages::MobileLink($defaultArray,array("CLASS"=>self::MOB_BUTTON_CLASS),array(ONCLICK));
    }
    else
      return Messages::getMessage(Messages::BUTTON, $defaultArray);
  }

  /**
   * Form send reminder button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getSendReminderButton($replaceArr = array(), $layerid="") {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $defaultArray = array("CLASS" => "fto-btn-green sprite-new cp", "ONCLICK" => "onSendReminder('$layerid')", "ID" => "SEND_REMINDER_BUTTON", "VALUE" => "Send Reminder");
    $defaultArray = array_merge($defaultArray, $replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON,$defaultArray);
    else
      return Messages::getMessage(Messages::BUTTON, $defaultArray);
  }

  /**
   * Form complete your profile button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getCompleteYourProfileButton($replaceArr = array()) {

    $defaultArray = array("CLASS" => "fto-btn-green sprite-new cp", "ONCLICK" => "onCompleteNow()", "ID" => "COMPLETE_YOUR_PROFILE", "VALUE" => "Complete your Profile");
    $defaultArray = array_merge($defaultArray, $replaceArr);
    if(MobileCommon::isMobile())
    {
      return Messages::MOBILE_INCOMPLETE;
    }
    else
      return Messages::getMessage(Messages::BUTTON, $defaultArray);
  }
  /**
   * Form complete your profile link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getCompleteYourProfileLink($replaceArr = array()) {

    $defaultArray = array("CLASS" => "b underline","HREF"=>"/profile/viewprofile.php?ownview=1&EditWhatNew=incompleteProfile","ID" => "COMPLETE_YOUR_PROFILE", "LINK" => "Complete your profile");
    $defaultArray = array_merge($defaultArray, $replaceArr);
    
    
    
    if(MobileCommon::isMobile())
    {
      return Messages::MOBILE_INCOMPLETE;
    }
    else
      return Messages::getMessage(Messages::LINK, $defaultArray);
  }


  /**
   * Form suggested matches button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getSuggestedMatches($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"fto-btn-green sprite-new cp","ONCLICK"=>"RedirectFromCE('/search/partnermatches')","HREF"=>"#","ID"=>"SUGGESTED_BUTTON","VALUE"=>"Suggested Matches");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
    {
      return Messages::MobileLink($defaultArray,array("HREF"=>"/search/partnermatches","CLASS"=>self::MOB_BUTTON_CLASS),array(ONCLICK));
    }
    else
      return Messages::getMessage(Messages::BUTTON,$defaultArray);
  }
  /**
   * Form suggested matches link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getSuggestedMatchesLink($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"mgtop underline","HREF"=>"/search/partnermatches","LINK"=>"Suggested Matches");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);
  }

  /**
   * Form free trial offer link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getFreeTrialOfferLink($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $replaceArr[PROFILECHECKSUM]=$layerid;
    $defaultArray=array("CLASS"=>"fs16 orange f16","HREF"=>self::FTO_URL,"LINK"=>"Free Trial Offer");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }
  /**
   * Form free trial offer  button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getFreeTrialOfferButton($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $replaceArr[PROFILECHECKSUM]=$layerid;

    $defaultArray=array("CLASS"=>"sprite-new fto-btn-green w190 cp","ONCLICK"=>"RedirectFromCE('".self::FTO_URL."')","ID"=>"GET FREE_TRIAL_OFFER","VALUE"=>"Get Free Trial Offer");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
    {
      $link=Messages::getMessage(self::FTO_URL,$defaultArray);
      return Messages::MobileLink($defaultArray,array("HREF"=>$link,"CLASS"=>self::MOB_BUTTON_CLASS),array("ONCLICK"));
    }
    else
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }
  /**
   * Form free trail offer link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getKnowMoreLink($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $replaceArr[PROFILECHECKSUM]=$layerid;
    $defaultArray=array("CLASS"=>"b underline fs14","HREF"=>self::FTO_URL,"LINK"=>"Know more");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }


  /**
   * form click here link required on paid users templates[used for call directly]
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */
  public static function getClickHere($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;

    $defaultArray=array("CLASS"=>"underline cp","ONCLICK"=>"onClickHereDetail('$layerid')","LINK"=>"Click here");
    if(MobileCommon::isMobile())
    {
      $defaultArray[HREF]="/contacts/PostCalldirect?profilechecksum=$layerid&ALLOWED=1&{{NAVIGATOR}}";
      unset($defaultArray[ONCLICK]);
    }
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }

  /**
   * Form upload photo link required in templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getUploadPhotoLink($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"b underline","HREF"=>"/social/addPhotos","LINK"=>"Upload photo");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      $message=Messages::PHONE_UPLOAD_MES;
    else
      $message=Messages::LINK;
    return Messages::getMessage($message,$defaultArray);

  }

  /**
   * Form education and occupation link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getEducationOccupationLink($replaceArr = array()) {

    $defaultArray = array("CLASS" => "b underline", "ONCLICK" => "", "HREF" => "#", "LINK" => "Education &amp; Occupation");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);
  }

  /**
   * return verify you phone link
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getVerifyPhoneLink($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"b underline cp","ONCLICK"=>'verify_layer_dp();',"HREF" => "#","Link"=>"Verify your phone number");
    $defaultArray=array_merge($defaultArray,$replaceArr);

    if(MobileCommon::isMobile())
    {
      $defaultArray["HREF"]=Messages::getKnowlarityNumber();
      unset($defaultArray["ONCLICK"]);
    }
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }
  /**
   * return verify you phone link for confirmation page
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getVerifyPhoneLinkConfirmation($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"b underline cp thickbox","ONCLICK"=>'',"HREF" => "/profile/myjs_verify_phoneno.php?sourcePage=CONTACT&flag=1&width=700","Link"=>"Verify your phone number");
    $defaultArray=array_merge($defaultArray,$replaceArr);

    if(MobileCommon::isMobile())
    {
      $defaultArray["HREF"]=Messages::getKnowlarityNumber();
      unset($defaultArray["ONCLICK"]);
    }
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }
  /**
   * Return verify your phone message, required in templates or in error message
   * @return Message to show unverify message
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getVerifyPhoneMessage($replaceArr=array())
  {

    $defaultArray=array("CLASS"=>"thickbox","HREF"=>"/profile/myjs_verify_phoneno.php?width=700&sourcePage=".$replaceArr[ENGINETYPE]."&fromNewSearch=".$replaceArr[FROMSEARCH]."&searchId=".$replaceArr[SEARCHID]."","LINK"=>"Click here");

    if(MobileCommon::isMobile())
    {
      $defaultArray[HREF]=Messages::getKnowlarityNumber();
      
    }
    $link=Messages::getMessage(Messages::LINK,$defaultArray);		

    return Messages::getMessage(self::UNVERIFY_PHONE_LIMIT,array("ALINK"=>"$link"));


  }
  /**
   * Form paid membership link required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getBuyPaidMembershipLink($replaceArr=array(),$extra_params="")
  {
    $defaultArray=array("CLASS"=>"b underline","HREF"=>"/profile/mem_comparison.php?$replaceArr[NAVIGATOR]","Link"=>"Buy paid membership");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }



  /**
   * Form Accept button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getAcceptButton($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;

    $defaultArray=array("CLASS"=>"fto-btn-green sprite-new w94 cp","ONCLICK"=>"onAccept('$layerid')","HREF"=>"",ID=>"ACCEPT","VALUE"=>"Accept");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON, $defaultArray);
    else	
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }
  /**
   * form send email button
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getSendEmail($replaceArr=array(),$layerid)
  {
    if(MobileCommon::isMobile())
		$defaultArray=array("CLASS"=>"actived-btn","ONCLICK"=>"onSendEmail('$layerid')","HREF"=>"","ID"=>"SEND_EMAIL","VALUE"=>"Send Message");
	else
		$defaultArray=array("CLASS"=>"fto-btn-green sprite-new cp","ONCLICK"=>"onWrite('$layerid')","ID"=>"SENDEMAIL","VALUE"=>"Send Message");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON, $defaultArray);
    else
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }
  /**
   * form complete your profile now button
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getCompleteNowButton($replaceArr=array())
  {
    $defaultArray=array("CLASS"=>"fto-btn-green  sprite-new cp","ONCLICK"=>"onCompleteNow()","ID"=>"complete_now_tab","VALUE"=>"Complete your profile");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
    {
      $defaultArray[HREF]="/profile/viewprofile.php?ownview=1&EditWhatNew=incompleteProfile";
      $defaultArray[LINK]=$defaultArray[VALUE];
      unset($defaultArray[ONCLICK]);
      $defaultArray["CLASS"]=self::MOB_BUTTON_CLASS;
      return Messages::getMessage(Messages::LINK,$defaultArray);
    }
    else
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }

  /**
   * form not interested button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getNotInterestedButton($replaceArr=array(),$layerid="")
  {
    //if(!$layerid)
    $layerid=Messages::$userChecksum;
    $defaultArray=array("CLASS"=>"grey-grad-btn sprite-new cp","ONCLICK"=>"onNotInterested('$layerid')","HREF"=>"","ID"=>"NOT_INTERESTED","VALUE"=>"Not Interested");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
      return Messages::getMessage(Messages::MOB_BUTTON, $defaultArray);
    else	
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }

  /**
   * get not interested link required by templates.
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getNotInterestedLink($replaceArr=array(),$layerid="")
  {
    //	if(!$layerid)
    $layerid=Messages::$userChecksum;

    $defaultArray=array("CLASS"=>"fr cp","ONCLICK"=>"onNotInterested('$layerid')","ID"=>"NOT_INTERESTED","Link"=>"Not interested in this member?");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }

  /**
   * return link to show Detail 
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getLinkToShowDetail($replaceArr=array(),$layerid="",$viewerProfileObj,$from_where="")
  {
    //if(!$layerid)

    $layerid=Messages::$userChecksum;
    $editObj= new EditOnFtoContactConfirmation($viewerProfileObj);
    $linkToShow=$editObj->getLinkToShowHref($from_where);
    $textToshow=$editObj->getLinkToShowText();	
    $defaultArray=array("CLASS"=>"b underline thickbox ","ONCLICK"=>"","HREF"=>"$linkToShow","ID"=>"LINK_TO_SHOW","Link"=>"$textToshow");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    return Messages::getMessage(Messages::LINK,$defaultArray);

  }
  /**
   * Membership button required by templates
   * @return dynamic message present as const in Messages class
   * @param Array $replaceArr -> Array that need to be replaced with constant variable.
   * @throws JsException
   */		
  public static function getBuyPaidMembershipButton($replaceArr=array(),$extra_params="")
  {
    $defaultArray=array("CLASS"=>"sprite-new fto-btn-green w190 cp","ONCLICK"=>"RedirectFromCE('/profile/mem_comparison.php')","ID"=>"BUY_PAID_MEMBERSHIP","VALUE"=>"Buy paid membership");
    $defaultArray=array_merge($defaultArray,$replaceArr);
    if(MobileCommon::isMobile())
    {
      return Messages::MobileLink($defaultArray,array("HREF"=>"/profile/mem_comparison.php?$replaceArr[NAVIGATOR]","CLASS"=>self::MOB_BUTTON_CLASS),array(ONCLICK));

    }
    else
      return Messages::getMessage(Messages::BUTTON,$defaultArray);

  }


  /**
   * returns dynamic message present as const in Messages class
   * @param $type -> const type of Message clas
   *        $replaceArr --> Attribute to replace static content with dynamic values provided
   * @throws JsException
   */
  public static function getMessage($message,$replaceArr=array())
  {
	  $hrefTags=array("CLASS","HREF","ID","ONCLICK","VALUE","NAME","STYLE");
    if($replaceArr["ID"])
      $replaceArr["NAME"]=$replaceArr["ID"];
    if($message)
    {

      foreach($replaceArr as $key=>$val)
      {
        $key = strtoupper($key);
        if(in_array($key,$hrefTags))
          $message=str_replace("{{".$key."}}",strtolower($key)."=\"".$val."\"",$message);
        else	
          $message=str_replace("{{".$key."}}",$val,$message);

      }

    }
    else
      throw new JsException("",Messages::NO_MESSAGE,"");

    $message=preg_replace("%\{\{\w+\}\}%", '', $message);
    return $message;
  }
  /**
   * return pre eoi message
   * @param ContactEngineObj $contactEngineObj
   * @return String
   */
  public static function getEOIMessage($contactEngineObj)
  {
    $multi = $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI);

    if($multi)
      $message = Messages::EOI_MESSAGE_MULTI;
    else
      $message = $contactEngineObj->contactHandler->getViewed()->getUSERNAME().Messages::EOI_MESSAGE;
    return $message;
  }
  /**
   * return pre Accept message
   * @param ContactEngineObj $contactEngineObj
   * @return String
   */
  public static function getAcceptMessage($contactEngineObj)
  {
    $message = $contactEngineObj->getComponent()->drafts[0][1];
    return $message;
  }
  /**
   * return post eoi message
   * @param ContactEngineObj $contactEngineObj
   * @return String
   */
  public static function getPostAcceptMessage($contactEngineObj)
  {
    $multi = $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI);

    if($multi)
      $message = Messages::POSTACCEPT_MESSAGE_MULTI;
    else
      $message = Messages::POSTACCEPT_MESSAGE.$contactEngineObj->contactHandler->getViewed()->getUSERNAME();
    return $message;
  }
  /**
   * return post decline message
   * @param ContactEngineObj $contactEngineObj
   * @return String
   */
  public static function getPostDeclineMessage($contactEngineObj)
  {
    $multi = $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI);

    if($multi)
      $message = Messages::POSTDELCINE_MESSAGE_MULTI;
    else
      $message = Messages::POSTDECLINE_MESSAGE.$contactEngineObj->contactHandler->getViewed()->getUSERNAME();
    return $message;
  }

  /**
   * return fto message
   * @param ContactEngineObj $contactEngineObj
   * @return String
   */
  public static function getFTOMessage($contactEngineObj)
  {
    $multi = $contactEngineObj->contactHandler->getElements(CONTACT_ELEMENTS::MULTI);

    if($multi)
      $message = Messages::PHONE_EMAIL_ACCEPT_MULTI;
    else
    {
      if($contactEngineObj->getComponent->genderPronoun == 'he')
        $message = Messages::PHONE_EMAIL_ACCEPT.Messages::HIM;
      else
        $message = Messages::PHONE_EMAIL_ACCEPT.Messages::HER;
    }	
    return $message;
  }
  /**
   * return Mobile link
   * @param Array $defaultArray <format>$defaultArray=array("HREF"=>"a.php","CLASS"=>"FL")</format>
   * @param Array $updateArray <format>$updateArray=array("HREF"=>"a1.php","CLASS"=>"fr")</format>
   * @param Array $unsetArray <format>$unsetArray=array("HREF");
   * @return String
   */
  public static function MobileLink($defaultArray,$updateArray=array(),$unsetArray=array())
  {
    foreach($updateArray as $key=>$val)
      $defaultArray[$key]=$val;
    foreach($unsetArray as $key=>$val)
      unset($defaultArray[$val]);
    $defaultArray[LINK]=$defaultArray[VALUE];
    return Messages::getMessage(Messages::LINK,$defaultArray);
  }
  /** returns knowlarity number
   * @return String $number;
   * @uses ivr/knowlarityFunctions.php
   * @uses Messages::$viewerChecksum
   */
  public static function getKnowlarityNumber()
  {
    $profileid=CommonFunction::getProfileFromChecksum(Messages::$viewerChecksum);
    $messagesEnum=1;
    include_once(sfConfig::get("sf_web_dir")."/ivr/knowlarityFunctions.php");
    $number=getAllProfileVirtualNumbers($profileid);
	return "tel:".$number;
  }
  /**
   * returns message for Incomplete profile with Complete your profile link.
   * @param string $message
   * @access public 
   */
  public static function getIncompleteMessage($message)
  {
    $defaultArray=array("HREF"=>"/profile/viewprofile.php?ownview=1&EditWhatNew=incompletProfile","LINK"=>"Click here");

    if(MobileCommon::isMobile())
    {
      return Messages::getMessage($message,array("ALINK"=>Messages::MOBILE_INCOMPLETE));
    }
    else
    {
      $link=Messages::getMessage(Messages::LINK,$defaultArray);
      return Messages::getMessage($message,array("ALINK"=>"$link"));
    }

  }
  /** 
   * returns message for Incomplete profile with Complete your profile button.
   * @param string $message
   * @return String 
   */
  public static function getIncompleteMessagewithButton($message)
  {
    if(MobileCommon::isMobile())
    {
      return Messages::getMessage($message,array("BUTTON"=>Messages::MOBILE_INCOMPLETE));
    }
    else
    {
      $button=Messages::getCompleteYourProfileButton();
      return Messages::getMessage($message,array("BUTTON"=>"$button"));
    }
  }
  /**
   * returns message for unverify phone with button
   * @param string $message
   * @return String
   */

  public static function getUnverifiedPaidMessage($message)
  {
    $button = Messages::getVerifyPhoneButton();
    return Messages::getMessage($message,array("BUTTON"=>"$button"));
  }
  /**
   * returns message of contact limit reached for free user.
   * @param string $message
   * @return String
   */	
  public static function getFreeOverAllLimitMessage($message)
  {
    $button = Messages::getBuyPaidMembershipButton();
    return Messages::getMessage($message,array("BUTTON"=>"$button"));
  }
  public static function getScreeningDetailsMessage()
  {
		
		
		if(FTOLiveFlags::IS_FTO_LIVE)
		{
			$paidLink=Messages::getBuyPaidMembershipLink();
			$freeTrialOffer=Messages::getFreeTrialOfferLink();
			return Messages::getMessage(Messages::DETAILS_UNDERSCREENING_FTO,array("MEMBERSHIPLINK"=>"$paidLink","FREETRIALOFFER"=>"$freeTrialOffer"));
		}
		else
			return Messages::DETAILS_UNDERSCREENING;
	}
}
?>
