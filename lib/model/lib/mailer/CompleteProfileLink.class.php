<?php
class CompleteProfileLink extends LinkClass{
	public function getLinkUrl($noMailGroup=""){
		$_linkAddress=parent::getLinkUrl();
		$sender_id=$this->_var_object->getParam('profileid');
		$lru = new Cache(LRUObjectCache::getInstance());
		$profileObj=$lru->get($this->_var_object->getParam("profileid"),1);
		$edit_layer_obj=new EditOnFtoContactConfirmation($profileObj,1);
		$edit_layer=$edit_layer_obj->current_layer;
		switch($edit_layer){
			case 'CUH':
				$editWhat='AstroData';
				break;
			case 'PEO':
				$editWhat='EduOcc';
				break;
			case 'PFD':
				$editWhat='FamilyDetails';
				break;
			case 'PLA':
				$editWhat='LifeStyle';
				break;
		}
		$_linkAddress.="&EditWhatNew=$editWhat";
		return $_linkAddress;
	}
	public function trackLink(){
	}
}
