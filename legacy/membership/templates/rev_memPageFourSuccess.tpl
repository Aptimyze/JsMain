<div class="bg4">
	<!--start:div-->
	<div>
		<div>
			<!-- coupn code layer  -->
			<div id="callOvrOne" style="display:none;">
				<div class="tapoverlay_cc posfix"></div>
				<div class="posfix btmo fullwid color2 f14 cc_bg1" style="z-index:120;">
					<!--start:error-->
					<div id="couponError" class="mem_brdr3"  style="display:none;">
						<div id="couponErrorTxt" class="cc_pad1">
						</div>   
					</div>     
					<!--end:error-->   
					<!--start:form-->
					<div class="cc_pad2">
						<div class="fullwid">
							<div class="fl">
								<input id="couponId" type="text" class="f14 cc_colr1" placeholder="Enter Coupon Code" autofocus />
							</div>
							<div id="applyCouponBtn" class="fr cursp">
								Apply
							</div>
							<div class="clr"></div>
						</div>
					 </div>        
				<!--end:form-->   
				</div>
			</div>
			<!-- coupn code layer ends -->
			<!--start:div title-->
			<div id="servicename">
				<!-- <div class="hgt10 bg7"></div> -->
				<form action="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" method="POST" accept-charset="utf-8" name="backButton" id="backButton">
					<div class="pad15">
						<div class="fl fontreg color2 r_mem_fnt1">Cart</div>
						<span id="backIco" href="#" title="Back" style="z-index:999;cursor:pointer;"><div class="fr pad5"><i class="mem-spite mem-redbackic"></i></div></span>
						<div class="clr"></div>
					</div>
					<input type="hidden" name="displayPage" value="3">
					<input type="hidden" name="JSX" value="1">
					<input type="hidden" name="mainMem" value="~$data.subscription_id`">
					<input type="hidden" name="mainMemDur" value="~$data.subscription_duration`">
				</form>
			</div>
			<!--end:div title-->
			<!--start:service choosen-->
			<div id="sevice_sel" class="pad1" style="overflow-y:auto">
				~foreach from=$data.cart_items.main_memberships key=k item=v name=mainServloop`
				<!--start:service-->
				<div id="~$data.subscription_id`~$data.subscription_duration`" class="memid mem_brdr3 color7 pad20">
					<div class="f19 fontreg">~$v.service_name`<div class="fr">~if $v.discount_given`<span class="strike color2">~$v.display_standard_price`</span>&nbsp;&nbsp;~/if`<span id="top_cart_price">~$v.display_price`</span></div></div>
					<div style="display:none;" id="mainDiscount">~$v.discount_given`</div>
					<div class="f14 fontlig color7 lh30">
						<div class="fl">~$v.service_duration`&nbsp;&nbsp;|&nbsp;&nbsp;~$v.service_contacts`</div>
						<div class="clr"></div>
					</div>
				</div>
				<!--end:service-->
				~/foreach`
				~if $data.cart_items.vas_memberships`
					~foreach from=$data.cart_items.vas_memberships key=k item=v name=vasServloop`
					<!--start:service-->
					<div class="color7 mem_brdr3 pad20">
						<div class="f19 fontreg">~$v.service_name`<div class="fr">~if $v.discount_given`<span class="strike color2">~$v.display_standard_price`</span>&nbsp;&nbsp;~/if`<span id="top_cart_price">~$v.price`</span></div></div>
						<div class="f14 fontlig lh30 color7">
							<div class="fl">~$v.service_duration`&nbsp;&nbsp;</div>
							<div class="clr"></div>
						</div>
					</div>
					<!--end:service-->
					~/foreach`
				~/if`
				<div class="clearfix fontreg lh30 f11">
				<div id="disclaimer" class="fl color7">~if $data.currency neq '$'`~$data.cart_bottom_text`~/if`</div>
					<div class="fr" style="cursor:pointer;"><a id="addCouponBtn" href="#" class="color2 f12">~$data.apply_coupon_text`</a></div>
				</div>
				<div id="couponDiscountTxtId" class="color2 f15 txtc" style="display:none">~$data.coupon_discount_text` ~$data.currency` <span id="discountTextVal"></span></div>
			</div>
			<!--end:service choosen-->
		</div>
		<!--end:months div-->
	</div>
	<div class="clr"></div>
	<!--end:div-->
	<div id="vas_slider" class="fullwid btmo txtc" style="position:fixed;padding-bottom:45px;">
		<!--start:VAS-->
		~if $data.showVas eq 1`
		<div>
			<div id="selVasText" class="pad15" style="cursor:pointer;z-index:50">
				<div class="fontreg color2 f15 txtc">~$data.vas_text`</div>
			</div>
			<!--start:scorller-->
			<div style="overflow-x: auto;" class="wrap vasbottomslider">
				<div class="frame white" id="vasBottomSlide" style="margin-left:-1px;">
					<ul class="clearfix">
						~foreach from=$data.vas_services key=k item=v name=vasSliderloop`
						<li>
							<div class="padl1 posrel"><img src="~sfConfig::get('app_site_url')`/images/jsms/membership_img/thumbnail_vas/~$v.vas_key`.jpg"/>
								<div class="posabs fullwid txt" style="bottom:15%"> <i class="mem-spite ~$v.vas_key`-small"></i>
									<div class="fontlig pad13 f15">~$v.vas_name`</div>
									<div class="pt10 fontthin f12">~$v.starting_price`</div>
								</div>
							</div>
						</li>
						~/foreach`
					</ul>
					<div class="clr"></div>
				</div>
			</div>
			<!--end:scroller-->
		</div>
		~/if`
		<!--end:VAS-->
		<!--start:select duration btn-->
		<form action="~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails" method="POST" accept-charset="utf-8" name="processPaymentForm" id="processPaymentForm">
		<div id="bottomCheckout" class="bg7 btmo pad20 fullwid" style="position:fixed;">
			<div id="priceSpan" class="dispibl white fontreg f22 txtc">~$data.currency`&nbsp;<span id="final_cart_price">~$data.cart_price_val`</span>&nbsp; | &nbsp;</div>
			<div id="checkout" class="white dispibl fontreg f22 txtc" style="cursor:pointer;">
				<span id="proceed_text">~$data.proceed_text`</span>
				<!-- <canvas class="shimpg4" id="shimmer" height="83" width="180"></canvas>
				<div class="mem-spite mem-downar dispibl"></div> -->
				<input type="hidden" name="processPayment" value="1">
				<input type="hidden" name="couponCodeVal" value="~$data.couponID`">
				<input type="hidden" name="couponID" value="~$data.couponID`">
				<input type="hidden" name="mainMembership" value="~$data.tracking_params.mainMembership`">
				~if $eSathi eq '1' and $fromBackend neq '1'`
					<input id="vasImpression" type="hidden" name="vasImpression" value="">
				~else`
					<input id="vasImpression" type="hidden" name="vasImpression" value="~$data.tracking_params.vasImpression`">
				~/if`
				<input type="hidden" name="device" value="mobile_website">
				<input type="hidden" name="AUTHCHECKSUM" value="~$authCode`">
				~if $fromBackend eq '1'`
					<input type="hidden" name="fromBackend" value="~$fromBackend`">
					<input type="hidden" name="checksum" value="~$checksum`">
					<input type="hidden" name="profilechecksum" value="~$profilechecksum`">
					<input type="hidden" name="reqid" value="~$reqid`">
				~/if`
			</div>
		</div>
		</form>
		<!--end:select duration btn-->
	</div>
	~if $data.vas_services`
	<!--start:slider main div-->
	<div style="overflow-x: none;" class="posabs abstop vasbigslider">
		<div class="frame white" id="vasBigSlide">
			<ul class="clearfix">
				~foreach from=$data.vas_services key=k item=v name=vasServBigloop`
				<li id="~$v.vas_key`" class="fl">
					<div>
						<div class="fullwid posrel"> <img src="~sfConfig::get('app_site_url')`/images/jsms/membership_img/expanded_vas/~$v.vas_key`.jpg" class="classimg1"/>
							<div class="posabs fullwid" style="top:25px;padding-left:15px;">
								<!--start:down arrow-->
								<div id="minimizeVas" class="txtr" style="padding:10px 25px 10px 0;z-index:180;cursor:pointer;"onclick="minimizeBigSlider(); return false;"><i class="mem-spite mem-downar"></i> </div>
								<div class="clr"></div>
								<!--end:down arrow-->
								<!--start:text and icon-->
								<div class="txtc fontlig mem_rfnt2 white r_mem_pb1">
									<div><i class="mem-spite ~$v.vas_key`-small"></i></div>
									<div class="pad1">~$v.vas_name`</div>
									<div class="opa50 pad1">~$v.vas_description`</div>
								</div>
								<div class="clr"></div>
								<!--end:text and icon-->
								<!--start:select plan-->
								<div id="vasOptions" class="fontlig white" style="overflow-y:auto;">
									<!--start:div-->
									~foreach from=$v.vas_options key=kk item=vv name=vasOptionsLoop`
									<div class="pad12 wid49p txtc vOpt" onclick="processVAS('~$v.vas_name`',~$vv.duration`,'~$vv.text`','~$vv.price_val`','~$vv.id`','~$v.vas_key`','~$vv.discount_given`'); setVASCookie();">
										<div class="disptbl" style="z-index=999;">
											<div id="~$vv.id`" class="whitecicle mem_bgw" style="">~$vv.duration`</div>
										</div>
										<div class="txtup mem_rfnt1 r_mem_pad5">~$vv.text`</div>
										~if $vv.discount_given`<div class="mem_rfnt1 strike">~$data.currency` ~$vv.display_standard_price`</div>~/if`
										<div id="vasOptPriceVal" class="mem_rfnt1">~$data.currency`&nbsp;~$vv.price_val`</div>
									</div>
									<!--end:div-->
									~/foreach`
								</div>
								<!--end:select plan-->
								<div class="clr"></div>
							</div>
						</div>
					</div>
				</li>
				~/foreach`
			</ul>
		</div>
		<ul class="fullwid pages"></ul>
	</div>
	<!--end:slider main div-->
	~/if`
</div>

<script type="text/javascript">
	var vasText = "~$data.vas_text`";
	var checkSuccessCoupon = "~$data.coupon_success`";
	var discountTextVal = parseInt("~$data.cart_items.main_memberships.0.discount_given`");
	if(isNaN(discountTextVal)){
		discountTextVal = 0;
	}
	// Copy variable containing only discount given on main membership selected in cart
	var mainDiscountVal = discountTextVal;
	if(checkSuccessCoupon == "1"){
		$("#addCouponBtn").removeClass('color2').addClass('color1');
		$("#couponDiscountTxtId").show();
	}
	var vasServices = new Array();
	var vasStandardPrices = new Array();
	~foreach from=$data.vas_services key=k item=v`
		~foreach from=$v.vas_options key=kk item=vv`
			vasServices["~$vv.id`"]="~$vv.discount_given`";
			vasStandardPrices["~$vv.id`"]="~$vv.display_standard_price`";
		~/foreach`
	~/foreach`
	createCookie('couponID',"~$data.couponID`");
	var cookieflag;
	$(document).ready(function(){
		var trackVasImpression = "~$data.tracking_params.vasImpression`";
		if(readCookie('vasImpression')){
			cookieflag = 1;
			var preSelectedVas = readCookie('vasImpression');
			if(preSelectedVas != ''){
				trackVasImpression = preSelectedVas;
				var testString = window.location.href;
				var arr  = preSelectedVas.split(',');
				for(var i = 0; i<arr.length; i++){
					$("#"+arr[i]).removeClass('mem_bgw').addClass('color13 bg4');
					var vasString = $("#"+arr[i]).parent().closest('.vOpt').attr('onclick').replace('; setVASCookie();','').replace('processVAS(','').replace(')','').replace(/'/g,'');
					var vasArr = vasString.split(',');
					processVAS(vasArr[0],vasArr[1],vasArr[2],vasArr[3],vasArr[4],vasArr[5],vasArr[6]);
				}
			cookieflag = 0;
			}
			if($('#sevice_sel').find('div.vasid').length > 0){
				var finalPrice = parseInt($('#final_cart_price').text().replace(',',''));
				$('#sevice_sel div.vasid').each(function(){
					discountTextVal += parseInt(vasServices[$(this).attr('id')]);
					finalPrice += parseInt($(this).find('#top_cart_price').text().replace(',',''));
				});
				$('#final_cart_price').text(finalPrice);
				$('#final_cart_price').digits();
			}
			updateAddRemoveButton();
		}
		$("div:visible[id*='top_cart_price']").each(function(){
			$(this).digits()
		});
		$("div[id*='vasOptPriceVal']").each(function(){
			$(this).digits();
		});
		$('#final_cart_price').digits();
		$("#discountTextVal").text(discountTextVal);
		var showVas = "~$data.showVas`";
		var winHeight = $(window).height();
		var winWidth = $(window).width();
		if(showVas == '1'){
			// Call Sly on frame
			var $frame = $('#vasBottomSlide');
			var $wrap  = $frame.parent();
			var sly = new Sly('#vasBottomSlide',{
				horizontal: true,
				itemNav: 'basic',
				smart: true,
				activateMiddle: true,
				activateOn: 'click',
				mouseDragging: true,
				touchDragging: true,
				releaseSwing: false,
				startAt: 0,
				scrollBar: $wrap.find('.scrollbar'),
				scrollBy: 1,
				speed: 250,
				swingSpeed: 0.9,
				syncSpeed: 0.9,
				elasticBounds: false,
				easing: 'swing',
				dragHandle: true,
				dynamicHandle: true,
				clickBar: false
			}).init();
			$('.vasbigslider .frame ul li').css('width',winWidth);
			var $frame2 = $('#vasBigSlide');
			var $wrap2  = $frame2.parent();
			var sly2 = new Sly('#vasBigSlide',{
				horizontal: true,
				itemNav: 'forceCentered',
				smart: true,
				activateMiddle: 1,
				activateOn: 'click',
				mouseDragging: true,
				touchDragging: true,
				releaseSwing: false,
				startAt: 0,
				scrollBar: $wrap2.find('.scrollbar'),
				pagesBar: $wrap2.find('.pages'),
				scrollBy: 1,
				speed: 250,
				swingSpeed: 0.5,
				syncSpeed: 0.9,
				elasticBounds: false,
				easing: 'swing',
				dragHandle: true,
				dynamicHandle: true,
				clickBar: false
			}).init();
			$(".vasbigslider").css('width',winWidth);
			sly.on('moveEnd', function(e){
				var position = this.rel.activeItem;
				sly2.toCenter(position);
			});
			sly2.on('active', function(e){
				$('li.active #vasOptions .whitecicle').attr('style','');
			});
			sly2.on('moveEnd', function(e){
				var vasBgHgt = $('.vasbigslider').height();
				$('li.active #vasOptions').css('height',(vasBgHgt/2));
				$('li.active #vasOptions .whitecicle').attr('style','');
			});
			sly2.on('moveStart', function(e){
				var vasBgHgt = $('.vasbigslider').height();
				$('.vasbigslider ul li').each(function(){
					$(this).find('#vasOptions').css('height',(vasBgHgt/2));
					$(this).find('#vasOptions .whitecicle').attr('style','');
				});
			});
			$("#selVasText").on('click', function(e){
				$("#proceed_text").text('Review Order');
				historyStoreObj.push(minimizeBigSlider,"#overlay");
				$('.vasbigslider').show();
				sly2.toCenter(0);
				var vasBgHgt = $('.vasbigslider').height();
				$('li.active #vasOptions').css('height',(vasBgHgt/2));
				$('li.active #vasOptions .whitecicle').attr('style','');
			});
			$(".bg4").css('height',winHeight)
			var vasHeight = $('#vas_slider').height();
			var servHeight = $('#servicename').height();
			var bottomHeight = $('#bottomCheckout').height();
			var servSelHeight = $('#sevice_sel').height();
			$('#sevice_sel').css('height',(winHeight - vasHeight - servHeight - bottomHeight - 20));
			$('.vasbigslider .frame ul li').css('height',winHeight - bottomHeight - 20);
			var frameHeight = $('.vasbigslider .frame ul li').height();
			$('.classimg1').css('height',frameHeight);
			$('.vasbigslider').hide();
			$('.vasbottomslider li').click(function(e){
				historyStoreObj.push(minimizeBigSlider,"#overlay");
				$('.vasbigslider').show();
				$("#proceed_text").text('Review Order');
			});
			$('#vasBottomSlide li').bind('click',function(e){
				e = e || window.event;
				var ul = $(this).parent();
				var index = ul.children().index(this);
				sly2.toCenter(index);
			});
		} else {
			var servHeight = $('#servicename').height();
			var bottomHeight = $('#bottomCheckout').height();
			$('#sevice_sel').css('height',(winHeight - servHeight - bottomHeight - 20));
		}
		//shimmerEffect('mem_pageFour','~$data.proceed_text`');
		$('#backIco').click(function(e){
			e.preventDefault();
			$('#backButton').submit();
		});
		$('#checkout').click(function(){
			var checkoutSelVas = new Array();
			if($("#proceed_text").text() == 'Review Order')
			{
				minimizeBigSlider();
			} 
			else if($('#sevice_sel').find('div.vasid').length == 0)
			{
				$('#processPaymentForm').submit();
			} 
			else {
				$('#sevice_sel div.vasid').each(function(){
					checkoutSelVas.push($(this).attr('id'));
				});
				$('#vasImpression').val(checkoutSelVas.join(","));
				$('#processPaymentForm').submit();
			}
			//$('.bg4').animate({'opacity':'0.5'},1500);
		});
		$('#bottomCheckout').on('touchstart', function(event) {
			event.preventDefault();
			$("#checkout").trigger('click');
		});
		var trackAllMemberships = "~$data.tracking_params.allMemberships`";
		if(trackVasImpression != ''){
			trackAllMemberships += ','+trackVasImpression;
		}
		var fromBackend = '~$fromBackend`';
		var trackingData = {};
		if(fromBackend != 1){
			trackingData ={"trackAppData":"1","source":"303","tab":"33","pgNo":"3","device":"mobile_website","allMemberships":trackAllMemberships,"mainMembership":"~$data.tracking_params.mainMembership`","vasImpression":trackVasImpression,"trackType":"F"};
		} else {
			var currency = '~$data.currency`';
			if(currency != '$'){
				var backTot = parseInt('~$data.cart_price`'.replace('8377','').replace(/[^0-9]/g, ''));
			} else {
				var backTot = parseInt('~$data.cart_price`'.replace(/[^0-9]/g, ''));
			}
			var backDisc = parseInt('~$data.cart_discount`'.replace(/[^0-9]/g, ''));
			trackingData ={"trackAppData":"1","source":"303","tab":"33","pgNo":"3","device":"discount_link","allMemberships":trackAllMemberships,"mainMembership":"~$data.tracking_params.mainMembership`","vasImpression":trackVasImpression,"trackType":"F","backDisc":backDisc,"backTot":backTot};
		}
		url ="~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails";
		$.ajax({
			type: 'POST',
			url: url,
			data: trackingData,
			success:function(data){
				response = data;
			}
		});
		$("#addCouponBtn").click(function(e){
			e.preventDefault();
			if(checkSuccessCoupon != "1"){
				$("#callOvrOne").show();
				historyStoreObj.push(clearOverlay,"#overlay");
			}
			$("#couponError").hide();
			$("#couponErrorTxt").text('');
			$("#couponId").val('');
			$("#couponId").focus();
			$("#couponId").trigger('focus');
			historyStoreObj.push(clearOverlay,"#overlay");
		});
		$("#applyCouponBtn").click(function(e){
			var couponID = $("#couponId").val().replace(/^\s+|\s+$/g,'');
			var subscription_id = '~$data.subscription_id`';
			var subscription_duration = '~$data.subscription_duration`';
			var paramStr = 'validateCoupon=1&couponID='+couponID+'&serviceID='+subscription_id+subscription_duration;
			paramStr = paramStr.replace(/amp;/g,'');
			url = "~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails?" + paramStr;

			$.ajax({
				type: 'POST',
				url: url,
				success:function(data){
					response = data;
					console.log(data);
					if(data.success_code!=1){
						$("#couponError").show();								
						$("#couponErrorTxt").text(data.message);
						$("#couponId").focus();
					}
					else{
						$("#callOvrOne").hide();
						window.location.href = window.location.href.replace('#overlay','')+"&couponID="+couponID;
					}	
				}
			});
		});
		$('.tapoverlay_cc').click(function(e){
			$("#callOvrOne").hide();
		});

	});
</script>
