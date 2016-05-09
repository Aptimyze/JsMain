<?php
class MembershipLink extends LinkClass{
	public function getLinkUrl($noMailGroup=""){
		$_linkAddress=parent::getLinkUrl();
        $source=$this->_var_object->getParam('source');
        if($source)
		{
			$_linkAddress.=strpos('?',$_linkAddress)?"&":"?";
			$_linkAddress.="from_source=$source";
		}
		return $_linkAddress;
	}
	public function trackLink(){
	}
}
