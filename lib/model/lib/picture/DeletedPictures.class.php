<?php
/**
** This Class will provide methods related to deleted picture(s) info of a profile.
 * @author Reshu Rajput
 * @created 2013-01-21
*/

class DeletedPictures extends Picture
{
	protected $REASON;
    function __construct()
        {
                $this->pictureType='D';
        }

	 //Getter Setter Methods , NULL is returned in many attributes as they are not described for deleted pictures
	 public function getProfilePicUrl()
        {
                return NULL;
        }
        public function getThumbailUrl()
        {
                return NULL;
        }
        public function getThumbail96Url()
        {
                return NULL;
        }
        public function getReason()
        {
                return $this->REASON;
        }
        public function getMainPicUrl()
        {
                return $this->mainPicUrl;
        }
        public function getPictureType()
        {
                return $this->pictureType;
        }
        public function setReason($REASON)
        {
                $this->REASON = $REASON;
        }
        public function setThumbailUrl($id)
        {
                $this->thumbnailUrl = $id;
        }
        public function setThumbail96Url($id)
        {
                $this->thumbnail96Url = $id;
        }
	public function setProfilePicUrl($id)
        {
                $this->profilePicUrl = $id;
        }
        public function setMainPicUrl($id)
        {
                $this->mainPicUrl = $id;
        }
	

	/** 
        This function is used to set the deleted  picture information having three attributes according to the PICTURE_DELETE_NEW table.
        * @param keyValueArray array of Object-Array
        */
	public function setDetail($keyValueArray)
        {
		if(is_array($keyValueArray))
		{
		 	foreach($keyValueArray as $k=>$v)
                	{
		        	$setArrayAllowed=array("PROFILEID","PICTUREID","REASON","mainPicUrl");
                        	if(in_array($k,$setArrayAllowed))
                        	{
                                	eval('$this->set'.$k.'($v);');
                        	}
                	}	
		}
        }

	// Wrapper functions for PICTURE_DELETE_NEW store 
         public function trackDeletedPhotoDetails($whereConditions)
        {
                $photoObj=new PICTURE_DELETE_NEW;
                $photoObj->trackDeletedPhotoDetails($whereConditions);
        }
        // Functions for PICTURE_DELETE_NEW insertion from PICTURE_FOR_SCREEN_NEWstore 
         public function insertDeletedPhotoDetails($whereConditions)
        {
								
								$photoObj=new PICTURE_DELETE_NEW;
                $photoObj->insertDeletedPhotoDetails($whereConditions);
								$moduleName= array();
								$moduleId=array();
								$imageType=array();
								$status=array();
								$j=0;
								foreach ($whereConditions["PICTUREID"] as $key=>$value)
                {
                        $moduleName[$j]="PICTURE_DELETED" ;
						            $moduleId[$j]=$value;
						            $imageType[$j]="MAIN_PHOTO_URL";
						            $status[$j]="N";
												$j++;
                }
                $imageServer=new ImageServerLog;
                $result=$imageServer->insertBulk($moduleName,$moduleId,$imageType,$status);
														
        }

         public function getDeletedPhotos($profileId)
        {
                $photoObj=new PICTURE_DELETE_NEW;
                $deletedPicArr=$photoObj->getDeletedPhotos($profileId);
                return $deletedPicArr;
        }

	/**Added by Reshu 
        This function is used to set all the urls complete of the picture provided.
        */

        public function setCompletePictureUrl()
        {
                $completeUrlArrayAllowed=array("MainPicUrl");
                foreach($completeUrlArrayAllowed as $v=>$k)
                {
                        $setServer="";
                        eval('$value = $this->get'.$k.'();');
                        if($value)
                        {
				$setServer = PictureFunctions::getCloudOrApplicationCompleteUrl($value);
                                if($setServer)
                                       eval('$this->set'.$k.'($setServer);');
                        }
               }
        }
        
        
        public function edit($paramArr=array(),$pictureId,$profileId)
        {
                $photoObj=new PICTURE_DELETE_NEW;
                
                $status=$photoObj->edit($paramArr,$pictureId,$profileId);
                return $status;
        }


}
?>
