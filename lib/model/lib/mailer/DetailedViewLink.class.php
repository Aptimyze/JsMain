<?php
include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
class DetailedViewLink extends LinkClass{
	public function getLinkUrl($noMailGroup=""){
		global $do_not_send;
		$_linkAddress=parent::getLinkUrl();
		$rec_id=$this->_var_object->getParam('receiver_id');
		if(!$rec_id){
			$do_not_send=true;
			return;
		}
		$checksum=md5($rec_id)."i".$rec_id;
	    $_linkAddress.=strpos('?',$_linkAddress)?"&":"?";
		$_linkAddress.="profilechecksum=$checksum";
		$source = $this->_var_object->getParam('source');
		if($source)
		{
			$_linkAddress.="&responseTracking=$source";
		}
		return $_linkAddress;
	}
	public function trackLink(){
	}
}
