<script>appPromoPerspective=1;</script>
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
				<div class="fl HamiconLogin"> <i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div>
				<img class ="loginLogo" src="~$IMG_URL`/images/jsms/commonImg/mainLogoNew.png" border="0">
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
					<div class="icons1 uicon"></div>
				</div>
				<div class="fl classone wid80p">
					<input type="email" id="email" value="" class="fullwid fontlig" name="email" placeholder="Email">
				</div>
				<div id="emailErr1" class="fl wid10p txtr" style="display: none">
					<i style="vertical-align: middle" class="mainsp err2_icon"></i>
				 </div>
				<div class="clr"></div>
			</div>
			<!--end:username-->
			<!--start:password-->
			<div class="fullwid brdr10 lgin_inp_pad">
				<div class="fl padr10 wid8p pt3">
					<div class="icons1 key"></div>
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
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>



			

			
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
				<div class="lgin_hgt1"></div>
				<!--start:reg/srch-->
				<div class="bg10 fullwid">
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
	<div id="hamburger" class="hamburgerCommon dn fullwid">	
		~include_component('static', 'newMobileSiteHamburger')`	
	</div>
</div>   
<script>
	var d = new Date();
	var hrefVal = $("#calltopSearch").attr("href")+"&stime="+d.getTime();
	var captchaShow=~$captchaDiv`;
	var nua = navigator.userAgent;
		var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 &&     nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	if(is_android){
		captchaShow=1;
	}
	
	$("#calltopSearch").attr("href",hrefVal);
    enableLoader = function()
    {
        $('.loader').addClass('simple').addClass('dark').addClass('image');
    }


</script> 
