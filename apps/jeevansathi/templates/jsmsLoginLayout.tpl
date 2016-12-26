<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<script>
	if(typeof history.pushState=="undefined" || typeof history.replaceState=='undefined' || typeof window.onpopstate=='undefined')
	{
		document.location.href="/static/redirectToOldJsms?rUrl="+escape(document.location.href);
	}
	if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);
	</script>
	~include_http_metas`
	~include_metas`
  ~include_partial('global/jsmsCommonHeader')`
	<script type="text/javascript">
        	var t_pagestart = new Date().getTime();
			var AndroidPromotion= ~JsConstants::$AndroidPromotion`;
      var trackingProfile = "~$sf_request->getAttribute('profileid')`";
                        var webView= "~$webView`";
	</script>
	<meta name="verify-v1" content="y8P0QEbZI8rd6ckhDc6mIedNE4mlDMVDFD2MuWjjW9M=" />
	<meta http-equiv="content-language" content="en" />
	<meta name="theme-color" content="#415765">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	~if $sf_request->getAttribute('mobLogoutPage') neq 'Y'`
        <link rel="stylesheet" async=true type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
	~/if`
        <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed_new.png">
	<link rel="apple-touch-icon" href="/apple-touch-icon_new.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed_new.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72_new.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed_new.png">

    ~assign var=trackProfileId value= $sf_request->getAttribute('profileid')`
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
     ~if $sf_request->getAttribute('mobLogoutPage') eq 'Y'` 
     <script async="true" src="~JsConstants::$jquery`"></script>
  
  ~else`
	<script src="~JsConstants::$jquery`"></script>
    ~minify_get_mobile('css','','1')`
    ~minify_include_stylesheets()`

~/if`
  ~if $sf_request->getAttribute('mobLogoutPage') neq 'Y'`
  ~minify_get_mobile('js','','1')`
    ~minify_include_javascripts()`

  ~/if`
    ~if $sf_request->getAttribute('mobLogoutPage') eq 'Y'`
     <style type="text/css">
       @font-face {
         font-family: 'Roboto';
         font-style: normal;
         font-weight: 300;
         src: local('Roboto Light'), local('Roboto-Light'), local('sans-serif-light'), url(http://fonts.gstatic.com/s/roboto/v15/Hgo13k-tfSpn0qi1SFdUfZBw1xU1rKptJj_0jans920.woff2) format('woff2');
         unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
       }
       @font-face {
         font-family: 'Roboto';
         font-style: normal;
         font-weight: 500;
         src: local('Roboto Medium'), local('Roboto-Medium'), local('sans-serif-medium'), url(http://fonts.gstatic.com/s/roboto/v15/RxZJdnzeo3R5zSexge8UUZBw1xU1rKptJj_0jans920.woff2) format('woff2');
         unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
       }
     </style>
    ~/if`
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
     	<script type="text/javascript">
		var t_headend = new Date().getTime();
		~if $sf_request->getAttribute('AppLoggedInUser')`
			var AppLoggedInUser=~$sf_request->getAttribute('AppLoggedInUser')`;
		~else`
			var AppLoggedInUser=0;
		~/if`
		var appPromoPerspective=0;
		var DualHamburger=1;
	</script>
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
               if(ucode){
                if(value){
                       _gaq.push(['_trackEvent', category, action, label, value]);
                } else {
                       _gaq.push(['_trackEvent', category, action, label]);
                }
               }
               else
               return false;
       }
	trackJsEventGA("jsms","new","1");

</script>
~/if`
  </head>
  
  <body style="background:#3e3e3e">
	~if $sf_request->getAttribute('mobLogoutPage') eq 'Y'`
    ~minify_include_stylesheets()`
    ~/if`
  
<noscript><div style="z-index:1000;width:100%"><div style="text-align:center;padding-bottom:3px;font:12px arial,verdana; line-height:normal;background:#E5E5E5;"><b><img src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" alt="matrimonial" height="20" width="23"> Javascript is disabled in your browser.Due to this certain functionalities will not work. Please enable it</b></div></div></noscript>

    <script type="text/javascript">
      var t_jsstart = new Date().getTime();
    </script>
	<div id="mainContent">
		<div class="loader" id="pageloader"></div>
        ~$sf_content`
        
	</div>
  ~if ($sf_request->getAttribute('mobLogoutPage') neq 'Y') && ($sf_request->getAttribute('jsmsMyjsPage') neq 1)`
  ~minify_include_javascripts('bottom')`
  ~/if`
  <div class="urldiv dn" id="urldiv" ></div>  
  <div class="posfix dn" style="top:45%; left:0;z-index:1000" id="2dView">
        	<img border="0">
        
        </div>
  ~if $sf_request->getParameter('module') eq 'membership' || $sf_request->getParameter('module') eq 'help'`
    ~if !($trackProfileId eq '8298074' || $trackProfileId eq '13038359' || $trackProfileId eq '12970375')`
        ~include_partial('global/freshDesk')`
     ~/if`
  ~/if`
 
  </body>

  ~if JsConstants::$boomerjs eq 1`
					<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/modernizr_js.js"></script>
				<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_js.js,/js/dns.js"></script>
				<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_tracking_js_2.js"></script>
				<noscript><img src="/beacon.php?noscript=1"></noscript>
			~/if`
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
~if $sf_request->getAttribute('mobLogoutPage') eq 'Y'` 
   <script type="text/javascript">
var jqueryVar = setInterval(function(){ checkJquery() }, 500);

function checkJquery() {
    if(window.jQuery) {
        stopInterval();

        var lib2 = document.createElement('script');
        lib2.src = "~JsConstants::$imgUrl`/min/?f=/js/jsms/login/newMobLogin_js_22.js";
        document.head.appendChild(lib2);
        
        var lib = document.createElement('script');
        lib.src = "~JsConstants::$imgUrl`/min/?f=/js/modernizr_p_js_3.js,/js/tracking_js_7.js,/js/jsms/common/CommonFunctions_22.js,/js/jsms/common/scrollTo_1.js,/js/jsms/common/urlParamHandling_1.js,/js/app_promo_js_41.js,/js/commonMob_2.js,/js/jsms/common/touchswipe_js_3.js,/js/jsms/common/disableScroll_js_1.js,/js/jsms/common/history_js_7.js,/js/commonExpiration_js_2.js,/js/rippleEffectCommon_js_3.js,/js/common_comscore_js_2.js";
        document.head.appendChild(lib);
    } else{
        console.log("Jquery not loaded");
    }
}
function stopInterval(){
    clearInterval(jqueryVar);
}
   </script>
~/if`
</html>
