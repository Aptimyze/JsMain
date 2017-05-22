<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
        <script type="text/javascript">
                var t_pagestart = new Date().getTime();
        </script>
<meta http-equiv="content-language" content="en" />
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
	<script src="~JsConstants::$jquery`"></script>
    ~include_http_metas`
    ~include_metas`
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
    ~minify_get_mobile('css')`
    ~minify_get_mobile('js')`
    ~minify_include_stylesheets()`
    ~minify_include_javascripts()`
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
        <script type="text/javascript">
                var t_headend = new Date().getTime();
        </script>
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
		//Load google js
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
  </head>
  ~if get_slot('optionaljsb9Key')|count_characters neq 0`
    ~JsTrackingHelper::getHeadTrackJs()`
  ~/if`
  <body>

	<script type="text/javascript">
      var t_jsstart = new Date().getTime();
    </script>
        <div id="main">
        ~include_component('static', 'mobleftslider')`
        <div id="maincomponent">
        ~include_component('static', 'mobheader')`
        <div id="mainpart">
            ~$sf_content`
            ~if get_slot('optionaljsb9Key')|count_characters neq 0`
                ~JsTrackingHelper::setJsLoadFlag(1)`
            ~/if`
            ~minify_include_javascripts('bottom')`
        </div>
        ~include_component('static', 'mobfooter')`
        </div>
        ~include_component('static', 'mobrightslider')`
        </div>

  </body>
  ~if get_slot('optionaljsb9Key')|count_characters neq 0`
    ~JsTrackingHelper::getTailTrackJs(0,true,2,"https://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
 ~/if`
  ~if JsConstants::$boomerjs eq 1`
    <script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/modernizr_js.js"></script>
        <script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_js.js,/js/dns.js"></script>
        <script type="text/javascript" src="~sfconfig::get("app_img_url")`/min/?f=/js/boomerang_tracking_js_2.js"></script>
        <noscript><img src="/beacon.php?noscript=1"></noscript>
~/if`
</html>
