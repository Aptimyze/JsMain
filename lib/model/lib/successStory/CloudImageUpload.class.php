<?php
/**
 * 
/**
 *CLASS CLOUDIMAGEUPLOAD 
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   successStory
 * @author    nikhil dhiman
 * @copyright 2012 
 * @version   SVN: 9619
  */
class CloudImageUpload
{
	private $filePath;
	private $fileContent;
	private $fileName;
	/**
         * 
         * Used to initialize object of ImageUpload  class
         * @param String $path image path
         * @param String $name name of image
         * @param Blob $content image contents
         */
	function __construct($path,$name,$content)
	{
		$this->filePath=$path."/".$name;
		if(is_writable(sfConfig::get(sf_web_dir).$this->filePath))
			throw new JsException("","Not writable $path");
		if(!$content)
			throw new JsException("","imagecontent is blank");
		if(!$name)
			throw new JsException("","Name is blank");
		$this->fileName=$name;
		$this->fileContent=$content;
	}
	/**
	*
	* Used to upload image on server and Absolute path
	* @returns String $path of images
	* @errors returns false
	*/
	public function UploadImage()
	{
		$imagePath=sfConfig::get(app_img_url);
		$web_dir=sfConfig::get("sf_web_dir");
		//  echo $filepath;die;
		$file = fopen(sfConfig::get(sf_web_dir).$this->filePath,"w+");
		if($file)
		{
			fwrite($file,$this->fileContent);
			fclose($file);
		}
		else
			return false;

		return $imagePath.$this->filePath;	
	}
}
