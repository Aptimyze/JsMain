~if $loggedIn`
<div id="slider" class="hideslider list-menu-left">
		
		<a href="~sfConfig::get('app_site_url')`/search/topSearchBand?isMobile=Y" alt="Search" title="Search"><strong class="icon-search">&nbsp;</strong>Search</a>
		<a href="~sfConfig::get('app_site_url')`/search/partnermatches" alt="My Matches" title="My Matches"><strong class="icon-matches">&nbsp;</strong>My Matches</a>
		<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1" alt="Register" title="My Matches"><strong class="icon-profile">&nbsp;</strong>My Profile</a>
		~if $memStat eq 'F'`
		<a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php" alt="Buy paid Membership (upgrade)" title="Buy paid Membership (upgrade)"><strong class="icon-buypaid">&nbsp;</strong>Buy paid Membership</a>
		~else if $memStat eq 'R'`
		<a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php" alt="Buy paid Membership (upgrade)" title="Buy paid Membership (upgrade)"><strong class="icon-buypaid">&nbsp;</strong>Renew Membership</a>	
		~/if`
		
			<span>My Contacts</span>
			~if $profileMemcacheObj->get('AWAITING_RESPONSE')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=eoi&filter=R" alt="Awaiting my response" title=""><strong class="icon-mamr">&nbsp;</strong>People to Respond to ~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')` (~$profileMemcacheObj->get('AWAITING_RESPONSE_NEW')`) ~/if`</a>
			~/if`
                        <a href="~$SITE_URL`/profile/contacts_made_received.php?page=filtered_eoi&filter=R" alt="Awaiting my response" title=""><strong class="icon-mamr">&nbsp;</strong>Filtered Interests Received</a>
                        <a href="~$SITE_URL`/profile/contacts_made_received.php?page=viewed_contacts_by&filter=R" alt="Who viewed my contacts" title=""><strong class="icon-rpv">&nbsp;</strong>Who Viewed my Contacts</a>
			~if $profileMemcacheObj->get('ACC_ME')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=accept&filter=R" alt="" title=""><strong class="icon-mwam">&nbsp;</strong>People who Accepted me ~if $profileMemcacheObj->get('ACC_ME_NEW')` (~$profileMemcacheObj->get('ACC_ME_NEW')`) ~/if`</a>
			~/if`
				~if $profileMemcacheObj->get('BOOKMARK')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=favorite&filter=M" alt="Shortlisted members" title=""><strong class="icon-sm">&nbsp;</strong>Shortlisted Members</a>
			~/if`
			~if $profileMemcacheObj->get('ACC_BY_ME')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=accept&filter=M" alt="Members I accepted" title=""><strong class="icon-mia">&nbsp;</strong>People I Accepted</a>
			~/if`
		
			~if $profileMemcacheObj->get('NOT_REP')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=eoi&filter=M" alt="Members yet to respond to me" title=""><strong class="icon-mytrtm">&nbsp;</strong>People yet to Respond</a>
			~/if`
			~if $profileMemcacheObj->get('FILTERED')`
			<!--a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=filtered_eoi&filter=R" alt="Filtered members" title=""><strong class="icon-fm">&nbsp;</strong>Filtered members</a-->
			~/if`
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=messages&filter=R" alt="My messages" title=""><strong class="icon-mm">&nbsp;</strong>My Messages ~if $profileMemcacheObj->get('MESSAGE_NEW')` (~$profileMemcacheObj->get('MESSAGE_NEW')`) ~/if`</a>
                <a href="~$SITE_URL`/profile/contacts_made_received.php?&page=decline&filter=M" alt="People I Declined" title=""><strong class="icon-matches">&nbsp;</strong>People I Declined ~if $profileMemcacheObj->get('DEC_BY_ME')` (~$profileMemcacheObj->get('DEC_BY_ME')`) ~/if`</a>
                <a href="~$SITE_URL`/profile/contacts_made_received.php?&page=ignore&filter=M" alt="People I Ignored" title=""><strong class="icon-matches">&nbsp;</strong>People I Ignored</a>
                <a href="~$SITE_URL`/profile/contacts_made_received.php?&page=decline&filter=R" alt="People Not Interested" title=""><strong class="icon-matches">&nbsp;</strong>People Not Interested ~if $profileMemcacheObj->get('DEC_ME_NEW')` (~$profileMemcacheObj->get('DEC_ME_NEW')`) ~/if`</a>
		
		<span>My Requests</span>
		~if $profileMemcacheObj->get('PHOTO_REQUEST')`
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=photo&filter=R" alt="" title=""><strong class="icon-prr">&nbsp;</strong>Photo Requests Received ~if $profileMemcacheObj->get('PHOTO_REQUEST_NEW')`  (~$profileMemcacheObj->get('PHOTO_REQUEST_NEW')`) ~/if`</a>
		~/if`
		~if $profileMemcacheObj->get('HOROSCOPE')`
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=horoscope&filter=R" alt="" title=""><strong class="icon-hrr">&nbsp;</strong>Horoscope Requests Received ~if $profileMemcacheObj->get('HOROSCOPE_NEW')` (~$profileMemcacheObj->get('HOROSCOPE_NEW')`) ~/if`</a>
		~/if`
		
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=chat&filter=A" alt="" title=""><strong class="icon-crr">&nbsp;</strong>Chat Requests Received</a>
		
		~if $profileMemcacheObj->get('MATCHALERT') || $profileMemcacheObj->get('VISITOR_ALERT')`
			<span>My Alerts</span>
			~if $profileMemcacheObj->get('MATCHALERT')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=matches&filter=R" alt="" title=""><strong class="icon-ma">&nbsp;</strong>Match Alerts (~$profileMemcacheObj->get('MATCHALERT')`)</a>
			~/if`
			~if $profileMemcacheObj->get('VISITOR_ALERT')`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=visitors&filter=R" alt="" title=""><strong class="icon-rpv">&nbsp;</strong>Recent Profile Visitors (~$profileMemcacheObj->get('VISITOR_ALERT')`)</a>
			~/if`
		~/if`
		
		<span>More</span>
		<a href="tel:18004196299" alt="Call Us" title="Call Us"><strong class="icon-callus">&nbsp;</strong>Call Us</a>
		<a href="~sfConfig::get('app_site_url')`/faq/feedback" alt="Feedback" title="Feedback"><strong class="icon-feedback">&nbsp;</strong>Feedback</a>
		<a href="~sfConfig::get('app_site_url')`/P/disclaimer.php" alt="Terms and Conditions" title="Terms and Conditions"><strong class="icon-terms">&nbsp;</strong>Terms and Conditions</a>
		<a><strong class="icon-facebook">&nbsp;</strong>&nbsp;
		<iframe scrolling="no" frameborder="0" allowtransparency="true" style="border:none; overflow:hidden; width:100px; height:21px;" src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fm.facebook.com%2Fjeevansathi&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=21"></iframe>
		</a>
		
		
		<a href="~sfConfig::get('app_site_url')`/P/logout.php?mobile_logout=1" alt="" title=""><strong class="icon-logout">&nbsp;</strong>Logout</a>
		<span class="icon-copy">Copyright &copy;~$smarty.now|date_format:"%Y"` Jeevansathi Internet Services</span>
	</div>
	
~else`
<div id="slider" class="hideslider list-menu-left">
	
	<a href="~sfConfig::get('app_site_url')`/register/page1?source=mobreg2" alt="Register" title="Register"><strong class="icon-matches">&nbsp;</strong>Register</a>
	<a href="~sfConfig::get('app_site_url')`/jsmb/login_home.php" alt="Login" title="login"><strong class="icon-matches-login">&nbsp;</strong>Login</a>
	<a href="~sfConfig::get('app_site_url')`/search/topSearchBand?isMobile=Y" alt="Search" title="Search"><strong class="icon-search">&nbsp;</strong>Search</a>
		<a href="tel:18004196299" alt="Call Us" title="Call Us"><strong class="icon-callus">&nbsp;</strong>Call Us</a>
		<a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php" alt="Buy paid Membership (upgrade)" title="Buy paid Membership (upgrade)"><strong class="icon-buypaid">&nbsp;</strong>Buy paid Membership</a>
		
		<span>More</span>
		<a href="~sfConfig::get('app_site_url')`/faq/feedback" alt="Feedback" title="Feedback"><strong class="icon-feedback">&nbsp;</strong>Feedback</a>
		
		<a href="~sfConfig::get('app_site_url')`/P/disclaimer.php" alt="Terms and Conditions" title="Terms and Conditions"><strong class="icon-terms">&nbsp;</strong>Terms and Conditions</a>
		<span class="icon-copy">Copyright &copy;~$smarty.now|date_format:"%Y"` Jeevansathi Internet Services</span>
		
	</div>
~/if`
