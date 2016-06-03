<div class="sp8"></div>
~if $contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser() eq CONTACT_ELEMENTS::EVALUE_SHOW`
<div style="padding-left:12px; width:337px;">
~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` has an eValue plan and has made contact details visible
</div>
~/if`
~if $contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser() eq CONTACT_ELEMENTS::EVALUE_SHOW`
<div class="inner_div" style="height:152px;">
~else`
<div class="inner_div">
~/if`
~if $contactEngineObj->getComponent()->contactDetailsObj->getPostDirectCall() eq 1`
<div class="fs16">You have <font class=" b">~$contactEngineObj->getComponent()->contactDetailsObj->getLEFT_ALLOTED()` </font>more contacts left to view. <br/>
</div>
~/if`
<div class="sp15"></div>
<div>
~assign var=detailObj value=$contactEngineObj->getComponent()->contactDetailsObj->getContactDetailArr()`
~assign var=pagesource value=$contactEngineObj->contactHandler->getPageSource()`
~foreach from=$detailObj item=i`
<div class="dspce15"> ~$i.LABEL`</div>
<div class="dspce15">
~if $i.mobileTag eq 1`
<div class="pMar"> <strong> <a href="tel:~$i.VALUE|decodevar`">~$i.VALUE|decodevar`</a></strong></div>
~else`
~if $i.LABEL eq "Email Id"`
<div class="pMar"> <a href="mailto:~$i.VALUE|decodevar`"> ~$i.VALUE|decodevar`</a></div>
~else`
<div class="pMar"> <strong> ~$i.VALUE|decodevar`</strong></div>
~/if`
~/if`
~if $i.REPORT eq 1` 
~if MobileCommon::isMobile() neq 1`
<div class="pMar">
<a class="thickbox" href="/profile/report_invalid_phone.php?checksum=&profilechecksum=~Messages::getUserChecksum()`
~if $pagesource eq 'search'`&fromSearch=1 ~/if`">
<strong> Report Invalid number </strong></a></div>
 ~/if`~/if`</div>
<div class="sp15"></div>
~/foreach`
</div>
</div>
~if  $contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser() eq CONTACT_ELEMENTS::EVALUE_SHOW`
<div class="sp15"></div>
<div style="padding-left:12px; width:337px;">
Upgrade to eValue to make your phone/email visible to all matching profiles
<div class="sp15"></div>
<a href="/profile/mem_comparison.php"> View Membership Plans </a>
</div>
~/if`
~if $contactEngineObj->contactHandler->getContactObj()->getType() neq 'N' && $contactEngineObj->contactHandler->getContactObj()->getType() neq 'E'`
~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`
~/if`



