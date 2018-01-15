<section class="s-info-bar">
		<div class="pgwrapper">
		~if !$PERSON_SELF`
			Profile of ~$PROFILENAME`
		~else`
			My Profile
		~/if`
		~$BREADCRUMB|decodevar`
		</div>
	</section>
<!-- Header end-->
<section class="action-btn bdr-btm">
	<div class="pgwrapper">
~if $SHOW_NEXT_PREV`
        ~if $SHOW_PREV || $SHOW_NEXT`
        ~if $SHOW_PREV`
                ~if $fromPage eq contacts`
                <a href="/profile/viewprofile.php?~$prevLink`&responseTracking=~$responseTracking`" class="pull-left btn pre-next-btn">Previous</a>
                ~else`
                <a href="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-left btn pre-next-btn"> Previous</a>
                ~/if`
        ~/if`
        ~if $SHOW_NEXT`
        ~if $fromPage eq contacts`
        <a href="/profile/viewprofile.php?~$nextLink`&responseTracking=~$responseTracking`" class="pull-right btn pre-next-btn"> Next</a>
        ~else`
        <a href="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`" class="pull-right btn pre-next-btn" > Next</a>
        ~/if`
        ~/if`
        ~/if`
~/if`
</div>
</section>

<!-- myjs feilds Start-->
<!-- myjs feilds end-->

<div class="confirm_msg fbld">
	~if $LOGIN_REQUIRED`
	Please note this Profile requires Login before it can be viewed.
  <br/>
</div>
<div class="new_exreg_btn">
New User? <a href="~sfConfig::get("app_site_url")`/jsmb/register.php">Register Free</a>
</div>

<div class="new_exreg_btn1">
Existing User <a href="~sfConfig::get("app_site_url")`/jsmb/login_home.php">Login</a>
</div>
	~else`
	~$MESSAGE|decodevar`
</div>
	~/if`
<p style="height:60px;"></p>

<br>
<p class="clr"></p>  
