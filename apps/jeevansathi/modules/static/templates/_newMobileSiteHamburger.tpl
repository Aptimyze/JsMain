
~assign var=showKundliList value= $sf_request->getParameter('showKundliList')`
<div >
	<div id="outerHamDiv" class="fullwid outerdiv" >
    	
       
       ~if $loggedIn`
			
			
		<div id="mainHamDiv" class="wid76p" style="float:left;">
		

			
			<div id="newHamlist" class="hamlist hampad1" > 
                		 <!--start:top header hamburger-->
          <div id="HamMenu" class="fontlig padHamburger">
            <div class="fl fullwid pt7">
              <div class="dispibl txtc  newham_wid32p"> <a  bind-slide=1 href="/inbox/1/1" class="dispbl white f12"> <i class="newham_icons1 int_rec posrel"> <!--start:count-->
                ~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')`
					<div class="posabs newham_pos1">
					  <div class="bg7 disptbl newham_count txtc" >
						<div class="vertmid dispcell">
						~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')>99`
							99+
						~else`
							~$profileMemcacheObj->get('AWAITING_RESPONSE_NEW')`
						~/if`						
						</div>
					  </div>
					</div>
				~/if`
                <!--end:count--></i>
                <div>Interests <br/>
                  Received</div>
                </a> </div>
              <div class="dispibl txtc newham_wid32p"> <a  bind-slide=1 href="/inbox/2/1" class="dispbl white f12"> <i class="newham_icons1 acc_mem posrel"><!--start:count-->
                ~if $profileMemcacheObj->get('ACC_ME_NEW')`
					<div class="posabs newham_pos1">
					  <div class="bg7 disptbl white f12 newham_count txtc" >
						<div class="vertmid dispcell">
						~if $profileMemcacheObj->get('ACC_ME_NEW')>99`
							99+
						~else`
							~$profileMemcacheObj->get('ACC_ME_NEW')`
						~/if`	
						</div>
					  </div>
					</div>
				~/if`
                <!--end:count--></i>
                <div>All<br/>
                  Acceptances</div>
                </a> </div>
              <div class="dispibl txtc newham_wid32p"> <a  bind-slide=1 href="/search/perform?justJoinedMatches=1" class="dispbl white f12"> <i class="newham_icons1 just_join posrel"> <!--start:count-->
                ~if $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')`
					<div class="posabs newham_pos1">
					  <div class="bg7 disptbl white f12 newham_count txtc" >
						<div class="vertmid dispcell">
						~if $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')>99`
							99+
						~else`
							~$profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')`
						~/if`
						</div>
					  </div>
					</div>
				~/if`
                <!--end:count--></i>
                <div>Just Joined <br/>
                  Matches</div>
                </a> </div>
            </div>
            <div class="clr"></div>
          </div>
          <!--end:top header hamburger-->
                <div class="brdr9_ham">
                  <div class="newham_pad1 lh25">
                    <div class="white fb1 fontrobbold f15">~$MembershipMessage.top`</div>
                    <a href="/profile/mem_comparison.php" bind-slide=1 class="white f18">~$MembershipMessage.bottom`</a>
                  </div>
                </div>
                <!--end:offer--> 
                <!--start:listing1-->
                <div class = "brdr9_ham pt20">
                  <ul class="fontlig">
                  <li><a href="#" onclick=translateSite('~$translateURL`'); bind-slide=1 class="white" style="font-size: 19px;">हिंदी में</a></li>
                  <li>                  <!--start:listing6-->
                <div id='appDownloadLink1' style='display:none'>       
                    <a onclick="window.location.href='/static/appredirect?type=jsmsHamburger';" bind-slide=1 class="white">Download  App | 3MB only </a>
                </div>
                <!--end:listing6--> 
                 <!--start:listing7-->
                <div id='appleAppDownloadLink1' style='display:none'>
                    <a onclick="window.location.href='/static/appredirect?type=jsmsHamburger&channel=iosLayer';" bind-slide=1 class="white">Download iOS App </a>
                    
                </div>
                <!--end:listing6--> </li>                 
                    <li><a href="/" bind-slide=1 onclick='trackJsEventGA("jsms","homeClick", "", "");' class="white" style="font-size: 17px;">Home</a></li>
                    <li><a href="/search/topSearchBand?isMobile=Y" bind-slide=1 class="white">Search</a></li>
                    <li><a href="/search/searchByProfileId" bind-slide=1 class="white">Search by Profile ID</a></li>
                    <li><a href="/search/MobSaveSearch" bind-slide=1 class="white">Saved Searches <span class="dispibl padl10 opa70 f12">~$savedSearchCount`</span></a></li>
                  </ul>
                </div>
                <!--end:listing1--> 
                <!--start:listing2-->
                <div class="brdr9_ham pt20">
                  <ul class="fontlig">
                    <li class="white fb1 ham_opa fontrobbold">My Matches</li>
                    
                    <li><a href="/search/perform?justJoinedMatches=1" bind-slide=1 class="white">Just Joined Matches ~if $profileMemcacheObj->get('JUST_JOINED_MATCHES')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('JUST_JOINED_MATCHES')`</span> ~/if`</a></li>
                    <li><a href="/search/verifiedMatches" bind-slide=1 class="white">Verified Matches </a></li>
                    
                    <li><a href="/inbox/7/1" bind-slide=1 class="white">Daily Recommendations ~if $profileMemcacheObj->get('MATCHALERT_TOTAL')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('MATCHALERT_TOTAL')`</span> ~/if`</a></li>
                    
                    <li><a href="/search/perform?partnermatches=1" bind-slide=1 class="white">Desired Partner Matches </a></li>
                    
                     ~if $showKundliList eq '1'`
                    <li><a href="/search/perform?kundlialerts=1" bind-slide=1 class="white">Kundli Matches <span class ="dispibl padl10 f12 white opa50">New</span></a></li>             
                    ~/if`
                    
                    <li><a href="/search/perform?twowaymatch=1" bind-slide=1 class="white">Mutual Matches</a></li>
                    
                    <li><a href="/search/perform?reverseDpp=1" bind-slide=1 class="white">Members Looking For Me</a></li>
                   
                    
                    <li><a href="/search/visitors?matchedOrAll=A" bind-slide=1 class="white">Profile Visitors ~if $profileMemcacheObj->get('VISITORS_ALL')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('VISITORS_ALL')`</span> ~/if`</a></li>
                    
                   <!-- <li><a href="#" bind-slide=1 class="white">Kundli Matches</a></li>-->
                    
                    
                  </ul>
                </div>
                <!--end:listing2--> 
                <!--start:listing3-->
                <div class="brdr9_ham pt20">
                  <ul class="fontlig">
                    <li class="white fb1 ham_opa fontrobbold">My Contacts</li>
                   
                    <li><a href="/inbox/1/1" bind-slide=1 class="white">Interests Received ~if $profileMemcacheObj->get('AWAITING_RESPONSE')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('AWAITING_RESPONSE')`</span> ~/if`</a></li>
                    
                   <!-- <li><a href="#" bind-slide=1 class="white">Filtered Interests </a></li>-->
                    <li><a href="/inbox/12/1" bind-slide=1 class="white">Filtered Interests ~if $profileMemcacheObj->get('FILTERED')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('FILTERED')`</span> ~/if`</a></li>


                    <li><a href="/inbox/2/1" bind-slide=1 class="white">All Acceptances ~if $profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME')`</span> ~/if`</a></li>
                    
                   <!-- <li><a href="#" bind-slide=1 class="white">Contacts Viewed</a></li>-->
                    <li><a href="/inbox/16/1" bind-slide=1 class="white">Phonebook</a></li>
                    
                    <li><a href="/inbox/17/1" bind-slide=1 class="white">Who Viewed My Contacts</a></li>
                    


                    <li><a href="/search/shortlisted" bind-slide=1 class="white">Shortlisted Profiles ~if $profileMemcacheObj->get('BOOKMARK')` <span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('BOOKMARK')`</span>~/if`</a></li>
                    
                    <li><a href="/inbox/4/1" bind-slide=1 class="white">Messages</a></li>
                    <!--~if $profileMemcacheObj->get('MESSAGE_NEW')`<span class="dispibl padl10 opa70 f12">~$profileMemcacheObj->get('MESSAGE_NEW')`</span> ~/if`</a></li>-->
                    
                   <li><a href="/inbox/11/1" bind-slide=1 class="white">Declined Members</a></li>
                    
                   <!-- <li><a href="#" bind-slide=1 class="white">Ignored Members</a></li>-->
                    
                    
                  </ul>
                </div>
                <!--end:listing3--> 
                 <!--start:listing4-->
                <div class="brdr9_ham pt20">
                  <ul class="fontlig">
                    <li class="white fb1 ham_opa fontrobbold">More</li>

                    <li><a href="/help/index" bind-slide=1 class="white">Help</a></li>
                    <li><a href="/contactus/index" bind-slide=1 class="white">Contact Us</a></li>
                    
                    <li><a href="/static/settings" bind-slide=1 class="white">Settings</a></li>
                    
                   <!-- <li><a href="#" bind-slide=1 class="white">Feedback</a></li>-->
                    
                    <!--<li><a href="#" bind-slide=1 class="white">Contact Us</a></li>-->
                    
                    <!--<li><a href="#" bind-slide=1 class="white">Ignored Members</a></li>-->
                    
                    
                  </ul>
                </div>
                <!--end:listing4--> 
                <!--start:listing5-->
                <div class="brdr9_ham pt20">
                  <ul class="fontlig">
                    
                    <li><a href="" onclick="window.location.href = 'tel:18004196299';"  title="call" alt="call" class="white">1800-419-6299 <span class="dispibl padl10 opa70 f12">Toll Free</span></a></li>
                    
                  </ul>
                </div>
<!--  Code moved Up to include Download App at top -->
              </div>
           	
				<!--start:edit profile-->
		</div>
				<div id ="hamProfile" class="dn posfix ham_pos3">
					<a bind-slide=1 href="/profile/viewprofile.php?ownview=1" class="dispbl fontlig f12 ham_color2">
						<i class="icons1 posabs ham_icon3 ham_pos4"></i> 
						<div class="pt10 txtc"><img src="~$ProfilePicUrl`" style="height:50px; width:50px;" class="ham_imgbrdr brdr18"/></div>
						<div class="lh25">Edit Profile</div>
					</a>
				</div>        
				<!--end:edit profile-->
      
       ~else`
			<div class="wid76p hamlist fl" id='mainHamDiv'>
			 <!--start:top header hamburger-->
			<div class="clearfix fontlig padHamburger">
				
			</div> 
			 <!--start:listing1-->
                <div class=" pt20  hampad1">
                  <ul class="fontlig">
                                 <li>  <div id='appDownloadLink2' style='display:none'>
                    
              <a onclick="window.location.href='/static/appredirect?type=jsmsHamburger';"  bind-slide=1 class="white">Download  App | 3MB only </a>
                </div>
                <!--end:listing6--> 
                <!--start:listing7-->
                <div id='appleAppDownloadLink2' style='display:none'>
                    
              <a onclick="window.location.href='/static/appredirect?type=jsmsHamburger&channel=iosLayer';"  bind-slide=1 class="white">Download iOS App </a>
                </div> </li>
                <!--end:listing7-->  

                  <li><a href="#" onclick=translateSite('~$translateURL`'); bind-slide=1 class="white" style="font-size: 19px">हिंदी में</a></li>
                    <li><a href="/" bind-slide=1 class="white" style="font-size: 17px">Home</a></li>
                    <li><a href="/search/topSearchBand?isMobile=Y" bind-slide=1 class="white">Search</a></li>
                    <li><a href="/search/searchByProfileId" bind-slide=1 class="white">Search by Profile ID</a></li>
                     <li><a href="/browse-matrimony-profiles-by-community-jeevansathi" bind-slide=1 class="white">Browse by Community</a></li>
                    <li><a href="/contactus/index" bind-slide=1 class="white">Contact Us</a></li>
                    <li><a href="/static/settings" bind-slide=1 class="white">Settings</a></li>
                  </ul>
                </div>
<!--  Code moved Up to include Download App at top -->
                <div class="hampad1" id='appleAppDownloadLink2' style='display:none'>
                  <ul class=" brdr9_ham fontlig">
                    
                    <li class="pt20 white fb1 ham_opa fontrobbold">It's Free</li>
                    <li class=""><a onclick="window.location.href='/static/appredirect?type=jsmsHamburger&channel=iosLayer';"  bind-slide=1 class="white">Download iOS App </a></li>
                    
                  </ul>
                </div>
                <!--end:listing7--> 
            </div>
				<!--start:login-->
				<div class="posfix ham_pos1 fullwid js-loginBtn">
					<div class="pad1">
						<div class="ham_bdr1">
							<div id= "loggedOutHamFoot" class="pt10 fontlig f17">
								<div class="fl wid49p txtc ham_bdr2">
									<a bind-slide=1 href="/static/LogoutPage" class="white lh30">Login</a>
								</div>
								<div class="fl wid49p txtc">
									<a bind-slide=1 href="/register/page1?source=mobreg5" class="white lh30">Register</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end:login-->
        ~/if`
	</div>
</div>
