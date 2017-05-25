<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">Suggested Matches</legend>
<div class="content-holder">
<div class="mt10">
<div class="mt10">
~assign var="count" value=$suggestedProfiles|@count`
~foreach from=$suggestedProfiles item=message key=i`
~if $i lt $count-1`
<div style="border-bottom:2px solid #CECECE">
~else`
<div>
~/if`
<div style="margin: 0px 10px 5px 0px; background-image: url(~$message.thumbnailPicUrl`)" class="flt">
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="60" height="57" border="0">
</div>
<div>
<div>
<a href="~$message.viewProfileUrl`">
<strong>~$message.username`</strong>
</a>
</div>
<br />
<div class="cll" style="color:#616161;">
~$message.age`,&nbsp;~$message.height|decodevar`,&nbsp;~$message.religion`.<br />~$message.community`,&nbsp;~$message.caste`
</div>
<div>
<a href="~sfConfig::get('app_site_url')`/contacts/PreEoi?profilechecksum=~$message.profilechecksum`&to_do=eoi&STYPE=34">
<input value="Express Interest" class="searchbtn fbld" style="font-size:12px; margin-left:0px; padding:0px; width: auto; text-align: center;" readonly>
</a>
</div>
</div>
</div>
<div class="clr"></div>
~if $i lt $count-1`
<br />
~/if`
~/foreach`
</div>

<div class="fs14 mt10"></div>
<div class="mt10 fs14"></div>
<div class=" mt10 fs14"></div>
</div>
</div>
</fieldset>
<div class="frt" style="font-size:11px">
* For complete details see 
<a href="~sfConfig::get('app_site_url')`/P/disclaimer.php">
terms and
conditions
</a>
</div>
</div>
<div class="clr"></div>
