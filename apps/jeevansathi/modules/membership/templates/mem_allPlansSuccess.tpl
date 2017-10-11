<!--new css added -->
<style type="text/css">
.s-info-bar-mem{background:#d8d8d8;font-size:12px;color:#111;padding:10px 0px;}
.s-info-bar-mem:before, .s-info-bar-mem:after{display:table;line-height:0;content:""}
.s-info-bar-mem:after{clear:both}
.fntmem16{font-size:16px;}
.fntmem12{font-size:12px;}
.fntmem13{font-size:13px;}
.fntwe700{font-weight:bold; }
.blackclr{color:#000;}
.clor666{color:#666666;}
.membrdrbtm{border-bottom:1px solid #e1e1e1;}
.mempadcell{padding:20px 0px;}
.mempadcell15{padding:15px 0px;}
.mempadcel3{text-align:center; padding-bottom:20px;}
.mempadcel4{padding-bottom:5px;}
.lh30{line-height:30px;}
#memtypes ul{ margin:0px; padding:0px 0px 0px 11px;}
.wid100{width:100px !important;}
a.callcust-btn {background-color: #f16c01;background-image: -moz-linear-gradient(top, #fcad3d, #f16c01);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fcad3d), to(#f16c01));background-image: -webkit-linear-gradient(top, #fcad3d, #f16c01);background-image: -o-linear-gradient(top, #fcad3d, #f16c01);background-image: linear-gradient(to bottom, #fcad3d, #f16c01);background-repeat: repeat-x;color: #fff;font-size: 13px;font-weight: bold;text-shadow: none;padding: 10px 0;width: 88%;}
ol,ul,li {list-style: disc outside none;}
ol,ul,li {margin-left: 8px;list-style: decimal outside none;}
.memmb_fnor{font-weight:normal;}
.memmb_pt5{padding-top:5px}
.memmb_pb5{padding-bottom:5px}
.memmb_pt7{padding-top:7px}
.memmb_pt9{padding-top:9px}
.mtop20{margin-top: 20px !important;}
.memmb_pb9{padding-bottom:9px}
.memmb_plnv{padding-left:130px}
.memmb_clr1{color:#333333}
.memmb_clr2{color:#931313}
.clrboth{clear:both;}
.s-drkblue-bar {
    background: none repeat scroll 0 0 #42688f;
    color: #fff;
    padding: 10px 0;
    font-size:15px
}
.s-drkblue-bar:after {
    clear: both;
}
.s-drkblue-bar:before, .s-drkblue-bar:after {
    content: "";
    display: table;
    line-height: 0;
}
.strikeout{text-decoration: line-through;}
b, strong, .fwB {font-weight: normal !important;}
.fntBld{font-weight: bold;}
#membership_band strong{
    font-weight: bold !important;
}
</style>

<div id="main" class="clearfix">
	<div id="maincomponent">
        <!--end:header-->
		<div>
			<!-- start:Sub Title -->
			<section class="s-info-bar">
				<div class="pgwrapper">
					<div>Membership Options</div>~if $countActiveServices ge 0`
                        ~if $countActiveServices eq 0 && $loginData`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You are a <span class="fntBld">Free</span> Member. Upgrade membership to initiate contact with your matches.</div>
                        ~else if $countActiveServices eq 0`
                            <div class="ftsize12 memmb_fnor memmb_pt5">Upgrade membership to initiate contact with your matches.</div>
                        ~else if $countActiveServices eq 1`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You are an  <span class="fntBld">~$subStatus.0.SERVICE_NAME`</span> Member. Your membership expires on ~$subStatus.0.EXPIRY_DT`.</div>
                        ~else`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You have the following Memberships:</div>
                            ~foreach from=$subStatus key=k item=v name=activeServicesLoop`
                                <div class="ftsize12 memmb_fnor memmb_pt5"> <span class="fntBld">~$subStatus.$k.SERVICE_NAME`</span> ~$subStatus.$k.SERVICE_DURATION` ~if $subStatus.$k.SERVICE_DURATION eq '1'` Month ~else` Months ~/if`: Expires on ~$subStatus.$k.EXPIRY_DT`</div>
                            ~/foreach`
                        ~/if` 
                    ~/if`
				</div>
			</section>
            <!-- end:Sub Title -->
            <!-- start:Sub Title -->
            ~if $userObj->userType eq 6 or $userObj->userType eq 4 or $specialActive eq '1' or $discountActive eq '1' or $fest eq '1'`
            <section class="s-drkblue-bar" style="display:none;">
                <div class="pgwrapper">
                ~if $userObj->userType eq 6 or $userObj->userType eq 4`
                    ~if $fest eq 1` <!-- Renewal Discount + Festive Offer -->
                        <div>Renew before ~$userObj->expiryDate` and get ~$renewalPercent`% off/extra months on all plans</div>
                    ~else`
                        <div>Renew before ~$userObj->expiryDate` and get ~$renewalPercent`% off on all plans</div>
                    ~/if`
                ~else if $specialActive eq 1` <!-- Variable Discount -->
                    ~if $fest eq '1'` <!-- Variable Discount + Festive Offer -->
                        <div>Upgrade before ~$variable_discount_expiry` and get upto ~$discountSpecial`% off/extra months on plans</div>
                    ~else`
                        <div>Upgrade before ~$variable_discount_expiry` and get upto ~$discountSpecial`% off on plans</div>
                    ~/if`
                ~else if $discountActive eq 1` <!-- Offer Discount -->
                    ~if $fest eq 1` <!-- Offer Discount + Festive Offer -->
                        <div>Upgrade before ~$discount_expiry` and get upto ~$discountPercent`% off/extra months on plans</div>
                    ~else`
                        <div>Upgrade before ~$discount_expiry` and get upto ~$discountPercent`% off on plans</div>
                    ~/if`
                ~else if $fest eq 1` <!-- Festive Offer only -->
                    <div>Upgrade before ~$festEndDt` and get extra months/attractive discounts</div>
                ~/if`
              </div>
            </section>
            ~/if`
            <!-- end:Sub Title --> 
            <!--start:page-->
            	<div id="memtypes">
                    <!--start:free-->
                    <section class="membrdrbtm  mempadcell">
                        <div class="pgwrapper" >
                            <div class="fntmem16 blackclr lh30">Free Membership</div>
                            <ol class="clor666 fntmem13">
                                <li>Send/Receive Interests</li>
                                <li>Reply to Messages and Chat</li>
                            </ol>
                        </div>
                    </section>
                    <!--end:free--> 
                    <!--start:services-->
                    ~foreach from=$activeServices key=k item=v name=serviceLoop`
                    <form name="form~$k`" action="~sfConfig::get('app_site_url')`/membership/mobileMembershipPlanDetails" method="get" accept-charset="utf-8">
                    ~if not $smarty.foreach.serviceLoop.last`
                    <section class="membrdrbtm  mempadcell">
                    ~else`
                    <section class="membrdrbtm mempadcell">
                    ~/if`
                    ~if $userObj->userType eq 6 or $userObj->userType eq 4 or ($discountActive eq '1' and $minPriceArr.$v.PRICE_INR neq $minPriceArr.$v.OFFER_PRICE) or ($specialActive eq '1' and $minPriceArr.$v.PRICE_INR neq $minPriceArr.$v.OFFER_PRICE)`
                        <div class="pgwrapper" >
                            <div class="clearfix">
                                <div class="fntmem16 blackclr lh30 pull-left">~$serviceName[~$k`]` Membership</div>
                                <div class="pull-right"><a onclick="form~$k`.submit(); return false;" class="btn pre-next-btn wid100">See Plans</a></div>
                            </div>
                            <div class="ftsize12 memmb_clr1 memmb_pb9">Starting @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`<span class="strikeout memmb_clr2 clrboth"><span class="ftsize12 memmb_clr1">~if $currency eq 'DOL'`~$minPriceArr.$v.PRICE_USD` ~else`~$minPriceArr.$v.PRICE_INR`~/if`</span></span>&nbsp;&nbsp;~$minPriceArr.$v.OFFER_PRICE`</div>
                            <input type="hidden" name="service" value="~$v`">
                            <ol class="clor666 fntmem13">
                                ~foreach from=$messages[~$k`] key=kk item=vv`
                                    <li>~$vv`</li>
                                ~/foreach`
                            </ol>
                            <input type='hidden' id='mainSubMemId' name='mainSubMemId' value="~$v`"></input> 
                            <input type='hidden' id="allMemberships" name='allMemberships' value="~$allMemberships`"></input> 
                            <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value=""/>
                            <input type="hidden" name="navigationStringToPayment" value=""/>
                            <input type="hidden" name="selectedStringToPayment" value=""/>
                            <input type="hidden" name="VASImpressionToPayment" value=""/>
                            <input type="hidden" name="showAllToPayment" value="1"/>                          
                        </div>
                    ~else`
                        <div class="pgwrapper" >
                            <div class="clearfix">
                                <div class="fntmem16 blackclr lh30 pull-left">~$serviceName[~$k`]` Membership</div>
                                <div class="pull-right"><a onclick="form~$k`.submit(); return false;" class="btn pre-next-btn wid100">See Plans</a></div>
                            </div>
                            <div class="ftsize12 memmb_clr1 memmb_pb9">Starting @ ~if $currency eq 'DOL'`$~$minPriceArr.$v.PRICE_USD` ~else`Rs. ~$minPriceArr.$v.PRICE_INR`~/if`</div>
                            <input type="hidden" name="service" value="~$v`">
                            <ol class="clor666 fntmem13">
                                ~foreach from=$messages[~$k`] key=kk item=vv`
                                    <li>~$vv`</li>
                                ~/foreach`
                            </ol>
                            <input type='hidden' id='mainSubMemId' name='mainSubMemId' value="~$v`"></input> 
                            <input type='hidden' id="allMemberships" name='allMemberships' value="~$allMemberships`"></input> 
                            <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value=""/>
                            <input type="hidden" name="navigationStringToPayment" value=""/>
                            <input type="hidden" name="selectedStringToPayment" value=""/>
                            <input type="hidden" name="VASImpressionToPayment" value=""/>
                            <input type="hidden" name="showAllToPayment" value="1"/>                          
                        </div>
                    ~/if`
                    </section>
                    </form>
                    ~/foreach`
                    <!--end:services-->
                    <section class="mempadcell">
                    <div class="pgwrapper fntmem12 mempadcel3">
                        <div class="lh30 fntmem16 blackclr">
                        Need help on which plan to buy?
                        </div>  
                        <!-- <div class="mempadcel2 blackclr">
                            To see additional discounts &amp; special offers (if any), call customer care or visit Desktop site
                        </div> --> 
                        ~if $profileid neq ''`
                        <div class="mempadcel4">
                            <a style="cursor:pointer;" id="excCallNew" href="~sfConfig::get('app_site_url')`/membership/addCallBck" class="btn callcust-btn">Request a Call Back</a>
                        </div>
                        <div class="mempadcell15 fntmem16">OR</div>
                        ~/if`
                        <div class="mempadcel4">
                            <a href="tel:18004196299" class="btn callcust-btn">Call Customer Service</a>                            
                        </div>
                        <div>(From 9 AM to 9 PM)</div>
                    </div>              
                    </section>
                </div>           
            <!--end:page-->
        </div>
	</div>
</div>
