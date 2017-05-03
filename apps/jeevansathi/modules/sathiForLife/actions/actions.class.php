<?php

/**
 * marketing actions.
 *
 * @package    jeevansathi
 * @subpackage marketing
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sathiForLifeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //$this->forward('default', 'module');
    $profileDetailsArr = $request->getParameterHolder()->getAll();    
    if($profileDetailsArr["submitForm"] == 1)
    {
      unset($profileDetailsArr["submitForm"]);
    	$this->submitForm($profileDetailsArr);
      $this->setTemplate("formSubmit");
    }
    else
    {
      $this->setTemplate("sathiForLife");
    }    
  }

  public function submitForm($profileDetailsArr)
  {
    $requiredDetailsArr = array("NAME","AGE","PARTNER_NAME","USERNAME","PHONE","EMAIL","DESCRIPTION","PICTURE","VIDEO_URL","SATHI_STORY","TWITTER_HANDLE","INSTA_USERNAME");

    $filesArr = $_FILES;

    if(is_array($filesArr))
    {
      $files = $this->validateFiles($filesArr);
    }
    if($files["Error"]["PICTURE"])
    {
      $this->filesError = $files["Error"]["PICTURE"];      
      return;  
    }
    $validFiles = $files["Valid"];
    if(is_null($files["Error"]) && is_array($validFiles))
    {
      $picFormat = $this->getImageType($validFiles["PICTURE"]["type"]);
      $saveUrl = $this->getSaveUrl($picFormat,$validFiles["PICTURE"]["name"]);
      $displayUrl = $this->getDisplayUrlDoc($picFormat);
      $pictureFunctionsObj = new PictureFunctions();      
      $result = $pictureFunctionsObj->moveImage($validFiles["PICTURE"]["tmp_name"],$saveUrl);      
      if($result)
      {
        $profileDetailsArr["PICTURE"] = $displayUrl;
        foreach($profileDetailsArr as $key=>$values)
        {
          if(!in_array($key, $requiredDetailsArr) || $values == "")
          {
            unset($profileDetailsArr[$key]);
          }  
        }
        $marketingObj = new MARKETING_PROFILE_DETAILS("newjs_masterRep");
        $marketingObj->insertProfileDetails($profileDetailsArr);
        unset($marketingObj);
        $this->successMsg = "Your entry has been saved successfully!";
      }
      else
      {
        $this->filesError = "Error In Image";
      }
    }
  }


  /*This function is used to validate files as per requirement
  * @param files : array of uploaded files
  * @return fileArr : array of errors or valid files
  */

  public function validateFiles($files=null)
  {
    $file_type_array = sfConfig::get("app_photo_formats_to_check");
    $fileArr = null;
    foreach($files as $key=>$value)
    {
      if($value["name"]!="")
      {
        if($value["error"]!=0)
          $fileArr["Error"][$key]="Some Error occurred Please try Again!!";
        elseif(!in_array($value["type"],$file_type_array))
          $fileArr["Error"][$key]="Use a valid image file ('jpg','jpeg','gif')";
        elseif($value["size"] > (sfConfig::get("app_max_photo_size")*1024*1024) )
          $fileArr["Error"][$key]="Use a smaller image (Upto 6 MB)";
        else
          $fileArr["Valid"][$key]=$value;
      }
      
    }
    return $fileArr;
  }

  public function getImageType($type)
  {
    if($type == "image/gif")             //Get the format of pic being uploaded
      $formatOfPic = "gif";
    else if ($type == "image/jpeg")
      $formatOfPic = "jpeg";
    else if ($type == "image/jpg")
      $formatOfPic = "jpg";
    return $formatOfPic;
  }

  public function getSaveUrl($type="",$picName)
  {
    $uploadDir = sfConfig::get("sf_upload_dir")."/sathi/";
    if(!is_dir($uploadDir)){
      mkdir($uploadDir);
    }
    $saveUrl = "";
    if(!$type)
      $type=".jpg";
    else
      $type=".".$type;
    $picName = explode(".",$picName);
    $this->docUrl = $picName[0].rand();
    $saveUrl=sfConfig::get("sf_upload_dir")."/sathi/".$this->docUrl.$type;
    return $saveUrl;
  }

  public function getDisplayUrlDoc($type="")
  {
    $displayUrl = "";
    if(!$type)
      $type=".jpg";
    else
      $type=".".$type;            
    $displayUrl="JS/uploads/sathi/".$this->docUrl.$type;
    return $displayUrl;
  }

}
