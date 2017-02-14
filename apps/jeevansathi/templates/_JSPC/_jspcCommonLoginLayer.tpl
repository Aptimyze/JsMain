<script>
    var captchaShow=~$captchaDiv`;
    var site_key = "~CaptchaEnum::SITE_KEY`";
</script>
    <div class="mauto layerbg wid520">
        <div class="layerp3">
            <div id="LoginMessage" class="f17 grey5">Login to continue..</div>
            <div id="LoginErrMessage" class="f17 errcolr disp-none">Invalid e-mail ID or password. Please try again!</div>
            <div id="CaptchaErrMessage" class="f17 errcolr disp-none">Please click the box 'I'm not a robot'</div>
            <!-- <div id="LoginErrMessage2" class="f17 errcolr disp-none">New Captcha message here</div> -->
            <div class="mt20">
                <form id="homePageLogin" method="post" target="iframe_login" onsubmit="return LoginValidation()">
                    <div id="EmailContainer" class="clearfix wid92p brderinp layerp2 ">
                       <input type="text" class="bgnone f15 grey6 brdr-0 fl wid70p" placeholder="Email ID" value="" id="email" name="email">
                       <span id="emailErr" class="errcolr f15 fr hgt18 vishid"></span>
                    </div>
                    <div id="PasswordContainer" class="clearfix wid92p brderinp layerp2 mt10 ">
                       <input type="password" class="bgnone f15 grey6 brdr-0 fl wid70p" placeholder="Password" value="" id="password" name="password">
                       <span id="passwordErr" class="errcolr f15 fr hgt18 vishid"></span>
                    </div>
                    <script>
                    </script>
<div class = "clearfix wid92p mt10"></div>
                    
        


                    <div id="afterCaptcha" class="clearfix mt20">
                    	<div class="fl">
                        	<div class="wid300 clearfix">
								<div class="fl">
                                 <input type="checkbox" checked class="fl" value="1" id="remember" name="remember" />
                                 </div>
                                 <div class="pl20 f15 grey5  pr10">Remember me</div>
                                
                            </div>
                        </div>
                        <div class="fr">
                        	<div id="forgotPasswordLoginLayer" class="cursp grey5 f15">Forgot Password</div>
                        </div>
                    </div>
                    <div class="mt15">
                    <div class="pos-rel scrollhid">
        				<button id="jspcLoginLayerButton" class="cursp blueRipple hoverBlue fullwid bg5 lh63 txtc f18 fontlig colrw brdr-0">LOGIN</button>
                    </div>    
        			</div>                
                </form>
            </div>
        </div>



    	<div class="brdt1 layerp3">
        	<p class="txtc f17 fontlig grey5">New on Jeevansathi?</p>
             <div class="mt15">
                <div class="pos-rel scrollhid">
        				<button id="loginRegistration" class="cursp fullwid pinkRipple hoverPink bg_pink lh63 txtc f18 fontlig colrw brdr-0 allcaps">Register free</button>
                </div>        
        	</div>  
        </div>
    </div>
  

