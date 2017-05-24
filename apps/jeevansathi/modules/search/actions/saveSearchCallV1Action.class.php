<?php

//This class performs the save search functioning 
class saveSearchCallV1Action extends sfActions {

	public function execute($request) {
			//logout case handling
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			if($loggedInProfileObj->getPROFILEID()!='')
			{
				if($loggedInProfileObj->getAGE()=="")
					$loggedInProfileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
			}
			else{
				$this->forward("static", "logoutPage");
			}
	
			$perform = $request->getParameter("perform");      //perform param provide the action to be performed
			$dataType = $request->getParameter("dataType");      //dataType param provide datatype to be returned for saveSearch data
			$inputValidateObj = ValidateInputFactory::getModuleObject('search');
			$respObj = ApiResponseHandler::getInstance();		
			$inputValidateObj->validateSaveSearch($perform,$request->getParameter("searchId"));
			$resp = $inputValidateObj->getResponse();
			if($resp["statusCode"] == ResponseHandlerConfig::$SEARCH_SAVED["statusCode"])
			{
				if($perform!="listing")
					$profileMemcacheObj = new ProfileMemcacheService($loggedInProfileObj);
				if ($perform == "delete") 
				{
					$SearchID = $request->getParameter("searchId");
					$objRemoveSaveSearch = new UserSavedSearches($loggedInProfileObj);
					if($rowCount = $objRemoveSaveSearch->deleteRecord($SearchID))	
					{
						$saveDetails["errorMsg"] = null;
						$saveDetails["successMsg"] = SaveSearchMsgEnum::$Successdelete;
						$profileMemcacheObj->update("SAVED_SEARCH",-$rowCount);
						//$profileMemcacheObj->updateMemcache();
						$profileMemcacheObj->clearInstance();
						$key=$loggedInProfileObj->getPROFILEID()."SAVESEARCH";
						JsMemcache::getInstance()->set($key,"");
					}
					else
					{
						//echo("ddd");die;
						$saveDetails["errorMsg"] = SaveSearchMsgEnum::$ID_Error;
						$saveDetails["successMsg"] = null;
					}
				}
				elseif ($perform == "listing") 
				{
					$userSavedSearchesObj = new UserSavedSearches($loggedInProfileObj);
					$savedSearchesData = $userSavedSearchesObj->getSavedSearches('',$getAllData=true);//get the searches saved by user
					if(is_array($savedSearchesData))
					{
						$searchEngine="solr";
						foreach($savedSearchesData as $k=>$v) 
						{
							$breadCrumbObj = new BreadCrumb;
							$savedSearches[$k]['data'] = $breadCrumbObj->getSearchParametersLabels($v,$searchEngine);
							$savedSearches[$k]['ID']=$v['ID'];
							$savedSearches[$k]['SEARCH_NAME']=str_replace(">", "", str_replace("<", "", $v['SEARCH_NAME']));
							if($dataType!="A")
							{
								$savedSearches[$k]['dataString']=implode(" | ",$savedSearches[$k]['data']);
								unset($savedSearches[$k]['data']);
							}
							unset($breadCrumbObj);
						}
					}
					$saveDetails["details"] = $savedSearches;
          $saveDetails["maxCount"] = SearchConfig::$maxSaveSearchesAllowed;
					$saveDetails["errorMsg"] = null;
				}
				elseif ($perform == "savesearch") {
					$count=$profileMemcacheObj->get("SAVED_SEARCH");
					if($count<SearchConfig::$maxSaveSearchesAllowed)
					{
						$saveDetailsObj = new UserSavedSearches($loggedInProfileObj);
						$saveDetails = $saveDetailsObj->SaveSearchbyid($request,$loggedInProfileObj);
						if($saveDetails["successMsg"])
						{
							$profileMemcacheObj->update("SAVED_SEARCH",1);
							//$profileMemcacheObj->updateMemcache();
							$profileMemcacheObj->clearInstance();
							$key=$loggedInProfileObj->getPROFILEID()."SAVESEARCH";
							JsMemcache::getInstance()->set($key,"");
						}else{
              $resp = ResponseHandlerConfig::$SEARCH_SAVED_ALREADY_EXISTS;
            }
					}else{
            $resp = ResponseHandlerConfig::$SEARCH_SAVED_MAX_REACHED;
          }
				}
				elseif($perform=="count"){
					$saveDetails['count']=$profileMemcacheObj->get("SAVED_SEARCH");
				}
				$outputArr["saveDetails"] = $saveDetails;
			}

			$respObj->setHttpArray($resp);
			$respObj->setResponseBody($outputArr);
			$respObj->generateResponse();
                        return sfView::NONE;
                        die;
	}
}
?>
