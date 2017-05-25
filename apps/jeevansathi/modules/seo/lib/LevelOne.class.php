<?php

/**
 * class LevelOne
 * 
 */
class LevelOne extends CommunityLevel
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/
 /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $PAGE_SOURCE;
  /**
   * 
   * @access private
   */
  private $TITLE;
  /**
   * 
   * @access private
   */
  private $ALT_TAG;
  /**
   * 
   * @access private
   */
  private $DESCRIPTION;
  /**
   * 
   * @access private
   */
  private $KEYWORDS;
  /**
   * 
   * @access private
   */
  private $PARENT_VALUE;
  /**
   * 
   * @access private
   */
  private $PARENT_TYPE;
  /**
   * 
   * @access private
   */
  private $SOURCE;
  
  private $FOLLOW;
  /**
   * 
   * @access private
   */
  private $IMG_URL;  
  
  private $SMALL_LABEL;

  private $LABEL_NAME;
  
  private $LEVEL;
  
  private $H1_TAG;
  
  private $CONTENT;
  
  function getLabelName(){ return $this->LABEL_NAME;}
  function setLabelName($LABEL_NAME){ $this->LABEL_NAME = $LABEL_NAME;}
  
  function getSmallLabel(){ return $this->SMALL_LABEL;}
  function setSmallLabel($SMALL_LABEL){ $this->SMALL_LABEL = $SMALL_LABEL;}  
  
  function getLevel() { return $this->LEVEL;}
  function setLevel($LEVEL) { $this->LEVEL = $LEVEL;}
    
  function getParentValue() { return $this->PARENT_VALUE;}
  function setParentValue($PARENT_VALUE) { $this->PARENT_VALUE = $PARENT_VALUE; }    

  function getParentType()  { return $this->PARENT_TYPE; }
  function setParentType($PARENT_TYPE) { $this->PARENT_TYPE = $PARENT_TYPE;}  

  function getSource()      { return $this->SOURCE;      }
  function setSource($SOURCE) { $this->SOURCE = $SOURCE ; }

  function getContent()     { return $this->CONTENT;     }
  function setContent($CONTENT) {$this->CONTENT=$CONTENT;}
  
  function getTitle()       { return $this->TITLE;       }
  function setTitle($TITLE) { $this->TITLE = $TITLE; }

  function getDescription() { return $this->DESCRIPTION; }
  function setDescription($DESCRIPTION) { $this->DESCRIPTION = $DESCRIPTION;} 
    
  function getKeywords()    { return $this->KEYWORDS;    }
  function setKeywords($KEYWORDS) { $this->KEYWORDS = $KEYWORDS;}
    
  function getH1Tag()       { return $this->H1_TAG;     }
  function setH1Tag($H1_TAG) { $this->H1_TAG = $H1_TAG;}
    
  function getFollow()      { return $this->FOLLOW;      }
  function setFollow($FOLLOW) { $this->FOLLOW = $FOLLOW;}
    
  function getImgUrl()      { return $this->IMG_URL;     }
  function setImgUrl($IMG_URL) { $this->IMG_URL = $IMG_URL;}
    
  function getAltTag()      { return $this->ALT_TAG;     }
  function setAltTag($ALT_TAG) { $this->ALT_TAG = $ALT_TAG;}
  
  function getPageSource() { return $this->PAGE_SOURCE;  }
  function setPageSource($PAGE_SOURCE) {$this->PAGE_SOURCE = $PAGE_SOURCE;}
  
  function getMappedValue() { return '';}
  function getMappedType() { return '';}  

} // end of LevelOne
?>
