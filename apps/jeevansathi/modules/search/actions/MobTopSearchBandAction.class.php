<?php
/**
 * MobTopSearchBandAction
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class MobTopSearchBandAction extends sfActions
{
	public function executeMobTopSearchBand($request)
	{				
		$this->noResultFound = $request->getParameter("noResultFound");
		$searchReferer = $_SERVER["HTTP_REFERER"];		
		$currentUri = $_SERVER['REQUEST_URI'];
		
		$this->stime= $request->getParameter("stime");
		$this->cookieTime ="";
		$this->docReferer= "";
		if(isset($_COOKIE["JSSearchRef"]))
		{
			$cookieValueArr = explode("||",$_COOKIE["JSSearchRef"]);
			$this->cookieTime = $cookieValueArr[1];
			$this->docReferer = $cookieValueArr[0];
			
		}	
		
		if($this->docReferer!="" && $this->stime==$this->cookieTime && $_COOKIE["jssf"])
                {
                        $this->redirect($this->docReferer);
                }
		if(strpos($currentUri,"random")==FALSE)
		{
			if(!strstr($searchReferer,"topSearchBand"))
				if(!strstr($searchReferer,"/search/perform"))
					setcookie("JSSearchRef", $searchReferer."||".$this->stime, time() + 360, "/");
		}
		
		/* capturing api */
		ob_start();
		sfContext::getInstance()->getController()->getPresentationFor('search','populateDefaultValuesV2');
		$populateDefaultValues = json_decode(ob_get_contents()); //we can also get output from above command.
		ob_end_clean();
		$this->loggedIn = false;
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$this->savedSearches = null;
		$this->maxSaveSearches=0;
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
		{
			$this->loggedIn = true;
			$profileMemcacheObj = new ProfileMemcacheService($loggedInProfileObj);
			$saveSearchCount=$profileMemcacheObj->get("SAVED_SEARCH");
			if($saveSearchCount && $saveSearchCount>0)
			{
			  ob_start();
			  $request->setParameter('useSfViewNone','1');
			  $request->setParameter('perform','listing');
                	  sfContext::getInstance()->getController()->getPresentationFor('search','saveSearchCallV1');
                	  $savedSearchesResponse = json_decode(ob_get_contents()); //we can also get output from above command.
		      	  ob_end_clean();
			  if($savedSearchesResponse->saveDetails && $savedSearchesResponse->saveDetails->details)
				$this->savedSearches = $savedSearchesResponse->saveDetails->details;
			  if(sizeOf($this->savedSearches)>=SearchConfig::$maxSaveSearchesAllowed)
				$this->maxSaveSearches=1;
			}

		}
		if($this->loggedIn)
                        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobTopSearchBandPageUrl);
                else
                        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobTopSearchBandPageLogOutUrl);
                        
                //die("y");
		$this->dropdowns = $this->getDropDownOptions($populateDefaultValues);
		$this->moredropdownArr = $this->getMoreDropDownOptions($populateDefaultValues);
		$this->gender = $populateDefaultValues->gender;
		$searchArr = "LAGE,HAGE,LHEIGHT,HHEIGHT,LINCOME,HINCOME,LOCATION,LOCATION_CITIES,RELIGION,MTONGUE,OCCUPATION,EDUCATION,MANGLIK";
		$this->searchFields = $searchArr;
		$this->havephoto = $populateDefaultValues->havephoto;
		$this->dispMore = '';
		$this->dispLess = 'dn';
		$this->setTemplate("mobile/MobTopSearchBand");
				
	}
        /**
         * This function creates the more options array for jsms site.
         * To add another more option create a data array at 1 index same as 0
         * @param type $defaultValues array of default values for fields
         * @return string
         */
        public function getMoreDropDownOptions($defaultValues){
                $dropDown = array();
                $dropDown[0]['showhide']['showLess'] = 'dn';
                $dropDown[0]['showhide']['showLess_label'] = 'Less Options - ';
                $dropDown[0]['showhide']['showMore'] = '';
                $dropDown[0]['showhide']['showMore_label'] = 'More Options + ';
                $dropDown[0]['ddData']["EDUCATION"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"education","dhide"=>"decide","dselect"=>"checkbox", "mylabel"=>"Education", "haveSearch"=>"1");
		$dropDown[0]['ddData']["EDUCATION"]["label"]=$defaultValues->education_label;
		$dropDown[0]['ddData']["EDUCATION"]["value"]=$defaultValues->education;
                $dropDown[0]['ddData']["EDUCATION"]["valueDependant"]["value"]=$defaultValues->education_label_dep;
                $dropDown[0]['ddData']["EDUCATION"]["valueDependant"]["data"]="";
                if($defaultValues->education != ''){
                  $dropDown[0]['showhide']['showLess'] = '';
                  $dropDown[0]['showhide']['showMore'] = 'dn';
                }
                $dropDown[0]['ddData']["OCCUPATION"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"occupation","dhide"=>"decide","dselect"=>"checkbox", "mylabel"=>"Occupation", "haveSearch"=>"1");
		$dropDown[0]['ddData']["OCCUPATION"]["label"]=$defaultValues->occupation_label;
		$dropDown[0]['ddData']["OCCUPATION"]["value"]= $defaultValues->occupation;
                $dropDown[0]['ddData']["OCCUPATION"]["valueDependant"]["value"]=$defaultValues->occupation_label_dep;
                $dropDown[0]['ddData']["OCCUPATION"]["valueDependant"]["data"]="";
                if($defaultValues->occupation != ''){
                  $dropDown[0]['showhide']['showLess'] = '';
                  $dropDown[0]['showhide']['showMore'] = 'dn';
                }
                $dropDown[0]['ddData']["MANGLIK"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"manglik","dhide"=>"decide","dselect"=>"checkbox", "mylabel"=>"Manglik", "haveSearch"=>"0");
		$dropDown[0]['ddData']["MANGLIK"]["label"]=$defaultValues->manglik_label;
		$dropDown[0]['ddData']["MANGLIK"]["value"]= $defaultValues->manglik;
                $dropDown[0]['ddData']["MANGLIK"]["valueDependant"]["value"]=$defaultValues->manglik_label_dep;
                $dropDown[0]['ddData']["MANGLIK"]["valueDependant"]["data"]="";
                if($defaultValues->manglik != ''){
                  $dropDown[0]['showhide']['showLess'] = '';
                  $dropDown[0]['showhide']['showMore'] = 'dn';
                }
		return $dropDown;
        }
	public function getDropDownOptions($defaultValues)
	{
		$dropDown = array();
		$dropDown["LAGE"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"lage","dhide"=>"single","dselect"=>"radio","mylabel"=>"Min Age","haveSearch"=>"0");
		$dropDown["LAGE"]["label"]=$defaultValues->lage_label;
		$dropDown["LAGE"]["value"]= $defaultValues->lage;
		$dropDown["HAGE"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"hage","dhide"=>"single","dselect"=>"radio", "mylabel"=>"Max Age", "haveSearch"=>"0");
		$dropDown["HAGE"]["label"]=$defaultValues->hage_label;
		$dropDown["HAGE"]["value"]= $defaultValues->hage;
		
		$dropDown["LHEIGHT"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"lheight","dhide"=>"single","dselect"=>"radio","mylabel"=>"Min Height","haveSearch"=>"0");
		$dropDown["LHEIGHT"]["label"]=$defaultValues->lheight_label;
		$dropDown["LHEIGHT"]["value"]= $defaultValues->lheight;
		$dropDown["HHEIGHT"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"hheight","dhide"=>"single","dselect"=>"radio", "mylabel"=>"Max Height", "haveSearch"=>"0");
		$dropDown["HHEIGHT"]["label"]= $defaultValues->hheight_label;
		$dropDown["HHEIGHT"]["value"]= $defaultValues->hheight;
		
		$dropDown["RELIGION"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"religion","dhide"=>"decide","dselect"=>"checkbox","dependant"=>"caste", "mylabel"=>"Religion", "haveSearch"=>"0");
		$dropDown["RELIGION"]["label"]=$defaultValues->religion_label;
		$dropDown["RELIGION"]["value"]= $defaultValues->religion;
		$dropDown["RELIGION"]["valueDependant"]["value"] =$defaultValues->caste_label;//'{"1":"20,25","2":"151"}';//;[{"1":"20,25"},{"2":"151"}]
		$dropDown["RELIGION"]["valueDependant"]["data"]=$defaultValues->caste;
		
		$dropDown["MTONGUE"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"mtongue","dhide"=>"mutiple","dselect"=>"checkbox", "mylabel"=>"Mother Tongue", "haveSearch"=>"1");
		$dropDown["MTONGUE"]["label"]=$defaultValues->mtongue_label;
		$dropDown["MTONGUE"]["value"]= $defaultValues->mtongue;
		$dropDown["MTONGUE"]["valueDependant"]["value"]=$defaultValues->mtongue_label_dep;
		$dropDown["MTONGUE"]["valueDependant"]["data"]="";
		
		$dropDown["LOCATION"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"location","dhide"=>"decide","dselect"=>"checkbox", "mylabel"=>"Country", "haveSearch"=>"1");
		$dropDown["LOCATION"]["label"]=$defaultValues->location_label;
		$dropDown["LOCATION"]["value"]= $defaultValues->location;
		$dropDown["LOCATION"]["valueDependant"]["value"]=$defaultValues->location_label_dep;
		$dropDown["LOCATION"]["valueDependant"]["data"]="";
		
                $dropDown["LOCATION_CITIES"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"location_cities","dhide"=>"decide","dselect"=>"checkbox", "mylabel"=>"State/City", "haveSearch"=>"1");
		$dropDown["LOCATION_CITIES"]["label"]=$defaultValues->location_cities_label;
		$dropDown["LOCATION_CITIES"]["value"]= $defaultValues->location_cities;
		$dropDown["LOCATION_CITIES"]["valueDependant"]["value"]=$defaultValues->location_cities_label_dep;
		$dropDown["LOCATION_CITIES"]["valueDependant"]["data"]="";
                
		$dropDown["LINCOME"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"lincome","dhide"=>"single","dselect"=>"radio","mylabel"=>"Min Income","haveSearch"=>"0");
		$dropDown["LINCOME"]["label"]=$defaultValues->lincome_label;
		$dropDown["LINCOME"]["value"]= $defaultValues->lincome;
		$dropDown["HINCOME"]["dd"]=Array("dropdownmenu"=>1,"dmove"=>"right","dshow"=>"hincome","dhide"=>"single","dselect"=>"radio", "mylabel"=>"Max Income", "haveSearch"=>"0");
		$dropDown["HINCOME"]["label"]=$defaultValues->hincome_label;
		$dropDown["HINCOME"]["value"]= $defaultValues->hincome;
                //print_r($dropDown);die;
		return $dropDown;
		
	}
}
