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
				<div id="pageTitle" class="fontthin f19">~$data.title`</div>
				<div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2 cursp"></i></div>
				<div id="nextButton" class="posabs rv2_pos1 fontlig cursp">~if $data.topButton` ~$data.topButton` ~/if`</div>
			</div>
		</div>
	</div>
	<!--end:header-->
	<div class="rv2_bg1">
		<div class="rv2_pad5" style="padding-bottom:50px;">
			~if $data.topBlockMessage`
			<!--start:expire info-->
			<div class="rv2_pad10">
				<!--start:expire div-->
				<div class="pt10">
					<div class="bg3 txtc fontlig padd22 rv2_boxshadow">
						<div id="topBlockTitleMessage" class="f17 fontmed color7">~$data.topBlockMessage.titleMessage`</div>
						~if $data.topBlockMessage.monthsValue eq 'Unlimited'`
						<div class="disptbl tablegap">
							<div class="dispcell rv2_brdr3 rv2_wid5 rv2_pad2 rv2_colr1">
								<div id="topBlockMonthsValue" class="f40  fontrobbold">~$data.topBlockMessage.monthsValue`</div>
								<div id="topBlockMonthsText"claid="topBlockMonthsText"ss="f12 fontlig">~$data.topBlockMessage.monthsText`</div>
							</div>
						</div>
						~else if $data.topBlockMessage.monthsValue`
						<!--start:timer-->
						<div class="disptbl tablegap" >
							<div class="dispcell rv2_brdr3 rv2_wid2 rv2_pad2 rv2_colr1">
								<div id="topBlockMonthsValue" class="f40  fontrobbold">~$data.topBlockMessage.monthsValue`</div>
								<div id="topBlockMonthsText" class="f12 fontlig">~$data.topBlockMessage.monthsText`</div>
							</div>
							<div class="dispcell rv2_brdr3 rv2_wid2 rv2_pad2 rv2_colr1">
								<div id="topBlockDaysValue" class="f40  fontrobbold">~$data.topBlockMessage.daysValue`</div>
								<div id="topBlockDaysText"class="f12 fontlig">~$data.topBlockMessage.daysText`</div>
							</div>
						</div>
						<!--end:timer-->
						~/if`
						~if $data.topBlockMessage.contactsLeftText`
						<!--start:div-->
						<div class="rv2_brdrtop1 pad12 txtc">
							<div id="topBlockContactsLeftText" class="f16 fontmed color7">~$data.topBlockMessage.contactsLeftText`</div>
							<div id="topBlockContactsLeftValue" class="f40 fontrobbold rv2_colr1 pt10">~$data.topBlockMessage.contactsLeftNumber`</div>
						</div>
						~/if`
						<!--end:div-->
						<!--start:div-->
						~if $data.topBlockMessage.currentBenefitsTitle`
						<div class="rv2_brdrtop1 pad12 txtc rv2_list2">
							<div id="topBlockCurrentBenefitsBlock" class="f17 fontmed color7">~$data.topBlockMessage.currentBenefitsTitle`</div>
							<ul>
								~foreach from=$data.topBlockMessage.currentBenefitsMessages key=k item=v name=benefitsLoop`
								<li>~$v`</li>
								~/foreach`
							</ul>
						</div>
						~/if`
						<!--end:div-->
						~if $data.topBlockMessage.nextMembershipMessage`
						<!--start:div-->
						<div class="rv2_brdrtop1 pad12 txtc">
							<div id="topBlockNextMembershipText" class="f16 fontmed color7">~$data.topBlockMessage.nextMembershipMessage`</div>
						</div>
						~/if`
						<!--end:div-->
					</div>
				</div>
				<!--end:expire div-->
			</div>
			~/if`
			~if $data.backgroundText`
			<!--start:offer div-->
			<div class="pt10">
				<div id="backgroundText" class="posrel txtc fontlig f16 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv2_pad16"> ~$data.backgroundText`</div>
			</div>
			<!--end:offer div-->
			~/if`
			<!--end:option div-->
			~if $data.vasContent`
			<div class="rv2_pad5">
				<!--start:option div-->
				~foreach from=$data.vasContent key=k item=v name=vasLoop`
				<div id="~$v.vas_key`_vasBlock" class="pt20">
					<div class="rv2_boxshadow ">
						<!--start:description-->
						<div class="bg4 padd22">
							<!--start:plan-->
							~if $v.starting_strikeout`
							<p id="~$v.vas_key`_startingStrike" class="strike rv2_colr1 txtr f14">~$data.currency`~$v.starting_strikeout`</p>
							<!--end:strike through-->
							~/if`
							<div class="disptbl fullwid color7 rv2_brdrbtm1 pb10">
								<!--start:strike through-->
								<div id="~$v.vas_key`_name" class="dispcell rv_ft1 fontmed">~$v.vas_name`</div>
								<div id="~$v.vas_key`_startingPrice" class="dispcell txtr f17 fontreg">~$v.starting_price_text` <span>~$data.currency`</span>~$v.starting_price`</div>
							</div>
							<!--end:plan-->
							<!--start:features list -->
							<div class="rv2_list1">
								<ul>
									<li>~$v.vas_description`</li>
								</ul>
							</div>
							<!--end:features list -->
							<!--start:duration-->
							<div id="~$v.vas_key`" class="vas_durations">
								<div id="~$v.vas_key`_selectDurationText" class="rv2_pad7 f18 fontmed ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if`">~$v.selectDurationText`</div>
								<!--start:option-->
								<div class="disptbl rv2_brdr2 rv2_brrad1 fullwid fontlig">
									~foreach from=$v.vas_options key=vk item=vd name=vasDurLoop`
									~if not $smarty.foreach.vasDurLoop.last`
									<div id="~$vd.id`" vasKey="~$v.vas_key`" class="cursp dispcell wid33p_a rv2_brdrright txtc vertmid pt15 pb15 vasClick">
										<div id="~$vd.id`_durationText" class="color8 f17">~$vd.duration` ~$vd.text`</div>
										<div class="rv2_colr1 f14 pt5">
											~if $vd.vas_price_strike`
											<span id="~$vd.id`_priceStrike" class="strike rv2_colr1 txtr f14">~$data.currency`~$vd.vas_price_strike`</span>
											~/if`
											<span id="~$vd.id`_price" >~$data.currency`</span>~$vd.vas_price`
										</div>
									</div>
									~else`
									<div id="~$vd.id`" vasKey="~$v.vas_key`" class="cursp dispcell wid33p_a txtc vertmid pt15 pb15 vasClick">
										<div id="~$vd.id`_durationText" class="color8 f17">~$vd.duration` ~$vd.text`</div>
										<div class="rv2_colr1 f14 pt5">
											~if $vd.vas_price_strike`
											<span id="~$vd.id`_priceStrike" class="strike rv2_colr1 txtr f14">~$data.currency`~$vd.vas_price_strike`</span>
											~/if`
											<span id="~$vd.id`_price" >~$data.currency`</span>~$vd.vas_price`
										</div>
									</div>
									~/if`
									~/foreach`
								</div>
								<!--end:option-->
							</div>
							<!--end:duration-->
							
						</div>
						<!--end:description-->
					</div>
				</div>
				~/foreach`
				<!--end:option div-->
			</div>
			~/if`
			<!--start:div-->
			<div id="bottomHelpMessage" class="txtc pt25 fontlig color8 f16 lh25">
				~$data.bottom_message`
			</div>
			<!--end:div-->
			~if $data.bottomHelp`
			<!--start:help-->
			<div class="pt40 pb30 cursp">
				<div class="rv2_pad4">
					<div id="callButtonBottom" class="rv2_brdr1 txtc pad2 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv2_brrad1">
						~$data.bottomHelp.title`
					</div>
				</div>
			</div>
			<!--end:help-->
			~/if`
		</div>
	</div>
	<!--start:continue button-->
	<div style="overflow:hidden;position: fixed;height: 61px;" class="fullwid disp_b btmo">
	<div id="continueBtn" class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp posfix btmo pinkRipple"> ~$data.continueText` </div>
	</div>
	<!--end:continue button-->
</div>
<script type="text/javascript">
	var AndroidPromotion = 0;
	var filteredVasServices = "~$data.filteredVasServices`",skipVasPageMembershipBased = JSON.parse("~$data.skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
	$(document).ready(function(){
		eraseCookie('couponID');
		$("#continueBtn").show();
		var preSelectedESathiVas = "~$data.preSelectedESathiVas`";
		if(checkEmptyOrNull(preSelectedESathiVas)){
			createCookie('selectedVas', preSelectedESathiVas, 0);
		}
		var preSelectedEValuePlusVas = "~$data.preSelectedEValuePlusVas`";
		
		if(readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))){
			updateSelectedVas("jsmsVasPage");
		}
		$(".vasClick").click(function(e){
			var that = this;
			$(this).parent().find('.vasClick').each(function(){
				if(checkEmptyOrNull(readCookie('device'))){
					if($(this).hasClass(readCookie('device')+'_vassel') && this!=that){
						$(this).removeClass(readCookie('device')+'_vassel');
					}
				} else {
					if($(this).hasClass('vassel') && this!=that){
						$(this).removeClass('vassel');
					}
				}
			});
			if(checkEmptyOrNull(readCookie('device'))){
				if($(that).hasClass(readCookie('device')+'_vassel')){
					$(that).removeClass(readCookie('device')+'_vassel');
				} else {
					$(that).addClass(readCookie('device')+'_vassel');
				}
			} else {
				if($(that).hasClass('vassel')){
					$(that).removeClass('vassel');
				} else {
					$(that).addClass('vassel');
				}
			}
			trackVasCookie($(that).attr("vasKey"), $(that).attr("id"));
			if(checkEmptyOrNull(readCookie('selectedVas'))){
				$("#nextButton").text('Cart');
			} else {
				$("#nextButton").text('Skip');
			}
		});
		$("#continueBtn, #nextButton").click(function(){
			callRedirectManager();
		});
		if(readCookie('backState') == "changePlan"){
			$("#pageBack").hide();	
		}
		$("#pageBack").click(function(e){
			if(readCookie('backState') != "changePlan"){
				eraseCookie('backState');
				paramStr = "/membership/jsms?displayPage=1";
				if(checkEmptyOrNull(readCookie('device'))){
					paramStr += '&device=' + readCookie('device');
				}
				window.location.href = paramStr;
			} else {
				window.history.back();
			}
		});
		var username = "~$data.userDetails.USERNAME`";
		var email = "~$data.userDetails.EMAIL`";
		setTimeout(function(){
			autoPopupFreshdesk(username,email);
		}, 90000);
	});
</script>
~/if`
