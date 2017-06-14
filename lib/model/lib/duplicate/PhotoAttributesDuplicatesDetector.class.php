<?php
/**
  * class PhotoAttributesDuplicatesDetector
  * This class is used to find duplicate profiles using photo atributes matching like width,height,datetime,imagesize
  * @author : lavesh
**/
class PhotoAttributesDuplicatesDetector extends DuplicateDetector  
{
	private $crawlerDuplicateDetector;
	private $rawCrawlerDuplicate;
	private $TYPE="PHOTO-ATTRIBUTE";

	/**
	 * This sets the profileid whose duplicates are to be found and the reason in the RawDuplicate object
	**/
	public function __construct(DuplicateDetector $duplicateDetector)
	{
		$this->crawlerDuplicateDetector=$duplicateDetector;
		$this->rawCrawlerDuplicate=new RawDuplicate();
		$this->rawCrawlerDuplicate->setProfileid1(LoggedInProfile::getInstance()->getPROFILEID()); //profile whose duplicates are to be found
		$this->rawCrawlerDuplicate->setReason($this->TYPE);
	}


	/**
	* This function gets the duplicate profiles and sets them in the rawDuplicate object.
	**/
	public function checkDuplicate() 
	{
		$duplicateObj=$this->crawlerDuplicateDetector->checkDuplicate();

		/*LoggedIn Profile Detail*/
		$myProfileId = LoggedInProfile::getInstance()->getPROFILEID();
		$myGender  = LoggedInProfile::getInstance()->getGENDER();
		$myCaste   = LoggedInProfile::getInstance()->getCASTE();
		$myMtongue = LoggedInProfile::getInstance()->getMTONGUE();
		$myAge     = LoggedInProfile::getInstance()->getAGE();


		/* get all screened pictureids of the loggedin-user which are not cheked for duplication. */
		$duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION = new duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION;
		$outerArr = $duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION->get($myProfileId);
		if(is_array($outerArr))
		{
			/** Import Duplication **/
			$ts = time();
			$ts-=7*30*24*60*60;
			$start_dt=date("Y-m-d",$ts);

			$NEWJS_PHOTO_IMPORT_USERDATA = new NEWJS_PHOTO_IMPORT_USERDATA;
			$importArr = $NEWJS_PHOTO_IMPORT_USERDATA->get($myProfileId,'',$start_dt);
			if(is_array($importArr))
			foreach($importArr as $k=>$v)
			{
				$uid = $v["UNIQUE_ID"];
				$source = $v["SOURCE"];
				$importArr1 = $NEWJS_PHOTO_IMPORT_USERDATA->get('',$source,'',$uid,$myProfileId);	

				/*****/
				if(is_array($importArr1))
				foreach($importArr1 as $kk=>$vv)
				{
					$dupId = $vv["PROFILEID"];
					$key = "source:".$vv["SOURCE"]."  UID".$vv["UNIQUE_ID"]."((".$myProfileId."-".$dupId."))";
					$final[$key][0] = $dupId;
					$final[$key][1] = 'P';
				}
				/*****/
			}
			unset($importArr);
			unset($importArr1);
			/** Import Duplication **/


			foreach($outerArr as $k=>$v)
			{
				$profileid = $v["PROFILEID"];
				$picId1 = $v["PICTUREID"];
				$deletePicIdAtEnd[] = $picId1;
				/* 
				getting picture details of the picture which needed to be matched with other pictures.
				*/
				$NEWJS_PICTURE_DETAILS = new NEWJS_PICTURE_DETAILS;
				$whereCriteria["SCREENED_PICTUREID"] = $picId1;
				$whereCriteria["PROFILEID"] = $profileid;
				$temp = $NEWJS_PICTURE_DETAILS->get($whereCriteria);
				if(is_array($temp) && $temp[0])
				{
					unset($whereCriteria);
					$arr = $temp[0];
					unset($temp);	
					$picId1 = $arr["SCREENED_PICTUREID"];
					$probableCriteriaExclude["PROFILEID"] = $profileid;
					$probableCriteria['SIZE']             = $arr["SIZE"];
					$probableCriteria['HEIGHT']           = $arr["HEIGHT"];
					$probableCriteria['WIDTH']            = $arr["WIDTH"];
					$probableCriteria['CAMERA_DATETIME']  = $arr["CAMERA_DATETIME"];
					$probableCriteria['FOCAL_LENGTH']     = $arr["FOCAL_LENGTH"];
					$probableCriteria['MODEL']            = $arr["MODEL"];

					/*
					* creating array of list of profileids which are suspected to be probable(P) or duplicate(D).
					*/		
					$arr = $NEWJS_PICTURE_DETAILS->get($probableCriteria,$probableCriteriaExclude);
					if(is_array($arr))
					{
						unset($keyArr);
						foreach($arr as $k=>$v)
						{
							if($probableCriteria['CAMERA_DATETIME']=='0000-00-00 00:00:00')
							{
								$dupOrProb = 'P';
								$suspectedProfiles[] = $v["PROFILEID"];
							}
							else
							{
								$dupOrProb = 'D';
								$suspectedProfiles[] = $v["PROFILEID"];
							}
							if($arr["SCREENED_PICTUREID"])
								$picId2 = $v["SCREENED_PICTUREID"]."(S)";
							else	
								$picId2 = $v["UNSCREENED_PICTUREID"]."(N)";
							$comments[$v["PROFILEID"]] = "PicId1:".$picId1." , PicId2:".$picId2;
						}
						unset($arr);	
						unset($probableCriteria);
						if(is_array($suspectedProfiles))
						{
							/* Getting Profile Information of suspected profiles  */
							$ProfileArray = new ProfileArray();		
							$whereConditions["PROFILEID"] = implode(",",$suspectedProfiles);

							$profieObjarr = $ProfileArray->getResultsBasedOnJprofileFields($whereConditions,'','',"GENDER,PROFILEID,CASTE,MTONGUE,AGE","JPROFILE");
							unset($suspectedProfiles);
							unset($whereConditions);

							$RevampCasteFunctions = new RevampCasteFunctions;
							if($dupOrProb=='P')
							/* Gender,Community(all hindi considered as 1),Caste Group, Age(+-1) checks*/
							{
								foreach($profieObjarr as $k=>$v)
								{
								if($v->getGENDER() == $myGender) 
								{
									if($v->getMTONGUE() == $myMtongue || (in_array($myMtongue,FieldMap::getFieldLabel('allHindiMtongues','',1))==in_array($v->getMTONGUE(),FieldMap::getFieldLabel('allHindiMtongues','',1))))
									{
										if($RevampCasteFunctions->sameGrpOrCaste($v->getCASTE(),$myCaste))
										{
											if(abs($v->getAGE()-$myAge)<=1)
											{
											$pid = $v->getPROFILEID();
											$key = $comments[$pid];
											$final[$key][0] = $pid;
											$final[$key][1] = 'P';
											}
										}
									}
								}			
								}
							}
							else
							/* DuplicateChecks : only Gender*/
							{
								foreach($profieObjarr as $k=>$v)
								{
									if($v->getGENDER() == $myGender) 
									{
										$pid = $v->getPROFILEID();
										$key = $comments[$pid];
										$final[$key][0] = $pid;
										$final[$key][1] = 'D';
									}
								}
							}
						}
					}
				}
			}
		}

		/* mark here */
		if(is_array($final))
		{
			foreach($final as $k=>$v)
			{
				$objName = "rawCrawlerDuplicate".$v[0];
				$this->$objName = clone($this->rawCrawlerDuplicate);
				$commentA = explode("((",$k);	
				$commentA = $commentA[0];
				$this->$objName->setComments("$commentA");
				$this->$objName->setProfileid2($v[0]); //profile found as a duplicate
				if($v[1] == 'D')
					$this->$objName->setIsDuplicate(IS_DUPLICATE::YES);
				else
					$this->$objName->setIsDuplicate(IS_DUPLICATE::PROBABLE);
				$duplicateObj->addRawDuplicateObj($this->$objName);
			}
		}
		/* mark here */

		//print_r($duplicateObj);die;
		if($deletePicIdAtEnd)
		{
			$tempStr = implode(",",$deletePicIdAtEnd);
			$duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION->del($tempStr);
		}
		unset($dupOrProb);
		unset($comments);
		unset($final);
		unset($outerArr);
		unset($profieObjarr);
		return $duplicateObj;
	}
} 
?>
