~assign var=module value= $sf_request->getParameter('module')`
~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=loginData value= $sf_request->getAttribute('loginData')`
~if $loginData`
    ~assign var=username value= $loginData.USERNAME`
    ~assign var=subscription value= $loginData.SUBSCRIPTION`
~/if`
~assign var=currency value= $sf_request->getAttribute('currency')`
~assign var=action value= $sf_request->getParameter('action')`
~assign var=profileid value= $sf_request->getAttribute('profileid')`
~assign var=profilechecksum value= $sf_request->getAttribute('profilechecksum')`
~assign var=showKundliList value=0`
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
~assign var=zedoProfileDetail value= $zedoValue["custom"]`
~if ($profileid eq '8298074' || $profileid eq '13038359' || $profileid eq '12970375')`
    ~assign var=stickyTopNavBar value= ''`
~/if`

<!--r_num is variable number whose value is fetched from Auth filter in case of JSPC and assigned to script to be accessed in commonExpiration_js.js-->
~assign var=r_num value=$sf_request->getParameter('revisionNumber')`
<script type="text/javascript">
var r_n_u_m = ~$r_num`;
//This fucntion returns the revision number and is called in commonExpiration_js.js
function getR_N_U_M(){
    return(r_n_u_m);
}
</script>
~if $zedo`
<script type="text/javascript">
 var initialPos=0;
 var zmt_mtag;
 var masterTag = ~$zedo['masterTag']`;
function loadScript(src, callback)
{
  var s,
      r,
      t;
  r = false;
  s = document.createElement('script');
  s.type = 'text/javascript';
  s.src = src;
  s.onload = s.onreadystatechange = function() {
    if ( !r && (!this.readyState || this.readyState == 'complete') )
    {
      r = true;
      callback();
    }
  };
  t = document.getElementsByTagName('script')[0];
  t.parentNode.insertBefore(s, t);
}

function renderBanners()
{
        
    zmt_mtag = zmt_get_tag(2466,"~$zedo['masterTag']`");
    ~foreach from=$zedo['tag'] item=foo key=mykey`
    if($("#zd_async_frame_zt_~$zedo['masterTag']`_~$mykey`").length)
        $("#zd_async_frame_zt_~$zedo['masterTag']`_~$mykey`").remove();
    if($("#zt_~$zedo['masterTag']`_~$mykey`").length)
    {    
        p~$zedo['masterTag']`_~$mykey` = zmt_mtag.zmt_get_placement("zt_~$zedo['masterTag']`_~$mykey`", "~$zedo['masterTag']`", "~$foo.id`" , "~$foo.source`" , "~$foo.size`" , "~$foo.network`", "~$foo.width`","~$foo.height`");
        p~$zedo['masterTag']`_~$mykey`.zmt_add_ct("~$zedoProfileDetail`");
    }
        ~/foreach`
        zmt_mtag.zmt_set_async();
        zmt_mtag.zmt_load(zmt_mtag);

    ~foreach from=$zedo['tag'] item=foo key=mykey`
    if($("#zt_~$zedo['masterTag']`_~$mykey`").length)
    {
        var newScript = document.createElement('script');
        newScript.id="zt_~$zedo['masterTag']`_~$mykey`";
        newScript.text="zmt_mtag.zmt_render_placement(p~$zedo['masterTag']`_~$mykey`);";
        document.getElementById("zt_~$zedo['masterTag']`_~$mykey`").appendChild(newScript);
    }
    ~/foreach`

}

var prev_handler = window.onload;
window.onload=function(){
    
    if (prev_handler) {
        prev_handler();
    }
    setTimeout(function(){loadScript('https://saxp.zedo.com/sclient/tt3/fmos.js',renderBanners);},100);
    var zedoLoad = 1;
}
</script>
~/if`
~if $loggedIn`
~assign var=showKundliList value= $sf_request->getParameter('showKundliList')`

<!--start:top nav-->
<div id="topNavigationBar" class="~if $stickyTopNavBar`stickyTopNavBar~else`pos_rel~/if` mainwid js-topnav navBarZ pos_rel">
    <div class="fullwid clearfix">
        <!--start:logo-->
        <div id="jeevansathiLogo" class="fl newLogoWidth logop1 hpwhite txtc disp-tbl">
            <p class="lgo" itemtype="http://schema.org/Organization" itemscope="">
            <a class="disp-cell vmid pl10" href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`" itemprop="url"> <img class="brdr-0 vmid" alt="Indian Matrimonials - We Match Better" src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/logo1.png" itemprop="logo"> </a>
            </p>
        </div>
        <!--end:logo-->
        <!--start:middle-->
        <div class="fr topnavbg mnav-wid1 colrw f14 hgt63">
            <div class="fontreg f14">
                <div class=" pl27 clearfix">
                    <!--start:left-->
                    <ul class="topnavbar listnone fontlig f14 fl pt23">
                        <li tabindex="1"><a id="homepageLink" href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`">HOME</a></li>
                        <li tabindex="1" class="ml13"> <a class="drop" href="/search/partnermatches">MATCHES</a>
                            <ul class="menushadowGNB">
                                <li><a class="disp_b js-gnbsearchLists cursp" data="partnermatches">Desired Partner Matches</a></li>
                                <li><a class="disp_b js-gnbsearchLists cursp" data="matchalerts"> Daily Recommendations</a></li>
                                <li><a class="disp_b js-gnbsearchLists cursp" data="justjoined">Just Joined Matches</a></li>
                                <li><a class="disp_b js-gnbsearchLists cursp" data="verifiedMatches">Verified Matches</a></li>
                                <li><a class="disp_b js-gnbsearchLists cursp" data="twoway">Mutual Matches</a></li>
                                <li><a class="disp_b js-gnbsearchLists cursp" data="reverseDpp">Members Looking for Me</a></li>
                                ~if $showKundliList eq '1'`
                                <li><a class="disp_b js-gnbsearchLists cursp" data="kundlialerts">Kundli Matches<div class="fr"><div class="bg_pink mr15 mt10"><div style="line-height:10px;" class="colrw disp_b padall-6">New</div></div></div></a></li>
                                ~/if`
                                <li><a class="disp_b" href="/search/shortlisted">Shortlisted Profiles</a></li>
                                <li><a class="disp_b" href="/search/visitors?matchedOrAll=A">Profile Visitors</a></li>
                                <li style="display:none"><a href="/common/realSearch"></a></li>
                                <!--
                                ~if CommonFunction::getMainMembership($subscription) eq mainMem::EVALUE || CommonFunction::getMainMembership($subscription) eq mainMem::EADVANTAGE`
                                ~else`
                                <li><a class="disp_b" href="/search/contactViewAttempts">Contact View Attempts<div class="fr"><div class="bg_pink mr15 mt10"><div style="line-height:10px;" class="colrw disp_b padall-6">New</div></div></div></a></li>
                                ~/if`
                                -->
                            </ul>
                        </li>
                        <li tabindex="1" class="ml37"> <a class="drop" href="/inbox/1/1">INBOX</a>
                            <ul class="menushadowGNB">
                                <li><a class="disp_b" href="/inbox/1/1"> Interests</a></li>
                                <li><a class="disp_b" href="/inbox/3/1">Acceptances</a></li>
                                <li><a class="disp_b" href="/inbox/4/1">Messages</a></li>
                                <li><a class="disp_b" href="/inbox/9/1">Requests</a></li>
                                <li><a class="disp_b" href="/inbox/11/1">Declined / Blocked members</a></li>
                                <li><a class="disp_b" href="/inbox/17/1">Viewed Contacts</a></li>
                            </ul>
                        </li>
                        <li id='js-searchTab' tabindex="1" class="ml37"><a class="drop cursp" href="/search/AdvancedSearch">SEARCH</a>
                            <ul class="menushadowGNB">
                                <li><a class="disp_b cursp" href="/search/AdvancedSearch"> Search</a></li>
                                <li><a class="disp_b cursp"  href="/search/savedSearches">My Saved Searches</a></li>
                                <li><a class="disp_b cursp js-srchbyid">Search by Profile ID</a></li>
                            </ul>
                        </li>
                        <li id="upgrade" tabindex="1" class="ml37"><a class="disp_b cursp" onclick="javascript:logOutCheck('/profile/mem_comparison.php',1); return true;">UPGRADE</a></li>
                        <li id="help" tabindex="1" class="ml27"><a class="disp_b cursp" onclick="javascript:logOutCheck('/help/index',1); return true;">HELP</a></li>
                    </ul>
                    <!--end:left-->
                    <!--start:right-->
                    <ul class="fr hor_list righttopnav pt9 fontlig">
                        <li id="viewBellCountHeader" class="pos-rel mt10 toplihg1 bellnot tnavwid2 mr30"> <i class="cursp sprite2 bellicon"></i>
                            <!--start:number-->
                            <div class="pos-abs tpos2 z2">
                                <div id="totalBellCountParent" class="bg_pink f12 fontthin colrw disp-tbl noti1 txtc" style="display:none">
                                    <div id="totalBellCount" class="disp-cell vmid f11" >0</div>
                                </div>
                            </div>
                            <!--end:number-->
                            <!--start:submenu-->
                            <ul class="submenu topnavbg pos-abs navbell menushadowGNB navBarZ">
                                <li>
                                    <a href="/search/justjoined">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Just Joined Matches</div>
                                        <div class="fr">
                                            <div id="justJoinedCountParent" class="disp-tbl  txtc" style="display:none">
                                                <div id="justJoinedCount" class="disp-cell vmid colrw bg_pink f12 fontlig tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/inbox/4/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Messages</div>
                                        <div class="fr">
                                            <div id="messagesCountParent" class="disp-tbl  txtc" style="display:none">
                                                <div id="messagesCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/inbox/9/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Photo Requests</div>
                                        <div class="fr">
                                            <div id="photoRequestsCountParent" class="disp-tbl countBell txtc" style="display:none">
                                                <div id="photoRequestsCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/inbox/1/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Interests Received</div>
                                        <div class="fr">
                                            <div id="interestsReceivedCountParent" class="disp-tbl  txtc" style="display:none">
                                                <div id="interestsReceivedCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/inbox/2/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Accepted Me</div>
                                        <div class="fr">
                                            <div id="membersAcceptedMeCountParent" class="disp-tbl countBell txtc" style="display:none">
                                                <div id="membersAcceptedMeCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>

                                     <li>
                                    <a href="/inbox/10/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl"> Declined/Cancelled</div>
                                        <div class="fr">
                                            <div id="membersDeclinedMeCountParent" class="disp-tbl txtc" style="display:none">
                                                <div id="DeclinedMeCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>


                                <li>
                                    <a href="/search/matchalerts">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Daily Recommendations</div>
                                        <div class="fr">
                                            <div id="membersDailyMatchesCountParent" class="disp-tbl  txtc" style="display:none">
                                                <div id="membersDailyMatchesCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </li>
				                <li>
                                    <a href="/inbox/12/1">
                                    <div class="clearfix topnavp1">
                                        <div class="fl">Filtered Interests</div>
                                        <div class="fr">
                                            <div id="membersFilteredInterestCountParent" class="disp-tbl  txtc" style="display:none">
                                                <div id="FilteredInterstsCount" class="disp-cell vmid colrw f12 fontlig bg_pink tdim2 count">0</div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    </a>
                                </li>

                           

                            </ul>
                            <!--end:submenu-->
                        </li>
                        <li class="toplihg2 mr30 pos-rel">
                            <a href="/profile/viewprofile.php?ownview=1" alt=""><img id='js-profilePicIcon' class="cursp" src="~PictureFunctions::getHeaderThumbnailPicUrl()`" style="height: 46px; width: 46px;border-radius: 23px;"></a>
                            <!-- <img class="topimgdim1 topimgdim1" src="~sfConfig::get('app_site_url')`/images/jspc//searchImg/srch_image1.jpg"> -->
                            <!--start:submenu-->
                            <ul id="gnbPhotoMenu" class="topnavbg pos-abs submenu fontlig menushadowGNB navBarZ">
                                <li><div class="topnavp1"><a class="disp_b" href="/profile/viewprofile.php?checksum=~$profilechecksum`&profilechecksum=~$profilechecksum`">My Profile ~if $username`(~$username`)~/if`</a></div></li>
                                <li><div class="topnavp1"><a class="disp_b" href="/profile/dpp"> Desired Partner Profile</a></div></li>
                                <li><div class="topnavp1"><a class="disp_b" href="/settings/alertManager"> Alert Manager</a></div></li>
                                <li><div class="topnavp1"><a class="disp_b" href="/settings/jspcSettings?visibility=1`">Profile Visibility</a></div></li>
                                <li><div class="topnavp1"><a class="disp_b" href="/settings/jspcSettings?hideDelete=1">Hide/Delete profile</a></div></li>
                                <li><div class="topnavp1"><a class="disp_b" href="/settings/jspcSettings?changePassword=1">Change Password</a></div></li>
                                <li>
                                    <div class="clearfix topnavp1">
                                        <div class="fl pt5"><a class="disp_b" href="/profile/mem_comparison.php">You are~if CommonFunction::getMembershipName($profileid) neq 'Free'` a Paid~else` a Free~/if` Member  </a></div>
                                        ~if CommonFunction::getMembershipName($profileid) neq 'Free'`
                                        ~else`
                                        <div class="fr">
                                            <div class="bg_pink navp2"><a class="colrw disp_b" href="/profile/mem_comparison.php">Upgrade</a></div>
                                        </div>
                                        ~/if`
                                    </div>
                                </li>
                                <li><div class="topnavp1 txtc"><a class="disp_b cursp" onclick="javascript:logOutCheck('/static/logoutPage?fromSignout=1'); return true;" id="jspcChatout">Sign out</a></div></li>
                            </ul>
                            <!--end:submenu-->
                        </li>
                    </ul>
                    <!--end:right-->
                </div>
            </div>
        </div>
        <!--end:middle-->
    </div>
</div>
<!--end:top nav-->
~else`
<!--start:top nav-->
<div id="topNavigationBar" class="~if $stickyTopNavBar`stickyTopNavBar~else`pos_rel~/if` mainwid z2 js-topnav navBarZ pos_rel">
    <div class="fullwid clearfix">
        <!--start:logo-->
        <div id="jeevansathiLogo" class="fl newLogoWidthLoggedOut logop1 hpwhite txtc disp-tbl">
            <p class="lgo" itemtype="http://schema.org/Organization" itemscope="">
            <a class="disp-cell vmid pl10" href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`" itemprop="url"> <img class="brdr-0 vmid" alt="Indian Matrimonials - We Match Better" src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/logo1.png" itemprop="logo"> </a>
            </p>
        </div>
        <!--end:logo-->
        <!--start:middle-->
        <div class="fl topnavbg mnav-wid2 colrw f14 hgt63 fontreg">
            <div class="fontreg f14">
                <div class="pl27 clearfix">
                    <!--start:left-->
                    <ul class="topnavbar listnone fontlig f14 fl pt23">
                        <li tabindex="1" id="browseprof"><a class="drop" href="#">BROWSE PROFILES BY</a>
                            <!--start:hover box-->
                            <div class="TabsContent coloropa1 menushadowGNB" id="BrowseTab_content">
                                <div class="InneerTabContent">
                                    <div class="TabsMenu fl coloropa2 fontreg">
                                        <a class="sub_h" id="mtongue" href="#">Mother tongue</a>
                                        <a class="sub_h" id="caste" href="#">Caste</a>
                                        <a class="sub_h" id="religion" href="#">Religion</a>
                                        <a class="sub_h" id="city" href="#">City</a>
                                        <a class="sub_h" id="occupation" href="#">Occupation</a>
                                        <a class="sub_h" id="state" href="#">State</a>
                                        <a class="sub_h" id="nri" href="#">NRI</a>
                                    <a class="sub_h" id="scases" href="#">Special Cases</a> </div>
                                    <div class="BrowseContent fl">
                                        <figure style="display: block;" class="mtongue_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="Hindi Delhi Matrimony" href="/matrimonials/hindi-matrimonial/"> Hindi-Delhi </a></li>
                                                            <li><a title="Marathi Matrimony" href="/matrimonials/marathi-matrimonial/"> Marathi </a></li>
                                                            <li><a title="Hindi UP Matrimony" href="/hindi-up-matrimony-matrimonials"> Hindi-UP </a></li>
                                                            <li><a title="Punjabi Matrimony" href="/matrimonials/punjabi-matrimonial/"> Punjabi </a></li>
                                                            <li><a title="Telugu Matrimony" href="/matrimonials/telugu-matrimonial/"> Telugu </a></li>
                                                            <li><a title="Bengali Matrimony" href="/matrimonials/bengali-matrimonial/"> Bengali </a></li>
                                                            <li><a title="Tamil Matrimony" href="/matrimonials/tamil-matrimonial/"> Tamil </a></li>
                                                            <li><a title="Gujarati Matrimony" href="/matrimonials/gujarati-matrimonial/"> Gujarati </a></li>
                                                            <li><a title="Malayalam Matrimony" href="/matrimonials/malayalee-matrimonial/"> Malayalam </a></li>
                                                            <li><a title="Kannada Matrimony" href="/matrimonials/kannada-matrimonial/"> Kannada </a></li>
                                                            <li><a title="Hindi MP Matrimony" href="/hindi-mp-matrimony-matrimonials"> Hindi-MP </a></li>
                                                            <li><a title="Bihari Matrimony" href="/matrimonials/bihari-matrimonial/"> Bihari </a></li>
                                                            <li><a href="/matrimonials/rajasthani-matrimonial/" title="Rajasthani Matrimony">Rajasthani</a></li>
                                                            <li><a href="/matrimonials/oriya-matrimonial/" title="Oriya Matrimony">Oriya</a></li>
                                                            <li><a href="/konkani-matrimony-matrimonials" title="Konkani Matrimony">Konkani</a></li>
                                                            <li><a href="/himachali-matrimony-matrimonials" title="Himachali Matrimony">Himachali</a></li>
                                                            <li><a href="/haryanvi-matrimony-matrimonials" title="Haryanvi Matrimony">Haryanvi</a></li>
                                                            <li><a href="/matrimonials/assamese-matrimonial/" title="Assamese Matrimony">Assamese</a></li>
                                                            <li><a href="/kashmiri-matrimony-matrimonials" title="Kashmiri Matrimony">Kashmiri</a></li>
                                                            <li><a href="/sikkim-nepali-matrimony-matrimonials" title="Sikkim Nepali Matrimony">Sikkim/Nepali</a></li>
                                                            <li><a href="/matrimonials/hindi-matrimonial/" title="Hindi Matrimony">Hindi</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="caste_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="Brahmin Matrimony" href="/matrimonials/brahmin-matrimonial/"> Brahmin </a></li>
                                                            <li><a title="Sunni Matrimony" href="/matrimonials/sunni-matrimonial/"> Sunni </a></li>
                                                            <li><a title="Kayastha Matrimony" href="/matrimonials/kayastha-matrimonial/"> Kayastha </a></li>
                                                            <li><a title="Rajput Matrimony" href="/matrimonials/rajput-matrimonial/"> Rajput </a></li>
                                                            <li><a title="Maratha Matrimony" href="/maratha-matrimony-matrimonials"> Maratha </a></li>
                                                            <li><a title="Khatri Matrimony" href="/matrimonials/khatri-matrimonial/"> Khatri </a></li>
                                                            <li><a title="Aggarwal Matrimony" href="/matrimonials/agarwal-matrimonial/"> Aggarwal </a></li>
                                                            <li><a title="Arora Matrimony" href="/matrimonials/arora-matrimonials/"> Arora </a></li>
                                                            <li><a title="Kshatriya Matrimony" href="/matrimonials/kshatriya-matrimonial/"> Kshatriya </a></li>
                                                            <li><a title="Shwetamber Matrimony" href="/matrimonials/shwetamber-matrimonial/"> Shwetamber </a></li>
                                                            <li><a title="Yadav Matrimony" href="/matrimonials/yadav-matrimonial/"> Yadav </a></li>
                                                            <li><a title="Sindhi Matrimony" href="/matrimonials/sindhi-matrimonial/"> Sindhi </a></li>
                                                            <li><a title="Bania Matrimony" href="/matrimonials/bania-matrimonial/"> Bania </a></li>
                                                            <li><a title="Scheduled Caste Matrimony" href="/matrimonials/scheduled-caste-matrimonial/">Scheduled Caste</a></li>
                                                            <li><a title="Nair Matrimony" href="/matrimonials/nair-matrimonial/">Nair</a></li>
                                                            <li><a title="Lingayat Matrimony" href="/matrimonials/lingayat-matrimonial/">Lingayat</a></li>
                                                            <li><a title="Jat Matrimony" href="/matrimonials/jat-matrimonial/">Jat</a></li>
                                                            <li><a title="Catholic   Roman Matrimony" href="/roman-catholic-matrimony-matrimonials">Catholic - Roman</a></li>
                                                            <li><a title="Patel Matrimony" href="/matrimonials/patel-matrimonial/">Patel</a></li>
                                                            <li><a title="Digamber Matrimony" href="/matrimonials/digamber-matrimonial/">Digamber</a></li>
                                                            <li><a title="Sikh Jat Matrimony" href="/sikh-jat-matrimony-matrimonials">Sikh-Jat</a></li>
                                                            <li><a title="Gupta Matrimony" href="/matrimonials/gupta-matrimonial/">Gupta</a></li>
                                                            <li><a title="Catholic Matrimony" href="/matrimonials/catholic-matrimonial/">Catholic</a></li>
                                                            <li><a title="Teli Matrimony" href="/matrimonials/teli-matrimonial/">Teli</a></li>
                                                            <li><a title="Vishwakarma Matrimony" href="/matrimonials/vishwakarma-matrimonial/">Vishwakarma</a></li>
                                                            <li><a title="Brahmin Iyer Matrimony" href="/matrimonials/iyer-matrimonial/">Brahmin Iyer</a></li>
                                                            <li><a title="Vaishnav Matrimony" href="/matrimonials/vaishnav-matrimonial/">Vaishnav</a></li>
                                                            <li><a title="Jaiswal Matrimony" href="/matrimonials/jaiswal-matrimonial/">Jaiswal</a></li>
                                                            <li><a title="Gujjar Matrimony" href="/matrimonials/gujjar-matrimonial/">Gujjar</a></li>
                                                            <li><a title="Syrian Matrimony" href="/matrimonials/syrian-matrimonial/">Syrian</a></li>
                                                            <li><a title="Adi Dravida Matrimony" href="/matrimonials/adi-dravida-matrimonial/">Adi Dravida</a></li>
                                                            <li><a title="Arya Vysya Matrimony" href="/arya-vysya-matrimony-matrimonials">Arya Vysya</a></li>
                                                            <li><a title="Balija Naidu Matrimony" href="/matrimonials/balija-naidu-matrimonial/">Balija Naidu</a></li>
                                                            <li><a title="Bhandari Matrimony" href="/matrimonials/bhandari-matrimonial/">Bhandari</a></li>
                                                            <li><a title="Billava Matrimony" href="/matrimonials/billava-matrimonial/">Billava</a></li>
                                                            <li><a title="Anavil Matrimony" href="/matrimonials/anavil-brahmin-matrimonial/">Anavil</a></li>
                                                            <li><a title="Goswami Matrimony" href="/matrimonials/goswami-matrimonial/">Goswami</a></li>
                                                            <li><a title="Brahmin Havyaka Matrimony" href="/matrimonials/havyaka-brahmin-matrimonial/">Brahmin Havyaka</a></li>
                                                            <li><a title="Kumaoni Matrimony" href="/matrimonials/kumaoni-matrimonial/">Kumaoni</a></li>
                                                            <li><a title="Madhwa Matrimony" href="/matrimonials/madhwa-matrimonial/">Madhwa</a></li>
                                                            <li><a title="Nagar Matrimony" href="/matrimonials/nagar-matrimonial/">Nagar</a></li>
                                                            <li><a title="Smartha Matrimony" href="/matrimonials/smartha-matrimonial/">Smartha</a></li>
                                                            <li><a title="Vaidiki Matrimony" href="/matrimonials/vaidiki-matrimonial/">Vaidiki</a></li>
                                                            <li><a title="Viswa Matrimony" href="/matrimonials/viswa-brahmin-matrimonial/">Viswa</a></li>
                                                            <li><a title="Bunt Matrimony" href="/matrimonials/bunt-matrimonial/">Bunt</a></li>
                                                            <li><a title="Chambhar Matrimony" href="/matrimonials/chambhar-matrimonial/">Chambhar</a></li>
                                                            <li><a title="Chaurasia Matrimony" href="/matrimonials/chaurasia-matrimonial/">Chaurasia</a></li>
                                                            <li><a title="Chettiar Matrimony" href="/matrimonials/chettiar-matrimonial/">Chettiar</a></li>
                                                            <li><a title="Devanga Matrimony" href="/matrimonials/devanga-matrimonial/">Devanga</a></li>
                                                            <li><a title="Dhangar Matrimony" href="/matrimonials/dhangar-matrimonial/">Dhangar</a></li>
                                                            <li><a title="Ezhavas Matrimony" href="/matrimonials/ezhava-matrimonial/">Ezhavas</a></li>
                                                            <li><a title="Goud Matrimony" href="/matrimonials/goud-matrimonial/">Goud</a></li>
                                                            <li><a title="Gowda Matrimony" href="/matrimonials/gowda-matrimonial/">Gowda</a></li>
                                                            <li><a title=" Brahmin Iyengar Matrimony" href="/matrimonials/iyengar-matrimonial/"> Brahmin Iyengar</a></li>
                                                            <li><a title="Marwari Matrimony" href="/matrimonials/marwari-matrimonial/">Marwari</a></li>
                                                            <li><a title="Jatav Matrimony" href="/matrimonials/jatav-matrimonial/">Jatav</a></li>
                                                            <li><a title="Kamma Matrimony" href="/matrimonials/kamma-matrimonial/">Kamma</a></li>
                                                            <li><a title="Kapu Matrimony" href="/matrimonials/kapu-matrimonial/">Kapu</a></li>
                                                            <li><a title="Khandayat Matrimony" href="/matrimonials/khandayat-matrimonial/">Khandayat</a></li>
                                                            <li><a title="Koli Matrimony" href="/matrimonials/koli-matrimonial/">Koli</a></li>
                                                            <li><a title="Koshti Matrimony" href="/matrimonials/koshti-matrimonial/">Koshti</a></li>
                                                            <li><a title="Kunbi Matrimony" href="/matrimonials/kunbi-matrimonial/">Kunbi</a></li>
                                                            <li><a title="Kuruba Matrimony" href="/matrimonials/kuruba-matrimonial/">Kuruba</a></li>
                                                            <li><a title="Kushwaha Matrimony" href="/matrimonials/kushwaha-matrimonial/">Kushwaha</a></li>
                                                            <li><a title="Leva Patidar Matrimony" href="/matrimonials/leva-patidar-matrimonial/">Leva Patidar</a></li>
                                                            <li><a title="Lohana Matrimony" href="/matrimonials/lohana-matrimonial/">Lohana</a></li>
                                                            <li><a title="Maheshwari Matrimony" href="/matrimonials/maheshwari-matrimonial/">Maheshwari</a></li>
                                                            <li><a title="Mahisya Matrimony" href="/matrimonials/mahisya-matrimonial/">Mahisya</a></li>
                                                            <li><a title="Mali Matrimony" href="/matrimonials/mali-matrimonial/">Mali</a></li>
                                                            <li><a title="Maurya Matrimony" href="/matrimonials/maurya-matrimonial/">Maurya</a></li>
                                                            <li><a title="Menon Matrimony" href="/matrimonials/menon-matrimonial/">Menon</a></li>
                                                            <li><a title="Mudaliar Matrimony" href="/matrimonials/mudaliar-matrimonial/">Mudaliar</a></li>
                                                            <li><a title="Mudaliar Arcot Matrimony" href="/matrimonials/mudaliar-arcot-matrimonial/">Mudaliar Arcot</a></li>
                                                            <li><a title="Mogaveera Matrimony" href="/matrimonials/mogaveera-matrimonial/">Mogaveera</a></li>
                                                            <li><a title="Nadar Matrimony" href="/matrimonials/nadar-matrimonial/">Nadar</a></li>
                                                            <li><a title="Naidu Matrimony" href="/matrimonials/naidu-matrimonial/">Naidu</a></li>
                                                            <li><a title="Nambiar Matrimony" href="/matrimonials/nambiar-matrimonial/">Nambiar</a></li>
                                                            <li><a title="Nepali Matrimony" href="/matrimonials/nepali-matrimonial/">Nepali</a></li>
                                                            <li><a title="Padmashali Matrimony" href="/matrimonials/padmashali-matrimonial/">Padmashali</a></li>
                                                            <li><a title="Patil Matrimony" href="/matrimonials/patil-matrimonial/">Patil</a></li>
                                                            <li><a title="Pillai Matrimony" href="/matrimonials/pillai-matrimonial/">Pillai</a></li>
                                                            <li><a title="Prajapati Matrimony" href="/matrimonials/prajapati-matrimonial/">Prajapati</a></li>
                                                            <li><a title="Reddy Matrimony" href="/matrimonials/reddy-matrimonial/">Reddy</a></li>
                                                            <li><a title="Sadgope Matrimony" href="/matrimonials/sadgope-matrimonial/">Sadgope</a></li>
                                                            <li><a title="Shimpi Matrimony" href="/matrimonials/shimpi-matrimonial/">Shimpi</a></li>
                                                            <li><a title="Somvanshi Matrimony" href="/matrimonials/somvanshi-matrimonial/">Somvanshi</a></li>
                                                            <li><a title="Sonar Matrimony" href="/matrimonials/sonar-matrimonial/">Sonar</a></li>
                                                            <li><a title="Sutar Matrimony" href="/matrimonials/sutar-matrimonial/">Sutar</a></li>
                                                            <li><a title="Swarnkar Matrimony" href="/matrimonials/swarnkar-matrimonial/">Swarnkar</a></li>
                                                            <li><a title="Thevar Matrimony" href="/matrimonials/thevar-matrimonial/">Thevar</a></li>
                                                            <li><a title="Thiyya Matrimony" href="/matrimonials/thiyya-matrimonial/">Thiyya</a></li>
                                                            <li><a title="Vaish Matrimony" href="/matrimonials/vaish-matrimonial/">Vaish</a></li>
                                                            <li><a title="Vaishya Matrimony" href="/matrimonials/vaishya-matrimonial/">Vaishya</a></li>
                                                            <li><a title="Vanniyar Matrimony" href="/matrimonials/vanniyar-matrimonial/">Vanniyar</a></li>
                                                            <li><a title="Varshney Matrimony" href="/matrimonials/varshney-matrimonial/">Varshney</a></li>
                                                            <li><a title="Veerashaiva Matrimony" href="/matrimonials/veerashaiva-matrimonial/">Veerashaiva</a></li>
                                                            <li><a title="Vellalar Matrimony" href="/matrimonials/vellalar-matrimonial/">Vellalar</a></li>
                                                            <li><a title="Vysya Matrimony" href="/matrimonials/vysya-matrimonial/">Vysya</a></li>
                                                            <li><a title="Gursikh Matrimony" href="/matrimonials/gursikh-matrimonial/">Gursikh</a></li>
                                                            <li><a title="Ramgarhia Matrimony" href="/matrimonials/ramgarhia-matrimonial/">Ramgarhia</a></li>
                                                            <li><a title="Saini Matrimony" href="/matrimonials/saini-matrimonial/">Saini</a></li>
                                                            <li><a title="Mallah Matrimony" href="/matrimonials/mallah-matrimonial/">Mallah</a></li>
                                                            <li><a title="Shah Matrimony" href="/matrimonials/shah-matrimonial/">Shah</a></li>
                                                            <li><a title="Dhobi Matrimony" href="/matrimonials/dhobi-matrimonial/">Dhobi</a></li>
                                                            <li><a title=" Kalar Matrimony" href="/matrimonials/kalar-matrimonial/">-Kalar</a></li>
                                                            <li><a title="Kamboj Matrimony" href="/matrimonials/kamboj-matrimonial/">Kamboj</a></li>
                                                            <li><a title="Kashmiri Pandit Matrimony" href="/matrimonials/kashmiri-pandit-matrimonial/">Kashmiri Pandit</a></li>
                                                            <li><a title="Rigvedi Matrimony" href="/matrimonials/rigvedi-matrimonial/">Rigvedi</a></li>
                                                            <li><a title="Vokkaliga Matrimony" href="/matrimonials/vokaliga-matrimonial/">Vokkaliga</a></li>
                                                            <li><a title="Bhavasar Kshatriya Matrimony" href="/matrimonials/bhavsar-matrimonial/">Bhavasar Kshatriya</a></li>
                                                            <li><a title="Agnikula  Matrimony" href="/matrimonials/agnikula-matrimony-matrimonials">Agnikula </a></li>
                                                            <li><a title="Audichya  Matrimony" href="/audichya-matrimony-matrimonials">Audichya </a></li>
                                                            <li><a title="Baidya  Matrimony" href="/baidya-matrimony-matrimonials">Baidya </a></li>
                                                            <li><a title="Baishya  Matrimony" href="/baishya-matrimony-matrimonials">Baishya </a></li>
                                                            <li><a title="Bhumihar  Matrimony" href="/bhumihar-matrimony-matrimonials">Bhumihar </a></li>
                                                            <li><a title="Bohra  Matrimony" href="/bohra-matrimony-matrimonials">Bohra </a></li>
                                                            <li><a title="Chamar  Matrimony" href="/chamar-matrimony-matrimonials">Chamar </a></li>
                                                            <li><a title="Chasa  Matrimony" href="/chasa-matrimony-matrimonials">Chasa </a></li>
                                                            <li><a title="Chaudhary  Matrimony" href="/chaudhary-matrimony-matrimonials">Chaudhary </a></li>
                                                            <li><a title="Chhetri  Matrimony" href="/chhetri-matrimony-matrimonials">Chhetri </a></li>
                                                            <li><a title="Dhiman  Matrimony" href="/dhiman-matrimony-matrimonials">Dhiman </a></li>
                                                            <li><a title="Garhwali  Matrimony" href="/garhwali-matrimony-matrimonials">Garhwali </a></li>
                                                            <li><a title="Gudia  Matrimony" href="/gudia-matrimony-matrimonials">Gudia </a></li>
                                                            <li><a title="Havyaka  Matrimony" href="/havyaka-matrimony-matrimonials">Havyaka </a></li>
                                                            <li><a title="Kammavar  Matrimony" href="/kammavar-matrimony-matrimonials">Kammavar </a></li>
                                                            <li><a title="Karana  Matrimony" href="/karana-matrimony-matrimonials">Karana </a></li>
                                                            <li><a title="Khandelwal  Matrimony" href="/khandelwal-matrimony-matrimonials">Khandelwal </a></li>
                                                            <li><a title="Knanaya  Matrimony" href="/knanaya-matrimony-matrimonials">Knanaya </a></li>
                                                            <li><a title="Kumbhar  Matrimony" href="/kumbhar-matrimony-matrimonials">Kumbhar </a></li>
                                                            <li><a title="Mahajan  Matrimony" href="/mahajan-matrimony-matrimonials">Mahajan </a></li>
                                                            <li><a title="Mukkulathor  Matrimony" href="/mukkulathor-matrimony-matrimonials">Mukkulathor </a></li>
                                                            <li><a title="Pareek  Matrimony" href="/pareek-matrimony-matrimonials">Pareek </a></li>
                                                            <li><a title="Sourashtra  Matrimony" href="/sourashtra-matrimony-matrimonials">Sourashtra </a></li>
                                                            <li><a title="Tanti  Matrimony" href="/tanti-matrimony-matrimonials">Tanti </a></li>
                                                            <li><a title="Thakur  Matrimony" href="/thakur-matrimony-matrimonials">Thakur </a></li>
                                                            <li><a title="Vanjari  Matrimony" href="/vanjari-matrimony-matrimonials">Vanjari </a></li>
                                                            <li><a title="Vokkaliga  Matrimony" href="/vokkaliga-matrimony-matrimonials">Vokkaliga </a></li>
                                                            <li><a title="Daivadnya  Matrimony" href="/daivadnya-matrimony-matrimonials">Daivadnya </a></li>
                                                            <li><a title="Kashyap  Matrimony" href="/kashyap-matrimony-matrimonials">Kashyap </a></li>
                                                            <li><a title="Kutchi  Matrimony" href="/kutchi-matrimony-matrimonials">Kutchi </a></li>
                                                            <li><a title="OBC Matrimony" href="/matrimonials/obc-matrimonial/">OBC</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="religion_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="Hindu Matrimony" href="/matrimonials/hindu-matrimonial/"> Hindu </a></li>
                                                            <li><a title="Muslim Matrimony" href="/matrimonials/muslim-matrimonial/"> Muslim </a></li>
                                                            <li><a title="Christian Matrimony" href="/matrimonials/christian-matrimonial/"> Christian </a></li>
                                                            <li><a title="Sikh Matrimony" href="/matrimonials/sikh-matrimonial/"> Sikh </a></li>
                                                            <li><a title="Jain Matrimony" href="/matrimonials/jain-matrimonial/"> Jain </a></li>
                                                            <li><a title="Buddhist Matrimony" href="/matrimonials/buddhist-matrimonial/"> Buddhist </a></li>
                                                            <li><a title="Parsi Matrimony" href="/matrimonials/parsi-matrimonial/"> Parsi </a></li>
                                                            <li><a title="Jewish Matrimony" href="/matrimonials/jewish-matrimonial/"> Jewish </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="city_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="New Delhi Matrimony" href="/matrimonials/delhi-matrimonials/"> New Delhi </a></li>
                                                            <li><a title="Mumbai Matrimony" href="/matrimonials/mumbai-matrimonial/"> Mumbai </a></li>
                                                            <li><a title="Bangalore Matrimony" href="/matrimonials/bangalore-matrimonial/"> Bangalore </a></li>
                                                            <li><a title="Pune Matrimony" href="/matrimonials/pune-matrimonial/"> Pune </a></li>
                                                            <li><a title="Hyderabad Matrimony" href="/matrimonials/hyderabad-matrimonial/"> Hyderabad </a></li>
                                                            <li><a title="Kolkata Matrimony" href="/matrimonials/kolkata-matrimonial/"> Kolkata </a></li>
                                                            <li><a title="Chennai Matrimony" href="/matrimonials/chennai-matrimonial/"> Chennai </a></li>
                                                            <li><a title="Lucknow Matrimony" href="/lucknow-matrimony-matrimonials"> Lucknow </a></li>
                                                            <li><a title="Ahmedabad Matrimony" href="/matrimonials/ahmedabad-matrimonial/"> Ahmedabad </a></li>
                                                            <li><a title="Chandigarh Matrimony" href="/matrimonials/chandigarh-matrimonial/"> Chandigarh </a></li>
                                                            <li><a title="Nagpur Matrimony" href="/nagpur-matrimony-matrimonials"> Nagpur </a></li>
                                                            <li><a href="/jaipur-matrimony-matrimonials" title="Jaipur Matrimony">Jaipur</a></li>
                                                            <li><a href="/gurgaon-matrimony-matrimonials" title="Gurgaon Matrimony">Gurgaon</a></li>
                                                            <li><a href="/bhopal-matrimony-matrimonials" title="Bhopal Matrimony">Bhopal</a></li>
                                                            <li><a href="/noida-matrimony-matrimonials" title="Noida Matrimony">Noida</a></li>
                                                            <li><a href="/indore-matrimony-matrimonials" title="Indore Matrimony">Indore</a></li>
                                                            <li><a href="/patna-matrimony-matrimonials" title="Patna Matrimony">Patna</a></li>
                                                            <li><a href="/bhubaneshwar-matrimony-matrimonials" title="Bhubaneshwar Matrimony">Bhubaneshwar</a></li>
                                                            <li><a href="/ghaziabad-matrimony-matrimonials" title="Ghaziabad Matrimony">Ghaziabad</a></li>
                                                            <li><a href="/kanpur-matrimony-matrimonials" title="Kanpur Matrimony">Kanpur</a></li>
                                                            <li><a href="/faridabad-matrimony-matrimonials" title="Faridabad Matrimony">Faridabad</a></li>
                                                            <li><a href="/ludhiana-matrimony-matrimonials" title="Ludhiana Matrimony">Ludhiana</a></li>
                                                            <li><a href="/thane-matrimony-matrimonials" title="Thane Matrimony">Thane</a></li>
                                                            <li><a href="/matrimonials/alabama-matrimonials/" title="Alabama Matrimony">Alabama</a></li>
                                                            <li><a href="/matrimonials/arizona-matrimonials/" title="Arizona Matrimony">Arizona</a></li>
                                                            <li><a href="/matrimonials/arkansas-matrimonials/" title="Arkansas Matrimony">Arkansas</a></li>
                                                            <li><a href="/matrimonials/california-matrimonials/" title="California Matrimony">California</a></li>
                                                            <li><a href="/matrimonials/colorado-matrimonials/" title="Colorado Matrimony">Colorado</a></li>
                                                            <li><a href="/matrimonials/connecticut-matrimonials/" title="Connecticut Matrimony">Connecticut</a></li>
                                                            <li><a href="/matrimonials/delaware-matrimonials/" title="Delaware Matrimony">Delaware</a></li>
                                                            <li><a href="/matrimonials/district-columbia-matrimonials/" title="District Columbia Matrimony">District Columbia</a></li>
                                                            <li><a href="/matrimonials/florida-matrimonials/" title="Florida Matrimony">Florida</a></li>
                                                            <li><a href="/matrimonials/indiana-matrimonials/" title="Indiana Matrimony">Indiana</a></li>
                                                            <li><a href="/matrimonials/iowa-matrimonials/" title="Iowa Matrimony">Iowa</a></li>
                                                            <li><a href="/matrimonials/kansas-matrimonials/" title="Kansas Matrimony">Kansas</a></li>
                                                            <li><a href="/matrimonials/kentucky-matrimonials/" title="Kentucky Matrimony">Kentucky</a></li>
                                                            <li><a href="/matrimonials/massachusetts-matrimonials/" title="Massachusetts Matrimony">Massachusetts</a></li>
                                                            <li><a href="/matrimonials/michigan-matrimonials/" title="Michigan Matrimony">Michigan</a></li>
                                                            <li><a href="/matrimonials/minnesota-matrimonials/" title="Minnesota Matrimony">Minnesota</a></li>
                                                            <li><a href="/matrimonials/mississippi-matrimonials/" title="Mississippi Matrimony">Mississippi</a></li>
                                                            <li><a href="/matrimonials/new-jersey-matrimonials/" title="New Jersey Matrimony">New Jersey</a></li>
                                                            <li><a href="/matrimonials/new-york-matrimonials/" title="New York Matrimony">New York</a></li>
                                                            <li><a href="/matrimonials/north-carolina-matrimonials/" title="North Carolina Matrimony">North Carolina</a></li>
                                                            <li><a href="/matrimonials/north-dakota-matrimonials/" title="North Dakota Matrimony">North Dakota</a></li>
                                                            <li><a href="/matrimonials/ohio-matrimonials/" title="Ohio Matrimony">Ohio</a></li>
                                                            <li><a href="/matrimonials/oklahoma-matrimonials/" title="Oklahoma Matrimony">Oklahoma</a></li>
                                                            <li><a href="/matrimonials/oregon-matrimonials/" title="Oregon Matrimony">Oregon</a></li>
                                                            <li><a href="/matrimonials/pennsylvania-matrimonials/" title="Pennsylvania Matrimony">Pennsylvania</a></li>
                                                            <li><a href="/matrimonials/south-carolina-matrimonials/" title="South Carolina Matrimony">South Carolina</a></li>
                                                            <li><a href="/matrimonials/tennessee-matrimonials/" title="Tennessee Matrimony">Tennessee</a></li>
                                                            <li><a href="/matrimonials/texas-matrimonials/" title="Texas Matrimony">Texas</a></li>
                                                            <li><a href="/matrimonials/virginia-matrimonials/" title="Virginia Matrimony">Virginia</a></li>
                                                            <li><a href="/matrimonials/washington-matrimonials/" title="Washington Matrimony">Washington</a></li>
                                                            <li><a href="/mangalorean-matrimony-matrimonials" title="Mangalorean  Matrimony">Mangalorean </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="occupation_h">
                                            <figcaption>
                                                <div class="contentHeader mCustomScrollbar fullwidth clearfix pl10 fontRobReg">
                                                    <ul class="clearfix hor_list">
                                                        <li><a title="IT Software Matrimony" href="/it-software-engineers-matrimony-matrimonials"> IT Software </a></li>
                                                        <li><a title="Teacher Matrimony" href="/teachers-matrimony-matrimonials"> Teacher </a></li>
                                                        <li><a title="CA Accountant Matrimony" href="/ca-accountant-matrimony-matrimonials"> CA/Accountant </a></li>
                                                        <li><a title="Businessman Matrimony" href="/businessman-matrimony-matrimonials"> Businessman </a></li>
                                                        <li><a title="Doctors Nurse Matrimony" href="/doctors-nurse-matrimony-matrimonials"> Doctors/Nurse </a></li>
                                                        <li><a title="Govt. Services Matrimony" href="/government-services-matrimony-matrimonials"> Govt. Services </a></li>
                                                        <li><a title="Lawyers Matrimony" href="/lawyers-matrimony-matrimonials"> Lawyers </a></li>
                                                        <li><a title="Defence Matrimony" href="/defence-matrimony-matrimonials"> Defence </a></li>
                                                        <li><a title="IAS Matrimony" href="/ias-matrimony-matrimonials"> IAS </a></li>
                                                    </ul>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="state_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="Maharashtra Matrimony" href="/matrimonials/maharashtra-matrimonial/"> Maharashtra </a></li>
                                                            <li><a title="Uttar Pradesh Matrimony" href="/matrimonials/uttar-pradesh-matrimonial/"> Uttar Pradesh </a></li>
                                                            <li><a title="Karnataka Matrimony" href="/matrimonials/karnataka-matrimonial/"> Karnataka </a></li>
                                                            <li><a title="Andhra Pradesh Matrimony" href="/matrimonials/andhra-pradesh-matrimonial/"> Andhra Pradesh </a></li>
                                                            <li><a title="Tamil Nadu Matrimony" href="/matrimonials/tamil-nadu-matrimonial/"> Tamil Nadu </a></li>
                                                            <li><a title="West Bengal Matrimony" href="/matrimonials/west-bengal-matrimonials/"> West Bengal </a></li>
                                                            <li><a title="Madhya Pradesh Matrimony" href="/matrimonials/madhya-pradesh-matrimonial/"> Madhya Pradesh </a></li>
                                                            <li><a title="Gujarat Matrimony" href="/matrimonials/gujarat-matrimonial/"> Gujarat </a></li>
                                                            <li><a title="Haryana Matrimony" href="/matrimonials/haryana-matrimonial/"> Haryana </a></li>
                                                            <li><a href="/bihar-matrimony-matrimonials" title="Bihar Matrimony">Bihar</a></li>
                                                            <li><a href="/matrimonials/kerala-matrimonial/" title="Kerala Matrimony">Kerala</a></li>
                                                            <li><a href="/rajasthan-matrimony-matrimonials" title="Rajasthan Matrimony">Rajasthan</a></li>
                                                            <li><a href="/punjab-matrimony-matrimonials" title="Punjab Matrimony">Punjab</a></li>
                                                            <li><a href="/matrimonials/orissa-matrimonial/" title="Orissa Matrimony">Orissa</a></li>
                                                            <li><a href="/matrimonials/assam-matrimonial/" title="Assam Matrimony">Assam</a></li>
                                                            <li><a href="/matrimonials/jammu-kashmir-matrimonial/" title="Jammu &amp; Kashmir Matrimony">Jammu &amp; Kashmir</a></li>
                                                            <li><a href="/matrimonials/goa-matrimonials/" title="Goa Matrimony">Goa</a></li>
                                                            <li><a href="/matrimonials/himachal-pradesh-matrimonial/" title="Himachal Pradesh Matrimony">Himachal Pradesh</a></li>
                                                            <li><a href="/matrimonials/arunachal-pradesh-matrimonial/" title="Arunachal Pradesh Matrimony">Arunachal Pradesh</a></li>
                                                            <li><a href="/matrimonials/mizoram-matrimonial/" title="Mizoram Matrimony">Mizoram</a></li>
                                                            <li><a href="/matrimonials/pondicherry-matrimonial/" title="Pondicherry Matrimony">Pondicherry</a></li>
                                                            <li><a href="/matrimonials/sikkim-matrimonial/" title="Sikkim Matrimony">Sikkim</a></li>
                                                            <li><a href="/matrimonials/tripura-matrimonial/" title="Tripura Matrimony">Tripura</a></li>
                                                            <li><a href="/matrimonials/jharkhand-matrimony-matrimonials/" title="Jharkhand Matrimony">Jharkhand</a></li>
                                                            <li><a href="/matrimonials/chhattisgarh-matrimony-matrimonials/" title="Chhattisgarh Matrimony">Chhattisgarh</a></li>
                                                            <li><a href="/matrimonials/uttarakhand-matrimony-matrimonials/" title="Uttarakhand Matrimony">Uttarakhand</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="nri_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="India Matrimony" href="/matrimonials/india-matrimonial/"> India </a></li>
                                                            <li><a title="United States Matrimony" href="/matrimonials/usa-matrimonial/"> United States </a></li>
                                                            <li><a title="United Arab Emirates Matrimony" href="/matrimonials/uae-matrimonial/"> United Arab Emirates </a></li>
                                                            <li><a title="United Kingdom Matrimony" href="/matrimonials/uk-matrimonial/"> United Kingdom </a></li>
                                                            <li><a title="Australia Matrimony" href="/australia-matrimony-matrimonials"> Australia </a></li>
                                                            <li><a title="Canada Matrimony" href="/matrimonials/canada-matrimonial/"> Canada </a></li>
                                                            <li><a title="Pakistan Matrimony" href="/matrimonials/pakistan-matrimonial/"> Pakistan </a></li>
                                                            <li><a title="Singapore Matrimony" href="/matrimonials/singapore-matrimonial/"> Singapore </a></li>
                                                            <li><a title="NRI Matrimony" href="/nri-matrimony-matrimonials"> NRI  </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                        <figure style="display: none;" class="scases_h">
                                            <figcaption>
                                                <div class="fullwidth clearfix pl10 fontRobReg">
                                                    <div class="contentHeader mCustomScrollbar" style="height:360px;padding-bottom:25px;">
                                                        <ul class="clearfix hor_list">
                                                            <li><a title="HIV Positive Matrimony" href="/hiv-positive-matrimony-matrimonials"> HIV Positive </a></li>
                                                            <li><a title="Thalassemia Major Matrimony" href="/thalassemia-major-matrimony-matrimonials"> Thalassemia Major </a></li>
                                                            <li><a title="Hearing Impaired Matrimony" href="/deaf-matrimony-matrimonials"> Hearing Impaired </a></li>
                                                            <li><a title="Speech Impaired Matrimony" href="/dumb-matrimony-matrimonials"> Speech Impaired </a></li>
                                                            <li><a title="Visually Impaired Matrimony" href="/blind-matrimony-matrimonials"> Visually Impaired </a></li>
                                                            <li><a title="Handicapped Matrimony" href="/handicapped-matrimony-matrimonials"> Handicapped </a></li>
                                                            <li><a title="Cancer Survivor Matrimony" href="/cancer-survivor-matrimony-matrimonials"> Cancer Survivor </a></li>
                                                            <li><a title="Diabetic Matrimony" href="/diabetic-matrimony-matrimonials"> Diabetic </a></li>
                                                            <li><a title="Leucoderma Matrimony" href="/leucoderma-vitiligo-white-patches-white-spots-matrimony-matrimonials"> Leucoderma </a></li>
                                                            <li><a title="Divorcee Matrimony" href="/divorcee-matrimony-matrimonials"> Divorcee </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <!--end:hover box-->
                        </li>
                        <li tabindex="1" class="ml50"><a class="drop cursp" href="/search/AdvancedSearch">SEARCH</a>
                            <ul class='menushadowGNB'>
                                <li><a class="disp_b cursp" href="/search/AdvancedSearch"> Search</a></li>
                                <li><a class="disp_b cursp js-srchbyid">Search by Profile ID</a></li>
                            </ul>
                        </li>
                        <li id="help" tabindex="1" class="ml50"><a class="disp_b cursp" onclick="javascript:logOutCheck('/help/index',1); return true;">HELP</a></li>
                    </ul>
                    <!--end:left-->
                    <!--start:right-->
                    <ul class="fr listnone clearfix mt20">
                        <li tabindex="1" class="fl pl20 pr24">
                            <div id="loginTopNavBar" class="clearfix cursp">
                                <a class="colrw"><p class="fl pt3 pr12">LOGIN</p></a>
                                <i class="sprite2 loginicon fl"></i>
                            </div>
                        </li>
                    </ul>
                    <!--end:right-->
                </div>
            </div>
        </div>
        <!--end:middle-->
        <!--start:registration-->
        <div tabindex="1" class="fr hpbg1 hpwid3 lh63 txtc disp-tbl"> <a class="disp-cell vmid fontreg f14 colrw" href="/profile/registration_new.php?source=~if $registerSource`~$registerSource`~else`gnb~/if`">REGISTER FREE</a> </div>
        <!--end:registration-->
    </div>
</div>
<!--end:top nav-->
~/if`
<!--search by profile id-->
~include_partial('global/JSPC/_jspcSearchByID')`
