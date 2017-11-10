<?php

class SPA {

public function spaRedirect($request, $redirectUrl = ""){

	// Code added to switch to hindi.jeevansathi.com for mobile site if cookie set !
	if($request->getcookie('JS_MOBILE')=='Y'){
       	$redirectUrl = CommonUtility::translateSiteLanguage($request);
	}	
	// End hindi switch code !

	if((MobileCommon::isNewMobileSite() || $request->getParameter("mobile_view") == 'Y') && JsConstants::$SPA['flag'] && !strpos($_SERVER['HTTP_USER_AGENT'],"Googlebot")){
		$spaUrls = array('login','myjs','viewprofile.php?profilechecksum','MobilePhotoAlbum?profilechecksum','static/forgotPassword','profile/mainmenu.php','com? ','P/logout.php','profile/viewprofile.php','mobile_view','login_home');
		$nonSpaUrls = array('ownview=1');
		$spa = 0;
		$originalArray = array('https://','http://');
		$replaceArray = array('','');
		$specificDomain = str_replace($originalArray, $replaceArray, $request->getUri());
		$specificDomain = explode('/',$specificDomain,2);
		$specificSubDomain = explode('?',$specificDomain[1],2);
			// die("above");
		// die(var_dump($request->getParameter("mobile_view") == 'Y'));
		if($specificDomain[1] == '' || $request->getParameter("newRedirect") || $request->getParameter("mobile_view") == 'Y' )
		{
			// echo "Inside";
			// die("Inside.");
			$spa = 1;
		}
		elseif(in_array($specificSubDomain[1],$nonSpaUrls) || in_array(substr($specificSubDomain[1],0,9), $nonSpaUrls))
			$spa = 0;
		else {
			foreach ($spaUrls as $url) {
		    	if (strpos($specificSubDomain[0], $url) !== FALSE) {
		        	$spa = 1;
		        	break;
		    	}
		    }
		}
		}
		// die(var_dump($spa));
		// die(var_dump(MobileCommon::isNewMobileSite()));
		if((MobileCommon::isNewMobileSite() || $request->getParameter("mobile_view") == 'Y')  && $spa && (strpos($request->getUri(), 'api') === false)) {
			$this->setMobileCookies();
			// die("Inside.");
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
	 private function setMobileCookies(){
	  	@setcookie('NEWJS_DESKTOP',"",0,"/");
	  	unset($_COOKIE['NEWJS_DESKTOP']);
	  	@setcookie('JS_MOBILE','Y',0,"/");
	//$this->getResponse()->setCookie('NEWJS_DESKTOP','',0,"/");
	//$this->getResponse()->setCookie('NEWJS_DESKTOP','',-1,"/");
	//$this->getResponse()->setCookie('JS_MOBILE','Y',0,"/");
  }
}
?>