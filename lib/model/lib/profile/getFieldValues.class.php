<?php
/**
 * CLASS getfieldValues
 * this class will be used to get values for registration based on list 
 * of fields passed 
 */

class getFieldValues {
  
  public $staticFields;
/*
   * Function to get values of fields present on a page
   * @FieldsList - this variables fetches list of all fields for a page
   * @page -  this contains page id  
   * @return - array of all fields in a json format
   */
  public function getListValues($FieldsList,$page) {
    $this->getFieldsFromMap($page);
    foreach($FieldsList as $field) {
      $dataArray[$field] = $this->getFieldDataVal($field);
    }
    return json_encode($dataArray,JSON_FORCE_OBJECT);
  }
  
  /*
   * Function to get array formatted for a particular field
   * @field - field name for which data is to be fetched
   * @return - array for a particular field
   */
  private function getFieldDataVal($field) {
    switch($field) {
      case "gender" : $fieldArr = RegistrationEnums::$defaultFieldVal["gender"];
      break;
      case "cpf" : $fieldArr = $this->orderCpf();
      break;
      case "dob" : $fieldArr = RegistrationEnums::$defaultFieldVal["dob"];
      break;
      case "month" : $fieldArr = RegistrationEnums::$defaultFieldVal["month"];
      break;
      case "mtongue" : $fieldArr = $this->getMtongueValues();
      break;
      case "religion" : $fieldArr = $this->staticFields["religion"];
      break;
      case "caste" : $fieldArr[0] = $this->religionBasedCaste();
      break;
      case "subcaste" : $fieldArr = $this->getMtongueValues();
      break;
      case "manglik" : $fieldArr = RegistrationEnums:: $defaultFieldVal["manglik"];
      break;
      case "mstatus_muslim" : $fieldArr = $this->staticFields["mstatus"];
      break;
      case "mstatus" : $fieldArr = $this->getMstatusNonMuslimValues();
      break;
      case "haveChildren" : $fieldArr = RegistrationEnums::$defaultFieldVal["haveChildren"];
      break;
      case "height" : $fieldArr = $this->orderHeightValues();
      break;
      case "city" : $fieldArr = $this->getCityValues();
      break;
      case "country" : $fieldArr = $this->getCountryValues();
      break;
      case "hdegree" : $fieldArr = $this->orderEduLevelNew();
      break;
      case "ugDegree" : $fieldArr = $this->staticFields["degree_ug"];
      break;
      case "pgDegree" : $fieldArr = $this->staticFields["degree_pg"];
      break;
      case "occupation" : $fieldArr = $this->orderForLabelGrid("occupation");
      break;
      case "income" : $fieldArr = $this->orderIncome();
      break;
      case "diet" : $fieldArr = $this->orderForLabelRadio("diet");
      break;
      case "drink" : $fieldArr = $this->orderForLabelRadio("drink");
      break;
      case "smoke" : $fieldArr = $this->orderForLabelRadio("drink");
      break;
      case "familyType" : $fieldArr = $this->orderForLabelRadio("family_type");
      break;
      case "brother" : $fieldArr = $this->orderForSibling();
      break;
      case "sister" : $fieldArr = $this->orderForSibling();
      break;
      case "fatherOccupation": $fieldArr = $this->orderForLabelGrid("family_background");
      break;
      case "motherOccupation": $fieldArr = $this->orderForLabelGrid("mother_occupation");
      break;
      case "familyState": $fieldArr = $this->orderForLabelGrid("state_india");
      break;
      case "familyCity": $fieldArr = $this->orderFamilyCity();
      break;
      case "countryReg" : $fieldArr = $this->getCountryValuesForInUsa();
      break;
      case "stateReg" : $fieldArr = $this->orderForLabelGrid("state_india");
      break;
      case "cityReg" : $fieldArr = $this->orderFamilyCity(1);
      break;
      case "horoscopeMatch" : $fieldArr = $this->orderForLabelRadio("horoscope_match");
      break;
    }
    return $fieldArr;
  }
  /*
  * function to order data for siblings
   */
private function orderForSibling()
{
        $labelArr = FieldMap::getFieldLabel("sibling",'',1);
        foreach($labelArr as $k=>$v)
	{
		if($k=="0")
			$finalArr[]=array($k=>"None");
		else
			$finalArr[]=array($k=>$v);
	}
        return $finalArr;
}
  /*
   * Function to get values from FieldMap and change format for diet
   * @page -  this contains page id  
   */
private function orderForLabelRadio($label)
{
	$labelArr = FieldMap::getFieldLabel($label,'',1);
	foreach($labelArr as $k=>$v)
		$finalArr[]=array($k=>$v);
	return $finalArr;
}

  /*
   * Function to get values from FieldMap and change format for occupation
   * @page -  this contains page id  
   */
private function orderForLabelGrid($label)
{
	$labelArr = FieldMap::getFieldLabel($label,'',1);
	foreach($labelArr as $k=>$v)
		$finalArr[]=array($k=>$v);
	$finalArray[]=$finalArr;
	return $finalArray;
}
  /*
   * Function to get values from FieldMap and change format for income
   * @page -  this contains page id  
   */
  private function orderIncome()
  {
	$incomeArr = FieldMap::getFieldLabel("income_level",'',1);
	$incomeMap =FieldMap::getFieldLabel("income_grouping_mapping",'',1);
	$loginProfile = LoggedInProfile::getInstance();
        $country=$loginProfile->getCOUNTRY_RES();
	$incomeGroup = ($country=="51")?1:2;
	$incomeMapArr = explode(",",$incomeMap[$incomeGroup]);
	foreach($incomeMapArr as $k=>$v)
		$finalArr[]=array($v=>$incomeArr[$v]);
	$finalArray[]=$finalArr;
	return $finalArray;

  }

  /*
   * Function to get values from FieldMap and change format for education
   * @page -  this contains page id  
   */
  private function orderEduLevelNew()
  {
	$eduSubList  = FieldMap::getFieldLabel("education",'',1);
	$eduGroup  = FieldMap::getFieldLabel("education_grouping",'',1);
	$eduSubMap  = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",'',1);
	$fieldArr = $this->staticFields["edu_level_new"];
	foreach($eduSubMap as $k=>$v)
	{
		$subListArr = explode(",",$v);
		foreach($subListArr as $ks=>$vs)	
		{
			$subListing[$ks]=array($vs=>$eduSubList[$vs]);
		}
		$dataArr[$eduGroup[$k]]=$subListing;
		unset($subListing);
	}
	foreach($eduGroup as $k=>$v)
	{
		$finalArr[$v] = $dataArr[$v];
	}
	unset($dataArr);
	$finalArray[]=$finalArr;
	return $finalArray;
  }
 
  /*
   * Function to get values from static action which itself fetches them from fieldMap file
   * @page -  this contains page id  
   */
  private function getFieldsFromMap($page) {
    $request = sfContext::getInstance()->getRequest();
    $request->setParameter("l",RegistrationEnums::$fieldsToFetchData[$page]);
    $request->setParameter("actionCall","1");
    ob_start();
    $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("static","getFieldData");
    $this->staticFields = json_decode(ob_get_contents(),true);
    ob_end_clean();
  }
  /*
   * Function to fromat and order height values in desired order
   */
  private function orderHeightValues() {
    $c=0;
    for($x=0;$x<=11;$x++) {
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x];
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x+12];
      $heightOrdered[0][$c++] = $this->staticFields["height_json"][0][$x+24];
    }
    $heightOrdered[0][$c] = $this->staticFields["height_json"][0][36];
    return $heightOrdered;
  }  
  /*
   * Function to fromat and order create profile for values in desired order
   */
  private function orderCpf() {
    $cpfOrdered = $this->staticFields["relationship_reg"][0];
    $cpfOrdered[3] = $this->staticFields["relationship_reg"][0][5];
    $cpfOrdered[5] = array("4"=>"Other");
    $cpfOrdered[6] = $this->staticFields["relationship_reg"][0][3];
    $cpfOrdered[7] = $this->staticFields["relationship_reg"][0][6];
    return $cpfOrdered;
  }
  
  /*
   * Function to get values of mtongue
   * @return - array of values of mtongue
   */
  private function getMtongueValues() {
    $mregion=FieldMap::getFieldLabel("mtongue_region_label",'',1);
    $mtongueArr=FieldMap::getFieldLabel("community_small",'',1);
    foreach($mregion as $key=>$val) {
        $i=0;
        $mtongueregion=FieldMap::getFieldLabel("mtongue_region",'',1);
        $arr=explode(",",$mtongueregion[$key]);
        foreach($arr as $kk=>$vv)
        {
                $Arr[0][$val][$i]=array($vv=>$mtongueArr[$vv]);
                $i++;
        }
    }
    return $Arr;
  }
  /*
   * Function to get values of mstatus for non muslim values
   * @return - array
   */
  private function getMstatusNonMuslimValues() {
      $tempStatic = $this->staticFields["mstatus"];
    foreach($this->staticFields["mstatus"][0] as $key=>$val) {
      foreach($val as $kk=>$v){
        if($kk =="M")
          unset($tempStatic[0][$key]);
      }
    }
    return $tempStatic;
  }
  
  /*
   * Function to get caste values directly from field map with desired formatting
   * @return - array of values for desired caste
   */
  private function getCasteValues($szKey) {
  
	$arrKey = explode('_',$szKey);
        $szField =  $arrKey[1];
        $szCasteValue = $arrKey[2];
        $szDependantValue = $arrKey[3];
        $request=sfContext::getInstance()->getRequest();
        $mtongue = null;
        if($szCasteValue == 1)
                $mtongue = $szDependantValue;//$request->getParameter("m");
        $this->fObj = new FieldOrder;
        $this->fObj->setDefault("impcaste",array(),"","");
        $this->fObj->setDefaultExist(1);
        $this->fObj->UpdateSelect();
        $impCasteJSon = $this->fObj->getJson();
        //print_r($impCasteJSon);
        $this->fObj=null;
        $this->fObj=new FieldOrder;
        $this->fObj->setDefault("caste",array($szCasteValue),"","");
        $this->fObj->UpdateSelect();
        $CasteJSon = $this->fObj->getJson();
        
        unset($CasteJSon[0]);
        $newJson = array();	

        $cnt = 0;
        if($impCasteJSon && $CasteJSon)
        {
                foreach($impCasteJSon as $key=>$val)
                {
                        if($val[0]==$mtongue)
                        {
                                $newJson["first"]["blank"][]=array($val[1]=>$val[3]);
                                $cnt++;
                        }
                }	
                foreach($CasteJSon as $key=>$val)
                {				
                        $newJson["alpha"][]=array($val[0]=>$val[1]);
                        $cnt++;
                }
                if(!empty ($newJson["first"])){
                  $newJson = array_merge($newJson["first"],$this->getAlphabeticalList($newJson["alpha"]));
                }
                else {
                  $newJson = $this->getAlphabeticalList($newJson["alpha"]);  
                }
                return $newJson;
        }
  }
  /*
   * Function to get values of fields present on a page
   * @FieldsList - this variables fetches list of all fields for a page
   * @page -  this contains page id  
   */
  private function religionBasedCaste() {
    $mtongueValues = $this->getMtongueValues();
    foreach($mtongueValues[0] as $key => $val) {
      foreach($val as $k=>$v){
        foreach($v as $kk => $vv){
          $arr["Hindu_".$kk] = $this->getCasteValues("reg_caste_1_".$kk);
        }
      }
    }
    $arr["Hindu"] = $this->getCasteValues("reg_caste_1_");
    $arr["Muslim"] = $this->getCasteValues("reg_caste_2_");
    $arr["Christian"] = $this->getCasteValues("reg_caste_3_");
    $arr["Sikh"] = $this->getCasteValues("reg_caste_4_");
    $arr["Jain"] = $this->getCasteValues("reg_caste_9_");
    return $arr;
  }

  /*
   * Function to get values from FieldMap and change format for familyCity
   * @page -  this contains page id  
   */
private function orderFamilyCity($addUsa='')
{
                $arrCity=FieldMap::getFieldLabel("city_india",'',1);

                ksort($arrCity);
                $arrFinalOut = array();
                foreach($arrCity as $key=>$val)
                {
                        if(strlen($key)===2)
                        {
				asort($arrFinalOut[$currentKey]);
                                $currentKey = $key;
                                $arrFinalOut[$currentKey] = array();
                        }
                        else
                        {
                                $arrFinalOut[$currentKey][$key] = $val;
                        }
                }
		asort($arrFinalOut[$currentKey]);
	foreach($arrFinalOut as $k=>$v)
	{
		foreach($v as $kx=>$vx)
		{
			$returnArr[$k][0][]=array($kx=>$vx);
		}
                //if($addUsa)
                    $returnArr[$k][0][] = array('0'=>'Others');
	}
        if($addUsa){
            $returnArr['128'] = $this->getCityValuesForInUsa('',1)[0]['United States'];
        }
	return $returnArr;
}
/*
   * get city values from field map
   */
  private function getCityValuesForInUsa($partnerCity = "",$onlyUsa='')
  {
    $countryIds = array(
              51=>array('name'=>'India','city_index'=>'india'),
              128=>array('name'=>'United States','city_index'=>'usa')
    ); // country id list
    if($onlyUsa)
        $countryIds = array(
              128=>array('name'=>'United States','city_index'=>'usa')
        );
    foreach($countryIds as $countryId=>$countryData){
      $countryName = $countryData['name'];
      $Arr[$countryId][0]=Array();
      if(isset($countryData['city_index'])){
        $cities = FieldMap::getFieldLabel("city_".$countryData['city_index'],'',1);
        $tempArray = FieldMap::getFieldLabel("top".$countryData['city_index']."_city", '', 1);
        if($countryId == "128")
          $cities["0"] = "Others";
        $Arr[$countryId][2]=$cities;
        if($tempArray){
          $j = 0;
          foreach ($tempArray as $key => $val) {
            $temp = explode(",", $val);
            foreach ($temp as $key => $val) {
              $topCity[$j][$val] = $cities[$val];
              $j++;
            }
           }
           $output[0][$countryName]["blank"] = $topCity;
        }
      }
      $formattedArr = $this->getAlphabeticalList($Arr[$countryId]);
      foreach($formattedArr as $key=>$val)
      {
        $output[0][$countryName][$key]=$val;
      }
    }
	  return $output;		
  }
  /*
   * get city values from field map
   */
  private function getCityValues($partnerCity = "") {
    $tempArray = FieldMap::getFieldLabel("topindia_city", '', 1);

    $state = FieldMap::getFieldLabel("state_india", '', 1);
    $Arr[51][0] = Array();
    $cityIndia = FieldMap::getFieldLabel("city_india", '', 1);
    foreach ($state as $key => $value) {
      unset($cityIndia[$key]);
    }
    $Arr[51][2] = $cityIndia;
    unset($state);
    if (!$partnerCity) {
      $j = 0;
      foreach ($tempArray as $key => $val) {
        $temp = explode(",", $val);
        foreach ($temp as $key => $val) {
          $topIndia[$j][$val] = $cityIndia[$val];
          $j++;
        }
      }
      $output[0]["blank"] = $topIndia;
    } else {
      unset($Arr);
      $Arr[51][0] = $cityIndia;
    }
    $i = 0;
    $formattedArr = $this->getAlphabeticalList($Arr[51]);
    foreach ($formattedArr as $key => $val) {
      $output[0][$key] = $val;
    }
    return $output;
  }

  /*
   * get country values from field map
   */
  private function getCountryValues()
  {
    $Arr[0]=FieldMap::getFieldLabel("impcountry",'',1);
    $Arr[2]=FieldMap::getFieldLabel("country",'',1);
    foreach($Arr[0] as $k=>$v){
      if($k != '51')
        $output["first"]["blank"][]=array($k=>$v);
    }
    foreach($Arr[2] as $k=>$v){
      if($k != '51')
        $output["alpha"][]=array($k=>$v);
    }
    $newJson[0] = array_merge($output["first"],$this->getAlphabeticalList($output["alpha"]));
    return $newJson;
  }
  /*
   * get country values from field map for USA and India
   */
  private function getCountryValuesForInUsa()
  {
    $Arr[0]=FieldMap::getFieldLabel("impcountry",'',1);
    $Arr[2]=FieldMap::getFieldLabel("country",'',1);
    foreach($Arr[0] as $k=>$v){
        $output["first"]["blank"][]=array($k=>$v);
    }
    foreach($Arr[2] as $k=>$v){
        $output["alpha"][]=array($k=>$v);
    }
    $newJson[0] = array_merge($output["first"],$this->getAlphabeticalList($output["alpha"]));
    return $newJson;
  }
  /*
   * Common Function to get values of fields firmatted in a array with alphabets as headings
   * @arr - array to be formatted in alphabetical order
   * @return - array which has been formatted
   */
  private function getAlphabeticalList($arr){
    $currentAlpha = "";
    $i=0;
    foreach($arr as $key=>$val){
      foreach($val as $k=>$v) {
        if($v == "Others" || $v=="Other" || $v=="other" || $v=="others"){
          $output["Others"][$i]= array($k=>$v);
          continue;
        }
        else if($v[0] == $currentAlpha) {
	  $output[$currentAlpha][$i]= array($k=>$v);		
        }
        else {
          $currentAlpha = $v[0];
          $output[$currentAlpha][$i] = array($k=>$v);
        }
        $i++;
      }
    }
    return $output;
  }
}
