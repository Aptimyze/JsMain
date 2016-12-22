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

	public function getProfilePicAppV1($profileObj)
	{
		$pictureService = new PictureService($profileObj);
	        $profilePicObj = $pictureService->getProfilePic();
		if($profilePicObj)
			$myPic = $profilePicObj->getThumbailUrl();
                if(!$myPic)
		{
			 if($pictureService->isProfilePhotoUnderScreening() =="Y")
				$myPic = PictureService::getRequestOrNoPhotoUrl('underScreeningPhoto','ThumbailUrl',$profileObj->getGENDER());
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
		$request = sfContext::getInstance()->getRequest();
		$showExpiring = $request->getParameter('showExpiring');
		foreach(self::$informationTypeFields as $key=>$value)
		{
			if(array_key_exists($key,$displayObj))
			{
				if($key == "INTEREST_EXPIRING" && !$showExpiring && !(MobileCommon::isApp()))
				{
					continue;
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
			


		return $displayV1;
        }


private function getBannerMessage($profileInfo) {    
				$MESSAGE=NULL;
				$request = sfContext::getInstance()->getRequest();
				$memHandlerObj = new MembershipHandler();
        	  	$apiAppVersion = $request->getParameter('API_APP_VERSION');
        			if(!empty($apiAppVersion) && is_numeric($apiAppVersion)){
        				$memMessage = $memHandlerObj->fetchMembershipMessage($request,$apiAppVersion);
        				if ($apiAppVersion<21) return $memMessage['membership_message']; // for backward compatibility of Android App
        			} else {
        				$memMessage = $memHandlerObj->fetchMembershipMessage($request);
        			}
			
	$profileObj=LoggedInProfile::getInstance('newjs_master');

	switch(true){

			case ($profileInfo["COMPLETION"]>50):
        	  $MESSAGE=$memMessage['membership_message'];
        	break;

        	case (!$profileObj->getFAMILYINFO()):
        	  $MESSAGE['top']='Add About Family';
          	  $MESSAGE['bottom']='Get more interests & responses';
          	  $MESSAGE['pageId'] = '4';
        	break;

        	case ($memMessage['membership_message']):
        	 $MESSAGE=$memMessage['membership_message'];
        	break;

        	case (!$profileObj->getEDUCATION()):
        		$MESSAGE['top']='Add About Education';
          		$MESSAGE['bottom']='Get more interests & responses';
          		$MESSAGE['pageId'] = '2';
        	break;

        	case (!$profileObj->getJOB_INFO()):
        		$MESSAGE['top']='Add About Work';	
				$MESSAGE['bottom']='Get more interests & responses';
				$MESSAGE['pageId'] = '3';
        	 break;
						}
	return $MESSAGE;					


        		}        




   public function getPhotoTypeFromId($key){
     		$mapArray=Array('INTEREST_RECEIVED'=>'ProfilePic120Url','MATCH_ALERT'=>'ProfilePic120Url','VISITORS'=>'ThumbailUrl');
     		return $mapArray[$key];

     	}


}
?>
