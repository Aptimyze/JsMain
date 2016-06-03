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
.membrdrbtm{border-bottom:1px solid #e1e1e1;}
.mempadcell{padding:20px 0px;}
.mempadcell15{padding:0 0 10px 0;}
.mempadcel3{text-align:center; padding-bottom:20px;}
.mempadcel4{padding-bottom:5px;}
.lh30{line-height:30px;}
#memtypes ul{ margin:0px; padding:0px 0px 0px 11px;}
.wid100{width:100px !important;}
a.callcust-btn {background-color: #f16c01;background-image: -moz-linear-gradient(top, #fcad3d, #f16c01);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fcad3d), to(#f16c01));background-image: -webkit-linear-gradient(top, #fcad3d, #f16c01);background-image: -o-linear-gradient(top, #fcad3d, #f16c01);background-image: linear-gradient(to bottom, #fcad3d, #f16c01);background-repeat: repeat-x;color: #fff;font-size: 13px;font-weight: bold;text-shadow: none;padding: 10px 0;width: 88%;}
ol,ul,li {list-style: disc outside none;}
ol,ul,li {margin-left: 8px;list-style: decimal outside none;}
ol,ul,li {margin-left: 8px;list-style: decimal outside none;}

/* new css added */
.memmb_fnor{font-weight:normal;}
.memmb_pt5{padding-top:5px}
.memmb_pb5{padding-bottom:5px}
.memmb_pt7{padding-top:7px}
.memmb_pt9{padding-top:9px}
.memmb_pb9{padding-bottom:9px}
.memmb_plnv{padding-left:130px}
.memmb_clr1{color:#333333}
.memmb_clr2{color:#931313}
.memmb_clr4{color:#666666}
.memmb_f16{font-size:16px !important; font-weight:normal !important}
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
.sumbox{margin:0 auto 10px; width:88%;background-color:#f2f2f2; text-align:center;}
.memmb_f14{font-size:14px;}
.memmb_wid1{width:58%;}
.memmb_wid2{width:41%}
.memmb_mt20{margin-top:20px}
ol.benefit li{color:#666666;font-size:12px; line-height:18px}
.sumboxad1{padding:19px; font-size:25px;}
.fntmem13{font-size:13px;}
.memmb_p1{padding-left:63%}
.memmb_pl8{padding-left:8px;}

.memmb_pt3{padding-top:3px}
.memmb_pl8{padding-left:8px;}
.memme_bg1{background-color:#f2f2f2;}
.membm_fw{width:100%}
b, strong, .fwB {font-weight: normal !important;}
</style>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
	function trackPaymentAndSubmit(){
		var navigationString=$("[name=navigationString]").val();
		var track_discount=parseInt($("[name=track_discount]").val());
		var track_total=parseInt($("[name=track_total]").val());
		var track_memberships=$("[name=track_memberships]").val();
		$.post("/membership/PaymentOptionsTracking",{ 'navigationString' : navigationString,'track_discount':track_discount,'track_total':track_total,'track_memberships':track_memberships},function(response){
		});
		setTimeout(function(){$("#makePaymentForm").submit();},200);
	}

	$(document).ready(function(){
		var track_discount      =$("[name=track_discount]").val();
		var track_total         =$("[name=track_total]").val();
		var track_memberships   =$("[name=track_memberships]").val();
		var trackType           ='F';
		data1 ={"track_total":track_total,"track_discount":track_discount,"track_memberships":track_memberships,"ajax_error":2,"Submit":1,"trackType":trackType};
		url ="/membership/PaymentOptionsTracking";
		$.ajax({
			type: 'POST',
			url: url,
			data: data1,
			success:function(data){
				response = data;
			}
		});
	});
</script>

<div id="main" class="clearfix">
	<div id="maincomponent">
		<!--end:header-->
		<div>
			<!-- start:Sub Title -->
			<section class="s-info-bar">
				<div class="pgwrapper clearfix">
					<div class="pull-left memmb_pt9">Purchase Summary</div>
					<div class="pull-right">
					<form name="formBack" action="~sfConfig::get('app_site_url')`/membership/mobileMembershipPlanDetails" method="get"><a style="display:none;" onclick="formBack.submit(); return false;" class="pull-right btn pre-next-btn wid100">Go Back</a>
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
			<!-- start:page -->
			<div id="memtypes"> 

				<!--start:summary-->
				<section>
					<div class="pgwrapper mempadcel3">
						<div class="wd_fp memmb_mt20">
							<div class="memmb_clr4 pull-left clor666 fntmem13 app_txtl memmb_wid1">Membership Plan</div>
							<div class="pull-left blackclr fntmem13 app_txtl memmb_wid2">: ~$mainMemName`</div>
							<div class="clear"></div>
						</div>
						<div class="wd_fp memmb_pt9">
							<div class="memmb_clr4 pull-left clor666 fntmem13 app_txtl memmb_wid1">Duration</div>
							<div class="pull-left blackclr fntmem13 app_txtl memmb_wid2">:~if $mainMemDuration eq 'L'` Unlimited ~else` ~$mainMemDuration` Months ~/if`</div>
							<div class="clear"></div>
						</div>
						<div class="wd_fp memmb_pt9">
							<div class="memmb_clr4 pull-left clor666 fntmem13 app_txtl memmb_wid1">Instant Phone/Email Views</div>
							<div class="pull-left blackclr fntmem13 app_txtl memmb_wid2">: ~$instaContacts`</div>
							<div class="clear"></div>
						</div>
						<div class="wd_fp memmb_pt9">
							<div class="memmb_clr4 pull-left clor666 fntmem13 app_txtl memmb_wid1">Contacts after acceptance</div>
							<div class="pull-left blackclr fntmem13 app_txtl memmb_wid2">: Unlimited</div>
							<div class="clear"></div>
						</div>
						~if $valueAddedServices eq '1'` 
						<div class="wd_fp memmb_pt9">
							<div class="memmb_clr4 pull-left clor666 fntmem13 app_txtl memmb_wid1">Additional Services</div>
							<div class="pull-left blackclr fntmem13 app_txtl memmb_wid2">
							  ~foreach from=$backendVAS key=kk item=vv name=vasLoop`
		                      <div class="membm_fw">
		                      	~if $smarty.foreach.vasLoop.first`
		                        	<div>: ~$vaMem[$kk][$vv]['NAME']`</div>
		                        ~else`
		                        	<div class="memmb_pl8">~$vaMem[$kk][$vv]['NAME']`</div>
		                        ~/if`
		                        ~if $kk eq 'I'`
		                        	<div class="memmb_clr4 ftsize12 app_txtl memmb_pl8 memmb_pb9">for ~$vaMem[$kk][$vv]['DURATION']` profiles</div>
		                        ~else`
		                        	<div class="memmb_clr4 ftsize12 app_txtl memmb_pl8 memmb_pb9">for ~$vaMem[$kk][$vv]['DURATION']` months</div>
		                        ~/if`
		                      </div>
		                      ~/foreach` 
		                    </div>
						</div>
						~/if` 
					</div>
				</section>
				<!--end:summary-->
				<!--start:benefit-->
				<section>
					<div class="pgwrapper mempadcel3">
						<div class="app_txtl blackclr ftsize12">Benefits of your membership</div>
						<ol class="app_txtl benefit memmb_pt5">
						~foreach from=$messages key=kk item=vv`
                        	<li>~$vv`</li>
                        ~/foreach`                    
						</ol>
					</div>
				</section>
				<!--end:benefit-->
				<section>
					<div class="pgwrapper mempadcel3">
						<div class="sumbox">
						~if $userObj->userType eq 6 or $userObj->userType eq 4 or $discountActive eq '1' or $specialActive eq '1' or $fest eq '1'`
							~if $allMainMem.$memID.$mainSubMemId.OFFER_PRICE neq $allMainMem.$memID.$mainSubMemId.PRICE`
								<div class="memmb_clr1 sumboxad1"> Total : ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$allMainMem.$memID.$mainSubMemId.OFFER_PRICE`</div>
							~else`
								<div class="memmb_clr1 sumboxad1"> Total : ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$allMainMem.$memID.$mainSubMemId.PRICE`</div>
							~/if`
						~else`
							<div class="memmb_clr1 sumboxad1"> Total : ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$allMainMem.$memID.$mainSubMemId.PRICE`</div>
						~/if`
						</div>
						<div class="mempadcel4">

						<form id="makePaymentForm" name="makePaymentForm" action="~sfConfig::get('app_site_url')`/api/v3/membership/membershipDetails" method="POST"><a onclick="trackPaymentAndSubmit(); return false;" class="btn callcust-btn memmb_f16">Proceed to Pay</a>
							<input type="hidden" name="processPayment" value="1">
							<input type="hidden" name="mainMembership" value="~$mainSubMemId`">
							<input type="hidden" name="vasImpression" value="">
		                    <input type="hidden" name="profileid" value="~$profileid`"/>
		                    <input type="hidden" name="checksum" value="~$profileChecksum`"/>
		                    <input type="hidden" name="profilechecksum" value="~$profilechecksum`"/>
		                    <input type="hidden" name="curtype" value="~$currency`"/>
		                    <input type="hidden" name="type" value="~$currency`"/>
		                    <input type="hidden" name="fromBackend" value="~$fromBackend`"/>
		                    <input type="hidden" name="backendId" value="~$backendId`"/>
		                    <input type="hidden" name="reqid" value="~$reqid`"/>
		                    <input type="hidden" name="discountBackend" value="~$discountBackend`"/>
		                    <input type="hidden" name="discSel" value="~$backendCheckSum`"/>
		                    <input type="hidden" name="backendCheckSum" value="~$backendCheckSum`"/>
		                    <input type="hidden" name="device" value="~$device`"/>
		                    <input type="hidden" name="track_memberships" value="~$mainSubMemId`"></input>
		                    <input type="hidden" name="track_discount" value="~$track_discount`"/>
	                    	<input type="hidden" name="track_total" value="~$track_total`"/>
                    		<input type="hidden" name="paymentMode" value="CR"/>
                    		<input type="hidden" name="cardType" value="card1"/>
						</form>
						</div>
					</div>
				</section>
			</div>
			<!--end:page-->
		</div>
	</div>
</div>
