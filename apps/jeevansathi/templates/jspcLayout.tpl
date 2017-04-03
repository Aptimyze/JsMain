~assign var=module value= $sf_request->getParameter('module')`
~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=action value= $sf_context->getActionName()`
~assign var=subscription value= CommonFunction::getMembershipName($sf_request->getAttribute('profileid'))`
~if JsConstants::$jsChatFlag eq "1"`
    ~assign var=showChat value= CommonUtility::checkChatPanelCondition($loggedIn,$module,$action,$sf_request->getAttribute('activated'))`
    ~assign var=selfUserChatName value= CommonUtility::fetchSelfUserName($loggedIn,$sf_request->getAttribute('profileid'),$module,$action,$showChat)`
~/if`
<!DOCTYPE html>
<head>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="content-language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="/favicon1.ico" />
    <link rel="stylesheet" async=true type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
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
    var _rID = "~sfContext::getInstance()->getRequest()->getAttribute('REQUEST_ID_FOR_TRACKING')`";
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
        
    </script>
    ~/if`
    <script>
        var loggedInJspcUser="~$sf_request->getAttribute('profileid')`";
        var showChat = "~$showChat`";
        var loggedInJspcGender = "~$sf_request->getAttribute('gender')|decodevar`";
        var self_checksum = "~$sf_request->getAttribute('profilechecksum')`";
        var self_username = "~$sf_request->getAttribute('username')`";
        var my_action = "~$action`";
        var moduleChat = "~$module`";
        var self_subcription = "~$subscription`";
        var hideUnimportantFeatureAtPeakLoad = ~JsConstants::$hideUnimportantFeatureAtPeakLoad`;
        var multiUserPhotoUrl = "~JsConstants::$multiUserPhotoUrl`";
        var listingWebServiceUrl = {"dpp":"~JsConstants::$chatListingWebServiceUrl['dpp']`","shortlist":"~JsConstants::$chatListingWebServiceUrl['shortlist']`","chatAuth":"~JsConstants::$chatListingWebServiceUrl['chatAuth']`"};
        var nonRosterRefreshUpdate = {"dpp":{"Free":"~JsConstants::$nonRosterRefreshUpdateNew['dpp']['Free']`","Paid":"~JsConstants::$nonRosterRefreshUpdateNew['dpp']['Paid']`"},"shortlist":{"Free":"~JsConstants::$nonRosterRefreshUpdateNew['shortlist']['Free']`","Paid":"~JsConstants::$nonRosterRefreshUpdateNew['shortlist']['Paid']`"}};
        var dppLiveForAll = "~JsConstants::$profilesEligibleForDpp['allProfiles']`";
        var profileServiceUrl = "~JsConstants::$profileServiceUrl`";
        //console.log("dppLiveForAll",dppLiveForAll);
        var betaDppExpression = "",specialDppProfiles="";
        if(dppLiveForAll == "0"){
            betaDppExpression = "~JsConstants::$profilesEligibleForDpp['modulusDivisor']`"+","+"~JsConstants::$profilesEligibleForDpp['modulusRemainder']`";
            specialDppProfiles = "~JsConstants::$profilesEligibleForDpp['privilegedProfiles']`";
        }
        
        //console.log("betaDppExpression",betaDppExpression);
        var selfUserChatName = "~$selfUserChatName`";
        localStorage.removeItem("self_subcription");
        localStorage.setItem("self_subcription","~$subscription`");
        //console.log("ankita_localstorage",localStorage.getItem("self_subcription"));
        //console.log("in ...2");
    </script>
    ~if $showChat`
        <script>
            //console.log("in ...3");
            var openfireUrl= "~JsConstants::$openfireConfig['HOST']`:~JsConstants::$openfireConfig['WSPORT']`";
            var openfireServerName = "~JsConstants::$openfireConfig['SERVER_NAME']`";
            //var my_action = "~$action`";
            //var moduleChat = "~$module`";
            var chatTrackingVar = {"stype":"~SearchTypesEnums::PC_CHAT_NEW`","rtype":"~JSTrackingPageType::PC_CHAT_RTYPE`"};
        //console.log("chatTrackingVar",chatTrackingVar);
        </script>
    ~else`
        <script>
            //console.log("in ...4");
            var openfireUrl= "",openfireServerName="";
            var chatTrackingVar = {"stype":"","rtype":""};
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
<div id='mainContent'>

    <div id="clickHolderCE" onclick="javascript:updateClickHolderCE(false,event)" style="height:0px;width:0px">&nbsp;</div>
    <div id="clickHolder" onclick="javascript:updateClickHolder(false,event)" style="height:0px;width:0px" >&nbsp;</div><div id="commonOverlay" class="jspcOverlay js-overlay overlayZ disp-none"></div>
    <!--start:error layer-->
<div class="pos_fix fullwid z7" style="background-color:#fdfdfd; display:none;" id="commonError">
    <div class="container errwid2 pt10 pb10">
        <div class="disp_ib pos-rel" style='margin:10px 0px 10px 30px;'>
            <i class="sprite2 erric1"></i>
        </div>
        <div class="f20 fontlig color11 vtop disp_ib pos-rel" style='margin:13px; width:680px;' id="js-commonErrorMsg">
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
</div>
</body>
~JsTrackingHelper::getTailTrackJs(0,true,2,"https://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
</html>
<script>var SSL_SITE_URL='~JsConstants::$ssl_siteUrl`';
</script>
~if !get_slot('disableFbRemarketing')`
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '569447716516417');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=569447716516417&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
~/if`
