<div class="mar47top"></div>
<fieldset>
<legend>Suggested Matches</legend>
<div class="mar40top fl fullwidth">&nbsp;</div>


<script>contactType='N';</script>
~assign var="count" value=$suggestedProfiles|@count`
~foreach from=$suggestedProfiles item=message key=i`
~if $i lt $count-1` 
<div class="h326 fto-srch-res mar45right">
~else`
<div class="h326 fto-srch-res">
~/if`
<div class="sp10"></div>
<a href="~$message.viewProfileUrl`" class="fs16">~$message.username`</a>
<div class="sp10"></div>
<a href="~$message.viewProfileUrl`">
<div class="fto-img-holder" style="background-image:url(~$message.profilePicUrl`)">
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
</div>
</a>
<div class="sp10"></div>
<div style="color:#616161;">~$message.age`,&nbsp;~$message.height|decodevar`,&nbsp;~$message.religion`<br />
~$message.community|truncate:10:".."`,&nbsp;~$message.caste|truncate:12:".."`</div>
<div class="sp10"></div>
<div class="center">
<div id="fto_~$message.profilechecksum`" class="layerce">
<input type="hidden" name="draft" id="draft" value="~$draft`"/>
<input type="button" class="fto-btn-green-fto sprte-fto white b" style="height:21px; cursor: pointer;"  value="Express Interest" /></div>
</div>
</div>
~/foreach`


<div class="sp15 clear">&nbsp;</div>
<div class="fullwidth fs20"> 
<span>
<div id="div_offerDCommonError" style="display: none;">
<div class="ce_357" style="display: inline-block; position: absolute; left: 525px; margin-top: -4px;">
<div class="ico-wrong sprite-new" style="width: 30px;">&nbsp;</div>
<div id="errMsg" class="fs13 fl w300" style="text-align: center; margin-top: -25px; margin-left: 20px;">
</div>
</div>
</div>
</span>
<a href="~sfConfig::get('app_site_url')`/search/partnermatches" class="fr b">See More profiles</a> </div>
</fieldset>


