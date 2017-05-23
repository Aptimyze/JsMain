<?php
class page3Action extends sfAction {
    /** Executes Registration page 3
     *
     */
    public function execute($request) {
				$request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[3]);
      $this->forward('register','regPage');
        //Contains login credentials
				$this->loginData = $request->getAttribute("loginData");
			//	$this->loginData[PROFILEID]=2345555;
				if(!$this->loginData[PROFILEID])
					$this->forward("register","page1");
				
        //Jsb9 page load time tracking page3
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage3Url);
        $this->SITE_URL = sfConfig::get("app_site_url");
        $this->IS_FTO_LIVE = FTOLiveFlags::IS_FTO_LIVE;
		//Get Sugar data and assign default values on page3
		$record_id=$request->getParameter('record_id');
		//$record_id="beb4f6e1-09d0-c33b-5603-4f7d74a6ba5d";
		$reg_param=array();
		if($record_id){
			$reg_param=$this->getSugarLeadData($record_id);
		} 
        $this->form = new PageForm($reg_param, array("page" => 'DP3','request' => $request), '');
        $this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData[PROFILEID], "PROFILEID");
		$this->religion=$this->loginProfile->getRELIGION();
		$this->caste=$this->loginProfile->getCASTE();
		$this->age=$this->loginProfile->getAGE();
		$this->mtongue=$this->loginProfile->getMTONGUE();
		$this->country_res=$this->loginProfile->getCOUNTRY_RES();
		$this->groupname=$request->getParameter('groupname');
		$this->source=$request->getParameter('source');
		$this->out_sideIndia = $request->getParameter('outside_inda') ==="1"?"checked":"";
		$this->countryDefault = $request->getParameter('reg[native_country]');
		if($this->countryDefault =='' ||  !$this->countryDefault)
		{
			$this->countryDefault = 51;
		}
		
        //Fetch Religion of profile and set correct parital for this religion
		if($this->religion!=Religion::BAHAI && $this->religion != Religion::JEWISH && $this->religion!=Religion::OTHERS)  
			$this->partial = strtolower($this->loginProfile->getDecoratedReligion()) . "_fields";
		//TODO following code to be removed only done for testing
		//$rel=$request->getParameter('religion');
		//$this->partial=$rel."_fields";
		//$this->GENDER=$request->getParameter('gender');
		$this->GENDER=$this->loginProfile->getGENDER();
		if($this->groupname=="Tyroo_AMJ@120")
		{
                    //check for firing tyroo pixel
                    if(PixelCode::firePixelCheck($this->loginProfile))
                        $this->fireTyroo=1;
                    $this->username=$this->loginData[USERNAME];
		}
		//Get date of birth for displaying in horo iframe
		$birthdt=$this->loginProfile->getDTOFBIRTH();
		$birth_arr=explode('-',$birthdt);
		$this->YEAR_OF_BIRTH=$birth_arr[0];
		$this->MONTH_OF_BIRTH=$birth_arr[1];
		$this->DAY_OF_BIRTH=$birth_arr[2];
		$this->PROFILEID=$this->loginData[PROFILEID];
        if ($request->getParameter('submit_pg3')) {
					
						$this->CheckSkip($request);
            $this->form->bind($request->getParameter('reg'));
            if ($this->form->isValid()) {
                if ($name_of_user = $this->form->getValue('name_of_user')) {
                    $name_pdo = new NameOfUser();
                    $name_pdo->insertName($this->loginData[PROFILEID], $name_of_user); //CHECK THIS
                }
                if($this->form->getValue('familyinfo'))
					RegChannelTrack::insertPageChannel($this->loginData[PROFILEID],PageTypeTrack::_ABOUTFAMILY);
                $this->form->updateData($this->loginData["PROFILEID"]);
                //DPP Auto Suggestor implemenation :
                $dppObj=new DppAutoSuggest($this->loginProfile);
				$profileFieldArr=array("MANGLIK");
				$dppObj->insertJpartnerDPP($profileFieldArr);
				//End of DPP auto Suggestor
                //TODO add logout from sem registrations and 302 redirect to jeevasathi.com domain with checksum to login
                $this->ForwardOrRedirect($request);
                
                
            } else {
				RegistrationMisc::logServerSideValidationErrors("DP3",$this->form);
            }
        } else {
            $astro_pdo = ProfileAstro::getInstance();
            $astro_pdo->insertInAstroPullingDetails($this->loginData["PROFILEID"]);

            if (trim($request->getParameter('groupname'))) {
				$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);

				//Rocket fuel pixel for registration page3	
				if (PixelCode::RocketFuelValidation($this->groupname,$this->source,$this->loginProfile->getAGE(),$this->loginProfile->getGENDER(),$this->loginProfile->getMTONGUE(),$this->loginProfile->getRELIGION())) 
					{
						if($this->pixelcode)
							$this->pixelcodeRocketFuel = PixelCode::fetchRocketFuelCode("regPage3");
						else
							$this->pixelcode = PixelCode::fetchRocketFuelCode("regPage3");
					}
            }
		}
		//vizury code to be displayed only when ISEARCH cookie not set
		if(!isset($_COOKIE["ISEARCH"]))
			$this->ISEARCH_COOKIE_NOTSET=1;
    }
    function CheckSkip($request)
    {
			if($request->getParameter(skip_to_next_page_edu))
			{
				$this->ForwardOrRedirect($request);
			}
		}
		function ForwardOrRedirect($request)
		{
		        $sem_url=$_SERVER['HTTP_HOST'];
				$sem_url_1=explode(".",$sem_url);
				if($sem_url_1[0]=='www')
				{
					unset($sem_url_1[0]);
					$sem_url=implode('.',$sem_url_1);
				}
				$sem_url_arr=array("hindijeevansathi.in","jeevansathi.co.in","marathijeevansathi.in","punjabijeevansathi.com","punjabijeevansathi.in","hindujeevansathi.com");
				if(in_array($sem_url,$sem_url_arr))
					$sem=1;
			if($sem)
			{
				$groupname=$request->getParameter("groupname");
				include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
				$authObj=new Protect;
				$profChecksum=CommonFunction::createChecksumForProfile($this->loginData[PROFILEID]);
				$authObj->RemoveLoginCookies();
				$echecksum=$authObj->js_encrypt($profChecksum);
				$url=sfConfig::get("app_site_url")."/register/page4?echecksum=$echecksum&checksum=$profChecksum&fromRegister=1&sem=1&groupname=$groupname";
				$this->redirect($url);
			}
			else
				$this->forward("register","page4");
			
		}
		function getSugarLeadData($record_id){
			$sugar_obj= new sugarcrm_leads();
            $lead_data=$sugar_obj->getLead_CstmDataById($record_id);
			$reg_param['gothra']=$lead_data['gothra_c'];
			$reg_param['manglik']=($lead_data['manglik_c'] == 'Y')?'M':$lead_data['manglik_c'];
			$reg_param['subcaste']=$lead_data['subcaste_c'];
			$reg_param['family_back']=$lead_data['father_occupation_c'];
			$reg_param['t_brother']=$lead_data['no_of_brothers_c'];
			$reg_param['t_sister']=$lead_data['no_of_sisters_c'];
			$reg_param['m_sister']=$lead_data['no_of_sisters_married_c'];
			$reg_param['m_brother']=$lead_data['no_of_brothers_married_c'];
			return $reg_param;
		}
}
