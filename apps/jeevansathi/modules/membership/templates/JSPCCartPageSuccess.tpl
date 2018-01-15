<script type="text/javascript">
    $('body').addClass('hasJS');
    var profileid = "~$profileid`";
    var tabVal = 3;
    var currency = "~$data.currency`";
    var pageType = 'cartPage';
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
    var skipVasPageMembershipBased = JSON.parse("~$data.skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
    
</script>
~include_partial('global/JSPC/_jspcCommonMemRegHeader',[pageName=>'membership'])`
<!--start:plan-->
<div class="bg-4">
    <div class="container mainwid">
        <!--start:div-->
        <div class="clearfix pt20 pb20">
            <!--start:VAS-->
            <div class="fl mr20 mem-widp1">
                <div class="fullwid bg-white">
                    <div class="mem_pad24">
                        <!--start:accordian-->
                        <div class="accordion fontmed">
                            ~foreach from=$data.paymentOptionsData.payment_options key=k item=v name=paymentOptionsLoop`
                            <div class="accordion-section">
                                <div class="mem_pad25"> <a class="accordion-section-title disp_ib icons memacc-notsel pl30" href="#accordion-~$v.mode_id`" paymentSel="~$v.mode_id`">~$v.name`</a> </div>
                                <div id="accordion-~$v.mode_id`" class="accordion-section-content">
                                    <div class="fullwid clearfix pos-rel">
                                        <div class="color12 fontlig f15 lh20 pos-abs" style="top:-40px; left:120px"> Yes, we have a 100% safe & secure payment gateway.</div>
                                        <!--start:left-->
                                        ~if $v.mode_id eq "CR"`
                                        <div id="CR-iconList">
                                            <div class="fullwid acc_list_box">
                                                <p class="txtc fontlig f12 pt15">Top Credit Cards</p>
                                                <ul class="memul clearfix memnp1 txtc">
                                                    <li id="CR-1">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-ame-exp mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="CR-2">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-mstr-card mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="CR-3">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-visa mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!-- <li id="CR-4">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-dinersclub mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="CR-5">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-united mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="CR-6">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-rupay mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li> -->
                                                </ul>
                                            </div>
                                        </div>
                                        ~/if`
                                        ~if $v.mode_id eq "DR"`
                                        <div id="DR-iconList">
                                            <div class="fullwid acc_list_box">
                                                <p class="txtc fontlig f12 pt15">Top Debit Cards</p>
                                                <ul class="memul clearfix memnp1 txtc">
                                                    <!-- <li id="DR-1">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-ame-exp mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li> -->
                                                    <li id="DR-1">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-mstr-card mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="DR-2">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-visa mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="DR-3">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-mastreo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="DR-4">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-rupay mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        ~/if`
                                        ~if $v.mode_id eq "NB"`
                                        <div id="NB-iconList">
                                            <div class="fullwid acc_list_box">
                                                <p class="txtc fontlig f12 pt15">Top Banks</p>
                                                <ul class="memul clearfix memnp1 txtc">
                                                    <li id="NB-1">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite mem-sbi mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="NB-2">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite icicilogo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="NB-3">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite hdfclogo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="NB-4">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite axislogo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="NB-5">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite citilogo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li id="NB-6">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite kotaklogo mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        ~/if`
                                        ~if $v.mode_id eq "CSH"`
                                        <div id="CSH-iconList">
                                            <div class="fullwid acc_list_box">
                                                <p class="txtc fontlig f12 pt15">Top Wallets</p>
                                                <ul class="memul clearfix memnp1 txtc">
                                                    ~foreach from=$v.payment_options key=kk item=vv name=cardLoop`
                                                    <li id="CSH-~$kk+1`">
                                                        <div class="memn-nosel cursp">
                                                            <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                                <div class="disp-cell vmid">
                                                                    <div class="mem-sprite ~if $vv.ic_id eq 'rv2_mobiwik'`mobikwiklogo~else`paytmlogo~/if` mauto"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    ~/foreach`
                                                </ul>
                                            </div>
                                        </div>
                                        ~/if`
                                        ~if $v.mode_id eq "PP"`
                                        <div class="pos-abs mem-pos8">
                                            <div class="mem_pad26 wid280 acc_list_box">
                                                <ul class="hor_list pl17 clearfix">
                                                    <li class="mr10">
                                                        <div class="mem-boxdim mem-bdr13 disp-tbl">
                                                            <div class="disp-cell vmid">
                                                                <div class="mem-sprite paypallogo mauto"></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        ~/if`
                                        <div class="wid50p">
                                            <div class="pt40 pos-rel ~$v.mode_id`_width">
                                                <div class="pos-rel fontlig">
                                                    <select name="paymentOption_~$v.mode_id`" class="custom">
                                                        <option>Select ~$v.name`</option>
                                                        ~foreach from=$v.payment_options key=kk item=vv name=cardLoop`
                                                        <option paymentMode="~$v.mode_id`" cardType="~$vv.mode_option_id`" id="~$v.mode_id`~$vv.ic_id`" value="~$k`">~$vv.name`</option>
                                                        ~/foreach`
                                                    </select>
                                                </div>
                                                <!--end:city-->
                                                <div class="clr"></div>
                                            </div>
                                            <div class="hgt55"></div>
                                        </div>
                                        <!--end:left-->
                                    </div>
                                </div>
                                <!--end accordion-section-content-->
                            </div>
                            ~/foreach`
                            ~if $data.paymentOptionsData.cash_cheque_pickup`
                            <div class="accordion-section">
                                <div class="mem_pad25"> <a class="accordion-section-title disp_ib icons memacc-notsel pl30" href="#accordion-4" id="cashPickUp">Cash/Cheque pick-up <span class="color12">(No charges)</span></a> </div>
                                <div id="accordion-4" class="accordion-section-content">
                                    <div class="fullwid clearfix pos-rel">
                                        <!--start:left-->
                                        <div class="fl wid50p">
                                            <div class="color12 fontlig f15 lh20"> Membership will get activated once Jeevansathi executive collects <span class="fontreg"><span>~$data.currency`</span><span id="cashPickUpPrice">~$data.cart_price`</span></span> from you</div>
                                            <!--start:form-->
                                            <div class="pt40 formmem-one fontlig" id="chqpickform">
                                                <!--start:city-->
                                                <label>City</label>
                                                <div class="pos-rel fontlig pb20 cashDrop_width">
                                                    <select name="cashCity" class="custom" id="cashCity">
                                                        <option>Select City</option>
                                                        ~foreach from=$data.chequeData.options[3].input_data key=k item=v name=cashDataLoop`
                                                        <option value="~$v.name`" ~if $v.name eq $data.payAtBranchesData.userCityRes` selected ~/if` id="~$v.name`">~$v.name`</option>
                                                        ~/foreach`
                                                    </select>
                                                </div>
                                                <div id="cityError" class="disp-none f13 pt17" style="color:#d9475c !important;">Please select a City</div>
                                                <!--end:city-->
                                                <!--start:Address-->
                                                <div class="pos-rel pt30">
                                                    <label id="labelAddress">Address</label>
                                                    <div class="mem-bdr12 f17 pb5 pt10">
                                                        <input type="text" class="fullwid brdr-0 f17 color11 fontlig" value="~$data.userDetails.CONTACT`" id="chequePickUpAddress"/>
                                                    </div>
                                                    <div id="addressError" class="disp-none f13 pt5" style="color:#d9475c !important;">Please enter a valid Address</div>
                                                </div>
                                                <!--end:Address-->
                                                <!--start:name-->
                                                <div class="pos-rel pt20">
                                                    <label id="labelName">Name</label>
                                                    <div class="mem-bdr12 f17 pb5 pt10">
                                                        <input type="text" class="fullwid brdr-0 f17 color11 fontlig" ~if $data.username` value="~$data.username`" ~else`  value="~$data.userDetails.USERNAME`" ~/if` id="chequePickUpName"/>
                                                    </div>
                                                    <div id="nameError" class="disp-none f13 pt5" style="color:#d9475c !important;">Please enter a valid Name</div>
                                                </div>
                                                <!--end:name-->
                                                <!--start:Mobile number-->
                                                <div class="pos-rel pt20">
                                                    <label id="labelMobile">Mobile Number</label>
                                                    <div class="mem-bdr12 f17 pb5 pt10">
                                                        <input type="text" class="fullwid brdr-0 f17 color11 color11 fontlig" value="~$data.userDetails.PHONE_MOB`" id="chequePickUpMobile"/>
                                                    </div>
                                                    <div id="mobileError" class="disp-none f13 pt5" style="color:#d9475c !important;">Please enter a valid Mobile Number</div>
                                                </div>
                                                <!--end:Mobile number-->
                                                <!--start:Phone number-->
                                                <div class="pos-rel pt20">
                                                    <label id="labelPhone">Phone Number</label>
                                                    <div class="mem-bdr12 f17 pb5 pt10">
                                                        <input type="text" class="fullwid brdr-0 f17 color11 fontlig" value="~$data.userDetails.PHONE_RES`" id="chequePickUpPhone"/>
                                                    </div>
                                                    <div id="phoneError" class="disp-none f13 pt5" style="color:#d9475c !important;">Please enter a valid Phone Number</div>
                                                </div>
                                                <!--end:Phone number-->
                                                <!--start:Preferred Date-->
                                                <div class="fontlig pt20">
                                                    <label>Preferred Date</label>
                                                    <div class="f17 pt10 clearfix color11 pos-rel">
                                                    <div class='prefDate_width'>
                                                        <div class="wid29p fl mr20 pb5 cursp pos-rel fontlig pb20">
                                                        <select name="preferredDateDay" class="custom" id="preferredDateDay"></select>
                                                    </div>
                                                    </div>
                                                    <div class="wid29p fl mr20 pb5 cursp pos-rel fontlig pb20">
                                                    <select name="preferredDateMonth" class="custom" id="preferredDateMonth"></select>
                                                </div>
                                                <div class="wid29p fl pb5 cursp pos-rel fontlig pb20">
                                                <select name="preferredDateYear" class="custom" id="preferredDateYear"></select>
                                            </div>
                                            <div class="pos-abs mem-pos9">
                                                <div class="txtc color12 mem_pad26 f13">Pick up request takes 48 hours to execute</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--start:comments-->
                                    <div class="pos-rel pt20">
                                        <label>Comments</label>
                                        <div class="mem-bdr12 f17 pb5 pt10">
                                            <input type="text" class="fullwid brdr-0 f17 color11 fontlig" value="" id="chequePickUpComments"/>
                                        </div>
                                    </div>
                                    <!--end:comments-->
                                </div>
                                <!--end:form-->
                            </div>
                            <!--end:left-->
                        </div>
                        <div class="hgt55"></div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                ~/if`
                <!--end .accordion-section-->
                ~if $data.payAtBranchesData`
                <div class="accordion-section">
                    <div class="mem_pad25"> <a id="payAtBranches" class="accordion-section-title disp_ib icons memacc-notsel pl30" href="#accordion-5">Pay at Jeevansathi Branches</a> </div>
                    <div id="accordion-5" class="accordion-section-content">
                        <div class="fullwid clearfix pos-rel">
                            <!--start:left-->
                            <div class="fl wid50p">
                                <div class="color12 fontlig f15 lh20"> Your subscription will get activated within 2 working days from receipt of amount <span class="fontreg"><span>~$data.currency`</span><span id="payAtBranchesPrice">~$data.cart_price`</span></span></div>
                                <!--start:form-->
                                <div class="pb14 pt40 formmem-one fontlig">
                                    <!--start:city-->
                                    <label>City</label>
                                    <div class="pos-rel fontlig branch_width">
                                        <select name="City" class="custom" id="city">
                                            <option>Select City</option>
                                            ~foreach from=$data.payAtBranchesData.branches_data key=k item=v name=payAtBranchesLoop`
                                            <option value="~$k`" ~if $k eq $data.payAtBranchesData.userCityRes` selected ~/if` >~$k`</option>
                                            ~/foreach`
                                        </select>
                                    </div>
                                    <!--end:city-->
                                    <div class="clr"></div>
                                    <!--start:address-->
                                    <div class="pt35 fontlig f13 colr2 lh20">
                                        <!--start:add1-->
                                        ~foreach from=$data.payAtBranchesData.branches_data key=k item=v name=payAtBranchesSelectedCity`
                                        ~foreach from=$v key=kk item=vv name=payAtBranchesSelectedDetails`
                                        <div class="branch ~if $k neq $data.payAtBranchesData.userCityRes` disp-none ~/if`" branch_id="~$k`_branch">
                                            <p class="fontreg">~$vv.NAME`</p>
                                            <p>CONTACT: ~$vv.CONTACT_PERSON`</p>
                                            <p>ADDRESS: ~$vv.ADDRESS`</p>
                                            <p>PHONE: ~$vv.PHONE` </p>
                                            <p>MOBILE: ~$vv.MOBILE`</p>
                                            <br><br>
                                        </div>
                                        ~/foreach`
                                        ~/foreach`
                                        <!--end:add1-->
                                    </div>
                                    <!--end:address-->
                                    <div class="pos-abs mem-pos8">
                                        <div class="fontreg f15 colr2">Sample Cheque</div>
                                        <div class="pos-rel cheque">
                                            <div class="pos-abs fullwid fontreg f13 colr2" style="top:0; left:0">
                                                <div class="chqp1"><span id="chequeDate"></span></div>
                                                <div class="chqp2">Jeevansathi Internet Services </div>
                                                <div class="chq3 lh22"><span id="amountInWords"></span></div>
                                            </div>
                                            <div class="pos-abs fontreg f13 colr2 chqp4">
                                                <div>
                                                  <span>~$data.currency`</span><span id="sampleChequePrice">~$data.cart_price`</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id='instructionsText' class="pt20 disp-none">
                                            <div class="fontreg f15 colr5">Important Instructions</div>
                                            <ul class="f13 mem-colr1 lh20 pt10 pl10 samplechq">
                                                <li>Do not forget to sign your cheque</li>
                                                <li>Mention ~$data.userDetails.USERNAME` on the reverse of Cheque/DD</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="hgt55"></div>
                                </div>
                                <!--end:form-->
                            </div>
                            <!--end:left-->
                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                ~/if`
                <!--end .accordion-section-->
            </div>
            <!--end:accordian-->
        </div>
    </div>
</div>
<!--end:VAS-->
<!--start:cart-->
<div class="fr mem-wid6 mem-widp2 mem_bg1">
    <div class="mem_pad19 colrw">
        <div class="fontthin f20 opa60">You are getting</div>
        <!--start:sel VAS-->
        <div class="pt25">
            <div class="lh23 f13 fontreg">
                ~if $data.cart_items.main_memberships`
                ~foreach from=$data.cart_items.main_memberships key=k item=v name=mainServLoop`
                <div class="disp-tbl fullwid">
                    <div class="disp-cell wid80p pos-rel">~$v.service_name`</div>
                    <div class="disp-cell txtr wid20p cart_prices">~$v.orig_price_formatted`</div>
                </div>
                ~/foreach`
                ~/if`
                ~if $data.cart_items.vas_memberships`
                ~foreach from=$data.cart_items.vas_memberships key=k item=v name=vasServLoop`
                <div class="disp-tbl fullwid">
                    <div class="disp-cell wid80p pos-rel">~$v.service_name`</div>
                    <div class="disp-cell txtr wid20p cart_prices">~if $v.orig_price_formatted`~$v.orig_price_formatted`~else`0.00~/if`</div>
                </div>
                ~/foreach`
                ~/if`
            </div>
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
    <div class="mem_pad20 colrw fontlig">
        ~if $data.cart_discount`
        <div class="txtc f15 pb10" id="savingsContainer"> <span id="savingsText">Total Savings</span> <span class="fontreg"><span class="currency" id="currecncySymbol">~$data.currency`&nbsp;</span><span id="cartDiscount">~$data.cart_discount`</span></span></div>
        <div class="pt40 txtc">
            ~else`
            <div class="txtc f15 pb10 disp-none" id="savingsContainer"> <span id="savingsText">Total Savings</span> <span class="fontreg"><span class="currency" id="currecncySymbol">~$data.currency`&nbsp;</span><span id="cartDiscount"></span></span></div>
            <div class="pt40 txtc">
                ~/if`
                <div class="fontlig f15">You Pay</div>
                <div class="fontrobbold f36 pt10"><span>~$data.currency`&nbsp;</span><span id="undiscountedPrice">~$data.cart_price`</span></div>
                ~if $data.actual_total_price`
                <div class="fontreg f15 strike pt7" id="discountedPriceContainer"><span class="currency">~$data.currency`&nbsp;</span><span id="discountedPrice">~$data.actual_total_price`</span></div>
                ~else`
                <div class="fontreg f15 strike pt7 disp-none" id="discountedPriceContainer"><span class="currency">~$data.currency`&nbsp;</span><span id="discountedPrice"></span></div>
                ~/if`
            </div>
            ~if $data.paymentOptionsData.backendLink.fromBackend eq '1'`
            <div class="pb15 pt30 pos-rel"></div>
            ~else if $data.cart_items.main_memberships`
            <!--start:coupon code-->
            <div class="pb15 pt30 pos-rel">
                <div class="disp-tbl f13 fontlig colrw fullwid pb10 disp-none" id="applyCouponDiv">
                    <div class="disp-cell vmid wid70p mem-bdr11 appcpn">
                        <input type="text" class="mem-outline-none brdr-0 colrw f11 fullwid bgnone" placeholder="Have coupon? Enter here" value="" name="" id="couponCode">
                    </div>
                    <div class="disp-cell vmid txtl mem-widp7 cursp js-coupon pl10" id="couponApplyBtn">Apply</div>
                </div>
                <div class="f13 fontlig colrw fullwid pb7 disp-none" id="couponSuccessDiv">
                    <div class="disp-cell vmid mem-widp8 appcpn">
                        <span id="textcoup" class=""></span>
                        <input type="text" name="" value="" placeholder="" class="disp-none brdr-0 colrw f13"/>
                    </div>
                    <div class="disp-cell vmid txtr mem-widp7 cursp js-coupon"><i class="mem-sprite mem-check2"></i></div>
                </div>
                <div class="mem_bgwh fullwid mem-hgt6"></div>
                <div class="pos-abs leftcorner_trianle mem-pos5"></div>
            </div>
            <!--end:coupon code-->
            ~else`
            <div class="pb15 pt30 pos-rel"></div>
            ~/if`
            <div id="noOptionSelected" class="disp-none txtc color5 fontreg f12 pb15">Please select a payment option</div>
            <div style="overflow:hidden;position: relative;">
            <div id="payNowBtn" class="fullwid bg_greyed txtc lh50"><span>~if $data.currency eq '$'`USD~else`~$data.currency`~/if`&nbsp;</span><span id="finalCartPrice">~$data.cart_price`&nbsp;|&nbsp;</span><span class="colrw" id="cartPaymentSpan">Pay Now</span></div>
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
</div>
<!--end:plan-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<script type="text/javascript">
    $(window).load(function() {
        ~if $data.paymentOptionsData.backendLink.fromBackend eq '1'`
            eraseCookie('mainMem');
            eraseCookie('mainMemDur');
            eraseCookie('selectedVas');
            ~if $data.subscription_id`
            createCookie('mainMem', '~$data.subscription_id`', 0);
            ~/if`
            ~if $data.subscription_duration`
            createCookie('mainMemDur', '~$data.subscription_duration`', 0);
            ~/if`
            ~if $data.paymentOptionsData.tracking_params.vasImpression`
                ~if $data.subscription_id neq 'ESP' and $data.subscription_id neq 'NCP'`
                    createCookie('selectedVas', '~$data.paymentOptionsData.tracking_params.vasImpression`', 0);
                ~/if`
            ~/if`
            if(window.top.location.href != window.location.href){
                window.top.location.href = window.location.href;
            }
        ~/if`
        eraseCookie('paymentMode');
        eraseCookie('cardType');
        eraseCookie('couponID');
        var couponAjaxResponse = 0;
        ~if $data.paymentOptionsData.cash_cheque_pickup`
        var day=new Array(),month=new Array(),year=new Array();
        ~foreach from=$data.chequeData.options[5].input_data key=k item=v name=dateLoop`
            var s="~$v.name`".split(" ");
            day.push(s[0]);month.push(s[1]);year.push(s[2]);
            month = $.unique(month);
            year = $.unique(year);
        ~/foreach`
        showPreferredDatesDropDown(day, month, year);
        ~/if`
        payAtBranchesTransition();
        setCouponCodeField("");
        var amount = parseFloat("~$data.cart_price`".replace(',', ''));
        displayChequeAmount(amount);
        displayChequeDate();
        var sb = new Array();
        $("select.custom").each(function() {
            sb[$(this).attr('name')] = new SelectBox({
                selectbox: $(this),
                height: 250,
                width: '100%',
                customScrollbar: true,
                changeCallback: function(e) {
                    payAtBranchesTransition();
                    var type = $("a.accordion-section-title.active").attr('id');
                    if(type != 'cashPickUp'){
                        var response = manageSelectedItem();
                        if(response){
                            enablePayNowButtonCartPage();
                        } else {
                            disablePayNowButtonCartPage();
                        }
                    }
                }
            });
        });
        $("a.accordion-section-title").click(function(e){
            // Grab current anchor value
            var currentAttrValue = $(this).attr('href');
            var currentTabName = $(this).html();
            var paymentOpt = $(this).attr('paymentSel');
            clearSelectedIcons(paymentOpt);
            if ($(e.target).is('.active')) {
                if ($(this).attr('id') == 'cashPickUp') {
                    eraseCookie('paymentMode');
                    eraseCookie('cardType');
                    disablePayNowButtonCartPage();
                }                
                close_accordion_section();
            } else {
                close_accordion_section();
                // Add active class to section title
                $(this).addClass('active');
                // Open up the hidden content panel
                $('.accordion ' + currentAttrValue).slideDown().addClass('open');
            }
            if (checkEmptyOrNull(paymentOpt)) {
                $("#accordion-" + paymentOpt + " .jspScrollable dd.itm-0").trigger('click');
                $("#accordion-" + paymentOpt + " .defaultScrollbar dd.itm-0").trigger('click');
                $("#accordion-" + paymentOpt).find('.selectedValue').html('Select '+currentTabName);
                eraseCookie('paymentMode');
                eraseCookie('cardType');
            }
            e.preventDefault();
            manageCartPaymentButtonTextChange();
        });
        $("div[id*=-iconList] li").on('click', function(e){
            var paymentOpt = $(this).attr('id');
            paymentOpt = paymentOpt.split("-");
            var thisAccordion = sb["paymentOption_"+paymentOpt[0]];
            thisAccordion.open();
            thisAccordion.jumpToIndex(paymentOpt[1]);
            $("#accordion-" + paymentOpt[0] + " .jspScrollable dd.itm-"+paymentOpt[1]).trigger('click');
            $("#accordion-" + paymentOpt[0] + " .defaultScrollbar dd.itm-"+paymentOpt[1]).trigger('click');
        });
        var scrollPos;
        ~if $data.payAtBranchesData.userCityRes`
            var cityRes = "~$data.payAtBranchesData.userCityRes`";
            cityRes = cityRes.replace(/([!@#$%^&*()_+={}\[\]\|\\:;'<>,.\/? ])+/g, '').replace(/^(-)+|(-)+$/g,'');
            scrollPos = $("#city").find('#'+cityRes).offset();
            if(scrollPos)
            {
                $('html','body').animate({
                    scrollTop:scrollPos.top
                },0);
            }
        ~/if`
        $("#couponCode").focusin(function(){
            if($(this).hasClass("colr5")){
                $(this).val("");
            }
            $("#couponCode").removeClass("colr5");
            $(this).removeAttr("placeholder");
        });
        $("#couponCode").focusout(function(){
            if(!$(this).val())
                $(this).attr("placeholder","Have coupon? Enter here");
        });
        $("#couponApplyBtn").click(function(){
            var couponID=$("#couponCode").val().replace(/^\s+|\s+$/g,'');
            var paramStr='validateCoupon=1&couponID='+couponID+'&serviceID='+readCookie('mainMem')+readCookie('mainMemDur');
            var priceToBeDiscouted=0,originalPrice=0;
            priceToBeDiscouted="~$data.cart_price`";
            ~if $data.actual_total_price`
                originalPrice="~$data.actual_total_price`";
            ~else`
                originalPrice=priceToBeDiscouted;
            ~/if`
            applyCouponOnCart(couponID,paramStr,priceToBeDiscouted,originalPrice);
        });
        
        $("#payNowBtn").click(function(e){
            if($("#cashPickUp").hasClass("active")){
                var isValid=validateCashPickupForm();
                if(isValid){
                    var mainMembership = readCookie('mainMem')+readCookie('mainMemDur');
                    var vasImpression = readCookie('selectedVas');
                    var name = encodeURIComponent($("#chequePickUpName").val().replace(/^\s+|\s+$/g, ''));
                    var landline = encodeURIComponent($("#chequePickUpPhone").val().replace(/^\s+|\s+$/g, ''));
                    var mobile = encodeURIComponent($("#chequePickUpMobile").val().replace(/^\s+|\s+$/g, ''));
                    var address = encodeURIComponent($("#chequePickUpAddress").val().replace(/^\s+|\s+$/g, ''));
                    var comment = encodeURIComponent($("#chequePickUpComments").val().replace(/^\s+|\s+$/g, ''));
                    var city = encodeURIComponent($("#select-cashCity .selectedValue").text());
                    var month = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf($("#select-preferredDateMonth .selectedValue").text()) / 3 + 1;
                    var date = $("#select-preferredDateYear .selectedValue").text()+"-"+pad(month,2)+"-"+$("#select-preferredDateDay .selectedValue").text()+" 00:00:00";
                    //var match = date.match(/^(\d+)-(\d+)-(\d+) (\d+)\:(\d+)\:(\d+)$/);
                    //date = new Date(match[1], match[2] - 1, match[3], match[4], match[5], match[6]);
                    //date = date.getTime() / 1000;
                    var paramStr = 'pickupRequest=1' + '&name=' + name + '&landline=' + landline + '&mobile=' + mobile + '&address=' + address + '&comment=' + comment + '&city=' + city + '&date=' + date + "&device=desktop" + "&mainMembership=" + mainMembership + "&vasImpression=" + vasImpression + "&couponID=" + readCookie('couponID');
                    paramStr = paramStr.replace(/amp;/g, '');
                    url = "/api/v3/membership/membershipDetails?" + paramStr;
                    $.myObj.ajax({
                        type: 'POST',
                        url: url,
                        success: function(data) {
                            response = data;
                            if (data.status == 1) {
                                $.redirectPost('/membership/jspc', {'displayPage':7, 'mainMembership':mainMembership, 'vasImpression':readCookie('selectedVas'), 'profileid':"~$profileid`", 'device':'desktop'});
                            }
                        }
                    });
                }
            }
            if (checkEmptyOrNull(readCookie('paymentMode')) && checkEmptyOrNull(readCookie('cardType'))) {
                ~if $data.paymentOptionsData.backendLink.fromBackend eq '1'`
                    if (checkEmptyOrNull(readCookie('mainMem')) && checkEmptyOrNull(readCookie('mainMemDur'))) {
                        if (checkEmptyOrNull(readCookie('selectedVas'))) {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':readCookie('selectedVas'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'), 'backendRedirect':1, 'fromBackend':1, 'checksum':"~$data.paymentOptionsData.backendLink.checksum`", 'profilechecksum':"~$data.paymentOptionsData.backendLink.profilechecksum`", 'reqid':"~$data.paymentOptionsData.backendLink.reqid`",'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        } else {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':'', 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'), 'backendRedirect':1, 'fromBackend':1, 'checksum':"~$data.paymentOptionsData.backendLink.checksum`", 'profilechecksum':"~$data.paymentOptionsData.backendLink.profilechecksum`", 'reqid':"~$data.paymentOptionsData.backendLink.reqid`",'userProfile':"~$data.paymentOptionsData.userProfile`"});    
                        }
                    } else if (checkEmptyOrNull(readCookie('selectedVas'))) {
                        $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':'', 'vasImpression':'', 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'), 'backendRedirect':1, 'fromBackend':1, 'checksum':"~$data.paymentOptionsData.backendLink.checksum`", 'profilechecksum':"~$data.paymentOptionsData.backendLink.profilechecksum`", 'reqid':"~$data.paymentOptionsData.backendLink.reqid`",'userProfile':"~$data.paymentOptionsData.userProfile`"});
                    }
                ~else`
                if (checkEmptyOrNull(readCookie('mainMem')) && checkEmptyOrNull(readCookie('mainMemDur'))) {
                    if($.inArray(readCookie('mainMem'),skipVasPageMembershipBased)>-1) {
                        if (checkEmptyOrNull(readCookie('couponID'))) {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':'', 'couponID':readCookie('couponID'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        } else {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':'', 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        }
                    } else if (checkEmptyOrNull(readCookie('selectedVas'))) {
                        if (checkEmptyOrNull(readCookie('couponID'))) {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':readCookie('selectedVas'), 'couponID':readCookie('couponID'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        } else {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':readCookie('selectedVas'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        }
                    } else {
                        if (checkEmptyOrNull(readCookie('couponID'))) {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':'', 'couponID':readCookie('couponID'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        } else {
                            $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':readCookie('mainMem')+readCookie('mainMemDur'), 'vasImpression':'', 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                        }
                    }
                } else if (checkEmptyOrNull(readCookie('selectedVas'))) {
                    if (checkEmptyOrNull(readCookie('couponID'))) {
                        $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':'', 'vasImpression':readCookie('selectedVas'), 'couponID':readCookie('couponID'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                    } else {
                        $.redirectPost('/api/v3/membership/membershipDetails', {'processPayment':1, 'mainMembership':'', 'vasImpression':readCookie('selectedVas'), 'device':'desktop', 'paymentMode':readCookie('paymentMode'), 'cardType':readCookie('cardType'),'userProfile':"~$data.paymentOptionsData.userProfile`"});
                    }
                }
                ~/if`
            } else {
                e.preventDefault();
                if($("#cashPickUp").hasClass("active") || $("#payAtBranches").hasClass('active')){
                    $('#noOptionSelected').addClass('disp-none');
                } else {
                    $('#noOptionSelected').removeClass('disp-none');
                }
            }
        });
    });
</script>