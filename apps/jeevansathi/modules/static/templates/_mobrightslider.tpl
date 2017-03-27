
<div id="slider_right" class="list-menu-right hideslider">
	~if $profileMemcacheObj->get("AWAITING_RESPONSE_NEW") || $profileMemcacheObj->get("AWAITING_RESPONSE")`
		~if $profileMemcacheObj->get("AWAITING_RESPONSE_NEW")`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=eoi&filter=R" alt="Awaiting my response" title="">People to Respond to<strong class="icon-mamr">~$profileMemcacheObj->get("AWAITING_RESPONSE_NEW")`</strong></a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=eoi&filter=R" alt="" title="Awaiting my response" class="icon-mamr">People to Respond to </a>
		~/if`
	~/if`
	
	~if $profileMemcacheObj->get("ACC_ME_NEW") || $profileMemcacheObj->get("ACC_ME")`
		~if $profileMemcacheObj->get("ACC_ME_NEW")`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=accept&filter=R" alt="Members who accepted me" title="Members who accepted me" >People who Accepted me<strong class="icon-mwam">~$profileMemcacheObj->get("ACC_ME_NEW")`</strong></a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=accept&filter=R" alt="Members who accepted me" title="Members who accepted me" class="icon-mwam">People who Accepted me</a>
		~/if`
	~/if`
	
	~if $profileMemcacheObj->get("MATCHALERT")`
		<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=matches&filter=R" alt="Daily Recommendations" title="">Daily Recommendations<strong class="icon-fm">~$profileMemcacheObj->get("MATCHALERT")`</strong></a>
	~/if`
	
	~if $profileMemcacheObj->get("MESSAGE_NEW") || $profileMemcacheObj->get("MESSAGE")`
		~if $profileMemcacheObj->get("MESSAGE_NEW")`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=messages&filter=R" alt="My Messages" title="">My Messages<strong class="icon-mm">~$profileMemcacheObj->get("MESSAGE_NEW")`</strong></a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=messages&filter=R" alt="My Messages" title="" class="icon-mm">My Messages</a>
		~/if`	
	~/if`
	
	~if $profileMemcacheObj->get("PHOTO_REQUEST_NEW")|| $profileMemcacheObj->get("PHOTO_REQUEST")`
		~if $profileMemcacheObj->get("PHOTO_REQUEST_NEW")`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=photo&filter=R" alt="Photo requests received" title="">Photo Requests Received<strong class="icon-prr">~$profileMemcacheObj->get("PHOTO_REQUEST_NEW")`</strong></a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=photo&filter=R" alt="Photo requests received" title="" class="icon-prr">Photo Requests Received</a>
		~/if`
	~/if`
	
	~if $profileMemcacheObj->get("HOROSCOPE_NEW")|| $profileMemcacheObj->get("HOROSCOPE")`
		~if $profileMemcacheObj->get("HOROSCOPE_NEW")`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=horoscope&filter=R" alt="Horoscope requests received" title="">Horoscope Requests Received<strong class="icon-hrr">~$profileMemcacheObj->get("HOROSCOPE_NEW")`</strong></a>
		~else`
			<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?&page=horoscope&filter=R" alt="Horoscope requests received" title="" class="icon-hrr">Horoscope Requests Received</a>
		~/if`	
	~/if`

</div>
