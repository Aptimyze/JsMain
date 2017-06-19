<?php

/**
 * class CommunityLevel
 * 
 */
class CommunityLevel extends SEO_COMMUNITY
{
  
  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/
  private $LEVEL_NUM; 
  
  private $BRIDE_URL;
  
  private $GROOM_URL;
  /**
   * 
   *
   * @param CommunityLevel level 
   * @return 
   * @access public
   */
  function setBrideGroomURL()
  {
    if ($this->getPageSource() != 'N') return;		
	
	if ($this->getLevelNum() == 1){
		 $obj = new NEWJS_COMMUNITY_PAGES();
		 $whereArr= array ('VALUE' => $this->getParentValue(),'TYPE'=>$this->getParentType());
		 $result = $obj->getURL($whereArr);
	}	 
	elseif($this->getLevelNum() == 2)
	{
		 $obj = new NEWJS_COMMUNITY_PAGES_MAPPING();
		 $whereArr = array('PARENT_VALUE' =>$this->getParentValue(),'MAPPED_VALUE'=>$this->getMappedValue(),'PARENT_TYPE'=>$this->getParentType(),'MAPPED_TYPE'=>$this->getMappedType());
		 $result = $obj->getURL($whereArr);
	}
	foreach($result as $row)
	{
		if($row["PAGE_SOURCE"]=="B")	
			$this->BRIDE_URL = $row["URL"];
		else
			$this->GROOM_URL = $row["URL"];
	}		
  } // end of member function fetchBrideGroomURL
  
  function getBrideURL() { return $this->BRIDE_URL;}
  function getGroomURL() { return $this->GROOM_URL;}
  
  function setLevelNum($LEVEL_NUM)
  {
  	$this->LEVEL_NUM = $LEVEL_NUM;    	
  }
  function getLevelNum()
  {
  	return $this->LEVEL_NUM;    	
  } 
  /**
   * 
   *
   * @return 
   * @abstract
   * @access public
   */
  function createBreadCrumb( )
  {
  	$sbc = new SEOBreadCrumb();  	
  	$sbc->setPageSource($this->getPageSource());
  	$sbc->setParentType($this->getParentType());
  	$sbc->setParentValue($this->getParentValue());
  	$sbc->setMappedValue($this->getMappedValue());
  	$sbc->setMappedType($this->getMappedType());
  	$sbc->setLevel1Count();
  	$sbc->setLevelOneBreadCrumb();
  	$sbc->setLevelTwoBreadCrumb();
  	return $sbc;  	
    
  } // end of member function createBreadCrumb
  
  function getProfiles()
  {
  	$seoProfileObj=new SearchSeoProfiles();
       
    $Level=$this->getLevelNum();
    if($Level == 1)
    {
        $type=$this->getParentType();
        $seoProfileObj->setLevel1Type($type);
        $value=$this->getParentValue();
        $seoProfileObj->setLevel1Value($value);
    }
    else if($Level==2)
    {
        $type=$this->getMappedType();
        $seoProfileObj->setLevel2Type($type);
        $value=$this->getMappedValue();
        $seoProfileObj->setLevel2Value($value);
        $type2=$this->getParentType();
        $seoProfileObj->setLevel1Type($type2);
        $value2=$this->getParentValue();
        $seoProfileObj->setLevel1Value($value2);
    }
    return $seoProfileObj;
       
   }// end of member function getProfiles
public  function setLevelOneProperties($row_url)
  {
    $this->setParentValue($row_url['VALUE']);
    $this->setLabelName($row_url['LABEL_NAME']);
    $this->setSmallLabel($row_url['SMALL_LABEL']);	
    $this->setParentType($row_url['TYPE']);	
    $this->setLevel($row_url['LEVEL']);	

    $this->setSource($row_url['SOURCE']);	
    $this->setTitle($row_url['TITLE']);
    $this->setDescription($row_url['DESCRIPTION']);
    $this->setKeywords($row_url['KEYWORDS']);
    $this->setH1Tag($row_url['H1_TAG']);
    $this->setFollow($row_url['FOLLOW']);
    $this->setImgUrl($row_url['IMG_URL']);
    $this->setContent(nl2br($row_url['CONTENT']));
    $this->setAltTag(trim($row_url['ALT_TAG']));
    $this->setPageSource($row_url['PAGE_SOURCE']);
    $this->setLevelNum(1);
  }
  public  function setLevelTwoProperties($row_url)
  {
    if($row_url["PARENT_VALUE"]=="NRI")
    {
        $NRIValues = implode(" ",array_keys(FieldMap::getFieldLabel("impcountry","",1))); $NRIValues = str_replace("51", "", $NRIValues); // Removing India from list $row_url["PARENT_VALUE"]= $NRIValues; 
    	$this->setParentValue($NRIValues);
    } else {
	    $this->setParentValue($row_url['PARENT_VALUE']);
    }
    $this->setParentType($row_url['PARENT_TYPE']);
    $this->setParentLabel($row_url['PARENT_LABEL']);	
    $this->setMappedValue($row_url['MAPPED_VALUE']);	
    $this->setMappedType($row_url['MAPPED_TYPE']);
    $this->setMappedLabel($row_url['MAPPED_LABEL']);		
    $this->setSource($row_url['SOURCE']);                   	
    $this->setTitle($row_url['TITLE']);
    $this->setDescription($row_url['DESCRIPTION']);
    $this->setKeywords($row_url['KEYWORDS']);
    $this->setH1Tag($row_url['H1_TAG']);
    $this->setFollow($row_url['FOLLOW']);
    $this->setImgUrl($row_url['IMG_URL']);
    $this->setContent(nl2br($row_url['CONTENT']));
    $this->setAltTag(trim($row_url['ALT_TAG']));
    $this->setPageSource($row_url['PAGE_SOURCE']);
    $this->setLevelNum(2);
  }

} // end of CommunityLevel
?>
