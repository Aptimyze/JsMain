<?php
/**
 * class DuplicateDetector
 * 
 */
class DuplicateDetector
{


   /*** Attributes: ***/

  /**
   * 
   * @access protected
   */
  protected $no_of_checks = 0;

  /**
   * 
   *
   * @return Duplicate
   * @access public
   */
  public function  checkDuplicate( ) {
	  //var_dump($this->duplicate);
	  
	  return (new Duplicate());
  } // end of member function checkDuplicate



} // end of DuplicateDetector
?>
