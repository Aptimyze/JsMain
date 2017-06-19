<?php

/**
 * class LevelGenerateFactory
 * 
 */
class LevelGenerateFactory
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/


  /**
   * 
   *
   * @param string url 
   * @return SEO_COMMUNITY
   * @static
   * @access public
   */
  static public function createLevel( $url )
  {
  	$obj1 = new NEWJS_COMMUNITY_PAGES();
  	 
  	if($row_url =  $obj1->getLevelObject($url))
  	{  	    
  	    $levelObj = new LevelOne();
  	    $levelObj->setLevelOneProperties($row_url);
  	}
  	else
  	{
  	    $obj2 = new NEWJS_COMMUNITY_PAGES_MAPPING();
  		$row_url =  $obj2->getLevelObject($url);
  		if($row_url)
  		{
			$levelObj = new LevelTwo();
			$levelObj->setLevelTwoProperties($row_url);
		}	
  	}
  	    return $levelObj;
    
  } // end of member function createLevel
  
   
} // end of LevelGenerateFactory
?>
