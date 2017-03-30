~assign var=zedoValue value= $sf_request->getAttribute('zedo')` ~assign var=zedo value= $zedoValue["zedo"]`
<header>
    <script>
        var iPCS = ~$iPCS`;
        var showFTU=~$showFtu`;
        var showExpiring=~$showExpiring`;
        var showMatchOfTheDay=~$showMatchOfTheDay`;
        var profileid = '~$profileid`';
        var profilePic = '~$profilePic`';
        var PageSource = "MyjsPc";
        var currentPanelArray = {};
        var scheduleRequestSent = 0;
        var newEngagementArray = {};
        newEngagementArray["DAILY_MATCHES_NEW"] = '~$engagementCount.DAILY_MATCHES_NEW`';
        newEngagementArray["NEW_MATCHES"] = '~$engagementCount.NEW_MATCHES`';
        var totalBellCounts = 0;
        var bellCountStatus = 0;
        var membershipPlanExpiry = '~$membershipPlanExpiry`';
        var showHelpScreen = '~$showHelpScreen`';
    </script>
    <input type="hidden" id="CALayerShow" value="~$CALayerShow`"></input>
    <input type="hidden" id="showConsentMsgId" value="~$showConsentMsg`"> ~if $videoLinkLayer neq 'N'`
    <div id="videoLinkDivID" class="fullwid" style="background-color:#fdfdfd">
        <div class="container mainwid pos-rel txtc lh61">

            <div class="f20 fontlig color11">
                Take a quick video tour of the Jeevansathi website <a href="https://www.youtube.com/watch?v=XIrGvlw-PTA" target="blank" class="color5">Watch Now</a>
            </div>
            <a id="videoCloseID" class="pos-abs disp_b" style="width: 26px;height: 26px;right:24px; top:18px; background:url(/images/jspc/myjsImg/videocross.png) no-repeat"></a>
        </div>
    </div>
    ~/if` 
    <div class="cover1">
        <div class="container mainwid pt35"> ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
            <div class="mt56 pos-rel">
            <!-- start: match of the day --> 
                ~if $showMatchOfTheDay eq 1`
                    ~include_partial("global/JSPC/_jspcMatchOfTheDayBar")`
                ~/if`
                <div class="fullwid color-blockfour">
                    <div class="padall-15 clearfix">
                        <div class="fl" style="height:91px;width:91px;">
                            <div style='height:100%;'>
                                <div style="position:relative; float:left;">
                                    <div class="hold hold1">
                                        <div class="pie pie1"></div>
                                    </div>
                                    <div class="hold hold2">
                                        <div class="pie pie2"></div>
                                    </div>
                                    <div class="pie" style=" z-index: 0; border-color: #879381;"> </div>
                                    <a style='color:white;' onclick="trackJsEventGA('My JS JSPC', 'User photo',loggedInJspcGender,'');" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1"><img src="~$photoUrl`" border="0" class="proPicMyJs" /></a>
                                </div>
                            </div>
                            <div id='completePercentId' class="pt3 fontrobbold colrw f17 txtc">~$iPCS`%</div>
                        </div>
                        <div class="fl pt8 pl10 fontlig f13">
                            <div class="clearfix">
                                <div class="f20 fl textTru js-syncChatHeaderName" id="js-usernameAutomation" style="max-width:250px;">~if $nameOfUser`~$nameOfUser`~else`~$loginProfile->getUSERNAME()`~/if`</div>
                                ~if $membershipStatus neq 'Free'`<div class="f17 fl pl10">~$membershipStatus` member</div>~/if`
                            </div>~if $membershipStatus neq 'Free'`
                            <ul class="hor_list pt4 lh20 clearfix">
                                <li class="pr10 myjs-bdr3">Plan Valid till <span class="fontrobbold">~$expirySubscription`</span></li>
                                <li class="pl10">Contacts left to view <span class="fontrobbold">~$contactsRemaining`</span></li>
                            </ul>~/if` ~if $iPCS neq 100`
                            <a style='color:#fff;' onclick="trackJsEventGA('My JS JSPC', 'Add profile info links',loggedInJspcGender,'')" href='/profile/viewprofile.php?ownview=1'>
                                <div id="test" class="pt15 f13">Add details to your profile</div>
                            </a>
                            <ul class="myjsul1 pt8 clearfix opa80 colrw"> ~foreach from=$arrMsgDetails key=KEY item=VALUE`
                                <li><a class='colrw f14' onclick="trackJsEventGA('My JS JSPC', 'Add profile info links',loggedInJspcGender,'')"  href='~$arrLinkDetails.$KEY`'>~$VALUE`</a></li>~/foreach` </ul> ~else`
                            <div id="test" class="pt15 f12">Congratulations!</div>
                            <div id="test" class="pt10 f14">Your profile is 100% complete.</div>~/if` </div>
                        <div class="fr pr20">
                            <a href="/search/matchalerts" onclick="trackJsEventGA('My JS JSPC', 'Match Alerts Bubble',loggedInJspcGender,'')">
                                <div id="dailyMatchesCountBar" class="fl cursp colrw fontlig pt10 disp-none">
                                    <div class="disp-tbl mauto">
                                        <div class="disp-cell myjs-br2 bg_pink vmid txtc imgdim2 f22 pos-rel"> <span id="dailyMatchesCountTotal">~$engagementCount.DAILY_MATCHES`</span> ~if $engagementCount.DAILY_MATCHES_NEW`
                                            <div id="dailyMatchesNewCircle" class="pos-abs fontlig myjs-pos1"> ~else`
                                                <div id="dailyMatchesNewCircle" class="pos-abs fontlig myjs-pos1 disp-none">~/if`
                                                    <div class="disp-tbl txtc myjs-bg1 myjs-dim2 bdr-rad2">
                                                        <div id="dailyMatchesCountNew" class="disp-cell vmid colr5 f12">~$engagementCount.DAILY_MATCHES_NEW`</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="f13 pt18">Daily Recommendations</div>
                                    </div>
                            </a>
                            <a href="/search/justjoined" onclick="trackJsEventGA('My JS JSPC', 'Just Joined Bubble',loggedInJspcGender,'')" >
                                <div id="justJoinedCountBar" class="fl colrw fontlig pt10 pl30 disp-none">
                                    <div class="disp-tbl mauto">
                                        <div class="disp-cell myjs-br2 bg_pink vmid txtc imgdim2 f22 pos-rel"><span id="justJoinedCountTotal">~$engagementCount.JUST_JOINED_MATCHES`</span> ~if $engagementCount.NEW_MATCHES`
                                            <div id="justJoinedNewCircle" class="pos-abs fontlig myjs-pos1">~else`
                                                <div id="justJoinedNewCircle" class="pos-abs fontlig myjs-pos1 disp-none">~/if`
                                                    <div class="disp-tbl txtc myjs-bg1 myjs-dim2 bdr-rad2">
                                                        <div id="justJoinedCountNew" class="disp-cell vmid colr5 f12">~$engagementCount.NEW_MATCHES`</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="f13 pt18">Just Joined</div>
                                    </div>
                            </a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="colrw fontmed pt20 pl30 pb20"> ~if $MembershipMessage['top'] neq ''`
                        <a href='/profile/mem_comparison.php' onclick="trackJsEventGA('My JS JSPC', 'Offer Text',loggedInJspcGender,'')" class='colrw'> <span class="f26">~$MembershipMessage['top']`</span> <span id='memExpiryDiv' style='display:none;'><span class="disp_ib pl5 f15">|</span> <span id='memExpiryHrs' class="disp_ib f15 pl10"></span><small>H</small> <span id='memExpiryMnts' class="disp_ib pl10 f15"></span><small>M</small><span id='memExpirySec' class="disp_ib pl10 f15"></span><small>S</small></span>
                        ~if $MembershipMessage['extra'] &&  $MembershipMessage['extra'] neq ""`
                            <br>
                            <span id="memExtraDiv" class="f16">~$MembershipMessage['extra']`</span>
                        ~/if`
                        </a> ~/if` </div>
                </div>
            </div>
</header>
<div class="bg-4">~if $showFtu eq 1`~include_partial("myjs/jspcMyjs/FTU" ,['profilePic'=>$profilePic,'username'=>$username,'photoUrl'=>$photoUrl,'schedule_visit_widget'=>$schedule_visit_widget,'profileid'=>$profileid,'scheduleVisitCount'=>$scheduleVisitCount,'nameOfUser'=>$nameOfUser,'FTUdata'=>$FTUdata,'computeImportantSection' =>$computeImportantSection])`~else`
    <div id="js-engBarMain" class="mainwid container pb40">
        <div class="myjs-bg2">
            <ul class="hor_list myjstab clearfix myjs-bdr4 tabs">
                <li id="interestEngagementHead">
                    <div class="myjs-bdr3 fullwid">
                        <div class="disp-tbl mauto">
                            <div id="engage_interestReceived" onclick="trackJsEventGA('My JS JSPC', 'Engagement Bar - Interests Received',loggedInJspcGender,'')" class="hgt25 disp-cell vmid myjs-fsize1 pr5">Interests Received</div>
                            <div id="totalInterestReceived" class="myjs-fsize2  vmid myjs-p15 disp-none myjs-fw"></div>
                            <div id="interetReceivedCount" class="scir fontreg txtc vmid myjs-dim4 disp-none"></div>
                        </div>
                    </div>
                </li>
                ~if $showExpiring eq 0`
                <li id="filteredInterestHead">
                    <div class="myjs-bdr3 fullwid">
                        <div class="disp-tbl mauto">
                            <div id="engage_filteredInterestReceived" class="hgt25 disp-cell vmid myjs-fsize1 pr5" onclick="trackJsEventGA('My JS JSPC', 'Engagement Bar - Filtered Interests',loggedInJspcGender,'')" >Filtered Interests</div>
                            <div id="totalFilteredInterestReceived" class="myjs-fsize2  vmid myjs-p15 disp-none myjs-fw"></div>
                            <div id="filteredInterestCount" class="scir fontreg txtc vmid myjs-dim4 disp-none"></div>
                        </div>
                    </div>
                </li>
                ~else`
                <li id="expiringInterestHead">
                    <div class="myjs-bdr3 fullwid">
                        <div class="disp-tbl mauto">
                            <div id="engage_expiringInterestReceived" class="hgt25 disp-cell vmid myjs-fsize1 pr5" onclick="trackJsEventGA('My JS JSPC', 'Engagement Bar - Expiring Interests',loggedInJspcGender,'')" >Interests Expiring
                            <div id="totalExpiringInterestReceived" style="padding-left: 5px;" class="myjs-fsize2 dispib vmid myjs_p_new disp-none myjs-fw"></div>
                            <div style="color:#D9475C;padding-left: 5px;" class="fontreg f14 vmid disp-none" id="ExpiringAction">Take Action</div>
                            </div>
                            <div id="expiringInterestCount" class="scir fontreg txtc vmid myjs-dim4 disp-none"></div>
                        </div>
                    </div>
                </li>
                ~/if`
                <li id="acceptanceEngagementHead">
                    <div class="myjs-bdr3 fullwid">
                        <div class="disp-tbl mauto">
                            <div id="AcceptanceId" class="hgt25 disp-cell vmid myjs-fsize1 pr5" onclick="trackJsEventGA('My JS JSPC', 'Engagement Bar - Acceptances Received',loggedInJspcGender,'')">Accepted Me</div>
                            <div id="totalAcceptsReceived" class="myjs-fsize2 vmid myjs-p15 disp-none myjs-fw"></div>
                            <div id="allAcceptanceCount" class="scir fontreg txtc vmid myjs-dim4 disp-none"></div>
                        </div>
                    </div>
                </li>
                <li id="MsgEngagementHead" class="notactive">
                    <div class="myjs-bdr3 fullwid">
                        <div class="disp-tbl mauto">
                            <div class="hgt25 disp-cell vmid pr5 myjs-fsize1" onclick="trackJsEventGA('My JS JSPC', 'Engagement Bar - Messages',loggedInJspcGender,'')">Messages</div>
                            <div id="totalMessagesReceived" class="myjs-fsize2 vmid myjs-p15 disp-none myjs-fw"></div>
                            <div id="messagesCountNew" class="scir fontreg txtc vmid myjs-dim4 disp-none"></div>
                        </div>
                    </div>
                </li>
            </ul>
            <div id="engagementContainerTop">
                <div id="engagementContainer" class="disp-none"> </div>
            </div>
        </div> 
        <article id="DAILYMATCHES">
            <div class="pt40 clearfix fontlig">
                <div class="fl f22 color11">Daily Recommendations <span class="fontreg colr5"></span></div>
                <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic11 pr15">View All</a> </div>
            </div>
            <div class="pt15">
                <div class="pos-rel">
                    <div class="fullwid scrollhid">
                        <div class="pos-rel li-slide2">
                            <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide2">
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                            </ul>
                        </div>
                    </div><i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide2"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide2"></i> </div>
            </div>
        </article>
        <article id="JUSTJOINED">
            <div class="pt30 clearfix fontlig">
                <div class="fl f22 color11">Just Joined Matches <span class="fontreg colr5"></span></div>
                <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic11 pr15">View All</a> </div>
            </div>
            <div class="pt15">
                <div class="pos-rel">
                    <div class="fullwid scrollhid">
                        <div class="pos-rel li-slide3">
                            <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide3">
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                            </ul>
                        </div>
                    </div><i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide3"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide3"></i> </div>
            </div>
        </article>
         <article id="LASTSEARCH">
            <div class="pt40 clearfix fontlig">
                <div class="fl f22 color11"> <span class="fontreg colr5"></span></div>
                <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic1 pr15">See All</a> </div>
            </div>
            <div class="pt15">
                <div class="pos-rel">
                    <div class="fullwid scrollhid">
                        <div class="pos-rel li-slide2">
                            <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide2">
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                            </ul>
                        </div>
                    </div><i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide2"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide2"></i> </div>
            </div>
        </article>
         <article id="DESIREDPARTNERMATCHES" class="disp-none">
            <div class="pt40 clearfix fontlig">
                <div class="fl f22 color11">Here are few matches for you <span class="fontreg colr5"></span></div>
                <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic1 pr15">See All</a> </div>
            </div>
            <div class="pt15">
                <div class="pos-rel">
                    <div class="fullwid scrollhid">
                        <div class="pos-rel li-slide2">
                            <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide2">
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                            </ul>
                        </div>
                    </div><i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide2"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide2"></i> </div>
            </div>
        </article>
      ~if $loadLevel >= 3`
        <article id="VERIFIEDMATCHES_HIDE" class="disp-none">
      ~else`
        <article id="VERIFIEDMATCHES">
      ~/if`
        
            <div class="pt30 clearfix fontlig">
                <div class="fl f22 color11">Verified Matches <span class="fontreg colr5"></span></div>
                <div class="fr f16 pt8"><a href="#" class="color12 icons myjs-ic11 pr15">View All</a> </div>
            </div>
            <div class="pt15">
                <div class="pos-rel">
                    <div class="fullwid scrollhid">
                        <div class="pos-rel li-slide3">
                            <ul class="hor_list clearfix myjslist boxslide pos-rel" id="js-slide3">
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                                <li>
                                    <div class="bg-white" style="width:220px; height:320px; overflow:hidden"> </div>
                                </li>
                            </ul>
                        </div>
                    </div><i class="pos-abs sprite2 myjs-ic2 myjs-pos3 scntrl cursp" id="prv-slide3"></i> <i class="pos-abs sprite2 myjs-ic3 myjs-pos4 scntrl cursp" id="nxt-slide3"></i> </div>
            </div>
        </article>
        <div class="clearfix pt45">
            <div id="VISITORS" class="myjs-wid11 fl">
                <p class="fontlig f22 color11">Profile Visitors </p>
                <ul class="hor_list clearfix mysj-btmwid pt30 pl20">
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Recent Profile Visitors - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Recent Profile Visitors - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Recent Profile Visitors - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Recent Profile Visitors - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Recent Profile Visitors - +x',loggedInJspcGender,'')"></li>
                </ul>
            </div>
          ~if $loadLevel >=3`
            <div id="SHORTLIST_HIDE" class="myjs-wid11 fr disp-none">
          ~else`
            <div id="SHORTLIST" class="myjs-wid11 fr">
          ~/if`
            
                <p class="fontlig f22 color11">Shortlisted Profiles</p>
                <ul class="hor_list clearfix mysj-btmwid pt30 pl20">
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Shortlisted Profiles - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Shortlisted Profiles - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Shortlisted Profiles - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Shortlisted Profiles - Photo',loggedInJspcGender,'')"></li>
                    <li class="myjs-bg3" style="width:72px; height:72px; border-radius:50%" onclick="trackJsEventGA('My JS JSPC', 'Shortlisted Profiles - +x',loggedInJspcGender,'')"></li>
                </ul>
            </div>
        </div>
        ~if $schedule_visit_widget eq 1 && $scheduleVisitCount eq 0`
        <div class="mt40 myjs-bg3 fullwid">
            <div id="schedule_visit" class="myjs-p9 clearfix"> <i class="fl sprite2 myjs-ic4"></i>
                <div class="fl pl20 fontlig color11">
                    <p class="f18">Get Verified</p>
                    <p class="f15 pt5">In-person verification of profiles by jeevansathi team. <a href="/static/agentinfo" class="colr5">Know more about it</a></p>
                </div>
               <div class='pos-rel scrollhid'> <div class="fr bg_pink fontreg colrw lh46 myjs-wid21 txtc cursp" id="schedule_visit_action" onclick="scheduleVisit();trackJsEventGA('My JS JSPC', 'Request a Visit',loggedInJspcGender,''); return false;">Request a Visit</div>
            </div> 
        </div> 
        ~/if`
        </div>
        <div class="mainwid container pb30">
            <!--enable notifications layer start-->
            ~include_partial("common/notificationLayerJSPC",[showEnableNotificationsLayer=>$showEnableNotificationsLayer])`
            <!--enable notifications layer end-->
        </div>
        
        <div class="txtc bg-4 pt20 pb20" id="zt_~$zedo['masterTag']`_bottom"> </div>~/if`
        <div class="mainwid container pb30">
            <div class="mt40 clearfix">
                <div class="fl wid33p_1">
                    <div class="myjs-wid3">
                        <div class="colr2 fontrobbold f20 myjs-bdr6 pb5 allcaps">Whats New</div>
                        <ul class="feature">
                            <li class="fontlig">All new best-in-class user interface</li>
                            <li class="fontlig">Profiles verified by Jeevansathi</li>
                            <li class="fontlig">Search got even more personalised</li>
                        </ul>
                    </div>
                </div>
                <div class="fl wid33p_1 ml50">
                    <div class="myjs-wid3">
                        <div class="colr2 fontrobbold f20 myjs-bdr6 pb5 allcaps">Success Stories</div>
                        <ul class="feature">
                            <li class="fontlig">Thousands of people have found</li>
                            <li class="fontlig">their soulmate on Jeevansathi</li>
                            <li class="fontlig"><a href="/successStory/story" class="colr5">Know more</a></li>
                        </ul>
                    </div>
                </div>
                <div class="fr">
                    <div class="myjs-wid3">
                        <div class="colr2 fontrobbold f20 myjs-bdr6 pb5 allcaps">We are secure</div>
                        <ul class="feature">
                            <li class="fontlig">Multiple methods for payment</li>
                            <li class="fontlig">Safe & secure payment gateway</li>
                            <li class="fontlig">We do not save your card details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>~include_partial("myjs/jspcMyjs/Container", ['profilePic'=>$profilePic])`~include_partial("myjs/jspcMyjs/_CardsSection", ['staticCardArr'=>$staticCardArr,'gender'=>$gender,'otherthumbnail'=>$otherthumbnail,'otherPhotoUrl'=>$otherPhotoUrl])`~include_partial("myjs/jspcMyjs/_helpScreens")`~include_partial('global/JSPC/_jspcAppPromo')` ~include_partial('global/JSPC/_jspcCommonFooter')`
<script type="text/javascript">
    function scheduleVisit() {
        if (!scheduleRequestSent) {
            $.post("/membership/scheduleVisit", {
                'profileid': profileid
            }, function(response) {
                $('#schedule_visit_action').html("Request Sent").removeClass('bg_pink cursp').addClass('myjs-bg9').unbind('click');
            });
        }
        scheduleRequestSent = 1;
    }
</script>
