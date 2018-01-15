<?php

class PhotoHandler implements VariableHandler {
  
  private $_token_name;
  private $_token_profile_id;
  private $_photo_class;
  private $_lru;
  private $_var_object;
  private $_photo_type;

  public function __construct($var_object) {
	  $this->_lru = new Cache(LRUObjectCache::getInstance());
	  $this->_var_object=$var_object;
	  $this->_token_name=$var_object->getVariableName();
  }

  public function getActualValue() {
	  $this->_photo_type=$this->_var_object->getParam('photo_type');
	if($this->_photo_type=='const')
	{
		switch($this->_token_name){
		case "MAX_NO_OF_PHOTOS":
			$photo = sfConfig::get("app_max_no_of_photos");
			break;
		case "PHOTO_FORMATS":
			$photo = strtolower(sfConfig::get("app_photo_formats"));
			break;
		case "MAX_PHOTO_SIZE":
			$photo = sfConfig::get("app_max_photo_size");
			break;
		default:
			break;
		}
	}
	elseif($this->_photo_type=='album_link'){
	  	$this->_token_profile_id=$this->_var_object->getParam('receiver_id');
		$this->_token_profile=$this->_lru->get($this->_token_profile_id);
		if($this->_token_profile->getHAVEPHOTO()=='Y'){
			if($this->_token_profile->getPHOTO_DISPLAY() !='C'){
			//Call for photo album link
				$link=LinkFactory::getLink(1);
			$link->setVariable($this->_var_object);
			$album_url=$link->getLinkUrl();
			$img_url=sfConfig::get('app_img_url');
			return '<td width="102" height="25" align="center" style="border:1px solid #c7c7c7; color:#0f529d" background="'.$img_url.'/images/mailer/gry_btn_bg.gif"><a href="'.$album_url.'" style="text-decoration:none; color:#0f529d;"><img src="'.$img_url.'/images/mailer/photoIC.gif" width="23" height="19" hspace="0" vspace="0" border="0" align="absmiddle" />Photo Album</a></td>';
			}
		else{
			return '<td width="102" height="25" align="center"></td>';
		}
		}
		elseif(!$this->_token_profile->getHAVEPHOTO() || $this->_token_profile->getHAVEPHOTO()=='N')
		{
			//Call for detailed profile page
			$link=LinkFactory::getLink(2);
			$link->setVariable($this->_var_object);
			$profile_url=$link->getLinkUrl();
			return '<td width="102" height="25" align="center" style=" color:#0f529d"><a href="'.$profile_url.'" style="text-decoration:underline; color:#0f529d;">Request Photo</a></td>';
		}
		else
		{
			return '<td width="102" height="25" align="center"></td>';
		}
	}
	else
	{
	  	$this->_token_profile_id=$this->_var_object->getParam('profileid');
		$viewer=$this->_lru->get($this->_var_object->getParam('receiver_id',true));
		$viewing=$this->_lru->get($this->_token_profile_id);
		$this->_photo_class = new PictureArray(array($viewing));
		$photo_obj_arr = $this->_photo_class->getProfilePhoto('S', '','',$viewer);
		$photo_obj=$photo_obj_arr[$this->_token_profile_id];
		switch($this->_photo_type){
		case 'profile':
			$photo = $photo_obj ? $photo_obj->getProfilePicUrl() : PictureService::getRequestOrNoPhotoUrl('requestPhoto', 'ProfilePicUrl', $viewing->getGENDER());
			break;
		case 'thumbnail':
			$photo=$photo_obj ? $photo_obj->getThumbailUrl(): PictureService::getRequestOrNoPhotoUrl('requestPhoto', 'ThumbailUrl', $viewing->getGENDER());
			break;
		case 'search':
			$photo=$photo_obj ? $photo_obj->getSearchPicUrl():PictureService::getRequestOrNoPhotoUrl('requestPhoto', 'SearchPicUrl', $viewing->getGENDER());
			break;
		}
	}
    return $photo;
  }
}
