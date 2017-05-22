<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class printAction extends sfAction
{
	public $data;
	public $jprofile_result;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter;
	public $contact_status;
	public $contact_status_new;
	public $filter_prof;
	public $spammer;
	public $paid;

	public $contact_matchalert;
	public $visitoralert;
	public $contact_limit_message;
	public $contact_limit_reached;
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
		
		//Contains login credentials
		$data=$this->loginData=$data=$request->getAttribute("loginData");
	
		//Contains logined Profile information;
		$this->loginProfile=LoggedInProfile::getInstance();
		$this->profile=Profile::getInstance();
		//Assinging smarty variable
		$this->smarty=$smarty;
		
		//Check for all error message, will forward only if any error
		ProfileCommon::checkViewed($this,"fromPrint");
		
		//Below this shows that viewed profile is always present
		//This function will be called immediately after checkViewed
		$this->commonVariables();
		
		
		//Showing contact engine
		$this->showContactEngine();
		
		//Set labels[profile/partner] on page.
		$this->setUserData();
		
		//Color the label that matching dpp of each other.
		$this->setColorCode();
		//Will be enabled only after lavesh is done with function creation.
		$this->setPhoto();
		
		$this->commonAssign();
		//Assinging smarty variables to this variable to access them on template
		ProfileCommon::old_smarty_assign($this);
		
		//This is required, since only 1 css is required in print profile with no screen variable of link
		//Should be declared just below old_smarty_assign function
		$this->setCssRequired();
		
		$this->setTitle();
		//Free memory.
        
        //Check Horoscope
		$this->horoscopeAvailable();
		unset($this->smarty);
		
	
	}
	/**
	 * Update Photos count
	 * 
	 */
	private function setPhoto()
	{
		$login=0;
		
		if($this->loginProfile->getPROFILEID())
			$login=1;
		$return=ProfileCommon::getprofilePicnCnt($this->profile,$this->contact_status,$login);
		$this->PHOTO=$return[0];
		$this->ALBUM_CNT=$return[1];
		$this->stopAlbumView=$return[2];
	}
	/**
	 * Set the view_page_css css
	 *
	 */
	function setCssRequired()
	{
		$this->getRequest()->setAttribute('view_page_css',$this->view_page_css);
	}
	/**
	 * Handles common variables required by contact engine
	 */
	private function commonVariables()
	{
		$request=$this->getRequest();
		
		//Creating jprofile_result, required by contact engine function
		if($this->profile->getPROFILEID()!=null)
			$this->jprofile_result[viewed]=$this->profile->convertObjectToArray();
		if($this->loginProfile->getPROFILEID()!=null)	
			$this->jprofile_result[viewer]=$this->loginProfile->convertObjectToArray();
		
			
		if($this->loginProfile->getGENDER()==$this->profile->getGENDER())
			$this->SAMEGENDER=1;
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
			//pdf view
			$this->pdf=$this->getRequest()->getParameter("pdf");

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
			
			
		}
		if($this->loginProfile->getPROFILEID())
		{
			$this->PERSON_LOGGED_IN="1";
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
		ProfileCommon::setPageInformation($this,$this->profile);
	}
	private function setTitle()
	{
		$response=sfContext::getInstance()->getResponse();
		
		$response->setTitle("Print page");
	}
	/**
	 * Colors the label of detailed profile and desired partner profile section.
	 *  
	 */ 
	private function setColorCode()
	{
		if($this->loginProfile->getPROFILEID()!=null)
		{
			//Getting partner details of viewer
			$jpartnerObj=ProfileCommon::getDpp($this->loginProfile->getPROFILEID());
		
			//Getting loginned profile desired partner data and setting object as well.
			$this->loginProfile->setJpartner($jpartnerObj);
			//Green label for detailed profile section of viewed profile.
			if($jpartnerObj!=null)
			{
					$this->CODEOWN=JsCommon::colorCode($this->profile,$this->loginProfile->getJpartner(),$this->casteLabel,$this->sectLabel);
			}
			//Green label for desired partner profile section of viewed profile.
			if($this->profile->getJpartner()!=null)
			{
				$this->CODEDPP=JsCommon::colorCode($this->loginProfile,$this->profile->getJpartner(),$this->casteLabel,$this->sectLabel);
				
				
			}
			
		}	
	}
    /**
	 * Horoscope is uploaded by viewed user or not
	 */
	private function horoscopeAvailable()
	{
		if(check_astro_details($this->profile->getPROFILEID(),"Y"))
		{					
			if($this->profile->getSHOW_HOROSCOPE()=="N" || $this->profile->getSHOW_HOROSCOPE()==""||$this->profile->getSHOW_HOROSCOPE()=="D")
			{
				$this->HOROSCOPE="N";
				
				$this->HIDE_HORO=1;
			}			
			else 
			{
				
				$this->HIDE_HORO=0;				
				$this->HOROSCOPE="Y";
			}
		}
		else
		{
			$this->HOROSCOPE="N";
			$this->REQUESTHOROSOCOPE="Y";
			$this->HIDE_HORO=0;	
			$chkprofilechecksum = JSCOMMON::createChecksumForProfile($this->loginProfile->getPROFILEID());
			
			/*if($this->is_already_requested($this->profile->getPROFILEID(),$chkprofilechecksum))
				$this->REQUESTED=1;
			else
				$this->REQUESTED =0;*/

		}
	}
}
