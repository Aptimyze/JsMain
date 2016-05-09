<?php

/* this class has enums for new addition of search parameters which need not be added
 * to searchQuery table adding them here will be sufficient
 * @author : Ankit Shukla
 * @package Search
 * @since 2016-02-23
 */

class searchCriteriaParamsEnum{
	public static $searchCriteria= array(
          'verifiedMatches' => array('field'=>"FSO_VERIFIED",'value'=>"F,")
          );
	}
?>
