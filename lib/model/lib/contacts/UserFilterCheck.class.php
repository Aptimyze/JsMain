<?php
/**
 * UserfilterCheck class handles the filter condition of user, whether
 * he/she is passing filter of his/her partner.
 * Logs the filter reason.
 * <code>
 * $filterObj=UserFilterCheck::getInstance(Profile $senderObj,Profile $receiverObj,$whyFilterInsert)
 * $filterObj->getFilteredContact();
 * </code>
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nikhil dhiman
 * @author     Hemant.a
 * @version 1.0   SVN: $Id: UserFilter.class.php 23810 2011-07-12 nikhil.dhiman $
 * @version 1.1   SVN: $Id: UserFilter.class.php 23810 2012.11.20 hemant.a $
 */
class UserFilterCheck
{
	private $myParameters;
	private $dppParameters;
	private $isSpam;
	private $filterParameters;
	public static $filterObj;
	private $senderObj;
	private $receiverObj;
	private $_whyFilterInsert;
	private $isHard;
	private $action;

	 /**
         * Creates all necessary parameters required to run filter check
         * @param array $my_parameters
         * @param array $filter_parameters
         * @param array $dpp_parameters
         * @param string $is_spam
         * @param Profile $senderObj
         * @param Profile $receiverObj
       */
	function __construct($my_parameters,$filter_parameters,$dpp_parameters,$is_spam,$senderObj,$receiverObj)
	{
		$this->myParameters=$my_parameters;
		$this->filterParameters=$filter_parameters;
		$this->dppParameters=$dpp_parameters;
		$this->isSpam=$is_spam;
		$this->senderObj = $senderObj;
		$this->receiverObj = $receiverObj;
		$this->isHard= UserFilterCheck::isHardFilter($receiverObj->getGENDER(),$filter_parameters);
	}
	/*
	function __destruct()
	{
		$key=$this->senderObj->getPROFILEID()."_".$this->receiverObj->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
	}
	*/


	/*
	 * @fn isHardFilter
         * @brief set profiles has set hardfilters or softFilters
         * @param array $filter_parameters
         * @param array $gender
	 * @returns true if he/she has set hard Filters otherwise false
	 */
	public static function isHardFilter($gender,$filter_parameters)
	{
		//call the store class function which calls the FILTER_STATUS table to check whether this profile is hard filter or not 
		//profileID is the parameter required
		
		if($filter_parameters)
		{
				if ($filter_parameters['HARDSOFT']=='Y')
					return true; 
				else
				{
					if($filter_parameters['COUNT']>=3)
					{
						if($gender=='M')
						return true;
						else 
						return false;
					}
					else
					return false;
				}
		}
		else
		{
			return false;
		}
	}

	
	/**
	 * returns instance if Instance not already exist
	 * 
	 * @access public
     * @param Profile $senderObj
     * @param Profile $receiverObj
	 * @param integer $whyFilterInsert default  1
	 * @return UserFilter::$filterObj
	 */
	public static function getInstance($senderObj,$receiverObj,$whyFilterInsert=0)
	{
		
		$key=$senderObj->getPROFILEID()."_".$receiverObj->getPROFILEID();
		if(!(UserFilterCheck::$filterObj[$key] instanceof UserFilterCheck))
		{
			
			$my_parameters = $senderObj->getFilterParameters();
			
			$filter_parameters = UserFilterCheck::getFilterParameters($receiverObj->getPROFILEID());
			
			$dpp_parameters = UserFilterCheck::getViewerDpp($receiverObj);
			
//			$is_spam = UserFilterCheck::isSpam($senderObj->getPROFILEID(), 'sendContact');
			UserFilterCheck::$filterObj[$key] = new UserFilterCheck($my_parameters,$filter_parameters,$dpp_parameters,$is_spam,$senderObj,$receiverObj);
			UserFilterCheck::$filterObj[$key]->senderObj=$senderObj;
			UserFilterCheck::$filterObj[$key]->receiverObj=$receiverObj;
		}
		UserFilterCheck::$filterObj[$key]->_whyFilterInsert = $whyFilterInsert;
		return UserFilterCheck::$filterObj[$key];
		
	}
	
	/**
	 * Returns profile is getting filtered or not.
	 * @access public
	 * @return bool
	 */
	public function getFilteredContact($action=ContactHandler::EOI)
	{
		$NEGATIVE_TREATMENT_LIST=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
		if($action==ContactHandler::INFO)
			$result=$NEGATIVE_TREATMENT_LIST->isFlagContactDetail($this->senderObj->getPROFILEID(),'N');
		else	
			$result=$NEGATIVE_TREATMENT_LIST->isFlagInboxEoi($this->senderObj->getPROFILEID(),'N');
		
		$this->action = $action;
		if($result)
		{
			$filtered_contact="Y";
			$this->WhyFilter('SPAMMER',"SPAMMER ".$action." ".$this->senderObj->getPROFILEID()." ".$this->receiverObj->getPROFILEID());
		}
		else
		{
		if(is_array($this->filterParameters))
		{
			$this->filterCheck="Y";
			$check_dpp = $this->checkDppForContact();
			if(!$check_dpp)
				$filtered_contact="Y";
			else
			{
				
				/*if($this->isSpam)
					$spam_check=1;
				else*/
					$filtered_contact="";
			}
		}
		else
		{
			
			/*if($this->isSpam)
				$spam_check=1;
			else*/
				$filtered_contact="";
		}
		/*if($spam_check && $filtered_contact!="Y")
		{
			$this->filterCheck="N";
			$this->filterParameters='';
			$check_dpp=$this->checkDppForContact();
			if(!$check_dpp)
			{
				//Required by contact engine.
				$this->spammer=1;
				$filtered_contact="Y";
			}	
			else
				$filtered_contact="";
		}*/
		//TRENDS SPAM CONTACT
		if($filtered_contact!="Y" && $this->isHard===false)
		{
			if($this->calculateSpamScore("",$action))
				$filtered_contact="Y";
		}
	}
		//IGNORE PROFILE CONTACT
		if($filtered_contact!="Y")
		{
			if($this->checkIgnoreProfile("",$action))
				$filtered_contact="Y";
				
		}
                
		return $filtered_contact == "Y" ? true : false;

	}
	/**
	 * returns whether profile is passing filter of other users or
	 * not. 
	 * @access public
	 * @return bool
	 */
	private function checkDppForContact()
	{
		
		if($this->filterCheck == Messages::YES)
		{
			if(!is_array($this->filterParameters))
			return true;	
		}
		if($this->dppParameters == '')
			return true;

		if($this->filterCheck != Messages::YES)	//When spam logic works.
		{
			$this->filterParameters[CASTE]=Messages::YES;
			$this->filterParameters[MTONGUE]=Messages::YES;
			$this->filterParameters[CITY_RES]=Messages::YES;
			$this->filterParameters[COUNTRY_RES]=Messages::YES;
		}
		
		if($this->isFilter("AGE","LAGE","HAGE"))
			return false;
		if($this->isFilter("CASTE"))
			return false;
		if($this->isFilter("MTONGUE"))
			return false;
		if ($this->action != "VISIT" )
			if($this->isFilter("CITY_RES"))
				return false;
		if($this->isFilter("COUNTRY_RES"))
            return false;
		if($this->isFilter("MSTATUS"))
            return false;
		if($this->isFilter("RELIGION"))
			return false;
		if ($this->action != "VISIT" )	
			if($this->isFilter("INCOME"))
	            return false;
            
		return true;
	}
	
	/**
	 * returns whether profile is marked as spam or not based on spam score calculation
	 * @access private
	 * @return bool
	 */
	private function checkIgnoreProfile()
	{
		//echo "lib--";
                $viewProfileOptimization = viewProfileOptimization::getInstance($this->senderObj->getPROFILEID(),$this->receiverObj->getPROFILEID());
		$ignFilterStatus = $viewProfileOptimization->getIgnoreProfileStatus();
                if(isset($ignFilterStatus)){
                    if($ignFilterStatus == 2)
                        $ignoreFilter = true;
                    else
                        $ignoreFilter = false;
                }
                else{
                    $ignoreObj = new IgnoredProfiles("newjs_master");
                    $ignoreFilter = $ignoreObj->ifIgnored($this->receiverObj->getPROFILEID(),$this->senderObj->getPROFILEID(),ignoredProfileCacheConstants::BYME);
                }
		if($ignoreFilter)
		{	$data= "Ignored By the Reciever";
			$type="Ignore";
			$this->WhyFilter($type,$data);
		}
		return $ignoreFilter;
	}
	/**
	 * returns whether profile is marked as spam or not based on spam score calculation
	 * @access private
	 * @return bool
	 */
	private function calculateSpamScore($needScore='',$action)
	{
		//stopping trend based logic as pe JSI-1136
		if($action!=ContactHandler::EOI)
			return 0;
		$totalScore = 0;
		
		$spamObj = new twowaymatch_TRENDS_FOR_SPAM();
		$spamTrendArr = $spamObj->getSpamTrends($this->receiverObj->getPROFILEID());
		$spamTrendResult = $spamTrendArr[0];
		
		if(!is_array($spamTrendResult))
			return 0;
	
		$senderMstatus = $this->senderObj->getMSTATUS();
		if($senderMstatus != 'N')
			$senderMstatus = 'M';
		$senderManglik = $this->senderObj->getMANGLIK();
		if($senderManglik != 'M' && $senderManglik != 'A')
			$senderManglik = 'N';
			
		$caste = $this->senderObj->getCASTE();
		$w_caste = $spamTrendResult['W_CASTE'];
		$caste_percentile = $spamTrendResult['CASTE_VALUE_PERCENTILE'];
		$casteScore = $this->getTrendsScore($caste,$caste_percentile,$w_caste);
		$totalScore += $casteScore;

		$mtongue = $this->senderObj->getMTONGUE();
		$w_mtongue =  $spamTrendResult['W_MTONGUE'];
		$mtongue_percentile = $spamTrendResult['MTONGUE_VALUE_PERCENTILE'];
		$mtongueScore = $this->getTrendsScore($mtongue,$mtongue_percentile,$w_mtongue);
		$totalScore += $mtongueScore;
	
		$edu_level_new = $this->senderObj->getEDU_LEVEL_NEW();
		$w_education = $spamTrendResult['W_EDUCATION'];
		$education_percentile = $spamTrendResult['EDUCATION_VALUE_PERCENTILE'];
		$edu_level_newScore = $this->getTrendsScore($edu_level_new,$education_percentile,$w_education);
		$totalScore += $edu_level_newScore;

		$occ = $this->senderObj->getOCCUPATION();
		$w_occupation = $spamTrendResult['W_OCCUPATION'];
		$occupation_percentile = $spamTrendResult['OCCUPATION_VALUE_PERCENTILE'];
		$occScore = $this->getTrendsScore($occ,$occupation_percentile,$w_occupation);
		$totalScore += $occScore;

		$manglik = $senderManglik;
		$w_manglik = $spamTrendResult['W_MANGLIK'];
		$manglikMP = $spamTrendResult['MANGLIK_M_P'];
		$manglikNP = $spamTrendResult['MANGLIK_N_P'];
		$manglikAP = $spamTrendResult['MANGLIK_A_P'];
		if($manglik == 'M')
			$manglikScore = $w_manglik*$manglikMP;
		elseif($manglik == 'A')
			$manglikScore = $w_manglik*$manglikAP;
		else
			$manglikScore = $w_manglik*$manglikNP;
		$totalScore += $manglikScore;

		$H1 = $this->senderObj->getHEIGHT();
		$H2 = $this->receiverObj->getHEIGHT();
		$height = $this->getTrendsBucket($H2,$H1);
		$w_height = $spamTrendResult['W_HEIGHT'];
		$height_percentile = $spamTrendResult['HEIGHT_VALUE_PERCENTILE'];
		$heightScore = $this->getTrendsScore($height,$height_percentile,$w_height);
		$totalScore += $heightScore;

		$mstatus = $senderMstatus;
		$w_mstatus = $spamTrendResult['W_MSTATUS'];
		$mstatusMP = $spamTrendResult['MSTATUS_M_P'];
		$mstatusNP = $spamTrendResult['MSTATUS_N_P'];
		if($mstatus == 'M')
			$mstatusScore = $w_mstatus*$mstatusMP;
		else
			$mstatusScore = $w_mstatus*$mstatusNP;
		$totalScore += $mstatusScore;

		$nri = $senderMstatus;
		$w_nri = $spamTrendResult['W_NRI'];
		$nriMP = $spamTrendResult['NRI_M_P'];
		$nriNP = $spamTrendResult['NRI_N_P'];
		if($nri == '51')
				$nriScore = $w_nri*$nriMP;
		else
				$nriScore = $w_nri*$nriNP;
		$totalScore += $nriScore;


		$age1 = $this->senderObj->getAGE();
		$age2 = $this->receiverObj->getAGE();
		$age = $this->getTrendsBucket($age2,$age1);
		$w_age = $spamTrendResult['W_AGE'];
		$age_percentile = $spamTrendResult['AGE_BUCKET'];
		$ageScore = $this->getTrendsScore($age,$age_percentile,$w_age);
		$totalScore += $ageScore;
                $incomeObj = new IncomeMapping();
		$inc1 = $incomeObj->getSortedIncome($this->senderObj->getINCOME());
		$inc2 = $incomeObj->getSortedIncome($this->receiverObj->getINCOME());
		unset($incomeObj);
		$inc = $this->getTrendsBucket($inc2,$inc1);
        $w_income = $spamTrendResult['W_INCOME'];
        $income_percentile = $spamTrendResult['INCOME_VALUE_PERCENTILE'];
		$incScore = $this->getTrendsScore($inc,$income_percentile,$w_income);
		$totalScore += $incScore;
		
		if($needScore)
			return $totalScore;
		
		if($totalScore < 0)	
		{
			$i_val = $spamTrendResult['I_VAL'];
			if($i_val < 0 && $totalScore < $i_val || $i_val >= 0)
			{
				$data="i_val=".$i_val."---Score=".$totalScore;
				$type="TRENDS";
				$this->filterCheck = Messages::YES;
				$this->WhyFilter($type,$data);
				return 1;
			}
		}
		return 0;
	}
	
	
	/**
	 * returns if there is any filter for the given type
	 * @param string $type
	 * @param string $type1 default value null
	 * @param string $type2 default value null
	 * @access private
	 * @return boolean
	 */
	private function isFilter($type,$type1="",$type2="")
	{
		if($this->filterParameters[$type]==Messages::YES && $type && $type2 && $type1) //Applied for Age check
		{
			if($this->myParameters[$type] < $this->dppParameters[$type1] || $this->myParameters[$type]>$this->dppParameters[$type2])
			{
				$str_filter=" ".$this->myParameters[$type]." ". $this->dppParameters[$type1]." " .$this->dppParameters[$type2]."";
				$this->WhyFilter($type,$str_filter);
				return true;
			}
		}
		else if($this->filterParameters[$type]==Messages::YES && is_array($this->dppParameters[$type]))
		{
                        if($type == 'CITY_RES' && $this->myParameters["COUNTRY_RES"] != 51){
                                if(in_array($this->myParameters["COUNTRY_RES"],$this->dppParameters["COUNTRY_RES"])){
                                        return false;
                                }
                        }
			if(!in_array($this->myParameters[$type],$this->dppParameters[$type]))
			{  
				
				$str_filter=$this->myParameters[$type]." ".implode(",",$this->dppParameters[$type]);
				$this->WhyFilter($type,$str_filter);
				return true; 
			}       
		}
		return false;
									
	}
	
	/**
	 * logs the filter information into database table
	 * 
	 * @access private
	 */
	private function WhyFilter($type,$data)
	{
		
		//Stored only those records that don't have below url's
		if($this->_whyFilterInsert==1)
			if($type && $data )
			{
				$whyFilter=new MIS_WHY_FILTER();
				$whyFilter->insertEntry($this->senderObj->getPROFILEID(),$this->receiverObj->getPROFILEID(),$type,$data,$this->filterCheck);
			}
			
	}
	
	
	/**
	 * returns calculated trends score
	 * @param string $MYVAL
	 * @param string $PERCENTILE
	 * @param string $weight
	 * @access private
	 * @return integer
	 */
	private function getTrendsScore($MYVAL,$PERCENTILE,$weight)
	{
		if(!strstr($PERCENTILE,"|$MYVAL#"))
			return 0;
		$temp=strpos($PERCENTILE,"|$MYVAL#");
		$len=strlen($MYVAL)+2;
		$end=strpos($PERCENTILE,"#",$temp);
		$len=strlen($MYVAL)+2;
		$len=$len+$temp-1;
		$temp=strpos($PERCENTILE,"|",$len);
		$lenOfValue=$temp-$len-1;
		$value=substr($PERCENTILE,$len+1,$lenOfValue);
		return $value*$weight;
	}


	/**
	 * returns trends bucket
	 * @param integer $sender_age
	 * @param integer $receiver_age
	 * @access private
	 * @return string
	 */
	private function getTrendsBucket($sender_age,$receiver_age)
	{
		if (($sender_age-$receiver_age) <-10)
			$age_diff='SY10';
		else if (($sender_age-$receiver_age)>=-10 && ($sender_age-$receiver_age)<-7)
			$age_diff='SY7t10';
		else if (($sender_age-$receiver_age)>=-7 && ($sender_age-$receiver_age)<-4)
			$age_diff='SY4t7';
		else if (($sender_age-$receiver_age)>=-4 && ($sender_age-$receiver_age)<-2)
			$age_diff='SY2t4';
		else if (($sender_age-$receiver_age)>=-2 && ($sender_age-$receiver_age)<0)
			$age_diff='SY0t2';
		else if($sender_age-$receiver_age==0)
			$age_diff='equal';
		else if (($sender_age-$receiver_age)>=0 && ($sender_age-$receiver_age)<2)
			$age_diff='SE0t2';
		else if (($sender_age-$receiver_age)>=2 && ($sender_age-$receiver_age)<4)
			$age_diff='SE2t4';
		else if (($sender_age-$receiver_age)>=4 && ($sender_age-$receiver_age)<7)
			$age_diff='SE4t7';
		else if (($sender_age-$receiver_age)>=7 && ($sender_age-$receiver_age)<10)
			$age_diff='SE7t10';
		else if ($sender_age-$receiver_age>=10)
			$age_diff='SEe10';
		else
			$age_diff='others';
			
        return $age_diff;
	}
	
	/**
	 * Filter paramenter of particular profile having 
	 * Jpartner data
	 * @param $jpartnerObj Jpartner profile jpartner object
	 * @return $DPP_PARAMETERS mixed partner details
	 * @throws jsException if partnerObj is null
	 */
	public static function getFilterDpp($jpartnerObj)
	{
		$DPP_PARAMETERS=array();
		
		if($jpartnerObj instanceof PartnerProfile)
		{
			$jpartnerObj->getRELIGION();
			$DPP_PARAMETERS["LAGE"]=$jpartnerObj->getLAGE();
			$DPP_PARAMETERS["HAGE"]=$jpartnerObj->getHAGE();

			if($jpartnerObj->getCASTE()!='')
			{
				$casteObj = new RevampCasteFunctions();
				$PARTNER_CASTE=CommonFunction::displayFormat($jpartnerObj->getCASTE());
				$DPP_PARAMETERS["CASTE"]=$casteObj->getAllcastes($PARTNER_CASTE,1);
			}
			if($jpartnerObj->getCOUNTRY_RES()!='')
				$DPP_PARAMETERS["COUNTRY_RES"]=CommonFunction::displayFormatModify($jpartnerObj->getCOUNTRY_RES());
				
			if($jpartnerObj->getCITY_RES()!='')
			{
				$CITYRES=CommonFunction::displayFormat($jpartnerObj->getCITY_RES());
				$DPP_PARAMETERS["CITY_RES"]=CommonFunction::getAllCities($CITYRES,1);
			}
                        if($jpartnerObj->getSTATE()!='')
                        {
                                $cityList = "";
                                $STATE=explode(",",$jpartnerObj->getSTATE());
                                foreach($STATE as $kk=>$vv)
                                    $cityList .= ",".FieldMap::getFieldLabel("state_CITY", $vv);
                                $CITYRES=explode(",",trim($cityList,','));
                                if(is_array($DPP_PARAMETERS['CITY_RES']))
                                    $DPP_PARAMETERS["CITY_RES"]=  array_merge($DPP_PARAMETERS['CITY_RES'],CommonFunction::getAllCities($CITYRES));
                                else
                                    $DPP_PARAMETERS["CITY_RES"]=  CommonFunction::getAllCities($CITYRES);
                        }
			
			if($jpartnerObj->getMSTATUS()!="")
				$DPP_PARAMETERS["MSTATUS"]=CommonFunction::displayFormatModify($jpartnerObj->getMSTATUS());
			if($jpartnerObj->getMTONGUE()!="")
				$DPP_PARAMETERS["MTONGUE"]=CommonFunction::displayFormatModify($jpartnerObj->getMTONGUE());
			if($jpartnerObj->getRELIGION()!="")
				$DPP_PARAMETERS["RELIGION"]=CommonFunction::displayFormatModify($jpartnerObj->getRELIGION());
			if($jpartnerObj->getINCOME()!="")
				$DPP_PARAMETERS["INCOME"]=CommonFunction::displayFormatModify($jpartnerObj->getINCOME());
				
				if($jpartnerObj->getLINCOME()>=0 || $jpartnerObj->getLINCOME_DOL()>=0)
				{
					if($jpartnerObj->getLINCOME()>=0)
					{
						if($jpartnerObj->getLINCOME_DOL()>=0)
						{
							 $rArr["minIR"] = $jpartnerObj->getLINCOME();
							 $rArr["maxIR"] = 19;
                             $dArr["minID"] = $jpartnerObj->getLINCOME_DOL();
                             $dArr["maxID"] = 19;
                             $incomeType = "B";
                             $incomeObj = new IncomeMapping($rArr,$dArr);

							 $incomes=$incomeObj->incomeMapping();
						}
						else
						{
							$rArr["minIR"] = $jpartnerObj->getLINCOME();
							$rArr["maxIR"] = 19;
							$incomeType = "R";
                            $incomeObj = new IncomeMapping($rArr,"");
							$incomes=$incomeObj->incomeMapping();
						}
					}
					else
						if($jpartnerObj->getLINCOME_DOL()>=0)
						{
							$dArr["minID"] = $jpartnerObj->getLINCOME_DOL();
							$dArr["maxID"] = 19;
							$incomeType = "D";
                            $incomeObj = new IncomeMapping("",$dArr);
							$incomes=$incomeObj->incomeMapping();
						}
					
					if($incomes[istr])
						$DPP_PARAMETERS["INCOME"]=explode(",",str_replace("'","",$incomes[istr]));
	
			}	
						
		}
		else
		{   
			$ex = new sfException(sprintf(' Jpartner object is not present  %s::%s.', get_class($this), $method));
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $ex);
			throw $ex;
		}
		
		return $DPP_PARAMETERS;
	}
	
	/**
	 * returns filterParameters that are to be checked
	 * @param integer $profileid
	 * @access public
	 * @static
	 * @return array
	 */
	public static function getFilterParameters($profileid)
	{
		$filtObj=new ProfileFilter();
		$row=$filtObj->fetchEntry($profileid);
		if(is_array($row))
		{
			
			if($row["AGE"]=="Y" || $row["MTONGUE"]=="Y" || $row["MSTATUS"]=="Y" || $row["COUNTRY_RES"]=="Y" || $row["CITY_RES"]=="Y" || $row["RELIGION"]=="Y" || $row["CASTE"]=="Y" || $row["INCOME"]=="Y")
			{
				$filter_parameters=array("AGE"=>$row["AGE"],
							"MSTATUS"=>$row["MSTATUS"],
							"MTONGUE"=>$row["MTONGUE"],
							"COUNTRY_RES"=>$row["COUNTRY_RES"],
							"CITY_RES"=>$row["CITY_RES"],
							"RELIGION"=>$row["RELIGION"],
							"INCOME"=>$row["INCOME"],
							"CASTE"=>$row["CASTE"],
							"COUNT"=>$row["COUNT"],
							"HARDSOFT"=>$row["HARDSOFT"]);
				
				return $filter_parameters;
			}
			else
				return '';
		}
		else
			return '';

	}
	
	
	/**
	 * returns if profile has been marked spam
	 * @access public
	 * @param integer $profileid
	 * @param string $action default null
	 * @return boolean
	 */
	public static function isSpam($profileid,$action='')
	{
		if($profileid)
		{
			$key=$profileid."SPAMMER";
			$spam=JsMemcache::getInstance()->get($key);
			if($spam==1)
			{
				return true;
			}
			if(!$spam)
			{
				$negativeListObj=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
				if($action == 'sendContact')
				       $bool= $negativeListObj->isFlagInboxEoi($profileid,"N");
				elseif($action == 'viewContactDetails')
					$bool= $negativeListObj->isFlagContactDetail($profileid,"N");
				if($bool)
				{
					//is spammer
					JsMemcache::getInstance()->set($key,1,3600);
					return true;
				}
				//not a spammer.
				JsMemcache::getInstance()->set($key,2,3600);
			}

		}
		return false;
	}
	
	/**
	 * returns dppParameter array
	 * @access public
	 * @param Profile $profileObj
	 * @return array
	 */
	public static function getViewerDpp($profileObj)
	{
		$dpp_parameters=array();
		if($profileObj->getPROFILEID())
		{
			$jpartnerObj=new PartnerProfile($profileObj);
			$jpartnerObj->getDppCriteria();
			$dpp_parameters=UserFilterCheck::getFilterDpp($jpartnerObj);	
			
		}
		else
		{		
			$ex = new sfException(sprintf(' No profile class object send with profileid  %s::%s.', get_class($actionObj), $method));
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $ex);
			throw $ex;
		}
		return $dpp_parameters;
	}
	
        
        public function getDppParameters(){
            
            return $this->dppParameters;
        }

}
