<?php
/**
 * userSpecific helps in getting next/previous profile when coming
 * from search or contact center.
 * 
 * @package    jeevansathi
 * @subpackage apps
 * @author     Nikhil dhiman
 * @version    SVN: $Id: userSpecific.class.php 23810 2011-07-12 11:07:44Z nikhil.dhiman $
 */
class userSpecific
{
	public static $called=null;
	
	/**
	 * Creates object if not created else
	 * passon the previously created object.
	 */
	public static function getInstance()
	{
		
		if(self::$called==null)
		{
			//If different instance is required
			$class = __CLASS__;
			self::$called = new $class();							
			
		}
		
		return self::$called;
	}
	/**
	 * 	Retrive profile when next/prev is clicked by user
	 * on detailed page.
	 */
	public function showNextPrev()
	{
		$request=sfContext::getInstance()->getRequest();
		$this->searchid=$request->getParameter("searchid");
		$this->show_profile=$request->getParameter("show_profile");
		$this->stype=$request->getParameter("stype");
		$this->ONLINE_SEARCH=$request->getParameter("ONLINE_SEARCH");
		$this->Sort=$request->getParameter("Sort");
		$this->actual_offset_real=$this->actual_offset=$request->getParameter("actual_offset");
		$this->offset=$request->getParameter("offset");
		$this->j=$request->getParameter("j");
		$this->total_rec=$request->getParameter("total_rec");
		$this->profilechecksum=$request->getParameter("profilechecksum");
		$this->SHOW_NEXT_PREV=0;
		$this->other_params="";
		$this->SHOW_PREV=0;
		$this->SHOW_NEXT=0;
		$this->bIsSearchIdExpire = false;
		$bHitFromMyjsPageAndroid = strlen($request->getParameter("hitFromMyjs"))!=0?true:false;
		if($bHitFromMyjsPageAndroid){
            $this->profilechecksum = profileDisplay::getNextPreviousProfileForMyjs('dailymatches',$this->actual_offset_real);
        }
		if($this->is_allowed_np() || $bHitFromMyjsPageAndroid)
		{  
			if(($this->searchid && $this->show_profile) || $bHitFromMyjsPageAndroid)
				$this->search_next_prev($bHitFromMyjsPageAndroid);
		
			if($this->searchid)
				$this->set_next_prev();
		}
	}
	/**
	 * Checks if show next/prev option is allowed for similar profile 
	 * search algo
	 * @return true/false
	 */
	private function is_allowed_np()
	{
		//$request=$this->getRequest();
		
		$STYPE_ARR=array("VO","VN","CO","CN","CN2");
		if(!in_array("$this->stype",$STYPE_ARR) && $this->searchid && $this->ONLINE_SEARCH!=1)
		{
				$this->SHOW_NEXT_PREV=1;
				return true;
		}
		return false;
	}
	/**
	 * Update the variables whenever next prev is clicked
	 */
	private function set_next_prev()
	{  
		$request=sfContext::getInstance()->getRequest();
		$other_params="";
		foreach($request->getGetParameters() as $key=>$val)
		{
			if($key!="profilechecksum" && $key!="NAVIGATOR" && $key!="searchid" && $key!="j" && $key!="total_rec" && $key!="actual_offset" && $key!="offset" && $key!="show_profile" && $key!="after_login_call" && $key!="CALL_ME" && $key!="CAME_FROM_CONTACT_MAIL" && $key!="AllPhotos")
				$other_params.="&$key=$val";
		}
		$this->other_params=$other_params;

		if(!$this->actual_offset)
		{
			if(!$this->j)
				$this->j=1;
				
			$this->actual_offset=($this->j-1)*SearchCommonFunctions::getProfilesPerPageOnSearch()+$this->offset;
			if(!$this->actual_offset)
				$this->actual_offset=0;
		}
		//$request->setParameter("actual_offset",$this->actual_offset);

		$this->SHOW_PREV=1;
		$this->SHOW_NEXT=1;
		if($this->actual_offset==0)
			$this->SHOW_PREV=0;
			
		$total_records=$this->total_rec-1;
		if($this->actual_offset==$total_records)
			$this->SHOW_NEXT=0;
				
	}
	/**
	 * Search for profile by using search logic..
	 */
	private function search_next_prev($hitFromMyjs = false)
	{
		if(!$this->j)
			$this->j=1;
		$db_master = connect_db();	
		//$actual_offset=$request->getParameter("actual_offset");	
		if(!$this->actual_offset)
			$this->actual_offset=($this->j-1)*SearchCommonFunctions::getProfilesPerPageOnSearch()+$this->offset;
		if($hitFromMyjs == true)
		{  
		  $this->next_prev_prof = profileDisplay::getNextProfileIdForMyjs('dailymatches',$this->actual_offset_real);;
		}
		else
		{	
		$this->next_prev_prof=$this->next_prev_view_profileid($this->searchid,$this->Sort,$this->actual_offset,$this->show_profile,$this->stype);
		}
		$this->profilechecksum=JsCommon::createChecksumForProfile($this->next_prev_prof);
		
		//$request->setParameter("profilechecksum",$profilechecksum);
		
		
		if($this->show_profile=="prev")
		{
			$this->actual_offset=$this->actual_offset-1;
			//$request->setParameter("actual_offset",$this->actual_offset);
		}
		elseif($this->show_profile=="next")
		{
			
			$this->actual_offset=$this->actual_offset+1;
			//$request->setParameter("actual_offset",$actual_offset);
		}
	}
	/**
	 * Retrieves profile if next/prev reaches the max cache value
	 * @return $profileid int profileid of the user
	 */
	private function next_prev_view_profileid($searchid,$Sort,$offset,$flag='',$stype='')
	{

		if(is_array($offset))
		{
						$profileId=  "----->>>".print_r($offset,true);
						$profileId.=  "----->>>".print_r($_POST,true);
						$profileId.=  "----->>>".print_r($_GET,true);
						$http_msg="::::---->>>".print_r($_SERVER,true);
						mail("reshu.rajput@gmail.com","lr2","$profileId: $http_msg");
						
		}

		//To be used in search to set from_viewprofile.
		$fromSymDetailed=1;
		global $sphinxJCACHE,$searchIndexJ,$j;
		$j=$this->j;

		if(!$Sort)
			$Sort='S';

		if($flag=='prev')
			$new_offset=$offset;
		else
			$new_offset=$offset+2;

		$SearchResultscacheObj = new SearchResultscache;
		if($stype==SearchTypesEnums::IOSFeatureProfile || $stype==SearchTypesEnums::JSMSFeatureProfile || $stype==SearchTypesEnums::FeatureProfile  || $stype==SearchTypesEnums::AAFeatureProfile)
			$next_pid = $SearchResultscacheObj->getProfile($searchid,$new_offset,'FP');
		else
			$next_pid = $SearchResultscacheObj->getProfile($searchid,$new_offset);
			
		if($next_pid === null)
		{
			$this->bIsSearchIdExpire = true;
		}	
		/*	
		$sphnix_cache=new NEWJS_SPHNIX_CACHE();
		if($stype=='W')
			$row=$sphnix_cache->getFP_CACHE($searchid);
		else
			$row=$sphnix_cache->getSR_CACHE($searchid,$sphinxJCACHE,$Sort);
			
		if(is_array($row))
		{
				$results_100cached=$row["RESULTS"];
				$temp_arr=explode(",",$results_100cached);
				//$temp_new_key=array_search($previousPid,$temp_arr);
				$next_pid=$temp_arr[$new_offset];
		}
		if(!$next_pid)
		{
			$searchIndexJ=$j=ceil(($offset+1)/10);
			
			include_once(sfConfig::get("sf_web_dir")."/profile/search.php");
			if($stype=='W')
				$row=$sphnix_cache->getFP_CACHE($searchid);
			else
				$row=$sphnix_cache->getSR_CACHE($searchid,$sphinxJCACHE,$Sort);
			if(is_array($row))
			{
				$results_100cached=$row["RESULTS"];
				$temp_arr=explode(",",$results_100cached);
				$next_pid=$temp_arr[$new_offset];
			}
		}
		*/
		return $next_pid;
	}
	
}
