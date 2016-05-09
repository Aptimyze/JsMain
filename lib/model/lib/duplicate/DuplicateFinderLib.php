<?php
/**
 * class DuplicateFinderLib
 * 
 */
class DuplicateFinderLib
{

    /*** Attributes: ***/
     const ALL=1;
     const EMAIL=1;
     //const LANDLINE=1;
     //const MOBILE=1;
     const PHOTO=1;
     const CRAWLER=1;
     const PHONE=1;
    
  /**
   * 
   *
   * @param int profileid 

   * @param undef checkfor 

   * @return undef
   * @static
   * @access public
   */
  public static function findDuplicate( $profile,  $checkfor , $changedBits='') {
	$profileid = LoggedInProfile::getInstance()->getPROFILEID();
	 $duplicateDetector=new DuplicateDetector($profileid); //Internally it will create Duplicate class object
	
	//Will be accessed by classes. 
	if($checkfor[PHONE]==DuplicateFinderLib::PHONE || $checkfor[ALL]==DuplicateFinderLib::ALL)
		$duplicateDetector=new PhoneDuplicateDetector($duplicateDetector,$changedBits); 
	if($checkfor[PHOTO]==DuplicateFinderLib::PHOTO || $checkfor[ALL]==DuplicateFinderLib::ALL)	
		$duplicateDetector=new PhotoAttributesDuplicatesDetector($duplicateDetector);
	if($checkfor[CRAWLER]==DuplicateFinderLib::CRAWLER || $checkfor[ALL]==DuplicateFinderLib::ALL)	
		$duplicateDetector=new CrawlerDuplicateDetector($duplicateDetector);
	$duplicateObj=$duplicateDetector->checkDuplicate(); 
	$duplicateObj->insertDuplicates();
  } // end of member function findDuplicate


} 
//DuplicateFinderLib::findDuplicate(144111,array("ALL"=>1));
// end of DuplicateFinderLib
?>
