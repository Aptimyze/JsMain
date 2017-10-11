<!--start:login layer-->
<div id="login-layer" > 
  <!-- start:close button--> 
  <i id="cls-login" class="sprite2 layersZ close pos_fix closepos cursp"></i> 
  <!-- end:close button--> 
  <!--start:login layer-->
  <div id="newLoginLayerJspc" class="pos_fix layersZ fontlig setshare" >
	~include_partial('global/JSPC/_jspcCommonLoginLayer',["captchaDiv"=>$captchaDiv])`
</div>
<div id="Hidden_iFrame">
	<iframe id="iframe_login"  style="display:none"  name="iframe_login">
	</iframe>
</div>
</div>
<!--start:forgotpassword layer-->
    ~include_partial('global/JSPC/_jspcCommonForgotPasswordLayer')`
<!--end:forgotpassword layer-->
