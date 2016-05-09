<meta name="format-detection" content="telephone=no">
<form id="mainForm1" action="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" method="POST" accept-charset="utf-8" style="background-color:#3e3e3e;">
	<div class="memoutdiv1"  style="overflow:hidden"> 
		<div class="posrel">
			<!--start:overlay1-->
			<div id="callOvrOne" style="display:none;">
				<div class="tapoverlay posfix"></div>
				<div class="posabs txtc fontlig bg4 fullwid" style="z-index:110; bottom:22px;">
					<div class="f22 color2 pt30"><a style="cursor:pointer; color:#d9475c !important;"href="tel:~$data.call_us.value`">~$data.call_us.phone_number`</a></div>
					<div class="f14 color13 pt15">~$data.call_us.call_text`</div>
					~if $profileid`
					<div class="f13 color1 pad2">~$data.call_us.or_text`</div>
					<div id="reqCallBack" style="cursor:pointer;"class="f24 color2 pb20">~$data.call_us.request_callback`</div>
					~else`
					<div class="pb20"></div>
					~/if`
				</div>
			</div>
			<!--end:overlay1-->
			<!--start:overlay2-->
			<div id="callOvrTwo" style="display:none;">
				<div class="tapoverlay posfix"></div>
				<div class="posabs fontlig bg4 fullwid" style="z-index:110; bottom:22px;">
					<div class="pad19">
						<div class="f14 color13"><i class="mainsp mem_coma"></i>				
							<span id="reqCallBackMessage"></span>
						<br>
						<div id="closeOvr2" class="fr f14 pt15 color2" style="padding-bottom:30px;padding-right:10px;">Close</div>
						</div>
					</div>
				</div>
			</div>
			<!--end:overlay2-->
			<img src="~sfConfig::get('app_site_url')`/images/jsms/membership_img/revamp_bg1.jpg" class="imgset1"/>
			<!--start:content-->
			<!--start:back arrow-->
			<div id="backIco" class="fr posabs" style="padding:15px;right:0px;top:10px;">
				<a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" class="mem-spite mem-backic"></a>
			</div>
			<div class="clr pad13"></div>
			<!--end:back arrow-->
			<div id="content" class="posabs fullwid" style="top:10%;overflow:auto;">
				<div class="fullwid pad1 fontlig white">
					<div class="clr pad13"></div>
					<div class="opa50 f25 fontreg">~$data.title`</div><br>
					<div class="f165 lh25">~$data.message`</div>
					~if $data.subscriptionExp and not $data.offer_expiry_date` <br>
					~foreach from=$data.benefits_message key=k item=v name=benefitsLoop`
						<span class="dispbl r_mem_padb1 f14 fb white">~$v`</span>
					~/foreach`
					<div id="clockIco" class="txtc pt15">
						<div class="mem-spite mem_clk"></div>
						<div>~$expDays` <span class="opa70 fontreg">Days Remaining</span></div>
						<div>~$contactsRemaining` <span class="opa70 fontreg">Contacts Left To View</span></div>
					</div>
					~else`
					~if $countdown and $validityCheck eq '1'`
					<br>
					<div class="txtc r_mem_pad1 fontreg" id="timeLeft"></div>
					~/if`
					~/if`
					~if $data.offer_expiry_date or $data.logout eq '1' and not $data.subscriptionExp`
					~if not $showCountdown` <br> ~else` <br> ~/if`
					<div class="txtl fontreg white f14 pb20">
						~foreach from=$data.benefits_message key=k item=v name=benefitsLoop`
						<span class="dispbl r_mem_padb1 fb">~$v`</span>
						~/foreach`
					</div>
					~/if`
				</div>
			</div>
			<!--end:content-->
			<div id="browse" class="fontreg btmo posfix" style="bottom:15px;left:15px;cursor:pointer;width:90%;">
				<div class="submitButton"><div class="fl f22 white posrel" style="bottom:10px;">~$data.browse_plan`<!-- <canvas id="shimmer" height="90" width="150"></canvas></div> --><div class="mem-spite mem-downar"></div></div></div>
				<div id="callButton" class="fr">
					<div class="disptbl" style="background-color:#323232; border-radius:50%; width:70px; height:70px">
						<div class="dispcell vertmid white txtc fontlig f16">~$data.call_us.title`</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="displayPage" value="2">
	<input type="hidden" name="JSX" value="1">
</form>

<script type="text/javascript">
	$(document).ready(function(){
		var windowWidth = $(window).width();
		var windowHeight = $(window).height();
		$('.memoutdiv1').css( "height", windowHeight );
		$('.imgset1').css( "height", windowHeight );
		$('.imgset1').css( "width", windowWidth );
		if(windowWidth>320)
		{
			$('.memoutdiv2').css( "height", windowHeight );
			$('.imgset2').css( "height", windowHeight );
			$('.imgset2').css( "width", windowWidth );
		}
		else
		{
			$('#mainbg1 img').addClass( "classimg1" );
		}
		var display = "~$showCountdown`";
		var validityCheck = "~$validityCheck`";
		var contentHeight = $('#content').height();
		var backIcon = $('#backIco').height();
		var clockIcon = $('#clockIco').height();
		var browseHeight = $('#browse').height();
		var contNewHgt = windowHeight*0.63;
		$('#content').css('height',contNewHgt);
		if(display == 1 && validityCheck == 1){
			updateTimeSpan("~$countdown`");
		}
		$('.submitButton').click(function(){
			$('#mainForm1').submit();
			//$('#mainForm1').animate({'opacity':'0.5'},1500);
		});
		$("#callButton").click(function(e){
			e.preventDefault();
			$("#callOvrOne").show();
			$("#callOvrTwo").hide();
			historyStoreObj.push(clearOverlay,"#overlay");
		});
		$('.tapoverlay').click(function(e){
			$("#callOvrTwo").hide();
			$("#callOvrOne").hide();
		});
		$("#closeOvr2").click(function(e){
			$("#callOvrTwo").hide();
			$("#callOvrOne").hide();
		});
		$("#reqCallBack").click(function(e){
			e.preventDefault();
			$("#callOvrOne").hide();
			var profileid = parseInt("~$profileid`");
			var paramStr = '~$data.call_us.params`';
			paramStr = paramStr.replace(/amp;/g,'');
			url ="~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails?" + paramStr;

			$.ajax({
				type: 'POST',
				url: url,
				success:function(data){
					response = data;
					$("#reqCallBackMessage").text(data.message);
				}
			});
			$("#callOvrTwo").show();
		});
		// var referer = '~$referer`';
		// console.log("referer :: "+referer);
		// if(referer.indexOf("revampMobileMembership") < 0 && referer.indexOf("mem_comparison") < 0){
		// 	var refCookie = referer.substring(0, referer.indexOf('?'));
		// 	console.log("final Cookie :: "+refCookie);
		// 	if(refCookie){
		// 		createCookie('referer',refCookie);
		// 	} else {
		// 		createCookie('referer',referer);
		// 	}
		// }
		// $('#backIco').click(function(e){
		// 	var cookieVal = readCookie('referer');
		// 	if(cookieVal){
		// 		e.preventDefault();
		// 		window.location = cookieVal;
		// 	}
		// 	//$('#mainForm1').animate({'opacity':'0.5'},1500);
		// });
		//shimmerEffect('mem_pageOne');
	});
</script>
