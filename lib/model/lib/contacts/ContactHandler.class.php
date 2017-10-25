<?php
/**
 *Contact Handler
 * Class which holds all the information required by libraries 
 * to perform any action related to contact engine.
 * 
 *  * How to call this class.
 * <code>

 * $viewerObj=new Profile(144111);
 * $viewedObj=new Profile(136580);
 * $engineType=ContactHandler::EOI;
 * $contactObj=new  Contacts(144111,136580);
 * $toBeType="I";
 * $action=ContactHandler::PRE;
 * $contactHandler=new ContactHandler($viewerObj, $viewedObj, $engineType, $contactObj, $toBeType, $action);
 * </code>
 * PHP versions 4 and 5

 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */

class ContactHandler
{



  /*** Constants: ***/
  
  const EOI="EOI";
  const CALL_DIRECT = "CALL_DIRECT";
  const CALL="CALL";
  const INFO="INFO";
  const PRE="PRE";
  const POST="POST";
  const NOCONTACT="N";
  const INITIATED="I";

  const REMINDER = "R";  
  const FILTERED = "F"; 
  const CANCEL_CONTACT="E";
  const ACCEPT="A";
  const DECLINE="D";
  const CANCEL="C";
  const WRITE_MESSAGE="M";
  const RECEIVER="R";
  const SENDER="S";
  const MULTI="MULTI";

/* contact enums where we distinguish contact type based on sender/receiver. */
  const INTEREST_SENT         = 'RI';
  const INTEREST_RECEIVED     = 'I';
  const ACCEPTANCES_SENT      = 'A';
  const ACCEPTANCES_RECEIVED  = 'RA';
  const DECLINED_SENT         = 'D';
  const DECLINED_RECEIVED     = 'RD';
  const CANCEL_SENT           = 'RC';
  const CANCEL_RECEIVED       = 'C';
  const CANCEL_EOI_SENT       = 'RE';
  const CANCEL_EOI_RECEIVED   = 'E';
  const CANCEL_ALL   = 'CER';
/* contact enums where we distinguish contact type based on sender/receiver. */
  /**
     * Profile Obj
     * Stores the Profile Obj of viewer, Who is trying to view the engine
     * 
     * @see Profile
     * @var Profile 
     */
  private $viewerObj;
  /**
     * Profile Obj
     * Stores the Profile Obj of viewed, Who is getting viewed by other user
     * @see Profile
     * @var Profile 
     */  
  private $viewedObj;
   /**
     * What engine user is trying to access[EOI/INFO]
     * @var String 
     */ 
  private $engineType;
   /**
     * Current type of contact b/w 2 users
     * @var String
     */
  private $contactType;
   /**
     * Action user is perfoming
     * User is just see the contact engine or user is 
     * performing action[Accepting,decline, initiating etc]
     * @var String
     */
  private $actionFlag;
/**
     * Used to fetch/set information while updating contact engine
     * class
     *
     * This variable is used in post/pre-parsing.
     *
     * Format:<pre>
     * array(
     * 				"status"=>"I",
     *        "fromPage"=>"VSP",
     *        "DRAFT_NAME"=>"DRE"
     *     )
     * )</pre>
     * @var array 
     */  
  private $contactElements;

   /**
     * Object that stores all the information about contact 
     * b/w 2 users, nohting but all information from contacts table
     * @see Contacts
     * @var Contacts 
     */  
  private $contactObj;
   /**
     * Object that store all the priviledge given to viewer profile when
     * he/she tries to contact/view details of other users.
     * @see Priviledge
     * @var Priviledge
     */   
  private $priObj;
   /**
     * Intended action of viewer
     * ex. Contact is in EOI state , so value can be "Cancle Eoi","Send Reminder"
     * @var String 
     */  
  private $toBeType;
   /**
     * Viewer request is coming from which source/page.
     * ex. View Similar Page[VSP], Search Page[SP]
     * @var String 
     */  
  private $pageSource;
  
  
  private $responseTracking;


  private $contactLimitWarning;

  public  function getContactLimitWarning(){
    return $this->contactLimitWarning;
  }
  public  function setContactLimitWarning($warning){
    $this->contactLimitWarning = $warning;
  }
  private $isJunk = false;
  private $junkType = 'default';
  private $junkData = 'default'; 

  public  function getIsJunk(){
    return $this->isJunk;
  }
  public  function setIsJunk($junk=false){
    $this->isJunk = $junk;
  }
  public function getJunkType(){
    return $this->junkType;
  }
  public function getJunkData(){
    return $this->junkData;
  }
  public function setJunkType($junkType=''){
    $this->junkType = $junkType;
  }
  public function setJunkData($junkData=''){
    $this->junkData = $junkData;
  }

/*
* This function update the default value while creation object
* @param Profile $viewerObj 
* @param Profile $viewedObj
* @param String $engineType Stores EOI/Contact Details call
* @param Contacts $contactObj
* @param String tobestatus Status changed while doing EOI
* @action String just viewing or posting on Eoi form
*
*/

  public function __construct($viewerObj,$viewedObj,$engineType,$contactObj,$toBeType,$action)
  {
    $this->setViewer($viewerObj);
    $this->setViewed($viewedObj);
    $this->setEngineType($engineType);
    $this->setContactObj($contactObj);
    $contactType=$contactObj->getType();
    $this->setContactType($contactType);
    $this->setAction($action);
    if(isset($toBeType))
	    $this->setToBeType($toBeType);
	 $this->setPageSource();   
  }
  /**
   * Return type of contact he/she is performing[EOI/C.D.]
   *
   * @param CONTACT_TYPE type 

   * @return string
   * @access public
   */
  public function getEngineType() {
    return $this->engineType;

  } // end of member function getType

  /**
   * Set type of contact he/she is performing[EOI/C.D.]
   *
   * @param String $toBeType 
   * @access public
   */
  public function setToBeType($toBeType)
  {
		$this->toBeType	=$toBeType;
  }
  public function getToBeType()
  {
	return $this->toBeType;
  }
  /**
   * What action user is performing in terms of contact engine[EOI/Contact Details section]
   *
   * @param CONTACT_TYPE type 
   * @access public
   */
  public function setEngineType( $type ) {
    if($type==ContactHandler::EOI || $type==ContactHandler::INFO || $type==ContactHandler::CALL ||  $type==ContactHandler::DETAILS) 
      $this->engineType=$type;
    else
      throw new JsException("",Messages::ENGINE_ERROR);
  } // end of member function setType


  /**
   * 
   * Return Type of contact users currently share
   * @param CONTACT_TYPE type 

   * @return string
   * @access public
   */
  public function getContactType() {
    return $this->contactType;
  } // end of member function getType

  /**
   * SEt type of contact users currently share
   *
   * @param CONTACT_TYPE type 
   * @access public
   */
  public function setContactType( $type ) {
    $this->contactType=$type;
  } // end of member function setType

  /**
   * Return who is sender of contact
   * @return string
   * @access public
   */
  public function getContactInitiator()
  {
		if($this->contactObj->getReceiverObj()->getPROFILEID()==$this->viewerObj->getPROFILEID())
			return ContactHandler::RECEIVER;
		else
			return ContactHandler::SENDER;
  }
  
/**
* Return particular element value
*@param CONTACTS_ELEMENTS $type 
* @access public
*/
  public function getElements($type) {
    if($type)
    {
      if(CONTACT_ELEMENTS::VerifyInput($type))
        return $this->contactElements[$type];
      else
        throw new JsException("",Messages::ELEMENT_ERROR);
    }
    else			
      return $this->contactElements;		

  } // end of member function getElements

  /**
   * Set element coming through form
   * @param CONTACT_ELEMENTS $type
   * @param string $value
   * @access public
   */
  public function setElement( $type,  $value ) {
    if(CONTACT_ELEMENTS::VerifyInput($type))
      $this->contactElements[$type]=$value;
    else
      throw new JsException("",Messages::ELEMENT_ERROR);
  } // end of member function setElement

  /**
   *Return Action performed by user [Pre/Post] 
   * @param string $ActionFlag 
   * @return String
   * @access public
   */
  public function getAction( $ActionFlag='' ) {
    return $this->actionFlag;
  } // end of member function getAction

  /**
   * Set action perfomed by user[Pre/Post]
   * @param string ActionFlag 
   * @access public
   */
  public function setAction( $actionFlag ) {
    if($actionFlag==ContactHandler::PRE || $actionFlag==ContactHandler::POST) 
      $this->actionFlag=$actionFlag;
    else
      throw new JsException("",Messages::ACTION_ERROR);
  } // end of member function setAction

  /**
   * Set Profile profileObj
   * @param Profile $profileObj
   * @access public
   */
  public function setViewer( $profileObj ) {
    if($profileObj instanceof Profile) {
      $this->viewerObj = $profileObj;
    }
    else
      throw new JsException("",Messages::NO_PROFILE_OBJ);
  } // end of member function setSender

  /**
   * Return profileObj
   * @return ProfileObj
   * @access public
   */
  public function getViewer() {
    return $this->viewerObj;
  } // end of member function getSender

  /**
   * set profile Obj as viewed 
   * @param Profile profileobj
   * @access public
   */
  public function setViewed( $profileObj ) {
    if($profileObj instanceof Profile)
		$this->viewedObj = $profileObj;
    else
		throw new JsException("",Messages::NO_PROFILE_OBJ);
  } // end of member function setReceiver

  /**
   * get viewer obj 
   * @return Profile viewerobj
   * @access public
   */
  public function getViewed() {
    return $this->viewedObj;
  }

  /**
   * 
   * get contacts class obj
   * @param Contacts contactObj 
   * @access public
   */
  public function setContactObj($contactObj) {
  	if($contactObj instanceof Contacts )
  		$this->contactObj=$contactObj;
  	else
  		throw new JsException("",Messages::CONTACT_ERROR);
  }
   /**
   * return contacts class Obj
   * @return Contacts contactObj
   * @access public
   */
  public function getContactObj()
  {  	
  	return $this->contactObj;
  }
   /**
   * return priviledgeObj, used to fetch user's permission in contact engine
   * @return Priviledge Object of Paid/Free/FTO Privilege class
   * @access public
   */
  public function getPrivilegeObj()
  {
	if(!($this->priObj instanceof Privilege))
		$this->priObj = PrivilegeFactory::getPrivObj($this->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus());
		$privilegeArray=$this->priObj->updatePrivilege($this);
		return $this->priObj;
  } 
   /**
   * From which page user is comming.
   * @return pageSourc (used to fetch Http:Page from which this Post action has been called in contactEngine)
   * @access public
   */
  public function getPageSource()
  {
	  return $this->pageSource;
	  /*$this->pageSource="D";
	  $pageSource=$_SERVER["HTTP_REFERER"];
	if(strstr($pageSource,'simprofile_search.php') || strstr($pageSource,'view_similar_profile.php'))
		$this->pageSource='VSP';
	elseif(strstr($pageSource,'viewprofile.php'))
		$this->pageSource='VDP';
	elseif(strstr($pageSource,'search.php'))
		$this->pageSource='S';
	elseif(strstr($pageSource,'contacts_made_received.php'))
		$this->pageSource='C';
	return $this->pageSource;
	*/
  } 
  public function setPageSource($pageSource='VDP')
  {
	$this->pageSource=$pageSource;  
  }
   
}
?>
