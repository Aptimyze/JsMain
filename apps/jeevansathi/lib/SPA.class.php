<?php

class SPA {

public function spaRedirect($request, $redirectUrl = ""){

	// Code added to switch to hindi.jeevansathi.com for mobile site if cookie set !
	if($request->getcookie('JS_MOBILE')=='Y'){
       	$redirectUrl = CommonUtility::translateSiteLanguage($request);
	}	
	// End hindi switch code !

	if(MobileCommon::isNewMobileSite() && JsConstants::$SPA['flag'] ){
		$spaUrls = array('login','myjs','viewprofile.php?profilechecksum','MobilePhotoAlbum','static/forgotPassword','profile/mainmenu.php','com? ','P/logout.php','profile/viewprofile.php','mobile_view');
		$nonSpaUrls = array('ownview=1');
		$spa = 0;
		$originalArray = array('https://','http://');
		$replaceArray = array('','');
		$specificDomain = str_replace($originalArray, $replaceArray, $request->getUri());
		$specificDomain = explode('/',$specificDomain,2);
		$specificSubDomain = explode('?',$specificDomain[1],2);
		if($specificDomain[1] == '' || $request->getParameter("newRedirect"))
			$spa = 1;
		elseif(in_array($specificSubDomain[1],$nonSpaUrls) || in_array(substr($specificSubDomain[1],0,9), $nonSpaUrls))
			$spa = 0;
		else {
			foreach ($spaUrls as $url) {
		    	if (strpos($specificDomain[1], $url) !== FALSE) {
		        	$spa = 1;
		        	break;
		    	}
		    }
		}
		}
		if(MobileCommon::isNewMobileSite() && $spa && (strpos($request->getUri(), 'api') === false)) {
			//bot section here.
			$phantomExecutalbe =  JsConstants::$docRoot."/spa/phantomjs-2.1.1/bin/phantomjs";
			$phantomCrawler =  JsConstants::$docRoot."/spa/phantomCrawler.js";

			//$_SERVER['HTTP_USER_AGENT'] .= " Googlebot";
			if (
				strpos($_SERVER['HTTP_USER_AGENT'],"Googlebot") &&
				!strpos($_SERVER['HTTP_USER_AGENT'],"Phantomjs")
				)
			{
				//$url = $request->getUri();
				//this needs to be commented on live code.
				$url = str_replace('https://', 'http://', $request->getUri());
				//$url = "http://xmppdev1.jeevansathi.com/login";
				(exec($phantomExecutalbe." ".$phantomCrawler." ".escapeshellarg($url), $output));
				$print = false;
				foreach ($output as $line) {
					if ( strpos($line,"DOCTYPE html") )
					{
						$print = true;
					}
					if ( $print)
					{
						echo "$line\n";
					}
				}
				die();
			}
			if($redirectUrl!= "")
			{
				header("Location:".JsConstants::$hindiTranslateURL."/spa/dist/index.html#"."?AUTHCHECKSUM=".$request->getParameter('AUTHCHECKSUM'));
			}
			else
			{
				header("Location:".$SITE_URL."/spa/dist/index.html#".$specificDomain[1]);
			}

			die;
		}
	}
}
?>