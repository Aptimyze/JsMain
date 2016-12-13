<?php

/* this class is used to store tracking data into CA_LAYER_TRACK table
 */
class CriticalActionLayerTracking
{

  const ANALYTIC_SCORE_THRESHOLD=70;
  const RCB_LAYER_REF_DATE='2011-01-01';

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
  
  public static function satisfiesDateCondition($profileId)
  {
     $now = time(); // or your date as well
     $your_date = strtotime(self::RCB_LAYER_REF_DATE);
     $datediff = $now - $your_date;
     $dayDiff=floor($datediff/(60*60*24));  
     $remainder=$dayDiff=$dayDiff%5;
     if($remainder==$profileId%5)return true;
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
    $layerButtonTrack->insert($profileId, $layerId,$button);
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
  public static function getCALayerToShow($profileObj,$interestsPending)
  {
    $profileId = $profileObj->getPROFILEID();
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

      $layer = CriticalActionLayerDataDisplay::getDataValue('','PRIORITY',$i);
      if (!$layer) 
          return 0;
      else if (self::checkFinalLayerConditions($profileObj,$layer,$interestsPending,$getTotalLayers))
          return $layer;

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
    if( (time() -strtotime($getTotalLayers[$layerToShow]["MAX_ENTRY_DT"])) <=  60*60*$compareTime) 
          return false;
            
    $profileid = $profileObj->getPROFILEID();
    $show=0;
    $request=sfContext::getInstance()->getRequest();
    $isApp=MobileCommon::isApp();
        switch ($layerToShow) {
          case '1': 
                    $picObj= new PictureService($profileObj);
                    $havePhoto= $picObj->isProfilePhotoPresent();
                    if ($havePhoto == null)
                      $show=1;
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
                      if(self::satisfiesDateCondition($profileid) &&  !CommonFunction::isPaid($loggedInUser->getSUBSCRIPTION()))
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
                      
                      if(!$isApp)
                      {
                          $memObject=  JsMemcache::getInstance();
                          if($memObject->get('MA_LOWDPP_FLAG_'.$profileid))
                                  $show=1;
                            
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
                        $profileObj = LoggedInProfile::getInstance('newjs_master');
                        $contactNumOb=new newjs_JPROFILE_CONTACT();
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
                        $profileObj = LoggedInProfile::getInstance('newjs_master');
                        $contactNumOb=new newjs_JPROFILE_CONTACT();
                        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileObject->getPROFILEID()),'','',"ALT_EMAIL, ALT_EMAIL_STATUS");
                        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL'] != NULL && $numArray['0']['ALT_EMAIL_STATUS'] != 'Y')
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
}
