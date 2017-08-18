<?php

/**
 * class FetchLevel
 * 
 */
class FetchLevel
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $levelObj;

  /**
   * 
   *
   * @param string url 
   * @return SEO_COMMUNITY
   * @access public
   */
  function FetchLevelObj( $url )
  {
    //SEO_COMMUNITY $levelObj = LevelGenerateFactory::createLevel($url);
  	$this->levelObj = LevelGenerateFactory::createLevel($url);
  	//set values in object
    return  $this->levelObj;
  } // end of member function FetchLevelObj





} // end of FetchLevel
?>
