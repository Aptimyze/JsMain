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
function jsb9createCookie(name,value,min){if(min){var date=new Date();date.setTime(date.getTime()+(min*60*1000));var expires="; expires="+date.toGMTString();}
else var expires="";document.cookie=name+"="+value+expires+"; path=/";}
function jsb9readCookie(name){var nameEQ=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)
return c.substring(nameEQ.length,c.length);}
return"-1|-1";}
function jsb9eraseCookie(name){jsb9createCookie(name,"",-1);}
function jsb9init_first()
{ date=new Date(); jsb9TrackStartTime=date.getTime();jsb9TrackVal=jsb9readCookie("jsb9Track");jsb9eraseCookie("jsb9Track");jsb9recordTimes=[];}

jsb9init_first();    
function jsb9onUnloadTracking() {
       
    //alert("Unload");
    if(jsLogin_layer==0)
    return true ;

    jsb9eraseCookie("jsb9Track");
    date = new Date();
    presentTime =date.getTime();
    presentUrl = window.location.href;
    // alert("Set Cookie "+ presentTime+"|"+presentUrl);
    jsb9createCookie("jsb9Track",presentTime+"|"+presentUrl,5);
}
function jsb9TrackTime(jsServerTime,jsFlag_unload,jsFlag_load,jsUrl,optionaljsb9Key) {

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
        var jsb9ServerTime = jsServerTime; 
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
        if(optionaljsb9Key)
            data = data+"|"+optionaljsb9Key;
        //remove '#' 
        data = data.replace(/#/g,'');
        jsb9Iframe.innerHTML = '<iframe border="0" height=0 widht=0 style="visibility: hidden" src="'+jsUrl+'?data='+data+'"></iframe>';
        
        document.getElementsByTagName("HEAD")[0].appendChild(jsb9Iframe);
    }
}

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
    function jsb9init_fourth(jsServerTime,jsFlag_unload,jsFlag_load,jsUrl,optionaljsb9Key)
{if(typeof(jsLoadFlag)=="undefined"){jsLoadFlag=0;}
if(jsLoadFlag!=0){
    $(document).ready(function(){if(jsLoadFlag==1){jsb9TrackTime(jsServerTime,jsFlag_unload,jsFlag_load,jsUrl,optionaljsb9Key);}
else{jsb9recordTimes["onLoad"]=(new Date()).getTime();}});}
else
jsb9eraseCookie("jsb9Track");if(jsFlag_unload)
{
    $(window).unload(function(){jsb9onUnloadTracking();});}
var date=new Date();jsb9TrackEndTime=date.getTime();}
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

