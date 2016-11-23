<?php
/**
 * MobTopSearchBandAction
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class MobSearchAction extends sfActions
{
	public function execute($request)
	{ 
		if($request->getParameter("searchBasedParam")=='justjoined')
			$request->setParameter("searchBasedParam","justJoinedMatches");

		$params["request"] = $request;
		$searchChannelFactoryObj = new SearchChannelFactory();
		$searchChannelObj = $searchChannelFactoryObj->getChannel($params);
		
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()==''){
                        $isLogout=1;
                        $params["isLogout"]=1;
		}
                else{
                        $this->loggedIn=1;
                }
                /**temp soln for logged-out case untill nitesh handles loggedout scenario*/
		if(($request->getParameter("justJoinedMatches")==1 || $request->getParameter("twowaymatch")==1 || $request->getParameter("reverseDpp")==1 || $request->getParameter("partnermatches")==1 || $request->getParameter("contactViewAttempts")==1 || $request->getParameter("verifiedMatches")==1 || $request->getParameter("lastSearchResults")==1  || in_array($request->getParameter("searchBasedParam"),array('shortlisted','visitors','justJoinedMatches','twowaymatch','reverseDpp','partnermatches','matchalerts','kundlialerts','contactViewAttempts','verifiedMatches','lastSearchResults')) || $request->getParameter("dashboard")==1) && $isLogout==1)
		        $this->forward("static","logoutPage");
                $this->szNavType = 'SR';
                
               
								
                if($request->getParameter("twowaymatch")==1){
			$this->searchBasedParam = 'twowaymatch';
                }
			
		/* capturing api */
		ob_start();
		$request->setParameter('useSfViewNone','1');
		$QuickSearchBand = $request->getParameter("QuickSearchBand");
		if(in_array($request->getParameter("searchBasedParam"),array('shortlisted','visitors')) ) //LATER- LOGGEDOUT
		{
			$this->ccListings=1;
			if($request->getParameter("currentPage")=='NaN')
                        {
				$http_msg=print_r($_SERVER,true);
                            	mail("lavesh.rawat@gmail.com","MobSearchAction - CC page","$http_msg");
                                $request->setParameter("currentPage",1);
                        }
                        if($request->getParameter("matchedOrAll"))
                            $this->matchedOrAll=$request->getParameter("matchedOrAll");
			$request->setParameter("infoTypeId",$request->getParameter("searchId"));
			$request->setParameter("pageNo",$request->getParameter("currentPage"));
            		$request->setParameter("ContactCenterDesktop",1);
			sfContext::getInstance()->getController()->getPresentationFor('inbox','performV2');
		}
		else
		{
			if($request->getParameter("searchBasedParam"))
			{
                	        $request->setParameter($request->getParameter("searchBasedParam"),1);
			}
			elseif($request->getParameter("dashboard"))
			{
                        	$request->setParameter("matchalerts",1);
			}	
			sfContext::getInstance()->getController()->getPresentationFor('search','performV1');
		}
		$jsonResponse = ob_get_contents(); //we can also get output from above command.
		//print_r($jsonResponse);die;
		ob_end_clean();
		/* capturing api */

		$ResponseArr = json_decode($jsonResponse,true);
		if(($ResponseArr["searchBasedParam"]=="justJoinedMatches" || $ResponseArr["searchBasedParam"]=="twowaymatch" || $ResponseArr["searchBasedParam"]=="reverseDpp" || $ResponseArr["searchBasedParam"]=="partnermatches" || $ResponseArr["searchBasedParam"]=="contactViewAttempts" || $ResponseArr["searchBasedParam"]=="verifiedMatches" || $ResponseArr["searchBasedParam"]=="lastSearchResults") && $isLogout==1)
		        $this->forward("static","logoutPage");
                
		$params["actionObject"] = $this;
		$params["ResponseArr"]= $ResponseArr;
		$params["loggedInProfileObj"]=$loggedInProfileObj;
		
		$searchChannelObj->setVariables($params);
                $this->stypeName=$ResponseArr["stype"];
                $this->heading = $ResponseArr["result_count"];
		
		//Setting cookie for search id
		$searchId = $ResponseArr["searchid"];
		setcookie("JSSearchId", $searchId, time() + 86400, "/"); // 86400 = 1 day
		if(($request->hasParameter('kundlialerts') && $request->getParameter("kundlialerts") == 1) || $ResponseArr["searchBasedParam"]=="kundlialerts" ){
					
									$this->szNavType = $this->stypeName;
		}
	
                //Navigator
                $currentPage=1;                
                $this->NAVIGATOR = navigation($this->szNavType,$searchId.":".$currentPage,'','Symfony');
                
		if(is_array($ResponseArr) && $ResponseArr["no_of_results"]==0)
                        $this->dontShowSorting=1;
		if(!$ResponseArr["pageTitle"] || $dashboard==1)
			$ResponseArr["pageTitle"] = "Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com";
		$this->setTitle($ResponseArr["pageTitle"]);
		$this->firstResponse = $jsonResponse;
        	$this->staticSearchData= json_encode($this->staticSearchData);
	}

        private function setTitle($title)
        {
                $response=sfContext::getInstance()->getResponse();
                $response->setTitle($title);
        }
}
