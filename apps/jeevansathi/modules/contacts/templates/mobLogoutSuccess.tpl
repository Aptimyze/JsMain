
<!-- Header end-->
<!-- search for start-->

    ~if $MOBILE_LOGOUT_MSG`

        <div class="existing1 fbld" style="color: #11AA22;">~$MOBILE_LOGOUT_MSG`</div>

    ~/if`

<div class="existing1 fbld">Login</div>

~if $WRONGUSER`

<div class="quick1 rederr1">

<img src="~$IMG_URL`/P/I/mobilejs/red-error2.gif"  align="absmiddle"/>

Invalid Username or Password

</div>

~/if`

<!-- search for end-->

<form action="~$SITE_URL`/profile/login.php" name="form1" method="post">

	<input type="hidden" name="prev_url" value="~$PREV_URL`"/>

<div class="quick1">

Username/Email<br />

<input type="text" name="username"/></div>

<div class="quick1">

Password<br />

<input type="password" autocomplete="off" name="password"/></div>

<div class="quick1"><input type="checkbox" name="rememberme" value='Y' checked/> Remember me</div>


<!-- search button start-->

<div>

<input type="submit" value="Login" name="submit" class="loginbtn fbld" style="font-size:13px;" />

&nbsp;<a href="~$SITE_URL`/jsmb/jsmb_forgotpassword.php" class="forgetpass_btn">Forgot Password?</a></div>

<!-- search button end-->

</form>

<!-- Newuser/existing user starts-->

<div class="new_exreg_btn3">

New User? <a href="~$SITE_URL`/jsmb/register.php?source=mobreg2">Register here</a>

</div>

<!-- Newuser/existing user end-->
