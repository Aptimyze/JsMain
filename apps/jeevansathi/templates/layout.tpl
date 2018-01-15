<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<meta http-equiv="content-language" content="en" />
    ~include_http_metas`
    ~include_metas`
    ~include_title`
    ~include_canurl`
	~if get_slot('editPage')`<script type="text/javascript" src="http://www.google.com/jsapi"></script>~/if`
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
    ~use helper = SfMinify`
    ~minify_include_stylesheets('common')`
    ~minify_include_stylesheets()`
~if get_slot('passwordReset')`
	<script type="text/javascript" language="Javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/jquery.js"></script>
~else`
	<script type="text/javascript" language="Javascript" src="~JsConstants::$jquery`"></script>
~/if`
    ~minify_include_javascripts('commonTop')`
    ~minify_include_javascripts('top')`
~if sfConfig::get("mod_"|cat:$sf_context->getModuleName()|cat:"_"|cat:$sf_context->getActionName()|cat:"_enable_google_analytics") neq 'off'`    
<script>
var domainCode={};
        domainCode[".hindijeevansathi.in"]="UA-20942264-1";
        domainCode[".jeevansathi.co.in"]="UA-20941176-1";
        domainCode[".marathijeevansathi.in"]="UA-20941180-1";
        domainCode[".punjabijeevansathi.com"]="UA-20941670-1";
        domainCode[".punjabijeevansathi.in"]="UA-20941669-1";
        domainCode[".jeevansathi.com"]="UA-179986-1";

        var host_url="http://"+window.location.host;
        var j_domain=host_url.match(/:\/\/[\w]{0,10}(.[^/]+)/)[1];
        j_domain=j_domain.toLowerCase();
        var ucode=domainCode[j_domain];
        if(ucode)
        {
                var _gaq = _gaq || [];

                _gaq.push(['_setAccount', ucode]);
                _gaq.push(['_setDomainName', j_domain]);
                _gaq.push(['_trackPageview']);
                _gaq.push(['_trackPageLoadTime']);
                (function() {
                        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();
        }
        function trackJsEventGA(category, action, label){
               if(ucode){
                       _gaq.push(['_trackEvent', category, action, label]);
               }
               else
               return false;
       }

</script>
~/if`
   </head>
    ~if get_slot('optionaljsb9Key')|count_characters neq 0`
  ~JsTrackingHelper::getHeadTrackJs()`
   ~/if`
  <body >
  <div id="clickHolderCE" onclick="javascript:updateClickHolderCE(false,event)" style="height:0px;width:0px">&nbsp;</div>
  	<div id="clickHolder" onclick="javascript:updateClickHolder(false,event)" style="height:0px;width:0px" >&nbsp;</div>~$sf_content`
  	~if get_slot('optionaljsb9Key')|count_characters neq 0`
    ~JsTrackingHelper::setJsLoadFlag(1)`
  ~/if`
    ~minify_include_javascripts('commonBottom')`
    ~minify_include_javascripts('bottom')`
    <!--Google Analytics Code-->
  ~include_partial('global/freshDesk')`
  </body>
  ~JsTrackingHelper::getTailTrackJs(0,true,2,"http://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`

</html>
