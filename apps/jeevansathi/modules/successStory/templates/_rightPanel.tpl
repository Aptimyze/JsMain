~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<!--Right panel-->
<div class="rf" style="width:123px;margin-right:0px;display:inline">
<div class="lf">
~if !$sf_request->getAttribute('login')`

<div class="lf rfree_panel" style="background:none; padding-top:12px;">
	<a href="~sfConfig::get("app_site_url")`/register/page1?source=rightpanel"><img src="~sfConfig::get("app_img_url")`/P/images/RegBanner.gif" border=0></a>


</div>
<div class="sp8"></div>
~/if`
<div style="padding:0px; width:123px;">
<div class="y">
<div class="y_top">
<div class="y_bot">
<div class="y_lft">
<div class="y_rgt">
<div class="y_b_l">
<div class="y_b_r">
<div class="y_t_l">
<div class="y_t_r">
<div class="sp5"></div>
~foreach from=$rightPanelStory key=k item=story`
<div class="lf orange t12 b" style="text-align:center; width:100%">Success Stories</div>
<div class="sp8"></div>
<div class="lf success_bg"><div style="padding: 21px 13px 5px 9px;"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.HOME_PIC_URL)`" style="width:97px; height:64px"></div></div>
<div class="sp8"></div>
<div class="lf" style="text-align:center; width:100%">~$story.NAME2`<br>
weds <br>
~$story.NAME1`</div>
<div class="sp12"></div>
<div class="lf" style="text-align:center; width:100%"><a href="~sfConfig::get("app_site_url")`/successStory/completestory?sid=~$story.SID`" class="blink b">View More</a></div>
<div class="sp8"></div>

~/foreach`
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
<!--right panel ends here-->
