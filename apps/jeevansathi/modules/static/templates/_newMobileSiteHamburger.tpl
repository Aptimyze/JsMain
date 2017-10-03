
                     <!--start:top header hamburger-->

       ~if $loggedIn`
       ~assign var=showKundliList value= $sf_request->getParameter('showKundliList')`
    <div id="hamburger" style="width:85%" class="white posfix fullheight"> 

  <div id="outerHamDiv"  >
      
          <div id="mainHamDiv" >
    

      
      <div id="newHamlist" class="hamlist hampad1" > 

                    <ul id="scrollElem" style="-webkit-overflow-scrolling: touch;" class="fontreg white listingHam overAutoHidden fontHam"> 
      
      

                        <li class="f13 pb8 fontlig">
                            <div id="appDownloadLink1" class="dispnone" style="margin-left:14px"><a  href="/static/appredirect?type=androidMobFooter" target="_blank" class="white fl">Download  App | 3MB only </a></div>
                            <div class="dispnone" id="appleAppDownloadLink1"><a style="margin-left:14px"  href="/static/appredirect?type=iosMobFooter" target="_blank" class="white fl">Download iOS App </a></div>
                            <div class="dispibl mr10 fr">
                                <div id="hindiLink" onclick="translateSite('http://hindi.jeevansathi.com');" class="white  mar0Imp">हिंदी में</div>
                            </div>
                        </li>
                        <div style="height: 1px;padding: 0px 20px;"><div style="background-color: white;height: 1px;opacity: .5;"></div></div>                        
                            <li>
                            <div class="fullwid">
                                <div class="dispibl txtc wid32p">
                                    <a bind-slide=1  id="awaitingResponseLinkTop" href="/inbox/1/1" class="dispbl white f12">
                                        <i id="int_rec" style="margin: 0px;" class="hamSprite irIcon posrel">
                                            ~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')` 
                                            <div class="posabs newham_pos1"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">~if $profileMemcacheObj->get('AWAITING_RESPONSE_NEW')>99` 99+~else`~$profileMemcacheObj->get('AWAITING_RESPONSE_NEW')`~/if`</div></div></div>
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
                            <a bind-slide=1  id="homeLink1" class=" white" href="/">Home</a>
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
                            <a bind-slide=1  href="/search/MobSaveSearch" id="savedSearchLink" class=" white">Saved Searches
                                <span class="f12 album_color1 ml15">~if $savedSearchCount`~$savedSearchCount`~/if`</span>
                            </a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite editProfileIcon"></i>
                            <a bind-slide=1  href="/profile/viewprofile.php?ownview=1" id="editProfileLink" class=" white">Edit Profile</a>
                        </li>
                        <li>
                            <div id="myMatchesParent" >
                                <i class="hamSprite myMatchesIcon"></i>
                                <div class=" ml10  white dispibl">My Matches</div>
                                <i id="expandMyMatches" class="hamSprite plusIcon fr"></i>
                            </div>
                            <ul id="myMatchesMinor"  style="height:0px" class="minorList f15" >
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
                        <li class='mt12'>
                            <div id="contactsParent"><i class="hamSprite myContactIcon"></i>
                                <div id="myContactLink" class=" ml10 white ml15 dispibl">My Contacts</div><i id="expandContacts" class="hamSprite plusIcon fr"></i></div>
                            <ul id="contactsMinor" style="height: 0px" class="minorList  f15" >
                                <li class='mb12'>
                                    <a bind-slide=1  id="intRecLink" href="/inbox/1/1" class="newS white">
                                        Interests Received
                                        <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('AWAITING_RESPONSE')` ~if $profileMemcacheObj->get('AWAITING_RESPONSE')>99` 99+~else`~$profileMemcacheObj->get('AWAITING_RESPONSE')`~/if`~/if`</span></a>
                                </li>
                                <li class='mb12'><a bind-slide=1  id="intSentLink" href="/inbox/6/1" class="newS white">Interests Sent
                                    <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('NOT_REP')` ~if $profileMemcacheObj->get('NOT_REP')>99` 99+~else`~$profileMemcacheObj->get('NOT_REP')`~/if`~/if`</span>
                                </a></li>
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
                                    <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('MESSAGE_NEW')` ~if $profileMemcacheObj->get('MESSAGE_NEW')>99` 99+~else`~$profileMemcacheObj->get('MESSAGE_NEW')`~/if`~/if`
                                    </span>
                                    </a>
                                </li>
                                <li><a bind-slide=1  id="messagesLink" href="/inbox/17/1" class="newS white">Who Viewed My Contacts</a></li>
                            </ul>
                        </li>
                        <li class='mt12 mb12'>
                            <i class="hamSprite shortlistedIcon"></i>
                            <a bind-slide=1  href="/search/shortlisted" id="shortlistedLink" class=" white">Shortlisted
                            <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('BOOKMARK')` ~if $profileMemcacheObj->get('BOOKMARK')>99` 99+~else`~$profileMemcacheObj->get('BOOKMARK')`~/if`~/if`
                            </span>
                            </a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite phoneIcon"></i>
                            <a bind-slide=1  href="/inbox/16/1" id="phoneLink" class=" white">Phonebook</a>
                        </li>
                        <li class='mb12'>
                            <i class="hamSprite profileVisitorIcon"></i>
                            <a bind-slide=1  href="/search/visitors?matchedOrAll=A" id="profileVisitorLink" class=" white">Profile Visitors
                                    <span class="f12 album_color1 ml15">~if $profileMemcacheObj->get('VISITORS_ALL')` ~if $profileMemcacheObj->get('VISITORS_ALL')>99` 99+~else`~$profileMemcacheObj->get('VISITORS_ALL')`~/if`~/if`</span>
                            </a>
                        </li>

                        <li class='mb12'>
                            <div id="settingsParent">
                                <i class="hamSprite settingsIcon"></i>
                                <div id="settingsLink" class="ml10 dispibl white">Settings & Assistance</div>
                                <i id="expandSettings" class="hamSprite plusIcon fr"></i>
                            </div>
                            <ul id="settingsMinor" style="height: 0px" class="minorList f15">
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
                                ~if LoggedInProfile::getInstance()->getACTIVATED() eq 'H'`
                                    <a bind-slide=1  id="hideProfileLink" href="/static/unHideOption" class="newS white">Unhide Profile</a>

                                ~else`
                                    <a bind-slide=1  id="hideProfileLink" href="/static/hideOption" class="newS white">Hide Profile</a>

                                ~/if`   
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
                                    <div bind-slide=1 href="/P/logout.php" id="logoutLink" style="margin-left: 30px;" class="newS white ml10">Logout</div>
                                </li>
                                <li>
                                    <a bind-slide=1  id="switchLink" href="/?desktop=Y" class="newS white">Switch to Desktop Site</a>
                                </li>

                            </ul>
                        </li>

                    </ul>
                    ~if $MembershipMessage.bottom`
                    ~if $MembershipMessage.top eq 'FLASH DEAL'`
                    <div id="bottomTab" class="mar0Imp posabs btmo fullwid">
                        <div class="brdrTop pad150">
                            <div class="txtc color9 mb15">~$MembershipMessage.bottom`</div>
                        </div>
                        <a bind-slide=1  href="/profile/mem_comparison.php" id="membershipLink" class="hamBtn  white bg7 mt15 fullwid lhHam">~$MembershipMessage.top|upper` | UPGRADE NOW</a>

                    </div>
                    ~else`
                    <div id="bottomTab" class="mar0Imp posabs btmo fullwid">
                        ~if $MembershipMessage.top`
                        <div class="brdrTop pad150">
                            <div class="txtc color9 mb15">~$MembershipMessage.top`</div>
                        </div>
                        ~/if`
                        <a bind-slide=1  href="/profile/mem_comparison.php" id="membershipLink" class="hamBtn  white bg7 mt15 fullwid lhHam">~$MembershipMessage.bottom|upper`</a>

                    </div>
                    ~/if`
                    ~/if`
         </div>
            
        <!--start:edit profile-->
    </div>

  </div>
</div>
    <div id="hamView" style="opacity:0.5;" class="fullwid darkView fullheight hamView dn"></div>

<!--  Code moved Up to include Download App at top -->
    
        <!--end:edit profile-->
      
       ~else`
<div id="hamburger" class="white posfix wid90p fullheight">
    <div id="outerHamDiv">
        <div id="mainHamDiv">
            <div id="newHamlist" class="hamlist hampad1">
                <ul id="scrollElem" style="-webkit-overflow-scrolling: touch;margin-left:0px ;padding:0px" class="fontreg white listingHam overAutoHidden fontHam">
                    <li style="padding: 10px 20px;" class="f13 pb8 fontlig">
                        <div id="appDownloadLink1" class="dispnone"><a href="/static/appredirect?type=androidMobFooter" target="_blank" class="white fl mar0Imp">Download  App | 3MB only </a>
                        </div>
                        <div class="dispnone" id="appleAppDownloadLink1"><a href="/static/appredirect?type=iosMobFooter" target="_blank" class="white fl mar0Imp">Download iOS App </a>
                        </div>
                        <div class="fr dispibl">
                            <div id="hindiLink" onclick="translateSite('http://hindi.jeevansathi.com');" class="white mar0Imp">Hindi Version</div>
                        </div>
                    </li>
                    <div style="height: 1px;padding: 0px 20px;">
                        <div style="background-color: white;height: 1px;opacity: .5;"></div>
                    </div>
                    <li class="mb12"><i class="hamSprite homeIcon mt10Imp"></i><a id="homeLink1" class="white" href="/">Home</a>
                    </li>
                    <li class="mb12"><i class="hamSprite searchIcon"></i><a id="searchLink" class="white" href="/search/topSearchBand?isMobile=Y">Search</a>
                    </li>
                    <li class="mb12"><i class="hamSprite searchProfileIcon"></i><a id="searchProfileIdLink" href="/search/searchByProfileId" class="white">Search by Profile ID</a>
                    </li>
                    <li class="mb12"><i class="hamSprite editProfileIcon"></i><a href="/browse-matrimony-profiles-by-community-jeevansathi" id="borwseCommLink" class="white">Browse By Community</a>
                    </li>
                    <li class="mb12">
                        <div id="settingsParent"><i class="hamSprite settingsIcon"></i>
                            <div id="settingsLink" class="ml10 dispibl white">Settings & Assistance</div><i id="expandSettings" class="hamSprite plusIcon fr"></i>
                        </div>
                        <ul id="settingsMinor" class="minorList f15" style="margin-top: 12px;padding-left:40px;height:0px;">
                            <li class="mb12 "><a id="contactUsLink" href="/contactus/index" class="newS white">Contact Us</a>
                            </li>
                            <li class="mb12"><a id="privacyPolicyLink" href="/static/page/privacypolicy" class="newS white">Privacy Policy</a>
                            </li>
                            <li class="mb12"><a id="termsLink" href="/static/page/disclaimer" class="newS white">Terms of use</a>
                            </li>
                            <li class="mb12"><a id="fraudLink" href="/static/page/fraudalert" class="newS white">Fraud Alert</a>
                            </li>
                            <li class="mb12"><a id="switchLink" href="/?desktop=Y" class="newS white">Switch to Desktop Site</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div id="bottomTab" class="mar0Imp posabs btm0 fullwid">
                    <div class="brdrTop pad150 fontreg">
                        <div class="dispibl wid49p pad16"><a id="homeLink2" class="hamBtnLoggedOut bg10 lh40 br6 white" href="/">LOGIN</a>
                        </div>
                        <div class="dispibl wid49p pad16"><a class="bg7 br6 lh40 white hamBtnLoggedOut" href="/register/page1?source=mobreg4">REGISTER</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="hamView" class="fullwid darkView fullheight hamView dn"></div>        ~/if`
