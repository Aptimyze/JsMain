<?php

/**
 * @class JsTrackingHelper
 * @brief Tracks Js page load time of various components
 * @author Tanu Gupta
 * @created 04-07-2011
 */

class JsTrackingHelper {

public static function getHeadTrackJs() {
    $returnData = <<<MARKUP
        <script>
    jsb9init_first();
    </script>
MARKUP;
    return $returnData;
}

public static function recordTimeStamp($key){
//        $key = $params['keyVal'];
  //      return $key;
    static $k = 0;
    if($key == "") {
        $key = $k;
        $k++;
    }
    $returnData = <<<MARKUP
    <script>
    var jsb9date = new Date();
    jsb9recordTimes["$key"]=jsb9date.getTime();
    </script>
MARKUP;
return $returnData;
}

public static function jsb9recordServerTime($key, $time) {
  //      $key = $params['key'];
//        $key = $params['time'];
        $returnData = <<<MARKUP
        <script>
        jsb9recordTimes["$key"]=$time;
        </script>
MARKUP;
return $returnData;
}

public static function setJsLoadFlag($key) {
//        $key = $params['val'];
        $returnData = <<<MARKUP
        <script>
        //alert("setting JSLoad $key");
        jsLoadFlag = $key;
	var jsLogin_layer=1;
        </script>
MARKUP;
return $returnData;
}


public static function getTailTrackJs($serverTime,$flag_unload,$flag_load,$url,$optionaljsb9Key="") {
//        $serverTime = $params['serverTime'];
  //      $flag_unload = $params['flag_unload'];
    //    $flag_load = $params['flag_load'];
      //  $url = $params['url'];
if(!$serverTime)
{
	global $jsb9_track_stime;
	if($jsb9_track_stime)
	$serverTime=microtime(true)-$jsb9_track_stime;
}
//return null;
    $returnData = <<<MARKUP

    <script>
    jsb9init_fourth($serverTime,$flag_unload,$flag_load,"$url","$optionaljsb9Key");
    </script>
MARKUP;
    return $returnData;
}

/**
 * sendDeveloperTrackMail()
 * Generic method to send mail, Which can used to track some scenario which are rare to occur or
 * can be used to track some cases. Mail be will be send on all the mail id provided in array
 */
public static function sendDeveloperTrackMail($arrDevelopersEmail,$szSubject="Developer StackTrace")
{
	$http_msg=print_r($_SERVER,true);
	$szMailId = implode(",",$arrDevelopersEmail);
	mail("$szMailId","$szSubject","$http_msg");
}
}

?>

