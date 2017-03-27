<body>
    <!--start:header-->
    <div class="cover1">
        <div class="container mainwid pt35 pb48">
            ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
        </div>
    </div>
    <!--end:header-->
    <!--start:middle-->
    <div class="bg-4">
        <div class="container mainwid">
     ~if $done` 
    <!--start:changed succesfully -->   
<div class="pb400 pt50 fontlig txtc color11">
<div class="pb30 pt30 mauto wid70p">
<p>You have successfully reset your jeevansathi account password,</p>
<p class="pt5"><a class="colr5" href="~sfConfig::get('app_site_url')`/static/logoutPage">Click here</a> to access your account.</p>
</div>
</div>
~elseif $expired`
	
        <div class="pb30 pt30">
         <div class="mauto fontlig bg-white wid520">
			<div class="layerp1">
			<div class="f17 grey5" id="titleErr">Forgot Password</div>
			<div class="mt10">
			<div id="ForgotPasswordMessage" class="f15 colr2 pt10">The link has been either accessed once or it has been more than 24 hours since you requested for it. In case you are trying to reset your password again, request for a new link below.</div>
			<div id="forgotPasswordErr" class="f15 colr5 pt10 txtc vishid">Please enter vaild Email Id or Phone number</div>
			<div class="mt10"></div>
			<form id="forgotPasswordForm" action="">
			<div class="clearfix wid92p brderinp layerp2" id="userEmailBox">
			<input type="text" class="bgnone f15 grey6 brdr-0 fl fullwid" placeholder="Registered Email Id or Primary Mobile number" id="userEmail">
			</div>
			<div class="mt30">
			<div class="lh63 txtc colrw f18 fullwid brdr-0 bg5 cursp"id="sendLinkForgot">Send Link</div>
			</div>
			</form>
			</div>
			</div>
			</div>
         </div>
         <script>
         var ResetPasswordPage=1;
         </script>
~else`
	~include_partial('global/JSPC/_jspcResetPassword',['emailStr'=>$emailStr,'d'=>$d,'h'=>$h,'passwordInvalid'=>$passwordInvalid])`
~/if`
            
        </div>
    </div>
    <!--end:middle-->
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`
    <!--end:footer-->
</body>
