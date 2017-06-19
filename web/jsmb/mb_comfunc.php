<?php
/**
 * This function will use web service provided by 99 acres to get mobile device info
 * */

/*
  function get_device_info($userAgent){
	if(trim($userAgent)){
		require_once 'XML/RPC2/CachedClient.php';
		try{
			$options=array(
				"cacheDir"=>"/tmp/jsxml_rpc/",
				"lifetime"=>"3600",
				"connectionTimeout"=>3
			);
		$response=$client->process($userAgent);
		return $response;
		}
		catch (XML_RPC2_FaultException $e){
			echo "ERROR:" . $e->getFaultString() ."(" . $e->getFaultCode(). ")";
		}
		catch(Exception $ex){
			echo "Caught Exception: ".$ex->getMessage();
		}
	}
	else 
		return "";
}
*/ 
include_once("mobile_device_detect.php");
function is_mobile($userAgent,$checkTablet=''){
	try{
	if(trim($userAgent))
		$res=mobile_device_detect($userAgent);
	else 
		return 0;
	if(strpos($userAgent,"MSIE 7.0")||strpos($userAgent,"MSIE 9.0")){
		setcookie('JS_MOBILE','N',time()+31536000,"/");
		return 0;
	}
	if(strpos($userAgent,"Googlebot-Mobile")){
		$res['mobileBrowser']="true";
	}
        if($checkTablet=='checkTablet')
                return $res;

	if($res['mobileBrowser'] && $res['is_tablet']!='true')
		return $res;
	else
		return 0;
	}catch(Exception $ex){
		return 0;
	}
}
function set_cookie_mobile($mob_arr){
		$jsMob_cookieStr="Y";
		setcookie('JS_MOBILE',$jsMob_cookieStr,time()+31536000,"/");
		setcookie('chatbar',"yes",time()+31536000,"/");
}
// function for getting the image url for GA for mobile devices
function googleAnalyticsGetImageUrl() {
    $GA_ACCOUNT='MO-179986-1';
    $GA_PIXEL="/ga.php";
    $url = "";
    $url .= $GA_PIXEL . "?";
    $url .= "utmac=" . $GA_ACCOUNT;
    $url .= "&utmn=" . rand(0, 0x7fffffff);

    $referer = $_SERVER["HTTP_REFERER"];
    $query = $_SERVER["QUERY_STRING"];
    $path = $_SERVER["REQUEST_URI"];

    if (empty($referer)) {
      $referer = "-";
    }
    $url .= "&utmr=" . urlencode($referer);

    if (!empty($path)) {
      $url .= "&utmp=" . urlencode($path);
    }

    $url .= "&guid=ON";

    return str_replace("&", "&amp;", $url);
  }

?>
