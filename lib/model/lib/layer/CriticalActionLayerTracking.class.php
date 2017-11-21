<?php

/* this class is used to store tracking data into CA_LAYER_TRACK table
 */
class CriticalActionLayerTracking
{

  const ANALYTIC_SCORE_THRESHOLD=70;
  const RCB_LAYER_REF_DATE='2011-01-01';
  public static $independentCALS = array(19); // cals that are highly important and surpass the normal CAL Logic
  /* this function will select entries for today
   *@param- profile id, layer on which user gives response,button the user presses
   */
  public static function selectTodaysLayer($profileId)
  {
    $layerButtonTrack= new MIS_CA_LAYER_TRACK();
    $result=$layerButtonTrack->select($profileId);
    return $result;
  }



  /* this function will Check whether todays date is matching the remainder condition
   *@param- profile id
   */

  public static function satisfiesDateCondition($profileId,$mod)
  {
     $now = new DateTime(); // or your date as well
     $your_date = new DateTime(self::RCB_LAYER_REF_DATE);
     $days = $now->diff($your_date);
     $dayDiff=$days->days;
     $remainder=$dayDiff%$mod;
     if($remainder==$profileId%$mod)return true;
     else return false;
  }



  public static function satisfiesCallBackCondition($dateTime)
  {
     $now = time(); // or your date as well
     $your_date = strtotime($dateTime);
      $datediff = $now - $your_date;
     if($datediff>15*60*60*24)
      return true;
     else
      return false;
  }


  /* this function will insert CA layer type and button type entry on a
   * particular profile id
   *@param- profile id, layer on which user gives response,button the user presses
   */
  public static function insertLayerType($profileId,$layerId,$button)
  {
    $layerButtonTrack= new MIS_CA_LAYER_TRACK();
    return $layerButtonTrack->insert($profileId, $layerId,$button);
  }
  /* this function will update button type entry on a
   * particular profile id
   *@param- profile id, layer on which user gives response,button the user presses
   */
  public static function updateLayerType($profileId,$layerId,$button)
  {
    $layerButtonTrack= new MIS_CA_LAYER_TRACK();
    $layerButtonTrack->update($profileId, $layerId, $button);
  }
  /*this function will check for various contion which layer should be diplayed
   * based on data stored in table for a particular layer type and profile id
   * @param- profile id, layer type
   * @return- boolean value to display layer or not
   */
  public static function getCALayerToShow($profileObj,$interestsPending,$checkForIndependentCal='')
  {//return 23;
   //
   //
//   return 2;
//   return 9;
    $profileId = $profileObj->getPROFILEID();

    /////// check from redis begin here
    $memObject = JsMemcache::getInstance();
    $calRedisKeys = $memObject->getMultiKeys(array($profileId.'_CAL_DAY_FLAG',$profileId.'_NOCAL_DAY_FLAG',$profileId.'_NO_LI_CAL'));
    $calDayFlag = unserialize($calRedisKeys[0][0]);
    $calNoDayFlag = unserialize($calRedisKeys[0][1]); //
    $NO_LIGHT_CAL = unserialize($calRedisKeys[0][2]); // lightning cal flag
     if($checkForIndependentCal && $NO_LIGHT_CAL==1)
        return 0;
  else  if(!$checkForIndependentCal && ($calDayFlag==1 || $calNoDayFlag==1))
        return 0;

//
    $fetchLayerList = new MIS_CA_LAYER_TRACK();
    $getTotalLayers = $fetchLayerList->getCountLayerDisplay($profileId);
    $maxEntryDt = 0;
    /* make sure no layer opens before one day */
    if(is_array($getTotalLayers))
    {

            //get maximum entry date of the records fetched
      foreach($getTotalLayers as $k=>$v)
      {
        if($maxEntryDt<$getTotalLayers[$k]["MAX_ENTRY_DT"])
        {
        $maxEntryDt = $getTotalLayers[$k]["MAX_ENTRY_DT"];
        }
      }


    }
    foreach (self::$independentCALS as $key => $value) {
      # code...
      if(self::checkFinalLayerConditions($profileObj,$value,'',$getTotalLayers))
        return $value;
    }
    if($checkForIndependentCal){
      $memObject->set($profileId.'_NO_LI_CAL',1,10800);
      return 0;
    }
        //default condition for minimum time difference between layers
            /* make sure no layer opens before one day */

        if($maxEntryDt)
        {
          if ( (time() - strtotime($maxEntryDt)) <= 60*60*24)
          {
            return 0;
          }

        }
    

// in the order of priority
        for ($i=1;;$i++)
        {

      $layer = CriticalActionLayerDataDisplay::getLAYERFromPriority($i);

      if (!$layer)
        {
            JsMemcache::getInstance()->set($profileId.'_NOCAL_DAY_FLAG',1,21600);
            return 0;
        }
      else if (self::checkFinalLayerConditions($profileObj,$layer,$interestsPending,$getTotalLayers))
      {
           return $layer;
      }

        }

return 0;


  }
  /*this function will check for various conditions for which layer should be diplayed
   * and return the layer to be displayed
   * @param- profile object, layer type
   * @return- layerid to display layer
   */
  public static function checkFinalLayerConditions($profileObj,$layerToShow,$interestsPending,$getTotalLayers)
  {

    $layerInfo=CriticalActionLayerDataDisplay::getDataValue($layerToShow);
    if($getTotalLayers[$layerToShow])
      if ($getTotalLayers[$layerToShow]["COUNT"]>=$layerInfo['TIMES'])
       return false;
         /*check for diffTime then check whether it is the same layer that was shown last or next layer
         *for both, same or changed layer compare diffTime with their respective values in table if diffTime is less than any of them return null
         */

    $compareTime=$layerInfo['MINIMUM_INTERVAL'];
    if($getTotalLayers[$layerToShow]["MAX_ENTRY_DT"])
    if((time() -strtotime($getTotalLayers[$layerToShow]["MAX_ENTRY_DT"])) <=  60*60*$compareTime)
         return false;

    $profileid = $profileObj->getPROFILEID();
    $show=0;
    $request=sfContext::getInstance()->getRequest();
    $isApp=MobileCommon::isApp();

        switch ($layerToShow) {
          case '1':
            if(strtotime('-30 days') < strtotime($profileObj->getVERIFY_ACTIVATED_DT()) )
                    {
                    $picObj= new PictureService($profileObj);
                    $havePhoto= $picObj->isProfilePhotoPresent();
                    if ($havePhoto == null)
                      $show=1;
                    }
                    break;
          case '2': if ($profileObj->getFAMILYINFO()=='')
                      $show=1;
                    break;
          case '4': if (self::showEducationLayer())
                      $show=1;
                    break;
          case '3': if ($interestsPending > 0)
                      $show=1;
                    break;
          case '5':
                    $show=1;
                    break;
          case '6':
                  if($isApp!='I')
                    {
                          $loginData = $request->getAttribute('loginData');
                          $birthdate = new DateTime($loginData['DTOFBIRTH']);
                          $today   = new DateTime('today');
                          $age = $birthdate->diff($today)->y;
                          $gender = $loginData['GENDER'];
                          //print_r($age." is age anfd gendere is ".$gender);
                          //die;
                      if(!($gender == 'M' && $age >= 24) && !($gender == 'F' && $age >=22) )break;

                      $loggedInUser=LoggedInProfile::getInstance();
                      if(self::satisfiesDateCondition($profileid,5) &&  !CommonFunction::isPaid($loggedInUser->getSUBSCRIPTION()))
                      {


                        $analyticRow=(new incentive_MAIN_ADMIN_POOL())->get($profileid,"PROFILEID","ANALYTIC_SCORE");
                        if($analyticRow['ANALYTIC_SCORE'] && $analyticRow['ANALYTIC_SCORE']>=self::ANALYTIC_SCORE_THRESHOLD)
                        {

                          $callBackRow=(new billing_EXC_CALLBACK())->getLatestEntryDate($profileid);
                          if(!$callBackRow || self::satisfiesCallBackCondition($callBackRow))
                            $show=1;
                        }
                      }

                    }

                    break;

          case '7':

                      $entryDate=$profileObj->getENTRY_DT();
                      if((time()-strtotime($entryDate))>7*24*60*60)
                      {
                      $arr=array("WHERE"=>array("IN"=>array("SENDER"=>$profileid)),"ORDER"=>"`TIME`","LIMIT"=>1);
                      $resultArr=(new newjs_CONTACTS(JsDbSharding::getShardNo($profileid)))->getContactedProfileArray($arr);
                      if(!$resultArr)
                        $show=1;
                      else
                        {
                          $lastInterest=strtotime(array_values($resultArr)[0]['TIME']);
                          if((time()-$lastInterest)>15*24*60*60) $show=1;
                        }
                      }

                    break;

                    case '8':


                      $isApp=MobileCommon::isApp();
                      if(!$isApp || ($request->getParameter('API_APP_VERSION')>72 && $isApp=='A'))
                      {

		      $negativeObj=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
                      if($negativeObj->isFtoDuplicate($profileid))
                          $show=1;
                      }

                    break;

                    case '9':
                      $appVersion=$request->getParameter('API_APP_VERSION');
                      if(!$isApp || ($isApp=='A' && $appVersion>=63) || ($isApp=='I' && $appVersion>=3.0) )
                      {
                      $nameArr=(new NameOfUser())->getNameData($profileid);
                      if(!is_array($nameArr[$profileid]) || !$nameArr[$profileid]['DISPLAY'] || !$nameArr[$profileid]['NAME'])
                          $show=1;
                      }

                    break;

                      case '10':
                      if(!$isApp)
                      {
                        if($profileObj->getHAVEPHOTO() == 'Y' && $profileObj->getPHOTO_DISPLAY() == 'C')
                          $show = 1;
                      }

                    break;

                      case '11':

                          $memObject=  JsMemcache::getInstance();
                          if($memObject->get('MA_LOWDPP_FLAG_'.$profileid))
                          {
                            $show=1;
                            if(!MobileCommon::isDesktop() && (!MobileCommon::isApp() || self::CALAppVersionCheck('16',$request->getParameter('API_APP_VERSION'))))
                            {
                            ob_start();
                            sfContext::getInstance()->getController()->getPresentationFor("profile", "dppSuggestionsCALV1");
                            $layerData = ob_get_contents();
                            ob_end_clean();
                            $dppSugg=json_decode($layerData,true);
                            if(is_array($dppSugg['dppSuggObject']))   $dppSugg = $dppSugg['dppSuggObject'];
                            if(is_array($dppSugg) && is_array($dppSugg['dppData']))
                            {
                              foreach ($dppSugg['dppData'] as $key => $value)
                              {
                                if(is_array($value['data']) && count($value['data']) )
                                {
                                  $show = 0;
                                  break;
                                }
                              }
                            }
                            }
                          }
                    break;

                      case '12':
                      if(!$isApp)
                      {
                        $horoscopeObj = new Horoscope();
                        if($profileObj->getHOROSCOPE_MATCH() == 'Y' && $horoscopeObj->ifHoroscopePresent($profileid) == 'N')
                          $show = 1;
                      }

                    break;

                    case '13':
                      if(!$isApp)
                      {
                        $profileObject = LoggedInProfile::getInstance('newjs_master');
                        $contactNumOb=new ProfileContact();
                        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileObject->getPROFILEID()),'','',"ALT_EMAIL");
                        if(!$numArray['0']['ALT_EMAIL'] || $numArray['0']['ALT_EMAIL'] == NULL)
                        {
                           $show = 1;
                        }

                      }

                    break;

                    case '14':
                      if(!$isApp)
                      {
                        $profileObject = LoggedInProfile::getInstance('newjs_master');
                        $contactNumOb=new ProfileContact();
                        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileObject->getPROFILEID()),'','',"ALT_EMAIL, ALT_EMAIL_STATUS");
                        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL'] != NULL && $numArray['0']['ALT_EMAIL_STATUS'] != 'Y')
                          $show = 1;
                      }

                    break;
                    case '15':
                    //This variable is introduced as we had to switch off this CAL for some duration (due to low numbers)
                      $switchForCAL = 0;
                      if($switchForCAL){
                      $screening=$profileObj->getSCREENING();
                      $nameArr=(new NameOfUser())->getNameData($profileid);
                      if(!$nameArr[$profileid]['DISPLAY'] && $nameArr[$profileid]['NAME'] && jsValidatorNameOfUser::validateNameOfUser($nameArr[$profileid]['NAME']) && Flag::isFlagSet("name", $screening))
                          $show=1;
                      }
                    break;
                    case '16':
                          if(MobileCommon::isNewMobileSite() || (MobileCommon::isApp() && self::CALAppVersionCheck('16',$request->getParameter('API_APP_VERSION'))) )
                          {

                              ob_start();
                              sfContext::getInstance()->getController()->getPresentationFor("profile", "dppSuggestionsCALV1");
                              $layerData = ob_get_contents();
                              ob_end_clean();
                              $dppSugg=json_decode($layerData,true);
                              if(is_array($dppSugg['dppSuggObject']))   $dppSugg = $dppSugg['dppSuggObject'];
                              if(is_array($dppSugg) && is_array($dppSugg['dppData']))
                              {
                                foreach ($dppSugg['dppData'] as $key => $value)
                                {
                                  if(is_array($value['data']) && count($value['data']))
                                  {
                                    $show = 1;
                                    $request->setParameter('dppSugg',$dppSugg);
                                    break;
                                  }
                                }
                              }

                          }
                    break;

                    case '17':

                     // $profileId=$profileObj->getPROFILEID();
                        $picture_new = new ScreenedPicture;
                        $ordering = $picture_new->getMaxOrdering($profileid);
                        $oneTwoPhotos;
                        if($ordering == null)
                        {
                          $oneTwoPhotos = 0;
                        }
                        else if ($ordering === "0" || $ordering === "1")
                        {
                          $oneTwoPhotos = 1;
                        }
                      $entryDate = $profileObj->getENTRY_DT();
                      if(((time() - strtotime($entryDate)) > 15*24*60*60 ) && $oneTwoPhotos)
                      {
                          $show=1;

                      }


                    break;
                    case '18':

                      if(!MobileCommon::isApp() || (MobileCommon::isApp() && self::CALAppVersionCheck('18',$request->getParameter('API_APP_VERSION'))))
                      if($profileObj->getOCCUPATION()==43)
                      {
                          $show=1;

                      }
                      break;

                    case '19':
                      if(!MobileCommon::isApp() || (MobileCommon::isApp() && self::CALAppVersionCheck('19',$request->getParameter('API_APP_VERSION'))) ){
                      $lightningCALObj = new LightningDeal();
                      $lightningCALData = $lightningCALObj->lightningDealCalAndOfferActivate($request);
                      if($lightningCALData != false){
                        $request->setParameter('DISCOUNT_PERCENTAGE',$lightningCALData['line2']);
                        $request->setParameter('DISCOUNT_SUBTITLE',$lightningCALData['line3']);
                        $request->setParameter('START_DATE','Plan starts @');
                        $request->setParameter('OLD_PRICE',$lightningCALData['strikeoutPrice']);
                        $request->setParameter('NEW_PRICE',$lightningCALData['discountedPrice']);
                        $request->setParameter('LIGHTNING_CAL_TIME',$lightningCALData['endTimeInSec']);
                        $request->setParameter('SYMBOL',$lightningCALData['currencySymbol']);

                        $show=1;
                        self::flushCALCacheData($profileid);
                      }
                      }
                    break;

                     case '21':
        if($isApp=='I' && self::CALAppVersionCheck('21',$request->getParameter('API_APP_VERSION')))
        {
                     $jpartnerObj=ProfileCommon::getDpp($profileid,"decorated",$page_source);
                    $strDPPCaste = $jpartnerObj->getDecoratedPARTNER_CASTE();
                    if($strDPPCaste != '' && $strDPPCaste != NULL && $strDPPCaste!="Doesn't Matter")
                    {
                      $layerDppCaste = explode(',',$strDPPCaste);
      foreach ($layerDppCaste as $key => $value) {
        $tempArr[$key] = explode(':', $value)[1];
      }
      $layerDppCaste = implode(',', $tempArr);
      $layerDppCaste = trim($layerDppCaste);
      $request->setParameter('DPP_CASTE_BAR',$layerDppCaste);
                      $show=1;
                    }
                    }
                      break;

                    case '22':
                    if(strtotime('-30 days') >= strtotime($profileObj->getVERIFY_ACTIVATED_DT()) )
                    {
                    $picObj= new PictureService($profileObj);
                    $havePhoto= $picObj->isProfilePhotoPresent();
                    if ($havePhoto == null)
                      $show=1;
                    }
                    break;


                    case '23' :
                        if(!MobileCommon::isApp() || self::CALAppVersionCheck('23',$request->getParameter('API_APP_VERSION')))
                  {
                    $familyBasedOutOfObj= new JProfile_NativePlace($profileObj);
                    if(!$familyBasedOutOfObj->getCompletionStatus())
                    {
                      $show=1;
                    }

                  }
                     break;


                  case '20':

                      if( (      !MobileCommon::isApp() || self::CALAppVersionCheck('20',$request->getParameter('API_APP_VERSION'))) && self::checkConditionForCityCAL($profileObj))
                      {
                          $show=1;

                      }


                    break;

                  case '25':
                    if(!MobileCommon::isApp() && !MobileCommon::isNewMobileSite()){
                      if(in_array($profileObj->getRELIGION(),
                        array(1/*hindu*/, 9/*jain*/, 4/*sikh*/, 7/*buddhist*/))){
                        if(!($profileObj->getMANGLIK())) {
                          $show=1;
                        }
                      }
                    }
                  break;

                  case '24':

                      if(MobileCommon::isApp() && self::CALAppVersionCheck('24',$request->getParameter('API_APP_VERSION')) /*&& ($profileid%19)==0*/) 
                      {
                          $nameData=(new NameOfUser())->getNameData($profileid);
                          $nameOfUser=$nameData[$profileid]['NAME'];
                          if($nameOfUser)
                          {
                            $aadhaarObj = new aadharVerification();
                            $details = $aadhaarObj->getAadharDetails($profileid)[$profileid];
                            if(!$details[AADHAR_NO] || $details[VERIFY_STATUS]=='N')
                              $show=1;
                          }
                      }


                    break;

                  case '26':

                      if($profileObj->getACTIVATED()=='Y' && self::CALAppVersionCheck('26',$request->getParameter('API_APP_VERSION')))
                      {
                          $len = strlen($profileObj->getYOURINFO());
                          if(!$len || $len<100)
                              $show=1;
                      }
                  break;
                  case '27':
                    $loggedinUserEmail = $profileObj->getEMAIL();
                    $bounceObj = new bounces_BOUNCED_MAILS();
                    $Flag = $bounceObj->checkEntry($loggedinUserEmail);
                    if(!MobileCommon::isApp() && $Flag){
                      $show = 1;
                    }
                  break;

          default : return false;
        }
        /*check if this layer is to be displayed
         * and then check no. of times the layer has been shown and then compare it with value of max times in the table
         */

        if($show)
          return true;
        else
          return false;
  }




// This function is for calculating whether to show  education Layer
  public static function showEducationLayer() {
  $profileObj=LoggedInProfile::getInstance();
  $highestDegree=$profileObj->getEDU_LEVEL_NEW();
  $fieldArray=FieldMap::getFieldLabel('degree_grouping_reg','','Y');
  switch (true)
  {

  case in_array($highestDegree, explode(',',$fieldArray['ug'])):
    return false;
  break;



  case in_array($highestDegree, explode(',',$fieldArray['g'])):

    $jprofileEduObj= ProfileEducation::getInstance();
    $education=$jprofileEduObj->getProfileEducation($profileObj->getPROFILEID());
    if(!$education['UG_DEGREE'] )
      return true;
    else return false;

  break;

  case (in_array($highestDegree, explode(',',$fieldArray['pg'])) || in_array($highestDegree, explode(',',$fieldArray['phd']))):

    $jprofileEduObj= ProfileEducation::getInstance();
    $education=$jprofileEduObj->getProfileEducation($profileObj->getPROFILEID());
    if(!$education['UG_DEGREE'] || !$education['PG_DEGREE'] )
      return true;
    else return false;
  break;

default:
return false;
break;

}



  }


  public static function CALAppVersionCheck($calID,$appVersion){

      $isApp = MobileCommon::isApp();
      if(!$isApp)return true;
      $versionArray = array(

                '16' => array(

                    'A' => '84',

                    'I' => '10.5'

                        ),

                '19' => array(
                  'A' => '97',
                  ),

                          '18' => array(

                    'A' => '96',
                   'I' => '5.5'
                        ),
                    '21' => array(

                    'A' => '99',
                    'I' => '5.3'
                        ),

                    '23' => array(
                    'A' => '99',
                    'I' => '5.4'
                        ),

                  '20' => array(
                    'A' => '99',
                    'I' => '5.4'
                        ),

                  '24' => array(
                    'A' => '107',
                    'I' => '6.0'
                        ) ,
                  '26' => array(
                    'A' => '109'
                        )


          );
      if($versionArray[$calID][$isApp] && $appVersion >= $versionArray[$calID][$isApp])
          return true;
       return false;


  }

    public static function checkConditionForCityCAL($profileObj){

      $cityRes =$profileObj->getCITY_RES();
      if($profileObj->getCOUNTRY_RES() == 51 && $cityRes == '0'){
        return true;
       }
       return false;

  }


  public static  function flushCALCacheData($profileid)
  {
    $redis = JsMemcache::getInstance();
    $redis->delete($profileid.'_CAL_DAY_FLAG');
  }
}
