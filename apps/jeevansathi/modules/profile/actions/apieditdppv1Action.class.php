<?php
/**
 * Desired Partner Profile Edit Api Action.
 */
 
/**
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @date	   13th Jan 2014
 * @version    
 */
 
class apieditdppv1Action extends sfAction 
{
	private $m_objLoginProfile;
	private $m_szQuery;
	private $m_bEditSpouse = false;
	private $m_bDppUpdate = false;
	private $dppUpdateArray ;
	public function execute($request)	
	{
		//Contains login credentials
		$this->loginData=$request->getAttribute("loginData");
		
		new ProfileCommon($this->loginData);
		
		$this->m_objLoginProfile = LoggedInProfile::getInstance();
		$this->profileId = $this->m_objLoginProfile->getPROFILEID();
    if($this->m_objLoginProfile->getAGE()== "")
      $this->m_objLoginProfile->getDetail($this->profileId ,"PROFILEID","*");
		
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		
		//Init JPartner Object
		if($this->profileId)
			$this->InitJpartnerObject();
		
		//Formatting Input Values
		$this->PreprocessInput();
		
		//Get symfony form object related to Edit Fields coming.
		$arrEditDppFieldIDs = $request->getParameter("editFieldArr");		
		
		if ( $_SERVER['HTTP_X_REQUESTED_BY'] === NULL && ( MobileCommon::isNewMobileSite() || MobileCommon:: isDesktop()))
		{
			$errorArr["ERROR"]="Something went wrong.";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}		
		else
		{
			if($arrEditDppFieldIDs && is_array($arrEditDppFieldIDs))
			{
				$this->form = new FieldForm($arrEditDppFieldIDs,$this->m_objLoginProfile);			       
				$this->form->bind($arrEditDppFieldIDs);
				if ($this->form->isValid())
				{
					if($this->m_bDppUpdate)
					{
						$this->Update();
					}

					if($this->m_bEditSpouse)
					{
						$this->form->updateData();
					}	
					$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
					JsMemcache::getInstance()->delete('dppIdsCaching_'.$this->loginData["PROFILEID"]);
					JsMemcache::getInstance()->delete('dppIdsCaching_'.$this->loginData["PROFILEID"].'_time');
					if($request->getParameter("getData")=="dpp"){
						ob_start();
						$request->setParameter("sectionFlag","dpp");
						$request->setParameter("internal",1);
						$fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile","ApiEditV1");
						$this->dppData = ob_get_contents();
						ob_end_clean();
						$apiResponseHandlerObj->setResponseBody(json_decode($this->dppData,true));

					}
				}
				else
				{
					$error=array();
					$e=$this->form->getErrorSchema();
					foreach($e as $k=>$v)
					{
						$errorArr[$k]=$v->getMessage();
					}
					$error[error]=json_decode(json_encode(array_values($errorArr)), FALSE);
					$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
					$apiResponseHandlerObj->setResponseBody($error);
				}
			}
			else
			{
				$errorArr["ERROR"]="Field Array is not valid";
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseBody($errorArr);
			}
		}
		$apiResponseHandlerObj->generateResponse();
		die;
		
	}
	
	/**
	 * UpdateQuery
	 */
	private function UpdateQuery()
	{
		$request = sfContext::getInstance()->getRequest();
		$arrEditDppFieldIDs = $request->getParameter("editFieldArr");
		//Update Partner Income Also if Income is updated
		//if(stristr($scase,"LINCOME") || stristr($scase,"HINCOME") || stristr($scase,"LINCOME_DOL") ||stristr($scase,"HINCOME_DOL"))
		if(array_key_exists("P_LRS",$arrEditDppFieldIDs) || array_key_exists("P_HRS",$arrEditDppFieldIDs) || array_key_exists("P_LDS",$arrEditDppFieldIDs) ||array_key_exists("P_HDS",$arrEditDppFieldIDs) )
		{
                        if($arrEditDppFieldIDs['P_LRS'] != 0 && $arrEditDppFieldIDs['P_LDS'] == 0 && $this->partnerObj->getLINCOME_DOL() != 12){
                                $arrEditDppFieldIDs['P_LDS'] = 12;
                                if(!$arrEditDppFieldIDs['P_HDS']){
                                        $arrEditDppFieldIDs['P_HDS'] = 19;
                                }
                        }
			if($arrEditDppFieldIDs['P_LRS'] || $arrEditDppFieldIDs['P_HRS'])
			{
				$rArr["minIR"] = $arrEditDppFieldIDs['P_LRS'] ;
				$rArr["maxIR"] = $arrEditDppFieldIDs['P_HRS'] ;
			}
			if($arrEditDppFieldIDs['P_LDS'] || $arrEditDppFieldIDs['P_HDS'])
			{
				$dArr["minID"] = $arrEditDppFieldIDs['P_LDS'] ;
				$dArr["maxID"] = $arrEditDppFieldIDs['P_HDS'] ;
			}
			$incomeMapObj = new IncomeMapping($rArr,$dArr);
			$incomeMapArr = $incomeMapObj->incomeMapping();
			$Income = $incomeMapArr['istr'];
			$scase .= ",PARTNER_INCOME" . "=\"$Income\"";
			$arrEditDppFieldIDs['P_INCOME'] = $Income;
			$arrEditDppFieldIDs['P_LRS'] = intval($incomeMapArr['rsLIncome']);
			$arrEditDppFieldIDs['P_HRS'] = intval($incomeMapArr['rsHIncome']);
			$arrEditDppFieldIDs['P_LDS'] = intval($incomeMapArr['doLIncome']);
			$arrEditDppFieldIDs['P_HDS'] = intval($incomeMapArr['doHIncome']);
			
			$request->setParameter("editFieldArr",$arrEditDppFieldIDs);
		}
		$arrDppUpdate = array();
		foreach($arrEditDppFieldIDs as $key=>$val)
		{
			if($key != 'SPOUSE')
				$arrDppUpdate[$key] = DPPConstants::FormatInputStr($key,$val);
		}
		$arrUpdateQuery = array();
	
		foreach($arrDppUpdate as $key=>$val)
		{
			$this->dppUpdateArray[DPPConstants::getDppFieldMapping($key)] = $val;
			$arrUpdateQuery[] = DPPConstants::BakeQuery($key,$val);
		}
		
		$szFinalQuery = implode(",",$arrUpdateQuery);
		$this->m_szQuery = $szFinalQuery;		
	}
	
	private function Update()
	{
		$this->UpdateQuery();
		$arrEditDppFieldIDs = sfContext::getInstance()->getRequest()->getParameter("editFieldArr");
		$fromBackend = sfContext::getInstance()->getRequest()->getParameter("fromBackend");
		if(!($this->m_objLoginProfile))
			return ;
			
		$scase	= $this->m_szQuery;	
		$request = sfContext::getInstance()->getRequest();		
		$this->m_objLoginProfile->getDetail($this->profileId,"","*","RAW");
		
		/*if(in_array("T", explode(",", $this->m_objLoginProfile->getSUBSCRIPTION()))) 
			$userType = UserType::AP_USER;
		
		if ($userType == UserType::AP_EXECUTIVE) 
		{	
			//Not required
		}
		
		//If editted by a assisted product User(Subscription T)
		if ($userType == UserType::AP_USER)
		{
			$objAp_Store_TEMP_DPP = new ASSISTED_PRODUCT_AP_TEMP_DPP;
			$szWhereStr = "AND CREATED_BY='ONLINE'";
			
			$bRecordExist = $objAp_Store_TEMP_DPP->updateDPP($this->profileId,$scase,$szWhereStr);
			
			$jpartnerObj =  $this->partnerObj;
			
			$objAp_DPP_FILTER_ARCHIVE = new AP_DPP_FILTER_ARCHIVE;
			//$szStatus	= "'SE','NQA','RQA','OBS'"; //TODO Check Status Requirements
			$arrJParnterInfo = $objAp_DPP_FILTER_ARCHIVE->fetchCurrentDPP($this->profileId,null);
			
			if(!$arrJParnterInfo || !$bRecordExist)
			{
				$arrEditInfo = DPPConstants::getEditValues($arrEditDppFieldIDs);
				
        //If Any edit values exist then mark online edit
        if(count($arrEditInfo)){
          $arrEditInfo['CREATED_BY'] = 'ONLINE';
        }
        if((!$arrJParnterInfo || (is_array($arrJParnterInfo) && count($arrJParnterInfo) == 0)) && $bRecordExist == false){
          //Get data from JPARTNER Table
          $jpartnerObj->setJpartnerUsingArray($arrEditInfo);
          $arrDPPTempColumn = $jpartnerObj->getJpartnerArray();
        }
        else if($bRecordExist == false)
        {//Create all list array
          foreach($arrEditInfo as $key=>$val)
          {
            $arrJParnterInfo[$key] = $val;
          }
          $arrDPPTempColumn = array();

          foreach($arrJParnterInfo as $key=>$val)
          {
            if(!in_array($key,DPPConstants::$arrAP_DPP_TEMP_FIELDS))
              continue;

            $arrDPPTempColumn[$key] = $val;
          }
        }
        else{
          //Update the existing row
          $arrDPPTempColumn = array();
          foreach ($arrEditInfo as $key => $val) {
              if (!in_array($key, DPPConstants::$arrAP_DPP_TEMP_FIELDS))
                  continue;

              $arrDPPTempColumn[$key] = $val;
          }
        }
				
        $objAp_Store_TEMP_DPP->replaceData($arrDPPTempColumn);
			}
		} 
		else
		{*/
			if($scase)
				$scase.= ",DPP='E',DATE=now()";
			
			$jpartnerObj = $this->partnerObj;
						
			if(!$jpartnerObj->isPartnerProfileExist($this->myDb, $this->mysqlObj)){
				$jpartnerObj->setPROFILEID($this->profileId);
				if($gender=='M')
					$partner_gender= 'F';
				else
					$partner_gender='M';
				$jpartnerObj->setGENDER($partner_gender);
			}
		
			$jpartnerObj->updatePartnerDetails($this->myDb, $this->mysqlObj, $scase);
			$jpartnerEditLog = new JpartnerEditLog();
			$param["fromBackend"] = $fromBackend;
			$jpartnerEditLog->logDppEdit($jpartnerObj,$this->dppUpdateArray,$param);
                        
                        // remove entry from list count table used in Match alerts mailer
                        TwoWayBasedDppAlerts::deleteEntry($this->profileId);
                        
                        // remove Low Dpp flag when user changes dpp
                        $memObject=JsMemcache::getInstance();
                        $memObject->remove('MA_LOWDPP_FLAG_'.$this->profileId);
                        (new MIS_CA_LAYER_TRACK())->truncateForUserAndLayer($this->profileId,11,'');
		//}
		
		//If profile's Source is ofl_prof Then do following
		if (strtolower($this->m_objLoginProfile->getSOURCE()) == "ofl_prof") 
		{
			$dbObj=new JSADMIN_OFFLINE_BILLING;
			$data=$dbObj->fetch($this->profileId);
			
			if($data)
			{
				$bid= $data['BILLID'];
				$dbObj->DPPUpdated($this->profileId,$bid);
			}
		}
	}
	
	private function InitJpartnerObject()
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		$this->partnerObj=new Jpartner;
		$this->mysqlObj=new Mysql;
		$this->myDbName=getProfileDatabaseConnectionName($this->profileId,'',$this->mysqlObj);
		$this->myDb=$this->mysqlObj->connect($this->myDbName);
		$this->partnerObj->setPartnerDetails($this->profileId,$this->myDb,$this->mysqlObj);    	
    $this->m_objLoginProfile->setJpartner($this->partnerObj);
	}
	
	
	private function PreprocessInput()
	{
		//Get symfony form object related to Edit Fields coming.
		$request = sfContext::getInstance()->getRequest();
		$arrEditDppField = $request->getParameter("editFieldArr");

		$arrOut = array();
		//arrMapNullValue contains all those value which used to mark as a null value or Doesnot matter value
		//as in case of API we are sending DM in view to map those values as doesnot matter in app
		$arrMapNullValue = array(-1,'DM');
		
		foreach($arrEditDppField as $key=>$val)
		{
			if($key == "P_AGE")
			{ 	
				$this->m_bDppUpdate = true;
				$arrOut["P_LAGE"] = (in_array($val["LAGE"],$arrMapNullValue))? "" : $val["LAGE"];
				$arrOut["P_HAGE"] = (in_array($val["HAGE"],$arrMapNullValue))? "" : $val["HAGE"];
				
				$gen = $this->m_objLoginProfile->getGENDER();
				$arrOut["P_GENDER"] = ($gen == 'M') ? 'F' : 'M';
			}
			else if($key == "P_HEIGHT")
			{   $this->m_bDppUpdate = true;
				$arrOut["P_LHEIGHT"] = (in_array($val["LHEIGHT"],$arrMapNullValue))? "" : $val["LHEIGHT"];
				$arrOut["P_HHEIGHT"] = (in_array($val["HHEIGHT"],$arrMapNullValue))? "" : $val["HHEIGHT"];
			}
			else if($key == "P_INCOME")
			{
				$arrOut["P_LRS"] = (in_array($val["LRS"],$arrMapNullValue))? "" : $val["LRS"];
				$arrOut["P_HRS"] = (in_array($val["HRS"],$arrMapNullValue))? "" : $val["HRS"];
				$arrOut["P_LDS"] = (in_array($val["LDS"],$arrMapNullValue))? "" : $val["LDS"];
				$arrOut["P_HDS"] = (in_array($val["HDS"],$arrMapNullValue))? "" : $val["HDS"];
				$this->m_bDppUpdate = true;
			}
			else if($key == 'SPOUSE')
			{
				$this->m_bEditSpouse = true;
				$arrOut[$key] = ($val == -1)? "" : $val ;
			}
			else if($key == 'P_CITY')
			{
				$this->m_bDppUpdate = true;
				$cityStateArr = explode(",",$val);
				$stateIndiaArr = FieldMap::getFieldLabel("state_india",'',1);
				foreach($cityStateArr as $k=>$v)
				{
					if(array_key_exists($v, $stateIndiaArr))
					{
						$stateArr[] =$v;
					}
					else
					{
						$cityArr[]= $v;
					}
					
				}
				if(is_array($cityArr))
				{
					foreach($cityArr as $key=>$value)
					{	
						if(!in_array(substr($value,0,2),$stateArr))
						{
							$cityString .= $value.",";
						}
					}
					$arrOut["P_CITY"] = rtrim($cityString,",");
				}
				else
				{
					$arrOut["P_CITY"] = "";
				}
				if(is_array($stateArr))
				{
					$arrOut['P_STATE'] = implode(",",$stateArr);	
				}
				else
				{
					$arrOut["P_STATE"] = "";
				}
					$arrOut['CITY_INDIA'] = NULL;
			}
			else if($key == "P_COUNTRY")
			{
				$this->m_bDppUpdate = true;
				if(strpos($val,'51') !== false)
				{
						$arrOut['CITY_INDIA'] = NULL;
				}
				$arrOut["P_COUNTRY"] = $val;
			}
			elseif($key == "P_OCCUPATION")
			{
				$this->m_bDppUpdate = true;
				$arrOut["P_OCCUPATION"] = $val;
				$arrOut["P_OCCUPATION_GROUPING"] = CommonFunction::getOccupationGroups($val); //this function was created to find occupation groups for values selected
			}
			elseif($key == "P_OCCUPATION_GROUPING")
			{
				$this->m_bDppUpdate = true;				
				$arrOut["P_OCCUPATION_GROUPING"] = $val;
				$arrOut["P_OCCUPATION"] = CommonFunction::getOccupationValues($val);	//this function was created to find occupation values for groups selected			
			}
			else
			{
				$arrOut[$key] = ($val == -1)? "" : $val ;
				$this->m_bDppUpdate = true;
			}
		}//End of For loop
		
		$request->setParameter("editFieldArr",$arrOut);
	}
}
?>
