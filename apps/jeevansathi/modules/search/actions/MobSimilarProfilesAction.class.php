<?php
/**
 * MobSimilarProfilesAction - This class is used to show ECP similar profile recommendations
 *
 * @package    - View Similar Profiles 
 * @subpackage - Search
 * @author     - Akash Kumar
 */
class MobSimilarProfilesAction extends sfActions
{
        /**
	  * This function is to be called to get and display similar profile listing
	  * @param - $request array 
	**/
	public function execute($request)
	{
		
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                if(($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()=='') || !$request->getParameter("profilechecksum"))
                        $isLogout=1;
                
                //Get profile of whos similar to be found
		$this->viewedProfilechecksum=$request->getParameter("profilechecksum");
		$viewedProfileID = JsCommon::getProfileFromChecksum($this->viewedProfilechecksum);
                if($viewedProfileID != 0){
                        $viewedProfileObj=new Profile();
                        $viewedProfileObj->getDetail($viewedProfileID,"PROFILEID");
                }
                //Get refferer to process
		$referUrl = $_SERVER["HTTP_REFERER"];
		$query_str = parse_url($referUrl, PHP_URL_QUERY);
		parse_str($query_str, $query_params);
		
                //Whether and what to show as successfull message
		if($request->getParameter("fromProfilePage") && $query_params["profilechecksum"]==$this->viewedProfilechecksum){
			$this->InterestSentMessage=$request->getParameter("fromProfilePage");
                        if($viewedProfileID != 0 && $viewedProfileObj && $viewedProfileObj->getPROFILEID() != ""){
                                $this->InterestSentToUsername=$viewedProfileObj->getUSERNAME();
                        }else{
                                $this->InterestSentToUsername = "this profile";
                        }
		}
		else
			$this->InterestSentMessage=0;
		/* capturing api */
		ob_start();
		$request->setParameter('useSfViewNone','1');
		$QuickSearchBand = $request->getParameter("QuickSearchBand");
		$request->setParameter("actionName","similarprofile");
		$request->setParameter("profilechecksum",$this->viewedProfilechecksum);
		sfContext::getInstance()->getController()->getPresentationFor('search','ViewSimilarProfilesV1');
		$jsonResponse = ob_get_contents(); //we can also get output from above command.
		ob_end_clean();
		/* capturing api */
                
                $this->firstResponse=$jsonResponse;
		$ResponseArr = json_decode($jsonResponse,true);
                /*$dateHourToAppend = date('m-d', time())."__".(date('H')-date('H')%3)."-".(date('H')+3-date('H')%3);
                $noOfResultsToStore = min($ResponseArr["no_of_results"],25);
                JsMemcache::getInstance()->hIncrBy("ECP_SIMILAR_PROFILES_COUNT_".MobileCommon::getChannel(),$dateHourToAppend."__".$noOfResultsToStore,1);*/
		 if($ResponseArr["no_of_results"]==0 && $request->getParameter("fromViewSimilarActionMobile")){
                        $url=JsConstants::$siteUrl.'/myjs/jsmsPerform';
                        header('Location: '.$url);die;
                }
		if($request->getParameter("fromViewSimilarActionMobile"))
                        $this->historyBackStop=1;
                else
                        $this->historyBackStop=0;

		if($isLogout==1)
		        $this->forward("static","logoutPage");

                $this->stypeName=$ResponseArr["stype"];
                $this->heading = $ResponseArr["result_count"];

                //Different options to be shown
		$this->dontShowSorting=1;
		$this->dontShowHam=1;
		$this->showClose=1;

		if(!$ResponseArr["pageTitle"])
			$ResponseArr["pageTitle"] = "Similar profile - Jeevansathi.com";
		$this->setTitle($ResponseArr["pageTitle"]);

		$this->noresultmessage = $ResponseArr["noresultmessage"];
		$this->_SEARCH_RESULTS_PER_PAGE = viewSimilarConfig::$suggAlgoNoOfResults_Mobile;;
		$this->setTemplate("mobile/MobSimilarProfiles");
	    $navObj = new Navigator();
		$this->NAVIGATOR = $navObj->navigation('JVS','profilechecksum__'.$this->viewedProfilechecksum,'','Symfony');
        $this->SPA_CE = ($request->getParameter("fromSPA_CE")==1) ? 'Y' : 'N';
        $this->BREADCRUMB = $request->getParameter("fromSPA_CE")==1 ? "" : $navObj->BREADCRUMB;//; added by Palash so that it doesn't run and gets blank only for SPA site
        $this->BREADCRUMB = str_replace('"','\'',$this->BREADCRUMB);

	}
        /**
	  * This function is to be called to set title of page
	  * @param - $title - title of the page to be set
	**/
        private function setTitle($title)
        {
                $response=sfContext::getInstance()->getResponse();
                $response->setTitle($title);
        }
}
