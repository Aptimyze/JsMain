<?php
/**
 * @package    Goa Chronicle Profiles API
 * @subpackage search
 * @author     Lavesh Rawat
 * @created 2014-09-12
 */
class goaChronicleAction extends sfActions
{ 
	public function executeGoaChronicle($request)
	{
                $paramArr["GENDER"] = $request->getParameter("gender");
                $paramArr["MTONGUE"] = '34'; //Konkani
                $paramArr["COUNTRY_RES"] = '51'; //India
                $page = $request->getParameter("page");
		$pageSize=10;
		//$sort = 'P';

		/** Validation check ***/
		if(!ValidationHandler::validateGender($paramArr["GENDER"]))
                        $errorString = 1;
		if(!is_numeric($page))
                        $errorString = 1;
		if($errorString)
                        ValidationHandler::getValidationHandler("",$errorString,'die');
		/** Validation check ***/

		$showColumns = array('DECORATED_HEIGHT','USERNAME','DECORATED_RELIGION','DECORATED_CASTE','DECORATED_MTONGUE','DECORATED_EDU_LEVEL_NEW','DECORATED_INCOME','DECORATED_OCCUPATION','AGE','DECORATED_CITY_RES','userLoginStatus','PHOTO','PROFILECHECKSUM');
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $SearchParametersObj = new SearchBasedOnParameters;
                $SearchParametersObj->getSearchCriteria($paramArr);
                $SearchParametersObj->setNoOfResults($pageSize);
                $SearchServiceObj = new SearchService;
		//$SearchServiceObj->setSearchSortLogic($SearchParametersObj,$loggedInProfileObj,"",$sort);
                $respObj = $SearchServiceObj->performSearch($SearchParametersObj,'','',$page);
                $SearchDisplayObj = new SearchDisplay($SearchParametersObj);
                $resultsArray = $SearchDisplayObj->searchPageTemplateInfo('',$loggedInProfileObj,$respObj,$this->searchId);
		$i=0;
		foreach($resultsArray as $arrayDetail)
		{
		
			foreach($arrayDetail as $kk=>$vv)
			{
				if(in_array($kk,$showColumns))
				{
					if($kk=='PROFILECHECKSUM')
						$resultArr[$i]['link'] = JsConstants::$siteUrl.'/profile/viewprofile.php/?profilechecksum='.$vv;
					else
						$resultArr[$i][$kk] = $vv;
				}
			}
			$i++;
		}	
		if($resultArr)
			print_r(json_encode($resultArr));
		die;
    	}
}
