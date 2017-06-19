<?php
/**
* This class will handle search validation result   
* @author Lavesh Rawat
*/
class SearchModuleInputValidate extends ValidationHandler
{
	private $response;
        public static $allowSomeValuesArr = array('All'=>1,'ALL'=>1,'O'=>1,'METRO'=>1,'DONT_MATTER'=>1,"NCR"=>1,SearchConfig::_nullValueAttributeLabel=>1,'41'=>1);

	public function getResponse()
	{
		return $this->response;
	}

	public function validatePopulateDefaultValues($request)
	{
		$this->response = ResponseHandlerConfig::$SUCCESS;
	}

	public static function validateMATCHALERTS_DATE_CLUSTER($value)
	{
                if(!$value)return true;

		$tempArr = FieldMap::getFieldLabel("matchAlertsDateClusters","",1);
		if(!$tempArr)
			return false;
		$arr = $tempArr + self::$allowSomeValuesArr;
		return self::valuesExistsInArr($value,$arr);
	}

	/**
	* This function will validate search forms of app.
	*/ 
 	public function validateSaveSearch($perform,$searchId)
   {
        $arr = SaveSearchMsgEnum::$arrperform;
        if(in_array($perform,$arr))
        {
		$notArr =array("listing","count");
           	if(!in_array($perform,$notArr) && $searchId == null)
           		$this->response = ResponseHandlerConfig::$FAILURE;
           	else
               $this->response = ResponseHandlerConfig::$SUCCESS;
        }
        else{
           $this->response = ResponseHandlerConfig::$FAILURE;
        }
    }
    public function validateSimilarProfile($request)
    {
   		$profileID = $request->getParameter("profilechecksum");
        	//$pid =  $profileObj->getPROFILEID();
        	//var_dump($profileID);die;
        	if($profileID)
        		$this->response = ResponseHandlerConfig::$SUCCESS;
        	else
            $this->response = ResponseHandlerConfig::$FAILURE;
       		
    }
	public function validateAppSearchForm($request)
	{
		$errorString="";
		$pass=TRUE;
		if(!self::validateGender($request->getParameter('gender')))
		{
			$errorString.=" Gender ".$request->getParameter('gender');
			$pass=FALSE;
		}
		if(!self::validateAge($request->getParameter('lage')))
		{
			$errorString.=" lage ".$request->getParameter('lage');
                        $pass=FALSE;
		}
		if(!self::validateAge($request->getParameter('hage')))
		{
                        $errorString.=" hage ".$request->getParameter('hage');
                        $pass=FALSE;
                }
		if(!self::validateHeight($request->getParameter('lheight')))
                {
                        $errorString.=" lheight ".$request->getParameter('lheight');
                        $pass=FALSE;
                }
                if(!self::validateHeight($request->getParameter('hheight')))
                {
                        $errorString.=" hheight ".$request->getParameter('hheight');
                        $pass=FALSE;
                }
		if(!self::validateIncome($request->getParameter('lincome')))
                {
                        $errorString.=" lincome ".$request->getParameter('lincome');
                        $pass=FALSE;
                }
                if(!self::validateIncome($request->getParameter('hincome')))
                {
                        $errorString.=" hincome ".$request->getParameter('hincome');
                        $pass=FALSE;
                }
		if(!self::validateReligion($request->getParameter('religion'),self::$allowSomeValuesArr))
		{
                        $errorString.=" religion ".$request->getParameter('religion');
                        $pass=FALSE;
                }
		if(!self::validateCaste($request->getParameter('caste'),self::$allowSomeValuesArr))
		{
                        $errorString.=" caste ".$request->getParameter('caste');
                        $pass=FALSE;
                }
		if(!self::validatePhoto($request->getParameter('photo')))
		{
                        $errorString.=" photo ".$request->getParameter('photo');
                        $pass=FALSE;
                }
		if(!self::validateMtongue($request->getParameter('mtongue'),self::$allowSomeValuesArr))
		{
                        $errorString.=" mtongue ".$request->getParameter('mtongue');
                        $pass=FALSE;
                }
		if(!self::validateLocation($request->getParameter('location')))
		{
                        $errorString.=" location ".$request->getParameter('location');
                        $pass=FALSE;
                }
		if(!self::validateSearchId($request->getParameter('searchId')))
		{
                        $errorString.=" searchId ".$request->getParameter('searchId');
                        $pass=FALSE;
                }
		if(!self::validateSortingOrder($request->getParameter('sort_logic')))
		{
                        $errorString.=" sort_logic ".$request->getParameter('sort_logic');
                        $pass=FALSE;
                }
		if(!self::validateAppCluster($request->getParameter('appCluster')))
		{
                        $errorString.="  appCluster".$request->getParameter('appCluster');
                        $pass=FALSE;
                }
		if(!self::validateAppClusterVal($request->getParameter('appClusterVal'),$request->getParameter('appCluster')))
		{
                        $errorString.="  appClusterVal".$request->getParameter('appClusterVal')." for cluster->".$request->getParameter('appCluster');
                        $pass=FALSE;
                }
		if(!self::validateAddRemoveCluster($request->getParameter('addRemoveCluster')))
		{
                        $errorString.="  addRemoveCluster".$request->getParameter('addRemoveCluster');
                        $pass=FALSE;
                }
		if($request->getParameter('partnermatches'))
		{
			$value = $request->getParameter('partnermatches');
			if($value && !is_numeric($value))
			{
                        	$errorString.=" partnermatches ".$request->getParameter('partnermatches');
	                        $pass=FALSE;
			}	
		}
		if($pass)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString = "Search Input Validation Failed:".$errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("",$errorString);
		}
	}

        /**
        * This function checks if value is an accpetable value for searchId
        */
        static public function validateSortingOrder($value)
        {
		if(!$value)
			return true;
		$refl = new ReflectionClass('SearchSortTypesEnums');
		$arr = $refl->getConstants();
                return self::valuesExistsInArr($value,$arr,1);
	}

	static public function validateAppCluster($value)
	{
		if(!$value)
			return true;
		return self::valuesExistsInArr($value,FieldMap::getFieldLabel("solr_clusters","",1));
	}

	static public function validateAppClusterVal($value,$value2)
	{
		if(!$value || !$value2)
			return true;

		if(strstr($value,'$'))
		//sliders
		{
			$t = explode("$",$value);
			foreach($t as $k=>$v)
				if($v && !is_numeric($v))
					return false;	
			return true;
		}
		else
		{
			$tempArr = FieldMap::getFieldLabel("solr_clusters","",1);
        
			$value2 = $tempArr[$value2];

			if($value2=='OCCUPATION_GROUPING')
			{
				$value2 = 'OCCUPATION';
				$value = str_replace('@','',$value);
                                $mergeForKeyArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation","",1);
                                $mergeForKeyArr = array_keys($mergeForKeyArr);
			}
			elseif($value2=='EDUCATION_GROUPING')
			{
				$value2 = 'EDU_LEVEL_NEW';
				$value = str_replace('@','',$value);
			}
			$fieldMapArrayLabelMapping = searchConfig::fieldMapArrayLabelMapping();
			$clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$value2];
			$tempArr = FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1);
                        
			if(!$tempArr)
				return false;
			$arr = $tempArr + self::$allowSomeValuesArr;
                        if($mergeForKeyArr)
                                   $arr = array_merge ($mergeForKeyArr,$arr);
			return self::valuesExistsInArr($value,$arr);
		}
	}

        /**
        * This function checks if value is an accpetable value for searchId
        */
        static public function validateSearchId($value)
        {
		if(is_numeric($value) || !$value)
			return true;
		return false;
        }
        /**
        * This function checks if value is an accpetable value for searchId
        */
        static public function validateAddRemoveCluster($value)
        {
		if(!$value || is_numeric($value))
			return true;
		return false;
	}

        /**
        * This function checks if value is an accpetable value for location(country/city)
        */
        static public function validateLocation($value)
        {
                if(!$value)
			return true;
		$arr = self::$allowSomeValuesArr + FieldMap::getFieldLabel("country","",1)+FieldMap::getFieldLabel("city","",1)+FieldMap::getFieldLabel("state_india","","true");
                return self::valuesExistsInArr($value,$arr);
        }

	/*
        This function validates the POST parameters for /search/searchFormData url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateSearchFormData($request)
        {
                $pattern1 = "/^([a-zA-Z])+$/";
                $pattern2 = "/^([0-9 :-])+$/";
                $param = json_decode($request->getParameter("json"),true);
                if($param && is_array($param))
                {
                        foreach($param as $k=>$v)
                        {
                                if(!preg_match($pattern1,$k) || !$v || !preg_match($pattern2,$v))
                                {
                                        $errorString = "search/validation/SearchModuleInputValidate.class.php : Reason ($k.':'.$v)";
                                        ValidationHandler::getValidationHandler("",$errorString);

                                        $resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
                                        break;
                                }
                        }
                }
                else
                {
			$errorString = "search/validation/SearchModuleInputValidate.class.php : Reason (no params)";
			ValidationHandler::getValidationHandler("",$errorString);
                        $resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
                }
                if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
        }
}
?>
