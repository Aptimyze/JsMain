<?php
/**
 * AdvancedSearchAction
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class AdvancedSearchAction extends sfActions
{
	
	public function execute($request)
	{ 
		//print_r($request->getParameterHolder()->getAll());
		$params["request"] = $request;
		$searchChannelFactoryObj = new SearchChannelFactory();
		$searchChannelObj = $searchChannelFactoryObj->getChannel($params);

		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()=='')
		{
			$isLogout=1;
			$params["isLogout"]=1;
			$this->loggedIn=0;
		}
		else
		{
			$this->loggedIn=1;
                        $pid = $loggedInProfileObj->getPROFILEID();
                        JsMemcache::getInstance()->set("cachedLSMS$pid","");
                        JsMemcache::getInstance()->set("cachedLSMR$pid","");
		}
		if($request->getParameter("searchId"))
			$parameters["SEARCHID"] = $request->getParameter("searchId");
		$advanceSearchPopulateObj = new AdvanceSearchPopulate($parameters);
		$this->dataArray = $advanceSearchPopulateObj->generateDataArray();
		$this->searchSection = $advanceSearchPopulateObj->getSearchSection();
		$this->searchFeilds = $advanceSearchPopulateObj->getSearchFeilds();
		$this->casteDropDown = json_encode($this->dataArray["caste"],true);
		$this->setTemplate("JSPC/advancedSearch");
	}
}
