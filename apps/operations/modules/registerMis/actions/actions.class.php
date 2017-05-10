<?php

/**
 * registerMis actions.
 *
 * @package    jeevansathi
 * @subpackage registerMis
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class registerMisActions extends sfActions {
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request) {
    // $this->forward('default', 'module');
  }

  /**
   * Executes communitywise action
   *
   * @param sfRequest $request A request object
   */

  public function executeCommunitywiseRegistration(sfWebRequest $request)
  {
    $preDefArray = array('screened_SIC' => 0,'SCREENED_CC' => 0,'OTHERS_COMMUNITY' => 0,'M26MVCC' => 0,'F22MVCC' => 0);
    $formArr = $request->getParameterHolder()->getAll();
    $name = $request->getAttribute('name');

    $qualityMis_top_cities = FieldMap::getFieldLabel("qualityMis_top_cities","",1);

    $this->cid = $formArr['cid'];
    if ($formArr['submit']) 
    {
      $commonUtilObj = new CommonUtility();
      $commonUtilObj->avoidPageRefresh("COMMUNITY_REGISTRATION", $name);
      $this->range_format = $formArr['range_format'];
      $params = array('range_format' => $formArr["range_format"]);
      if ($formArr["range_format"] == "Y") {      //If year is selected
        $start_date = $formArr['yearValue'] . "-04-01";
        $end_date = ($formArr['yearValue']+1) . "-03-31";
        $this->columnDates = RegistrationMisEnums::$columnDates;
        $this->displayDate = $formArr['yearValue'];
      } else {
        $formArr["date1_dateLists_month_list"] ++;
        $formArr["date2_dateLists_month_list"] ++;

  if(strlen($formArr["date1_dateLists_day_list"])==1)
                $formArr["date1_dateLists_day_list"] = "0".$formArr["date1_dateLists_day_list"];
        if(strlen($formArr["date2_dateLists_day_list"])==1)
                $formArr["date2_dateLists_day_list"] = "0".$formArr["date2_dateLists_day_list"];
  if(strlen($formArr["date1_dateLists_month_list"])==1)
                $formArr["date1_dateLists_month_list"] = "0".$formArr["date1_dateLists_month_list"];
        if(strlen($formArr["date2_dateLists_month_list"])==1)
                $formArr["date2_dateLists_month_list"] = "0".$formArr["date2_dateLists_month_list"];

        $start_date = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
        $end_date = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
        $this->verifyDates($start_date,$end_date);
        $this->columnDates = $this->GetDays($start_date, $end_date);
        $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
      }
      if($this->errorMsg == ''){
        $regQualityObj = new REGISTRATION_QUALITY('newjs_masterRep');
        $params['start_date'] = $start_date;
        $params['end_date'] = $end_date;
        $this->registrationData = $regQualityObj->getRegisrationData($params); 
        $sourceCategoryMapping = new SOURCE_CATEGORY_MAPPING('newjs_masterRep');
       
        $sourceCategory = ($sourceCategoryMapping->getSourceCategory());

        $this->sourceCategoryKey = array();

        foreach ($sourceCategory as $value) {
          if ( $value['SOURCE_GROUP'] != '' && $value['SOURCE_GROUP'] != NULL)
          {
            $this->sourceCategoryKey[$value['SOURCE_GROUP']] = ($value['SOURCE_CATEGORY']);
          }
          else 
          {
            $this->sourceCategoryKey[$value['SOURCE_ID']] = ($value['SOURCE_CATEGORY']);
          }
        }

        $this->sourceCategoryKey = array_change_key_case($this->sourceCategoryKey, CASE_LOWER);
      

        $this->dateRegistrationData = array();
        $preValue = array();
        foreach ($this->registrationData['source_data'] as $value) {
          
          if (!array_key_exists($value['REG_DATE'],$this->dateRegistrationData))
          {
            $this->dateRegistrationData[$value['REG_DATE']] = array();
          }
          if(array_key_exists(strtolower($value['GROUPNAME']),$this->sourceCategoryKey )){
             $value['GROUPNAME'] = $this->sourceCategoryKey[strtolower($value['GROUPNAME'])];
          }
          else if(array_key_exists(strtolower($value['SOURCEID']),$this->sourceCategoryKey )){
              $value['GROUPNAME'] = $this->sourceCategoryKey[strtolower($value['SOURCEID'])];
          }else{
              $value['GROUPNAME'] = "Direct PC";
          }
          if (!array_key_exists($value['GROUPNAME'],$this->dateRegistrationData[$value['REG_DATE']]))
          {
            $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']] = array();
          }
          if(!$value['SOURCECITY'])
            $value['SOURCECITY'] = "Others";

          if($value['SOURCECITY'] != "Others")
          {
            if ( in_array($value['SOURCECITY'], $qualityMis_top_cities))
              $value['SOURCECITY'] = FieldMap::getFieldLabel("city_india",$value['SOURCECITY']);
            else
              $value['SOURCECITY'] = "Others";
            if(!$value['SOURCECITY'])
              $value['SOURCECITY'] = "Others";
          }

          if (!array_key_exists($value['SOURCECITY'],$this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']]))
          {
            $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']] = $preDefArray;
          }
          $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']]['screened_SIC'] += $value['SCREENED_SIC'];
          $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']]['screened_CC'] += $value['SCREENED_CC'];
          $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']]['OTHERS_COMMUNITY'] += $value['OTHERS_COMMUNITY'];
          $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']]['M26MVCC'] += $value['M26MVCC'];
          $this->dateRegistrationData[$value['REG_DATE']][$value['GROUPNAME']][$value['SOURCECITY']]['F22MVCC'] += $value['F22MVCC'];
        }
        krsort($this->dateRegistrationData);
        if ($formArr['report_format'] == 'CSV') {
          $this->createCSVFormatCommunitywise($this->dateRegistrationData);
        }

        $this->setTemplate('communitywiseRegistration');
      }
      else
      {
        $this->communitywiseRegistration = true;
        // $this->source_cities = $this->setSourceCities();
        $this->startMonthDate = "01";
        $this->todayDate = date("d");
        $this->todayMonth = date("m");
        $this->todayYear = date("Y");
        $this->rangeYear = date("Y");
        $this->dateArr = GetDateArrays::getDayArray();
        $this->yearArr = array();
        // $sourceObj = new MIS_SOURCE('newjs_slave');
        // $this->sources = $sourceObj->getSourceList(); // get source names for dropdown
        $dateArr = GetDateArrays::generateDateDataForRange('2015', ($this->todayYear));
        foreach (array_keys($dateArr) as $key => $value) {
          $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
        }
        $this->setTemplate('qualityRegistration');
      }
    }
    else
    {

      $this->communitywiseRegistration = true;
      // $this->source_cities = $this->setSourceCities();
      $this->startMonthDate = "01";
      $this->todayDate = date("d");
      $this->todayMonth = date("m");
      $this->todayYear = date("Y");
      $this->rangeYear = date("Y");
      $this->dateArr = GetDateArrays::getDayArray();
      $this->yearArr = array();
      // $sourceObj = new MIS_SOURCE('newjs_slave');
      // $this->sources = $sourceObj->getSourceList(); // get source names for dropdown
      $dateArr = GetDateArrays::generateDateDataForRange('2015', ($this->todayYear));
      foreach (array_keys($dateArr) as $key => $value) {
        $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
      }
      $this->setTemplate('qualityRegistration');
    }
  }

  public function executeQualityRegistration(sfWebRequest $request) {
    $formArr = $request->getParameterHolder()->getAll();
    $name = $request->getAttribute('name');
    $this->cid = $formArr['cid'];
    if ($formArr['submit']) {
      ini_set('memory_limit','512M');
      $commonUtilObj = new CommonUtility();
      $commonUtilObj->avoidPageRefresh("QUALITY_REGISTRATION", $name);
      $this->range_format = $formArr['range_format'];
      $params = array('range_format' => $formArr["range_format"], 'source_names' => $formArr['source_names'],'source_cities'=>$formArr['source_cities']);
      if ($formArr["range_format"] == "Y") {      //If year is selected
        $start_date = $formArr['yearValue'] . "-04-01";
        $end_date = ($formArr['yearValue']+1) . "-03-31";
        $this->columnDates = RegistrationMisEnums::$columnDates;
        $this->displayDate = $formArr['yearValue'];
      } else {
        $formArr["date1_dateLists_month_list"] ++;
        $formArr["date2_dateLists_month_list"] ++;

	if(strlen($formArr["date1_dateLists_day_list"])==1)
                $formArr["date1_dateLists_day_list"] = "0".$formArr["date1_dateLists_day_list"];
        if(strlen($formArr["date2_dateLists_day_list"])==1)
                $formArr["date2_dateLists_day_list"] = "0".$formArr["date2_dateLists_day_list"];
	if(strlen($formArr["date1_dateLists_month_list"])==1)
                $formArr["date1_dateLists_month_list"] = "0".$formArr["date1_dateLists_month_list"];
        if(strlen($formArr["date2_dateLists_month_list"])==1)
                $formArr["date2_dateLists_month_list"] = "0".$formArr["date2_dateLists_month_list"];

        $start_date = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
        $end_date = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
        $this->verifyDates($start_date,$end_date);
        $this->columnDates = $this->GetDays($start_date, $end_date);
        $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
      }
      if($this->errorMsg == ''){
        $regQualityObj = new REGISTRATION_QUALITY('newjs_masterRep');
        $params['start_date'] = $start_date;
        $params['end_date'] = $end_date;
        $registrationData = $regQualityObj->getRegisrationData($params);  
        if (!empty($registrationData)) {
          $profilesArr = array();
          $sourceGroups = array();
          $sArray = array();
          $i = 1;
          foreach ($registrationData['source_data'] as $key => $groupData) {
            if ($keyVal = array_search($groupData['GROUPNAME'], $sArray)) {
            } else {
              if($i <=3 && $formArr['range_format'] == 'Y'){ //if key value is jan,feb or march then replace there numeric value 1,2,3 by 13,14,15
                $keyVal = $i + 12;
              }else{
                $keyVal = $i;
              }
              if($i == 12){
                      $keyVal = $i = 16;
              }
              $sArray[$keyVal] = $groupData['GROUPNAME'];
              $i++;
            }
            if($groupData['REG_DATE'] <=3 && $formArr['range_format'] == 'Y'){ //if Registration month is jan,feb or march then replace there numeric value 1,2,3 by 13,14,15
              $groupData['REG_DATE'] = $groupData['REG_DATE'] + 12;
            }
            
            // Block to be added for total sum of all source groups at the top
            $sourceGroups[0]['group_data']['is_grp'] = 1;
            $sourceGroups[0]['group_data']['groupName'] = 'Total Registrations';
            $sourceGroups[0]['group_data']['TOTAL_REG'][$groupData['REG_DATE']] += $groupData['TOTAL_REG'];
            $sourceGroups[0]['group_data']['F22'][$groupData['REG_DATE']] += $groupData['F22'];
            $sourceGroups[0]['group_data']['F22MV'][$groupData['REG_DATE']] += $groupData['F22MV'];
            $sourceGroups[0]['group_data']['F22MVCC'][$groupData['REG_DATE']] += $groupData['F22MVCC'];
            $sourceGroups[0]['group_data']['M26'][$groupData['REG_DATE']] += $groupData['M26'];
            $sourceGroups[0]['group_data']['M26MV'][$groupData['REG_DATE']] += $groupData['M26MV'];
            $sourceGroups[0]['group_data']['M26MVCC'][$groupData['REG_DATE']] += $groupData['M26MVCC'];
            $sourceGroups[0]['group_data']['SCREENED_SIC'][$groupData['REG_DATE']] += $groupData['SCREENED_SIC'];
            
            // insert source name data
            $sourceGroups[$keyVal]['group_data']['is_grp'] = 1;
            $sourceGroups[$keyVal]['group_data']['groupName'] = $groupData['GROUPNAME'] == '' ? 'BlankSourceGroup' : $groupData['GROUPNAME'];
            $sourceGroups[$keyVal]['group_data']['TOTAL_REG'][$groupData['REG_DATE']] += $groupData['TOTAL_REG'];
            $sourceGroups[$keyVal]['group_data']['F22'][$groupData['REG_DATE']] += $groupData['F22'];
            $sourceGroups[$keyVal]['group_data']['F22MV'][$groupData['REG_DATE']] += $groupData['F22MV'];
            $sourceGroups[$keyVal]['group_data']['F22MVCC'][$groupData['REG_DATE']] += $groupData['F22MVCC'];
            $sourceGroups[$keyVal]['group_data']['M26'][$groupData['REG_DATE']] += $groupData['M26'];
            $sourceGroups[$keyVal]['group_data']['M26MV'][$groupData['REG_DATE']] += $groupData['M26MV'];
            $sourceGroups[$keyVal]['group_data']['M26MVCC'][$groupData['REG_DATE']] += $groupData['M26MVCC'];
            $sourceGroups[$keyVal]['group_data']['SCREENED_SIC'][$groupData['REG_DATE']] += $groupData['SCREENED_SIC'];
            if($formArr['source_names']){
              // insert source id data
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['groupName'] = strtolower($groupData['SOURCEID']) == 'blanksourcegroup' ? 'BlankSourceId' : $groupData['SOURCEID'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['TOTAL_REG'][$groupData['REG_DATE']] += $groupData['TOTAL_REG'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['F22'][$groupData['REG_DATE']] += $groupData['F22'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['F22MV'][$groupData['REG_DATE']] += $groupData['F22MV'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['F22MVCC'][$groupData['REG_DATE']] += $groupData['F22MVCC'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['M26'][$groupData['REG_DATE']] += $groupData['M26'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['M26MV'][$groupData['REG_DATE']] += $groupData['M26MV'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['M26MVCC'][$groupData['REG_DATE']] += $groupData['M26MVCC'];
              $sourceGroups[$keyVal][$groupData['SOURCEID']]['SCREENED_SIC'][$groupData['REG_DATE']] += $groupData['SCREENED_SIC'];
            }
          }
        } else {
          $sourceGroups = array();
        }
        $this->selectedCities = "";
        if(!empty($params["source_cities"])){
                $cities_arr=array();
                foreach($params["source_cities"] as $selCity){
                        $cities_arr[] = FieldMap::getFieldLabel("city_india",$selCity);
                }
                $this->selectedCities = implode(",",$cities_arr);
        }
        if ($formArr['report_format'] == 'CSV') {
          $this->createCSVFormatOutput($sourceGroups, $registrationData['source_dates'], $this->columnDates, $this->displayDate, $this->range_format,$this->selectedCities);
        }
        $this->sgroupData = $sourceGroups;
        $this->dates_count = $registrationData['source_dates'];
        $this->setTemplate('qualityRegistrationResultScreen');
      }else{
        $this->source_cities = $this->setSourceCities();
        $this->startMonthDate = "01";
        $this->todayDate = date("d");
        $this->todayMonth = date("m");
        $this->todayYear = date("Y");
        $this->rangeYear = date("Y");
        $this->dateArr = GetDateArrays::getDayArray();
        $this->yearArr = array();
        $sourceObj = new MIS_SOURCE('newjs_slave');
        $this->sources = $sourceObj->getSourceList(); // get source names for dropdown
        $dateArr = GetDateArrays::generateDateDataForRange('2014', ($this->todayYear));
        foreach (array_keys($dateArr) as $key => $value) {
          $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
        }
      }
    } else {// for selection screen
      $this->source_cities = $this->setSourceCities();
      $this->startMonthDate = "01";
      $this->todayDate = date("d");
      $this->todayMonth = date("m");
      $this->todayYear = date("Y");
      $this->rangeYear = date("Y");
      $this->dateArr = GetDateArrays::getDayArray();
      $this->yearArr = array();
      $sourceObj = new MIS_SOURCE('newjs_slave');
      $this->sources = $sourceObj->getSourceList(); // get source names for dropdown
      $dateArr = GetDateArrays::generateDateDataForRange('2014', ($this->todayYear));
      foreach (array_keys($dateArr) as $key => $value) {
        $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
      }
    }
  }
 public function setSourceCities(){
      $sourceCities = FieldMap::getFieldLabel("qualityMis_top_cities","",1);
      $cityIndia = FieldMap::getFieldLabel("city_india","",1);
      $topCity = array();
      $topCites = array();
      $source_cities = array();
      foreach($cityIndia as $cityCode=>$city){
              if(!in_array($cityCode, $sourceCities) && !ctype_alpha($cityCode)){
                     $source_cities[$cityCode] = $city;
              }elseif(in_array($cityCode, $sourceCities)){
                      $topCity[$cityCode] = $city;
              }
      }
      foreach($sourceCities as $orderedCity){
              $topCites[$orderedCity] = $topCity[$orderedCity];
      }
      return array_merge($topCites,$source_cities);
 }
  // Create CSV for Mis
  /**
   * This function generates csv file
   * @param array $sgroupData source group data
   * @param array $dates_count total sum of registration on each date
   * @param array $columnDates date column name i.e. dates if date range selected else months array from RegistrationMisEnums
   * @param string $displayMsg message to be diaplayed on the top i.ee either date range or year value
   * @param string $range_format selected range format year 'Y' or month 'm'
   */
  public function createCSVFormatOutput($sgroupData, $dates_count, $columnDates, $displayMsg,$range_format,$selectedCities) {
    $csvData = 'Quality Registration MIS' . "\n";
    if($selectedCities != ""){
              $selectedCities = str_replace(",", ' | ', $selectedCities);
      }
    if($range_format == 'Y'){
      $csvData .= 'For the Year of '.$displayMsg . "\n";
      if($selectedCities != ""){
              $csvData .= 'Cities:,'.$selectedCities . "\n";
      }
      $csvData .= 'Day,';
      foreach($columnDates as $Date){
        if($Date>12){
          $csvData .= ($displayMsg+1).'-0'.($Date-12).',';
        }else{
          $csvData .= $displayMsg.'-'.$Date.',';
        }
      }
      $csvData = rtrim($csvData, ',');
    }else{
      $csvData .= $displayMsg . "\n";
      if($selectedCities != ""){
              $csvData .= 'Cities:,'.$selectedCities . "\n";
      }
      $csvData .= 'Day,';
      $csvData .= implode(',', $columnDates);
    }
    $csvData .= ',Total' . "\n";
    $dateTotal = 0;
    $csvData .= 'Total,';
    foreach ($columnDates as $dtColumn) {
      if (isset($dates_count[$dtColumn])) {
        $dateTotal += $dates_count[$dtColumn];
        $csvData .= $dates_count[$dtColumn] . ',';
      } else {
        $csvData .= "0,";
      }
    }
    $csvData .= $dateTotal . "\n";
    foreach ($sgroupData as $ky => $groupData) {
      foreach ($groupData as $ky => $srcData) {
        foreach ($columnDates as $dtColumn) {
          if (!isset($srcData['TOTAL_REG'][$dtColumn]))
            $srcData['TOTAL_REG'][$dtColumn] = 0;
          if (!isset($srcData['F22'][$dtColumn]))
            $srcData['F22'][$dtColumn] = 0;
          if (!isset($srcData['F22MV'][$dtColumn]))
            $srcData['F22MV'][$dtColumn] = 0;
          if (!isset($srcData['F22MVCC'][$dtColumn]))
            $srcData['F22MVCC'][$dtColumn] = 0;
          if (!isset($srcData['M26'][$dtColumn]))
            $srcData['M26'][$dtColumn] = 0;
          if (!isset($srcData['M26MV'][$dtColumn]))
            $srcData['M26MV'][$dtColumn] = 0;
          if (!isset($srcData['M26MVCC'][$dtColumn]))
            $srcData['M26MVCC'][$dtColumn] = 0;
          if (!isset($srcData['SCREENED_SIC'][$dtColumn]))
            $srcData['SCREENED_SIC'][$dtColumn] = 0;

          $srcData['TOTAL_QUALITY'][$dtColumn] = $srcData['F22MVCC'][$dtColumn] + $srcData['M26MVCC'][$dtColumn];
        }
	ksort($srcData['TOTAL_REG']);
        ksort($srcData['F22']);
        ksort($srcData['F22MV']);
        ksort($srcData['F22MVCC']);
        ksort($srcData['M26']);
        ksort($srcData['M26MV']);
        ksort($srcData['M26MVCC']);
        ksort($srcData['SCREENED_SIC']);
        $csvData .= $srcData['groupName'] . ",";
        $csvData .= implode(',', $srcData['TOTAL_REG']);
        $csvData .= ',' . array_sum($srcData['TOTAL_REG']) . "\n";

        $csvData .= "F>=22,";
        $csvData .= implode(',', $srcData['F22']);
        $csvData .= ',' . array_sum($srcData['F22']) . "\n";

        $csvData .= "F>=22 + MV,";
        $csvData .= implode(',', $srcData['F22MV']);
        $csvData .= ',' . array_sum($srcData['F22MV']) . "\n";

        $FMVCC = array_sum($srcData['F22MVCC']);
        $csvData .= "F>=22 + MV +CC,";
        $csvData .= implode(',', $srcData['F22MVCC']);
        $csvData .= ',' . $FMVCC . "\n";

        $csvData .= "M>=26,";
        $csvData .= implode(',', $srcData['M26']);
        $csvData .= ',' . array_sum($srcData['M26']) . "\n";

        $csvData .= "M>=26 + MV,";
        $csvData .= implode(',', $srcData['M26MV']);
        $csvData .= ',' . array_sum($srcData['M26MV']) . "\n";

        $MMVCC = array_sum($srcData['M26MVCC']);
        $csvData .= "M>=26 + MV + CC,";
        $csvData .= implode(',', $srcData['M26MVCC']);
        $csvData .= ',' . $MMVCC . "\n";

        $csvData .= "All screened + SIC,";
        $csvData .= implode(',', $srcData['SCREENED_SIC']);
        $csvData .= ',' . array_sum($srcData['SCREENED_SIC']) . "\n";

        $total = $MMVCC + $FMVCC;
        $csvData .= "Total Quality Reg,";
        $csvData .= implode(',', $srcData['TOTAL_QUALITY']);
        $csvData .= ',' . $total . "\n";
      }
    }
    header("Content-Type: application/vnd.csv");
    header("Content-Disposition: attachment; filename=Quality_Registration_Mis.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $csvData;
    die;
  }




  // create csv files for community wise registeration

  public function createCSVFormatCommunitywise($registrationData)
  {
      $csvData = 'communitywise Registration MIS' . "\n";
      $csvData = $csvData.$this->displayDate."\n";
      $csvData = $csvData."Date".","."City".","."Source Category".","."Community".","."Total Registerations".","."Quality Registerations". "\n";
      header("Content-Type: application/vnd.csv");
      header("Content-Disposition: attachment; filename=Communitywise_Registration_Mis.csv");
      header("Pragma: no-cache");
      header("Expires: 0");

      foreach ($registrationData as $date => $data_date )
       {
        foreach ($data_date as $source => $data_source)
        {
          foreach ($data_source as $city => $data_city)
          {
            $row = "";
            if ($data_city['screened_CC'] != 0)
              $row .= $date.",".$city.",".$source.",CC,".$data_city['screened_CC'].",".($data_city['M26MVCC'] + $data_city['F22MVCC'])."\n";
            if ($data_city['screened_SIC'] != 0)
              $row .= $date.",".$city.",".$source.",SIC,".$data_city['screened_SIC'].",0"."\n";

            if ($data_city['OTHERS_COMMUNITY'] != 0)
              $row .= $date.",".$city.",".$source.",Others,".$data_city['OTHERS_COMMUNITY'].",0"."\n";
            
            $csvData = $csvData.$row ;
          }
        }
      }
      echo $csvData;
      die;

  }
  /*
   * Get number of days between 2 dates
   * @param date $sStartDate start date
   * @param date $sEndDate end date
   */

  public function GetDays($sStartDate, $sEndDate) {
    $sStartDate = date("Y-m-d", strtotime($sStartDate));
    $sEndDate = date("Y-m-d", strtotime($sEndDate));
    $aDays[] = $sStartDate;
    $sCurrentDate = $sStartDate;
    while ($sCurrentDate < $sEndDate) {
      $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
      $aDays[] = $sCurrentDate;
    }
    return $aDays;
  }
  public function verifyDates($start_date,$end_date){
    if($start_date>$end_date)
            $this->errorMsg = "Invalid Date Selected";
    elseif(ceil((strtotime($end_date)-strtotime($start_date))/(24*60*60))>=100)
            $this->errorMsg = "More than 100 days selected in range";
  }

  //This action calls the LocationAgeRegistrationSuccess.tpl
  public function executeLocationAgeRegistration(sfWebRequest $request)
  {
    $formArr = $request->getParameterHolder()->getAll();
    $this->cid = $formArr['cid'];
    if ($formArr['submit']) 
    { 
      //An array of the required Form Data
      $params = array('range_format'=>$formArr["range_format"],'quarter_year'=>$formArr['qyear'],'month_year'=>$formArr['myear'],'day_month'=>$formArr['dmonth'],'day_year'=>$formArr['dyear'],'report_type'=>$formArr['report_type'],'report_format'=>$formArr['report_format']);
      
      $this->range_format = $params['range_format'];
      
      //displayDate to be shown in the Results Page
      if($this->range_format == 'Q')
      {
        $this->displayDate = $params['quarter_year'];
      }
      elseif($this->range_format == 'M')
      {
        $this->displayDate = $params['month_year'];
      }
      else
      {
        $this->displayDate = $params['day_month']."-".$params['day_year'];
      }

      if($params['report_type'] == 'CITY_RES')
      {
        $this->displayName = "By City";
      }
      elseif($params['report_type'] == 'MTONGUE')
      {
        $this->displayName = "By Community";
      }
      else
      {
        $this->displayName = "By Age & Gender";
      }


      //creating memcacheObj
      $memcacheObj = JsMemcache::getInstance();
      //Memcache Key based on Form inputs
      $this->memcacheKey = $this->range_format."_".$this->displayDate."_".$params['report_type'];
      
      $memKeySet = $memcacheObj->get($this->memcacheKey);
      $params['memKeySet'] = $this->memcacheKey;
      
      if($memKeySet == 'C')
      {
        $this->computing = true;
        $this->setTemplate('computingRegistrationMis');
      }
      elseif(is_array($memKeySet))
      { 
        $this->groupData = $memKeySet;
        $this->totalCountValue = $this->groupData['totalCountValue'];
        $this->computing = false;
        $this->monthNames = RegistrationMisEnums::$monthNames;
        $this->quarterNames = RegistrationMisEnums::$quarterNames;
        if($formArr['report_format'] == 'CSV')
        { //check parameters to be sent
          $registrationMisObj = new cityAgeRegistrationMis();
          $csvData = $registrationMisObj->createCSVFromatData($params,$this->groupData,$this->displayDate,$this->displayName);
          header("Content-Type: application/vnd.csv");
          header("Content-Disposition: attachment; filename=Location_Age_Community_RegistrationMIS.csv");
          header("Pragma: no-cache");
          header("Expires: 0");
          echo($csvData);
          die;
        }
        $this->setTemplate('locationAgeRegistrationResultScreen');
      }
      elseif($memKeySet == '')
      {
        $this->computing = true;
        $memcacheObj->set($this->memcacheKey,"C");
        $memcacheObj->set("MIS_PARAMS_KEY",$params);
        $filePath = JsConstants::$cronDocRoot."/symfony cron:cronLocationAgeRegistrationMis  > /dev/null &";
        $command = JsConstants::$php5path." ".$filePath;
        passthru($command);
        $this->setTemplate('computingRegistrationMis');
      }
    }
    else
    {
      $this->mmarr = GetDateArrays::getMonthArray();
      $this->yyarr = array();
      $this->currentYear = date("Y");
      $this->currentMonth = date("m");
      for ($i = 2014; $i <= date("Y"); $i++) 
      {
        $this->yyarr[$i - 2014] = $i;
      }
    }
    
  }
  public function executeScreeningCountMis(sfWebRequest $request) 
  {
    $formArr = $request->getParameterHolder()->getAll();
    $name = $request->getAttribute('name');
    $this->cid = $formArr['cid'];
    if ($formArr['submit']) {
        if(strlen($formArr["date1_dateLists_day_list"])==1)
                $formArr["date1_dateLists_day_list"] = "0".$formArr["date1_dateLists_day_list"];
        if(strlen($formArr["date1_dateLists_month_list"])==1)
                $formArr["date1_dateLists_month_list"] = "0".$formArr["date1_dateLists_month_list"];
	$fromDate = $formArr['date1_dateLists_year_list']."-".$formArr['date1_dateLists_month_list'].$formArr['date1_dateLists_day_list'];
	$screeningQueueCountObj = new MIS_SCREENING_QUEUE_COUNTS('newjs_slave');
	$records = $screeningQueueCountObj->getRecords($fromDate);
	foreach($records as $k=>$v)
	{
		$finalRec[$v['DATE']][$v['AT_HOUR']]=$v;
	}
	$this->hrArr = range(0,23);
	$blankArr = array(
                    "PROFILE_NEW" => "","PROFILE_EDIT" => "", "PHOTO_ACCEPT_REJ_NEW" =>"", "PHOTO_ACCEPT_REJ_EDIT" =>"", "PHOTO_PROCESS_NEW" =>"","PHOTO_PROCESS_EDIT" => "");
	foreach($finalRec as $x=>$y)
	{
		$hrsAvailable = array_keys($y);
		unset($missingHrs);
		$missingHrs = array_diff($this->hrArr,$hrsAvailable);
		foreach($missingHrs as $n=>$m)
		{
			$finalRec[$x][$m]=$blankArr;
			$finalRec[$x][$m]['DATE']=$x;
			$finalRec[$x][$m]['AT_HOUR']=$m;
		}
		ksort($finalRec[$x]);
	}
	$this->finalRec = $finalRec;
        $this->setTemplate('ScreeningCountMisScreen');
    }
    else
    {
      $this->startMonthDate = "01";
      $this->todayDate = date("d");
      $this->todayMonth = date("m");
      $this->todayYear = date("Y");
      $this->rangeYear = date("Y");
      $this->dateArr = GetDateArrays::getDayArray();
      $this->yearArr = array();
      $dateArr = GetDateArrays::generateDateDataForRange('2015', ($this->todayYear));
      foreach (array_keys($dateArr) as $key => $value) {
        $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
	}
    }
  }
}
