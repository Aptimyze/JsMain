<?php
include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
class ResponseTrackingLink extends LinkClass{
	public function getLinkUrl($noMailGroup=""){
		$_linkAddress=parent::getLinkUrl();
        $rec_id=$this->_var_object->getParam('receiver_id');
		$checksum=md5($rec_id)."i".$rec_id;
	    $_linkAddress.=strpos('?',$_linkAddress)?"&":"?";
		$_linkAddress.="profilechecksum=$checksum";
		$source=$this->_var_object->getParam('source');
        if($source == "eoi")
        {
			$_linkAddress.="&responseTracking=".JSTrackingPageType::EOI_MAILER;
		}
		elseif($source == "yn")
		{
			$_linkAddress.="&responseTracking=".JSTrackingPageType::YN_MAILER;
		}
		elseif($source == "ei")
		{
			$_linkAddress.="&responseTracking=".JSTrackingPageType::EXPIRING_INTEREST_MAILER;
		}
		elseif($source == "eoiFilter")
		{
			$_linkAddress.="&responseTracking=".JSTrackingPageType::EOI_FILTER_MAILER;
		}
		
		return $_linkAddress;
	}
	public function trackLink(){
	}
}
