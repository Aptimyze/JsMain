<?php
/**
 * class LandlineDuplicateDetector
 * 
 */
class LandlineDuplicateDetector extends DuplicateDetector
{


   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private  $landlineDuplicateDetector;

  /**
   * 
   * @access private
   */
  private $rawLandlineDuplicate;
  
  private $TYPE="PHONE";
  
  public function __construct(DuplicateDetector $duplicateDetector)
  {
	  
	  $this->landlineDuplicateDetector=$duplicateDetector;
	  
	  $this->rawLandlineDuplicate=new RawDuplicate();
	 // $this->rawLandlineDuplicate->setProfileid1($duplicateDetector->profile->getPROFILEID());
	  $this->rawLandlineDuplicate->setProfileid1(LoggedInProfile::getInstance()->getPROFILEID());
	  $this->rawLandlineDuplicate->setReason($this->TYPE);
	  
	  
  }

  /**
   * 
   *
   * @return Duplicate
   * @access public
   */
  public function checkDuplicate() {
	 
	  $duplicateObj=$this->landlineDuplicateDetector->checkDuplicate();
	   
	  $this->rawLandlineDuplicate->setComments("Lanline is 1444");
	  $this->rawLandlineDuplicate->setProfileid2(3456);
	  $this->rawLandlineDuplicate->setIsDuplicate(IS_DUPLICATE::YES);
	  $duplicateObj->addRawDuplicateObj($this->rawLandlineDuplicate);
	  
	  return $duplicateObj;
	  
  } // end of member function checkDuplicate





} // end of LandlineDuplicateDetector
?>
