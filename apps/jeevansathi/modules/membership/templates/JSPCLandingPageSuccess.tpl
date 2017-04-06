<script type="text/javascript">
    var profileid = "~$profileid`";
    var helpAllStr = '~$data.topHelp.params`';
    var bannerMsg = "~$data.dividerText`";
    var bannerTimeout = "~$data.dividerExpiry`";
    var showCountdown = "~$showCountdown`";
    var countdown = "~$countdown`";
    var currency = "~$data.currency`";
    var topBlockMsg = "~$data.topBlockMessage.titleMessage`";
    var topBlockCLT = "~$data.topBlockMessage.contactsLeftText`";
    var topBlockCLN = "~$data.topBlockMessage.contactsLeftNumber`";
    var topBlockDays = "~$data.topBlockMessage.daysValue`";
    var topBlockMonths = "~$data.topBlockMessage.monthsValue`";
    var dividerExpiry = "~$data.dividerExpiry`";
    var tabVal = 1;
    var preFilledEmail = "~$data.userDetails.EMAIL`";
    var preFilledMobNo = "~$data.userDetails.PHONE_MOB`";
    var vasNames = new Array();
    var paidBenefits = new Array();
    var vasPrice = new Array();
    var openedCount = "~$data.openedCount`";
    var filteredVasServices = "~$data.filteredVasServices`";
    var skipVasPageMembershipBased = JSON.parse("~$data.skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
    ~if $data.serviceContent` 
            var pageType = 'membershipPage';  
            ~if $data.preSelectLandingVas` 
                var preSelectLandingVas = "~$data.preSelectLandingVas`"
            ~else`
                var preSelectLandingVas = ""
            ~/if`
            var astroDurations = [];
    ~/if`
    ~if $data.vasContent`              
        var pageType = 'ConditionsBasedDivVasPaid';
        ~foreach from=$data.vasContent key=k item=v name=vasLoop`
            vasNames["~$k`"] = "~$v.vas_name`";
        ~/foreach`
    ~/if`
    ~if $data.topBlockMessage.currentBenefitsMessages`
        ~foreach from=$data.topBlockMessage.currentBenefitsMessages key=k item=v name=benefitsCondLoop`
            paidBenefits["~$k`"] = "~$v`";
        ~/foreach`
    ~/if`
    ~if $data.upgradeMembershipContent` 
            var pageType = 'upgradeMembershipPage';
    ~/if`
    ~if $data.topBlockMessage.monthsValue neq 'Unlimited' && $data.topBlockMessage.JSPCnextMembershipMessage`
        var message = "~$data.topBlockMessage.JSPCnextMembershipMessage`";
        var pageType = 'ConditionsBasedHeader';
    ~else if $data.topBlockMessage.JSPCHeaderRenewMessage`
        var message = "~$data.topBlockMessage.JSPCHeaderRenewMessage`";
        var pageType = 'ConditionsBasedHeader';
    ~else if $data.topBlockMessage.contactsLeftNumber eq '0'`
        var message = "Benefits of your membership; you have reached the limit of quota to view contact details, to view more contact details Renew your membership";
        var pageType = 'ConditionsBasedHeader';    
    ~/if`
    ~if $data.pageOneVas`
        ~foreach from=$data.pageOneVas key=k item=v name=pageOneVasLoop`
            ~foreach from=$v.vas_options key=kk item=vv name=vasOptionsLoop`
                vasPrice["~$vv.id`"] = "~$vv.vas_price`";
            ~/foreach`
        ~/foreach`
    ~/if`
</script>
~include_partial('global/JSPC/_jspcCommonMemRegHeader',[pageName=>'membership'])`
~if $data.serviceContent`
<!--start:compare all plans popup-->
<div id="cmpplan" class="pos_fix layersZ disp-none" style="top:20px">
    <div class="mem-wid19 mem-bg1 pos-rel">
        <!--start:close icon-->
        <i id="compareCloseBtn" class="mem-sprite pos-abs mem-cross2 cursp mem-pos12"></i>
        <!--end:close icon-->
        <div class="mem_pad35">
            <!--start:table-->
            <table class="fullwid fontlig f15 mem-colr2" cellpadding="0" cellspacing="0">
                <tr>
                    <!--start:first col-->
                    <td class="cmp-wid1"><table class="fullwid" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="bg5 colrw cmp-lh1 vmid fontreg f17 pl20">Benefits You Get</td>
                        </tr>
                        ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                            ~if $v.subscription_id eq 'ESP'`
                                ~assign var=eSathiCheck value=1`
                            ~else`
                                ~assign var=eSathiCheck value=0`
                            ~/if`
                        ~/foreach`
                        ~foreach from=$data.allBenefits key=k item=v name=allBenefitsLoop`
                        ~if $smarty.foreach.allBenefitsLoop.index lte 2`
                        <tr>
                            <td class="bdrb cmp-lh1 pl20">~$v`</td>
                        </tr>
                        ~/if`
                        ~if $eSathiCheck eq 1`
                            ~if $smarty.foreach.allBenefitsLoop.index gt 2 && $smarty.foreach.allBenefitsLoop.index neq $smarty.foreach.allBenefitsLoop.last`
                            <tr>
                                <td class="bdrb cmp-lh1 pl20">~$v`</td>
                            </tr>
                            ~/if`
                            ~if $smarty.foreach.allBenefitsLoop.last`
                            <tr>
                                <td class="bdrb cmp-lh2 pl20">~$v`</td>
                            </tr>
                            ~/if`
                        ~else`
                            ~if $smarty.foreach.allBenefitsLoop.index lt (($smarty.foreach.allBenefitsLoop.total)-4)`
                            ~if $smarty.foreach.allBenefitsLoop.index gt 2 && $smarty.foreach.allBenefitsLoop.index lt (($smarty.foreach.allBenefitsLoop.total)-4)`
                            <tr>
                                <td class="bdrb cmp-lh1 pl20">~$v`</td>
                            </tr>
                            ~/if`
                            ~if $smarty.foreach.allBenefitsLoop.index eq (($smarty.foreach.allBenefitsLoop.total)-4)`
                            <tr>
                                <td class="bdrb cmp-lh2 pl20">~$v`</td>
                            </tr>
                            ~/if`
                            ~/if`
                        ~/if`
                        ~/foreach`
                        <tr>
                            <td class="bdrb1 mem-lh1 txtr pr10">Starts From</td>
                        </tr>
                        <tr>
                            <td class="hgt60"></td>
                        </tr>
                    </table></td>
                    <!--end:first col-->
                    ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                    ~if $v.subscription_id neq 'X'`
                    <!--start:esathi col-->
                    <td class="cmp-wid2 cmpcol" id="esathi-col"><table class="fullwid" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="pos-rel" ><div class="pos-abs fullwid fullhgt hoverimg"></div>
                            <table class="fullwid" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="bg5 colrw cmp-lh1 vmid fontreg f17 txtc">~$v.subscription_name`</td>
                                </tr>
                                ~foreach from=$v.benefits key=kk item=vv name=benefitsLoop`
                                ~if $smarty.foreach.benefitsLoop.index eq 4`
                                <tr>
                                    <td class="bdrb1 bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-chk3"></i></td>
                                </tr>
                                ~else`
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-chk3"></i></td>
                                </tr>
                                ~/if`
                                ~/foreach`
                                ~foreach from=$v.benefitsExcluded key=kk item=vv name=benefitsExcludedLoop`
                                ~if $eSathiCheck eq 1`
                                    ~if $smarty.foreach.benefitsExcludedLoop.last`
                                    <tr>
                                        <td class="bdrb1 bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                    </tr>
                                    ~else`
                                    <tr>
                                        <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                    </tr>
                                    ~/if`
                                ~else`
                                    ~if $smarty.foreach.benefitsExcludedLoop.index lt (($smarty.foreach.benefitsExcludedLoop.total)-4)`
                                    ~if $smarty.foreach.benefitsExcludedLoop.index eq (($smarty.foreach.benefitsExcludedLoop.total)-5)`
                                    <tr>
                                        <td class="bdrb1 bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                    </tr>
                                    ~else`
                                    <tr>
                                        <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                    </tr>
                                    ~/if`
                                    ~/if`
                                ~/if`
                                ~/foreach`
                                <tr>
                                    <td class="bdrb1 vmid txtc cmp-pad1"><div class="~if $v.starting_strikeout`strike~/if` color12 f13">~$v.starting_strikeout`&nbsp;</div>
                                    <div class="color11 f17"><span>~$data.currency`&nbsp;</span>~$v.starting_price_string`</div></td>
                                </tr>
                            </table></td>
                        </tr>
                        <tr>
                            <td id="viewDur_~$v.subscription_id`" viewDurLink="~$v.subscription_id`" class="pt10 cursp cmp-vbtn hgt50 viewDurLink" style="overflow:hidden;position: relative;"><div class="fullwid bg_pink txtc lh40 hoverPink"><a href="#" class="colrw fontreg f13 pinkRipple">View Duration</a></div></td>
                        </tr>
                    </table></td>
                    <!--end:esathi col-->
                    ~/if`
                    ~/foreach`
                    <!--start:free col-->
                    <td class="cmp-wid3 cmpcol" id="free-col"><table class="fullwid " cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="pos-rel" ><div class="pos-abs fullwid fullhgt hoverimg"></div>
                            <table class="fullwid" cellpadding="0" cellspacing="0">
                                <!-- <tr>
                                    <td class="bg5 colrw vtop fontreg f17"><table class="fullwid" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="pl15">Free</td>
                                            <td class="vmid"><div class="headfootsprtie no-img-small" ></div></td>
                                        </tr>
                                    </table></td>
                                </tr> -->
                                <tr>
                                    <td class="bg5 colrw cmp-lh1 vmid fontreg f17 txtc">Free</td>
                                </tr>
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                ~if $eSathiCheck eq 1`
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                <tr>
                                    <td class="bdrb2 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                ~/if`
                                <tr>
                                    <td class="bdrb2 bdrb1 vmid txtc cmp-lh2"><i class="mem-sprite mem-cross1"></i></td>
                                </tr>
                                <tr>
                                    <td class="bdrb1 vmid txtc mem-cb-hgt1"></td>
                                </tr>
                            </table></td>
                        </tr>
                        <tr>
                            <td class="hgt60"></td>
                        </tr>
                    </table></td>
                    <!--end:free col-->
                </tr>
            </table>
            <!--end:table-->
        </div>
    </div>
</div>
<!--end:compare all plans popup-->
<!--start:plan-->
<div class="bg-4">
    <div class="container mainwid mem_pad4 scrollhid">
        <!--start:menu-->
        <div class="sli_brdr1 clearfix">
            <div class="fl planlist">
                <ul class="tabs">
                    ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                    ~if $v.subscription_id neq 'X'`
                    <li id="main_~$v.subscription_id`" mainMemTab="~$v.subscription_id`" class="fontrobbold ~if $smarty.foreach.servicesLoop.total gt 4`planwidth~/if` trackJsEventGA evalVas"><span></span><span class="trackJsEventGAName">~$v.subscription_name`</span> <span class="fontlig">~$v.starting_price` <span>~$data.currency`&nbsp;</span>~$v.starting_price_string`</span> </li>
                    ~/if`
                    ~/foreach`
                </ul>
            </div>
            <div id="comparePopup" class="cursp fr colr5 f24 fontrobbold ~if $smarty.foreach.servicesLoop.total gt 4`pt22~/if`">Compare Plans</div>
        </div>
        <!--end:menu-->
        <!--start:content slider-->
        <div class="pt25 clearfix pos-rel">
            <!--start:left slider-->
            <div class="fl mem-wid9 scrollhid">
                <div id="sliderContainer" class="mem-wid12t clearfix pos-rel">
                    ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                    <!--start:slidercontent-->
                    ~if $v.subscription_id neq 'X'`
                    <div id="tab_~$v.subscription_id`" class="clearfix js-main_~$v.subscription_id` fl mem-wid9">
                        <div class="planopt fontreg mem-wid9 fl">
                            <div class="pos-rel">
                                ~if $data.userId eq '0'`
                                <!-- start:logged out overlay -->
                                <div class="mem-overlay3 pos-abs">
                                    <div class="disp-tbl fullwid fullhgt txtc fontlig">
                                        <div class="disp-cell vmid colrw">
                                            <div class="f26">~$v.subscription_name` starts @ <span>~$data.currency`&nbsp;</span><span id="tab_~$v.subscription_id`_startingPrice">~$v.starting_price_string`</span></div>
                                            <div class="f17 pt10">You must be logged in to see detailed plans</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end:logged out overlay-->
                                ~/if`
                                ~foreach from=$v.durations key=kd item=vd name=servDurationsLoop`
                                <!--start:row-->
                                <div style="position: relative;overflow: hidden;">
                                <div id="~$v.subscription_id`~$vd.duration_id`" mainMem="~$v.subscription_id`" mainMemDur="~$vd.duration_id`" mainMemContact="~$vd.contacts`" class="durSel cursp disp-tbl opt padallb mb5 evalVas ~if $vd.mostPopular eq 'Y'`plansel~/if` blueRipple">
                                    <div class="disp-cell vmid txtr pr30 mem-wid5">
                                        <div class="f13 newclr1"></div>
                                        <div id="~$v.subscription_id`~$vd.duration_id`_duration" class="durationTextPlaceholder f20 pl5">~$vd.duration` ~$vd.duration_text`</div>
                                    </div>
                                    <div class="disp-cell vmid txtc mem-wid6">
                                        <div id="~$v.subscription_id`~$vd.duration_id`_contacts"class="f13">~$vd.contacts`</div>
                                        <div class="pos-rel mem_pad9">
                                            <div class="mem-sep"></div>
                                            <div class="pos_abs smallcircle mem-pos1"></div>
                                        </div>
                                        ~if $vd.mostPopular eq 'Y'`
                                        <div id="~$v.subscription_id`~$vd.duration_id`_popularity"class="f13">Popular</div>
                                        ~/if`
                                    </div>
                                    <div class="disp-cell vmid txtr pr30 mem-wid7">
                                        <div id="~$v.subscription_id`~$vd.duration_id`_price_strike"class="f13 fontlig txtstr">~$vd.price_strike`</div>
                                        <div id="~$v.subscription_id`~$vd.duration_id`_price"class="f20">~$vd.price`</div>
                                        ~if $vd.price_per_month`
                                        <div class="f13 fontlig"><span>~$data.currency`&nbsp;</span>~$vd.price_per_month`/month</div>
                                        ~/if`
                                    </div>
                                    <div class="disp-cell vmid mem-wid8 txtc"> <i class="sprite2 mem-radio"></i> </div>
                                </div>
                                </div>
                                <!--end:row-->
                                ~/foreach`
                            </div>
                        </div>
                    </div>
                    <!--end:slidercontent-->
                    ~/if`
                    ~/foreach`
                </div>
            </div>
            <!--end:left slider-->
            <!--start:benefits-->
            <div class="fl mem-wid10">
                <div class="mem_pad10_new1 benefits">
                    ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                    ~if $v.subscription_id neq 'X'`
                    <!--start:eRishta-->
                    <div class="list-main_~$v.subscription_id` opt planfeat disp-none">
                        <div class="fontreg mem_pad10_new2 f20 color11">~$v.subscription_name` Benefits</div>
                        <ul class="fontlig mem_pad10_new1">
                            ~foreach from=$v.benefits key=kk item=vv name=benefitsLoop`
                                ~assign var=continue value=0`
                                ~foreach from=$v.servMessage key=kkk item=vvv name=servMessageLoop`
                                    ~if $vv eq $kkk`
                                        ~assign var=continue value=1`
                                        <li class="check ~if $vv eq 'Profile Boost'`fontmed~/if`">~$vv`~if $vv eq 'Profile Boost'`<span class="colr5"> new</span>~/if`<i class="newSprt cursp newSprt_6 pl10"><div class="bg-white pos-abs ~if $vv eq 'Featured Profile'`hoverDiv2~/if` hoverDiv color11 f14 fontlig lh20" style="width:267px; top:48px; right:11px">
                                                    ~assign var=helpText value=". "|explode:$vvv`
                                                    ~foreach from=$helpText key=helpKey item=helpVal name=helpLoop`
                                                        ~$helpVal`<br>
                                                    ~/foreach`
                                                    <div class="colr5">FREE with eAdvantage package</div></div></i></li>
                                    ~/if`
                                ~/foreach`
                                ~if $continue eq 0`
                                    <li class="check">~$vv`</li>
                                ~/if`
                            ~/foreach`
                            ~foreach from=$v.benefitsExcluded key=kk item=vv name=benefitsExcludedListingLoop`
                                ~assign var=continueExc value=0`
                                ~foreach from=$v.servMessage key=kkk item=vvv name=servMessageLoop`
                                    ~if $vv eq $kkk`
                                        ~assign var=continueExc value=1`
                                        ~if $eSathiCheck eq 1`
                                            <li class="cross txtstr color12 ~if $vv eq 'Profile Boost'`fontmed~/if`">~$vv`~if $vv eq 'Profile Boost'`<span class="colr5"> new</span>~/if`<i class="newSprt cursp newSprt_6 pl10"><div class="bg-white pos-abs ~if $vv eq 'Featured Profile'`hoverDiv2~/if` hoverDiv color11 f14 fontlig lh20" style="width:267px; top:48px; right:11px">
                                                        ~assign var=helpText value=". "|explode:$vvv`
                                                        ~foreach from=$helpText key=helpKey item=helpVal name=helpLoop`
                                                            ~$helpVal`<br>
                                                        ~/foreach`
                                                        <div class="colr5">FREE with eAdvantage package</div></div></i></li>
                                        ~else`
                                            ~if $smarty.foreach.benefitsExcludedListingLoop.index lt (($smarty.foreach.benefitsExcludedListingLoop.total)-4)`
                                                <li class="cross txtstr color12 ~if $vv eq 'Profile Boost'`fontmed~/if`">~$vv`~if $vv eq 'Profile Boost'`<span class="colr5"> new</span>~/if`<i class="newSprt cursp newSprt_6 pl10"><div class="bg-white pos-abs ~if $vv eq 'Featured Profile'`hoverDiv2~/if` hoverDiv color11 f14 fontlig lh20" style="width:267px; top:48px; right:11px">
                                                            ~assign var=helpText value=". "|explode:$vvv`
                                                            ~foreach from=$helpText key=helpKey item=helpVal name=helpLoop`
                                                                ~$helpVal`<br>
                                                            ~/foreach`
                                                            <div class="colr5">FREE with eAdvantage package</div></div></i></li>
                                            ~/if`
                                        ~/if`
                                    ~/if`
                                ~/foreach`
                                ~if $continueExc eq 0`
                                    ~if $eSathiCheck eq 1`
                                        <li class="cross txtstr color12">~$vv`</li>
                                    ~else`
                                        ~if $smarty.foreach.benefitsExcludedListingLoop.index lt (($smarty.foreach.benefitsExcludedListingLoop.total)-4)`
                                            <li class="cross txtstr color12">~$vv`</li>
                                        ~/if`
                                    ~/if`
                                ~/if`
                            ~/foreach`
                        </ul>
						~if $data.userId neq '0'`
                        <!--start:add astro-->
                        ~foreach from=$data.pageOneVas key=benefitsK item=benefitsV name=pageOneVas`
                            <div class="addOnBrd1 mt24">
                                <div class="addOnp1">
                                    <div class="fontmed f16">
                                        Add ~$benefitsV.vas_name`
                                        <i class="newSprt cursp newSprt_6 pl10 pos_rel">
                                            <div class="bg-white pos-abs  hoverDiv color11 f14 fontlig lh20" style="width:178px; top:-82px; right:-93px">
                                                ~$benefitsV.vas_description`
                                            </div>
                                        </i>
                                    </div>
                                    <ul class="addOnAstro fontlig pt5">
                                        ~foreach from=$benefitsV.vas_options key=vasOpK item=vasOpV name=vasOp`
                                            <li class="clearfix pt5">
                                                <div class="fl">
                                                    <input type="checkbox" value="~$vasOpV.duration`MASTRO" name="MONTHASTRO~$v.subscription_id`[]" astroAddon="~$vasOpV.id`" dur="~$vasOpV.duration`" class="astroAddon" id="~$v.subscription_id`~$vasOpV.id`">
                                                </div>
                                                <div class="fl f14 mt1 grey5 pl5">
                                                    ~$vasOpV.duration` months for ~$data.currency` <span id="~$v.subscription_id`~$vasOpV.id`_price">~$vasOpV.vas_price`</span>  ~if $vasOpV.vas_price_strike`<span class="txtstr upcolr1 f13" id="~$v.subscription_id`~$vasOpV.id`_price_strike">~$vasOpV.vas_price_strike`</span>~/if`
                                                </div>
                                            </li>
                                        ~/foreach`
                                    </ul>
                                </div>
                            </div>   
                        ~/foreach`
                        <!--end:add astro-->
                        
                        <div class="mem_pad10_new2 txtc brdrTop colrgrey f15 fontreg">
                            
                        </div>
                        ~else`

                        <div class="mt24 mem_pad10_new2 wid90p txtc brdrTop colrgrey f15 fontreg">
                            <span id="finalMemTab_~$v.subscription_id`">
							</span>
							<span id="finalMemDuration_~$v.subscription_id`"></span>~$data.currency`<span id="finalMemPrice_~$v.subscription_id`"></span>
                        </div>
						~/if`
                        <!--start:total-->
                        ~if $data.userId eq '0'`
                        <!-- <div style="overflow:hidden;position: relative;" class="mt30"> -->
                        <div id="mainServLoginBtn" class="fontreg loginLayerJspc cursp bg_pink txtc mem_pad7 colrw f17 mt30">Login to Continue</div>
                        <!-- </div> -->
                        ~else`
                        <div id="~$v.subscription_id`_savings_container" class="txtc f13 lh41 colr5 fontreg"> Your Savings ~$data.currency`&nbsp;<span id="~$v.subscription_id`_savings"></span></div>
                        <div class="overflowPinkRipple fontreg" style="overflow:hidden;position: relative;height: 55px;">
                        <div id="mainServContinueBtn" class="cursp bg_pink txtc mem_pad7 colrw f17 continueBtn pinkRipple hoverPink fontreg" selectedTab="~$v.subscription_id`"> <span class="disp_ib">~if $data.currency eq '$'`USD~else`~$data.currency`~/if`&nbsp;</span><span id="~$v.subscription_id`_final_price"></span> | <span class="disp_ib pl10">Pay Now</span></div>
                        </div>
                        ~/if`
                        <!--end:total-->
                    </div>
                    <!--end:eRishta-->
                    ~/if`
                    ~/foreach`
                </div>
            </div>
            <!--end:benefits-->
        </div>
        <!--end:content slider-->
    </div>
</div>
<!--end:plan-->
<!--start:JS Exclusive-->
<div class="mem_bg1" id="jsExclusiveDiv">
    <div class="container mainwid mem_pad4">
        <!--start:div-->
        <div class="fullwidth colrw clearfix mem_brdr1">
            <!--start:left-->
            <div class="fl mem_brdr1 pb10"> <span class="f28 fontrobbold disp_ib mem_pad3">JS Exclusive <span class="f13 fontreg">Personalised service</span></span> </div>
            <!--end:left-->
            <!--start:right-->
            <div class="fr pt10"> <span class="f17 fontlig"><a href="~sfConfig::get('app_site_url')`/membership/jsexclusiveDetail#placeRequestForm" style="color:#fff">Request Callback</a></span> </div>
            <!--end:right-->
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="clearfix mem_pad_new colrw fontlig" >
            <!--start:left-->
            <div class="fl wid50p jsexc">
                <ul>
                    <li>Create a profile for you that gets noticed</li>
                    <li>Understand qualities you are looking in your desired partner</li>
                    <li>Hand-picking of profiles by Jeevansathi that match your expectations</li>
                    <li>Contact shortlisted profiles & arrange meetings on your behalf</li>
                    <li>Priority Customer service</li>
                </ul>
                <div id="jsxKnowMoreLink" class="pl19 pt10"><a href="~sfConfig::get('app_site_url')`/membership/jsexclusiveDetail" class="fontreg f16 colr5">Know More</a></div>
                <div class="fullwid txtc">
                    <div class="f13 pt10">Need more details?</div>
                    ~if $data.currency eq '$'`
                        <div class="fontreg f18 lh30">Call Rahul @ +91-8800909042</div>
                    ~else`
                        <div class="fontreg f18 lh30">Call us @ 1800-3010-6299</div>
                        <!-- <div class="fontreg f18 lh30">Call Rahul @ 1800-3010-6299</div> -->
                    ~/if`
                </div>
            </div>
            <!--end:left-->
            <!--start:value-->
            <div class="fl wid50p jsexc">
                <div id="exclusiveContainer" class="clearfix fontreg">
                    <!--start:div-->
                    <div class="disp-tbl valuopt fullwid mt10">
                        ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                        ~if $v.subscription_id eq 'X'`
                        ~foreach from=$v.durations key=kd item=vd name=servDurationsLoop`
                        <!--start:div-->
                        <div id="~$v.subscription_id`~$vd.duration_id`" class="jsxDur disp-cell cursp wid33p_1 txtc ~if $vd.mostPopular eq 'Y'`active~/if`" mainMem="~$v.subscription_id`" mainMemDur="~$vd.duration_id`" mainMemContact="~$vd.contacts`">
                            <div class="mem_pad16">
                                ~if $vd.price_strike`
                                <div id="~$v.subscription_id`~$vd.duration_id`_price_strike" class="txtr txtstr opa80 f13 fontlig">~$vd.price_strike`</div>
                                ~else`
                                <div class="txtr opa80 f13 fontlig">&nbsp;</div>
                                ~/if`
                                <div class="f20">~$vd.duration`&nbsp;<span class="f13">~$vd.duration_text`</span></div>
                                <div class="f20"><span>~$data.currency`&nbsp;</span><span id="~$v.subscription_id`~$vd.duration_id`_price">~$vd.price`</span></div>
                            </div>
                        </div>
                        <!--end:div-->
                        ~/foreach`
                        ~/if`
                        ~/foreach`
                    </div>
                    <!--end:div-->
                </div>
                <!--end:value-->
                ~if $data.userId eq '0'`
                <!-- <div style="overflow:hidden;position: relative;" class="mt30"> -->
                <div id="jsxServLoginBtn" class="loginLayerJspc cursp bg_pink txtc mem_pad7 colrw f17 fontreg mt30">Login to Continue</div>
                <!-- </div> -->
                ~else`
                <!--start:div-->
                <div class="txtc colrw padallf f13" id="X_savings_container"><span class="fontlig">Your Savings</span><span class="fontreg disp_ib pl5"><span>~$data.currency`&nbsp;</span><span id="X_savings"></span></div>
                <!--end:div-->
                <div id='tab_X'>
                    <div class="cursp bg_pink txtc mem_pad7 continueBtn overflowPinkRipple hoverPink" selectedTab="X" style="overflow:hidden;position: relative;">
                        <div id="jsxServContinueBtn" class="f17 fontreg pinkRipple"> <span class="disp_ib"><span>~if $data.currency eq '$'`USD~else`~$data.currency`~/if`&nbsp;</span><span id="X_final_price"></span> | <span class="colrw disp_ib pl10">Buy Now</span></div>
                    </div>
                </div>
                ~/if`
            </div>
            <!--end:right-->
        </div>
        <!--end:div-->
    </div>
</div>
<!--end:JS Exclusive-->
<!-- start: membership benefits block -->
<div class="bg-white" id="whyPaidDiv">
    <div class="container mainwid mem_pad4">
        <div class="fullwid txtc colr3">
            <div class="f24 fontlig">
                Why Paid Membership?
            </div>
            <div class="pt10 f16 fontlig">
                Upgrade your membership now to find your perfect partner! With a paid membership, you can seamlessly connect with your prospects and get more responses. Here are some key benefits:
            </div>
            <div class="disp_ib memPadNew">
                <i class="newSprt newSprt_1">
                </i>
                <div class="fullwid txtc pt10 f16 fontlig">
                    Send personalized messages to members you like
                </div>
            </div>
            <div class="disp_ib memPadNew">
                <i class="newSprt newSprt_2">
                </i>
                <div class="fullwid txtc pt10 f16 fontlig">
                    Send chat requests to members
                </div>
            </div>
            <div class="disp_ib memPadNew">
                <i class="newSprt newSprt_3">
                </i>
                <div class="fullwid txtc pt10 f16 fontlig">
                    View contact details of the members
                </div>
            </div>
            <div class="disp_ib memPadNew">
                <i class="newSprt newSprt_4">
                </i>
                <div class="fullwid txtc pt10 f16 fontlig">
                    Show your contact details to other members
                </div>
            </div>
            <div class="fr cursp pt10 f16 fontlig" id="scrollTop">
                <i class="newSprt pr10 newSprt_5">
                </i>
                Top
            </div>
        </div>
    </div>
</div>
<!-- end: membership benefits block-->
~/if`

<!--start:upgrade membership eligible user section-->
~if $data.upgradeMembershipContent`
    <!--start:plan-->
    <div class="bg-4">
        <div class="container mainwid">        
            <div class="clearfix color11 pt30 pb30">
                <div class="fl">
                    <p class="fontmed f24 color5">Exclusive offer for you, valid till ~$data.upgradeMembershipContent.upgradeOfferExpiry`</p>
                    <p class="f16 pt5 fontreg">Upgrade your current ~$data.topBlockMessage.currentMemName` membership to ~$data.upgradeMembershipContent.upgradeMainMemName` membership by paying ~if $data.currency eq '$'`USD~else`~$data.currency`~/if` ~$data.upgradeMembershipContent.upgradeExtraPay`</p>
                </div>       
            </div>        
            <!--start:block1-->        
            <div class="clearfix pb30 color11">         
                <div class="fl upwid1 upgrd_bg1 fontlig" id="currentMemSection">               
                        <!--start:head-->   
                        <div class="upgrd_p1 upb1">                
                            <div class="f14">Current Membership</div>
                             <ul class="listnone f14 uoul pt15">
                                <li class="f20"><strong>~$data.topBlockMessage.currentMemName`</strong></li>
                                <li>~$data.topBlockMessage.currentActualDuration` Month</li>
                                <li>~$data.topBlockMessage.totalContactsAllotted` Contacts To View</li>                        
                            </ul>
                         </div>
                         <!--end:head-->
                         <div class="upgrd_p1">
                            ~if $data.topBlockMessage.currentBenefitsMessages`
                                <p class="upcolr1 f14 fontmed">Benefit</p>
                                <ul class="uplino f14 pt10 lh22">
                                    ~foreach from=$data.topBlockMessage.currentBenefitsMessages key=k item=v name=benefitsCondLoop`
                                        <li>~$v`</li>
                                    ~/foreach`
                                </ul>
                            ~/if`
                         </div>
                </div>            
                <div class="fr wid55p upgrd_bg1 fontreg" id="upgardeMemSection">               
                        <!--start:head-->   
                        <div class="upgrd_p1 upb1">                
                            <div class="f14">Special upgrade offer for you </div>
                            <ul class="listnone f14 uoul pt15">
                                <li class="f20"><strong>~$data.upgradeMembershipContent.upgradeMainMemName`</strong></li>
                                <li>~if $data.upgradeMembershipContent.upgradeMainMemDur eq 'L'` Unlimited ~else` ~$data.upgradeMembershipContent.upgradeMainMemDur` ~/if` Month</li>
                                <li>~$data.upgradeMembershipContent.upgradeTotalContacts` Contacts To View</li>                        
                            </ul>                    
                        </div>
                         <!--end:head-->
                        <div class="upgrd_p1">
                            ~if $data.upgradeMembershipContent.upgradeAdditionalBenefits`
                                <p class="upcolr1 f14 fontmed">Additional Benefit</p>
                                <ul class="uplino f14 pt10 lh22 fontlig">
                                    ~foreach from=$data.upgradeMembershipContent.upgradeAdditionalBenefits key=k item=v name=additionalBenefitsCondLoop`
                                        <li>~$v`</li>
                                    ~/foreach`
                                </ul>
                            ~/if`
                            ~if $data.upgradeMembershipContent.upgardeComparedFacts`
                                <p class="upcolr1 f14 fontmed pt20">Did you know?</p>
                                <ul class="uplino f14 pt10 lh22 fontlig">
                                    ~foreach from=$data.upgradeMembershipContent.upgardeComparedFacts key=k item=v name=comparedBenefitsCondLoop`
                                        <li>- ~$v`</li>
                                    ~/foreach`
                                </ul>
                            ~/if`
                            <div class="pt30 txtc" id="upgradeMainMemBtn">
                                <button class="upb cursp">~if $data.currency eq '$'`USD~else`~$data.currency`~/if`  ~$data.upgradeMembershipContent.upgradeExtraPay` &nbsp;|&nbsp;Pay Now</button>
                            </div>
                         </div>
                </div>        
            </div>        
            <!--end:block1-->
            <!--start:block 2-->
            ~if $data.vasContent`
                <div id="VASdiv">
                    ~foreach from=$data.vasContent key=k item=v name=vasLoop`                    
                        <div style="margin-top:30px;padding-top:40px; padding-bottom:40px" class="upgrd_bg1" id="~$v.vas_key`">
                            <div class="clearfix fontlig">
                                <div class="fl pl35 color11 upwid2">
                                    <p class="fontrobbold f24">~$v.vas_name`</p>
                                    <p class="f13 pt5">~$v.vas_description`</p>
                                    <div class="pt30">
                                        <ul class="uplino optionsList">
                                            ~foreach from=$v.vas_options key=kk item=vv name=vasDurLoop`
                                                ~if $kk gt 0`
                                                <li id="~$vv.id`" class="clearfix disp_ib pl30" vasKey="~$v.vas_key`">
                                                ~else`
                                                <li id="~$vv.id`" class="clearfix disp_ib" vasKey="~$v.vas_key`">
                                                ~/if`
                                                    <div class="fl"><input type="radio"  value="~$vv.duration`M" name="MONTH[]" id="~$vv.id`"></div>
                                                    <div class="fl pl10" id="~$vv.id`_duration" data-price=" ~$data.currency` ~$vv.vas_price`">~$vv.duration` months for ~$data.currency` ~$vv.vas_price`  ~if $vv.vas_price_strike`<span class="txtstr upcolr1" id="~$vv.id`_price_strike">~$vv.vas_price_strike`</span> </div>~/if`
                                                </li>
                                            ~/foreach`
                                        </ul>                               
                                    </div>                
                                </div>
                                <div class="fr upgrd_p2">
                                <div class="pt30 txtc vasPayBtn" vasKey="~$v.vas_key`">
                                    <button class="upb cursp" id="selectedVasPrice_~$v.vas_key`"><span class="price">~$data.currency` ~$vv.vas_price`</span> &nbsp;|&nbsp;Pay Now</button>
                                </div>
                                </div>
                            </div>
                        </div>  
                    ~/foreach` 
                </div>        
            ~/if`
            <!--end:block 2-->      
        </div>
    </div>
    <!--end:plan-->
<!--end:upgrade membership eligible user section-->

~else if $data.vasContent`
<!--start:plan-->
<div class="bg-4">
    <div class="container mainwid pt40">
        <!--start:title-->
        <div class="fullwid mem-brd3"> <span class="f28 color11 fontrobbold disp_ib mem-brd4 mem_pad3 pb10"> Select Value Added Services </span> </div>
        <!--end:title-->
        <!--start:div-->
        <div class="clearfix pt23">
            <!--start:VAS-->
            <div class="fl mr20 mem-widp1">
                <div class="fullwid" id="VASdiv">
                    <ul class="clearfix">
                        ~foreach from=$data.vasContent key=k item=v name=vasLoop`
                        <!--start:VAS div-->
                        <li id="~$v.vas_key`" class="bg-white mem-widp3 pos-rel">
                            <!--start:overlay-->
                            <div id="~$v.vas_key`_overlay" class="disp-none">
                                <div class="vasoverlay"></div>
                                <div class="vasoverlay2 fullwid">
                                    <div class="mem_pad18">
                                        <div class="txtc mem-brd7 pb23"> <i class="mem-sprite vasselicon"></i> </div>
                                        <div class="pt16 txtc colrw">
                                            <div class="fontmed f20">~$v.vas_name` Added</div>
                                            ~foreach from=$v.vas_options key=kk item=vv name=vasDurLoop`
                                            <div id="~$vv.id`_overlay" class="pt10 fontlig opa80 disp-none">~$vv.duration` ~$vv.text`&nbsp;/&nbsp;<span>~$data.currency`</span>~$vv.vas_price`</div>
                                            ~/foreach`
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mem_pad17">
                                <!--start:icon-->
                                <div class="disp-tbl txtc fullwid">
                                    <div class="disp-cell vmid mem-hgt2"> <i class="mem-sprite mem-~$v.vas_key`"></i> </div>
                                </div>
                                <!--end:icon-->
                                <!--start:VAS name-->
                                <div id="~$v.vas_key`_name" class="colr5 fontmed f20 txtc pt30">~$v.vas_name`</div>
                                <!--end:VAS name-->
                                <!--start:VAS desc-->
                                <div class="fontlig f15 colr2 lh20 txtc pt5">~$v.vas_description`</div>
                                <!--end:VAS desc-->
                                <!--start:VAS Plan-->
                                <div class="pt35">
                                    <div class="mem-brd5 disp-tbl fullwid fontlig f15 lh20">
                                        ~foreach from=$v.vas_options key=kk item=vv name=vasDurLoop`
                                        ~if not $smarty.foreach.vasDurLoop.last`
                                        <div id="~$vv.id`" class="disp-cell vmid mem-brd6 mem-widp4 txtc vascell" vasKey="~$v.vas_key`">
                                            <div id="~$vv.id`_duration">~$vv.duration` ~$vv.text`</div>
                                            ~if $vv.vas_price_strike`
                                            <div id="~$vv.id`_price_strike"><span>~$data.currency`</span><span class="f13 fontlig txtstr prc">~$vv.vas_price_strike`</span></div>
                                            ~/if`
                                            <div id="~$vv.id`_price"><span>~$data.currency`</span><span class="prc">~$vv.vas_price`</span></div>
                                        </div>
                                        ~/if`
                                        ~if $smarty.foreach.vasDurLoop.last`
                                        <div id="~$vv.id`" class="disp-cell vmid mem-widp4 txtc vascell" vasKey="~$v.vas_key`">
                                            <div id="~$vv.id`_duration">~$vv.duration` ~$vv.text`</div>
                                            ~if $vv.vas_price_strike`
                                            <div id="~$vv.id`_price_strike"><span>~$data.currency`</span><span class="f13 fontlig txtstr prc">~$vv.vas_price_strike`</span></div>
                                            ~/if`
                                            <div id="~$vv.id`_price"><span>~$data.currency`</span><span class="prc">~$vv.vas_price`</span></div>
                                        </div>
                                        ~/if`
                                        ~/foreach`
                                    </div>
                                </div>
                                <!--end:VAS Plan-->
                            </div>
                        </li>
                        <!--end:VAS div-->
                        ~/foreach`
                    </ul>
                </div>
            </div>
            <!--end:VAS-->
            <!--start:cart-->
            <div class="fr mem-wid6 mem-widp2 mem_bg1">
                <div class="mem_pad19 colrw mem-hgt3">
                    <div class="fontthin f20 opa60">You are getting</div>
                    <!--start:sel VAS-->
                    <div class="">
                        ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                        ~if $v.subscription_id eq $data.selectedMainServKey`
                        ~foreach from=$v.durations key=kd item=vd name=servDurationsLoop`
                        ~if $vd.duration_id eq $data.selectedMainServDur`
                        <div id="mainPlan">
                            <div class="disp-tbl fullwid">
                                <div id="mainPlanName" class="disp-cell f15 fontreg wid80p pos-rel">~$v.subscription_name`<span id="changeMainPlan" class="vsup opa60 cursp">CHANGE PLAN</span></div>
                                <div id="mainPlanStrikePrice" class="disp-cell f13 fontlig opa60 txtr strike wid20p">~$vd.price_strike`</div>
                            </div>
                            <div class="disp-tbl fullwid">
                                <div id="mainPlanDurAndCont" class="disp-cell vbtm fontlig f13">~$vd.duration` ~$vd.duration_text` | ~$vd.contacts`</div>
                                <div id="mainPlanPrice" class="disp-cell fontreg f15 txtr">~$vd.price`</div>
                            </div>
                        </div>
                        ~/if`
                        ~/foreach`
                        ~/if`
                        ~/foreach`
                        <div id="vasServices"></div>
                    </div>
                    <!--end:sel VAS-->
                </div>
                <!--start:seprator-->
                <div class="pos-rel fullwid mem-hgt4">
                    <div class="pos-ab mem-pos3"> <i class="mem-sprite mem-leftcirlce"></i> </div>
                    <div class="pos-abs mem-pos1"> <i class="mem-sprite mem-rightcirlce"></i> </div>
                    <div class="pos-abs fullwid mem-pos4 mem-brd8"></div>
                </div>
                <!--end:seprator-->
                <!--start:total-->
                <div class="mem_pad23 colrw fontlig">
                    <div id="savingsBlock" class="txtc f15 pb10 disp-none">
                        <span>Your Savings &nbsp;</span><span>~$data.currency`</span><span id="totalSavings"></span>
                    </div>
                    <div style="overflow:hidden;position: relative;">
                        <div id="payNowBtn" class="fullwid txtc lh50">
                            <span>~if $data.currency eq '$'`USD~else`~$data.currency`~/if`</span>&nbsp;<span id="totalPrice"></span>&nbsp;|&nbsp;<span class="colrw">Pay Now</span>
                        </div>
                    </div>
                    <div class="pt10 f11 txtc">PRICE INCLUDES ~$data.taxRate`% SERVICE TAX</div>
                </div>
                <!--end:total-->
            </div>
            <!--end:cart-->
        </div>
        <!--end:div-->
    </div>
</div>
<!--end:plan-->
~/if`
<!--start:feature content-->
<div class="bg-4">
    <div class="container mainwid clearfix disp-tbl mem_pad4">
        <!--start:div-->
        <div class="wid33p_1 disp-cell">
            <div class="mem_wid4">
                <div class="colr2 fontrobbold f20 mem_brd1 pb5 allcaps">WHY JEEVANSATHI</div>
                <ul class="feature">
                    <li class="fontlig">Maximum benefits per month</li>
                    <li class="fontlig">Biggest savings per month</li>
                    <li class="fontlig">Lowest price per contact</li>
                </ul>
            </div>
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="wid33p_1 disp-cell">
            <div class="mem_wid4">
                <div class="colr2 fontrobbold f20 mem_brd1 pb5 allcaps">Whats new</div>
                <ul class="feature">
                    <li class="fontlig">Real-time profile updates</li>
                    <li class="fontlig">Profiles verified by Jeevansathi</li>
                    <li class="fontlig">Search got even more personalised</li>
                </ul>
            </div>
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="wid33p_1 disp-cell">
            <div class="mem_wid4">
                <div class="colr2 fontrobbold f20 mem_brd1 pb5 allcaps">We are secure</div>
                <ul class="feature">
                    <li class="fontlig">Multiple methods for payment</li>
                    <li class="fontlig">Safe & secure payment gateway</li>
                    <li class="fontlig">We do not save your card details</li>
                </ul>
            </div>
        </div>
        <!--end:div-->
    </div>
</div>
<!--end:feature content-->
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
<script type="text/javascript">
    if(window.top.location.href != window.location.href){
        window.top.location.href = window.location.href;
    }
    
    $(window).load(function(){
        $("#scrollTop").on("click", function () {
            $('html, body').animate({
                scrollTop: '0px'
            }, 1000);
        });
        $("#knowMore").on("click", function () {
            $("html, body").animate({
                scrollTop: $("#whyPaidDiv").prop('scrollHeight') + 800
            }, 1000);
        });
        $("#landJSExc").on("click", function () {
            $("html, body").animate({
                scrollTop: $("#jsExclusiveDiv").prop('scrollHeight') + 360
            }, 1000);
        });
    });

    $(document).ready(function() {
    ~if $data.serviceContent`
        eraseCookie('paymentMode');
        eraseCookie('cardType');
        eraseCookie('couponID');
        if(!checkEmptyOrNull(readCookie('expCheck'))){
            eraseCookie('selectedVas');
            createCookie('expCheck', '1');
        }
        initializeMembershipPage();

        var containerWidth = ~$data.serviceContent|count`-1;
        containerWidth = containerWidth*621;
        var ScreenHgt = $(window).height(),ScreenWid = $(window).width(),leftval = (ScreenWid / 2) - 450;
        $("#sliderContainer").css('width',containerWidth);
        $('#cmpplan').css('left', leftval);
        $(".trackJsEventGA").on('click', function(){
            var tabName = $(this).find("span.trackJsEventGAName").html();
            if(profileid == ''){
                trackJsEventGA("jspc","MembershipTabChange", tabName, 0);
            } else {
                trackJsEventGA("jspc","MembershipTabChange", tabName, profileid);
            }
        });
        $('#js-panelbtn').click(function(e) {
            if (!$("#js-panelbtn").hasClass("mem-down")) 
            {
                jsMemExpandAnimate(true);
            } else {
                $('.js-closeview ').css('display', 'none');
                jsMemExpandAnimate(false);
            }
        });
        $("ul.tabs li").click(function(e) {
            if (!$(this).hasClass("active")) {
                $("ul.tabs li.active").removeClass("active");
                $(this).addClass("active");
                var m = $(this).attr('mainMemTab'),d = $("#tab_"+m+" .durSel.plansel").attr("mainMemDur");
                managePriceStrike(m,d);
                createCookie('mainMemTab', m);
                createCookie('mainMem', m);
                createCookie('mainMemDur', d);
                var tabNum = $(this).index(),getTabId = $(this).attr('id');
                changeTabContent(getTabId, tabNum, 200);
            }
        });
        $("#comparePopup").click(function(e){
            $('header').before("<div class='overlay1'></div>");
            $('#cmpplan').removeClass('disp-none');
        });
        $("#compareCloseBtn").click(function(e){
            $('.overlay1').remove();
            $('#cmpplan').addClass('disp-none');
        });
        $(".jsxDur").click(function(e){
            $(".jsxDur.active").removeClass('active');
            $(this).addClass('active');
            var m = $(this).attr("mainMem"),d = $(this).attr("mainMemDur"),c = $(this).attr("mainMemContact");
            changeMemCookie(m,d,c);
            managePriceStrike(m,d);
        });
        $(".durSel").click(function(e){
            var m = $(this).attr("mainMem"),d = $(this).attr("mainMemDur"),c = $(this).attr("mainMemContact");
            $("#tab_"+m+" .durSel.plansel").removeClass('plansel');
            $(this).addClass('plansel');
            changeMemCookie(m,d,c);
            managePriceStrike(m,d);
        });
        $('.viewDurLink').click(function(e){
            $('.overlay1').delay(1250).remove();
            $('#cmpplan').addClass('disp-none');
            createCookie('mainMemTab', $(this).attr('viewDurLink'));
            $("ul.tabs li.active").removeClass('active');
            $("ul.tabs li[mainMemTab="+readCookie('mainMemTab')+"]").addClass('active');
            var tabNum = $("ul.tabs li.active").index(),getTabId = $("ul.tabs li.active").attr('id');
            changeTabContent(getTabId,tabNum, 200);
            $('#sliderContainer div').find('.plansel').each(function () {
                var m = $(this).attr('mainMem'),
                    d = $(this).attr('mainMemDur');
                managePriceStrike(m, d);
            });
        })
        $(".continueBtn").click(function(){
            if ($(this).attr('id') == 'mainServContinueBtn') 
            {
                var mainMemDurCookie = readCookie('mainMemDur'),selectedVasCookie = readCookie('selectedVas');
                if(checkEmptyOrNull(selectedVasCookie) && checkEmptyOrNull(mainMemDurCookie))
                {
                    var currentVas = selectedVasCookie,tempArr = currentVas.split(","),vasId = null;
                    if(tempArr.length > 0){
                        // remove all other vas which start with supplied character except currently selected
                        tempArr.forEach(function(item, index){
                            if(item.substring(0, 1) == "M"){
                                if(mainMemDurCookie != item.substring(0, 1)){
                                    tempArr.splice(index, 1);
                                    if(mainMemDurCookie == "L"){
                                        vasId = "M12";
                                    } else {
                                        vasId = "M"+mainMemDurCookie;
                                    }
                                    if (!checkEmptyOrNull(mainMemDurCookie)) {
                                        vasId = "M3";
                                    }
                                }
                            }
                        });
                    }
                    if(vasId){
                        tempArr.push(vasId);
                    }
                    currentVas = tempArr.join(",");
                    createCookie('selectedVas', currentVas, 0);
                }
                var mainMemCookie = readCookie('mainMem');
                if(!checkEmptyOrNull(mainMemCookie) || mainMemCookie == "X"){
                    var currentMainSel = $(".planlist li.active").attr('mainMemTab'),m = currentMainSel,d = $('#tab_'+m+' .durSel.plansel').attr("mainMemDur"),c = $('#tab_'+m+' .durSel.plansel').attr("mainMemContact");
                    changeMemCookie(m,d,c);
                }
                mainMemCookie = readCookie('mainMem');
                if (checkEmptyOrNull(readCookie('mainMem')) && readCookie('mainMem') != "ESP" ) {
                    selectedVasCookie = readCookie('selectedVas');
                    if(checkEmptyOrNull(selectedVasCookie)){
                        $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMemCookie, 'mainMemDur':readCookie('mainMemDur'), 'selectedVas':selectedVasCookie, 'device':'desktop'});
                    }
                    else{
                        $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMemCookie, 'mainMemDur':readCookie('mainMemDur'), 'device':'desktop'});
                    }
                } else {
                    $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMemCookie, 'mainMemDur':readCookie('mainMemDur'), 'device':'desktop'});
                }
            } else {
                var mainMemCookie = readCookie('mainMem');
                if(!checkEmptyOrNull(mainMemCookie) || mainMemCookie != "X"){
                    var currentXSel = $(".jsxDur.active"),m = $(currentXSel).attr("mainMem"),d = $(currentXSel).attr("mainMemDur"),c = $(currentXSel).attr("mainMemContact");
                    changeMemCookie(m,d,c);
                }
                mainMemCookie = readCookie('mainMem');
                $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMemCookie, 'mainMemDur':readCookie('mainMemDur'), 'device':'desktop'});
            }
        });
        $(".durationTextPlaceholder").each(function(){
            var str = $(this).text();
            if(str.indexOf('FREE') >= 0){
                str = str.toString();
                str = str.replace("FREE","<p class='f13 colr5'>FREE</p>");
                $(this).html(str);
            }
        });
        
        $(".astroAddon").each(function(k,v){
            dur = $(v).attr("dur");
            if($.inArray(dur, astroDurations) === -1){
                astroDurations.push(dur);
            }
        });
        
        $(".planlist .tabs li").each(function(k,v){
            tab = $(v).attr("mainmemtab");
            customCheckboxAstro("MONTHASTRO"+tab+"[]");
        });
        evaluateVasToBeClicked();
        $(".evalVas").click(function(){
            evaluateVasToBeClicked();
        });
    ~/if`
    ~if $data.upgradeMembershipContent`
        //initilize upgrade page
        initializeUpgradePage();
        $("#upgradeMainMemBtn").click(function(e){
            //flush vas selection when upgrade button clicked
            eraseCookie('selectedVas');
            var upgradeType = "~$data.upgradeMembershipContent.type`",mainMem = "~$data.upgradeMembershipContent.upgradeMainMem`",mainMemDur = "~$data.upgradeMembershipContent.upgradeMainMemDur`";
            createCookie('mainMemTab', mainMem);
            createCookie('mainMem', mainMem);
            createCookie('mainMemDur', mainMemDur);
            $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMem, 'mainMemDur':mainMemDur, 'device':'desktop' , 'upgradeMem':upgradeType});
        }); 
    ~/if`
    ~if $data.vasContent`
        eraseCookie('paymentMode');
        eraseCookie('cardType');
        eraseCookie('couponID');
        eraseCookie('mainMem');
        eraseCookie('mainMemDur');
        checkLogoutCase(profileid);
        var selectedVasCookie = readCookie('selectedVas');
        if(pageType != 'upgradeMembershipPage'){
            if(selectedVasCookie && checkEmptyOrNull(selectedVasCookie)){
                updateAlreadySelectedVas();
            }
            $(".vascell").click(function(e){
                var that = this;
                $(this).parent().find('.vascell').each(function(){
                    if($(this).hasClass('mem-vas-active') && this!=that){
                        $(this).removeClass('mem-vas-active');
                    }
                });
                if($(that).hasClass('mem-vas-active')){
                    $(that).removeClass('mem-vas-active');
                } else {
                    $(that).addClass('mem-vas-active');
                }
                trackVasCookie($(that).attr("vasKey"), $(that).attr("id"));
                manageVasOverlay($(that).attr("vasKey"));
                updateVasPageCart();
            });
            $('.vasoverlay,.vasoverlay2').click(function(e){
                var vasKey = $(this).parent().attr('id').replace('_overlay',''),vasId;
                $("#"+vasKey+" .vascell").each(function(e){
                    if($(this).hasClass('mem-vas-active')){
                        vasId = $(this).attr('id');
                        $(this).removeClass('mem-vas-active');
                    }
                });
                manageVasOverlay(vasKey);
                trackVasCookie(vasKey,vasId);
                updateVasPageCart();
            });
            $("#payNowBtn").click(function(e){
                var selectedVasCookie = readCookie('selectedVas');
                
                if(parseInt($("#totalPrice").html()) > 0 && checkEmptyOrNull(selectedVasCookie)){
                    $.redirectPost('/membership/jspc', {'displayPage':3, 'selectedVas':selectedVasCookie, 'device':'desktop'});
                } else {
                    e.preventDefault();
                    //sweetAlert("Hi !", "Please select atleast one item to continue", "error");
                }
            });
            updateVasPageCart();
            var ScreenHgt = $(window).height(),ScreenWid = $(window).width(),leftval = (ScreenWid / 2) - 450;
            $('#cmpplan').css('left', leftval);
        }
    ~/if`
    });
</script>