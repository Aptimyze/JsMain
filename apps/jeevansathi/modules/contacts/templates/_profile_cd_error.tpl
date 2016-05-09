<div class="inner_div_detail">
~if $errorIcon eq 1`
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs16">~$contactEngineObj->getComponent()->errorMessage`</div>
~else`
~include_partial("contacts/profile_locked_phoneEmail")`
<div class="sp15"></div>
<div class="fs15 fl w300 mar_top_-4">~$contactEngineObj->getComponent()->errorMessage|decodevar`</div>
~/if`
</div>
