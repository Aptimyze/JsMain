~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
~assign var=zedoProfileDetail value= $zedoValue["custom"]`
<script type="text/javascript">
 var initialPos=0;
 var zmt_mtag;
 
function loadScript(src, callback)
{
  var s,
      r,
      t;
  r = false;
  s = document.createElement('script');
  s.type = 'text/javascript';
  s.src = src;
  s.onload = s.onreadystatechange = function() {
    if ( !r && (!this.readyState || this.readyState == 'complete') )
    {
      r = true;
      callback();
    }
  };
  t = document.getElementsByTagName('script')[0];
  t.parentNode.insertBefore(s, t);
}

function renderBanners()
{
        
    zmt_mtag = zmt_get_tag(2466,"~$zedo['masterTag']`");
    ~foreach from=$zedo['tag'] item=foo key=mykey` 
        p~$zedo['masterTag']`_~$mykey` = zmt_mtag.zmt_get_placement("zt_~$zedo['masterTag']`_~$mykey`", "~$zedo['masterTag']`", "~$foo.id`" , "~$foo.source`" , "~$foo.size`" , "~$foo.network`", "~$foo.width`","~$foo.height`");
        p~$zedo['masterTag']`_~$mykey`.zmt_add_ct("~$zedoProfileDetail`");
        ~/foreach`
        zmt_mtag.zmt_set_async();
        zmt_mtag.zmt_load(zmt_mtag);

	~foreach from=$zedo['tag'] item=foo key=mykey`
	    var newScript = document.createElement('script');
	    newScript.id="zt_~$zedo['masterTag']`_~$mykey`";
	    newScript.text="zmt_mtag.zmt_render_placement(p~$zedo['masterTag']`_~$mykey`);";
	    document.getElementById("zt_~$zedo['masterTag']`_~$mykey`").appendChild(newScript);
	~/foreach`

}

window.onload=function(){setTimeout(function(){loadScript('http://axp.zedo.com/client/axp/fmos.js',renderBanners);},100); 
$('#gutterBanner').css('left',($('#css3menu').width()+$('#css3menu').offset().left)+'px');
var el=document.getElementById('newmenu'); if (el!==null) initialPos=el.offsetTop; makeMenuSticky();

}
</script>
<script>
function makeMenuSticky(){
if   ($('#newmenu').length>0){
$(window).scroll(function () {
if ($(window).scrollTop()>=initialPos) $('#newmenu').addClass('stickyMenu');
  else $('#newmenu').removeClass('stickyMenu');

});
}
}
var SITE_URL="~sfConfig::get('app_site_url')`";
var prof_checksum="~$sf_request->getAttribute('checksum')`";
var user_login="~$sf_request->getAttribute('login')`";
var loggedIn="~$sf_request->getAttribute('login')`";
var AppLoggedInUser="~$sf_request->getAttribute('AppLoggedInUser')`";
var google_plus=0;
var searchId = "~$sf_request->getParameter("searchid")`";
if(!searchId)
        searchId = "~$searchId`";
var showSearchBand="~$showSearchBand`";

</script>


<style>
.stickyMenu{
position:fixed;
    top: 0;
    left: 0;
    right: 0;
    margin: 0 auto;
        z-index:1000000;


}	

</style>
<input type="hidden" id="PHONE_VERIFIED" value="~$sf_request->getAttribute('PHONE_VERIFIED')`">
<input type="hidden" id="showConsentMsgId" value="~$sf_request->getAttribute('showConsentMsg')`">
<noscript>
	<div style="position:fixed;z-index:1000;width:930px;">
		<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;"><b><img src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due to this certain functionalities will not work. <a href="~sfConfig::get('app_site_url')`/jshelp/js-help-new.html" target="_blank">Click Here</a> , to know how to enable it.</b>
		</div>
	</div>
</noscript>
 

<div class="hdr-tp-bg sprte-hdr-foot pad7top ">
<div class="hdr-con">
	<div class="hdr-tp-bg-txt pos-rel ">
		~if $sf_request->getAttribute('login')` 
		Welcome ~$sf_request->getAttribute('username')`
		<span style = "position:absolute;">
		| 
		<div class="b blink" style="height:19px; display:inline-block" id="outer_setting">Settings</div>
  		<p id="inner_setting" class="set_menu" style="display:none;position:relative; height:105px;  z-index:1;">
			<a href="~sfConfig::get('app_site_url')`/register/page6?checksum=~$sf_request->getAttribute('checksum')`">Manage Filters</a><br />

			<a href="~sfConfig::get('app_site_url')`/profile/revamp_privacy_settings.php?checksum=~$sf_request->getAttribute('checksum')`">Profile Visibility</a><br />

			<a href="~sfConfig::get('app_site_url')`/profile/unsubscribe.php?checksum=~$sf_request->getAttribute('checksum')`">Alert Manager</a><br />

			<span id="abc3"><a href="~sfConfig::get('app_site_url')`/profile/hide_delete_revamp.php?checksum=~$sf_request->getAttribute('checksum')`">~if $sf_request->getAttribute('activated') neq 'H'`Hide / Delete Profile~else`Unhide / Delete Profile~/if`</a><br />
			</span>

			<a href="~sfConfig::get('app_site_url')`/profile/revamp_change_password.php?checksum=~$sf_request->getAttribute('checksum')`">Change Password</a><br />
		</p>
		</span>
		<span style = "margin-left:65px"> | <a class="cur-point b" id="head_logout" href="#">[ Logout ]</a></span>
		~else if $pageName eq 'SearchPage' || $pageName eq 'successStory' || $pageName eq 'membership'`
			Welcome Guest | <a class="cur-point" onClick="sub_header_fn(0,'~$pageName`',9999);return false;" href="~sfConfig::get('app_site_url')`/profile/login.php">Login</a> | <a class="cur-point b" href="~sfConfig::get('app_site_url')`/profile/registration_page1.php?source=js_header">Register</a>
		~else`
			Welcome Guest | <a class="cur-point thickbox" href="~sfConfig::get('app_site_url')`/profile/login.php?SHOW_LOGIN_WINDOW=1">Login</a> | <a class="cur-point b" href="~sfConfig::get('app_site_url')`/profile/registration_page1.php?source=js_header">Register</a>
		~/if`
	</div>
	<div class="fl mar120left mar1top ">
		<div class="hdr-naukri-btn1 sprte-hdr-foot"> </div>
		<div class="fl">Group </div>
	</div>
	<div class="hdr-tp-bg-txt1 fr" >
		<b>
		<!-- <i class="hdr-spky sprte-hdr-foot fl"></i> -->
		<!-- <a class=" b "  style="color:#D20000" href="http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=http://www.jeevansathi.com/images_try/liveperson"  target="_blank" onclick="javascript:window.open('http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=http://www.jeevansathi.com/P/faq_redirect.htm&site=13507809&imageUrl=http://www.jeevansathi.com/images_try/liveperson&referrer='+escape(document.location),'chat13507809','width=472,height=320');return false;">Live Help</a> --></b> &nbsp;
		<a href="~sfConfig::get('app_site_url')`">Home</a>
		| <a href="~sfConfig::get('app_site_url')`/successStory/story">Success Stories</a>
		| <a href="~sfConfig::get('app_site_url')`/profile/contact.php">Contact Us</a>
	</div>
</div>
</div><!-- top bar ends -->



<div class="hdr-con ">
	<div class="h115">
		<div  class="fl w172">
			<a href="~sfConfig::get('app_site_url')`"><i class="hdr-logo-small sprte-hdr-foot fl">&nbsp;</i></a>
			<font class="fr fs12">We Match Better</font>
		</div>
		<div id="zt_~$zedo['masterTag']`_top" class="fr mar10top" style="position:relative;" > 
  			
	</div>
	</div>
</div><!-- logo and banner ends -->
<!--start:header menu-->
<div id="newmenu">
	<div class="greybar">
    	<div class="mcontainer" >
        	<!--start:header menu-->
        	~if $sf_request->getAttribute('login')`
				~include_partial("global/sub_header_navigation_login",['szNavType'=>$szNavType])`
            ~else`
				~include_partial("global/sub_header_navigation_loggedOut",['pageName'=>$pageName])`
            
            ~/if`
           
				
            <!--end:header menu-->  
            
            
        </div>
    </div>
</div>
~assign var=zedoTag value= $zedo["tag"]`
~if $zedoTag.side`
				<div id="gutterBanner" class="gutter-banner-vertical"><span id="zt_~$zedo['masterTag']`_side">
	</span></div>
			~/if`
<!--start:header menu-->
