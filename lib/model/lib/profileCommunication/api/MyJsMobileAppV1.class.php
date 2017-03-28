<?php
/**
 * @brief This class is for myjs mobile App version 1 functions
 * @author Reshu Rajput
 * @created 2013-12-19
 */

class MyJsMobileAppV1
{
	static public $informationTupleFields;
	static public $informationTypeFields;
	static public $myProfileIncompleteFields;
	static public $noTupleText;
	static public $tupleTitleField ;



	public static function deleteMyJsCache($profileIdArray){
            $memObject = JsMemcache::getInstance();
            foreach ($profileIdArray as $key => $value) {
                $memObject->delete(MyJsMobileAppV1::getCacheKey($value).'_I');
                $memObject->delete(MyJsMobileAppV1::getCacheKey($value).'_A');
                $memObject->delete(MyJsMobileAppV1::getCacheKey($value).'_M');

                
            }    

		
	}

    public static function getCacheKey($pid)
        {

        return $pid."_MYJS_CACHED_DATA";

    	}

	public function getProfilePicAppV1($profileObj)
	{   
		$pictureService = new PictureService($profileObj);
	        $profilePicObj = $pictureService->getProfilePic();

		if($profilePicObj)
			$myPic = $profilePicObj->getThumbailUrl();
		//die('hjhj');
        if(!$myPic)
		{
			 if($pictureService->isProfilePhotoUnderScreening() =="Y")
				$myPic = $profilePicObj->getThumbail96Url();
			else
				$myPic = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$profileObj->getGENDER());
		}
   		
		if (MobileCommon::isApp()=='A') 
			$myPic=PictureFunctions::mapUrlToMessageInfoArr($myPic,"ThumbailUrl","",$profileObj->getGENDER());
		if (MobileCommon::isNewMobileSite() || MobileCommon::isApp()=='I') 
			$myPic=PictureFunctions::mapUrlToMessageInfoArr($myPic,"ThumbailUrl","",$profileObj->getGENDER())['url'];
                return $myPic;	
	}

        public function getJsonAppV1($displayObj,$profileInfo='')
	{

		LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "class MyJsMobileAppV1 getJsonAppV1 hit");
$className = get_class($this);
			$className::init();
			
		$displayV1= Array();
		$showExpiring = $this->getExpiring();
		foreach(self::$informationTypeFields as $key=>$value)
		{
			if(array_key_exists($key,$displayObj))
			{
				$isApp = MobileCommon::isApp();
				$appVersion=sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION")?sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION"):0;				
				if($key == "INTEREST_EXPIRING" && !$showExpiring)
				{
					continue;
				}
				if($key == "INTEREST_EXPIRING" && $isApp == "A" && $appVersion  && $appVersion < 81)
				{
					continue;
				}
				if($key == "MATCH_OF_THE_DAY")
				{
					if (LoggedInProfile::getInstance()->getACTIVATED() == 'U')
					{
						continue;
					}

					if(MobileCommon::isApp() && ($isApp == "A" && $appVersion  && $appVersion < 88))
					{  
						// Version Check For ANDROID
						continue;
					}	
				}
				foreach($value as $k=>$v)
                                {
					if($v == "TUPLES")
					{
						$count=0;
						if(is_array($displayObj[$key][$v]))
						{
							
							 
// This code block is for calculating the education for all the profileids in the result, to save the number of queries
							$mailerServiceObj=new MailerService();
							unset($eduArray);
							unset($profileIdArray);
							foreach($displayObj[$key][$v] as $pid=>$fieldValue)
								$profileIdArray[]=$pid;
							$eduArray=$mailerServiceObj->getMultipleEducationDetails($profileIdArray);
							

							
							foreach($displayObj[$key][$v] as $pid=>$fieldValue)
							{
								foreach(self::$informationTupleFields[$key] as $i=>$field)
								{
 									$photoType=$this->getPhotoTypeFromId($key);
                                                                        $tupleObj= $displayObj[$key][$v][$pid];
									eval('$fieldValue =$tupleObj->get' . $field . '();'); 
									$fieldLabel = strpos($field,"Url")>0?"PHOTO":$field;
									if(array_key_exists($key,self::$tupleTitleField) && $fieldLabel == self::$tupleTitleField[$key])
										$fieldLabel="tuple_title_field";
									if($fieldLabel=="PHOTO"){
										$gender=$tupleObj->getGENDER();
										$fieldValue=PictureFunctions::mapUrlToMessageInfoArr($fieldValue,"ProfilePic120Url","",$gender);  // in this line it checks whether $fieldValue is a stock image or not. returns null for app and some stock image for JSMS if $fielsValue has a old stock image.									
									}	
									if($fieldLabel=="BUTTONS" && !empty($fieldValue))
												{
													$buttonObj = new ButtonResponse();
													$button = array();
													$tracking = $displayObj[$key]["TRACKING"];
													$tracking = explode("&",$tracking);
													foreach($tracking as $s=>$u)
													{
														$u = explode("=",$u);
														$track = $u[0];
														$$track = $u[1];
														$page[$u[0]] = $u[1];
													}

													foreach($fieldValue as $y=>$b)
													{
														if($b == "SHORTLIST")
														{
															$button[] = $buttonObj->getShortListButton('','',$tupleObj->getIS_BOOKMARKED());
														}
														else if($b!="PHOTO")
															$button[] = $buttonObj->getCustomButtonByBName($b,$page);
														else
															$button[] = ButtonResponse::getAlbumButton($tupleObj->getPHOTO_COUNT(),$tupleObj->getGENDER());
													}
													$buttonDetails["buttons"] = $button;
													$displayV1[strtolower($key)][strtolower($v)][$count]["buttonDetails"] = ButtonResponse::buttonDetailsMerge($buttonDetails);
												}
												else
													$displayV1[strtolower($key)][strtolower($v)][$count][strtolower($fieldLabel)] = $fieldValue?(is_array($fieldValue)?$fieldValue:strval($fieldValue)):NULL;
									}
												if(MobileCommon::isApp()!='A')
													$displayV1[strtolower($key)][strtolower($v)][$count]['education'] = $eduArray[$pid];
												$displayV1[strtolower($key)][strtolower($v)][$count]['educationNew'] = $eduArray[$pid];
									$count++;
								} 
							}
							else
							{
								$displayV1[strtolower($key)][strtolower($v)] = NULL;
								$displayV1[strtolower($key)]["no_tuple_text"]=self::$noTupleText[$key];
							}
						}
						else
						{
							if(array_key_exists($v,$displayObj[$key]) && ($displayObj[$key][$v]!="" || $displayObj[$key][$v]==0))
								$displayV1[strtolower($key)][strtolower($v)]= strval($displayObj[$key][$v]);
							else					
								$displayV1[strtolower($key)][strtolower($v)] = NULL;
							
						}
					}
				}
				else
					$displayV1[strtolower($key)]=NULL;
			}
		
		if(is_array($profileInfo))
		{
			foreach($profileInfo as $key=>$value)
			{
				if($key == "INCOMPLETE")
				{
					if(is_array($value))
					{
						foreach($value as $k=>$v)
						{
							$displayV1[strtolower("MY_PROFILE")][strtolower($key)][strtolower($k)]=$v?$v:NULL;
						}
					}
					else
						$displayV1[strtolower("MY_PROFILE")][strtolower($key)] =NULL;
				}
				else
				$displayV1[strtolower("MY_PROFILE")][strtolower($key)] =$value?(is_array($value)?$value:strval($value)):NULL; 		
			


			}

			

			}
		 

		$displayV1['membership_message'] = $this->getBannerMessage($profileInfo);     
			

//print_r($displayV1);die;
		return $displayV1;
        }

        
 /**
  * 
  * @param type $profileInfo
  * @param type $bforceFullyDBCall
  * @return string
  */
  public function getBannerMessage($profileInfo, $bforceFullyDBCall=false)
  {

    $MESSAGE = NULL;
    $profileObj = LoggedInProfile::getInstance('newjs_master');
    $request = sfContext::getInstance()->getRequest();
    $apiAppVersion = $request->getParameter('API_APP_VERSION');
      
    if (!empty($apiAppVersion) && is_numeric($apiAppVersion)) {
      $memMessage = $this->setAndGetOCBCache($request, $apiAppVersion, $profileObj,$bforceFullyDBCall);
      if ($apiAppVersion < 21)
        return $memMessage['membership_message']; // for backward compatibility of Android App
    } else {
      $memMessage = $this->setAndGetOCBCache($request, 17, $profileObj,$bforceFullyDBCall);
    }

    switch (true) {

      case ($profileInfo["COMPLETION"] > 50):
        $MESSAGE = $memMessage['membership_message'];
        break;

      case (!$profileObj->getFAMILYINFO()):
        $MESSAGE[myjsCachingEnums::TOP_PART] = 'Add About Family';
        $MESSAGE[myjsCachingEnums::BOTTOM_PART] = 'Get more interests & responses';
        $MESSAGE[myjsCachingEnums::PAGEID] = '4';
        break;

      case ($memMessage['membership_message']):
        $MESSAGE = $memMessage['membership_message'];
        break;

      case (!$profileObj->getEDUCATION()):
        $MESSAGE[myjsCachingEnums::TOP_PART] = 'Add About Education';
        $MESSAGE[myjsCachingEnums::BOTTOM_PART] = 'Get more interests & responses';
        $MESSAGE[myjsCachingEnums::PAGEID] = '2';
        break;

      case (!$profileObj->getJOB_INFO()):
        $MESSAGE[myjsCachingEnums::TOP_PART] = 'Add About Work';
        $MESSAGE[myjsCachingEnums::BOTTOM_PART] = 'Get more interests & responses';
        $MESSAGE[myjsCachingEnums::PAGEID] = '3';
        break;
    }

    return $MESSAGE;
  }

  public function getPhotoTypeFromId($key){
     		$mapArray=Array('INTEREST_RECEIVED'=>'ProfilePic120Url','MATCH_ALERT'=>'ProfilePic120Url','VISITORS'=>'ThumbailUrl');
     		return $mapArray[$key];

     	}

    public function getExpiring()
    {
		$this->profile=Profile::getInstance();
        $this->loginProfile=LoggedInProfile::getInstance();
        $entryDate = $this->loginProfile->getENTRY_DT();
		$currentTime=time();
		$registrationTime = strtotime($entryDate);
        $showExpiring = 0;
		if(($currentTime - $registrationTime)/(3600*24) >= CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT)
		{
			$showExpiring = 1;
		}
		return $showExpiring;
    }
    
  /**
   * 
   * @param type $request
   * @param type $appVersion
   * @param type $profileObj
   * @return type
   */
  private function setAndGetOCBCache($request, $appVersion = 17, $profileObj,$bForceFullyDB=false)
  {

    $memCacheObject = JsMemcache::getInstance();
    $profileId = $profileObj->getPROFILEID();
    $valArr = NULL;
    $bEnableCache = (myjsCachingEnums::FLAG || $bForceFullyDB) ? false : true;
    
    if ($bEnableCache) {
      $valArr = $memCacheObject->getHashAllValue(myjsCachingEnums::PREFIX . $profileId . '_MESSAGE_BANNER');
    }
    if ($valArr != NULL && is_array($valArr)) {

      $memMessage = '';

      if ($valArr['is_null'] == 1) {
        return NULL;
      }

      if ($valArr['top']) {
        $memMessage['membership_message'][myjsCachingEnums::TOP_PART] = $valArr['top'];
      }
      if ($valArr['bottom']) {
        $memMessage['membership_message'][myjsCachingEnums::BOTTOM_PART] = $valArr['bottom'];
      }
      if ($valArr['pageId']) {
        $memMessage['membership_message'][myjsCachingEnums::PAGEID] = $valArr['pageId'];
      }
    }
    else {
      $memHandlerObj = new MembershipHandler();
      $memMessage = $memHandlerObj->fetchMembershipMessage($request, $appVersion);
      
      if ($bEnableCache) {
        $arr[myjsCachingEnums::IS_NULL] = 0;
        $arr[myjsCachingEnums::TOP_PART] = '';
        $arr[myjsCachingEnums::BOTTOM_PART] = '';
        $arr[myjsCachingEnums::PAGEID] = '';

        if ($memMessage['membership_message'] == NULL) {
          $arr[myjsCachingEnums::IS_NULL] = 1;
        }
        else {
          if (is_array($memMessage['membership_message'])) {
            foreach ($memMessage['membership_message'] as $key => $value) {
              $arr[$key] = $value;
            }
          }
        }
        $timeForCache = myjsCachingEnums::TIME;
        $memCacheObject->setHashObject(myjsCachingEnums::PREFIX . $profileId . '_MESSAGE_BANNER', $arr, $timeForCache);
      }

    }
    return $memMessage;
  }

}
?>
