<?php

/**
 * sitemap actions.
 *
 * @package    jeevansathi
 * @subpackage sitemap
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sitemapActions extends sfActions
{
 
  public function preExecute()
  {
  	$response=sfContext::getInstance()->getResponse();
	$title='Jeevansathi - Site Map';
	$response->setTitle($title);
	$this->typeArr = array('MTONGUE','CASTE','RELIGION','OCCUPATION','CITY','COUNTRY','STATE');
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	                                                                     
  }
  public function executeShow($request)
  {
  	$pathInfoArray = $request->getPathInfoArray();
  	$uri = $pathInfoArray['REQUEST_URI'];
    $uri = strtolower($uri);
  	$uriArr = preg_split( "/[.|-]/", $uri );
  	if($uriArr[0]=='/matrimony')
  		$this->pageSource = 'N';
  	else if($uriArr[0] == '/brides')
  		$this->pageSource = 'B';
  	else if($uriArr[0] == '/grooms')
  		$this->pageSource = 'G';
  			
  	$size = count($uriArr);
  	if($uriArr[$size-2] == 'profiles')
  	{
  		$this->setLinks($this->pageSource);
  		return 'LevelOne';
  	}
  	else
  	{
  		$this->setParameters($uriArr,$size);
  		//this check is to check whether given url is correct or not
  		if($uriArr[2]=='by' && in_array(strtoupper($this->parentType), $this->typeArr) && in_array(strtoupper($this->mappedType), $this->typeArr))
  		{
  			$this->setSecondLevelLinks(strtoupper($this->parentType), strtoupper($this->mappedType),$this->pageSource);
  			return 'LevelTwo';
  		}
  		else
  			$this->forward("seo","404");	
  	}
  }
  
  public function setLinks($pageSource)
  {
  	$dbObj = new NEWJS_COMMUNITY_PAGES();
  	$siteMapLinks = $dbObj->getSiteMapLinks($pageSource);
  	$this->mtongueLinks = $siteMapLinks['MTONGUE'];
  	$this->casteLinks = $siteMapLinks['CASTE'];
  	$this->religionLinks = $siteMapLinks['RELIGION'];
  	$this->occupationLinks = $siteMapLinks['OCCUPATION'];
  	$this->cityLinks = $siteMapLinks['CITY'];
  	$this->stateLinks = $siteMapLinks['STATE'];
  	$this->countryLinks = $siteMapLinks['COUNTRY'];
  }
  
  public function setSecondLevelLinks($parentType,$mappedType,$pageSource)
  {
  	$dbObj = new NEWJS_COMMUNITY_PAGES_MAPPING();
  	$this->siteMapLinks = $dbObj->getSiteMapLinks($parentType,$mappedType,$pageSource);
  }
  
  /**
   * 
   * set the parent type and Maped Type
   * @param array $uriArr
   * @param int $size
   */
  public function setParameters($uriArr,$size)
  {
  	$mappedTypeIndex = $size - 2;
  	if($uriArr[$mappedTypeIndex]=='tongue' && $uriArr[$mappedTypeIndex-1]=='mother')
  	{
 		$this->mappedType = 'MTONGUE';
 		$this->showMappedType = 'Mother Tongue';
 		$mappedTypeIndex--;
  	}
  	elseif($uriArr[$mappedTypeIndex]=='caste' && $uriArr[$mappedTypeIndex-1]=='sub')
  	{
 		$this->mappedType = 'CASTE';
 		$this->showMappedType = 'Sub Caste';
 		$mappedTypeIndex--;
  	}
  	else
  	{
 		$this->mappedType = $uriArr[$mappedTypeIndex];
 		$this->showMappedType = ucfirst($this->mappedType);
  	}
	
  	$parentTypeIndex = $mappedTypeIndex-1;
 	if($uriArr[$parentTypeIndex]=='tongue'&& $uriArr[$parentTypeIndex-1]=='mother')
 	{
 		$this->parentType = 'MTONGUE';
 		$this->showParentType = 'Mother Tongue';
 	}
 	else
 	{		
  		$this->parentType = $uriArr[$parentTypeIndex];
  		$this->showParentType = ucfirst($this->parentType);
 	}
  }
}
