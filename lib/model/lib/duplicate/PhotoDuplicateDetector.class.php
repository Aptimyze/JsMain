<?php
/**
 * class PhotoDuplicateDetector
 * 
 */
class PhotoDuplicateDetector extends DuplicateDetector  
{


   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private  $photoDuplicateDetector;

  /**
   * 
   * @access private
   */
  private $PictureObj;

  /**
   * 
   * @access private
   */
  private $rawPhotoDuplicate;
   private $TYPE="PHOTO";

  public function __construct(DuplicateDetector $duplicateDetector)
  {
	  
	  $this->photoDuplicateDetector=$duplicateDetector;
	  $this->rawPhotoDuplicate=new RawDuplicate();
	 // $this->rawLandlineDuplicate->setProfileid1($duplicateDetector->profile->getPROFILEID());
	  $this->rawPhotoDuplicate->setProfileid1(LoggedInProfile::getInstance()->getPROFILEID());
	  $this->rawPhotoDuplicate->setReason($this->TYPE);
  }

  /**
   * 
   *
   * @return Duplicate
   * @access public
   */
  public function checkDuplicate( ) {
	  $duplicateObj=$this->photoDuplicateDetector->checkDuplicate();
	  
	  $this->rawPhotoDuplicate->setComments("phto is duplicate is 3456");
	  $this->rawPhotoDuplicate->setProfileid2(3456);
	  $this->rawPhotoDuplicate->setIsDuplicate(IS_DUPLICATE::YES);
	  $duplicateObj->addRawDuplicateObj($this->rawPhotoDuplicate);
	  return $duplicateObj;
  } // end of member function checkDuplicate





} // end of PhotoDuplicateDetector
?>
