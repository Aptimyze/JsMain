<!DOCTYPE html>
<html amp class="no-js">
<head>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
 
    <meta charset="utf-8">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

    ~include_metas`
    <meta name="verify-v1" content="y8P0QEbZI8rd6ckhDc6mIedNE4mlDMVDFD2MuWjjW9M=" />
    <meta http-equiv="content-language" content="en" />
    <meta name="theme-color" content="#415765">
    <meta content='width=device-width, initial-scale=1.0, minimum-scale=1.0,  maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed_new.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon_new.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed_new.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72_new.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed_new.png"> ~assign var=trackProfileId value= $sf_request->getAttribute('profileid')` ~include_title` ~include_canurl` ~use helper = SfMinify`
   ~assign var=ampurl value= $sf_request->getAttribute('ampurl')`
   
   <link rel="canonical" href="~$ampurl`">
   <style amp-custom>
        *{margin:0 auto;padding:0 -moz-box-sizing: border-box;-webkit-box-sizing:border-box;box-sizing:border-box;-webkit-tap-highlight-color:rgba(0,0,0,0)}.bg4{background-color:#fff}.pad15{padding:12px 10px}.bg1{background-color:#415765}.txtc{text-align:center}.posrel{position:relative}.fontthin{font-family:"Roboto","sans-serif";font-weight:100}.f20{font-size:20px}.white{color:#fff}a{text-decoration:none}.f14{font-size:14px}.pad19{padding:30px 15px}.color11{color:#000}.fullwid{width:100%}.content_t p{padding-bottom:8px}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(http://fonts.gstatic.com/s/roboto/v15/vzIUHo9z-oJ4WgkpPOtg13YhjbSpvc47ee6xR_80Hnw.woff) format("woff")}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(http://fonts.gstatic.com/s/roboto/v15/Hgo13k-tfSpn0qi1SFdUfbO3LdcAZYWl9Si6vvxL-qU.woff) format("woff")}.fontlig{font-family:"Roboto","sans-serif";font-weight:300}
    </style>
</head>
<body>
    <div id="mainContent">
        <div class="loader" id="pageloader"></div>
        ~$sf_content` ~if get_slot('optionaljsb9Key')|count_characters neq 0` ~JsTrackingHelper::setJsLoadFlag(1)` ~/if`
    </div>
</body>
</html>