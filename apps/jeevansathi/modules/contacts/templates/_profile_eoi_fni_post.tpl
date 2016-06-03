~if MobileCommon::isMobile() neq 1`
	~include_partial("contacts/eoiCongratulation",['contactEngineObj'=>$contactEngineObj])`
	<div style="color:#505050; margin-left:23px" class="fs14">
    		We are happy you found a profile you like, we will notify you as soon as ~$contactEngineObj->contactHandler->getViewed()->getUSERNAME()` responds.
	</div>
	<div class="sp15"></div>
	<div class="sp5"></div>
	<div class="fs16" style="margin-left:23px;">

	    To include contact details or send message, ~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR])`
	</div>
~else`

	<section>
		<div class="pgwrapper">
        		<div class="js-content">
            			~include_partial("contacts/eoiCongratulation",['contactEngineObj'=>$contactEngineObj])`

            			<p>To write your personalized message &amp; include your phone/email in your message</p>			
            			<p class="clearfix">~Messages::getBuyPaidMembershipLink(["NAVIGATOR"=>$NAVIGATOR,"CLASS"=>"pull-left btn pre-next-btn","Link"=>"Become a paid member","style"=>"width:auto"])`</p>
        		</div>
	    	</div> 
	</section>
~/if`