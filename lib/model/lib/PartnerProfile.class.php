<?php
/**
 * @brief This class list the possible saved search paramters based on user id.
 * @author Lavesh Rawat
 * @created 2012-08-21
 */
class PartnerProfile extends SearchParamters
{
	private $isDppExist;
	public function getIsDppExist(){return $this->isDppExist;}
	public static $addNonFilledValuesAttributeArr = array('DIET','SMOKE','HIV','DRINK');

        public function __construct($loggedInProfileObj)
        {
		parent::__construct();
                $this->possibleSearchParamters = SearchConfig::$possibleSearchParamters;
		$this->loggedInProfileObj = $loggedInProfileObj;
               	$this->pid =  $this->loggedInProfileObj->getPROFILEID();
		if(!$this->pid)
		{
			$context = sfContext::getInstance();
			$context->getController()->forward("static", "logoutPage"); //Logout page
			throw new sfStopException();
		}
        }

	public function getDppForMultipleProfiles($profileIdArr,$dbName,$fieldsStr="*")
	{
		$jpartnerObj = new newjs_JPARTNER($dbName);
		$dppArr = $jpartnerObj->getDataForMultipleProfiles($profileIdArr,$fieldsStr.", PROFILEID");
		return $dppArr;
	}


	/*
	* Sets PartnerProfileObject(SearchParamtersObj) corresponding to id(primary key of teh JPARTNER table.
	* Must be run if user is logged-in else timed-out page.
	* @param (optional) only sent in case of forward search for assisted_product
	* @return array containing dpp info.
	*/
	public function getDppCriteria($param="",$source="",$getFromCache=0)
	{
		if($param)
		{
			$apObj = new ASSISTED_PRODUCT_AP_TEMP_DPP;
			$row = $apObj->getData($this->pid,$param);
			unset($apObj);
			if($row)
			{
				if($row["GENDER"])
					$this->setGENDER($row["GENDER"]);
				if($row["LAGE"])
					$this->setLAGE($row["LAGE"]);
				if($row["HAGE"])
					$this->setHAGE($row["HAGE"]);
				if($row["LHEIGHT"])
					$this->setLHEIGHT($row["LHEIGHT"]);
				if($row["HHEIGHT"])
					$this->setHHEIGHT($row["HHEIGHT"]);
				if($row["CHILDREN"])
					$this->setHAVECHILD($row["CHILDREN"]);
				if($row["HANDICAPPED"])
					$this->setHANDICAPPED(str_replace("'","",$row["HANDICAPPED"]));
				if($row["PARTNER_MSTATUS"])
					$this->setMSTATUS(str_replace("'","",$row["PARTNER_MSTATUS"]));
				if($row["PARTNER_COUNTRYRES"])
					$this->setCOUNTRY_RES(str_replace("'","",$row["PARTNER_COUNTRYRES"]));
				if($row["PARTNER_INCOME"])
					$this->setINCOME(str_replace("'","",$row["PARTNER_INCOME"]));
				if($row["PARTNER_RELIGION"])
					$this->setRELIGION(str_replace("'","",$row["PARTNER_RELIGION"]));
				if($row["PARTNER_CASTE"])
					$this->setCASTE(str_replace("'","",$row["PARTNER_CASTE"]));
				if($row["PARTNER_MTONGUE"])
					$this->setMTONGUE(str_replace("'","",$row["PARTNER_MTONGUE"]));
				if($row["PARTNER_MANGLIK"])
					$this->setMANGLIK(str_replace("'","",$row["PARTNER_MANGLIK"]));
				if($row["PARTNER_CITYRES"])
					$this->setCITY_RES(str_replace("'","",$row["PARTNER_CITYRES"]));
				if($row["PARTNER_DIET"])
					$this->setDIET(str_replace("'","",$row["PARTNER_DIET"]));
				if($row["PARTNER_SMOKE"])
					$this->setSMOKE(str_replace("'","",$row["PARTNER_SMOKE"]));
				if($row["PARTNER_DRINK"])
					$this->setDRINK(str_replace("'","",$row["PARTNER_DRINK"]));
				/*
				if($row["PARTNER_BTYPE"])
					$this->setBTYPE(str_replace("'","",$row["PARTNER_BTYPE"]));
				if($row["PARTNER_COMP"])
					$this->setCOMPLEXION(str_replace("'","",$row["PARTNER_COMP"]));
				*/
				if($row["PARTNER_OCC"])
					$this->setOCCUPATION(str_replace("'","",$row["PARTNER_OCC"]));
				if($row["PARTNER_ELEVEL_NEW"])
					$this->setEDU_LEVEL_NEW(str_replace("'","",$row["PARTNER_ELEVEL_NEW"]));
				/*
				if($row["PARTNER_ELEVEL_NEW"])
				{
					$eduObj = new NEWJS_EDUCATION_LEVEL_NEW;
					$eduLevels = $eduObj->getEduLevels($row["PARTNER_ELEVEL_NEW"]);
					unset($eduObj);
					if($eduLevels)
					$this->setEDU_LEVEL(implode(",",$eduLevels));
				}
				*/
				if($row["NHANDICAPPED"])
					$this->setNATURE_HANDICAP(str_replace("'","",$row["NHANDICAPPED"]));
				$this->isDppExist = 1;
			}
			else
				die;
		}
		else
		{
			$paramArr['PROFILEID'] = $this->pid;

			/**
			* called the store(JPARTNER) to get details for the id.
			*/	
			if($getFromCache == 1){
                                $memObject=JsMemcache::getInstance();
                                $jpartnerData = $memObject->get('SEARCH_JPARTNER_'.$this->pid);

                                if(empty($jpartnerData)){
                                        $dbName = JsDbSharding::getShardNo($this->pid);
                                        $JPARTNERobj = new newjs_JPARTNER($dbName);
                                        $fields = SearchConfig::$dppSearchParamters.",MAPPED_TO_DPP";
                                        $arr = $JPARTNERobj->get($paramArr,$fields);
                                        $memObject->set('SEARCH_JPARTNER_'.$this->pid,serialize($arr),  SearchConfig::$matchAlertCacheLifetime);
                                }else{
                                      $arr = unserialize($jpartnerData);
                                }
                        }else{
                                $dbName = JsDbSharding::getShardNo($this->pid);
                                $JPARTNERobj = new newjs_JPARTNER($dbName);
                                $fields = SearchConfig::$dppSearchParamters.",MAPPED_TO_DPP";
                                $arr = $JPARTNERobj->get($paramArr,$fields);
                        }
                        
			if(is_array($arr[0]))
			{
				$this->isDppExist = 1;
				foreach($arr[0] as $field=>$value)
				{
					$value = str_replace("'","",$value);
					if(strstr($this->possibleSearchParamters,$field))
					{
						if(in_array($field,self::$addNonFilledValuesAttributeArr) && $value)
						{
							if(!strstr($value,SearchConfig::_nullValueAttributeLabel))
								$value=$value.",".SearchConfig::_nullValueAttributeLabel;
						}
						eval ('$this->set'.$field.'($value);');
					}
				}
				//if($this->getCITY_RES() && $this->getCITY_INDIA()=="")
					//$this->setCITY_INDIA($this->getCITY_RES());
				/*as state is mapped to city and if both are same , it means we have mapped them and in order to show city cluser , we need to unset city*/
				if($this->getSTATE()==$this->getCITY_RES())
					$this->setCITY_RES('');

                                if($this->getHAVECHILD())
                                {
                                        $temp = $this->getHAVECHILD();
                                        if(strstr($temp,'Y'))
                                        {
                                                if(!strstr($temp,'YT'))
                                                if(!strstr($temp,'YS'))
                                                {
                                                        $temp = str_replace($temp,"Y","YT,YS");
                                                        $this->setHAVECHILD($temp);
                                                }
                                        }
                                }
                                // if($this->getOCCUPATION() && $source != 'AP'){
                                //         $occpationArray = explode(",",$this->getOCCUPATION());
                                //         $occupationNewWithGrouping = SearchCommonFunctions::getOccupationMappingData($occpationArray);
                                //         if($occupationNewWithGrouping){
                                //                $this->setOCCUPATION(implode(',',$occupationNewWithGrouping)); 
                                //         }
                                // }
				//Special case for mapped values. => useful for cluster display
				$mappedStr = $arr[0]['MAPPED_TO_DPP'];
				if($mappedStr)
				{
					$mappedArr = explode(",",$mappedStr);
					foreach($mappedArr as $k=>$v)
						$this->$v = '';
				}
				//Special case for mapped values.
			}
		}
		if($source != "MAILER")
		{
			if(MobileCommon::isApp()=='I')
				$this->setSEARCH_TYPE(SearchTypesEnums::iOSDpp);
			elseif($source=='mobileAppDpp')
				$this->setSEARCH_TYPE(SearchTypesEnums::AppDpp);
			elseif(MobileCommon::isMobile())
				$this->setSEARCH_TYPE(SearchTypesEnums::WapDpp);
			else
				$this->setSEARCH_TYPE(SearchTypesEnums::Dpp);
		}
		if(PremiumMember::isDummyProfile($this->pid))
		{
			
				$dummyDppKeywordsObj = new DummyDppKeywords($this->pid);
					$result = $dummyDppKeywordsObj->getDummyDPPKeywords();
					if(is_array($result) && $result["KEYWORD"]!='')
		      {
						$this->setKEYWORD($result["KEYWORD"]);
						$this->setKEYWORD_TYPE($result["KEYWORD_TYPE"]);
						
					}
		
		}
		
	}

        /*
        * This Function is to save performed search as dpp.
        * @param SearchParamtersObj object-array storing the deatils of search perfomed.
        * @return true on success.
        */
        public function saveSearchAsDpp($SearchParamtersObj,$mappedArr='')
        {
                if(!$this->pid)
                        throw new jsException("","ProfileId cannot be blank in addRecords of JPARTNER.");

                if($SearchParamtersObj->getGENDER() == $this->loggedInProfileObj->getGENDER())
                        throw new jsException("","trying to save same gender in addRecords of JPARTNER.");


		/* Sharding */
                $dbName = JsDbSharding::getShardNo($this->pid);
                $JPARTNERobj = new newjs_JPARTNER($dbName);

		$possibleSearchParamters = explode(",",SearchConfig::$dppSearchParamters);

		/** will be removed when all quotes is removed **/
		//$withQuotes = array('HANDICAPPED','PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL','PARTNER_ELEVEL_NEW','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RELATION','PARTNER_SMOKE','PARTNER_COMP','PARTNER_RELIGION','PARTNER_NAKSHATRA','NHANDICAPPED'); 
		$withQuotes = array('HANDICAPPED','PARTNER_BTYPE','PARTNER_CASTE','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_DIET','PARTNER_DRINK','PARTNER_ELEVEL_NEW','PARTNER_INCOME','PARTNER_MANGLIK','PARTNER_MSTATUS','PARTNER_MTONGUE','PARTNER_OCC','PARTNER_RELATION','PARTNER_SMOKE','PARTNER_COMP','PARTNER_RELIGION','PARTNER_NAKSHATRA','NHANDICAPPED'); 
		/* added as naming convention are differnt in jpartner and search tables.*/
		foreach($possibleSearchParamters as $v)
		{
			if($v)
			{
				if(strstr($v," AS "))
				{
					$tempArr = explode(" AS ",$v);
					$getter = "get".$tempArr[1];

					$vv = $SearchParamtersObj->$getter();
					if($vv)
					{
						if($tempArr[0])
						{
							$key = $tempArr[0];
							if(in_array($tempArr[0],$withQuotes))
							{
								$vv ="\'".str_replace(",","\',\'",$vv)."\'";
							}
							if(!in_array(htmlspecialchars(stripslashes($vv)),searchConfig::$dont_all_labels))
								$updateArr[$key]="'".$vv."'";	
						}
					}
				}
				else
				{
					$getter = "get".$v;
					$vv = $SearchParamtersObj->$getter();
					if($vv || $vv=='0')
						if(!in_array($vv,searchConfig::$dont_all_labels))
						{
							if(strstr($vv,","))
								$vv ="\'".str_replace(",","\',\'",$vv)."\'";
							$updateArr[$v]="'".$vv."'";
						}
				}
			}
		}
		/* added as naming convention are differnt in jpartner and search tables.*/

		//MAPPING INCOME VALUES
		if(($SearchParamtersObj->getLINCOME_DOL() || $SearchParamtersObj->getLINCOME_DOL() == '0') && ($SearchParamtersObj->getHINCOME_DOL() || $SearchParamtersObj->getHINCOME_DOL()=='0'))		//DOLLAR VALUE PRESENT
                {
                        if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))		//RUPEE VALUE PRESENT
                        {
				//BOTH RUPEE AND DOLLAR VALUE PRESENT SO NOTHINGS TO BE DONE
			}
			else	//RUPEE VALUE NOT PRESENT
			{
				$dArr["minID"] = $SearchParamtersObj->getLINCOME_DOL();
                                $dArr["maxID"] = $SearchParamtersObj->getHINCOME_DOL();
                                $incomeType = "D";
                                $incomeMappingObj = new IncomeMapping("",$dArr);
				$incomeMappingObj->getMappedValues();
				$updateArr["LINCOME"] = "'".$incomeMappingObj->getIncomeArr("minIR")."'";
				$updateArr["HINCOME"] = "'".$incomeMappingObj->getIncomeArr("maxIR")."'";
                                unset($incomeMappingObj);
			}
		}
		else	//DOLLAR VALUE NOT PRESENT
		{
			if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))            //RUPEE VALUE PRESENT
                        {
                                $rArr["minIR"] = $SearchParamtersObj->getLINCOME();
                                $rArr["maxIR"] = $SearchParamtersObj->getHINCOME();
                                $incomeType = "R";
                                $incomeMappingObj = new IncomeMapping($rArr,"");
                                $incomeMappingObj->getMappedValues();
				$updateArr["LINCOME_DOL"] = "'".$incomeMappingObj->getIncomeArr("minID")."'";
				$updateArr["HINCOME_DOL"] = "'".$incomeMappingObj->getIncomeArr("maxID")."'";
                                unset($incomeMappingObj);
                        }
		}
		//MAPPING ENDS

                $key = 'PROFILEID';
		$updateArr[$key]=$this->pid;

                $key = 'DPP';
                $updateArr[$key] = "'".$SearchParamtersObj->getSEARCH_TYPE()."'";

		if($mappedArr)
		{
			$key="MAPPED_TO_DPP";
			$updateArr[$key] = "'".implode(",",$mappedArr)."'";
		}
								$oldDpp = $JPARTNERobj->get(array("PROFILEID"=>$this->pid));
								if(is_array($oldDpp))
								{
									$jpartnerEditLog = new JpartnerEditLog();
									$jpartnerEditLog->logDppEditFromSave($oldDpp[0],$updateArr,$param);
								}
                $JPARTNERobj->addRecords($updateArr);
                return true;
        }
}
?>
