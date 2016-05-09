<!-- start header -->
~include_partial('global/header',[showSearchBand=>0,searchId=>$searchId,pageName=>$pageName,loggedInProfileid=>$loggedInProfileid])`
<!--end header -->

<style>
.abcde{background:url(~$IMG_URL`/images/hm_pg_sprte3.gif) 0px -261px;}
</style>
<!--Main container starts here-->
<div class="fto-main-content">
<p class="clr_4">
</p>

<div id="topSearchBand">
</div>

~include_partial('global/sub_header',[pageName=>$pageName])`

<p class="clr_4">
</p>
<p class="clr_4">
</p>

~if $showBackLink eq 1`
<a href="~$refererUrl`" class="fs16" style="text-decoration:underline;">&lt;&lt;&nbsp;Back</a>
~/if`
<div class="sp10"></div>
<div class="fto-mem  center"><strong> Free Trial offer ends</strong></div>
<div class="sp10"></div>
<div class="sp10"></div>
<div class="fto-main-heading-2 h57 sprte-fto" ></div>
<div class="sp15"></div>
<div class="h47"></div>

<fieldset>
<legend>Benefits as a Free Member</legend>

<div class="mar35top"></div>



<div class="fl w111 mar94right ">
<div class="fto-bg1 h110 fl center sprte-fto" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center;">Search for matches</div>
</div>
<div class="fl w127 mar84right"><div class="fto-bg3 h110 fl center sprte-fto" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;

</div>
<div class="fl fs24 fullwidth" style="text-align: center; margin-top: 18px;">View Profile<br />
Details</div>
</div>
<div class="fl w176 mar81right">
<div class="fto-bg2  h110 fl sprte-fto mar33left" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center; margin-top: 4px;">Express Interest <br />
in those you like</div>
</div>
<div class="fl w170">
<div class="fto-bg4  w88 h110 fl center sprte-fto mar33left" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center; margin-top: 11px;">Send &amp; receive <br />
Acceptances</div>
</div>
</fieldset> 
<div class="mar47top"></div>
<fieldset>
<legend>Benefits of Paid Membership</legend>

<div class="fl w219" style="margin-right:106px">

<div class="fto-bg5  h110 fl center sprte-fto" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center;">See phone numbers/ <br />
email ids</div>


</div>
<div class="fl w172" style="margin-right:128px;">

<div class="fto-bg6   h110 fl center sprte-fto" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center; margin-top: 2px;">Initiate chat on <br />
website or gtalk</div>


</div>
<div class="fl w202">

<div class="fto-bg7   h110 fl center sprte-fto mar33left" >&nbsp;</div>
<div class="sp10 fl fullwidth">&nbsp;</div>
<div class="fl fs24 fullwidth" style="text-align: center; margin-top: 13px;">Send Personalized <br />
messages</div>


</div>

</fieldset>
<div class="mar47top"></div>
<div class="fs24 center">To enjoy all these benefits </div>
<div class="fs24 center"></div>
<div class="sp10"></div>
<div class="fl center fullwidth fs24">
<input id="buyPaidMembership" type="button" class="w288 fto-btn-green-fto white fs24 sprte-fto " value="Buy Paid Membership" style="cursor: pointer;" onclick="window.location='~sfConfig::get('app_site_url')`/profile/mem_comparison.php';" />
<br />
<div class="sp10"></div>
or
<div class="sp10"></div>
<div>
<p><a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" class="fs24 " style="text-decoration:underline">Continue as Free Member</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
</div>

</div><!--Main content finish -->
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[data=>~$loggedInProfileid`,pageName=>$pageName])`


