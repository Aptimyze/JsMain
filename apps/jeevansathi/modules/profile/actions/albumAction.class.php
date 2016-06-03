<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class albumAction extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function preExecute()
	{
		$this->start_tm=microtime(true);
		
	}
	public function execute($request)
	{
		global $smarty,$data;
		MobileCommon::forwardmobilesite($this);
		
		//Contains login credentials
		$this->loginData=$data=$request->getAttribute("loginData");
		//Contains logined Profile information;
		$this->loginProfile=LoggedInProfile::getInstance();
		$this->profile=Profile::getInstance();
		//if no stype is dr.
		if(!$request->getParameter("stype"))
			$request->setParameter("stype",40);
		//Assinging smarty variable
		$this->smarty=$smarty;

		//To show next/prev if coming from search, contact page.
		$this->showNextPrev();
		if(MobileCommon::isDesktop())
		{
			if($username = $this->profile->getUSERNAME())
				$this->redirect("/profile/viewprofile.php?username=".$username);
			else
				$this->redirect("/");
		}
		
		//To set Breadcrumb on page.
		$this->setNavigation();
		
		
		//Check for all error message, will forward only if any error
		ProfileCommon::checkViewed($this,"fromAlbum");
		
		//Set labels[profile/partner] on page.
		$this->setUserData();
		
		$this->commonAssign();
		//response tracking
		$JSTrackingObj = new JSResponseTracking();
		$this->responseTracking = $JSTrackingObj->getProfilePageTracking($request);
		
		$this->viewAllPhotos();
		
		//Check if contact limit reached.
		ProfileCommon::contactLimitReached($this);
		
		$this->setContactEngine();
		
		$this->smartyAssign();
		
		//Assinging smarty variables to this variable to access them on template
		ProfileCommon::old_smarty_assign($this);
		
		//Free memory.
		unset($this->smarty);
		$this->TopUsername=ProfileCommon::getTopUsername($this->profile->getUSERNAME());
		
		//Set last login date of viewed user on template
		$this->setLastLogin();

		$this->setTitle();		
		
	}
	/**
	 * sets title of page
	 */
	function setTitle()
	{
		$response=sfContext::getInstance()->getResponse();
		
		$response->setTitle("Social Profile - ".$this->profile->getUSERNAME()." - Jeevansathi.com");
		
		$response->addMeta('description', "View photos of ".$this->profile->getUSERNAME().". Know more about hobbies, interest and complete social profile of ".$this->profile->getUSERNAME()."");
	}
	/**
	 * Sets contact engine 
	 */
	function setContactEngine()
	{
		$this->albumPage=1;
		$this->CALL_ACCESS=getCallNowSetting($this->profile->getPROFILEID());

		//Checks if number verification is show or not
		if(checkPhoneVerificationLayerCondition($this->loginData[PROFILEID]))
	        {
                	$this->PH_UNVERIFIED_STATUS=1;
        	}
		ProfileCommon::addIntroCall($this->contact_status,$this);
		if($this->contact_status=="I" )
		{
			
		}
		if($this->contact_status=="RI")
		{
			
		}
		if($this->contact_status=="RA")
		{
			
		}
		if($this->contact_status=="A")
		{
			
		}
		if($this->contact_status=="D")
		{
			
		}
		if($this->contact_status=="C")
		{
			
		}
	}
	/**
	 * Breadcrump only if viewed profile exist.
	 */
	private function setNavigation()
	{
		if($this->profile!=null)
			$navigation_link=navigation("DP","",$this->profile->getUSERNAME());		
	}
		
	/**
	 * Fetch which profile to show when Next/Previous link is clicked by users
	 * Currently Next/Previous option is only available if coming from detailed/contact page.
	 */
	private function showNextPrev()
	{
		
		ProfileCommon::showNextPrev($this);
		
	}	
	/** 
	 * Sets last login date in required format of viewed profile.
	 */
	private function setLastLogin()
	{
		$this->OnlineMes=ProfileCommon::getLastLoginFormat($this->profile->getLAST_LOGIN_DT());
		
	}
	/**
	 * Views all photos
	 */
	function viewAllPhotos()
	{
		$viewAllPhotosObj = new ViewAllPhotos($this->profile);
		
		$outputArr = $viewAllPhotosObj->setCommonVariables("none",$this->contact_status);
		
		//If photo doesn't exist
		if(!$outputArr->userPics)
		{
			$this->request->setAttribute("ERROR",9);
			$this->forward("profile","noprofile");
		}
		
		$this->keywords = 		$outputArr->keywords;
		$this->userPics = 		$outputArr->userPics;
		$this->countOfPics =		$outputArr->countOfPics;      				//Count no of pics
		$this->allThumbnailPhotos = 	$outputArr->allThumbnailPhotos;
		$this->mainPicArr=$outputArr->mainPicArr;
		$this->tempCount = 		$outputArr->tempCount;
		$this->allPicIds = 		$outputArr->allPicIds;
		$this->titleArr = 		$outputArr->titleArr;
		
		if(is_array($outputArr->keywordArrStr))
		{
			foreach($outputArr->keywordArrStr as $key=>$val)
			{
				$keywordArrStr[$key]=$this->get_label_keyword($val);
			} 
		}
		$this->keywordArrStr=$keywordArrStr;
		
		$this->picIdArr = 		$outputArr->picIdArr;
		$this->picType = 		$outputArr->picType;
		$this->sliderNo = 		$outputArr->sliderNo;
		$this->currentPicIndex = 	$outputArr->currentPicIndex;                   	//Set Picture Number for the Template
		$this->frontPicUrl = 		$outputArr->frontPicUrl;           		//Set Main Pic Url to be displayed
		$this->currentPicId = 		$outputArr->currentPicId;			//Set Current Pic Id to be stored in the hidden input in template
		$this->currentPic_Type = 	$outputArr->currentPic_Type;			//Set Current Pic Id to be stored in the hidden input in template
		$this->currentPicKeywords = 	$outputArr->currentPicKeywords;			//Current Pic Keywords
		$this->dropdownKeywordsLabel = 	$outputArr->dropdownKeywordsLabel;		//Keywords list display in the disabled dropdown
		$this->widthOfMainPic =         $outputArr->widthOfMainPic;
                $this->heightOfMainPic =        $outputArr->heightOfMainPic;

		// MIS logging by Reshu for Album view start
		$MISviewAlbum= new AlbumViewLog(); 
		$MISviewAlbum->misViewAlbumInsert($this->profile->getPROFILEID(),$_SERVER[HTTP_REFERER],$outputArr->countOfPics);
		// MIS logging end
	}
	/**
	 * Return labels of photo keywords
	 *
	 */
	function get_label_keyword($val)
	{
		$str="";
		if($this->keywords)
		{
			if($val!="")
			{
				$arr=explode(",",$val);
				foreach($arr as $key=>$value)
				{
					$str.=", ".$this->keywords[$value-1];
				}
				$str=ltrim($str,",");
			}
		}
		return $str;
	}
	/**
	 * Handles common variables that need by the template
	 * 
	 */
	private function commonAssign()
	{
		//If viewed profile exist.
		if($this->profile->getPROFILEID())
		{
			$this->sim_contact=$this->profile->getPROFILEID();
			$this->viewed_gender=$this->profile->getGENDER();
			$this->PROFILECHATID=$this->profile->getPROFILEID();
			$this->PROFILENAME=$this->profile->getUSERNAME();
			$this->GENDER=$this->profile->getGENDER();
			if($this->GENDER=='M')
					$this->HIMHER="Him";
			else
					$this->HIMHER="Her";
					
			$this->other_profileid=$this->profile->getPROFILEID();
			$this->PROFILECHECKSUM_NEW=$this->PROFILECHECKSUM=JSCOMMON::createChecksumForProfile($this->profile->getPROFILEID());
			
			//Used for setting header
			$this->FROM_PROFILEPAGE=0;	
			
		}
		if($this->loginProfile->getPROFILEID())
		{
			$this->PERSON_LOGGED_IN="1";
			if(sfContext::getInstance()->getRequest()->getParameter("contact_album_layer"))
				$this->autocontactlayer=1;
		}
		
			
	}
	/**
	 * Handles the display of contact engine[Express/contact/call now tab]
	 */
	private function showContactEngine()
	{
		
		symCalling($this->jprofile_result,$this->loginData,$this->contact_status_new,"",$this->spammer,$this->filter_prof,$this->contact_limit_reached,$this->SAMEGENDER,$this->contact_limit_message,$this->smarty);
	}
		/**
	 * Sets the label of detailed profile and desired partner profile section.
	 */
	private function setUserData()
	{
		
		$profileSection=new ProfileSections($this->profile);
		$hobArray=$profileSection->getHobbies();
		
		$this->hobArray=array("My Hobbies"=>$hobArray[Hobbies],
		"Favourite Books"=>$hobArray["Favourite  Books"],
		"Favourite Movies"=>$hobArray["Favourite  movies"],
		"Favourite TV Shows"=>$hobArray["Favourite TV Shows"],
		"Favourite Food"=>$hobArray["Favourite Food"],
		"Food I cook"=>$hobArray["Food I Cook"],
		"Favourite Music"=>$hobArray["Favourite  Music"],
		"Favourite Vacation Destination"=>$hobArray["Favourite Vacation Destination"],
		"Favourite Sports"=>$hobArray["Sports/ Fitness"]
	);
		$this->hobArray=ProfileCommon::removeBlank($this->hobArray);
		
		
	}
/**
	 * Update class variable profile if profileid is passed
	 * @param $profileid Integer profileid of user
	 * @throws jsException if blank $profileid is passed
	 */
	public function setViewed($profileid)
	{
		if($profileid)
		{
			$this->profile=Profile::getInstance();
			$this->profile->getDetail($profileid,"PROFILEID","","RAW");
		}
		else
				throw new jsException("Please pass on the profileid");
		
	}
	/**
	 * Handling smarty variables that are used by previous code,
	 * This function is made to accumulate the smarty code scattered
	 * in old script.
	 */
	private function smartyAssign()
	{
		ProfileCommon::smartyAssign($this,"album");
	}
}
