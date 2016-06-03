<section class="s-info-bar">
		<div class="pgwrapper">
			~if $to_do eq 'eoi'` To Express Interest Login~else`
			~if $to_do eq 'view_contact'` To see Contact Details Login
			~else`Login to Jeevansathi.com
			~/if`
			~/if`
		</div>
	</section>
<section>
	<form id="homepageLogin" action="~JsConstants::$ssl_siteUrl`/profile/login.php" name="form1" method="post" onsubmit="return loginValidate()"  target="iframe_login" >
		<input type="hidden" name="prev_url" value="~$PREV_URL`"/>
		<div class="pgwrapper">
			<div class="js-content">
				<div class="error-msg" id="error_mess" ~if !$WRONGUSER` style="display:none"~/if`>~if $WRONGUSER` Invalid Email or Password~/if`</div>
				<div class="frm-container">
					<label>Your Email</label>
					<div class="row04"><div><input type="text" name="username" class="wd_name" id="username"/></div></div>
				</div>
				<div class="frm-container">
					<label>Password</label>
					<div class="row04" style="position:relative"><div><input type="password" name="password" maxlength="40" class="wd_pwd" id="password" />
					<span class="showhide" id="showHide" style="background:white" >Show</span></div></div>	
				</div>
				<div class="frm-container">
					<div class="row04"><div><input type="checkbox" name="rememberme" value='Y' checked /> Keep me logged in</div></div>
				</div>
				<div class="frm-container">
					<div class="row03">
						<div><input type="submit" name="submit" value="Login" class="actived-btn w100"></div>
						<div><a href="/jsmb/jsmb_forgotpassword.php" class="btn pre-next-btn w100 wd_fp" style="margin-top:3px">Forgot Password?</a></div>
					</div>
				</div>
				<div class="frm-container">
					<div class="row04">
						<div><a href="/jsmb/register.php?source=mobreg2" class="btn pre-next-btn" style="width:100%">New user? Register now</a></div>
					</div>
				</div>
				
			</div>
		</div> 
	</section>	
