<style>
.fl.clear.fullwidth span:first-child.fl {
width: 105px;
  font-size: 13px;
  padding-top: 3px;
}
table {
  font-size: 13px;
  font-weight: bold;
color: #5B5B5B;
}
div.fl.clear.fullwidth {
  line-height: 18px;
}
</style>
<div class="mar47top"></div>
<fieldset>
<legend>Express Interest in ~$profileObject.username`</legend>
<div class="mar40top">
<div class="fl mar15right">
<div id="fto_~$profilechecksum`" class="layerce">

<script>contactType='N';</script>

<input type="button" class="w204 fto-btn-green-fto white fs24 sprte-fto" style="cursor: pointer;" value="Express Interest" onclick="onExpressInterest('~$profilechecksum`')"/>
</div> 
</div>
<span class="fs24 fl lh18"> In ~$profileObject.username` to be able to see 
~if $loginGender eq 'M'`
her 
~else`
his
~/if`
Phone /Email </span>


<div class="fl pad15top pad25left mar28top fto-exp-intr">
<div class="fl w180"> 
<a href="~$profileObject.viewProfileUrl`">
<div style="background-image:url(~$profileObject.profilePicUrl`); width: 150px; height: 200px;">
<img src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;" style="outline:1px solid #aaa" alt="~$profileObject.username`"/>
</div>
</a>
<div class="sp10 clear">&nbsp;</div>
~if $profileObject.showViewAlbumLink eq '1'`
<div class="mar42left">
<a class="thickbox" href="/social/album?profilechecksum=~$profilechecksum`">View Album</a></div>
~/if`
</div>
<div class="fl w295" style="color:#818181">
<div class="fr fullwidth " style="border-right: 1px solid #e8e8e9"> <a href="~$profileObject.viewProfileUrl`" class="clear block fs16 mar30bottom" style="margin-bottom: 15px;">~$profileObject.username`</a>
<div class="fl clear fullwidth"> 
<span class="fl">Age</span>
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.age`&nbsp;yrs
</td>
</tr>
</tbody>
</table>
</span> 
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Height</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.height|decodevar`
</td>
</tr>
</tbody>
</table>
</span> 
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Religion</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.religion`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Caste</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.caste`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Community</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.community`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Education</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.education`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth " > 
<span class="fl">Occupation </span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.occupation`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth" > 
<span class="fl">Income</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.incomeLevel`
</td>
</tr>
</tbody>
</table>
</span>
</strong>
</div>
<div class="fl clear fullwidth" style="padding-bottom: 15px;"> 
<span class="fl">Location</span> 
<strong>
<span class="fl" style="width: 180px;">
<table>
<tbody>
<tr>
<td valign="top">
:&nbsp;
</td>
<td>
~$profileObject.country`
</td>
</tr>
</tbody>
</table>
</span> 
</strong>
</div>
</div>
</div>
<div class="w245 fl mar37left mar30top">
<div>
<div class="fl">Phone :
<div class="sp10"></div>
Email :</div>
<div class="fl sprte-fto fto-num-disabled w148 h45">&nbsp;</div>
</div>
</div>
<div class="fs16 fl mar37left mar48top w245">To unlock Phone/Email* </div>
<input type="hidden" name="draft" id="draft" value="~$draft`" />
<input type="button" class="w204 fto-btn-green-fto white fs24 sprte-fto mar37left mar10top" style="cursor: pointer;" value="Express Interest" onclick="ftoExpress('fto_~$profilechecksum`')"/>
</div>
</fieldset>
