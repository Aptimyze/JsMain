<div class="inner_div_detail">
~if $errorIcon eq 1`
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs16">~$contactEngineObj->getComponent()->errorMessage`</div>
~else`
~include_partial("contacts/profile_locked_phoneEmail")`
<div class="sp15"></div>
<div class="fs13"><div class="lf" style=" bottom: 0px; left: 10px;">Only paid members can view contact details directly.<br> Please <a class="blink b" href="/profile/mem_comparison.php?from_source=Express_interest_tab">Upgrade your membership</a> or  <a href="#" class="blink b" onclick="javascript:{show_layer('exp');return false;}">Express Interest</a>
</div></div>
~/if`
</div>
