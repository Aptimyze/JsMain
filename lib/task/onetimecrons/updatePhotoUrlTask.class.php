<?php
/*
 *	Author:Esha Jain
 */

class updatePhotoUrlTask extends sfBaseTask
{
	protected function configure()
	{
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
            ));

	    $this->namespace        = 'oneTimeCron';
	    $this->name             = 'updatePhotoUrl';
	    $this->briefDescription = 'Checks complete url of a pic to see if there is any error';
	    $this->detailedDescription = <<<EOF
	   This task will fetch pic url's from search_male and search_female JOIN *     picture_new, convert it into complete url and then check according to curl request response whether the data is correct or not. Also, in case thumbailUrl is not present or is incorrect, it is created and updated.
	   Call it with:
	   [php symfony oneTimeCron:updatePhotoUrl]
EOF;
	}
	protected function execute($arguments = array(), $options = array())
	{	
        	if (!sfContext::hasInstance())
        		sfContext::createInstance($this->configuration);
	$maxCount = 10000;
        $limit = 800;
        $offset  = 0;
        $incrementValue = 800;
        $pictureFieldsArr = ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS;
        $pictureFieldsArrAlbumPic = ProfilePicturesTypeEnum::$PICTURE_FIELD_FOR_ALBUM_PICS;
        
        $picObj = new PICTURE_INCORRECT_PICTURE_DATA("newjs_slave");
        
        for($i=0;$i<$maxCount;$i+= $incrementValue)
        {
            if($lowerLimit < $maxCount) //change logic depending on maxCount logic
            {
                $picUrlArr = $picObj->getPicUrlArr($offset,$limit);  
                $offset += $incrementValue;
            } 
            
            if(is_array($picUrlArr))
            {
                foreach($picUrlArr as $key=>$value)
                {   
                    $profileId = $value["PROFILEID"];
                    $ordering = $value["ORDERING"];
                    $pictureId = $value["PICTUREID"];
                    
                    if($ordering == 0) // i.e. it is the profile pic
                    {   
                        $this->checkPicUrl($value,$pictureFieldsArr,$profileId,$pictureId,$ordering);
                    }
                    else  // Album Pic
                    {
                        $this->checkPicUrl($value,$pictureFieldsArrAlbumPic,$profileId,$pictureId,$ordering);
                    }
                }
            if(is_array($this->donePictureIds))
            {
                $picObj1 = new PICTURE_INCORRECT_PICTURE_DATA("master");
                $picObj1->deleteIncorrectPicDetail($this->donePictureIds);
print_r($this->donePictureIds);
                unset($this->donePictureIds);
                unset($picObj1);die;
            }
            }
        }
	}

    //This function will make a curl request for the given url and will return the result
    public function getCurlResult($url)
    {
        $completeUrl = PictureFunctions::getCloudOrApplicationCompleteUrl($url);

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$completeUrl);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_NOBODY,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $result=curl_getinfo($ch);
        curl_close ($ch);

        return $result;
    }

    //This function is used to check if a PIC url is blank or not. The data is inserted in a table if the url is blank or the http response is not 200
    public function checkPicUrl($value,$pictureFieldsArr,$profileId,$pictureId,$ordering)
    {
        foreach($value as $k1=>$v1)
        {
		$newValue[$k1]=$v1;
            if(in_array($k1,$pictureFieldsArr))
            {
		if($v1!='')
		{
                    $result = $this->getCurlResult($v1);
                    if($result["http_code"]!="200" && $result["http_code"]!="304")
		    {
			$newValue[$k1]='';
			$v1='';
		    }
		}
                if($k1 == "MainPicUrl")
                {
                    $mainPicUrl = $v1;
                }                
                if($k1 == "OriginalPicUrl")
                {
                    $originalPicUrl = $v1;
                }
                if($v1 == '')
                {
		    $newValue[$k1]='';
		    $blankArr[]=$k1;
                }
            }
        }        
	$this->checkPicDetails($newValue,$profileId,$pictureId,$ordering,$mainPicUrl,$originalPicUrl,$blankArr,$value);
    }

    public function checkPicDetails($valueArr,$profileId,$pictureId,$ordering,$mainPicUrl,$originalPicUrl,$blankArr,$originalValArr)
    {
	$countBlank = count($blankArr);
	file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/pictureDelete.txt",$profileId.":::".$pictureId.":::::".var_export($valueArr,true)."\n",FILE_APPEND);
	if($countBlank==0 ||($countBlank==1 && in_array("OriginalPicUrl",$blankArr)))
	{
		return;
	}
        $profileObj = new LoggedInProfile('',$profileId);
        $profileObj->getDetail('', '', '*');
	$pictureServiceObj=new PictureService($profileObj);
	if($mainPicUrl=='' && $originalPicUrl=='')
	{
		if($ordering==0)
		{
/*
			$newProfilePicId='';
			$pics=$pictureServiceObj->getAlbum();
			if($pics)
			{
				foreach((array)$pics as $v)
				{
					if($v->getORDERING()==1)
					{
						$newProfilePicId = $v->getPICTUREID();
						break;
					}
				}
			}
			if($newProfilePicId)
			{
				$whereArr["PICTUREID"] = $newProfilePicId;
				$whereArr["PROFILEID"] = $profileId;
				$pictureObj=$pictureServiceObj->getPicDetails($whereArr);
				$status=$pictureServiceObj->setProfilePic($pictureObj[0]);
			}
*/
		}
		else
		{
			echo $pictureId.",";
			$bkpObj = new PICTURE_NEW_BKP;
			$bkpObj->ins($originalValArr);
		//	$pictureServiceObj->deletePhoto($pictureId,$profileId,"other");
			$this->donePictureIds[]=$pictureId;
		}
		return;
	}
/*
	if(($mainPicUrl!='' && $ordering==0 &&($countBlank>1||($countBlank==1&& !in_array("OriginalPicUrl",$blankArr))))
		||
		($mainPicUrl=='' && $originalPicUrl!=''))
	{
		$whereArr["PICTUREID"] = $pictureId;
		$whereArr["PROFILEID"] = $profileId;
		$pictureObj=$pictureServiceObj->getPicDetails($whereArr);
                $updateArray = $this->getNonScreenedObjectArray($pictureObj[0]);
		$PICTURE_FOR_SCREEN_NEW = new NonScreenedPicture;
                $PICTURE_FOR_SCREEN_NEW->setDetail($updateArray);
                $pictureServiceObj->addPhotos($PICTURE_FOR_SCREEN_NEW);
		return;
	}
*/
    }
        public function getNonScreenedObjectArray($picObj)
        {
                foreach(ProfilePicturesTypeEnum::$PICTURE_SCREENED_SIZES_FIELDS as $k=>$v)
                {
/*
                        if($k=="TITLE" && $picObj->getUNSCREENED_TITLE())
                                eval('$updateArray['.'"'.$v.'"'.']=$picObj->getUNSCREENED_TITLE();');
		      else
		      {
*/
                                eval('$updateArray['.'"'.$v.'"'.']=$picObj->get'.$v.'(1);');
                                $updateArray[$v] = PictureFunctions::getPictureServerUrl($updateArray[$v]);
//                        }
                }
                return $updateArray;
        }
}
