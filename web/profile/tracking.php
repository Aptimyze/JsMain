<?php

/**
 * Smarty  class for symfony,
 * @version $Id$
 * @author Jayasim N.S.R.
 */

class BrijjTrackingHelper {

public static function getHeadTrackJs() {
    $returnData = <<<MARKUP
        <script>
    function jsb9createCookie(name,value,min) {
        if (min) {
            var date = new Date();
            date.setTime(date.getTime()+(min*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
       // alert(document.cookie);
    }

    function jsb9readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) 
		return c.substring(nameEQ.length,c.length);
        }
        return "-1|-1";
    }
    function jsb9eraseCookie(name) {
        jsb9createCookie(name,"",-1);
    }
    var date = new Date();
    jsb9TrackStartTime  = date.getTime();
    var jsb9TrackVal = jsb9readCookie("jsb9Track");
    jsb9eraseCookie("jsb9Track");
    var jsb9recordTimes = [];
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


public static function getTailTrackJs($serverTime,$flag_unload,$flag_load,$url,$regUrl="") {
//        $serverTime = $params['serverTime'];
  //      $flag_unload = $params['flag_unload'];
    //    $flag_load = $params['flag_load'];
      //  $url = $params['url'];

    $returnData = <<<MARKUP

    <script>

    if(typeof(jsLoadFlag) == "undefined") {
        jsLoadFlag = 0;
    }
    //alert("2"+jsLoadFlag);
    //alert(jsLoadFlag);
if(jsLoadFlag != 0) {
    var prevWindowOnload = window.onload;
    window.onload = function(){
        try{
        if(typeof(prevWindowOnload) == "function") {
            prevWindowOnload.call();
        }
        }catch(e){
        }
        if(jsLoadFlag ==1) {
        jsb9TrackTime();
}else{

    jsb9recordTimes["onLoad"]=(new Date()).getTime();
 }
    }

}
else{
   jsb9eraseCookie("jsb9Track");

}
MARKUP;
if($flag_unload) {
    $returnData .= <<<MARKUP

    var prevWindowOnunload = window.onbeforeunload;
    window.onbeforeunload = function(){
        try{
        if(typeof(prevWindowOnunload) == "function") {
            prevWindowOnunload.call();
        }
        }catch(e){
        }
        jsb9onUnloadTracking();
    }

    function jsb9onUnloadTracking() {
       
//alert("Unload");
if(jsLogin_layer==0)
	return true ;

jsb9eraseCookie("jsb9Track");
        var date = new Date();
        var presentTime =
date.getTime();
        var presentUrl = window.location.href;
       // alert("Set Cookie "+ presentTime+"|"+presentUrl);
        jsb9createCookie("jsb9Track",presentTime+"|"+presentUrl,5);
    }
MARKUP;
}
    $returnData .= <<<MARKUP
    var date = new Date();
    jsb9TrackEndTime = date.getTime();
    function jsb9TrackTime() {

	//alert("Tracking");
        var jsb9date = new Date();
        jsb9TrackFinalLoad=jsb9date.getTime();
        //console.debug(jsb9TrackVal);
        //alert(jsb9TrackVal+" "+typeof(jsb9TrackVal));
        if(typeof(jsb9TrackVal) == "string") {
            var cookieArr = jsb9TrackVal.split('|');
            var prevTime = cookieArr[0];
            var refererUrl = cookieArr[1];
            var jsb9Iframe = document.createElement('div');
            jsb9Iframe.id = 'jsb9Div';
            var style = 'border:0;width:0;height:0;display:none';
            var jsb9ServerTime = $serverTime;
            var presentUrl = window.location.href;

            //Removing | and : from present url and referer url from variable.
	    presentUrl=presentUrl.replace(/\|/g,"");
	    refererUrl=refererUrl.replace(/\|/g,"");
	    presentUrl=presentUrl.replace(/\:/g,"");
            refererUrl=refererUrl.replace(/\:/g,"");

            var customTrack = "";
            for(var i in jsb9recordTimes) {
                //alert(i+""+typeof(jsb9recordTimes[i]));

                if(typeof(jsb9recordTimes[i]) == "number") {
                        customTrack += "|"+i+":"+jsb9recordTimes[i];
                }
            }
            var data = presentUrl+"|"+refererUrl+"|"+prevTime+"|"+jsb9TrackStartTime+"|"+jsb9TrackEndTime+"|"+jsb9TrackFinalLoad+"|"+jsb9ServerTime+customTrack;
MARKUP;
            if($regUrl){
				 $returnData .= <<<MARKUP
				 jsb9Iframe.innerHTML = '<iframe border="0" height=0 widht=0 style="visibility: hidden" src="$url?data='+data+'|$regUrl"></iframe>';
MARKUP;
			 }
            else
            {
				$returnData .= <<<MARKUP
				jsb9Iframe.innerHTML = '<iframe border="0" height=0 widht=0 style="visibility: hidden" src="$url?data='+data+'"></iframe>';
MARKUP;
			}
			$returnData .= <<<MARKUP
	    document.getElementsByTagName("HEAD")[0].appendChild(jsb9Iframe);
        }
    }
    </script>
MARKUP;
    return $returnData;
}
}

?>
