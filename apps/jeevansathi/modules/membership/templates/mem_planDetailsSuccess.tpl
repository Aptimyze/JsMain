<!--new css added -->
<style type="text/css">
.s-info-bar-mem{background:#d8d8d8;font-size:12px;color:#111;padding:10px 0px;}
.s-info-bar-mem:before, .s-info-bar-mem:after{display:table;line-height:0;content:""}
.s-info-bar-mem:after{clear:both}
.fntmem16{font-size:16px;}
.fntmem12{font-size:12px;}
.fntwe700{font-weight:bold; }
.blackclr{color:#000;}
.clor666{color:#666666;}
.clorgrey2{color:#333333;}
.membrdrbtm{border-bottom:1px solid #e1e1e1;}
.mempadcell{padding:20px 0px;}
.mempadcell15{padding:15px 0px;}
.mempadcel2{padding:10px 0px 20px 0px;}
.mempadcel3{text-align:center; padding-bottom:20px;}
.mempadcel4{padding-bottom:5px;}
.mempadcel5{padding-bottom:20px;}
.lh30{line-height:30px;}
#memtypes ul{ margin:0px; padding:0px 0px 0px 15px;}
a.callcust-btn {background-color: #f16c01;background-image: -moz-linear-gradient(top, #fcad3d, #f16c01);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fcad3d), to(#f16c01));background-image: -webkit-linear-gradient(top, #fcad3d, #f16c01);background-image: -o-linear-gradient(top, #fcad3d, #f16c01);background-image: linear-gradient(to bottom, #fcad3d, #f16c01);background-repeat: repeat-x;color: #fff;font-size: 13px;font-weight: bold;text-shadow: none;padding: 10px 0;width: 88%;}
ol,ul,li {list-style: disc outside none;}
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

<script>
var festDurBanner=new Array();
~foreach from=$festDurBanner key=k item=v`
festDurBanner["~$k`"]="~$v`";       
~/foreach`
</script>

<div id="main" class="clearfix">
	<div id="maincomponent">
        <!--end:header-->
		<div>
			<!-- start:Sub Title -->
			<section class="s-info-bar">
				<div class="pgwrapper clearfix">
					<div class="pull-left" style="padding-top: 7px; width:60%">
                    <div>Choose Duration</div>
                    <div>
                      ~if $countActiveServices ge 0`
                        ~if $countActiveServices eq 0 && $loginData`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You are a <span class="fntBld">Free</span> Member. Upgrade membership to initiate contact with your matches.</div>
                        ~else if $countActiveServices eq 0`
                            <div class="ftsize12 memmb_fnor memmb_pt5">Upgrade membership to initiate contact with your matches.</div>
                        ~else if $countActiveServices eq 1`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You are an <span class="fntBld">~$subStatus.0.SERVICE_NAME`</span> Member. Your membership expires on ~$subStatus.0.EXPIRY_DT`.</div>
                        ~else`
                            <div class="ftsize12 memmb_fnor memmb_pt5">You have the following Memberships:</div>
                            ~foreach from=$subStatus key=k item=v name=activeServicesLoop`
                                <div class="ftsize12 memmb_fnor memmb_pt5"><span class="fntBld">~$subStatus.$k.SERVICE_NAME`</span> ~$subStatus.$k.SERVICE_DURATION` ~if $subStatus.$k.SERVICE_DURATION eq '1'` Month ~else` Months ~/if`: Expires on ~$subStatus.$k.EXPIRY_DT`</div>
                            ~/foreach`
                        ~/if`
                      ~/if` 
                    </div>
                    </div>
                     <div class="pull-right">
                     <form name="formBack" action="~sfConfig::get('app_site_url')`/membership/mobileMembershipMaster" method="get"><a onclick="formBack.submit(); return false;" class="btn pre-next-btn wid100">Go Back</a>
                    <input type='hidden' id='mainSubMemId' name='mainSubMemId' value="~$mainSubMemId`"></input>
                    <input type='hidden' id='service' name='service' value="~$service`"></input> 
                    <input type='hidden' id="allMemberships" name='allMemberships' value="~$allMemberships`"></input> 
                    <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value=""/>
                    <input type="hidden" name="navigationStringToPayment" value=""/>
                    <input type="hidden" name="selectedStringToPayment" value=""/>
                    <input type="hidden" name="VASImpressionToPayment" value=""/>
                    <input type="hidden" name="showAllToPayment" value="1"/>
                    <input type="hidden" name="festActive" value="~$fest`"/>
                    </div>
                    </form> 
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
            <!--start:page-->
            <div id="memtypes">
            	 <!--start-->
                 ~foreach from=$tabs key=k item=v name=tabsLoop`
                 <form name="form~$k`" action="~sfConfig::get('app_site_url')`/membership/mobilePaymentOptions" method="post">
                  ~if $userObj->userType eq 6 or $userObj->userType eq 4 or $discountActive eq '1' or $specialActive eq '1' or $fest eq '1'`
                 <section class="membrdrbtm  mempadcell">
                 	<div class="pgwrapper">
                    	<div class="clearfix">
                            ~assign var='mainMemDur' value="~$memID`~$k`"`
                        	<div class="fntmem16 blackclr memmb_pt7 pull-left">
                                ~if ($allMainMem.$memID.$mainMemDur.OFFER_PRICE neq $v.PRICE) or ($fest eq 1 and $k eq 'L')`
                                  <div class="fntmem16 blackclr pull-right">~$allMainMem.$memID.$mainMemDur.OFFER_PRICE`</div>
                                ~/if`
                                <div class="clrboth">~if $k eq 'L'`Unlimited ~else` ~$k` Months ~/if`for ~if $currency eq 'DOL'`$~else`Rs. ~/if` ~if ($allMainMem.$memID.$mainMemDur.OFFER_PRICE neq $v.PRICE) or ($fest eq 1 and $k eq 'L')`<span class="strikeout memmb_clr2 clrboth"><span class="blackclr">~$v.PRICE`</span></span>~else` ~$v.PRICE` ~/if`</div>
                            </div>
                            ~if $allMainMem.$memID.$mainMemDur.OFFER_PRICE neq $v.PRICE`
                              <div class="mtop20 pull-right"><a onclick="form~$k`.submit(); return false;" class="btn pre-next-btn">Buy</a></div>
                            ~else`
                              <div class="pull-right"><a onclick="form~$k`.submit(); return false;" class="btn pre-next-btn">Buy</a></div>
                            ~/if`
                        </div>
                        ~if $festDurBanner.$k and $k neq 'L' and $k neq '12'`
                          <div class="ftsize12 memmb_clr2 memmb_pb9">~$festDurBanner.$k`</div>
                        ~/if`
                        <ul class="fntmem12 clor666">
                        	<li><span class="fntwe700">~$v.CALL` </span>Instant Phone/Email Views</li>
                            <li><span class="fntwe700">Unlimited </span>Phone/Email Views after Acceptance</li>
                        </ul>
                    </div>
                    <input type='hidden' id='mainSubMemId' name='mainSubMemId' value="~$memID`~$k`"></input>
                    <input type='hidden' id='service' name='service' value="~$service`"></input>  
                    <input type='hidden' id="allMemberships" name='allMemberships' value="main~$memID`~$k`"></input> 
                    <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value="~$memID`~$k`"/>
                    <input type="hidden" name="navigationStringToPayment" value="~$memID`~$k`"/>
                    <input type="hidden" name="selectedStringToPayment" value="main~$memID`~$k`"/>
                    <input type="hidden" name="VASImpressionToPayment" value=""/>
                    <input type="hidden" name="showAllToPayment" value="1"/> 
                 </section>
                 </form>
                 ~else`
                 <section class="membrdrbtm  mempadcell">
                  <div class="pgwrapper">
                      <div class="clearfix">
                          <div class="fntmem16 blackclr memmb_pt7 pull-left">
                                <div class="clrboth">~if $k eq 'L'`Unlimited ~else` ~$k` Months ~/if`for ~if $currency eq 'DOL'`$~else`Rs. ~/if` ~$v.PRICE`</div>
                            </div>
                            <div class="pull-right"><a onclick="form~$k`.submit(); return false;" class="btn pre-next-btn">Buy</a></div>
                        </div>
                        <ul class="fntmem12 clor666">
                           <li><span class="fntwe700">~$v.CALL` </span>Instant Phone/Email Views</li>
                           <li><span class="fntwe700">Unlimited </span>Phone/Email Views after Acceptance</li>
                        </ul>
                    </div>
                    <input type='hidden' id='mainSubMemId' name='mainSubMemId' value="~$memID`~$k`"></input> 
                    <input type='hidden' id='service' name='service' value="~$service`"></input> 
                    <input type='hidden' id="allMemberships" name='allMemberships' value="main~$memID`~$k`"></input> 
                    <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value="~$memID`~$k`"/>
                    <input type="hidden" name="navigationStringToPayment" value="~$memID`~$k`"/>
                    <input type="hidden" name="selectedStringToPayment" value="main~$memID`~$k`"/>
                    <input type="hidden" name="VASImpressionToPayment" value=""/>
                    <input type="hidden" name="showAllToPayment" value="1"/> 
                 </section>
                 </form>
                 ~/if`
                 ~/foreach`
                 <!--end--> 
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
