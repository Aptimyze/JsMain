<script type="text/javascript">
    var profileid = "~$profileid`";
    var currency = "~$data.currency`";
    var pageType = 'successPage';
    var preFilledEmail = "~$data.userDetails.EMAIL`";
    var preFilledMobNo = "~$data.userDetails.PHONE_MOB`";
    var helpAllStr;
    var tabVal;
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
    
</script>
~include_partial('global/JSPC/_jspcCommonMemRegHeader',[pageName=>'membership'])`
<div class="bg-4">
    <div class="container mainwid">
        <!--start:white container-->
        <div class="pt30 pb30">
            <div class="mem_pad21  bg-white">
                <div class="clearfix"> <i class="mem-sprite fl mem-heart1"></i>
                    <div class="fl fontreg colr5 f20 pl10 mem_mr2">~$data.message`</div>
                    <div class="fr fontlig f13 colr2">Order confirmation mail has been sent to <span class="fontreg">~$data.userDetails.EMAIL`</span></div>
                </div>
                <div class="clearfix pt30">
                    <!--start:left-->
                    <div class="fl mem-widp1">
                        <!--start:orderid-->
                        <div class="clearfix fullwid">
                            <div class="fl fontmed f17 colr4">Order ID</div>
                            <div class="fl mem-bdr14 f16 fontlig colr2 txtc ml10 mem-wid15">~$data.order_content.orderid`</div>
                        </div>
                        <!--end:orderid-->
                        <!--start:trancs-->
                        <div class="clearfix fullwid pt20">
                            <div class="fl fontmed f17 colr4">Transaction Date</div>
                            <div class="fl mem-bdr14 f16 fontlig colr2 ml10 mem-wid16"> <span class="disp_ib mem_pad30">~$data.order_content.transaction_date`</span> </div>
                        </div>
                        <!--end:trancs-->
                        <!--start:summary-->
                        <table cellspacing="0" cellpadding="0" class="disp-tbl mem-bdr15 fullwid mt30">
                            <tbody><tr>
                                <td class="f17 fontmed colr4  mem_pad31 mem-bdr14" colspan="2"> You just bought... </td>
                                <td class="17 fontmed colr4 mem_pad31 mem-bdr16 mem-bdr14"> Duration </td>
                            </tr>
                            <tr>
                                <td class="mem_pad31"><ul class="colr2 f15 fontlig vasopted lh26">
                                    ~if $data.order_content.membership_plan`
                                    <li>~$data.order_content.membership_plan` membership</li>
                                    ~/if`
                                    ~if $data.order_content.vas_services`
                                        ~foreach from=$data.order_content.vas_services key=k item=v name=vasLoop`
                                        <li>~$k`</li>
                                        ~/foreach`
                                    ~/if`
                                </ul></td>
                                <td>
                                    <div class="mauto mem-passwid">
                                        <div class="fullwid txtc mem-rotate mem-bdr17">
                                            <div class="fontrobbold f36 colr5">PAID</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="mem_pad31 mem-bdr16"><ul class="colr2 f15 fontlig vasopted lh26">
                                    ~if $data.order_content.duration`
                                    <li>~$data.order_content.duration`</li>
                                    ~/if`
                                    ~if $data.order_content.vas_services`
                                        ~foreach from=$data.order_content.vas_services key=k item=v name=vasLoop`
                                        <li>
                                            ~foreach from=$v key=kk item=vv name=vasdurationLoop`
                                                ~$vv`
                                            ~/foreach`
                                        </li>
                                        ~/foreach`
                                    ~/if`
                                </ul></td>
                            </tr>
                        </tbody></table>
                        <!--end:summary-->
                        <!--start:div-->
                        <div class="clearfix pt35">
                            <div class="fl fontmed f17 colr4">Amount Paid</div>
                            <div class="fl mem-bdr14 f16 fontlig colr2 ml10 wid150 txtc"><span>~if $data.currency eq '$'`USD~else`~$data.currency`~/if`</span> ~$data.order_content.amount`</div>
                            <div class="fr mem-bdr14 f16 fontlig colr2 ml10 mem-wid17 txtc" id="cardTypeValue"></div>
                            <div class="fr fontmed f17 colr4 ml10">Mode of Payment</div>
                        </div>
                        <!--end:div-->
                        <div class="pt20 f13 fontlig colr2">For any assistance call us at ~$data.number_label`</div>
                    </div>
                    <!--end:left-->
                    <!--start:right-->
                    <div class="fr mem-wid6 mem-widp2 mem_bg1 mt15">
                        <div class="mem_pad19 colrw fontlig">
                            <div class="txtc pt45 pb15">~$data.topBlockMessage.titleMessage`</div>
                            ~if $data.topBlockMessage.monthsValue eq 'Unlimited'`
                            <div class="mauto mem-wid14">
                                <div class="bg-white bdr-rad1 colr5 fullwid fontmed txtc f22 lh61">
                                    UNLIMITED
                                </div>
                                <div class="colrw f11 txtc fontlig pt10">DAYS</div>               
                            </div>
                            ~else`
                            <div class="mauto mem-wid180">
                                <div class="clearfix">
                                    <div class="fl">
                                        <div>
                                            <div class="mem-timerbg disp-tbl txtc">
                                                <div id="expMonths" class="disp-cell vmid colr5 f40 fontrobbold">~$data.topBlockMessage.monthsValue`</div>
                                            </div>
                                        </div>
                                        <div class="pt7 allcaps f11 txtc">~$data.topBlockMessage.monthsText`</div>
                                    </div>
                                    <div class="fl">
                                        <div>
                                            <div class="mem-timerbg disp-tbl txtc">
                                                <div id="expDays" class="disp-cell vmid colr5 f40 fontrobbold">~$data.topBlockMessage.daysValue`</div>
                                            </div>
                                        </div>
                                        <div class="pt7 allcaps f11 txtc">~$data.topBlockMessage.daysText`</div>
                                    </div>
                                </div>
                            </div>
                            ~/if`
                            ~if $data.topBlockMessage.contactsLeftText`
                            <div class="mem_pad29 txtc" style="margin-bottom: 40px;">
                                <p>~$data.topBlockMessage.contactsLeftText`</p>
                                <p class="pt10 fontrobbold f40">~$data.topBlockMessage.contactsLeftNumber`</p>
                            </div>
                            ~/if`
                            <div style="overflow:hidden;position: relative;">
                            <div class="fullwid bg_pink cursp txtc lh50 colrw pinkRipple hoverPink" id="getStartedBtn">Get Started</div>
                            </div>
                        </div>
                    </div>
                    <!--end:right-->
                </div>
            </div>
        </div>
        <!--end:white container-->
    </div>
</div>
~include_partial('global/JSPC/_jspcCommonFooter')`
<script type="text/javascript">
    $(window).load(function() {
        var cardType = readCookie('paymentMode');
        $("#cardTypeValue").text(setCardTypeField(cardType));
        eraseCookie('selectedVas');
        eraseCookie('mainMemDur');
        eraseCookie('mainMem');
        eraseCookie('paymentMode');
        eraseCookie('cardType');
        eraseCookie('couponID');
        $("#getStartedBtn").click(function(e){
            window.location.href = "/myjs/jspcPerform";
        });
    });
</script>