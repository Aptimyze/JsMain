<!-- Header starts -->
<!-- Header ends -->

~include_partial("mobileDCommon", ["showBackLink" => $showBackLink, "refererUrl" => $refererUrl])`

<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">Finding a Life
Partner</legend>
<div class="content-holder">
<div class="mt10">
<div class="mt10">
<p>
Most people <strong>'Express Interest'</strong> in
at<br>
least <strong>40 profiles</strong> to find a Life
Partner
</p>
</div>
<div class="mt10">
<a href="~sfConfig::get('app_site_url')`/search/partnermatches">
<input value="Express Interest" class="searchbtn fbld" style="font-size:12px; margin-left:0px; padding:0px; text-align: center; width: 130px;" readonly>
</a>
<br>
in all the profiles you like
</div>
<div class="mt10 fs14"></div>
<div class=" mt10 fs14"></div>
</div>
</div>
</fieldset>
</div>

~if $showSuggestedMatches eq 1`
~include_partial("mobileSuggestedProfiles", ["suggestedProfiles" => $suggestedProfiles])`
~/if`

<div class="quickMob" style="text-align:center">
Hurry! offer vaild till
<strong style=" color:#b00800;">~$day`<sup>~$superscript`</sup>
&nbsp;~$month`,&nbsp;~$year`</strong>
</div>

<div class="quickMob" style="margin:0 auto; position:relative; text-align:center;">
<a href="~sfConfig::get('app_site_url')`/search/partnermatches">
<input value="See more matches" class="searchbtn fbld" style="font-size:12px; margin-left:0px; padding:0px; text-align: center;">
</a>
</div>

<div class="quickMob fs14" style="text-align:center">
or call us on 
<a href="tel:18004196299">
1 - 800 - 419 - 6299
</a>
</div>

