<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
var SITE_URL="~sfConfig::get('app_site_url')`";
var user_login="~$sf_request->getAttribute('login')`";
var google_plus=0;
</script>
<input type="hidden" id="PHONE_VERIFIED" value="~$sf_request->getAttribute('PHONE_VERIFIED')`">
<link rel="stylesheet" type="text/css" href="http://static.jeevansathi.com/min/?f=/css/global_4.css,/css/common_css_5.css,/css/header-footer_5.css"/>
<style type="text/css">
@font-face {
font-family:"Roboto Lt";
src: local('Roboto Lt'), local('Roboto Light'), url(~sfConfig::get('app_site_url')`/images/Roboto-Light.eot?) format("eot"),url(~sfConfig::get('app_site_url')`/images/Roboto-Light.woff) format("woff"),url(~sfConfig::get('app_site_url')`/images/Roboto-Light.ttf) format("truetype"),url(~sfConfig::get('app_site_url')`/images/Roboto-Light.svg#Roboto-Light) format("svg");
font-weight:normal;
font-style:normal;
}

 body{background-color:#f3f3f3;margin:0; padding:0; }
 #appdsk_m{ margin: 0 auto; width:980px; font-family: "Roboto Light",Arial, Helvetica, sans-serif}
 #appdsk_m .sprte-appdsk{background:url(~sfConfig::get('app_site_url')`/images/sprit-dsk-app.png) transparent ; display:block;}
 #appdsk_m .hdr-logo-appdsk{background-position:0 -410px;height:56px;width:265px}
 #appdsk_m .logo1-appdsk{background-position:-279px -404px;height:73px;width:73px}
 #appdsk_m .logo2-appdsk{background-position:-377px -404px;height:73px;width:73px}
 #appdsk_m .logo3-appdsk{background-position:-474px -405px;height:73px;width:73px}
 #appdsk_m .logo4-appdsk{background-position:-568px -404px;height:73px;width:73px}
 #appdsk_m .logo5-appdsk{background-position:-669px -404px;height:73px;width:73px}
 #appdsk_m .logo6-appdsk{background-position:-775px -404px;height:73px;width:73px}
 #appdsk_m .banner-appdsk{background-position:0px 0px;height:392px;width:980px; background-repeat:no-repeat}
 #appdsk_m .appdsk_f30{font-size:30px}
 #appdsk_m .appdsk_f22{font-size:22px}
 #appdsk_m .appdsk_f20{font-size:20px}
 #appdsk_m .appdsk_pos1{top:113px; left:40px;}
 #appdsk_m .appdsk_wid260{width:260px;}
 #appdsk_m .appdek_colr1{background-color:#2c0403}
 #appdsk_m .appdek_colr2{color:#505050;}
 #appdsk_m .appdek_colr3{color:#709d36;}
 #appdsk_m .appdsk_bg2{background-color:#dadada}
 #appdsk_m .appdsk_pad1{padding:10px 0px 10px 0px}
 #appdsk_m .appdsk_pad2{padding:30px 20px 30px 20px;}
  #appdsk_m .appdsk_ml1{margin-left:76px;}
 #applinkmob form input[type="text"] { border:0px; height:30px; font-size:20px;padding:0px; margin:0px;color:#5f5f5f; padding:10px 0px 10px 5px; width:230px; background-color:#f7f7f7; }
 #applinkmob form input[type="submit"] {border:0px; background-color:#666; color:#fff; font-size:20px; height:50px; width:115px; }
 .lshadow{width:100%x;height:1px;background-color:#666666;box-shadow: 1px 2px 2px #888888;}
  .befbox{width:233px;}
  .clearfix:after {	visibility: hidden;	display: block;	font-size: 0;	content: " ";	clear: both;	height: 0;	}
   * html .clearfix             { zoom: 1; } /* IE6 */
	*:first-child+html .clearfix { zoom: 1; } /* IE7 */
 

</style>
</head>

<body>



	<div id="appdsk_m">
        <!-- start:header -->
       <div class="w217 mar20top mar20bottom">
           <a href="http://www.jeevansathi.com"><i class="hdr-logo-appdsk sprte-appdsk">&nbsp;</i></a>
        </div>	
        <!--end:header-->
        <!--start:banner-->
        <div class="sprte-appdsk banner-appdsk pos-rel">
        	<div class="pos-abs appdsk_pos1">
            	<div class="white appdsk_f30">The much awaited Jeevansathi <br/>Android App is here</div>
                <div class="mar20top center appdek_colr1 appdsk_wid260">
                	<div class="appdsk_f30 appdsk_pad1">
                    	<a href="https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Ddesktop%26utm_content%3DLP_forSMS_D%26utm_campaign%3DJSAA" style="color:#fff">Download</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end:banner-->
        <!--start:second box-->
        <div class="appdsk_pad2 appdsk_bg2">
        	<div class="center">
            	<!--
            	<div class="appdsk_f22 appdek_colr2">Or provide your mobile number to get the link to download Jeevansathi App</div>
                <div id="applinkmob" class="pad20top">
                	<form action="~sfConfig::get('app_site_url')`/common/appPromotionDesktop">
						<input type="hidden" value="~$alreadySent`" name="alreadySent"/>
                    	<input type="text" value="~$phone`" name="phone"/>
                        <input type="submit" value="Submit" name="submit"/>                    
                    </form>                
                </div>
                
                ~if $limit eq '0'`
                <div class="appdek_colr3 appdsk_f20 appdsk_pad1" ~if $sent neq 'Y'` style="display:none;"~/if`>
                    An SMS with download link has been sent to your Mobile Number.
                </div>
                ~else`
                <div class="appdek_colr3 appdsk_f20 appdsk_pad1" ~if $sent neq 'Y'` style="display:none;"~/if`>
                    You cannot send more than 10 SMS to the same number.
                </div>
                ~/if`
                -->

                <div class="fs24 appdek_colr2 pad50top pad10bottom">
                	Benefits of Jeevansathi Android App
                </div>
                <div class="lshadow"></div>
                <!--start:row 1-->
                <div class="pad20top fs16 appdek_colr2">
                	<div class="clearfix">
                        <div class="fl befbox center">
                            <i class="logo1-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">Easy registration process with simple steps</div>
                        </div>
                        <div class="fl befbox center">
                            <i class="logo2-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">Refine your search by Education, Profession etc.</div>
                        </div>
                        <div class="fl befbox center">
                            <i class="logo3-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">View Full Profiles and Photos of Members</div>
                        </div>
                        <div class="fl befbox center">
                            <i class="logo4-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">Edit your Profile and Upload Photos</div>
                        </div> 
                    </div>                                                  
                </div>   
                <!--end:row 1--> 
                <!--start:row 2-->            
                <div class="pad20top fs16 appdek_colr2">
                 	<div class="clearfix">
                        <div class="fl befbox">&nbsp;</div>
                         <div class="fl befbox center">
                            <i class="logo5-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">Get Instant notifications of Interests and Accepts</div>
                        </div>
                         <div class="fl befbox center">
                            <i class="logo6-appdsk sprte-appdsk appdsk_ml1"></i>
                            <div class="pad10top">See Daily Recommendations within the App</div>
                        </div>                    
                    </div>                
                </div>
                <!--end:row 2-->
            </div>
        </div>
        
        <!--end:second box-->
    </div>
    

</body>
<script>
	var sent='~$sent`';
	</script>
</html>
