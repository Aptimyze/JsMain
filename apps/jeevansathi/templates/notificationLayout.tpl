<!DOCTYPE html>
<head>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="content-language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="/favicon1.ico" />
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" async=true type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
    ~include_http_metas`
    ~include_canurl`
    ~include_title`
    ~include_metas`
    ~use helper = SfMinify`
   
    <script type="text/javascript" language="Javascript" src="~JsConstants::$jquery`"></script>
    ~if sfConfig::get("mod_"|cat:$sf_context->getModuleName()|cat:"_"|cat:$sf_context->getActionName()|cat:"_enable_google_analytics") neq 'off'`
    <script>
        var domainCode={};
        domainCode[".hindijeevansathi.in"]="UA-20942264-1";
        domainCode[".jeevansathi.co.in"]="UA-20941176-1";
        domainCode[".marathijeevansathi.in"]="UA-20941180-1";
        domainCode[".punjabijeevansathi.com"]="UA-20941670-1";
        domainCode[".punjabijeevansathi.in"]="UA-20941669-1";
        domainCode[".jeevansathi.com"]="UA-179986-1";
        var host_url="https://"+window.location.host;
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
            } else {
            return false;
            }
        }
        var loggedInJspcUser="~$sf_request->getAttribute('profileid')`";
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
<body >
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
    
    
    <!--Google Analytics Code-->
</body>
~JsTrackingHelper::getTailTrackJs(0,true,2,"http://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
</html>
<script>var SSL_SITE_URL='~JsConstants::$ssl_siteUrl`';

</script>
