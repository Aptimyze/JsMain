~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<!--start:header-->
<div class="cover1">
  <div class="container mainwid pt35 pb48"> 
    <!--start:top nav case logged in-->
       ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`      
    <!--end:top nav case logged in--> 
  </div>
</div>
<!--end:header--> 
<!--start:middle-->
<div class="bg-4">
  <div class="container mainwid clearfix pt30 pb30"> 
    
    <!--start:left-->
    <div class="fl wid300"> 
      <!--start:advr block--> 
      <!-- ===========remove the inline css given below while placing the ad codes============= -->
      <div class="txtc" id="zt_~$zedo['masterTag']`_left1"> </div>
      <div class="txtc mt30" id="zt_~$zedo['masterTag']`_left2"> </div>
      <!--end:advr block--> 
    </div>
    <!--end:left--> 
    <!--start:right-->
    <div class="fl wid67p ml20">
      <div class="fullwid bg11 fontlig" id="loggedout"> 
        <!-- ===================id="loggedout" is neccesary to overwrite the earlier defined css for login layer ============================--> 
        ~include_partial('global/JSPC/_jspcCommonLoginLayer',["captchaDiv"=>$captchaDiv])`
      </div>
    </div>
    <div id="Hidden_iFrameLoggedOut">
	<iframe id="iframe_login"  style="display:none"  name="iframe_login">
	</iframe>
	</div>
    <!--end:end--> 
    <!--start:forgotpassword layer-->
		~include_partial('global/JSPC/_jspcCommonForgotPasswordLayer')`
	<!--end:forgotpassword layer-->
  </div>
</div>
<!--end:middle--> 
 ~include_partial('global/JSPC/_jspcAppPromo')`
            ~include_partial('global/JSPC/_jspcMatrimonialLinks')`
            ~include_partial('global/JSPC/_jspcSeoText')`
<footer> 
  ~include_partial('global/JSPC/_jspcCommonFooter')`
</footer>
<!--end:footer-->
<script>
var LoggedoutPage=1;
var fromSignout=~$fromSignout`;
var logoutChat = ~$logoutChat`;
if(logoutChat) localStorage.setItem("cout","1");
$("#loginRegistration").addClass("logout");
</script>
