<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>	  
  <script>
var user_login="~$sf_request->getAttribute('login')`";
  </script>
    ~include_http_metas`
    ~include_metas`
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
    ~minify_include_stylesheets()`
    <script type="text/javascript" language="Javascript" src="~JsConstants::$jquery`"></script>
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

</script>
~/if`

   </head>
   ~if get_slot('optionaljsb9Key')|count_characters neq 0`
	~JsTrackingHelper::getHeadTrackJs()`
   ~/if`
    ~$sf_content`
    ~if get_slot('optionaljsb9Key')|count_characters neq 0`
		~JsTrackingHelper::setJsLoadFlag(1)`
	~/if`
    ~minify_include_javascripts('commonBottom')`
    ~minify_include_javascripts('bottom')`
    <!--Google Analytics Code-->
  </body>
  ~if get_slot('optionaljsb9Key')|count_characters neq 0`
		~JsTrackingHelper::getTailTrackJs(0,true,2,"https://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
  ~/if`
~if sfConfig::get('app_timetracker') eq 1`
<script src="~sfConfig::get('app_img_url')`/min/?f=/js/timetracker_js.js"></script>
<script>
var objtnm = new tnm();
objtnm.tnmPageId="~get_slot('optionaljsb9Key')`";
$(document).ready(function(){
window.onload = function () {objtnm.init();}
 window.onunload = function() { objtnm.LogCatch.call(objtnm);}
});
</script>
~/if`
</html>
