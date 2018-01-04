~include_partial('global/header',[pageName=>'membership'])`
<script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
<script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
<style type="text/css" >
    body {margin-top:0;}
</style>
<script type="text/javascript" >
    var user=new Object();
    var popular=new Array();
    var serviceInactive;
    ~foreach from=$popular key=k item=v`
    popular["~$k`"]="~$v`";
    ~/foreach`
    var showTable="~$showTable`";
    var tableToShow="~$tableToShow`";
    user.userType="~$userObj->userType`";
    user.memStatus="~$userObj->memStatus`";
    user.ipAddress="~$userObj->ipAddress`";
    user.currency="~$userObj->currency`";
    user.profileid="~$userObj->profileid`";
    serviceInactive="~$serviceInactive`";
</script>
<!--Main container starts here-->
<div id="main_cont">
    <div class="sp10 clear"></div>
    <div class="mem-tabs pos-rel">
        <div class="f17 pos-rel">
            <div>
                ~if $userObj->userType eq 5 or $userObj->userType eq 6 or $userObj->userType eq 7`
                ~assign var=countOnlyVAS value=0`
                ~foreach from=$subStatus item=v`
                ~if $v.LINK eq 'B' or $v.LINK eq 'Y' && $userObj->userType neq 7`
                Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
                ~else if $v.LINK eq 'N' && $userObj->userType eq 7 && $countOnlyVAS eq '0'`
                Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
                ~assign var=countOnlyVAS value=$countOnlyVAS+1`
                ~/if`
                ~/foreach`
            </div>
            <div style="padding: 2px 0px 5px;">
                <a id="subscriptions" style="text-decoration:underline;cursor:pointer;color:#0f7eab">See all your subscriptions</a>
            </div>
            ~else if $subStatus && $userObj->userType neq 1 && $userObj->userType neq 2`
            ~assign var=countCheck value=0`
            ~foreach from=$subStatus item=v`
            ~if $v.LINK eq 'N' && $userObj->userType neq 7 && $countCheck eq '0'`
            Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
            ~assign var=countCheck value=$countCheck+1`
            ~/if`
            ~/foreach`
        </div>
        <div style="padding: 2px 0px 5px;">
            <a id="subscriptions" style="text-decoration:underline;cursor:pointer;color:#0f7eab">See all your subscriptions</a>
        </div>
        ~/if`
        <div style="display:none;left:218px; top:-48px; z-index:999999; border-width:3px; border-color:#b2b2b2" class="pos-rel" id="lightbox">
            <i id="pointIndicator" class="ico-indicator2 sprte-mem fl">     &nbsp;</i>
            <div style="width:450px; padding-top:10px" class="container2 fl">
                <div class="fl" style="width:420px">
                    <div id="subscription-cont">
                        <strong>Your current subscriptions</strong>
                        <strong style="width:110px">Expires on</strong>
                        <ol>
                            ~foreach from=$subStatus item=v`
                            ~if $v.LINK neq 'N'`
                            <li style='float:none;'>
                                <div style="width:400px">
                                    <div style="display:inline-block;">~$v.SERVICE`</div>
                                    <div style="display:inline-block;width:120px">~$v.EXPIRY_DT`</div>
                                </div>
                            </li>
                            ~/if`
                            ~/foreach`
                            ~foreach from=$subStatus key=k item=v`
                            ~if $v.LINK eq 'N'`
                            <li style='float:none;'>
                                <div style="width:400px">
                                    <div style="display:inline-block">~$v.SERVICE`</div>
                                    <div style="display:inline-block;width:120px">~$v.EXPIRY_DT`</div>
                                </div>
                            </li>
                            ~/if`
                            ~/foreach`
                        </ol>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="btn-close fl">X</div>
            </div>
        </div>
    </div>
    <input type="hidden" name="selMemArray" id="selMemArray" value="~$selMemArr|@count`"/>
    <input type="hidden" name="activeTable" id="activeTable" value="~$activeTable`"/>
    ~if $bannerDisplay eq '1'`
    ~if $userObj->userType eq 6 or $userObj->userType eq 4`
    ~if $userObj->contactsRemaining eq '' or $userObj->contactsRemaining eq 0 or $userObj->userType eq 4`
    <div class="sp10 clear"></div>
    <div class="mem-holi2 fl">
        <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
            <div class="  fs22">
                Renew before
                <font class="maroon" style=" font-size:30px">~$userObj->expiryDate`</font> and get <span > <font class="maroon" style=" font-size:30px">~$renewalPercent`% OFF
                <font class="fs24 black">on all plans</font></font> </span>
            </div>
        </div>
    </div>
    ~else if $specialActive eq '1'`
    <!--Special Discount -->
    <div class="mem-holi2 fl" style="margin-top:5px;">
        <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
            <div class="  fs24">
                Buy before
                <font class="maroon" style=" font-size:30px">~$variable_discount_expiry`</font> and get ~$discountLimitTextVal` <span > <font class="maroon" style=" font-size:30px">~$discountSpecial`% OFF
                <font class="fs24 black">on plans</font></font> </span>
            </div>
        </div>
    </div>
    ~else`
    <div class="mem-holi2 fl">
        <div class="fl" style="margin-left:173px; margin-top:29px">
            <div class="fl fs22" style="width:280px">
                <p>Renew before</p>
                <p><font class="maroon">~$userObj->expiryDate`</font> and get </p>
            </div>
            <div class="fl" style="width:110px">
                <font class="maroon" style=" font-size:30px;"><span class="fs22">~$renewalPercent`% OFF</span><br /><font class="fs20 black">on all plans</font></font>
            </div>
            <div class="fl mar10right"><img src="/images/plussign.png"></img></div>
            <div class=" fl">
                <font class="maroon" style=" font-size:30px"><span class="fs22">~$userObj->contactsRemaining` additional </span><br /><font class="fs20 black">contact details</font></font></div>
            </div>
        </div>
        ~/if`
    ~else if $specialActive eq '1'`
    <!--Special Discount -->
    <div class="mem-holi2 fl" style="margin-top:5px;">
        <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
            <div class="  fs24">
                Buy before
                <font class="maroon" style=" font-size:30px">~$variable_discount_expiry`</font> and get ~$discountLimitTextVal` <span > <font class="maroon" style=" font-size:30px">~$discountSpecial`% OFF
                <font class="fs24 black">on plans</font></font> </span>
            </div>
        </div>
    </div>
        ~else if $discountActive eq '1'`
        <!--Offer Discount-->
        <div class="mem-holi2 fl" style="margin-top:5px;">
            <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
                <div class="  fs24">
                    Buy before
                    <font class="maroon" style=" font-size:30px">~$discount_expiry`</font> and get ~$cashDiscountDisplayText` <span > <font class="maroon" style=" font-size:30px">~$discountPercent`%
                    <font class="fs24 black">discount</font></font> </span>
                </div>
            </div>
        </div>
        ~else if $fest eq '1'`
        <!--Festive Discount-->
    <!--    <div style="padding:15px 0px">
                        <img src='/~$festBanner`'/>
                    </div> -->
                    ~/if`
                    ~/if`
                    <ul class="tabs ">
                        <li ><a id="mainMembershipTab" class="carryForms active" href="#tab1">Choose Membership Plan</a></li>
                        <li><a id="valueAddedTab" class="carryForms" style="cursor:pointer;height:24px;" >Choose Additional Services</a></li>
                        <li><a id="paymentOptionTab" class="carryForms" style="cursor:pointer;height:24px;">Payment Options</a></li>
                    </ul>
                    ~foreach from=$benefits key=k item=v`
                    <input type='hidden' value="~$v`" id="~$k`hiddenDiv" />
                    ~/foreach`
                    ~include_partial('global/membershipAlertLayout')`
                    <!-- TAB 1 CONTENT  STARTS-->
                    <div style="display: block; padding: 20px 14px;width:895px; float:left;" id="tab1">
                        <form id="mainMemForm" action="/membership/valueAddedMembership" method="post" target="_top" >
                            <table cellpadding="0" cellspacing="0" class="fs13">
                                <tr>
                                    <td class="center fs14 pad-new color-new b" colspan="2">
                                        We can help find the Best Plan for you! Call us at <span class="color-new1">~if $currency eq 'DOL'`+911204393500~else`1800-419-6299 (Toll Free)~/if`</span> or just
                                        <a href="#" style="cursor:pointer;font-size: 15px;" class="color-blue" id="excCallNew" onClick="execCallback('1','JS_ALL');">Request a Call Back from us</a>.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:295px;" valign="top">
                                        <table cellpadding="0" class="fullwidth" cellspacing="0" rules="all" border="1" bordercolor="#e6e6e6" >
                                            <tr height="40">
                                                <td style=" border-top:1px solid #FFF; border-left:2px solid #FFF" class="pd7L color-new f_14">
                                                    <b>Benefits you Get</b>
                                                </td>
                                            </tr>
                                            <tr height="45" >
                                                <td class="pd7L color-new">
                                                    Search People and Send/Receive Interests
                                                </td>
                                            </tr>
                                            <tr height="50" >
                                                <td class="pd7L color-new">
                                                    Instantly see Phone/Email of people you like
                                                </td>
                                            </tr>
                                            <tr height="45">
                                                <td class="pd7L color-new">
                                                    Initiate Messages and Chat
                                                </td>
                                            </tr>
                                            <tr height="45">
                                                <td class="pd7L color-new">
                                                    Show your Phone/Email to other members
                                                </td>
                                            </tr>
                                            ~foreach from=$serviceTabs key=k item=v`
                                            ~if $v eq 'ESP' or $v eq 'NCP'`
                                            <tr height="45">
                                                <td valign="top">
                                                    <div style="padding:13px 1px 0px 7px;">
                                                        <div style="float: left;" class="color-new">
                                                            Feature your profile on top of relevant searches
                                                        </div>                                                        
                                                        <div style="clear:both;"></div>
                                                    </div>
                                                </td></tr>
                                                <tr height="45">
                                                <td valign="top">
                                                    <div style="padding:13px 1px 0px 7px;">
                                                        <div style="float: left;" class="color-new">
                                                            Jeevansathi sends interests on your behalf
                                                        </div>
                                                        <div style="clear:both;"></div>
                                                    </div>
                                                </td></tr>
                                                ~/if`~/foreach`
                                                ~if $userObj->userType eq 6 or $userObj->userType eq 4`
                                                <tr id="discountBar" bgcolor="#e6e6e6"><td valign="top" class="pd7L" height="100" style="border-right:1px solid #fff">
                                                    <div class="offer-btn discount-text">Discounted Price <span class="pointer-arr sprte-mem"></span></div>
                                                </td></tr>
                                                ~/if`
                                            </table>
                                            <!-- start:VAS box -->
                                            <div class="pos_rltv1 new-zind1">
                                                <div class="pos_abs1" style="top:-20px;">
                                                    <div id="openCloseVAS" class="response-box" style="padding:10px;display:none;">
                                                        <div class="new-vas">
                                                            <div style="height: 10px; position: absolute; top: 1px; left: 150px; background: url(/images/spriteblueimg1.png) no-repeat scroll 1px -31px transparent; width: 13px;"></div>
                                                            ~foreach from=$eSSpcls key=k item=v name=eSathiLoop`
                                                            ~if $smarty.foreach.eSathiLoop.iteration eq "1"`<p style="font-size:12px;padding-left:4px;">~$v` <span id="~$k`Benefit" class="ques-icn sprte-mem" style="cursor:pointer"></span></p>
                                                            ~else`
                                                            <p style="font-size:12px;padding-left:4px;">~$v` <span id="~$k`Benefit" class="ques-icn sprte-mem" style="cursor:pointer"></span></p>
                                                            ~/if`
                                                            ~/foreach`
                                                            <div id="vasBenefits" class="pop-layer" style="visibility:hidden">
                                                                <div class="layer-content">
                                                                    <div class="close-layer" title="Close">x</div>
                                                                    <div id="vasContent">Busy? Don't worry! <br />
                                                                        Allow Jeevansathi.com to contact
                                                                        people who match your criteria
                                                                        and get 8 times more response.</div>
                                                                    </div>
                                                                    <div id="vasBenefitsPtr" class="pointer sprte-mem"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div></div>
                                                    <!-- end:VAS box -->
                                                </td>
                                                <td style="width:599px;" valign="top">
                                                    <table cellpadding="0" cellspacing="0" border="1" bordercolor="#e6e6e6" class="center fullwidth">
                                                        <tr height="240"><td colspan="4" >
                                                            <table id="servcCols" cellpadding="0" cellspacing="0" border="1"  frame="rhs" rules="cols" bordercolor="#e6e6e6">
                                                                <tr height="39" class="brd1pxbot">
                                                                    <td align="center" style="width:140px;"><b class="fs18 color-new">Free</b></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td align="center" style="width:151px;"><b class="fs18 color-new">eRishta</b></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'`<td width="~$width`"><div class="mem-ico-eclassifieds sprte-mem">&nbsp;</div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td style="width:151px;"><b class="fs18 color-new">eValue</b></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td style="width:151px;"><b class="fs18 color-new">eAdvantage</b></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td style="width:151px;"><b class="fs18 color-new">eSathi</b></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-right-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'`<td>&nbsp;</td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'`<td>&nbsp;</td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                ~foreach from=$serviceTabs key=k item=v`
                                                                ~if $v eq 'ESP' or $v eq 'NCP'`
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'` <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'` <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                ~/if`
                                                                ~/foreach`
                                                                ~foreach from=$serviceTabs key=k item=v`
                                                                ~if $v eq 'ESP' or $v eq 'NCP'`
                                                                <tr height="45">
                                                                    <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'` <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'D'` <td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'C'`<td><div class="mem-ico-wrong-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'NCP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'ESP'`<td><div class="mem-ico-right-new sprte-mem-new"></div></td>~/if`
                                                                    ~/foreach`
                                                                </tr>
                                                                ~/if`
                                                                ~/foreach`
                                                                
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    ~foreach from=$serviceTabs key=k item=v`
                                                                    ~if $v eq 'P'`<td align="center"  bgcolor="#e6e6e6" style="border-right:1px solid #fff"><table width="100%">
                                                                    ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.P.PRICE_INR neq $minPriceArr.P.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.P.PRICE_INR neq $minPriceArr.P.OFFER_PRICE)`
                                                                    ~assign var=extraVal value=1`
                                                                    <tr align="center"><td>
                                                                        <div class="offer-price-box">
                                                                            <div style="display: table;">
                                                                                <font class="fs20">
                                                                                    <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050;">Starts @</div>
                                                                                    ~if $currency eq 'DOL'`<div style="display: table-cell;color:#505050;">$&nbsp; ~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                                                                    ~if $currency eq 'DOL'`
                                                                                    <div class="strikethrough" style="display: table-cell;">~$minPriceArr.P.PRICE_USD`</div>
                                                                                    ~else`
                                                                                    <div class="strikethrough" style="display: table-cell;">~$minPriceArr.P.PRICE_INR`</div>
                                                                                    ~/if`
                                                                                </font>
                                                                                <font class="fs24" style="padding-left:57px;color:#000000">~if $currency eq 'DOL'`<span>$~else`<span style="color:#000000;font-family:WebRupee">Rs.~/if`</span>~$minPriceArr.P.OFFER_PRICE`</font>
                                                                            </div>
                                                                        </div>
                                                                    </td></tr><tr align="center"><td>
                                                                    <input id="P" type="button" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                                                </td></tr>
                                                                ~else`
                                                                <tr align="center"><td>
                                                                    <div class="offer-price-box">
                                                                        <div style="display: table;">
                                                                            <font class="fs20">
                                                                                <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                                                                ~if $currency eq 'DOL'`<div style="display: table-cell;">$~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                                                                ~if $currency eq 'DOL'`
                                                                                <div style="display: table-cell;">~$minPriceArr.P.PRICE_USD`</div>
                                                                                ~else`
                                                                                <div style="display: table-cell;">~$minPriceArr.P.PRICE_INR`</div>
                                                                                ~/if`
                                                                            </font>
                                                                            <div class="extraSp"></div>
                                                                        </div>
                                                                    </div></td></tr><tr align="center"><td>
                                                                    <input id="P" type="button" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                                                </div></td></tr>
                                                                ~/if`
                                                            </table>
                                                        </td>
                                                        ~/if`
                                                        ~/foreach`
                                                        ~foreach from=$serviceTabs key=k item=v`
                                                        ~if $v eq 'D'`<td align="center"  bgcolor="#e6e6e6" style="border-right:1px solid #fff"><table width="100%">
                                                        ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.D.PRICE_INR neq $minPriceArr.D.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.D.PRICE_INR neq $minPriceArr.D.OFFER_PRICE)`<tr align="center"><td>
                                                        ~assign var=extraVal value=1`
                                                        <div class="offer-price-box">
                                                            <div style="display: table;">
                                                                <font class="fs20">
                                                                    <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                                                    ~if $currency eq 'DOL'`<div style="display: table-cell;color:#505050;">$&nbsp; ~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee;">Rs.~/if`</div>
                                                                    ~if $currency eq 'DOL'`
                                                                    <div class="strikethrough" style="display: table-cell;">~$minPriceArr.D.PRICE_USD`</div>
                                                                    ~else`
                                                                    <div class="strikethrough" style="display: table-cell;">~$minPriceArr.D.PRICE_INR`</div>
                                                                    ~/if`
                                                                </font>
                                                                <font class="fs24" style="padding-left:57px;color:#000000">~if $currency eq 'DOL'`<span>$~else`<span style="color:#000000;font-family:WebRupee;">Rs.~/if`</span>~$minPriceArr.D.OFFER_PRICE`</font>
                                                            </div>
                                                        </td></tr><tr align="center"><td>
                                                        <input type="button" id="D" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                                    </div></td></tr>
                                                    ~else`
                                                    <tr align="center"><td>
                                                        <div class="offer-price-box">
                                                            <div style="display: table;">
                                                                <font class="fs20">
                                                                    <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                                                    ~if $currency eq 'DOL'`<div style="display: table-cell;">$&nbsp;~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                                                    ~if $currency eq 'DOL'`
                                                                    <div style="display: table-cell;">~$minPriceArr.D.PRICE_USD`</div>
                                                                    ~else`
                                                                    <div style="display: table-cell;">~$minPriceArr.D.PRICE_INR`</div>
                                                                    ~/if`
                                                                </font>
                                                                <div class="extraSp"></div>
                                                            </div>
                                                        </div>
                                                    </td></tr><tr align="center"><td>
                                                    <input type="button" id="D" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                                </div></td></tr>
                                                ~/if`
                                            </table>
                                        </td>
                                        ~/if`
                                        ~/foreach`
                                        ~foreach from=$serviceTabs key=k item=v`
                                        ~if $v eq 'C'`
                                        <td align="center"  bgcolor="#e6e6e6" style="border-right:1px solid #fff"><table width="100%">
                                            ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.C.PRICE_INR neq $minPriceArr.C.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.C.PRICE_INR neq $minPriceArr.C.OFFER_PRICE)`<tr align="center"><td>
                                            ~assign var=extraVal value=1`
                                            <div class="offer-price-box">
                                                <div style="display: table;">
                                                    <font class="fs20">
                                                        <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                                        ~if $currency eq 'DOL'`<div style="display: table-cell;color:#505050;">$&nbsp; ~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                                        ~if $currency eq 'DOL'`
                                                        <div class="strikethrough" style="display: table-cell;">~$minPriceArr.C.PRICE_USD`</div>
                                                        ~else`
                                                        <div class="strikethrough" style="display: table-cell;">~$minPriceArr.C.PRICE_INR`</div>
                                                        ~/if`
                                                    </font>
                                                    <font class="fs24" style="padding-left:57px;color:#000000">~if $currency eq 'DOL'`<span>$~else`<span style="color:#000000;font-family:WebRupee">Rs.~/if`</span>~$minPriceArr.C.OFFER_PRICE`</font>
                                                </div>
                                            </td></tr><tr align="center"><td>
                                            <input type="button" id="C" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                        </div></td></tr>
                                        ~else`
                                        <tr align="center"><td>
                                            <div class="offer-price-box">
                                                <div style="display: table;">
                                                    <font class="fs20">
                                                        <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                                        ~if $currency eq 'DOL'`<div style="display: table-cell;">$~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                                        ~if $currency eq 'DOL'`
                                                        <div style="display: table-cell;">~$minPriceArr.C.PRICE_USD`</div>
                                                        ~else`
                                                        <div style="display: table-cell;">~$minPriceArr.C.PRICE_INR`</div>
                                                        ~/if`
                                                    </font>
                                                    <div class="extraSp"></div>
                                                </div>
                                            </div>
                                        </td></tr><tr align="center"><td>
                                        <input type="button" id="C" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                                    </div></td></tr>
                                    ~/if`
                                </table>
                            </td>
                            ~/if`
                            ~/foreach`
                            ~foreach from=$serviceTabs key=k item=v`
                            ~if $v eq 'NCP'`
                            <td align="center"  bgcolor="#e6e6e6"><table width="100%">
                                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.NCP.PRICE_INR neq $minPriceArr.NCP.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.NCP.PRICE_INR neq $minPriceArr.NCP.OFFER_PRICE)`<tr align="center"><td>
                                ~assign var=extraVal value=1`
                                <div class="offer-price-box">
                                    <div style="display: table;">
                                        <font class="fs20">
                                            <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                            ~if $currency eq 'DOL'`<div style="display: table-cell;color:#505050;">$&nbsp;~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                            ~if $currency eq 'DOL'`
                                            <div class="strikethrough" style="display: table-cell;">~$minPriceArr.NCP.PRICE_USD`</div>
                                            ~else`
                                            <div class="strikethrough" style="display: table-cell;">~$minPriceArr.NCP.PRICE_INR`</div>
                                            ~/if`
                                        </font>
                                        <font class="fs24" style="padding-left:57px;color:#000000">~if $currency eq 'DOL'`<span>$~else`<span style="color:#000000;font-family:WebRupee">Rs.~/if`</span>~$minPriceArr.NCP.OFFER_PRICE`</font>
                                    </div>
                                </td></tr><tr align="center"><td>
                                <input type="button" id="NCP" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                            </div></td></tr>
                            ~else`
                            <tr align="center"><td>
                                <div class="offer-price-box">
                                    <div style="display: table;">
                                        <font class="fs20">
                                            <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                            ~if $currency eq 'DOL'`<div style="display: table-cell;">$~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                            ~if $currency eq 'DOL'`
                                            <div style="display: table-cell;">~$minPriceArr.NCP.PRICE_USD`</div>
                                            ~else`
                                            <div style="display: table-cell;">~$minPriceArr.NCP.PRICE_INR`</div>
                                            ~/if`
                                        </font>
                                        <div class="extraSp"></div>
                                    </div>
                                </div>
                            </td></tr><tr align="center"><td>
                            <input type="button" id="NCP" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                        </div></td></tr>
                        ~/if`
                    </table>
                </td>
                ~/if`
                ~/foreach`
                ~foreach from=$serviceTabs key=k item=v`
                ~if $v eq 'ESP'`
                <td align="center"  bgcolor="#e6e6e6"><table width="100%">
                    ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.ESP.PRICE_INR neq $minPriceArr.ESP.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.ESP.PRICE_INR neq $minPriceArr.ESP.OFFER_PRICE)`<tr align="center"><td>
                    ~assign var=extraVal value=1`
                    <div class="offer-price-box">
                        <div style="display: table;">
                            <font class="fs20">
                                <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                ~if $currency eq 'DOL'`<div style="display: table-cell;color:#505050;">$&nbsp;~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                ~if $currency eq 'DOL'`
                                <div class="strikethrough" style="display: table-cell;">~$minPriceArr.ESP.PRICE_USD`</div>
                                ~else`
                                <div class="strikethrough" style="display: table-cell;">~$minPriceArr.ESP.PRICE_INR`</div>
                                ~/if`
                            </font>
                            <font class="fs24" style="padding-left:57px;color:#000000">~if $currency eq 'DOL'`<span>$~else`<span style="color:#000000;font-family:WebRupee">Rs.~/if`</span>~$minPriceArr.ESP.OFFER_PRICE`</font>
                        </div>
                    </td></tr><tr align="center"><td>
                    <input type="button" id="ESP" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
                </div></td></tr>
                ~else`
                <tr align="center"><td>
                    <div class="offer-price-box">
                        <div style="display: table;">
                            <font class="fs20">
                                <div style="font:14px arial;display: table-cell;vertical-align:middle;padding-right:5px;color:#505050">Starts @</div>
                                ~if $currency eq 'DOL'`<div style="display: table-cell;">$~else`<div style="display: table-cell;color:#505050;padding-right:5px;font-family:WebRupee">Rs.~/if`</div>
                                ~if $currency eq 'DOL'`
                                <div style="display: table-cell;">~$minPriceArr.ESP.PRICE_USD`</div>
                                ~else`
                                <div style="display: table-cell;">~$minPriceArr.ESP.PRICE_INR`</div>
                                ~/if`
                            </font>
                            <div class="extraSp"></div>
                        </div>
                    </div>
                </td></tr><tr align="center"><td>
                <input type="button" id="ESP" class="main mem-btn-green widthauto sprte-mem" value="choose plan" style="cursor:pointer;margin-bottom:~$marginBottom`"/>
            </div></td></tr>
            ~/if`
        </table>
    </td>
    ~/if`
    ~/foreach`
</tr>
<input type="hidden" id="extraSpace" value=~$extraVal`>
</table>
</td>
</tr>
</table>
<!-- end:second rhs table -->
</td>
</tr>
<!-- start:hover tabel-->
<tr>
    <td colspan="2">
        <div id="memDetailsContainer" style="width:717px; border: 3px solid #63ac1c; float:right; padding-top:15px; position:relative">
            <div  class="fs16 mem-btn-hover" style="line-height:2.6;top:-50px;height:47px;text-align:center;" id="hoverChoose">
                <b>
                    <font size="4px">Choose Plan</font>
                </b>
            </div>
            <table id="PTable" class="mainTable" width="717" cellpadding="0" cellspacing="0" align="center">
                <tr id="durationRow" class="fs14 pad1-new">
                    <td width="93" align="center" class="hgt-new1"></td>
                    ~foreach from=$allMainMem.P item=v key=k`
                    ~if $v.DURATION neq '1'`
                    <td width="93" class="durationId" align="center">~if $v.DURATION eq 1188` Unlimited ~elseif $v.DURATION eq 1`~$v.DURATION` Month~else`~$v.DURATION` Months~/if` </td>
                    ~/if`
                    ~/foreach`
                </tr>
                ~if $fest eq 1`
                <tr>
                    <td></td>
                    ~foreach from=$allMainMem.P item=v key=k`
                    ~if $v.DURATION neq '1'`
                    ~if $festDurBanner.P.~$v.DURATION` neq ''`<td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.P.~$v.DURATION``</div></div></td>~else`<td width="93" align="center"></td>~/if`
                    ~/if`
                    ~/foreach`
                </tr>
                ~/if`
                <tr class="fs14" bgcolor="#f3f3f3">
                    <td class="fs13 pad2-new txt-newlft">Phone/Email Views <span class="b">After</span> Acceptance of Interest</td>
                    ~foreach from=$allMainMem.P item=v key=k`
                    ~if $v.DURATION neq '1'`
                    <td width="93" align="center">Unlimited</td>
                    ~/if`
                    ~/foreach`
                </tr>
                <tr><td style="height:1px;"></td></tr>
                <tr id="contactsRow" class="fs14" bgcolor="#f3f3f3">
                    <td class="fs13 pad1-new txt-newlft"><span class="b">Instant</span> Phone/Email Views of any Member</td>
                    ~foreach from=$allMainMem.P item=v key=k`
                    ~if $v.DURATION neq '1'`
                    <td width="93" align="center">~$v.CALL`</td>
                    ~/if`
                    ~/foreach`
                </tr>
                <tr id="priceRow" class="fs16" >
                    <td  id="mainMemIcon" width="150px" valign="top" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0;" align="center"><div style="padding-top:25px"><i class="mem-ico-erishta sprte-mem" style="display:block;width=100%"></i></div></td>
                    ~foreach from=$allMainMem.P item=v key=k`
                    ~if $v.DURATION neq '1'`
                    <td align="center"  valign="top" width="~$rishtaWidth`" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0;~if $k eq $popular.P` background-color:#ffeded~/if`"><div style="padding-top:30px"><input type="radio" style="border:none;outline:none;" class="widthauto" value="main~$k`" id="~$k`" name="PPriceRadio" ~if $selMemArr.P` ~if $k eq $selMemArr.P` checked='checked'~/if` ~else if $k eq $popular.P` checked='checked' ~/if` /><br />
                        ~if $userObj->userType eq 6 or $userObj->userType eq 4`
                        <font class="strikethrough">~$v.PRICE`</font><br/>
                        ~else if $specialActive`
                        ~if $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE`
                        <font class="strikethrough">~$v.PRICE`</font>
                        ~/if`<br />
                        ~else if $discountActive and $v.PRICE neq $v.OFFER_PRICE`
                        <font class="strikethrough">~$v.PRICE`</font> <br />
                        ~else if $fest eq '1' && $v.PRICE neq $v.OFFER_PRICE`
                        <font class="strikethrough">~$v.PRICE`</font> <br />
                        ~/if`
                        ~$v.OFFER_PRICE`
                        ~if $v.DURATION neq 'L' and $v.DURATION neq '1188' and $fest neq 1`
                        <div style="color: #777777; font-size: 12px;">
                            ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`
                            ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($specialActive and $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE) or ($discountActive and $v.PRICE neq $v.OFFER_PRICE) or ($fest eq '1' and $v.DURATION eq '12')`
                            ~($v.OFFER_PRICE/$v.DURATION)|ceil`/month
                            ~else`
                            ~($v.PRICE/$v.DURATION)|ceil`/month~/if`
                        </div>
                        ~/if`
                        ~if $k eq $popular.P`<div><i class="mem-ico-popular sprte-mem" style="display:block;width=100%"></i></div>~/if`</div></td>
                        ~/if`
                        ~/foreach`
                    </tr>
                    <tr>
                        <td >&nbsp;</td>
                        ~assign var=count value=0`
                        ~foreach from=$allMainMem.P name=mainLoop item=v key=k`
                        ~if $v.DURATION neq '1'`
                        ~if stristr($freeBiesER,$k)`
                        ~assign var=count value=$count+1`
                        <td bgcolor="#f3f3f3" valign="top" align="center"
                        style="border-bottom:1px solid #d5d5d5; border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" style="border-right:1px solid #d5d5d5;">
                        ~assign var=arrLeft value=$rishtaWidth/2-10`
                        <i class="mem-ico-greyarr-up sprte-mem fl" style="position:relative;top:-18px;left:~$arrLeft`px;"></i>
                        ~foreach from=$freeBiesERA item=v2 key=k2`
                        ~if $k eq $k2`
                        ~assign var=totalPrice value=0`
                        ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
                        ~assign var=totalPrice value=$totalPrice+$v3.price`
                        ~/foreach`
                        <br/><div style="color:#fff;font-weight:bold;width:50px;padding-bottom:5px;"><div style="padding:2px;background-color:#D30808;font-size:12px;">FREE</div></div>
                        <table><tr><td align="center" style="padding-bottom:5px;"><font class="fs12 maroon b">
                            Worth ~if $currency eq 'DOL'`$~else`Rs.~/if` ~$totalPrice`<br/>
                        </font>
                        ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
                        ~if $smarty.foreach.thisLoop.iteration eq "1"`
                        <font class="fs11">~$v3.name`</font>
                        ~else`
                        ~if $v3.name neq ''`<font class="fs11"><br/> +<br/>~$v3.name`</font>~/if`
                        ~/if`
                        ~/foreach`
                    </td>
                </tr>
            </table>
            ~/if`
            ~/foreach`
        </td>
        ~else`
        <td>&nbsp;</td>
        ~/if`
        ~/if`
        ~/foreach`
    </tr>
</table>
<table id="CTable" class="mainTable" width="717" cellpadding="0" cellspacing="0" align="center">
    <tr id="durationRow" class="fs14">
        <td width="93" class="hgt-new1"></td>
        ~foreach from=$allMainMem.C item=v key=k`
        <td width="93" class="durationId" align="center">~if $v.DURATION eq 1188` Unlimited ~elseif $v.DURATION eq 1`~$v.DURATION` Month~else`~$v.DURATION` Months~/if`</td>
        ~/foreach`
    </tr>
    ~if $fest eq 1`
    <tr>
        <td></td>
        ~foreach from=$allMainMem.C item=v key=k`
        ~if $festDurBanner.C.~$v.DURATION` neq ''`<td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.C.~$v.DURATION``</div></div></td>~else`<td width="93" align="center"></td>~/if`
        ~/foreach`
    </tr>
    ~/if`
    <tr class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad2-new txt-newlft"> Phone/Email Views <span class="b">After</span> Acceptance of Interest</td>
        ~foreach from=$allMainMem.C item=v key=k`
        <td width="93" align="center">Unlimited</td>
        ~/foreach`
    </tr>
    <tr><td style="height:1px;"></td></tr>
    <tr id="contactsRow" class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad1-new txt-newlft"> <span class="b">Instant</span> Phone/Email Views of any Member</td>
        ~foreach from=$allMainMem.C item=v key=k`
        <td width="93" align="center">~$v.CALL`</td>
        ~/foreach`
    </tr>
    <tr id="priceRow" class="fs16" height="114">
        <td  id="mainMemIcon" valign="middle" width="150px" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0"><i class=" mem-only-eval sprte-mem" style="display:block;width=100%"></i></td>
        ~foreach from=$allMainMem.C item=v key=k`
        <td align="center" valign="top" width="~$valueWidth`" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:35px 0 25px 0;~if $k eq $popular.C` background-color:#ffeded~/if`"><div style="padding-top:30px"><input type="radio" style="border:none;outline:none;" class="widthauto" value="main~$k`" id="~$k`" name="CPriceRadio" ~if $selMemArr.C` ~if $k eq $selMemArr.C` checked='checked'~/if` ~else if $k eq $popular.C` checked='checked' ~/if`  /><br />~if $userObj->userType eq 6 or $userObj->userType eq 4`<font class="strikethrough">~$v.PRICE`</font><br/>~else if $specialActive` ~if $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE`<font class="strikethrough">~$v.PRICE`</font> ~/if` <br />~else if $discountActive and $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~else if $fest eq '1' && $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~/if`~$v.OFFER_PRICE`
            ~if $v.DURATION neq 'L' and $v.DURATION neq '1188' and $fest neq 1`
            <div style="color: #777777; font-size: 12px;">
                ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`
                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($specialActive and $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE) or ($discountActive and $v.PRICE neq $v.OFFER_PRICE) or ($fest eq '1' and $v.DURATION eq '12')`
                ~($v.OFFER_PRICE/$v.DURATION)|ceil`/month
                ~else`
                ~($v.PRICE/$v.DURATION)|ceil`/month~/if`
            </div>
            ~/if`
            ~if $k eq $popular.C`<div><i class="mem-ico-popular sprte-mem" style="display:block;width=100%"></i></div>~/if`</div></td>
            ~/foreach`
        </tr>
        <tr>
            <td>&nbsp;</td>
            ~assign var=count value=0`
            ~foreach from=$allMainMem.C item=v key=k`
            ~if stristr($freeBiesEV,$k)`
            ~assign var=count value=$count+1`
            <td bgcolor="#f3f3f3" valign="top" align="center"
            style="border-bottom:1px solid #d5d5d5; border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" style="border-right:1px solid #d5d5d5;">
            ~assign var=arrLeft value=$valueWidth/2-10`
            <i class="mem-ico-greyarr-up sprte-mem fl" style="position:relative;top:-18px;left:~$arrLeft`px;"></i>
            ~foreach from=$freeBiesEVA item=v2 key=k2`
            ~if $k eq $k2`
            ~assign var=totalPrice value=0`
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~assign var=totalPrice value=$totalPrice+$v3.price`
            ~/foreach`
            <br><div style="color:#fff;font-weight:bold;width:50px;padding-bottom:5px;"><div style="padding:2px;background-color:#D30808;font-size:12px;">FREE</div></div>
            <table><tr><td align="center" style="padding-bottom:5px;"><font class="fs12 maroon b">
                Worth ~if $currency eq 'DOL'`$~else`Rs.~/if` ~$totalPrice`<br/>
            </font>
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~if $smarty.foreach.thisLoop.iteration eq "1"`
            <font class="fs11">~$v3.name`</font>
            ~else`
            ~if $v3.name neq ''`<font class="fs11"><br/> + <br/>~$v3.name`</font>~/if`
            ~/if`
            ~/foreach`
        </td></tr></table>
        ~/if`
        ~/foreach`
    </td>
    ~else`
    <td>&nbsp;</td>
    ~/if`
    ~/foreach`
</tr>
</table>
<table id="DTable" class="mainTable" width="717" cellpadding="0" cellspacing="0" align="center">
    <tr id="durationRow" class="fs16">
        <td width="93"></td>
        ~foreach from=$allMainMem.D item=v key=k`
        <td align="center" width="93" class="durationId">~if $v.DURATION eq 1188` Unlimited ~elseif $v.DURATION eq 1`~$v.DURATION` Month~else`~$v.DURATION` Months~/if` </td>
        ~/foreach`
    </tr>
    ~if $fest eq 1`
    <tr>
        <td></td>
        ~foreach from=$allMainMem.D item=v key=k`
        ~if $festDurBanner.D.~$v.DURATION` neq ''`<td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.D.~$v.DURATION``</div></div></td>~else`<td width="93" align="center"></td>~/if`
        ~/foreach`
    </tr>
    ~/if`
    <tr id="priceRow" class="fs16" height="114">
        <td  id="mainMemIcon" valign="middle" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;" align="center" width="150"><i class=" mem-ico-eclassifieds sprte-mem" style="display:block;width=100%"></i></td>
        ~foreach from=$allMainMem.D item=v key=k`
        <td valign="top" width="~$classWidth`" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0;~if $k eq $popular.D` background-color:#ffeded~/if`" align="center"><div style="padding-top:30px"><input type="radio" style="border:none;outline:none;" class="widthauto" value="main~$k`" id="~$k`" name="DPriceRadio" ~if $selMemArr.D` ~if $k eq $selMemArr.D` checked='checked'~/if` ~else if $k eq $popular.D` checked='checked' ~/if` /><br />~if $userObj->userType eq 6 or $userObj->userType eq 4`<font class="strikethrough">~$v.PRICE`</font><br>~else if $specialActive` ~if $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE`<font class="strikethrough">~$v.PRICE`</font> ~/if` <br />~else if $discountActive and $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~else if $fest eq '1' && $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~/if`~$v.OFFER_PRICE`
            ~if $v.DURATION neq 'L' and $v.DURATION neq '1188' and $fest neq 1`
            <div style="color: #777777; font-size: 12px;">
                ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`
                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($specialActive and $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE) or ($discountActive and $v.PRICE neq $v.OFFER_PRICE) or ($fest eq '1' and $v.DURATION eq '12')`
                ~($v.OFFER_PRICE/$v.DURATION)|ceil`/month
                ~else`
                ~($v.PRICE/$v.DURATION)|ceil`/month~/if`
            </div>
            ~/if`
            ~if $k eq $popular.D`<div><i class="mem-ico-popular sprte-mem" style="display:block;width=100%"></i></div>~/if`</div></td>
            ~/foreach`
        </tr>
        <tr>
            <td>&nbsp;</td>
            ~assign var=count value=0`
            ~foreach from=$allMainMem.D item=v key=k`
            ~if strstr($freeBiesEC,$k)`
            ~assign var=count value=$count+1`
            <td bgcolor="#f3f3f3" valign="top" align="center"
            style="border-bottom:1px solid #d5d5d5; border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" style="border-right:1px solid #d5d5d5;">
            ~assign var=arrLeft value=$classWidth/2-10`
            <i class="mem-ico-greyarr-up sprte-mem fl" style="position:relative;top:-18px;left:~$arrLeft`px;"></i>
            ~foreach from=$freeBiesECA item=v2 key=k2`
            ~if $k eq $k2`
            ~assign var=totalPrice value=0`
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~assign var=totalPrice value=$totalPrice+$v3.price`
            ~/foreach`
            <br/><div style="color:#fff;font-weight:bold;width:50px;padding-bottom:5px;"><div style="padding:2px;background-color:#D30808;font-size:12px;">FREE</div></div>
            <table><tr><td align="center" style="padding-bottom:5px;"><font class="fs12 maroon b">
                Worth ~if $currency eq 'DOL'`$~else`Rs.~/if` ~$totalPrice`<br/>
            </font>
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~if $smarty.foreach.thisLoop.iteration eq "1"`
            <font class="fs11">~$v3.name`</font>
            ~else`
            ~if $v3.name neq ''`<font class="fs11"><br/> + <br/>~$v3.name`</font>~/if`
            ~/if`
            ~/foreach`</td></tr>
        </table>
        ~/if`
        ~/foreach`
    </td>
    ~else`
    <td>&nbsp;</td>
    ~/if`
    ~/foreach`
</tr>
</table>
<table id="NCPTable" class="mainTable" width="717" cellpadding="0" cellspacing="0" align="center">
    <tr id="durationRow" class="fs14">
        <td align="center" class="hgt-new1">&nbsp;</td>
        ~foreach from=$allMainMem.NCP item=v key=k`
        <td id="durationId" class="widthauto" align="center">~if $v.DURATION`~$v.DURATION` Months~/if`</td>
        ~/foreach`
    </tr>
    ~if $fest eq 1`
    <tr>
        <td></td>
        ~foreach from=$allMainMem.NCP item=v key=k`
        ~if $v.DURATION eq '1188'`
        ~if $festDurBanner.NCP.1188 neq ''`
        <td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.NCP.1188`</div</div></td>
        ~/if`
        ~else`
        ~if $festDurBanner.NCP.~$v.DURATION` neq ''`
        <td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.NCP.~$v.DURATION``</div></div></td>
        ~else`<td width="93" align="center"></td>
        ~/if`
        ~/if`
        ~/foreach`
    </tr>
    ~/if`
    <tr  class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad2-new txt-newlft">Phone/Email Views <span class="b">After</span> Acceptance of Interest</td>
        ~foreach from=$allMainMem.NCP item=v key=k`
        <td align="center">Unlimited</td>
        ~/foreach`
    </tr>
    <tr><td style="height:1px;"></td></tr>
    <tr id="contactsRow" class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad1-new txt-newlft"><span class="b">Instant</span> Phone/Email Views of any Member</td>
        ~foreach from=$allMainMem.NCP item=v key=k`
        <td align="center">~$v.CALL`</td>
        ~/foreach`
    </tr>
    <tr id="priceRow" class="fs16" height="114">
        <td  id="mainMemIcon" width="150" valign="middle" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;" align="center"><i class="mem-only-evalPlus sprte-mem" style="display:block;"></i></td>
        ~foreach from=$allMainMem.NCP item=v key=k`
        <td valign="top" width="~$ncpWidth`" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0;~if $k eq $popular.NCP` background-color:#ffeded~/if`" align="center"><div style="padding-top:30px"><input type="radio" style="border:none;outline:none;" class="widthauto" value="main~$k`" id="~$k`" name="NCPPriceRadio" ~if $selMemArr.N` ~if $k eq $selMemArr.N` checked='checked'~/if` ~else if $k eq $popular.NCP` checked='checked' ~/if` /><br />~if $userObj->userType eq 6 or $userObj->userType eq 4`<font class="strikethrough">~$v.PRICE`</font><br/>~else if $specialActive` ~if $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE`<font class="strikethrough">~$v.PRICE`</font> ~/if` <br />~else if $discountActive and $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~else if $fest eq '1'&& $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~/if`~$v.OFFER_PRICE`
            ~if $v.DURATION neq 'L' and $v.DURATION neq '1188' and $v.DURATION neq '12' and $fest neq 1`
            <div style="color: #777777; font-size: 12px;">
                ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`
                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($specialActive and $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE) or ($discountActive and $v.PRICE neq $v.OFFER_PRICE) or ($fest eq '1' and $v.DURATION eq '12')`
                ~($v.OFFER_PRICE/$v.DURATION)|ceil`/month
                ~else`
                ~($v.PRICE/$v.DURATION)|ceil`/month~/if`
            </div>
            ~/if`
            ~if $k eq $popular.NCP`<div><i class="mem-ico-popular sprte-mem" style="display:block;width=100%"></i></div>~/if`</div></td>
            ~/foreach`
        </tr>
        <tr>
            <td>&nbsp;</td>
            ~assign var=count value=0`
            ~foreach from=$allMainMem.NCP item=v key=k`
            ~if stristr($freeBiesES,$k)`
            ~assign var=count value=$count+1`
            <td bgcolor="#f3f3f3" valign="top" align="center"
            style="border-bottom:1px solid #d5d5d5; border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" style="border-right:1px solid #d5d5d5;">
            ~assign var=arrLeft value=$espWidth/2-10`
            <i class="mem-ico-greyarr-up sprte-mem fl" style="position:relative;top:-18px;left:~$arrLeft`px;"></i>
            ~foreach from=$freeBiesESA item=v2 key=k2`
            ~if $k eq $k2`
            ~assign var=totalPrice value=0`
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~assign var=totalPrice value=$totalPrice+$v3.price`
            ~/foreach`
            <br/><div style="color:#fff;font-weight:bold;width:50px;padding-bottom:5px;"><div style="padding:2px;background-color:#D30808;font-size:12px;">FREE</div></div>
            <table><tr><td align="center" style="padding-bottom:5px;"><font class="fs12 maroon b">
                Worth ~if $currency eq 'DOL'`$~else`Rs.~/if` ~$totalPrice`<br/>
            </font>
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~if $smarty.foreach.thisLoop.iteration eq "1"`
            <font class="fs11">~$v3.name`</font>
            ~else`
            ~if $v3.name neq ''`<font class="fs11"><br/> + <br>~$v3.name`</font>~/if`
            ~/if`
            ~/foreach`</td></tr>
        </table>
        ~/if`
        ~/foreach`
    </td>
    ~else`
    <td>&nbsp;</td>
    ~/if`
    ~/foreach`
</tr>
</table>
<table id="ESPTable" class="mainTable" width="717" cellpadding="0" cellspacing="0" align="center">
    <tr id="durationRow" class="fs14">
        <td align="center" class="hgt-new1">&nbsp;</td>
        ~foreach from=$allMainMem.ESP item=v key=k`
        <td id="durationId" class="widthauto" align="center">~if $v.DURATION eq 12` Unlimited ~elseif $v.DURATION eq 1`~$v.DURATION` Month~else`~$v.DURATION` Months~/if` </td>
        ~/foreach`
    </tr>
    ~if $fest eq 1`
    <tr>
        <td></td>
        ~foreach from=$allMainMem.ESP item=v key=k`
        ~if $v.DURATION eq '12'`
        ~if $festDurBanner.ESP.1188 neq ''`
        <td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.ESP.1188`</div</div></td>
        ~/if`
        ~else`
        ~if $festDurBanner.ESP.~$v.DURATION` neq ''`
        <td width="93" align="center"><div style="padding:8px 2px;font-size:11px;color:#fff;font-weight:bold"><div style="background-color:#dd5500;padding:2px 0px">~$festDurBanner.ESP.~$v.DURATION``</div></div></td>
        ~else`<td width="93" align="center"></td>
        ~/if`
        ~/if`
        ~/foreach`
    </tr>
    ~/if`
    <tr  class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad2-new txt-newlft">Phone/Email Views <span class="b">After</span> Acceptance of Interest</td>
        ~foreach from=$allMainMem.ESP item=v key=k`
        <td align="center">Unlimited</td>
        ~/foreach`
    </tr>
    <tr><td style="height:1px;"></td></tr>
    <tr id="contactsRow" class="fs14" bgcolor="#f3f3f3">
        <td class="fs13 pad1-new txt-newlft"><span class="b">Instant</span> Phone/Email Views of any Member</td>
        ~foreach from=$allMainMem.ESP item=v key=k`
        <td align="center">~$v.CALL`</td>
        ~/foreach`
    </tr>
    <tr id="priceRow" class="fs16" height="114">
        <td  id="mainMemIcon" width="150" valign="middle" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;" align="center"><i class="mem-only-esathi sprte-mem" style="display:block;"></i></td>
        ~foreach from=$allMainMem.ESP item=v key=k`
        <td valign="top" width="~$espWidth`" style="border-right:1px solid #d5d5d5; border-top:1px solid #d5d5d5; border-bottom:1px solid #d5d5d5;padding:25px 0;~if $k eq $popular.ESP` background-color:#ffeded~/if`" align="center"><div style="padding-top:30px"><input type="radio" style="border:none;outline:none;" class="widthauto" value="main~$k`" id="~$k`" name="ESPPriceRadio" ~if $selMemArr.E` ~if $k eq $selMemArr.E` checked='checked'~/if` ~else if $k eq $popular.ESP` checked='checked' ~/if` /><br />~if $userObj->userType eq 6 or $userObj->userType eq 4`<font class="strikethrough">~$v.PRICE`</font><br/>~else if $specialActive` ~if $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE`<font class="strikethrough">~$v.PRICE`</font> ~/if` <br />~else if $discountActive and $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~else if $fest eq '1'&& $v.PRICE neq $v.OFFER_PRICE` <font class="strikethrough">~$v.PRICE`</font> <br />~/if`~$v.OFFER_PRICE`
            ~if $v.DURATION neq 'L' and $v.DURATION neq '1188' and $v.DURATION neq '12' and $fest neq 1`
            <div style="color: #777777; font-size: 12px;">
                ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`
                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($specialActive and $v.SPECIAL_DISCOUNT_PRICE neq $v.PRICE) or ($discountActive and $v.PRICE neq $v.OFFER_PRICE) or ($fest eq '1' and $v.DURATION eq '12')`
                ~($v.OFFER_PRICE/$v.DURATION)|ceil`/month
                ~else`
                ~($v.PRICE/$v.DURATION)|ceil`/month~/if`
            </div>
            ~/if`
            ~if $k eq $popular.ESP`<div><i class="mem-ico-popular sprte-mem" style="display:block;width=100%"></i></div>~/if`</div></td>
            ~/foreach`
        </tr>
        <tr>
            <td>&nbsp;</td>
            ~assign var=count value=0`
            ~foreach from=$allMainMem.ESP item=v key=k`
            ~if stristr($freeBiesES,$k)`
            ~assign var=count value=$count+1`
            <td bgcolor="#f3f3f3" valign="top" align="center"
            style="border-bottom:1px solid #d5d5d5; border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" style="border-right:1px solid #d5d5d5;">
            ~assign var=arrLeft value=$espWidth/2-10`
            <i class="mem-ico-greyarr-up sprte-mem fl" style="position:relative;top:-18px;left:~$arrLeft`px;"></i>
            ~foreach from=$freeBiesESA item=v2 key=k2`
            ~if $k eq $k2`
            ~assign var=totalPrice value=0`
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~assign var=totalPrice value=$totalPrice+$v3.price`
            ~/foreach`
            <br/><div style="color:#fff;font-weight:bold;width:50px;padding-bottom:5px;"><div style="padding:2px;background-color:#D30808;font-size:12px;">FREE</div></div>
            <table><tr><td align="center" style="padding-bottom:5px;"><font class="fs12 maroon b">
                Worth ~if $currency eq 'DOL'`$~else`Rs.~/if` ~$totalPrice`<br/>
            </font>
            ~foreach from=$v2 key=k3 item=v3 name=thisLoop`
            ~if $smarty.foreach.thisLoop.iteration eq "1"`
            <font class="fs11">~$v3.name`</font>
            ~else`
            ~if $v3.name neq ''`<font class="fs11"><br/> + <br>~$v3.name`</font>~/if`
            ~/if`
            ~/foreach`</td></tr>
        </table>
        ~/if`
        ~/foreach`
    </td>
    ~else`
    <td>&nbsp;</td>
    ~/if`
    ~/foreach`
</tr>
</table>
<!--<table class="mar17top" width="680">
                            <tr>
                            <td class="fs11 fr" style="color:#444" align="right" >All Prices are in ~$currencyType` inclusive of 12.36 % service tax</td></tr>
                        </table>-->
                        <!--<div class="sp15"></div>-->
                        <!--<div class="sp15"></div>-->
                        <div class="sp10"></div>
                        <div class="sp10"></div>
                        <input type="hidden" name="continueMainIcon" value=""></input>
                        <input type="hidden" name="continueMainId" value="~$tableToShow`"></input>
                        <input type="hidden" name="continueMainSubId" value="~$showTable`"></input>
                        <input type="hidden" name="continueMainDuration" value=""></input>
                        <input type="hidden" name="continueMainPrice" value=""></input>
                        <input type="hidden" name="selMemDur" id="selMemDur" value=""/>
                        <input type="hidden" name="discountActive" value="~$discountActive`"/>
                        <input type="hidden" name="specialActive" value="~$specialActive`"/>
                        <input type="hidden" name="festActive" value="~$fest`"/>
                        <input type="hidden" name="discountExpiry" value="~$discount_expiry`"/>
                        <input type="hidden" name="specialExpiry" value="~$variable_discount_expiry`"/>
                        <input type="hidden" name="discountPercent" value="~$discountPercent`"/>
                        <input type="hidden" name="specialDiscount" value="~$discountSpecial`"/>
                        <input type="hidden" name="navigationString" value="~$showTable`"/>
                        <div class="pos-rel center"> <button id="continueMain" class="carryForms cont-btn-green">Continue</button> </div>
                        <!-- start:tabel 4-->
                        <table class="mar17top" width="680" style="margin-top:19px;margin-bottom:8px;">
                            <tr>
                                <td class="fs11 fr" style="color:#444" align="right" >All Prices are in ~$currencyType` inclusive of ~$tax_rate`% service tax (including Swachh Bharat Cess).</td></tr>
                            </table>
                            <!-- end:tabel 4-->
                        </div>
                    </td>
                </tr>
                <!-- end:hover tabel-->
            </table>
            <!-- end:main table -->
        </form>
    </div>
    <div id="test"></div>
    <!-- TAB 1 CONTENT FINISH-->
</div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="exclusive-banner-cont">
    <div id="exclusive-details" style="width:302px;padding-right:16px;">
        <p style="color:#3f3f3f">A service where a dedicated expert helps you find the perfect life partner by understanding your needs, shortlisting and contacting people on your behalf, and arranging meetings.</p>
        <div class="sp15"></div>
        <div class="sp5"></div>
        <p>For more details, please call <br />
            +91-8800909042 and speak to <br />
            Rahul Sharma </p>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#X3').trigger('click');
            })
        </script>
        <div id="exclusive-packages">
            <!--<form  method="post" ENCTYPE="multipart/form-data">-->
            <div class="b"><label><input style="border:none;outline:none;" type="radio" name="r1" id="X3" checked="checked"/> 3 months</label></div>
            <div class="sp5"></div>
            <div class="b"><label><input style="border:none;outline:none;" type="radio" name="r1" id="X6"/> 6 months</label></div>
            <div class="sp5"></div>
            <div class="b"><label><input style="border:none;outline:none;" type="radio" name="r1" id="X12"/> 12 months</label></div>
            <div class="sp15"></div>
            <div id="exclusivePricesDiv">
                ~if $userObj->userType eq 6 or $userObj->userType eq 4 or $discountActive or $specialActive`
                ~foreach from=$exclusiveInfo key=k item=v`
                ~if $v.OFFER_PRICE neq $v.PRICE`
                <div id="~$k`" ~if $k neq 'X3'`style="display:none;"~/if`>
                    <p class="f25" style="color:#505050;">For ~if $currency eq 'DOL'`<span>$</span>~else`<span style="font-family:WebRupee">Rs. </span>~/if`<span class="strikethrough" id="excPrice">~$exclusiveInfo.$k.PRICE`</span></p>
                    <p class="f25" style="padding-left:44px;">~if $currency eq 'DOL'`<span>$</span>~else`<span style="color:#000000;font-family:WebRupee">Rs. </span>~/if`<span id="excOfferPrice">~$exclusiveInfo.$k.OFFER_PRICE`</span></p>
                    <div class="sp15"></div>
                </div>
                ~else`
                <div id="~$k`" ~if $k neq 'X3'`style="display:none;"~/if`>
                    <p class="f25">For ~if $currency eq 'DOL'`<span>$</span>~else`<span style="color:#000000;font-family:WebRupee">Rs. </span>~/if`<span id="excPrice">~$exclusiveInfo.$k.PRICE`</span></p>
                    <div class="sp15"></div>
                </div>
                ~/if`
                ~/foreach`
                ~else`
                ~foreach from=$exclusiveInfo key=k item=v`
                <div id="~$k`" ~if $k neq 'X3'`style="display:none;"~/if`>
                    <p class="f25">For ~if $currency eq 'DOL'`<span>$</span>~else`<span style="color:#000000;font-family:WebRupee">Rs. </span>~/if`<span id="excPrice">~$exclusiveInfo.$k.PRICE`</span></p>
                    <div class="sp15"></div>
                </div>
                ~/foreach`
                ~/if`
            </div>
            <div class="sp15"></div>
            <form name="masterToPayment" id="masterToPayment" action="/membership/paymentOptions" method="post" target="_top">
                <input type="hidden" name="continueMainSubId" value="~$showTable`"></input>
            </form>
            <form name="js-exclusive" id="js-exclusive" action="/membership/paymentOptions" method="post" target="_top">
                <input type="hidden" name="jsExcRadioSel" id="jsExcRadioSel" value="~$jsDefaultSrvc`"/>
                <!--<button id="jsExclusive" class="carryForms mem-btn-green sprte-mem" title="Buy Now" style="margin-top:0px;cursor:pointer;width:105px;height:39px;" value="Buy Now">Buy Now</button>-->
                <input id="jsExclusive" class="carryForms mem-btn-green sprte-mem" title="Buy Now" style="margin-top:0px;cursor:pointer;width:98px;height:35px;line-height:39px;text-align:center;" value="Buy Now" onfocus="this.blur()" readonly />
            </form>
            <div class="sp15"></div>
            <div class="pos-rel"><div class="sp15" style="height:5px"></div>
            <form id="requestCallback" name="requestCallback" action="/membership/jsexclusiveDetail" method="post" target="_blank" >
                <a style="text-decoration:underline;cursor:pointer; line-height:23px" id="jsExcCall" href="#">Know more about<br/> this plan</a>
                <input type="hidden" name="profileid" value="~$userObj->profileid`"/>
                <!-- ~include_partial('global/membershipAlertLayout')` -->
            </form>
        </div>
    </div>
</div>
</div><!--Main container ends here-->
<script type="text/javascript">
    function autoPopupFreshdesk(username, email){
        var len = $("#fc_chat_layout").length;
        if(len){
            $("#fc_chat_layout").click();
            if($("#fc_chat_layout input[id*='name']").length){
                $("#fc_chat_layout input[id*='name']").val(username);
            }
            if($("#fc_chat_layout input[id*='email']").length){
                $("#fc_chat_layout input[id*='email']").val(email); 
            }
            $("#fc_chat_header").click();
        }
    }
    function autoPopulateFreshdeskDetails(username, email){
        if($("#lc_chat_layout input[id*='name']").length){
            var checkName = $("#lc_chat_layout input[id*='name']").val();
            if(checkName == ''){
                $("#lc_chat_layout input[id*='name']").val(username);
            }
        }
        if($("#lc_chat_layout input[id*='email']").length){
            var checkEmail = $("#lc_chat_layout input[id*='email']").val(); 
            if(checkEmail == ''){
                $("#lc_chat_layout input[id*='email']").val(email); 
            }
        }
    }
    $(document).ready(function(){
        $('#jsExcCall').click(function () {
            $("form#requestCallback").submit();
        });
    });
</script>
~include_partial('global/footer')`
