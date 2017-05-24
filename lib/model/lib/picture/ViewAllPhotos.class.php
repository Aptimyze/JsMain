<?php
class ViewAllPhotos
{
	private $profileObj;

	public function __construct($profileObj)
        {
                $this->profileObj=$profileObj;
        }

	public function setCommonVariables($param,$contactTypeWithLoggedinProfile='')
	{
		if(!$this->profileObj->getHAVEPHOTO())
			$this->profileObj->getDetail("","","HAVEPHOTO"); ///--------test -------
	
		$this->keywords=sfConfig::get("app_social_keywords");
//print_r($this->profileObj);
                $pictureServiceObj=new PictureService($this->profileObj);
				
                $savedPics=$pictureServiceObj->getAlbum($contactTypeWithLoggedinProfile);                              //Fetch All Picture Objects in an array

                if ($savedPics){

                $this->userPics = true;

                foreach((array)$savedPics as $v)                                        //Fetch all Pictures Object Attributes in arrays
                {
                        $savedurls[]=$v->getThumbail96Url();
                        $mainPicUrls[]=$v->getMainPicUrl();
                        $pictureID[]=$v->getPICTUREID();
                        $title[]=$v->getTITLE();
                        $keyword1[]=$v->getKEYWORD();
                        $picType[]=$v->getPictureType();
                }
                $this->countOfPics = count($pictureID);
		
		//Create temporary URL's if required by the slider
                if (count($savedurls)%9 == 0)
                        $temp = count($savedurls);
                else
                        $temp = count($savedurls) + 9-(count($savedurls)%9);

                for ($i=0;$i<$temp;$i++)
                {
                        if ($i<count($savedurls))
                        {
                                $temp_urls[$i] = $savedurls[$i];
                        }
                        else
                        {
                                $temp_urls[$i] = null;
                        }
                }
                //Temporary URL's creation ENDS

                //Set values/arrays to be passed to the template
                $this->allThumbnailPhotos=$temp_urls;
                $this->mainPicArr=$mainPicUrls;
                //print_r($mainPicUrls);
                $this->tempCount = count($temp_urls);
                $this->allPicIds=implode(",",$pictureID);
                $this->titleArr=$title;
                $this->keywordArrStr=$keyword1;
                $this->picIdArr=$pictureID;
                $this->picType=$picType;
                //Setting of values/array ENDS

		if($param == 'none')                       //No picture ID is paased to the viewAllPhotosSuccess template
                {
                        $this->sliderNo = 0;
                        $this->currentPicIndex = "1";                                   //Set Picture Number for the Template
                        $this->frontPicUrl = $mainPicUrls[0];                           //Set Main Pic Url to be displayed
                        $this->currentPicId = $pictureID[0];                            //Set Current Pic Id to be stored in the hidden input in template
                        $this->currentPic_Type = $picType[0];                           //Set Current Pic Id to be stored in the hidden input in template
                        if (trim($keyword1[0]) == "")                                   //If image has no keywords
                        {
                                $this->currentPicKeywords = "";                         //Current Pic Keywords
                                $this->dropdownKeywordsLabel = "";                      //Keywords list display in the disabled dropdown
                        }
                        else                                                            //Else image has keywords
                        {
                                $currentKeywordArr = explode(",",$keyword1[0]);         //keyword1[0] has keywords indexes like 1,2,3 etc.

                                for ($i=0;$i<count($currentKeywordArr);$i++)            //Get keywords names from the indexes
                                {
                                        $current_Pic_Keywords[$i] = $this->keywords[$currentKeywordArr[$i]-1];
                                }
                                $this->currentPicKeywords = implode(", ",$current_Pic_Keywords);    //Make a string of keywords names separated by , to be displayed

                                if (count($current_Pic_Keywords)>2)                             // If keywords are greater than 1
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0].", ".$current_Pic_Keywords[1].", ...";  //1st keywords name followed by 3 dots
                                }
                                else if (count($current_Pic_Keywords)==2)
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0].", ".$current_Pic_Keywords[1];
                                }
                                else
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0];                //1st keyword name or blank
                                }
                        }
		}
                else                                                                    //Template is opened with a picture id like id000<picId>
                {
                        $picId = $param;                 //Get parameter
                        $picId = substr($picId,5);                                      //Get picture ID from parameter
                        $key = array_search($picId,$pictureID);                         //Get index of the picture ID from the array having all the picture ID's
                        if ($key<9)
                                $this->sliderNo = 0;
                        else if ($key>=9 && $key<18)
                                $this->sliderNo = 1;
                        else
                                $this->sliderNo = 2;
                        $this->currentPicIndex = $key+1;                                //Set picture number for template
                        $this->frontPicUrl = $mainPicUrls[$key];                        //Set Main Pic Url for the template
                        $this->currentPicId = $pictureID[$key];                         //Set current picture ID in the hidden input type
                        $this->currentPic_Type = $picType[$key];                                //Set Current Pic Id to be stored in the hidden input in template
                        if (trim($keyword1[$key]) == "")                                //If picture has no keywords
                        {
                                $this->currentPicKeywords = "";                         //Set the keywords list as "" for the template
                                $this->dropdownKeywordsLabel = "";                      //Save the empty list to be displayed in the disabled dropdown 
                        }
                        else                                                            //Else picture has keywords
                        {
                                $currentKeywordArr = explode(",",$keyword1[$key]);      //keyword1[$key] has keywords indexes like 1,2,3 etc

                                for ($i=0;$i<count($currentKeywordArr);$i++)            //Get keyword names from the indexes
                                {
                                        $current_Pic_Keywords[$i] = $this->keywords[$currentKeywordArr[$i]-1];
                                }
                                $this->currentPicKeywords = implode(", ",$current_Pic_Keywords);        //Set the names as string separated by , for the template

                                if (count($current_Pic_Keywords)>2)                             // If keywords are greater than 1
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0].", ".$current_Pic_Keywords[1].", ...";  //1st keywords name followed by 3 dots
                                }
                                else if (count($current_Pic_Keywords)==2)
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0].", ".$current_Pic_Keywords[1];
                                }
                                else
                                {
                                        $this->dropdownKeywordsLabel = $current_Pic_Keywords[0];        //Only 1st keyword or blank
                                }
                        }
                }
		$size = getimagesize($this->frontPicUrl);
		if($size[0]<463)
			$size[0]=463;
		if($size[1]<493)
			$size[1]=493;
		$this->widthOfMainPic = $size[0];
		$this->heightOfMainPic = $size[1];
                }
                else{
                        $this->userPics = false;
                        $this->countOfPics = 0;
                        }

	return $this;
	}
}
?>
