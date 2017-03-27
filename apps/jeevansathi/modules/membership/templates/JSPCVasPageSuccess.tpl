<script type="text/javascript">
    var profileid = "~$profileid`";
    var tabVal = 2;
    var currency = "~$data.currency`";
    var pageType = 'vasPage';
    var preFilledEmail = "~$data.userDetails.EMAIL`";
    var preFilledMobNo = "~$data.userDetails.PHONE_MOB`";
    var helpAllStr;
    var bannerMsg;
    var bannerTimeout;
    var showCountdown;
    var countdown;
    var topBlockMsg;
    var topBlockCLT;
    var topBlockCLN;
    var topBlockDays;
    var topBlockMonths;
    var vasNames;
    var paidBenefits;
    var openedCount;
    var filteredVasServices = "~$data.filteredVasServices`";
    var skipVasPageMembershipBased = JSON.parse("~$data.skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
    var preSelectVasGlobal = "~$data.preSelectVasGlobal`";
</script>
~include_partial('global/JSPC/_jspcCommonMemRegHeader',[pageName=>'membership'])`
<!--start:plan-->
<div class="bg-4">
    <div class="container mainwid pt40">
        <!--start:title-->
        <div class="fullwid mem-brd3">
            <span class="f28 color11 fontrobbold disp_ib mem-brd4 mem_pad3 pb10"> Select Value Added Services </span>
            <div id="skipVasBtn" class="cursp fr color11 f19 fontreg mt10~if $smarty.foreach.servicesLoop.total gt 4`pt22~/if`">Skip</div>
        </div>
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
                                <div class="cursp vasoverlay"></div>
                                <div class="cursp vasoverlay2 fullwid">
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
                    <div class="fontthin f20 opa60">Review your order</div>
                    <!--start:sel VAS-->
                    <div class="pt25">
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
                        <div id="payNowBtn" class="fullwid txtc lh50 pinkRipple hoverPink">
                            <span>~if $data.currency eq '$'`USD~else`~$data.currency`~/if`&nbsp;</span>
                            <span id="totalPrice"></span>&nbsp;|&nbsp;<span class="colrw">Pay Now</span>
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
~include_partial('global/JSPC/_jspcCommonFooter')`
<script type="text/javascript">
    $(window).load(function() {
        eraseCookie('paymentMode');
        eraseCookie('cardType');
        eraseCookie('couponID');
        checkLogoutCase(profileid);
        if(!checkEmptyOrNull(readCookie('selectedVas'))){
            preSelectVas();
        }
        if(readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))){
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
            var vasKey = $(this).parent().attr('id').replace('_overlay','');
            var vasId;
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
        $("#skipVasBtn").click(function(){
            eraseCookie('selectedVas');
            $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':readCookie('mainMem'), 'mainMemDur':readCookie('mainMemDur'), 'device':'desktop'});
        });
        $("#payNowBtn").click(function(e){
            if(parseInt($("#totalPrice").html()) > 0){
                if(checkEmptyOrNull(readCookie('selectedVas'))){
                    $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':readCookie('mainMem'), 'mainMemDur':readCookie('mainMemDur'), 'selectedVas':readCookie('selectedVas'), 'device':'desktop'});
                } else {
                    $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':readCookie('mainMem'), 'mainMemDur':readCookie('mainMemDur'), 'device':'desktop'});
                }
            } else {
                e.preventDefault();
                //sweetAlert("Hi !", "Please select atleast one item to continue", "error");
            }
        });
        $('#changeMainPlan').click(function(){
            $.redirectPost('/membership/jspc', {'displayPage':1, 'device':'desktop'});
        });
        updateVasPageCart();
    });
</script>