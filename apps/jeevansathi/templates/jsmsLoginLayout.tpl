<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
  <link rel="canonical" href="https://www.jeevansathi.com/"/> 
  ~assign var=currentMSPageName value= $sf_request->getParameter('currentMSPageName')`
  <script>
  var currentMSPageName = "~$currentMSPageName`";
  var loggedInJspcGender = "~$sf_request->getAttribute('gender')|decodevar`";
  if(typeof history.pushState=="undefined" || typeof history.replaceState=='undefined' || typeof window.onpopstate=='undefined')
  {
    document.location.href="/static/redirectToOldJsms?rUrl="+escape(document.location.href);
  }
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
        <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed_new.png">
  <link rel="apple-touch-icon" href="/apple-touch-icon_new.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed_new.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72_new.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed_new.png">

    ~assign var=trackProfileId value= $sf_request->getAttribute('profileid')`
    ~include_title`
    ~include_canurl`
    ~use helper = SfMinify`
     <script async="true" src="~JsConstants::$jquery`" onload='loadJS();'  ></script>
        <script type="text/javascript">
//var jqueryVar = setInterval(function(){ loadJS() }, 500);

function loadJS() {

         var lib = document.createElement('script');
         lib.src = "~JsConstants::$imgUrl`/min/?f=~$sf_request->getAttribute('singleJs')`";
         document.head.appendChild(lib);

        var lib2 = document.createElement('script');
        lib2.src = "~JsConstants::$imgUrl`/min/?f=~$sf_request->getAttribute('JSArray')`";
        document.head.appendChild(lib2);
}
   </script>

  
<style amp-custom>
.key,.uicon{width:21px;height:16px}.err2_icon,.key,.uicon{height:16px}.clearfix:after,.clr{clear:both}.nl_btn1,a{text-decoration:none}.ham_b20,.nl_pageHd,.scrollhid{overflow:hidden}.headerimg1{background:url(~JsConstants::$imgUrl`/images/mrevamp/loginbg.jpg) center center no-repeat #3e3e3e!important;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover}.uicon{background-position:-7px -82px}.key{background-position:-7px -117px}.err2_icon{background-position:-143px -59px;width:17px}.opaer1{background-color:rgba(102,102,102,.9)}.classone input{border:0;background:0 0;font-size:15px;color:#dddbdb}.icons1,.mainsp{background-repeat:no-repeat}.classone input ::-webkit-input-placeholder{color:#dddbdb}.classone input :-moz-placeholder{color:#dddbdb}.classone input ::-moz-placeholder{color:#dddbdb}.classone input :-ms-input-placeholder{color:#dddbdb}.app_clrw,.white{color:#fff}@media (min-width:280px) and (max-width:320px){.lgin_pad1{padding:20px 0}.lgin_inp_pad{padding:20px 15px}}@media (min-width:321px){.lgin_pad1{padding:45px 0 20px}.lgin_inp_pad{padding:25px 15px}}.HamiconLogin{padding-left:5%}.loginLogo{margin-left:20%}.transLayer{position:absolute;height:100%;opacity:.2;background-color:#fff}.loaderSmallIcon2{display:block;margin-left:auto;margin-right:auto;position:relative;width:20px;height:20px}.loaderSmallIcon{width:20px;height:20px;position:absolute;margin-top:-2px}*,body{margin:0 auto}*{padding:0;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;-webkit-tap-highlight-color:transparent}html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}body{font-family:"Roboto Light, Arial, sans-serif, Helvetica Neue",Helvetica;background-color:#f5f5f5}.fontlig,.fontrobbold{font-family:Roboto,sans-serif}.clearfix:after,.clearfix:before{display:table;line-height:0;content:""}.fl{float:left}.fr{float:right}.txtc{text-align:center}.txtr{text-align:right}.posrel{position:relative}.loader,.posfix,.urldiv{position:fixed}.dispbl{display:block}.dispibl{display:inline-block}.fullwid{width:100%}.fullheight{height:100%!important}.wid8p{width:8%}.wid80p{width:80%}.wid10p{width:10%}.fwid_c1{width:100%}.wid49p{width:49%}.wid76p{width:76%}.lh30{line-height:30px}.bg7{background-color:#d9475c}.bg10{background-color:#34495e}.pad12{padding:12px 0}.padr10{padding-right:10px}.pad2{padding:20px 0}.padl10{padding-left:10px}.pad1{padding:0 15px}.pt3{padding-top:3px}.pt20{padding-top:20px}.app_pt10,.pt10{padding-top:10px}.mt5{margin-top:5px}.opa70{opacity:.7;filter:alpha(opacity=70)}.brdr9{border-top:1px solid #868181}.brdr10{border-bottom:1px solid #868181}.brdr11{border-right:1px solid #2d4156}.f13{font-size:13px}.f12{font-size:12px}.f14{font-size:14px}.f18{font-size:18px}.f17{font-size:17px}.f15{font-size:15px}.mainsp{background-image:url(https://static.jeevansathi.com/images/jsms/commonImg/main-sprite_new_1.png)}.icons1{background-image:url(https://static.jeevansathi.com/images/jsms/commonImg/icons1.png)}input{border:0;background:0 0;outline:0}.urldiv{width:100%;background:url(https://static.jeevansathi.com/images/jsms/commonImg/loader.gif) center no-repeat #fff;top:0;z-index:10}.dn{display:none}.apppromoshow,.nl_close{display:block}.loader{width:0;height:0}.loader.simple{width:100%;height:100%;z-index:10000}.loader.simple.dark{opacity:.6;background:#fff}.loader.simple.dark.image,.loaderimg{background-image:url(https://static.jeevansathi.com/images/jsms/commonImg/loader.gif);background-repeat:no-repeat;background-position:center}.fontlig{font-weight:300}.fontrobbold{font-weight:700}.padl5{padding-left:5px}#appPromo img{max-width:100%;height:auto}.app_posr{position:relative}.app_posa,.ham_b100{position:absolute}.app_pt20{padding-top:20px}.app_pt30{padding-top:30px}.app_f20{font-size:20px}.app_f40{font-size:140%}.app_lh20{line-height:180%}.app_txtc{text-align:center}.app_pos1{top:10px}.app_fnt16{font-size:16px}.app_clr1{color:#d09091}.app_btn{background-color:#AA5053;padding:15px;margin:0 auto;width:70%}.transitionApp{height:0;-webkit-transition:height 2s linear;-moz-transition:3s ease;-o-transition:3s ease;transition:3s ease}.app_grad1{background:-webkit-linear-gradient(#8B1B03,#9A1904);background:-o-linear-gradient(#8B1B03,#9A1904);background:-moz-linear-gradient(#8B1B03,#9A1904);background:linear-gradient(#8B1B03,#9A1904)}.napp_pos2{top:5%;left:5%}.nfamily1,.nfamily2{font-family:"Roboto Light"}.app_txtl{text-align:left}.app_txtr,.nl_txtc{text-align:right}.ncolr1{color:#999}.ncolr2{color:#e2e2e2}.app_fnta{font-size:80%}.app_fntb{font-size:120%}.napp_pad1{padding:3% 4% 0}.napp_pt1{padding-top:3%}.napp_rat5{background:url(https://static.jeevansathi.com/images/mobilejs/ratingapp.png) no-repeat;width:135px;height:31px}.napp_pt20,.napp_pt_abc{padding-top:20px}.napp_promo_profile{width:180px;font-weight:400}@media (min-width:340px) and (max-width:979px){.napp_pad1{padding:10% 4% 0}.napp_pt20{padding-top:80px}.napp_pt_abc{padding-top:40px}.napp_promo_profile{width:240px;font-weight:400}}.nl_pageHd{background:#42688f;font-size:12px;font-weight:400;padding:.7em .8em .8em;color:#fff}.nl_f16{font-size:16px}.nl_f13{font-size:13px}.nl_f18{font-size:18px}.nl_f11{font-size:11px}.nl_f12{font-size:12px}.start_r{background:url(https://static.jeevansathi.com/images/nl-star.png) 1px 1px no-repeat;width:62px;height:15px}.nl_close{background:url(https://static.jeevansathi.com/images/nl-star.png) -2px -19px no-repeat;width:16px;height:16px}.nl_full{width:100%}.nl_pt12{padding-top:12px}.nl_p10{padding:10px}.nl_wid49{width:49%}.nl_wid38{width:38%}.nl_btn1{background-color:#e9e9e9;text-align:center;padding:9px 15px;font-size:13px;color:#000}.nl_marginappPromo{margin-left:0}.nl_pt15{padding-top:15px}.int_img1{background:url(https://static.jeevansathi.com/images/jsms/interstital/bg_ham.jpg) center center no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover}.int_color1{color:#e2e2e2}.int_quote1{background-position:-238px -295px;width:33px;height:46px}@media (min-width:280px) and (max-width:320px){.int_pad1{padding-top:0}.int_pad2{padding-top:5%}.logo_pad1{padding:20px 0}.logo_pad1ios{padding:20px 0 0}.int_btm{bottom:16px}}@media (min-width:321px) and (max-width:720px){.int_pad1{padding-top:5%}.int_pad2{padding-top:10%}.logo_pad1,.logo_pad1ios{padding:55px 0 20px}.int_btm{bottom:25px}}.ham_minu100{-webkit-transform:translateY(-100%);transform:translateY(-100%)}.ham_plus100{-webkit-transform:translateY(100%);transform:translateY(100%)}.ham_b100{width:100%;height:100%;cursor:pointer;-webkit-transition:-webkit-transform 2s;transition:transform 2s;-webkit-transform-origin:50% 50%;transform-origin:50% 50%}.newocbbg1{background-color:#fff}.newocbbg2{background-color:#d9475c}.ocbnewimg{background-image:url(~JsConstants::$imgUrl`/images/jsms/interstital/mob-newocb.svg);background-repeat:no-repeat}.logoocb{background-position:1px 1px;width:60px;height:58px}.ocbclose{background-position:-61px -27px;width:16px;height:16px}.ocbstar{background-position:-62px -4px;width:69px;height:16px}.errClass,.ham_b20{position:absolute;width:100%}.ocbbr1{border-radius:5px}.ocbp1{padding:10px 20px}.ham_minu20{-webkit-transform:translateY(-78px);transform:translateY(-78px)}.ham_plus20{-webkit-transform:translateY(78px);transform:translateY(78px)}.ham_b20{height:78px;cursor:pointer;-webkit-transition:-webkit-transform 2s;transition:transform 2s;-webkit-transform-origin:50% 50%;transform-origin:50% 50%}.padAppPromo{padding:10px 15px}.errClass{z-index:10000;transition:.3s ease;-webkit-transition:.3s ease;-webkit-transform:translateY(-100%);transform:translateY(-100%);-webkit-animation:opac0 .4s;-moz-animation:opac0 .4s;animation:opac0 .4s}.errClass.showErr{-webkit-transform:translateY(0);transform:translateY(0);-webkit-animation:opac90 1s;-moz-animation:opac90 1s;animation:opac90 1s}.pad12_e{padding:12px}.op1{background-color:rgba(102,102,102,1)}
</style>
     <style amp-custom>
       @font-face {
         font-family: 'Roboto';
         font-style: normal;
         font-weight: 300;
         src: local('Roboto Light'), local('Roboto-Light'), local('sans-serif-light'), url(https://fonts.gstatic.com/s/roboto/v15/Hgo13k-tfSpn0qi1SFdUfZBw1xU1rKptJj_0jans920.woff2) format('woff2');
         unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
       }
       @font-face {
         font-family: 'Roboto';
         font-style: normal;
         font-weight: 500;
         src: local('Roboto Medium'), local('Roboto-Medium'), local('sans-serif-medium'), url(https://fonts.gstatic.com/s/roboto/v15/RxZJdnzeo3R5zSexge8UUZBw1xU1rKptJj_0jans920.woff2) format('woff2');
         unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
       }
     </style>
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
      <script type="text/javascript">
    var t_headend = new Date().getTime();
    ~if $sf_request->getAttribute('AppLoggedInUser')`
      var AppLoggedInUser=~$sf_request->getAttribute('AppLoggedInUser')`;
    ~else`
      var AppLoggedInUser=1;
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
                _gaq.push(['_trackPageview', currentMSPageName || ""]);
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
    ~minify_include_stylesheets()`
  
<noscript><div style="z-index:1000;width:100%"><div style="text-align:center;padding-bottom:3px;font:12px arial,verdana; line-height:normal;background:#E5E5E5;"><b><img src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" alt="matrimonial" height="20" width="23"> Javascript is disabled in your browser.Due to this certain functionalities will not work. Please enable it</b></div></div></noscript>

    <script type="text/javascript">
      var t_jsstart = new Date().getTime();
    </script>
	<div id="mainContent">
		<div class="loader" id="pageloader"></div>
        ~$sf_content`
        
	</div>
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
</html>
