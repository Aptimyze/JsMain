<div class="pagespan">
	<div id="content" class="bg4" style="overflow:auto">
		<!--start:div title-->
		<div id="servicename">
			<!-- <div class="hgt10 bg7"></div>  -->
			<form action="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" method="POST" accept-charset="utf-8" name="backButton" id="backButton">
				<div class="pad15">
					<div class="fl fontreg color2 r_mem_fnt1">Duration</div>
					<div id="backIco" class="fr pad15"><a href="#" title="Back" style="z-index:999;cursor:pointer;"><i class="mem-spite mem-redbackic"></i></a></div>
					<div class="clr"></div>
					<input type="hidden" name="displayPage" value="2">
					<input type="hidden" name="JSX" value="1">
				</div>
			</form>
		</div>
		<!--end:div title-->
		<!--start:scroll y div-->
		<!--start:amount title-->
		<div id="priceWrap" class="txtc pb10 top_price_bar">
			<div id="priceTop" class="color1 fontreg f12 strike">~if $data.currency eq '$'`<span id="webRupee" class='dispibl padr2'>~$data.currency`&nbsp;</span>~else`<span id="webRupee" class='dispibl padr2'>&#8377;</span>~/if`<span id="value"></span></div>
			<div id="price">~if $data.currency eq '$'`<span id="webRupee" class='r_mem_fnt2 color1 fontreg' style="display:inline-block;padding-right:5px;">~$data.currency`</span>~else`<span id="webRupee" class='r_mem_fnt2 color1 fontreg' style="display:inline-block;padding-right:5px;">&#8377;</span>~/if`<span id="finalPrice" class="r_mem_fnt2 color1 fontreg"></span></div>
			<div id="priceBottom" class="f12 fontreg color2"><span id="saveTxt">You save</span>&nbsp;~if $data.currency eq '$'`<span id="webRupee" class="dispibl padr2">~$data.currency`</span>~else`<span id="webRupee" class="dispibl padr2">&#8377;</span>~/if`<span id="value"></span></div>
		</div>
		<!--end:amount title-->
		<!--start:contact div-->

		<div id="contact" class="wrap contact brdr20">
			<div class="frame pt10 fontreg r_mem_fnt4 color1" id="forcecentered">
				<ul class="clearfix">
					~foreach from=$data.prices key=k item=v name=contactsLoop`
					<li id="~$v.duration`">~$v.contacts`</li>
					~/foreach`
				</ul>
			</div>
			<div class="clr"></div>
			<div class="txtc fontthin color1 f14 pad4 pb20">Contacts</div>
		</div>
		<!--end:contact div-->
		<!--start:months div-->
		<div id="durationWrap" class="wrap duration bg5 brdr21">
			<div class="posabs mem_pos2"><i class="mem-spite mem_selarrow"></i></div>
			<div class="frame brdr20 pt10 fontthin r_mem_fnt3 color1 fb" id="durationcentered">
				<ul class="clearfix">
					~foreach from=$data.prices key=k item=v name=durationLoop`
					<li id="~$v.duration_id`">~$v.duration`</li>
					~/foreach`
				</ul>
			</div>
			<div class="clr"></div>
			<div id="durationText" class="txtc fontreg color2 f14 pad4" style="padding-top:15px;"></div>
		</div>
	</div>
	<!--end:div-->
	<!--start:select duration btn-->
	<div id="continue" class="fullwid bg7 white btmo pad20 txtc" style="position:fixed;">
		<form name="submitDuration" id="submitDuration" action="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" method="GET" accept-charset="utf-8">
			<div id="cartPrice" class="dispibl white txtc fontreg f22"><span>~$data.currency`&nbsp;</span><span id="value"></span>&nbsp;&nbsp;|&nbsp;</div>
			<div id="proceed_cart" class="white dispibl txtc fontreg f22" style="cursor:pointer">
				Continue
				<!-- <canvas class="shimpg3" id="shimmer" height="55" width="100"></canvas>
				<div class="mem-spite mem-downar dispibl"></div> -->
			</div>
			<input type="hidden" name="mainMem" value="~$data.subscription_id`">
			<input id="mainMemDur" type="hidden" name="mainMemDur" value="">
			<input type="hidden" name="includeMatriProfileVAS" value=0>
			<input type="hidden" name="JSX" value="1">
			<input type="hidden" name="CC" value="1">
			<input type="hidden" name="displayPage" value="4">
		</form>
	</div>
	<!--end:select duration btn-->
</div>

<script type="text/javascript">
	var price = new Array();
	var price_top = new Array();
	var price_bottom = new Array();
	var durationText = new Array();
	var duration_id = new Array();
	~foreach from=$data.prices key=k item=v`
		price["~$k`"]="~$v.price`";
		price_top["~$k`"]="~$v.price_top_value`";
		price_bottom["~$k`"]="~$v.price_bottom_value`";
		durationText["~$k`"]="~$v.duration_text`";
		duration_id["~$k`"]="~$v.duration_id`";
	~/foreach`

	$(document).ready(function(){
		updatePageThreePrices(1,price_top,price_bottom,durationText,duration_id);
		resetPageThreeHeight();
		// -------------------------------------------------------------
		//   Force Centered Navigation for sliders
		// -------------------------------------------------------------

		var $frame = $('#forcecentered');
		var $wrap  = $frame.parent();

		var $frame2 = $('#durationcentered');
		var $wrap2  = $frame2.parent();

		// Call Sly on frame
		var sly = new Sly('#forcecentered',{
			horizontal: true,
			itemNav: 'forceCentered',
			smart: true,
			activateMiddle: true,
			activateOn: 'click',
			mouseDragging: false,
			touchDragging: false,
			releaseSwing: true,
			startAt: 1,
			scrollBar: $wrap.find('.scrollbar'),
			scrollBy: 1,
			speed: 250,
			swingSpeed: 0.3,
			syncSpeed: 0.1,
			elasticBounds: false,
			easing: 'easeOutExpo',
			dragHandle: false,
			dynamicHandle: false,
			clickBar: false
		}).init();

		// Call Sly on frame
		var sly2 = new Sly('#durationcentered',{
			horizontal: true,
			itemNav: 'forceCentered',
			smart: true,
			activateMiddle: 1,
			activateOn: 'click',
			mouseDragging: true,
			touchDragging: true,
			releaseSwing: false,
			startAt: 1,
			scrollBar: $wrap2.find('.scrollbar'),
			scrollBy: 1,
			speed: 250,
			swingSpeed: 0.3,
			syncSpeed: 0.1,
			elasticBounds: false,
			easing: 'easeOutExpo',
			dragHandle: false,
			dynamicHandle: false,
			clickBar: false
		}).init();
		sly2.on('moveEnd', function(e){
			var position = this.rel.activeItem;
			sly.toCenter(position);
			sly2.toCenter(position);
			updatePageThreePrices(position,price_top,price_bottom,durationText,duration_id);
			resetPageThreeHeight();
		});
		var windowWidth = $(window).width();
		//$("#cartPrice").css('padding-left',(windowWidth/4)+'px');
		//shimmerEffect('mem_pageThree');
		$('#proceed_cart').click(function(){
			eraseCookie('vasImpression');
			eraseCookie('couponID');
			var subscription_id = '~$data.subscription_id`';
			createCookie('mainMem',subscription_id);
			$('#submitDuration').submit();
			//$('.pagespan').animate({'opacity':'0.5'},1500);
		});
		resetPageThreeHeight();
		$("#L").css('font-size','25px');
		$('#backIco').click(function(e){
			e.preventDefault();
			$('#backButton').submit();
		});
		$("html, body").animate({ scrollTop: 1000 }, 1000);
	});
</script>
