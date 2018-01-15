<?php
abstract class Picture
{
	protected $PICTUREID;
	protected $PROFILEID;
	protected $ORDERING;
	protected $TITLE;
	protected $KEYWORD;
	protected $UPDATED_TIMESTAMP;
	protected $PICFORMAT;
	protected $pictureType;
	protected $mainPicUrl;
	protected $profilePicUrl;
	protected $thumbnailUrl;
	protected $thumbnail96Url;
	protected $MobileAppPicUrl;
        protected $ProfilePic120Url;
	protected $ProfilePic235Url;
	protected $ProfilePic450Url;
        protected $SCREEN_BIT;
	private   $COL_FILES=998001;
	private   $DIR_FILES=999;

	/*
	This Function is used to create photo directory structure.
	Non-Screened : When we need to create directory chmod -R 777 is required.This is done on application server only.
	Screened     : We need to move screened images from application server to photo server.This is done by ftp. 
	@param picId unique picture id
		
	@param basePath  path from where images need to fetched.
	@param noFilePathChk  we will chk for file path existance. eg for fetching case.
	@param $migrate ftp Object for creating directory and moving files from application server to photo server.
	*/
	function getDirectory($picId,$basePath,$noFilePathChk='',$migrate="")
	{
        	if(trim($picId) != '')
	        {
        	        $quotient = $picId/$this->COL_FILES;
                	$first_level = floor($quotient);
			$remainder = $picId%$this->COL_FILES;
			$second_level = floor($remainder/$this->DIR_FILES);
			$file_path = $basePath."$first_level/$second_level";
			$file_path1 = $basePath.$first_level;
			if(!$noFilePathChk)
			{
				if ($migrate)
				{
					if($migrate->getGetCase()!='1')
					{
						$migrate->makeDir($file_path1);
						$migrate->makeDir($file_path);
					}
				}
				else if(!file_exists($file_path))
				{
					if(!file_exists($file_path1))
						$flag=1;
					mkdir($file_path, 0777, true);
					if($flag)
						shell_exec("chmod -R 777 $file_path1");
					else
						shell_exec("chmod -R 777 $file_path");

					if(!file_exists($file_path))
						return false;
				}
			}
        	        return $file_path;
	        }
        	else
                	return false;
	}

        public function photoEncyption($picId,$profileId)
        {
                $picCrypt=md5($picId);
                $profileIdCrypt=md5($profileId);
		$curTime = time();
                $photoUrlId=$picId."ii".$picCrypt."ii".$profileIdCrypt;
                return $photoUrlId;
        }

	/************************************abstract functions*************************************************/
	abstract protected function setProfilePicUrl($PICTUREID);
	abstract protected function getProfilePicUrl();
	abstract protected function setThumbailUrl($PICTUREID);
	abstract protected function getThumbailUrl();
	abstract protected function setMainPicUrl($PICTUREID);
	abstract protected function getMainPicUrl();
	abstract protected function setThumbail96Url($PICTUREID);
	abstract protected function getThumbail96Url();
	/************************************abstract functions*************************************************/

	/*************************************Getter/Setter*****************************************************/
	public function getPICTUREID() 
	{ 
		return $this->PICTUREID; 
	} 
	public function getPROFILEID() 
	{ 
		return $this->PROFILEID; 
	} 
	public function getORDERING() 
	{ 
		return $this->ORDERING; 
	} 
	public function getTITLE() 
	{ 
		return $this->TITLE; 
	} 
	public function getKEYWORD() 
	{ 
		return $this->KEYWORD; 
	} 
	public function getUPDATED_TIMESTAMP() 
	{ 
		return $this->UPDATED_TIMESTAMP; 
	} 
	public function getPICFORMAT() 
	{ 
		return $this->PICFORMAT; 
	} 

	public function setPICTUREID($x) 
	{ 
		$this->PICTUREID = $x; 
	} 
	public function setPROFILEID($x) 
	{ 
		$this->PROFILEID = $x; 
	} 
	public function setORDERING($x) 
	{ 
		$this->ORDERING = $x; 
	} 
	public function setTITLE($x) 
	{ 
		$this->TITLE = $x; 
	} 
	public function setKEYWORD($x) 
	{ 
		$this->KEYWORD = $x; 
	} 
	public function setUPDATED_TIMESTAMP($x) 
	{ 
		$this->UPDATED_TIMESTAMP = $x; 
	}
	public function setPICFORMAT($x) 
	{ 
		$this->PICFORMAT = $x; 
	} 
	public function getSaveUrlPicture($picType,$picId,$profileId,$type="",$objectType,$migrate="")
	{
                $saveUrl = "";
                if(!$type)
                        $type=".jpeg";
                elseif(!strstr($type,"."))
                        $type=".".$type;

                $photoUrlId=$this->photoEncyption($picId,$profileId);
                if($picType == "canvasPic")
                {
                        $saveUrl = sfConfig::get("sf_upload_dir")."/".$picType."/".$photoUrlId.$type;
                }
                else
                {
			if($objectType=='screened')
			{
				//$saveUrl=$this->getDirectory($picId,sfConfig::get("app_screened_dir_path")."/ScreenedImages/$picType/",'',$migrate)."/".$photoUrlId.$type;
				$saveUrl=$this->getDirectory($picId,sfConfig::get("sf_upload_dir")."/ScreenedImages/$picType/")."/".$photoUrlId.$type;
			}
			elseif($objectType=='nonScreened')
			{
				$saveUrl=$this->getDirectory($picId,sfConfig::get("sf_upload_dir")."/NonScreenedImages/$picType/")."/".$photoUrlId.$type;
			}
                }
                return $saveUrl;
	}
        public function getDisplayPicUrlPicture($picType,$picId,$profileId,$type="",$objectType,$imgAttachName="")
        {
		$saveUrl = "";
		if($picType == "MailImages")
		{
			$saveUrl = IMAGE_SERVER_ENUM::$appPicUrl."/uploads/".$picType."/".$imgAttachName;
		}
		else
		{
			if(!$type)
				$type=".jpeg";
			elseif(!strstr($type,"."))
				$type=".".$type;
			$photoUrlId=$this->photoEncyption($picId,$profileId);
			if($picType == "canvasPic")
			{
				$saveUrl = sfConfig::get("app_photo_url")."/uploads/".$picType."/".$photoUrlId.$type;
			}
			else
			{
				if($objectType=='screened')
					$saveUrl=$this->getDirectory($picId,IMAGE_SERVER_ENUM::$appPicUrl.IMAGE_SERVER_ENUM::getImageServerEnum($profileId,'1',1)."/uploads/ScreenedImages/$picType/",1)."/".$photoUrlId.$type;
				elseif($objectType=='nonScreened')
					$saveUrl=$this->getDirectory($picId,IMAGE_SERVER_ENUM::$appPicUrl.IMAGE_SERVER_ENUM::getImageServerEnum($profileId,'1',1)."/uploads/NonScreenedImages/$picType/",1)."/".$photoUrlId.$type;
			}
		}
                return $saveUrl;
        }

	/*************************************Getter/Setter*****************************************************/
}
