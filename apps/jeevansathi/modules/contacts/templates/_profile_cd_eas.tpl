
<div class="sp8"></div>
<div class="inner_div">
~include_partial("contacts/profile_locked_phoneEmail")`


<div class="sp15"></div>
~if $contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser() eq CONTACT_ELEMENTS::EVALUE_STOP || $contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser() eq CONTACT_ELEMENTS::EVALUE_NO`
<div class="fs14">Upgrade your membership to view phone/email of  ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` (and other members) <div class="sp12"></div> 

<div>
<div class="sp12"></div> 
<strong>Why Upgrade?</strong>
<div class="sp12"></div> 
<ul>
<li>Instantly see phone/email</li>
<li>Initiate messages and chat</li>
<li>Get more interests and faster responses</li>
</ul>
<div class="sp12"></div> 
<div class="sp12"></div> 
<a href="/profile/mem_comparison.php"> View Membership Plans </a>
</div>
</div>
~else`
<div class="fs16">Unlock Phone/Email of this member NOW,<br /> 
<div class="sp3"></div>
 ~Messages::getBuyPaidMembershipButton(["NAVIGATOR"=>$NAVIGATOR])`</div>
<div class="sp5"></div>
<div class="sp15"></div>
<div class="fs16"></div>
<div class="sp5"></div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div class="sp5"></div>
<div class="fs16"></div>
~/if`
</div>

~include_partial("contacts/notinterested",[contactEngineObj=>$contactEngineObj])`

