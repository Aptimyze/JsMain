<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<script type="text/javascript">
        	var t_pagestart = new Date().getTime();
	</script>
    ~include_http_metas`
    ~include_metas`
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, , initial-scale=1.0">

	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png">
	<script src="~JsConstants::$jquery`"></script>	
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
    ~minify_get_mobile('js')`
    ~minify_include_stylesheets()`
    ~minify_include_javascripts()`
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
     	<script type="text/javascript">
		var t_headend = new Date().getTime();
	</script>
  </head>
  ~if get_slot('optionaljsb9Key')|count_characters neq 0`
	~JsTrackingHelper::getHeadTrackJs()`
  ~/if`
  <body style="overflow-x:hidden">
	  <div class="loader" id="pageloader"></div>
<noscript><div style="z-index:1000;width:100%"><div style="text-align:center;padding-bottom:3px;font:12px arial,verdana; line-height:normal;background:#E5E5E5;"><b><img src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" alt="matrimonial" height="20" width="23"> Javascript is disabled in your browser.Due to this certain functionalities will not work. Please enable it</b></div></div></noscript>

    <script type="text/javascript">
      var t_jsstart = new Date().getTime();
    </script>
    <div class="perspective" id="perspective">
	<div class="pcontainer" id="pcontainer">
	
            ~$sf_content`
            
    
</div>
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
