<?php
/**
 * noprofile handles all the error message that are passed/forward by
 * other actions. It handles no profile, filter, contacted, hidden, 
 * deleted errors and shows appropriate message to user.
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class myprofilemobAction extends sfAction
{
	
	//~ public $smarty;
	
	public function execute($request)
	{ 
		if($request->getParameter('fromCALHoro') == 1)
			$this->fromCALHoro = 1;
		if($request->getParameter('fromCALphoto') == 1)
			$this->fromCALphoto = 1;

		$this->groupname = $request->getParameter("groupname");
		//Testing Variables:
		$request->setParameter("sectionFlag","all");
		
                if($request->getParameter("fromPhone") == "1"){
                  $this->fromPhoneVerify = $request->getParameter("fromPhone");  
                  $this->sourcename = $request->getParameter("sourcename");
                  
                }
		
		//Contains login credentials
		$this->loginData=$request->getAttribute("loginData");
		if($request->getParameter('check')==1)
		$this->checkalbum=1;
		//~ $this->isMobile=MobileCommon::isMobile("JS_MOBILE");

		//Viewer and Viewed profile ids
		$this->profile=Profile::getInstance();
		$this->loginProfile=LoggedInProfile::getInstance();
		if($this->loginProfile->getAGE()== "")
			$this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
		$pixelcodeObj = new PixelCodeHandler($this->groupname,'',$pageName="JSMS6",$this->loginProfile);
		$this->pixelcode = $pixelcodeObj->getPixelCode();
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobEditPageUrl);
		//Jpartner need to set for dpp
		
		//Action Templates Variables
		$this->GENDER = $this->loginProfile->getGENDER();
		$this->USERNAME = $this->loginProfile->getUSERNAME();
                $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
                $cnt_logic = $newjsMatchLogicObj->getPresentLogic($this->loginProfile->getPROFILEID(),MailerConfigVariables::$oldMatchAlertLogic);
                if($cnt_logic>0)
                        $this->toggleMatchalerts = "dpp";
                else
                        $this->toggleMatchalerts = "new";
		
		$this->output=$output;
		//print_r($this->output);die;
		//Your Info 
		//Content Right to profile pic
		$MobileCommonFunctions=new MobileCommonFunctions;
		$this->MyProfileYourInfo=$MobileCommonFunctions->getInfo($this->editArr[MyBasicInfo][YOURINFO][label_val],"yourinfo");
		// for education page
		if(array_key_exists($this->editArr[Education][EDU_LEVEL_NEW][value],FieldMap::getFieldLabel("degree_pg","",1)))
			$this->pgEducation=1;
		else
			$this->pgEducation=0;
		
		//Album PAge variables:
		//$this->username=$this->loginProfile->getUSERNAME();
		$this->privacy =$this->loginProfile->getPHOTO_DISPLAY();
		$this->upload = true;
		//$this->gender=$this->loginProfile->getGENDER();
		$picServiceObj = new PictureService($this->loginProfile);
				if($picServiceObj->getProfilePic())
				{ 
						if($picServiceObj->getProfilePic()->getProfilePic235Url())
						{
							$this->profilepicurl= $picServiceObj->getProfilePic()->getProfilePic235Url();
							$this->picturecheck=0;
						}
                        elseif($picServiceObj->getProfilePic()->getprofilePicUrl())
                        {
							$this->profilepicurl= $picServiceObj->getProfilePic()->getprofilePicUrl();
							$this->picturecheck=1;
						}
						else
						{
							$this->profilepicurl= $picServiceObj->getProfilePic()->getmainPicUrl();
							$this->picturecheck=0;
						}
                        $album = $picServiceObj->getAlbum();
				}
				
		$album = $picServiceObj->getAlbum();
		if($request->getParameter('selectFile')==1)
			$this->selectFileOrNot = $request->getParameter('selectFile');
		else
			$this->selectFileOrNot = 0;
		if(is_array($album))
			$this->alreadyPhotoCount = count($album);
		else
		$this->alreadyPhotoCount = 0;
		

		$sectionFlag = $request->getParameter("sectionFlag");
		
		$action=$request->getParameter("editaction");
		if(!$action)
			$this->Album();
			$this->selectTemplate = 1;
			//echo($this->sel);die;
    
		$horoscope = new Horoscope();  
		$this->horoExist = $horoscope->isHoroscopeExist($this->loginProfile);
		$this->setTemplate("_mobedit/myprofilemob");
    
	}
	private function Album()
	{
		
	}
}
