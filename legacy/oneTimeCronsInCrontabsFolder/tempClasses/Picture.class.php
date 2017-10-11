<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

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
	private   $COL_FILES=998001;
	private   $DIR_FILES=999;

	function getDirectory($picId,$basePath,$noFilePathChk='')
	{
        	if(trim($picId) != '')
	        {
        	        $quotient = $picId/$this->COL_FILES;
                	$first_level = floor($quotient);
			$remainder = $picId%$this->COL_FILES;
			$second_level = floor($remainder/$this->DIR_FILES);
			$file_path = $basePath."$first_level/$second_level";
			if(!$noFilePathChk)
			{
				if(!file_exists($file_path))
				{
					$file_path1 = $basePath.$first_level;
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
	public function getSaveUrl($picType,$picId,$profileId,$type="",$objectType,$directory)
	{
		$uploadDir = $directory."/uploads";
                $saveUrl = "";
                if(!$type)
                        $type=".jpg";
                elseif(!strstr($type,"."))
                        $type=".".$type;

                $photoUrlId=$this->photoEncyption($picId,$profileId);
                if($picType == "canvasPic")
                {
                        $saveUrl = $uploadDir."/".$picType."/".$photoUrlId.$type;
                }
                else
                {
			if($objectType=='screened')
			{
				$saveUrl=$this->getDirectory($picId,$uploadDir."/ScreenedImages/$picType/")."/".$photoUrlId.$type;
			}
			elseif($objectType=='nonScreened')
			{
				$saveUrl=$this->getDirectory($picId,$uploadDir."/NonScreenedImages/$picType/")."/".$photoUrlId.$type;
			}
                }
                return $saveUrl;
	}
        public function getDisplayPicUrl($picType,$picId,$profileId,$type="",$objectType,$path)
        {
                $saveUrl = "";
                if(!$type)
                        $type=".jpg";
                elseif(!strstr($type,"."))
                        $type=".".$type;
                $photoUrlId=$this->photoEncyption($picId,$profileId);
                if($picType == "canvasPic")
                {
                        $saveUrl = $path."/uploads/".$picType."/".$photoUrlId.$type;
                }
                else
                {
			if($objectType=='screened')
				$saveUrl=$this->getDirectory($picId,$path."/uploads/ScreenedImages/$picType/",1)."/".$photoUrlId.$type;
			elseif($objectType=='nonScreened')
				$saveUrl=$this->getDirectory($picId,$path."/uploads/NonScreenedImages/$picType/",1)."/".$photoUrlId.$type;
                }
                return $saveUrl;
        }

	/*************************************Getter/Setter*****************************************************/
}
