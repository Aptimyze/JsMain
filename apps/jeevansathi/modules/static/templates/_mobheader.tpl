<script>var AndroidPromotion= ~JsConstants::$AndroidPromotion`;
var AppLoggedInUser=~$sf_request->getAttribute('AppLoggedInUser')`;
</script>
<script>
window.onscroll= function()
{
	$("#header").css('top','0px');
};
</script>
<div id="header">
		<div class="pgwrapper">
			<div>
				<div id="list-icon" class="pull-left icon cp"><span class="list">&nbsp;</span></div>
				<!--
				<a href="javascript:void(0);" id="logo-icon" class="pull-right icon"><span class="lock">&nbsp;</span></a>
				
				~if $loggedIn eq 0`
				<a id="log-icon1" href="/jsmb/login_home.php" class="pull-right icon cp"><span class="lock">&nbsp;</span></a>
				~else`-->
				~if $memcacheCountTotal >0`
					~if $memcacheCountNew >0`
					<div id="log-icon" class="pull-right cp msgCount">
					~else`
						<div id="log-icon" class="pull-right cp zeroMsgCount">
					~/if`
					<span class="">~$memcacheCountNew`</span>
				</div>
				~/if`
				<!--~/if`-->
				<div id="logo-js" style="text-align:center;margin:0 45px">
					<a href="~sfConfig::get('app_site_url')`"><img src="/images/mobilejs/revamp_mob/js-logo.png" style="padding-top:9px" alt="" /></a>
				</div>
			</div>
		</div>
	</div>
<div class="b7nHUd" id="hamabs"></div>
<div id="Hidden_iFrame">
	<iframe id="iframe_login"  style="display:none"  name="iframe_login">
	</iframe>
</div>
