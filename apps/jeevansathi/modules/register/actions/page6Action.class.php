<?php
class page6Action extends sfAction
{
/** Executes Registration page 5
   * */
  public function execute($request)
  {
      $request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[6]);
      $this->forward('register','regPage');
	global $smarty, $data;
		//Jsb9 page load time tracking page6
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage6Url);
	  //Common variables:
		
		// CRM related checks and variables:
	// *********** TODO **************
	$this->crmback=$request->getParameter("crmback");
	$this->crmredirect=$request->getParameter("crmredirect");
	$this->cid=$request->getParameter("cid");
	$this->pid=$request->getParameter("pid");
	$SITE_URL = sfConfig::get("app_site_url");
	if($this->crmback=='admin' && $this->crmredirect!=1)
	{	/*added to SET Filters from backend*/
		$this->crmredirect=1;
		$protect_obj=new protect;
		$this->checksum=md5($this->pid)."i".$this->pid;	
		$this->echecksum=$protect_obj->js_encrypt($this->checksum);
		header("Location:$SITE_URL/register/page6?echecksum=".$this->echecksum."&checksum=".$this->checksum."&cid=".$this->cid."&crmredirect=1&company=".$request->getParameter("company"));die;
	}
	$this->SITE_URL=$SITE_URL;
	if($request->getParameter("crmredirect"))
	{
		$crmBackArr["admin"]='admin';
		$crmBackArr["cid"]=$request->getParameter("cid");
		$crmBackArr["company"]=$request->getParameter("company");
	}
		$this->REG_P6=$request->getParameter('REG_P6');
		$this->loginData=$request->getAttribute("loginData");
	    if(!$this->loginData[PROFILEID] && $this->REG_P6==1)
			$this->forward("register","page1");
		elseif(!$this->loginData[PROFILEID])
			$this->forward("static","logoutPage");
		$this->loginProfile=LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData[PROFILEID],"PROFILEID");
		
		$this->fromPage=$request->getParameter("fromPage");
		$this->cameFrom=$request->getParameter("cameFrom");
		if($request->getParameter('profilechecksum'))
		$this->profilechecksum=$request->getParameter('profilechecksum');
		else
		$this->profilechecksum = JSCOMMON::createChecksumForProfile($this->loginProfile->getPROFILEID());
		$this->REG_P6=$request->getParameter('REG_P6');
		$this->from_mail=$request->getParameter('from_mail');
		$this->IS_FTO_LIVE=FTOLiveFlags::IS_FTO_LIVE;
		
		//Page specific variables current filters values:
		$this->filters = new FILTERS($this->loginData[PROFILEID]);
		//$this->filters = new FILTERS(3809685);
		$this->filters->currentFilters();
		$this->filters->setDpp();
		$this->religion=$this->filters->getFilterReligion();
		$this->lage=$this->filters->getFilterLAge();
		$this->hage=$this->filters->getFilterHAge();
		$this->mstatus=$this->filters->getFilterMStatus();
		$this->caste=$this->filters->getFilterCaste();
		$this->country_res=$this->filters->getFilterCountry();
		$this->city=$this->filters->getFilterCity();
		$this->mtongue=$this->filters->getFilterMTongue();
		if($this->filters->getFilterIncome())
		$this->income=$this->filters->getFilterIncome();
		else
		$this->income="Rs. No Income,&nbsp;&nbsp;&nbsp;&nbsp;$ No Income";
		
		//Filters which are currently set:
		$filterFlagArr=$this->filters->getFilterArr();
		
		$this->Filterid=$filterFlagArr["FILTERID"];
		$this->religion_flag=$filterFlagArr["RELIGION"];
		$this->age_flag=$filterFlagArr["AGE"];
		$this->mstatus_flag=$filterFlagArr["MSTATUS"];
		$this->caste_flag=$filterFlagArr["CASTE"];
		$this->country_flag=$filterFlagArr["COUNTRY_RES"];
		$this->city_flag=$filterFlagArr["CITY_RES"];
		$this->mtongue_flag=$filterFlagArr["MTONGUE"];
		$this->income_flag=$filterFlagArr["INCOME"];
		
		//additional variables:
		$this->gli=$this->loginData["GENDER"];
		
	 if($this->REG_P6){
		if(!isset($_COOKIE["ISEARCH"]))
				$this->ISEARCH_COOKIE_NOTSET=1;
	}
	else
	$this->REG_P6=0;
	/*********NEED TO BE DISCUSSED *******************/
	
	if(!$this->REG_P6)
	{
	//include_once("sphinx_search_function.php");
	//savesearch_onsubheader($data["PROFILEID"]);
	$this->data=$this->loginProfile->getPROFILEID();
	
	//$this->manage_filter=1;
	//$this->REVAMP_LEFT_PANEL=$smarty->fetch("leftpanel_settings.htm"));
	$this->FOOT=1;//$smarty->fetch("footer.htm"));//Added for revamp
	$this->SUB_HEAD=1;//$smarty->fetch("sub_head.htm"));
	$this->head_tab="my jeevansathi";
	$this->REVAMP_HEAD=1;
	//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	}
	//$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));

	if($this->loginProfile->getSUBSCRIPTION()!='')
	{
		$sub=explode(",",$this->loginProfile->getSUBSCRIPTION());
		if(in_array("T",$sub))
		{
			//include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
			$assistedProductOnline=1;//variable needed to be send to filter class.
			$dbApDppFilterArchive= new AP_DPP_FILTER_ARCHIVE();
			$liveDPP=$dbApDppFilterArchive->fetchCurrentDPP($this->loginProfile->getPROFILEID());
			$APeditID=$liveDPP["DPP_ID"];

			$whrStr="AND ONLINE='Y' AND ROLE='ONLINE' AND CREATED_BY='ONLINE'";
			
			if($liveDPP["DPP_ID"])
				$whrStr.=" AND DPP_ID>'$liveDPP[DPP_ID]'";
			$currentdpp=$dbApDppFilterArchive->fetchCurrentDPP($this->loginProfile->getPROFILEID(),"",$whrStr);
			if(count($currentdpp))
			{
				$APeditID=$currentdpp["DPP_ID"];
				$this->apEditMsg==1;
			}
			else
			{
				$currentDPP=$liveDPP;
			}
			if($APeditID)
				$this->APeditID=$APeditID;

			if($this->cameFrom=="EDITDPP")
				$this->APShowMessage=1;
		}
	}
	
	
	//*****************************PENDING********************
	
	//From login page redirection and filter page redirection logic:
	$dbFilters= new ProfileFilter();
		if($this->fromPage=='loginDeclineRedirect' || $this->fromPage=='filter_redirect')
			{
				$whrStr=" and (HARDSOFT='Y' OR COUNT>3)";
				$result=$dbFilters->fetchFilterDetails($this->loginProfile->getPROFILEID(),$whrStr);
				if(is_array($result))
					$this->fromPage="";
			}		
			
			if($this->fromPage=='loginDeclineRedirect' ||$this->fromPage=='filter_redirect')
			{
					
				if($this->fromPage=='loginDeclineRedirect')
				{
					$whrStr="AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=COUNT+1, HARDSOFT='N'";
					
					$result=$dbFilters->updateFilters($this->loginProfile->getPROFILEID(),$whrStr);
					if(!$result)
					{		
						$whrStr="AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=1, HARDSOFT='N'";
						$result=$dbFilters->insertFilterEntry($this->loginProfile->getPROFILEID(),$whrStr);	
			
					}
				}
				$selectStr="COUNT";
				$result=$dbFilters->fetchFilterDetails($this->loginProfile->getPROFILEID(),"",$selectStr);
				
				
				if($result["COUNT"]>2)
				$this->dontSetFilter=0;
				else
				$this->dontSetFilter=1;
				$this->filter_redirect=1;
				$this->fromPage="filter_redirect";
				
				if($this->loginProfile->getRELIGION()=="1" ||$this->loginProfile->getRELIGION()=="2"||$this->loginProfile->getRELIGION()=="3")
				$this->religion_check=1;
			}
			
		
		if($this->reg_page6){
				$this->REG_P6=1;
		if(!isset($_COOKIE["ISEARCH"]))
				$this->ISEARCH_COOKIE_NOTSET=1;
			} 
		

        
		//************************* END OF PRE CONDITONS*********************
		
		//Submit Action :
	if($request->getParameter("Submit"))
	     {
			 if($request->getParameter("skipToFto")==1)
			 die();
			 
			 $requestArr["Submit"]=$request->getParameter("Submit");	
			 
			 if($request->getParameter("mstatus_filter"))
				$requestArr["mstatus_filter"]=$request->getParameter("mstatus_filter");
			 if($request->getParameter("age_filter"))
				$requestArr["age_filter"]=$request->getParameter("age_filter");
			 if($request->getParameter("city_res_filter"))
				$requestArr["city_res_filter"]=$request->getParameter("city_res_filter");
			 if($request->getParameter("country_res_filter"))
				$requestArr["country_res_filter"]=$request->getParameter("country_res_filter");
			 if($request->getParameter("mtongue_filter"))
				$requestArr["mtongue_filter"]=$request->getParameter("mtongue_filter");			 
			 if($request->getParameter("caste_filter"))
				$requestArr["caste_filter"]=$request->getParameter("caste_filter");			 
			 if($request->getParameter("income_filter"))
				$requestArr["income_filter"]=$request->getParameter("income_filter");
			 if($request->getParameter("religion_filter"))
				$requestArr["religion_filter"]=$request->getParameter("religion_filter");
			 if($request->getParameter("selectId"))
				$requestArr["selectId"]=$request->getParameter("selectId");	
			 if($request->getParameter("noFilter"))
				$requestArr["noFilter"]=$request->getParameter("noFilter");
			 if($request->getParameter("NOT_UPDATE_HARDSOFT"))
				$requestArr["NOT_UPDATE_HARDSOFT"]=$request->getParameter("NOT_UPDATE_HARDSOFT");
			 die($this->filters->submitFilters($requestArr,$crmBackArr,$assistedProductOnline));
		 }
	//if session log out few left condtions are :  
        //pending login error if normal page
        // 
      if($this->from_mail==1)
                $this->login_mes="Please login to see your filters";
	
  }
}
?>
