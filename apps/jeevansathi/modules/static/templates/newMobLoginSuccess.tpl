<script>appPromoPerspective=1;
var site_key = "~CaptchaEnum::SITE_KEY`";
</script>
<div class="perspective" id="perspective">
	<div class="" id="pcontainer">    
		<div id="headerimg1" class="rel_c" style="height: 455px;">
		
			<div class="op_pad1">
			<!--start:error-->
			<div id="errordiv" class="txtc pad12 white f13 opaer1 errordiv posfix fullwid" style="display: none">
					Invalid Email or Password
				</div>        
			<!--end:error-->
			<!--start:logo-->
			<div class="lgin_pad1">
				<div class="fl HamiconLogin"> 
					<div id ="hamburgerIcon" hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1">
						<img class="loaderSmallIcon dn">
						<svg id="hamIc" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.2px;}</style></defs><title>icons</title><line class="cls-1" x1="2" y1="3.04" x2="18" y2="3.04"/><line class="cls-1" x1="2.29" y1="10" x2="18.29" y2="10"/><line class="cls-1" x1="2" y1="16.96" x2="18" y2="16.96"/></svg>
					</div> 
				</div>
				<img class ="loginLogo" border="0">
			</div>
			<!--end:logo-->
			~if $showRegisterMsg eq 'Y'`
			<div class="txtc f15 white fontlig" style='padding:20px 35px;'>
You need to be a Registered Member<br />to connect with this user</div>
	~/if`
			<form onsubmit="return false" novalidate>
				<input id="prev_url" type="hidden" name="prev_url" value="~$PREV_URL`"/>
			<!--start:username-->
			<div class="fullwid brdr9 brdr10 lgin_inp_pad">
				<div class="fl padr10 wid8p">
					<!--div class="icons1 uicon"></div-->
					<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.2px;}</style></defs><title>icons</title><path class="cls-1" d="M18,17.14c-.63-2.36-3.69-2.46-4.35-2.75a3.1,3.1,0,0,1-1.28-1,2.45,2.45,0,0,1-.45-1A6.23,6.23,0,0,0,13.8,9.46,2.45,2.45,0,0,0,14.41,8a.82.82,0,0,0-.29-.66,4.38,4.38,0,0,0-4-4.52H10A4.35,4.35,0,0,0,6,7.29.82.82,0,0,0,5.58,8a2.7,2.7,0,0,0,.82,1.67,6.23,6.23,0,0,0,1.69,2.59,2.69,2.69,0,0,1-1.73,2c-.66.29-3.72.48-4.35,2.84Z"/></svg>
				</div>
				<div class="fl classone wid80p">
					<input type="email" id="email" value="" class="fullwid fontlig" name="email" placeholder="Email">
				</div>
				<div id="emailErr1" class="fl wid10p txtr" style="display: none">
					<!--i style="vertical-align: middle" class="mainsp err2_icon"></i-->
					<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs><style>.cls-1{fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.2px;}.cls-1,.cls-2{stroke:#fff;}.cls-2{fill:#808184;stroke-miterlimit:10;}</style></defs><title>icons</title><circle class="cls-1" cx="10" cy="10" r="8"/><line class="cls-1" x1="10" y1="5.48" x2="10" y2="11.94"/><circle class="cls-2" cx="10" cy="13.72" r="0.49"/></svg>
				 </div>
				<div class="clr"></div>
			</div>
			<!--end:username-->
			<!--start:password-->
			<div class="fullwid brdr10 lgin_inp_pad">
				<div class="fl padr10 wid8p pt3">
					<!--div class="icons1 key"></div-->
					<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.2px;}</style></defs><title>icons</title><path class="cls-1" d="M17.49,2h-2a.5.5,0,0,0-.33.12l-6.9,6A7.31,7.31,0,0,0,7,8,5,5,0,1,0,7,18a4.69,4.69,0,0,0,4.8-4.64,4,4,0,0,0-.52-2.06L13.87,10V7.68h1.81c.26,0,.25,0,.26-.26V5.1h1.81c.32,0,.26.13.26-.26V2.5A.5.5,0,0,0,17.49,2ZM15.16,4,9.32,9.07m-1.64,4.8a1.55,1.55,0,1,0-1.55,1.55A1.55,1.55,0,0,0,7.68,13.87Z"/></svg>
				</div>
				<div class="fl classone wid80p">
					<input type="password" id="password" value="" class="fullwid fontlig" maxlength="40" name="password" placeholder="Password">
				</div>
				<div id="showHide" style="display: none" class="fl f12 white fontlig wid10p txtr">
                                    <span style="vertical-align: middle;">Show</span>
				</div>
				<div class="clr"></div>           
			</div>
			<!--end:password--> 
			



			

			
			  <!--start:forgot password-->
				<div id="afterCaptcha" class="txtc pad12">
					<a href="/static/forgotPassword" bind-slide="1" class="white f14 fontlig">Forgot Password</a>
				</div>
				<!--end:forgot password-->	      
			<div class="abs_c fwid_c" style="margin-top: 20px">
					  
				<!--start:login-->
				<div class="posrel scrollhid">
				<div id="loginButton" class="bg7 fullwid txtc pad2 pinkRipple">
					<input type="submit" class="white f18 fontlig" value="Login">      
				</div>
				</div>
				<!--end:login-->
				<!--div class="lgin_hgt1"></div-->
				<!--start:reg/srch-->
				<div class="bg10 fullwid mt5">
					<div class="wid49p fl brdr11 txtc pad12">
						<a href="/register/page1?source=mobreg4" onclick="enableLoader()" class="f17 fontlig white">Register</a>
					</div>
					<div class="wid49p fl txtc pad12 posrel scrollhid">
						<a  id="calltopSearch" href="/search/topSearchBand?isMobile=Y" class=" blueRipple f17 fontlig white">Search</a>
					</div>
					<div class="clr"></div>
				</div>       
				<!--end:reg/srch-->
			   <!--start:div-->
			   <div id="appLinkAndroid" class="txtc pad2" style="display: none">
				<a href="/static/appredirect?type=androidMobFooter" class="f15 white fontlig">Download App</a>
			   </div> 
			   <div id="appLinkIos" class="txtc pad2" style="display: none">
				<a href="/static/appredirect?type=iosMobFooter" class="f15 white fontlig">Download App</a>
			   </div>      
			   <!--end:div-->
			</div>
	   </form>
		</div>
		
		</div>
	</div>
	
</div>   
<script>
	var d = new Date();
	var hrefVal = $("#calltopSearch").attr("href")+"&stime="+d.getTime();
	var captchaShow=~$captchaDiv`;
	var nua = navigator.userAgent;
		var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 &&     nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	
	
	$("#calltopSearch").attr("href",hrefVal);
    enableLoader = function()
    {
        $('.loader').addClass('simple').addClass('dark').addClass('image');
    }


</script> 
