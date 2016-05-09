~include_partial("profile/mobViewProfile/_tabHeader",['username'=>$TopUsername,'myPreview'=>$myPreview])`
<div class="bg4 txtc" id="errorContent">
	~if $LOGIN_REQUIRED`

	<div class="txtc r_vpro_pad1">
        <div class="f16 fontlig color13 pb20"> To view this profile </div>
    <!--start:login-->
    
		<a href="~sfConfig::get("app_site_url")`/static/logoutPage" bind-slide=1 >
			<i class="vpro_sprite vpro_login"></i>
			<div class="f14 color2 fontlig">Login</div>
		</a>
		<div class="r_vpro_pad2"></div>    
		<!--end:login-->
		 <!--start:login-->
		<a href="~sfConfig::get("app_site_url")`/register/page1?source=mobreg6" >
			<i class="vpro_sprite vpro_regis"></i>
			<div class="f1	4 color2 fontlig">Register</div>
		
		</a>    
		<!--end:login-->

	</div>
	~else`
		<div class="pad19" id="noProfileIcon">    
			<i class="vpro_sprite ~$noProfileIcon`"></i>
			<div class="f14 fontreg color13 lh30">~$MESSAGE|decodevar`</div>
		</div>
	~/if`
</div>
