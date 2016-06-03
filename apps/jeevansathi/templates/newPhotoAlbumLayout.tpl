<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	~include_http_metas`
	~include_metas`
	<script type="text/javascript">
        	var t_pagestart = new Date().getTime();
	</script>
	<meta http-equiv="content-language" content="en" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png">

	<script src="~JsConstants::$jquery`"></script>	
    
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
    ~minify_get_mobile('js','','1')`
     ~minify_get_mobile('css','','1')`
    ~minify_include_stylesheets()`
    ~minify_include_javascripts()`
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
     	<script type="text/javascript">
		var t_headend = new Date().getTime();
		~if $sf_request->getAttribute('AppLoggedInUser')`
			var AppLoggedInUser=~$sf_request->getAttribute('AppLoggedInUser')`;
		~else`
			var AppLoggedInUser=0;
		~/if`
		var AndroidPromotion= ~JsConstants::$AndroidPromotion`;
		var appPromoPerspective=0;
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
  <body style="overflow-x:hidden">

<noscript><div style="z-index:1000;width:100%"><div style="text-align:center;padding-bottom:3px;font:12px arial,verdana; line-height:normal;background:#E5E5E5;"><b><img src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" alt="matrimonial" height="20" width="23"> Javascript is disabled in your browser.Due to this certain functionalities will not work. Please enable it</b></div></div></noscript>

    <script type="text/javascript">
      var t_jsstart = new Date().getTime();
    </script>
	<div id="mainContent">
		<div class="loader" id="pageloader"></div>

        ~$sf_content`
        ~if get_slot('optionaljsb9Key')|count_characters neq 0`
            ~JsTrackingHelper::setJsLoadFlag(1)`
        ~/if`
	</div>
 ~minify_include_javascripts('bottom')`
  <div class="urldiv dn" id="urldiv" ></div>  
  <div class="posfix dn" style="top:45%; left:0;z-index:1000" id="2dView">
        	<img src="~sfConfig::get('app_img_url')`/images/jsms/commonImg/2d-slider-left.png" border="0">
        
        </div>
  </body>
 ~if get_slot('optionaljsb9Key')|count_characters neq 0`
    ~assign var="jsb9Key" value=get_slot('optionaljsb9Key')`
	~JsTrackingHelper::getTailTrackJs(0,true,2,"http://track.99acres.com/images/zero.gif","~$jsb9Key|replace:'JSMOB':'JSNEWMOB'`")`
 ~/if`
  ~if JsConstants::$boomerjs eq 1`
					<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/modernizr_js.js"></script>
				<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_js.js,/js/dns.js"></script>
				<script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_tracking_js_2.js"></script>
				<noscript><img src="/beacon.php?noscript=1"></noscript>
			~/if`
</html>
