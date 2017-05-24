<?php
/*
 * Author: Akash Kumar
 * This task fetches mobile App pic sizes and inserts into PICTURE.MOBAPPPICSIZE
*/

class MobAppPicSizeTask extends sfBaseTask
{
	private $limit = '100';
	protected function configure()
  	{
                $this->addArguments(array(
                    new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('PROFILEID', sfCommandArgument::OPTIONAL, 'My argument')
		));
                
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'picture';
	    $this->name             = 'MobAppPicSize';
	    $this->briefDescription = 'fetches mobile App pic sizes and inserts into PICTURE.MOBAPPPICSIZE';
	    $this->detailedDescription = <<<EOF
	
	Call it with:

	  [php symfony picture:MobAppPicSize totalScript currentScript PROFILEID] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
                if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

                if($arguments["PROFILEID"]){
                        $profileObj=LoggedInProfile::getInstance('newjs_master',$arguments["PROFILEID"]);
                        $profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");
                        
                        $picObj = new PictureService($profileObj);
                        $ProfilePicObj = $picObj->getProfilePic();
                        if(!$ProfilePicObj)
                        {echo "No ProfilePic";die;}
                        $pictureDetails[$ProfilePicObj->getPICTUREID()] = $ProfilePicObj->getmobileAppPicUrl();
                        
                }
                else{
                        $pictureObj = new PICTURE_NEW();
                        $pictureDetails = $pictureObj->getProfilesNotRecorded($this->limit,$arguments["totalScript"],$arguments["currentScript"]);
                        
                }
                
                foreach($pictureDetails AS $pictureId => $url){
                        if($url){
                                if (stristr($url,'JS/uploadsaaaaaa')) {
                                       $imageLocal=str_ireplace('JS/uploads',JsConstants::$screenedPhotoDir,$url);
                                }
                                else{
                                        $pictureFunctionObj = new PictureFunctions();
                                        $imageLocal=$pictureFunctionObj->getCloudOrApplicationCompleteUrl($url);
                                }
                                $currentSize = getimagesize($imageLocal);
                                if($currentSize!=0)
                                        $size[$pictureId] = $currentSize;
                                else 
                                        $pictureNotFound[] = $pictureId; 
                                
                        }
                }
                
                if(is_array($size)){
                        $mobPicObj = new PICTURE_MobAppPicSize();
                        $mobPicObj->updateSize($size);
                }
                if(is_array($pictureNotFound)){
                        $subject = "Photo Not Found For SIZE";
                        SendMail::send_email("lavesh.rawat@gmail.com,akashkumardtu@gmail.com","'".print_r($pictureNotFound,true)."'",$subject);
                }
                
                
  	}
}
