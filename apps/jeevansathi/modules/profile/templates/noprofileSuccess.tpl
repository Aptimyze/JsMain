<!--Main container starts here-->
   <!--Header starts here-->
 ~include_partial('global/header',[showGutterBanner=>1])`
 <!--Header ends here-->
<!--pink strip starts here-->
<div id="main_cont">	
    
<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
 <!--slide-bluetop starts here-->


 <!--slide-bluetop ends here-->
<!--orange strip starts here here-->

<!--orange strip ends here here-->

<!--top tab  start -->

<div class="sp16"></div>
<div class="b">~if $PROFILENAME`<a name="det_prof"></a>~/if`<div class="spacer1">
<a name="express_link"></a>
</div>
</div>
<div class="lstnxt b">~$BREADCRUMB|decodevar`</div>
~if $OFFLINE_CALL_PROGRESS`
<div class="b fr">Communication with client in progress</div>
~else`
<div class="~if !$OFFLINE_ASSISTANT_REM`addmem~else` b ~/if` fr">~if $OFFLINE_ASSISTANT_ADD`<img src="~sfConfig::get(app_img_url)`/images/plus-icon.gif" align="absmiddle">&nbsp;<a href="~$SITE_URL`/profile/invoke_contact_engine.php?width=400&height=360&checksum=&profilechecksum=~$PROFILECHECKSUM`&index=0&to_do=add_intro&ajax_error=1" class="thickbox">Add members to "intro call" list</a>~/if`~if $OFFLINE_ASSISTANT_REM`Added to `members to be called list`~/if` </div>
~/if`

~include_partial("profile_sub_head",[FROM_PROFILEPAGE=>$FROM_PROFILEPAGE,TopUsername=>$TopUsername,total_rec=>$total_rec,actual_offset=>$actual_offset,j=>$j,searchid=>$searchid,other_params=>$other_params,NAVIGATOR=>$NAVIGATOR,PROFILECHECKSUM=>$PROFILECHECKSUM,SHOW_NEXT_PREV=>$SHOW_NEXT_PREV,SHOW_PREV=>$SHOW_PREV,SHOW_NEXT=>$SHOW_NEXT,SHOW_PREV=>$SHOW_PREV,fromPage=>$fromPage,prevLink=>$prevLink,nextLink=>$nextLink,OnlineMes=>$OnlineMes,stopAlbumView=>$stopAlbumView,show_profile=>$show_profile,actual_offset_real=>$actual_offset_real,curLink=>$curLink,responseTracking=>$responseTracking])`
<div class="sp3"></div>
<div class="lf"  style="margin-right:150px;margin:auto;width:930px;">
<div class="sp3"></div>
<div class="lf">
</div>
<div class="sp8"></div>
	~if $LOGIN_REQUIRED`
	<div class="lf">
	        <div class="sp8"></div>
        	<div class="lf t14  gray">
        	<div class="lf" style="width:36px"><img align="absmiddle" src="~sfConfig::get('app_img_url')`/img_revamp/notification.gif"></img></div>
        	<div class="lf" style="width:720px;margin-top:5px">  This profile requires <a class="thickbox" href="login.php?SHOW_LOGIN_WINDOW=1">login</a> before it can be viewed. New user, please  <a href="~sfConfig::get('app_site_url')`/profile/registration_new.php?source=js_block">register here</a></div><div class="sp12"></div><div style="margin-left: 40px;" class="lf t14 b">
        	</div>
        </div>
~/if`
	~if $MESSAGE`
	<div class="lf">
	        <div class="sp8"></div>
        	<div class="lf t14 gray">
        	<div class="lf" style="width:36px"><img align="absmiddle" src="~sfConfig::get('app_img_url')`/img_revamp/notification.gif"></img></div>
        	 <div class="lf" style="width:720px;margin-top:5px"> ~$MESSAGE|decodevar`</div>
        	 <div class="sp12"></div><div style="margin-left: 40px;" class="lf t14 b">
        	</div>
        </div>
        ~/if`
	<div class="sp12" style="height:300px"> </div>
</div>
  <div class="sp8"></div>
  <div class="sp8"></div>
</div>

  <!--right part ends here-->

 <p class=" clr_2"></p>

<p class="clr_18"></p>
 
<!--mid bottom content end -->
 <p class=" clr_18"></p>

<!--footer tabbing  start -->
<!--Main container ends here-->	
~if $SEO_FOOTER`
<p class="clr_8"></p><p class="clr_8"></p>
~include_partial('seo/tabbing',[SEO_FOOTER=>$SEO_FOOTER])`;
~/if`
</div>
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,data=>$loginProfileId])`
