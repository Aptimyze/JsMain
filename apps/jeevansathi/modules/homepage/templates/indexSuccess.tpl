<!--start:header-->
<header>
    <div class="hp-header pos-rel" style="height:642px;">
        <div id="homepage" class="container mainwid pt35">
            
                ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
                <!--start:search-->
                <div id="hpSearchBar" class="hpp10new">
                                    <div class="txtc fontlig colrw f30 pb20"> Love is looking for you. Be found.</div>
                                    ~include_partial("searchBand",["pageSource"=>'homePageJspc'])`
                </div>
                <!--end:search-->
                <!--start:links-->
                <div class="clearfix pt16 pb30">
                    <ul class="hor_list fr f14">
                        <li class="pr10" style="border-right:1px solid #fff"><a class="colrw cursp js-srchbyid fontlig">Search by Profile ID</a></li>
                        <li class="pl10"><a href="/search/AdvancedSearch" class="cursp colrw fontlig">Advanced Search</a></li>
                    </ul>
                </div>
                <!--end:links-->
           
        </div>
    </div>
</header>
<!--end:header-->
<!--start:row 1-->
<article id="hpblk2">
  <div class="bg_pink">
    <div class="container mainwid txtc pt35 pb40 colrw fontlig">
      <div class="homeTag">
        <h1>The one you are perfect for, is waiting for you to log on.</h1>
      </div>
      <div class="clearfix fullwid pt40">
        <div class="fl">
            <iframe width="428" height="240" style="border:15px solid #e47f8d" src="~CommonConstants::AddLink`" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="fr txtl f16">
            <p class="pt30">You believe in soulmates, so do we. </p>
            <p>Connect with your perfect one here, on Jeevansathi.</p>
            <p class="pt20">While you do so, we take utmost care of your Privacy & Security. </p>
            <p>We ensure 100% Screening of profiles, Verified Stamp on those </p>
            <p>we've met in person and Advanced Privacy Settings.</p>
            <div class="pt35">
               <a href="/profile/registration_new.php?source=home" class="colrw"> <div class="wid280 pos-rel scrollhid"><button id="registerButtonHomepage" class="f24 bg5 lh61 colrw brdr-0 wid280 blueRipple hoverBlue cursp">Register Free</button></div></a>
            </div>
        </div>
      </div>
    </div>
  </div>
</article>
<!--end:row 1-->
<!--start:row 2-->
<article>
    <div class="hpbg3">
        <!--start:div-->
        <div class="mauto hpwid11 fontlig hpp4">
            <div class="homeTag txtc fontlig color11">
        <h2>Upgrade your Membership to contact people you like</h2>
      </div>
            <!--start:div-->
            <div class="clearfix fullwid pt50">
                <!--start:left-->
                <div class="fl wid55p">
                    <ul class="hor_list clearfix fontlig f17 colr2">
                        <li><i class="sprite2 hpic2"></i></li>
                        <li class="wid80p pl18">
                            <p class="fontrobbold f19 color11">View Contacts</p>
                            <p class="pt8">See Mobile & Landline numbers.</p>
                            <p>Call directly. Send Text messages.</p>
                        </li>
                    </ul>
                </div>
                <!--end:left-->
                <!--start:right-->
                <div class="fr wid40p">
                    <ul class="hor_list clearfix fontlig f17 colr2">
                        <li><i class="sprite2 hpic3"></i></li>
                        <li class="wid70p pl17">
                            <p class="fontrobbold f19 color11">Send Messages</p>
                            <p>Send Personalized Messages </p>
                            <p>while expressing Interest.</p>
                        </li>
                    </ul>
                </div>
                <!--end:right-->
            </div>
            <!--end:div-->
            <!--start:div-->
            <div class="clearfix fullwid pt50">
                <!--start:left-->
                <div class="fl hpwid8">
                    <ul class="hor_list clearfix fontlig f17 colr2">
                        <li><i class="sprite2 hpic4"></i></li>
                        <li class="wid70p pl5">
                            <p class="fontrobbold f19 color11">See Email</p>
                            <p>Talk via emails. Share more</p>
                            <p> pictures, biodata, kundli etc.</p>
                        </li>
                    </ul>
                </div>
                <!--end:left-->
                <!--start:right-->
                <div class="fr wid40p">
                    <ul class="hor_list clearfix fontlig f17 colr2">
                        <li><i class="sprite2 hpic7"></i></li>
                        <li class="wid70p pl10">
                            <p class="fontrobbold f19 color11">Chat</p>
                            <p>Chat instantly with other</p>
                            <p>members who are online. </p>
                        </li>
                    </ul>
                </div>
                <!--end:right-->
            </div>
            <!--end:div-->
            <div class="mauto pos-rel scrollhid bg_pink txtc lh63 wid45p mt40 hoverPink"><a id="homepageMemLinkBtn" href="/profile/mem_comparison.php" class="pinkRipple colrw f24">Browse Membership Plans</a></div>
            <p class="txtc pt15 colr2">To know more, call us @ <span class="fontreg">~if $currency eq 'RS'`1-800-419-6299~else`+91-120-4393500~/if`</span></p>
        </div>
        <!--end:div-->
    </div>
</article>
<!--end:row 2-->
<!--start:row 3-->
<article>
    <div class="container mainwid hpp5">
       <div class="homeTag txtc fontlig color11">
        <h3>Matched By Jeevansathi</h3>
      </div>
        <div class="pt55">
            <ul class="hor_list clearfix mtch f14 color11 fontlig">
                ~foreach from=$successStoryData key=k item=successStory`
                ~if $k eq 0`
                <li class="center">
                    ~else`
                    <li class="center imggapl imggapl_ie ">
                        ~/if`
                        <a href="~$SITE_URL`/successStory/completestory?sid=~$successStory.SID`&year=~$successStory.YEAR`"> <img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($successStory.SQUARE_PIC_URL)`"  class="homeSuccessWidHgt"/> </a>
                        <div class="txtc pt10"> <a href="~$SITE_URL`/successStory/completestory?sid=~$successStory.SID`&year=~$successStory.YEAR`" class="color11 f14"> ~$successStory.NAME2` weds ~$successStory.NAME1`</a> </div>
                    </li>
                    ~/foreach`
                </ul>
            </div>
        </div>
    </article>
    <!--end:row 3-->
    <!--start:row 4-->
            ~include_partial('global/JSPC/_jspcAppPromo')`
            ~include_partial('global/JSPC/_jspcMatrimonialLinks')`
            ~include_partial('global/JSPC/_jspcSeoText')`


                                                        
                                                        ~include_partial('global/JSPC/_jspcCommonFooter')`
                                                      
                                                        <script type="text/javascript">
                                                        $(document).ready(function() {
                                                        slider();
                                                        });
                                                        </script>
