<?php
/*
 *	Author:Sanyam Chopra
 *	This task will fetch pic url's from search_male and search_female JOIN *     picture_new, convert it into complete url and then check according to curl request response whether the data is correct or not. Also, in case thumbailUrl is not present or is incorrect, it is created and updated.
 */

class checkPhotoUrlTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addArguments(array(
		new sfCommandArgument('tableName', sfCommandArgument::REQUIRED, 'tableName'),
        new sfCommandArgument('maxCount', sfCommandArgument::REQUIRED, 'maxCount'),
        new sfCommandArgument('curInst', sfCommandArgument::REQUIRED, 'curInst'),
		));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
            ));

	    $this->namespace        = 'oneTimeCron';
	    $this->name             = 'checkPhotoUrl';
	    $this->briefDescription = 'Checks complete url of a pic to see if there is any error';
	    $this->detailedDescription = <<<EOF
	   This task will fetch pic url's from search_male and search_female JOIN *     picture_new, convert it into complete url and then check according to curl request response whether the data is correct or not. Also, in case thumbailUrl is not present or is incorrect, it is created and updated.
	   Call it with:
	   [php symfony oneTimeCron:checkPhotoUrl tableName maxCount|[INFO]]
EOF;
	}
	protected function execute($arguments = array(), $options = array())
	{	
        // if (!sfContext::hasInstance())
        //     sfContext::createInstance($this->configuration);
        $tableName = $arguments["tableName"];
        $maxCount = $arguments["maxCount"];  //The max count provided should ideally be greater than the limit
        $curInst = $arguments['curInst'];
        $limit = 100;
        $offset  = 0+(($curInst-1)*20000);
        $incrementValue = 100;
        $pictureFieldsArr = array("MainPicUrl", "ProfilePic120Url","ProfilePic235Url","ProfilePicUrl","ProfilePic450Url","MobileAppPicUrl","Thumbail96Url","ThumbailUrl","SearchPicUrl");
        $pictureFieldsArrAlbumPic = array("MainPicUrl","Thumbail96Url");
        if(file_exists(sfConfig::get("sf_upload_dir")."/SearchLogs/photoUrl".$curInst.$tableName.".txt"))
                $offset = file_get_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/photoUrl".$curInst.$tableName.".txt");

        //PICTURE_NEW object
        $picObj = new PICTURE_NEW("newjs_slave");
        
        for($i=0;$i<$maxCount;$i+= $incrementValue)
        {
            file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/photoUrl".$curInst.$tableName.".txt",$offset);
            if($lowerLimit < $maxCount) //change logic depending on maxCount logic
            {
                $picUrlArr = $picObj->getPicUrlArr($tableName,$offset,$limit);  
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
            if($k1 == "PICFORMAT")
            {
                $picFormat = $v1; // to get the PIC Format
            }

            if(in_array($k1,$pictureFieldsArr))
            {
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
                    $this->checkPicDetails($k1,$profileId,$pictureId,$ordering,"B",$mainPicUrl,$originalPicUrl,$picFormat);                     
                    break;
                }
                else
                {                    
                    $result = $this->getCurlResult($v1);
                
                    if($result["http_code"]!="200" && $result["http_code"]!="304")
                    {
                        $this->checkPicDetails($k1,$profileId,$pictureId,$ordering,"I",$mainPicUrl,$originalPicUrl,$picFormat);
                        break;
                    }                    
                }
            }
        }        
    }

    //This function checks if a given url is of ThumbailUrl type and if it is Blank or incorrect, a new url is created and updated. For others, a database entry marking the fact that the data is incorrect is placed. Also,
    public function checkPicDetails($urlType,$profileId,$pictureId,$ordering,$reason,$mainPicUrl,$originalPicUrl,$picFormat)
    {
        if($urlType == "ThumbailUrl")
        {
            $urlType = "thumbnail";
            $this->createThumbnailUrl($urlType,$profileId,$pictureId,$mainPicUrl,$reason,$picFormat);
        }
        elseif($urlType == "OriginalPicUrl" && $originalPicUrl != "")
        {
            $checkUrl = substr($originalPicUrl,0,2);

            if($checkUrl ==  IMAGE_SERVER_ENUM::$cloudUrl)
            {
                $originalPicUrl = str_replace(IMAGE_SERVER_ENUM::$cloudUrl,IMAGE_SERVER_ENUM::$cloudArchiveUrl,$originalPicUrl);
                $paramArr = array("OriginalPicUrl"=>$originalPicUrl);
                $picObj = new PICTURE_NEW("newjs_masterRep");
                $picObj->edit($paramArr,$pictureId,$profileId);
            }
            else
            {
                $this->insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason);
            }
        }
        else
        {
            $this->insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason);
        }
    }

    //This functions creates ThumbailUrl and updates in on the database
    public function createThumbnailUrl($urlType,$profileId,$pictureId,$mainPicUrl,$reason,$picFormat)
    {
        $mainPicUrlType = "MainPicUrl";
        $jeevansathiDomain = "www.jeevansathi.com";
        $screenedPicObj = new ScreenedPicture;

        //main pic complete url is required to be sent in generateImage function
        $mainPicCompleteUrl = PictureFunctions::getCloudOrApplicationCompleteUrl($mainPicUrl);

        //This is to check whether the pic is coming from jeevansathi.com or mediacdn and accordingly take action
        if(strpos($mainPicCompleteUrl,$jeevansathiDomain) === false)
        {
            $mainPicDest = $screenedPicObj->getSaveUrl($mainPicUrlType,$pictureId,$profileId,$picFormat);
            //copies the url to the given local location
             copy($mainPicCompleteUrl,$mainPicDest);
        }
        else
        {
            $mainPicDest = $mainPicCompleteUrl;
        }

        chmod($mainPicDest,0777);

        $destPicName = $screenedPicObj->getSaveUrl($urlType,$pictureId,$profileId,$picFormat);
        $extraPicUrl = $screenedPicObj->getDisplayPicUrl($urlType,$pictureId,$profileId,$picFormat);
        //To give a new name to the url is case they already existed but were incorrect.
        if($reason == "I")
        {
            $destPicName = $destPicName."?1";
            $extraPicUrl = $extraPicUrl."?1";
        }

        $profileObj = LoggedInProfile::getInstance('', $profileId);
        $pictureServiceObj=new PictureService($profileObj);

        //get size of main pic url
        $size = getimagesize($mainPicDest);

        $pictureParamArr = array("1"=>"0","2"=>"0","5"=>$size[0],"6"=>$size[1]);
        $pictureServiceObj->generateImages($urlType,$mainPicDest,$destPicName,$picFormat,$pictureParamArr);

        //to update entry in PICTURE_NEW
        $paramArr = array("ThumbailUrl"=>$extraPicUrl);
        $picObj = new PICTURE_NEW("newjs_masterRep");
        $picObj->edit($paramArr,$pictureId,$profileId);

        //to insert row in ImageServerLog
        $imageServer=new ImageServerLog;
        $result=$imageServer->insertBulk("PICTURE",$pictureId,"ThumbailUrl","N");
    }

    public function insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason)
    {
       $incorrectPicDetailObj = new PICTURE_INCORRECT_PICTURE_DATA("newjs_masterRep");
       $incorrectPicDetailObj->insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason);
       unset($incorrectPicDetailObj);
    }
}
