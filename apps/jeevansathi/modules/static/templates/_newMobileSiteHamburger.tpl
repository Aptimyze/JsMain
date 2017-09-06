
~assign var=showKundliList value= $sf_request->getParameter('showKundliList')`
    <div id="hamburger" class="white posfix wid90p fullheight"> 

  <div id="outerHamDiv"  >
      
          <div id="mainHamDiv" >
    

      
      <div id="newHamlist" class="hamlist hampad1" > 
                     <!--start:top header hamburger-->

       ~if $loggedIn`
                    <ul id="scrollElem" style="-webkit-overflow-scrolling: touch;" class="fontreg white listingHam overAutoHidden"> 
      
      

                        <li class="f13 pb8 fontlig">
                            <div id="appDownloadLink1" class="wid49p dispnone"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger" target="_blank" class="white fl mar0Imp">Download  App | 3MB only </a></div>
                            <div class="wid49p dispnone" id="appleAppDownloadLink1"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger&channel=iosLayer" target="_blank" class="white fl mar0Imp">Download iOS App </a></div>
                            <div class="wid49p dispibl">
                                <div id="hindiLink" onclick="translateSite('http://js.mox.net.in');" class="white fr mar0Imp">हिंदी में</div>
                            </div>
                        </li>
                        <div style="height: 1px;padding: 0px 20px;"><div style="background-color: white;height: 1px;opacity: .5;"></div></div>                        
                            <li>
                            <div class="fullwid">
                                <div class="dispibl txtc wid32p">
                                    <a bind-slide=1  id="awaitingResponseLinkTop" href="/inbox/1/1" class="dispbl white f12">
                                        <i id="int_rec" style="margin: 0px;" class="hamSprite irIcon posrel">
                                            ~if $profileMemcacheObj->get('AWAITING_RESPONSE')` 
                                            <div class="posabs newham_pos1"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">~if $profileMemcacheObj->get('AWAITING_RESPONSE')>99` 99+~else`~$profileMemcacheObj->get('AWAITING_RESPONSE')`~/if`</div></div></div>
                                            ~/if` 
                                        </i>
                                        <div>Interests<br>Received</div>
                                    </a>
                                </div>
                                <div class="dispibl txtc wid32p">
                                    <a bind-slide=1 id="accMemLinkTop" href="/inbox/2/1" class="dispbl white f12">
                                        <i id="acc_mem" style="margin: 0px;" class="hamSprite allAcIcon posrel">
                                            ~if $profileMemcacheObj->get('ACC_ME_NEW')`<div class="posabs newham_pos1"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">~if $profileMemcacheObj->get('ACC_ME_NEW')>99` 99+~else`~$profileMemcacheObj->get('ACC_ME_NEW')`~/if`</div></div></div>~/if`
                                        </i>
                                        <div>All<br>Acceptances</div>
                                    </a>
                                </div>
                                <div class="dispibl txtc wid32p">
                                    <a bind-slide=1  id="justJoinedLinkTop" href="/search/perform?justJoinedMatches=1" class="dispbl white f12">
                                        <i id="just_join" style="margin: 0px;" class="hamSprite justJnIcon  posrel">
                                            ~if $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')` <div class="posabs newham_pos1"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">~if $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')>99` 99+~else`~$profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW')`~/if`</div></div></div>~/if` 
                                        </i>
                                        <div>Just Joined<br>Matches</div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite homeIcon"></i>
                            <a bind-slide=1  id="homeLink1" class="f17 white" href="/">Home</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite searchIcon"></i>
                            <a bind-slide=1  id="searchLink" class="white" href="/search/topSearchBand?isMobile=Y">Search</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite searchProfileIcon"></i>
                            <a bind-slide=1  id="searchProfileIdLink" href="/search/searchByProfileId" class="white">Search by Profile ID</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite savedSearchIcon"></i>
                            <a bind-slide=1  href="/search/MobSaveSearch" id="savedSearchLink" class="f17 white">Saved Searches</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite editProfileIcon"></i>
                            <a bind-slide=1  href="/profile/viewprofile.php?ownview=1" id="editProfileLink" class="f17 white">Edit Profile</a>
                        </li>
                        <li class='mb12'>
                            <div id="myMatchesParent" >
                                <i class="hamSprite myMatchesIcon"></i>
                                <div class=" ml25 f17 white dispibl">My Matches</div>
                                <i id="expandMyMatches" class="hamSprite plusIcon fr"></i>
                            </div>
                            <ul id="myMatchesMinor"  style="margin-top: 12px" class="minorList dispnone f15" >
                                <li class='mb12'>
                                    <a bind-slide=1  id="dppLink" href="/search/perform?partnermatches=1" class="newS white">Desired Partner Matches</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="mutualMatchesLink" href="/search/perform?twowaymatch=1" class="newS white">Mutual Matches</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="memLookingLink" href="/search/perform?reverseDpp=1" class="newS white">Members Looking For Me</a>
                                </li>
                                ~if $showKundliList eq '1'`
                                <li class='mb12'>
                                    <a bind-slide=1  id="kundliLink" href="/search/perform?kundlialerts=1" class="newS white">Kundli Matches</a>
                                </li>
                                ~/if`
                                <li class='mb12'>
                                    <a bind-slide=1  id="verifiedLink" href="/search/verifiedMatches" class="newS white">Matches Verified By Visit</a>
                                </li>
                                <li>
                                    <a bind-slide=1  id="dailyRec" href="/inbox/7/1" class="newS white">Daily Recommendations
                                        <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('MATCHALERT_TOTAL')` ~if $profileMemcacheObj->get('MATCHALERT_TOTAL')>99` 99+~else`~$profileMemcacheObj->get('MATCHALERT_TOTAL')`~/if`~/if`</span>
                                </a>
                                </li>
                            </ul>
                        </li>
                        <li class='mb12'>
                            <div id="contactsParent"><i class="hamSprite myContactIcon"></i>
                                <div id="myContactLink" class="f17 ml25 white ml15 dispibl">My Contacts</div><i id="expandContacts" class="hamSprite plusIcon fr"></i></div>
                            <ul id="contactsMinor" style="margin-top: 12px" class="minorList dispnone f15" >
                                <li class='mb12'>
                                    <a bind-slide=1  id="intRecLink" href="/inbox/1/1" class="newS white">
                                        Interests Received
                                        <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')` ~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')>99` 99+~else`~$profileMemcacheObj->get('AWAITING_RESPONSE_NEW')`~/if`~/if`</span></a>
                                </li>
                                <li class='mb12'><a bind-slide=1  id="intSentLink" href="/inbox/6/1" class="newS white">Interests Sent</a></li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="filtIntLink" href="/inbox/12/1" class="newS white">
                                        Filtered Interests
                                        <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('FILTERED')` ~if $profileMemcacheObj->get('FILTERED')>99` 99+~else`~$profileMemcacheObj->get('FILTERED')`~/if`~/if`</span>
                                    </a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="allAccLink" href="/inbox/2/1" class="newS white">
                                        All Acceptances
                                        <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME')`~if $profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME')>99` 99+~else`~$profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME')`~/if`~/if`</span>
                                    </a>
                                </li>
                                <li class='mb12'><a bind-slide=1  id="declinedLink" href="/inbox/11/1" class="newS white">Declined Members</a></li>
                                <li class='mb12'><a bind-slide=1  id="blockedLink" href="/inbox/20/1" class="newS white">Blocked/Ignored Members</a></li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="messagesLink" href="/inbox/4/1" class="newS white">
                                        Messages
                                    </a>
                                </li>
                                <li><a bind-slide=1  id="messagesLink" href="/inbox/17/1" class="newS white">Who Viewed My Contacts</a></li>
                            </ul>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite shortlistedIcon"></i>
                            <a bind-slide=1  href="/search/shortlisted" id="shortlistedLink" class="f17 white">Shortlisted
                            <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('BOOKMARK')` ~if $profileMemcacheObj->get('BOOKMARK')>99` 99+~else`~$profileMemcacheObj->get('BOOKMARK')`~/if`~/if`
                            </span>
                            </a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite phoneIcon"></i>
                            <a bind-slide=1  href="/inbox/16/1" id="phoneLink" class="f17 white">Phonebook</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite profileVisitorIcon"></i>
                            <a bind-slide=1  href="/search/visitors?matchedOrAll=A" id="profileVisitorLink" class="f17 white">Profile Visitors
                                    <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('VISITORS_ALL')` ~if $profileMemcacheObj->get('VISITORS_ALL')>99` 99+~else`~$profileMemcacheObj->get('VISITORS_ALL')`~/if`~/if`</span>
                            </a>
                        </li>

                        <li class='mb12'>
                            <div id="settingsParent">
                                <i class="hamSprite settingsIcon"></i>
                                <div id="settingsLink" class="ml25 dispibl white">Settings</div>
                                <i id="expandSettings" class="hamSprite plusIcon fr"></i>
                            </div>
                            <ul id="settingsMinor" style="margin-top: 12px" class="minorList dispnone f15">
                                <li class='mb12'>
                                    <a bind-slide=1  id="recommendationLink" href="/profile/viewprofile.php?ownview=1#Dpp" class="newS white">Recommendation Settings</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="privacySettingLink" href="/static/privacySettings" class="newS white">Privacy Settings</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="changePassLink" href="/static/changePass" class="newS white">Change Password</a>
                                </li>
                                <li class='mb12'>   
                                    <a bind-slide=1  id="hideProfileLink" href="/static/hideOption" class="newS white">~if LoggedInProfile::getInstance()->getACTIVATED() eq 'H'`Unhide Profile~else`Hide Profile~/if`</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="deleteProfileLink" href="/static/deleteOption" class="newS white">Delete Profile</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="helpLink" href="/help/index" class="newS white">Help</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="contactUsLink" href="/contactus/index" class="newS white">Contact Us</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="privacyPolicyLink" href="/static/page/privacypolicy" class="newS white">Privacy Policy</a>
                                </li>
                                <li class='mb12'>
                                    <a bind-slide=1  id="termsLink" href="/static/page/disclaimer" class="newS white">Terms of use</a>
                                </li>
                                <li class='mb12'><a bind-slide=1  id="fraudLink" href="/static/page/fraudalert" class="newS white">Fraud Alert</a>
                                </li>
                                <li class='mb12'>
                                    <div id="logoutLink" style="margin-left: 35px;" class="newS white ml10">Logout</div>
                                </li>
                                <li>
                                    <a bind-slide=1  id="switchLink" href="/?desktop=Y" class="newS white">Switch to Desktop Site</a>
                                </li>

                            </ul>
                        </li>

                    </ul>
                    ~if $MembershipMessage.bottom`
                    <div id="bottomTab" class="mar0Imp posabs btmo fullwid">
                        ~if $MembershipMessage.top`
                        <div class="brdrTop pad150">
                            <div class="txtc color9 mb15">~$MembershipMessage.top`</div>
                        </div>
                        ~/if`
                        <a bind-slide=1  href="/profile/mem_comparison.php" id="membershipLink" class="hamBtn f17 white bg7 mt15 fullwid lh50">~$MembershipMessage.bottom|upper`</a>

                    </div>
                    ~/if`

<!--  Code moved Up to include Download App at top -->
    
        <!--end:edit profile-->
      
       ~else`
   <ul id="scrollElem" style="-webkit-overflow-scrolling: touch;" class="fontreg white listingHam overAutoHidden"> 

                        <li class="f13 pb8 fontlig">
                            <div id="appDownloadLink1" class="wid49p dispnone"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger" target="_blank" class="white fl mar0Imp">Download  App | 3MB only </a></div>
                            <div class="wid49p dispnone" id="appleAppDownloadLink1"><a bind-slide=1   href="/static/appredirect?type=jsmsHamburger&channel=iosLayer" target="_blank" class="white fl mar0Imp">Download iOS App </a></div>
                            <div class="wid49p dispibl">
                                <div id="hindiLink" onclick="translateSite('http://js.mox.net.in');" class="white fr mar0Imp">हिंदी में</div>
                            </div>
                        </li>
                        <li>
                            <i class="hamSprite homeIcon mt10Imp"></i>
                            <a id="homeLink1" class="f17 white" href="/">Home</a>
                        </li>
                        <li>
                            <i class="hamSprite searchIcon"></i>
                            <a id="searchLink" class="white" href="/search/topSearchBand?isMobile=Y">Search</a>
                        </li>
                        <li>
                            <i class="hamSprite searchProfileIcon"></i>
                            <a id="searchProfileIdLink" href="/search/searchByProfileId" class="white">Search by Profile ID</a>
                        </li>
                        <li>
                            <i class="hamSprite editProfileIcon"></i>
                            <a href="/browse-matrimony-profiles-by-community-jeevansathi" id="borwseCommLink" class="f17 white">Browse By Community</a>
                        </li>

                        <li>
                            <div id="settingsParent">
                                <i class="hamSprite settingsIcon"></i>
                                <div id="settingsLink" class="ml15 dispibl white">Settings & Assistance</div>
                                <i id="expandSettings" class="hamSprite plusIcon fr"></i>
                            </div>
                            <ul id="settingsMinor" style="margin-top: 12px;" class="dispnone minorList f15">
                                <li><a id="switchLink" href="/?desktop=Y" class="white">Switch to Desktop Site</a></li>
                                <li><a id="contactUsLink" href="/contactus/index" class="white">Contact Us</a></li>
                                <li><a id="privacyPolicyLink" href="/static/page/privacypolicy" class="white">Privacy Policy</a></li>
                                <li><a id="termsLink" href="/static/page/disclaimer" class="white">Terms of use</a></li>
                                <li><a id="fraudLink" href="/static/page/fraudalert" class="white">Fraud Alert</a></li>
                            </ul>
                        </li>
        </ul>
        <!--end:login-->
        ~/if`
         </div>
            
        <!--start:edit profile-->
    </div>

  </div>
</div>
    <div id="hamView" class="fullwid darkView fullheight hamView dn"></div>
