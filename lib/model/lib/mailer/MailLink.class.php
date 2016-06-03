<?php
class MailLink extends LinkClass{
	public function getLinkUrl($noMailGroup=""){
		$_linkAddress=parent::getLinkUrl();
		return "mailto:$_linkAddress";
	}
	public function trackLink(){
	}
}
