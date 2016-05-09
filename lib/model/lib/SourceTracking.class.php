<?php
/**
 *CLASS SourceTracking
 * This class will handle the source tracking according to the page hit.
 * SourceTracking function will insert the source values in mis_source_Unknown table if a source is unknown and mis_hits_table to track the page hits depending upon the page from where it is called.
 * The SourceTracking obj which will contain the source information.
 *<BR>
 * How to call this file<BR> 
 * <code>
	* $sourceObj=new SourceTracking();
	* $sourceObj->setSource("IP");
	* $sourceObj->setFromPage("HOME_PAGE");
	* $sourceObj->SourceTracking();
* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   registration Revamp
 * @author   Nitesh
 * @copyright 2013 
  */
Class SourceTracking
{
	private $source;
	private $fromPage;
	
	/*
	 * set SourceTracking Obj
	 * @param source id ,page from whr it is called
	 */
	function __construct($source="",$fromPage="",$newSource="",$tieUpSource="")
	{
		if($source)
		$this->setSource($source);
		if($fromPage)
		$this->setFromPage($fromPage);
		if($newSource)
		$this->setNewSource($newSource);
		if($tieUpSource)
		$this->setTieUpSource($tieUpSource);
	}
	//SETTERS
	
	/**
	* set Source attribute of SourceTracking class
	* @return 
	 * @param source string
	 */
	function setSource($source)
	{
		$this->source=$source;
	}
	
	/**
	* set newSource attribute of SourceTracking class
	* @return 
	 * @param source string
	 */
	function setNewSource($newSource)
	{
		$this->newSource=$newSource;
	}
	
	/**
	* set setTieUpSource attribute of SourceTracking class
	* @return 
	 * @param tieup_source string
	 */
	function setTieUpSource($tieUpSource)
	{
		$this->tieUpSource=$tieUpSource;
	}
	
	/**
	* set fromPage attribute of SourceTracking class
	* @return 
	 * @param fromPage string
	 */
	function setFromPage($fromPage)
	{
		$this->fromPage=$fromPage;
	}
	
	//GETTERS
	
	/**
	* return source attribute of SourceTracking Object
	* @return source string
	 * @param
	 */
	function getSource()
	{
		return $this->source;
	}
	
	/**
	* return fromPage attribute of SourceTracking Object
	* @return fromPage string
	 * @param
	 */
	function getFromPage()
	{
		return $this->fromPage;
	}
	
	/**
	* return newSource attribute of SourceTracking Object
	* @return 
	 * @param source string
	 */
	function getNewSource()
	{
		return $this->newSource;
	}
	
	/**
	* return newSource attribute of SourceTracking Object
	* @return 
	 * @param source string
	 */
	function getTieUpSource()
	{
		return $this->tieUpSource;
	}
	/**
	* Source tracking of the user depending upon the page on which the user is coming. 
	* insert into Mis.UNKNOWN_SOURCE if a source is unknown and calls save_hit function to store hits in MIS_HITS  table.
	* @return
	 * frompage and source attribute should be set before calling this fucntion
	 */
	
	function SourceTracking()
	{
		if($this->fromPage==SourceTrackingEnum::$HOME_PAGE_FLAG)
		{
			if($this->source)
			{
				
				$dbMisSource=new MIS_SOURCE();
				if($result=$dbMisSource->existSource($this->source))
				{
					$this->source=$result;
					$this->savehit(SourceTrackingEnum::$REG_PAGE_URL);
				}
				else
				{
					$dbMisUnknownSource=new MIS_UNKNOWN_SOURCE();
					$dbMisUnknownSource->insertUnknownSource($this->source);
					
					$this->source=SourceTrackingEnum::$UNKNOWN_SOURCE;
					$this->savehit(SourceTrackingEnum::$REG_PAGE_URL);
				}
			}
			else
			{
				$this->source=SourceTrackingEnum::$SOURCE_IP;
				$this->savehit($_SERVER['PHP_SELF']);
			}
		}
		
		elseif($this->fromPage==SourceTrackingEnum::$REG_PAGE_1_FLAG)
		{
			if($this->source=="" && $this->tieUpSource=="")
			{
				if($this->newSource!="")
					$this->source=$this->newSource;
				elseif(isset($_COOKIE['JS_SOURCE']))
				{
					$this->source=$_COOKIE['JS_SOURCE'];
					//removing check  of if(!strstr(strtolower($source),"af")) to insert into mis_unknown source.
						
					$dbMisSource=new MIS_SOURCE();
					if(!$dbMisSource->existSource($this->source))
					{
						$dbMisUnknownSource=new MIS_UNKNOWN_SOURCE();
						$dbMisUnknownSource->insertUnknownSource($this->source);
						$this->source=SourceTrackingEnum::$UNKNOWN_SOURCE;
						
					}
						
				}
				else
				{
					$this->source=SourceTrackingEnum::$UNKNOWN_SOURCE;					
					$this->savehit($_SERVER['PHP_SELF']);
					
				}
			}
	// if source has come in that means that the person has clicked on a banner on jeevansathi
			else
			{
				if($this->source!='onoffreg')
				{
					if(isset($_COOKIE['JS_SOURCE']) && $this->source!='ofl_prof' && $this->source!='101')
					$this->source=$_COOKIE['JS_SOURCE'];
				}
				//removing check 
				//if(!strstr(strtolower($source),"af"))
				$dbMisSource=new MIS_SOURCE();
				if($result=$dbMisSource->existSource($this->source))
				{
					$this->source=$result;
				}
				else
				{
					$dbMisUnknownSource=new MIS_UNKNOWN_SOURCE();
					$dbMisUnknownSource->insertUnknownSource($this->source);
					$this->source=SourceTrackingEnum::$UNKNOWN_SOURCE;
						
				}
					
				$this->savehit($_SERVER['PHP_SELF']);
				
			}
		}
	}
	/**
	* inserts into MIS_HITS table to track the hits on the home page and registraion page 1.
	* @return
	 *  source attribute should be set before calling this function
	 * @param $pageName the name of the page to be entered into database.
	 */
	function savehit($pageName)
	{
		if(sfContext::getInstance()->getRequest()->getParameter("customReg"))
		{
			CommonUtility::SaveHit($this->source,'CUSTOM-REG');
		}
		elseif($this->source!="" && !stristr($_SERVER['HTTP_USER_AGENT'],"Adsbot-Google"))
		{
			$now = date("Y-m-d G:i:s");
			$ip=CommonFunction::getIP();
      
			$dbMisHits= new MIS_HITS();
			$dbMisHits->insertRecord($this->source,$now,$pageName,$ip);
		}
	}
	
	/**
	* inserts into MIS_REG_HOME table to track the sources from Home page to registraion page 1.
	* @return
	 * @param $source the home page source
	 * @param $profileId
	 */
	
	function sourceFromHomePage($source,$profileID)
	{
			$dbRegHome=new MIS_REG_HOME();
			$dbRegHome->insert($profileID,$source);
	}
	
  /*
   * Function to Log Reg Hits
   * @$ip : Reported Ip by FetchClientIp function
   */
  private function logRegHits($ip){
    if(SourceTrackingEnum::$REG_PAGE_1_FLAG !== $this->fromPage){
      return ;
    }
    try{
      //Log this hit
      $actualIP=CommonFunction::getClientIP();
      $objLogStore = new REG_LOG_REG_HITS;

      $url = $_SERVER['REQUEST_URI'];
      $reportedIP = $ip;

      $objLogStore->insertRecord($url,$actualIP,$reportedIP);
    }
    catch (Exception $e){
       //Send Mail to Developer 
        $subject = "reg.LOG_REG_HITS Error: Some issue while inserting records";
        $body = "Reported ip : ($reportedIP) , Actual Ip: ($actualIP), Url : ($url) , and exception :";
        SendMail::send_email("kunal.test02@gmail.com,lavesh.rawat@gmail.com",$body."'".print_r($e,true)."'",$subject);
        return ;
    }
  }
}
?>
