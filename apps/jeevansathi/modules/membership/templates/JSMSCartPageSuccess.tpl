<script type="text/javascript">
	~if $logoutCase eq '1'`
	url = window.location.href.replace(window.location.pathname,"/api/v3/membership/membershipDetails");
	console.log(url);
	$.ajax({
		type: 'POST',
		url: url,
		success:function(response){
			CommonErrorHandling(response);
		}
	});
	~/if`
</script>
~if $logoutCase neq '1'`
<meta name="format-detection" content="telephone=no">
<div class="fullwid">
	<!--start:header-->
	<div class="bg1">
		<div class="rv2_pad1 txtc">
			<div class="posrel white">
				<div id="pageTitle" class="f19 fontthin">~$data.title`</div>
				<div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2 cursp"></i></div>
			</div>
		</div>
	</div>
	<!--end:header-->
	<div id="removeOverlay" style="display: none;">
		<!--start:overlay-->
		<div class="tapoverlay posabs"></div>
		<!--end:overlay-->
		<!--start:content overlay-->
		<div class="posabs rv2_pos5 rv2_z102 fullwid">
			<div class="disptbl fullwid" id="ContLayer">
				<div class="dispcell vertmid">
					<div class="bg4">
						<div class="txtc rv2_pad15 f16 fontreg ">
							<div class="rv2_sprtie1 rv2_removeic"></div>
							<div id="removeServ" class="color3 pt10"></div>
							<div id="removeMsg" class="pt10 rv2_colr2"></div>
						</div>
						<div class="disptbl brdr21 fullwid fontlig f18 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if`">
							<div id="removeBtn" vasId="" memId="" class="dispcell rv2_brdrright1 wid50p txtc rv2_lh60 cursp">Remove</div>
							<div id="removeCncl" class="dispcell wid50p txtc cursp">Cancel</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end:content overlay-->
	</div>
	<div id="changeWithCouponOverlay" style="display: none;">
		<!--start:overlay-->
		<div class="tapoverlay posabs"></div>
		<!--end:overlay-->
		<!--start:content overlay-->
		<div class="posabs rv2_pos5 rv2_z102 fullwid">
			<div class="disptbl fullwid" id="ContLayerCoup">
				<div class="dispcell vertmid">
					<div class="bg4">
						<div class="txtc rv2_pad15 f16 fontreg ">
							<div class="rv2_sprtie1 rv2_removeic"></div>
							<div id="removeServCoup" class="color3 pt10"></div>
							<div id="removeMsgCoup" class="pt10 rv2_colr2"></div>
						</div>
						<div class="disptbl brdr21 fullwid fontlig f18 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if`">
							<div id="changeBtn" vasId="" memId="" class="dispcell rv2_brdrright1 wid50p txtc rv2_lh60 cursp">Remove</div>
							<div id="removeCnclCoup" class="dispcell wid50p txtc cursp">Cancel</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end:content overlay-->
	</div>
	<!--start:main body-->
	<div class="rv2_bg1">
		<div class="rv2_pad5" style="padding-bottom:50px;">
			~if $data.cart_items.main_memberships`
			<!--start:div-->
			~foreach from=$data.cart_items.main_memberships key=k item=v name=mainServLoop`
			<div id="membershipCard" class="pt10">
				<div class="rv2_boxshadow">
					<div class="bg4 rv2_pad3">
						<!--start:strike through-->
						~if $v.price_strike`
						<p id="membershipPriceStrike" class="strike rv2_colr1 txtr f14">~$data.currency`~$v.price_strike`</p>
						~/if`
						<!--end:strike through-->
						<!--start:plan-->
						<div class="disptbl fullwid color7 rv2_brdrbtm1 pb10">
							<div id="membershipName" class="dispcell rv2_wid4 rv_ft1">~$v.service_name` <span id="membershipSideTitle" class="rv2_colr2 f12">~$v.sideTitle`</span></div>
							<div id="membershipPrice" class="dispcell rv2_wid3 txtr f18"><span>~$data.currency`</span>~$v.price`</div>
						</div>
						<!--end:plan-->
						<!--start:div-->
						<div class="disptbl fullwid">
							<!--start:left part-->
							<div class="dispcell">
								<div class="rv2_list3">
									<ul>
										<li id="membershipDuration" class="rv_ft2">~$v.service_duration`</li>
										<li id="membershipContacts" class="rv_ft2">~$v.service_contacts`</li>
									</ul>
								</div>
							</div>
							<!--end:left part-->
							<!--start:right part-->
							<div name="~$v.service_name`" memId="~$data.subscription_id`" class="dispcell rv2_colr1 rv_ft3 fontlig txtr rv2_vb mainMem">
								~if $v.remove_text`
								<span id="membershipRemoveButton" class="dispibl cursp removeCall">~$v.remove_text`</span>
								<span class="dispibl rv2_pad11">|</span>
								~/if`
								~if $v.change_text && $data.upgradeMem neq "MAIN"`
								<span id="memnbershipChangeButton" class="dispibl cursp changeCall">~$v.change_text`</span>
								~/if`
							</div>
							<!--end:right part-->
						</div>
						<!--end:div-->
					</div>
				</div>
			</div>
			<!--end:div-->
			~/foreach`
			~/if`
			~if $data.cart_items.vas_memberships`
            ~if !($data.totalVasCount eq "1" and $data.cart_items.vas_memberships[0].service_name eq "Profile Boost")`
			<!--start:div-->
			<div id="vasCard" class="pt10">
				<div class="rv2_boxshadow">
					<div class="bg4 rv2_pad3">
						~foreach from=$data.cart_items.vas_memberships key=k item=v name=vasServLoop`
                        ~if $v.service_name neq "Profile Boost"`
						<!--start:VAS plan-->
						<!--start:strike through-->
						~if $v.vas_price_strike`
						<p id="~$v.vas_id`_PriceStrike" class="strike rv2_colr1 txtr f14">~$data.currency`~$v.vas_price_strike`</p>
						~/if`
						<!--end:strike through-->
						<div>
							<!--start:plan-->
							<div class="disptbl fullwid color7">
								<div id="~$v.vas_id`_Name" class="dispcell rv2_wid4 rv_ft1 rv2_colr1">~$v.service_name`</div>
								<div id="~$v.vas_id`_Price" class="dispcell rv2_wid3 txtr f18"><span>~$data.currency`</span>~if $v.vas_price`~$v.vas_price`~else`0~/if`</div>
							</div>
							<!--end:plan-->
							<!--start:div-->
							<div class="disptbl fullwid">
								<!--start:left part-->
								<div class="dispcell">
									<div class="rv2_list3">
										<ul>
											<li id="~$v.vas_id`_Duration" class="rv_ft2">~$v.service_duration`</li>
										</ul>
									</div>
								</div>
								<!--end:left part-->
								<!--start:right part-->
								<div name="~$v.service_name`" msg="~$v.service_duration` - ~$data.currency`~$v.vas_price`" vasId="~$v.vas_id`" class="dispcell rv2_colr1 rv_ft3 fontlig txtr rv2_vb vasServ">
									~if $v.remove_text`
									<span id="~$v.vas_id`_RemoveButton" class="dispibl cursp removeCall">~$v.remove_text`</span>
									<span class="dispibl rv2_pad11">|</span>
									~/if`
									~if $v.change_text`
									<span id="~$v.vas_id`_ChangeButton" class="dispibl cursp changeCall">~$v.change_text`</span>
									~/if`
								</div>
								<!--end:right part-->
							</div>
							<!--end:div-->
						</div>
						<!--end:VAS plan-->
                        ~if $data.subscription_id neq 'NCP'`
                            ~if not $smarty.foreach.vasServLoop.last`
                            <div class="pad9"><div class="rv2_top2"></div></div>
                            ~/if`
                        ~/if`
                        ~/if`
						~/foreach`
					</div>
				</div>
			</div>
			<!--end:div-->
            ~/if`
			~/if`
			<!--start:div-->
			<div class="rv2_pad3">
				<!--start:total pay div-->
				~if $data.coupon_discount_text`
				<div class="disptbl fullwid pb10">
					<div id="totalText" class="dispcell f16 color7 wid30p">~$data.actual_total_text`</div>
					<div id="cartFinalPrice" class="dispcell txtr f16 wid70p padr10"><span>~$data.currency`</span>~$data.actual_total_price`</div>
				</div>
				<div class="disptbl fullwid rv2_brdrbtm3 pb10">
					<div id="couponPriceText" class="dispcell f16 color7 wid60p">~$data.coupon_discount_text`</div>
					<div id="couponDiscount" class="dispcell txtr f16 wid70p padr10"><span>~$data.currency`</span>~if $data.upgradeMem &&  $data.upgradeMem neq 'NA'`  ~$data.coupon_discount` ~else` ~$data.cart_discount` ~/if`</div>
				</div>
				~/if`
				<div class="disptbl fullwid rv2_brdrbtm3 pt10 pb10">
					<div id="cartText" class="dispcell f16 color7 wid30p">~$data.cart_price_text`</div>
					<div id="cartPrice" class="dispcell txtr f16 wid70p padr10"><span>~if $data.currency eq '$'`USD ~else`~$data.currency`~/if`</span>~$data.cart_price`</div>
				</div>
				<!--end:total pay div-->
				<div id="cartTaxText" class="rv2_colr2 fontlig f11 pt5 padl10">~$data.cart_tax_text`</div>
				~if $data.apply_coupon_text`
				<div id="enterCouponBtn" class="txtc pt22 f16 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` fontlig cursp">~$data.apply_coupon_text`</div>
				~/if`
				<div id="cartBottomText" class="txtc f16 color8 fontlig pt30">~$data.cart_bottom_text`</div>
				
			</div>
			<!--end:div-->
		</div>
	</div>
	<!--start:main body-->
	<!--start:continue button-->
	<div style="overflow:hidden;position: fixed;height: 61px;" class="fullwid disp_b btmo">
	<div id="continueBtn" class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp posfix btmo pinkRipple">~$data.continueText`</div>
	</div>
	<!--end:continue button-->
</div>
<script type="text/javascript">
	var AndroidPromotion = 0;
	var skipVasPageMembershipBased = JSON.parse("~$data.skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
	~if $data.backendLink`
		~if $data.cart_items.main_memberships`
			createCookie('mainMem', '~$data.subscription_id`', 0);
			createCookie('mainMemDur', '~$data.subscription_duration`', 0);
		~/if`
		~if $data.cart_items.vas_memberships`
			~if $data.subscription_id neq 'ESP' and $data.subscription_id neq 'NCP'`
				var cookVasArr = new Array();
				~foreach from=$data.cart_items.vas_memberships key=k item=v name=vasServLoop`
					cookVasArr.push('~$v.vas_id`');
				~/foreach`
				var finalVasStr = cookVasArr.join(",");
	        	createCookie('selectedVas', finalVasStr, 0);
        	~/if`
		~/if`
	~/if`
	$(document).ready(function(){
		var upgradeMem = "~$data.upgradeMem`";
		$('html').addClass('rv2_bg1');
		$("#continueBtn").show();
		~if $data.coupon_success`
		if(~$data.coupon_success`){
			$('html, body').animate({
				scrollTop: $(window).height()+100
			}, 1000);
		}
		~/if`
		$(".removeCall").click(function(e){
			e.preventDefault();
			$("#removeOverlay").show();
			setRemoveOverlayHeight();
			var couponCheck = ~if $data.coupon_success` ~$data.coupon_success` ~else` 0 ~/if`;
			historyStoreObj.push(clearOverlay,"#overlay");
			$(window).scrollTop(0);
			$('html, body, #mainContent').css({
				'overflow': 'hidden',
				'height': '100%'
			});
			if(checkEmptyOrNull(couponCheck) && couponCheck != 0){
				$("#removeOverlay #removeServ").text('Coupon code will be removed');
				$("#removeOverlay #removeMsg").text('You can apply it again after you are done with your changes');
				$("#removeOverlay #removeBtn").text('Okay');
			}
			if($(this).parent().hasClass('mainMem')){
				var memName = $(this).parent().attr('name');
				var memId = $(this).parent().attr('memId');
				if(couponCheck == 0){
					$("#removeOverlay #removeServ").text('Remove '+memName);
					$("#removeOverlay #removeMsg").text('Are you sure you want to remove this membership?');
				}
				$("#removeOverlay #removeBtn").attr('memId', memId);
				$("#removeOverlay #removeBtn").attr('vasId', "");
			} else if($(this).parent().hasClass('vasServ')){
				var vasName = $(this).parent().attr('name');
				var vasMsg = $(this).parent().attr('msg');
				var vasId = $(this).parent().attr('vasId');
				if(couponCheck == 0){
					$("#removeOverlay #removeServ").text('Remove '+vasName);
					$("#removeOverlay #removeMsg").text(vasMsg);
				}
				$("#removeOverlay #removeBtn").attr('vasId', vasId);
				$("#removeOverlay #removeBtn").attr('memId', "");
			}
		});
		$("#removeBtn").click(function(e){
			var memId = $(this).attr('memId');
			var vasId = $(this).attr('vasId');
			if(checkEmptyOrNull(readCookie('couponID'))){
				eraseCookie('couponID');
			}
			if(vasId){
				removeFromVas(vasId);
				callRedirectManager();
			} else if(memId){
				eraseCookie('mainMem');
				eraseCookie('mainMemDur');
				url = "/membership/jsms?"+"displayPage=1";
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
				window.location.href = url;
			}
		});
		$("#changeBtn").click(function(e){
			var memId = $(this).attr('memId');
			var vasId = $(this).attr('vasId');
			createCookie('backState', "changePlan");
			if(checkEmptyOrNull(readCookie('couponID'))){
				eraseCookie('couponID');
			}
			if(vasId){
				if(!checkEmptyOrNull(readCookie('mainMemDur'))){
					url = "/membership/jsms?"+"displayPage=1";
				} else {
					url = "/membership/jsms?"+"displayPage=2&mainMem="+readCookie("mainMem")+"&mainMemDur="+readCookie("mainMemDur");
				}
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
				window.location.href = url;
			} else if(memId){
				url = "/membership/jsms?"+"displayPage=1";
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
				window.location.href = url;
			}
		});
		$('.tapoverlay, #removeCncl, #removeCnclCoup').click(function(e){
			$("#removeOverlay").hide();
			$("#changeWithCouponOverlay").hide();
			$('html, body, #mainContent').css({
				'overflow': 'auto',
				'height': 'auto'
			});
		});
		$(".changeCall").click(function(e){
			e.preventDefault();
			var memId = $(this).parent().attr('memId');
			var vasId = $(this).parent().attr('vasId');
			var couponCheck = ~if $data.coupon_success` ~$data.coupon_success` ~else` 0 ~/if`;
			if(checkEmptyOrNull(couponCheck) && couponCheck != 0){
				$("#changeWithCouponOverlay").show();
				setCouponOverlayHeight();
				historyStoreObj.push(clearOverlay,"#overlay");
				$(window).scrollTop(0);
				$('html, body, #mainContent').css({
					'overflow': 'hidden',
					'height': '100%'
				});
				$("#changeWithCouponOverlay #removeServCoup").text('Coupon code will be removed');
				$("#changeWithCouponOverlay #removeMsgCoup").text('You can apply it again after you are done with your changes');
				$("#changeWithCouponOverlay #changeBtn").text('Okay');
				if($(this).parent().hasClass('mainMem')){
					var memName = $(this).parent().attr('name');
					var memId = $(this).parent().attr('memId');
					$("#changeWithCouponOverlay #changeBtn").attr('memId', memId);
					$("#changeWithCouponOverlay #changeBtn").attr('vasId', "");
				} else if($(this).parent().hasClass('vasServ')){
					var vasName = $(this).parent().attr('name');
					var vasMsg = $(this).parent().attr('msg');
					var vasId = $(this).parent().attr('vasId');
					$("#changeWithCouponOverlay #changeBtn").attr('vasId', vasId);
					$("#changeWithCouponOverlay #changeBtn").attr('memId', "");
				}
			} else {
				createCookie('backState', "changePlan");
				if(vasId){
					if(!checkEmptyOrNull(readCookie('mainMemDur'))){
						url = "/membership/jsms?"+"displayPage=1";
					} else {
						url = "/membership/jsms?"+"displayPage=2&mainMem="+readCookie("mainMem")+"&mainMemDur="+readCookie("mainMemDur");
					}
					if(checkEmptyOrNull(readCookie('device'))){
						url += '&device=' + readCookie('device');
					}
					window.location.href = url;
				} else if(memId){
					url = "/membership/jsms?"+"displayPage=1";
					if(checkEmptyOrNull(readCookie('device'))){
						url += '&device=' + readCookie('device');
					}
					window.location.href = url;
				}
			}
		})
		$("#pageBack").click(function(e){
			if(readCookie('backState') == "couponMain"){
				url = "/membership/jsms?displayPage=1";
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
		    	window.location.href = url
			} else if(readCookie('backState') == "couponVas") {
				if(upgradeMem == 'MAIN'){
					url = "/membership/jsms?displayPage=1";
				}
				else{
					url = "/membership/jsms?displayPage=2&mainMem="+readCookie('mainMem')+"&mainMemDur="+readCookie('mainMemDur');
				}
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
		    	window.location.href = url
			} else if(readCookie('backState') == "failurePage") {
				if(upgradeMem != "MAIN" && checkEmptyOrNull(readCookie('mainMem')) && ($.inArray(readCookie('mainMem'),skipVasPageMembershipBased)==-1))
				{
					url = "/membership/jsms?displayPage=2&mainMem="+readCookie('mainMem')+"&mainMemDur="+readCookie('mainMemDur');
				} else {
					url = "/membership/jsms?displayPage=1";
				}
				if(checkEmptyOrNull(readCookie('device'))){
					url += '&device=' + readCookie('device');
				}
				window.location.href = url;
			} else {
				eraseCookie('couponID');
				window.history.back();
			}
		});
		$("#continueBtn").click(function(){
			eraseCookie('backState');
			~if $data.backendLink`
				paramStr = "displayPage=5&backendRedirect=1&checksum=~$data.backendLink.checksum`&profilechecksum=~$data.backendLink.profilechecksum`&reqid=~$data.backendLink.reqid`";
			~else`
			if(checkEmptyOrNull(readCookie('mainMem')) && checkEmptyOrNull(readCookie('mainMemDur'))){
				if(checkEmptyOrNull(readCookie('selectedVas')) && $.inArray(readCookie('mainMem'),skipVasPageMembershipBased)==-1){
					paramStr = "displayPage=5&mainMembership="+readCookie("mainMem")+readCookie("mainMemDur")+"&vasImpression="+readCookie('selectedVas')+"&upgradeMem="+upgradeMem;  
			    } else {
					paramStr = "displayPage=5&mainMembership="+readCookie("mainMem")+readCookie("mainMemDur")+"&vasImpression="+"&upgradeMem="+upgradeMem;
			    }	
			} else {
				if(checkEmptyOrNull(readCookie('selectedVas'))){
					paramStr = "displayPage=5&mainMembership=&vasImpression="+readCookie('selectedVas');  
			    } else {
					paramStr = "displayPage=5&mainMembership=&vasImpression=";
			    }
			}
			
		    if(checkEmptyOrNull(readCookie('couponID'))){
		    	paramStr += "&couponID="+readCookie('couponID');
		    }
		    ~/if`
		    url = "/membership/jsms?" + paramStr;
		    if(checkEmptyOrNull(readCookie('device'))){
				url += '&device=' + readCookie('device');
			}
		    window.location.href = url;
		});
		$("#enterCouponBtn").click(function(e){
			var upgradeMem = "~$data.upgradeMem`";
			url = "/membership/jsms?displayPage=4";
			if(checkEmptyOrNull(readCookie('device'))){
				url += '&device=' + readCookie('device');
			}
			if(checkEmptyOrNull(upgradeMem)){
				url += "&upgradeMem="+upgradeMem;
			}
		    ShowNextPage(url,0,0);
		});
		var username = "~$data.userDetails.USERNAME`";
		var email = "~$data.userDetails.EMAIL`";
		setInterval(function(){
			autoPopulateFreshdeskDetails(username,email);
		},100);
		setTimeout(function(){
			autoPopupFreshdesk(username,email);
		}, 90000);
	});
</script>
~/if`
