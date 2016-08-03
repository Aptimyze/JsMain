~assign var=module value= $sf_request->getParameter('module')`
~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=action value= $sf_context->getActionName()`
~if JsConstants::$jsChatFlag eq "1"`
    ~assign var=showChat value= CommonUtility::checkChatPanelCondition($loggedIn,$module,$action)`
~/if`
<!DOCTYPE html>
<head>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="content-language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="/favicon1.ico" />
    <link rel="stylesheet" async=true type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
    ~include_http_metas`
    ~include_canurl`
    ~include_title`
    ~include_metas`
    ~use helper = SfMinify`
    ~minify_include_stylesheets('common')`
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
        function trackJsEventGA(category, action, label, value){
            if (ucode){
                if (value) {
                       _gaq.push(['_trackEvent', category, action, label, value]);
                } else {
                       _gaq.push(['_trackEvent', category, action, label]);
                }
            } else {
               return false;
            }
        }
        var loggedInJspcUser="~$sf_request->getAttribute('profileid')`";
        var showChat = "~$showChat`";
        var loggedInJspcGender = "~$sf_request->getAttribute('gender')|decodevar`";
        var self_checksum = "~$sf_request->getAttribute('profilechecksum')`";
    </script>
    ~/if`
    ~if $showChat`
        <script>
            var openfireUrl= "~JsConstants::$openfireConfig['HOST']`:~JsConstants::$openfireConfig['WSPORT']`";
        </script>
    ~else`
        <script>
            var openfireUrl= "";
        </script>
    ~/if`
</head>
~if get_slot('optionaljsb9Key')|count_characters neq 0`
~JsTrackingHelper::getHeadTrackJs()`
~/if`
<noscript>
    <div class="bg_pink lh46 f18 txtc colrw fontreg boxshadow" style="font-weight: 300;">
        You have not enabled Javascript on your browser, please enable it to use the website
    </div>
</noscript>
~if $showChat`
    <body>
    <!--start:chat panel-->
        <div id="chatOpenPanel"> 
        </div>
    <!--end:chat panel-->
~else if`
    <body>
~/if`

    <div id="clickHolderCE" onclick="javascript:updateClickHolderCE(false,event)" style="height:0px;width:0px">&nbsp;</div>
    <div id="clickHolder" onclick="javascript:updateClickHolder(false,event)" style="height:0px;width:0px" >&nbsp;</div><div id="commonOverlay" class="jspcOverlay js-overlay overlayZ disp-none"></div>
    <!--start:error layer-->
<div class="pos_fix fullwid z6" style="background-color:#fdfdfd; display:none;" id="commonError">
    <div class="container errwid2 pt10 pb10">
        <div class="fl">
            <i class="sprite2 erric1"></i>
        </div>
        <div class="fl f20 fontlig color11 pt10 pl20" id="js-commonErrorMsg">
        Something went wrong. Please try again after some time.
        </div>
    </div>
</div>
<!--end:error layer-->
~$sf_content`
    ~if get_slot('optionaljsb9Key')|count_characters neq 0`
    ~JsTrackingHelper::setJsLoadFlag(1)`
    ~/if`
    ~minify_include_javascripts('commonBottom')`
    ~minify_include_javascripts('bottom')`
    <!--Google Analytics Code-->
</body>
~JsTrackingHelper::getTailTrackJs(0,true,2,"http://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
</html>
<script>var SSL_SITE_URL='~JsConstants::$ssl_siteUrl`';
if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);
</script>
~if !get_slot('disableFbRemarketing')`
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
_fbq.push(['addPixelId', '569447716516417']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<script type="text/javascript" language="Javascript" src="~JsConstants::$siteUrl`/min/?f=/js/jspc/chat/enc-base64-min.js,/js/jspc/chat/aes.js,/js/jspc/chat/core.js,/js/jspc/chat/enc-utf16.js"></script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=569447716516417&amp;ev=PixelInitialized" /></noscript>
~/if`
