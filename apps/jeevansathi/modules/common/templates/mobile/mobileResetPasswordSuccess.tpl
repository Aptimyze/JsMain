<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>

<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114-precomposed.png">
<script type="text/javascript" language="Javascript" src="~JsConstants::$jquery`"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="google" content="notranslate" />

<title>Jeevansathi.com</title>
<SCRIPT language="JavaScript">
	function Validate(event)
	{
		var specialChars_regex = /^[\x20-\x7F]*$/;
                var password1 = document.getElementById("password1").value;
		if(specialChars_regex.test(password1)==false)
			document.getElementById("password1").value = password1.substring(0, password1.length - 1);
	}
        function sure()
        {
                var password1 = document.getElementById("password1").value;
                var password2 = document.getElementById("password2").value;
                var emailStr = document.getElementById("emailStr").value;

		$("#error").show();
		if(password1=='')
		{
			$("#error").text("Please choose a new password");
			return false;
		}
		if(password2=='')
		{
			$("#error").text("Please confirm your new password");
			return false;
		}
		var length = password1.length;
		if(length<8 || length>40)
		{
			$("#error").text("Password should be at least 8 characters long");
                        return false;
		}
                if(password1!=password2)
                {
			$("#error").text("Both passwords do not match. Please enter again.");
                        return false;
                }
                if(password1==emailStr)
                {
                        $("#error").text("Password cannot be similar to your email id. Choose a different one to keep your account safe.");
                        return false;
                }
		var lowercasePassword = password1.toLowerCase();
		if(lowercasePassword=="jeevansathi"||lowercasePassword=="matrimony"||lowercasePassword=="12345678"||lowercasePassword=="123456789"||lowercasePassword=="1234567890"||lowercasePassword=="password"||lowercasePassword=="marriage")
		{
			$("#error").text("Password is too easy for someone to guess. Choose a difficult one to keep your account safe.");
                        return false;
		}
		$("#error").hide();
		return true;
        }
</script>
<link rel='canonical' href="~sfConfig::get('app_site_url')`" />

<link rel="stylesheet" type="text/css" href="~sfConfig::get('app_img_url')`/min/?f=/css/mobile_media_css_2.css,/css/mobilejs_revamp_4.css,/css/mobile_hamburger_css_1.css" />



<link rel="stylesheet" type="text/css" href="~sfConfig::get('app_img_url')`/min/?f=/css/reg_common_mobile_css_2.css" />

<!--new css added -->
<style type="text/css">
.pageHd_1 {
    background: none repeat scroll 0 0 #d8d8d8;color: #000000;font-size: 12px;font-weight: normal;overflow: hidden;padding: 0.8em;	}
</style>
</head>

      
<body>
 

<div id="main">
	<div id="maincomponent">  
    
      
		
        
        <div id="mainpart">
			<div class="bodyCon">
                
                <!--start:html to be added-->
                <section class="pageHdCont">
                	<p class="pageHd">Reset your password</p>
                </section>                
                <form action="~sfConfig::get('app_site_url')`/common/resetPassword" method="POST" onsubmit="return sure();">
                <input type=hidden name="d" id="d" value="~$d`">
                <input type=hidden name="h" id="h" value="~$h`">
                <input type=hidden name="emailStr" id="emailStr" value="~$emailStr`">

                	<section class="wrap">
                    	
                        <article class="formRow">
							<label for="reg_email" class="lblStyl">New Password</label>
							<input type="password" id="password1" maxlength="40" name="password1" onkeyup="Validate(event);">							
							<div class="clr"></div>
						</article>
                        <article class="formRow">
							<label for="reg_email" class="lblStyl">Confirm new password</label>
							<input type="password" id="password2" maxlength="40" name="password2" onkeyup="Validate(event);" class="error">
							<div class="error err_msg" for="reg_email" style="" id="error">
                            </div>
							<div class="clr"></div>
						</article>
                        <article class="formRow">
                        	<input class="btnM" type="submit" value="Submit" name="submitPassword"/>
                        </article>
                    </section>                
                </form>
                <!--end:html to be added-->
                
			</div>
        </div>
         
        



</div>

</div>
</body>

 
</html>

