<?php
/** Class AddStory
 * Helps in adding stories to db, works both for backednd and frontend application
*/
class AddStory
{
	/** Add story to SuccessStory
	* @param $successArr Array contains parameter required by StorySuccess lib[exact replica of successstory table
        *@returns lastid of successstory table
	*/
	public static function AddSuccessStory($successArr)
	{
			if($successArr["ID"])
				$sObj=new SuccessStories('',$successArr["ID"]);
			 else
				$sObj = new SuccessStories;
			$filename=$successArr[PHOTO];
	
			unset($successArr[PHOTO]);
			$sObj->UpdateGetVar($successArr);
			
			if($successArr["ID"])
			{
				$sObj->UpdateRecord();
				$lastid = $successArr["ID"];
			}
			else
			{	
				$sObj->ReplaceRecord();			
				$lastid=$sObj->getID();
			}

		 	$type=AddStory::ImageType($_FILES[$filename][type])?AddStory::ImageType($_FILES[$filename][type]):"jpg";
	
			$imageName=$lastid."S.".$type;


			$imagepath=AddStory::UploadPic($filename,"/uploads/NonScreenedImages/story/",$imageName);
			if($imagepath)
			{
				$sObj->startTransaction();
				$sObj->setPIC_URL($imagepath);
				$sObj->UpdateRecord();
				//Insert into cloud table, for latter movement to cloud.
				$imageServerObj=new ImageServerLog;
				$imageServerObj->insertBulk("SUCCESS_STORY",$lastid,"PIC_URL","N");
				$sObj->commitTransaction();
			}
			return $lastid;
	}
	/** Return correct image type , based on given file type
        *@param $type string file type
        *@return String
	*/
	public static function ImageType($type)
	{
		$imageArr=array("image/jpeg"=>"jpg","image/gif"=>"gif","image/png"=>"png");
		return $imageArr[$type];	
	}
	public static function AddIndividualStory($individualArr)
	{
		if($individualArr["SID"])
			$iObj=new IndividualStories('',$individualArr["SID"]);
		else
			$iObj=new IndividualStories;
			
		$mainfile  = $individualArr[MAIN_PIC];
		$framefile = $individualArr[FRAME_PIC];
		$homefile  = $individualArr[HOME_PIC];
		$squareFile = $individualArr[SQUARE_PIC];
		
		unset($individualArr[MAIN_PIC]);
		unset($individualArr[FRAME_PIC]);
		unset($individualArr[HOME_PIC]);
		unset($individualArr[SQUARE_PIC]);
		
		$iObj->UpdateGetVar($individualArr);
			
		if($individualArr["SID"])
		{
			$iObj->UpdateRecord();
			$lastid = $individualArr["SID"];
		}
		else
		{	
			$iObj->ReplaceRecord();			
			$lastid=$iObj->getSID();
		}
		
		$allImages = 1;

		$type=AddStory::ImageType($_FILES[$mainfile][type])?AddStory::ImageType($_FILES[$mainfile][type]):"jpg";		
			
		$imageName=$lastid."M.".$type;
		$imagepath=AddStory::UploadPic($mainfile,"/uploads/ScreenedImages/story/",$imageName);
		if($imagepath && $allImages)
		{
			$iObj->setMAIN_PIC_URL($imagepath);
		}
		else
		 $allImages = 0;
		$type=AddStory::ImageType($_FILES[$framefile][type])?AddStory::ImageType($_FILES[$framefile][type]):"jpg";
	
		$imageName=$lastid."F.".$type;
		$imagepath=AddStory::UploadPic($framefile,"/uploads/ScreenedImages/story/",$imageName);
		if($imagepath && $allImages)
		{
			$iObj->setFRAME_PIC_URL($imagepath);
		}
		else
			$allImages = 0;
		$type=AddStory::ImageType($_FILES[$homefile][type])?AddStory::ImageType($_FILES[$homefile][type]):"jpg";
	
		$imageName=$lastid."H.".$type;
		$imagepath=AddStory::UploadPic($homefile,"/uploads/ScreenedImages/story/",$imageName);
		if($imagepath && $allImages)
		{
			$iObj->setHOME_PIC_URL($imagepath);
		}
		else
			$allImages = 0;
		//Square PIC
		$type=AddStory::ImageType($_FILES[$squareFile][type])?AddStory::ImageType($_FILES[$squareFile][type]):"jpg";			
		$imageName=$lastid."S.".$type;
		$imagepath=AddStory::UploadPic($squareFile,"/uploads/ScreenedImages/story/",$imageName);
		if($imagepath && $allImages)
		{
			$iObj->setSQUARE_PIC_URL($imagepath);
		}
		else
		 $allImages = 0;	
		 
		if($allImages)
		{	
			$iObj->startTransaction();
			$iObj->UpdateRecord();
			//Insert into cloud table, for latter movement to cloud.
			$imageServerObj=new ImageServerLog;
			$moduleName = array('INDIVIDUAL_STORY','INDIVIDUAL_STORY','INDIVIDUAL_STORY','INDIVIDUAL_STORY');
			$moduleId = array($lastid,$lastid,$lastid,$lastid);
			$imageType = array('HOME_PIC_URL','MAIN_PIC_URL','FRAME_PIC_URL','SQUARE_PIC_URL');
			$status = array('N','N','N','N');
			$imageServerObj->insertBulk($moduleName,$moduleId,$imageType,$status);
			$iObj->commitTransaction();
		}
}
	
	/** Upload pic to defined destination
	*@param $filename string temporary upload file name
        *@param $path String where to upload image
        *@param $imageName String image name
        */
	public static function UploadPic($filename,$path,$imageName)
	{
		if($_FILES[$filename]["tmp_name"])
		{
			$fp = fopen($_FILES[$filename]["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($_FILES[$filename]["tmp_name"]));
			fclose($fp);
			$imageObj=ImageUploadFactory::getImageUploadObj($path,$imageName,$fcontent);
			$imagepath=$imageObj->UploadImage();
		}
		if(strlen($imagepath))
			return $imagepath."?".rand();
		return "";	
	}
        
        
        /** Get encryted mail id AND ALSO store entry in table. The encrypted mail id should be sent in the mailer.
         * Takes profile id as input
         * Returns mailer id if execusion is successful else returns false
	*@param profileid  The profile id for which the mailer is generated
        *@return string mailid The encrypted email ID to be sent in the mailer link
        */
	public static function getEncryptedMailerId($profileid,$status='Y')
	{
            if(!$profileid){
                return false;
            }
            $authenticationJsObj = new JsAuthentication();
            $emailLog = new incentive_SUCCESS_STORY_EMAIL_LOG();
            $mailId = $emailLog->insertLogEntry($profileid,$status);
            $mailIdEncrypt=$authenticationJsObj->js_encrypt($mailId);
            if($mailIdEncrypt && $mailId){
                return $mailIdEncrypt;
            }
            else{
                return false;
            }
	}
        
}
