<?php
/**
 * class RawDuplicate
 * 
 */
class RawDuplicate 
{

   /*** Attributes: ***/

	 /**
   * 
   * @access const
   */
	const current_date="Y-m-d H:i:s";
	
  /**
   * 
   * @access private
   */
  private $profileid1;

  /**
   * 
   * @access private
   */
  private $profileid2;

  /**
   * 
   * @access private
   */
  private $reason;

  /**
   * 
   * @access private
   */
  private $comments="";

  /**
   * 
   * @access private
   */
  private $is_duplicate=IS_DUPLICATE::PROBABLE;

  /**
   * 
   * @access private
   */
  private $screened_by="";

  /**
   * 
   * @access private
   */
  private $screened_action=SCREEN_ACTION::NONE;

  /**
   * 
   * @access private
   */
  private $current_state=CURRENT_STATE::PROBABLE;

	/**
   * 
   * @access private
   */
   private $entry_dt;
  /**
   * 
   *
   * @param int profileid 

   * @return 
   * @access public
   */
  public function setProfileid1( $profileid ) {
	  $this->profileid1=intval($profileid);
  } // end of member function setProfileid1

  /**
   * 
   *
   * @param int profileid 

   * @return 
   * @access public
   */
  public function setProfileid2( $profileid ) {
	  $this->profileid2=intval($profileid);
  } // end of member function setProfileid2

  /**
   * 
   *
   * @param string reason 

   * @return 
   * @access public
   */
  public function setReason( $reason ) {
	  $this->reason=$reason;
  } // end of member function setReason

  /**
   * 
   *
   * @param string comments 

   * @return 
   * @access public
   */
  public function setComments( $comments ) {
	  $this->comments=$comments;
  } // end of member function setComments

  /**
   * 
   *
   * @param IS_DUPLICATE is_duplicate 

   * @return 
   * @access public
   */
  public function setIsDuplicate($is_duplicate ) {
	  $this->is_duplicate=$is_duplicate;
  } // end of member function setIsDuplicate

  /**
   * 
   *
   * @param string screened_by 

   * @return 
   * @access public
   */
  public function setScreenedBy( $screened_by ) {
	  $this->screened_by=$screened_by;
  } // end of member function setScreenedBy

  /**
   * 
   *
   * @param SCREEN_ACTION screen_action 

   * @return 
   * @access public
   */
  public function setScreenAction($screen_action ) {
	 $this->screened_action=$screen_action;
  } // end of member function setScreenAction

  /**
   * 
   *
   * @param CURRENT_STATE current_state 

   * @return 
   * @access public
   */
  public function setCurrentState($current_state ) {
	  $this->current_state=$current_state;
  } // end of member function setCurrentState

/**
   * 
   *
   * @param CURRENT_STATE current_state 

   * @return 
   * @access public
   */
  public function setEntryDt($date) {
	  $this->entry_dt=$date;
  } // end of member function setCurrentState
  
  /**
   * 
   *
   * @param string name 

   * @param string value 

   * @return 
   * @access public
   */
  public function addExtension( $name,  $value ) {
	  $this->$name=$value;
  } // end of member function addExtension

  /**
   * 
   *
   * @return int
   * @access public
   */
  public function getProfileid1( ) {
	  return $this->profileid1;
  } // end of member function getProfileid1

  /**
   * 
   *
   * @return int
   * @access public
   */
  public function getProfileid2( ) {
	  return $this->profileid2;
  } // end of member function getProfileid2

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getReason( ) {
	  return $this->reason;
  } // end of member function getReason

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getComments( ) {
	  return $this->comments;
  } // end of member function getComments

  /**
   * 
   *
   * @return IS_DUPLICATE
   * @access public
   */
  public function getIsDuplicate( ) {
	  return $this->is_duplicate;
  } // end of member function getIsDuplicate

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getScreenedBy( ) {
	  return $this->screened_by;
  } // end of member function getScreenedBy

  /**
   * 
   *
   * @return SCREEN_ACTION
   * @access public
   */
  public function getScreenAction() {
	  return $this->screened_action;
  } // end of member function getScreenAction

  /**
   * 
   *
   * @return CURRENT_STATE
   * @access public
   */
  public function getCurrentState( ) {
	  return $this->current_state;
  } // end of member function getCurrentState

  /**
   * 
   * @return string
   * @access public
   */
  public function getEntryDt() {
	  return $this->entry_dt;
  } // end of member function getExtension
	
  /**
   * 
   * @param string $name
   * @return string
   * @access public
   */
  public function getExtension($name) {
	  return $this->$name;
  } // end of member function getExtension
	

 


} // end of RawDuplicate
?>
