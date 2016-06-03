<div class="pagespan container">
	<!--start:overlay2-->
	<div id="callOvrTwo" style="display:none;">
		<div class="tapoverlay posfix"></div>
		<div class="posabs fontlig bg4 fullwid" style="z-index:110; bottom:0px;">
			<div class="pad19">
				<div class="f14 color13"><i class="mainsp mem_coma"></i>				
					<span id="reqCallBackMessage"></span>
				<br>
				<div id="closeOvr2" class="fr f14 pt15 color2" style="padding-bottom:30px;padding-right:10px;cursor:pointer;">Close</div>
				</div>
			</div>
		</div>
	</div>
	<!--end:overlay2-->
	<div class="serviceWrap">
		<div class="frame oneservice" id="oneservice">
			<ul class="clearfix">
				~foreach from=$data.membership_plans key=k item=v name=plansLoop`
				<li ~if $v.subscription_id eq 'X'`class="ex-pinkbg"~/if`>
					<form id="form.~$v.subscription_id`" action="revampMobileMembership" method="POST" accept-charset="utf-8">
						<!--start:service selected-->
						<div id="servicename">
							<!-- <div class="hgt10 bg7"></div> -->
							~if $smarty.foreach.plansLoop.first`
								<div id="~$v.subscription_name`" class="fontreg color2 r_mem_fnt1 pad19 pagetwotitle">~$v.subscription_name`<div class="fr pad1"><span id="~$v.next_plan_name`" class="slideMover" style="padding-right:10px;opacity:0.3;z-index:105;" position='~($k+1)`'>~$v.next_plan_name`</span><a id="backIco" href="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" title="Back" style="z-index:999"><i class="mem-spite mem-redbackic"></i></a></div></div><div class="clr"></div>
							~else if $smarty.foreach.plansLoop.last`
								<div id="~$v.subscription_name`" class="fontreg color2 r_mem_fnt1 pad19 pagetwotitle">~$v.subscription_name`<div class="fr pad1"><a id="backIco" href="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" title="Back" style="z-index:999"><i class="mem-spite mem-redbackic"></i></a></div></div><div class="clr"></div>
							~else`
								<div id="~$v.subscription_name`" class="fontreg color2 r_mem_fnt1 pad19 pagetwotitle">~$v.subscription_name`<div class="fr pad1"><span id="~$v.next_plan_name`" class="slideMover" style="padding-right:10px;opacity:0.3;z-index:105;" position='~($k+1)`'>~$v.next_plan_name`</span><a id="backIco" href="~sfConfig::get('app_site_url')`/membership/revampMobileMembership" title="Back" style="z-index:999"><i class="mem-spite mem-redbackic"></i></a></div></div><div class="clr"></div>
							~/if`
						</div>
						<!--end:service selected-->
						<!--start:VAS-->
						~if $v.subscription_id neq 'X'`
						<div id="vas_srv" class="pad16" style="overflow:auto">
							<div class="fullwid fontlig color13 f13">
								<!--start:first col-->
								<div class="fl holder fullwid">
									<div class="fullwid txtc">
										<!--start:div-->
										~if $v.icon_visibility.1.visibility eq 1`
											<div class="pb20"><i class="mem-spite ini_chat"></i>
										~else`
											<div class="pb20"><i class="mem-spite mem_notav"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.1.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.1.description`</div>
										</div>
										<!--end:div-->
										<!--start:div-->
										~if $v.icon_visibility.0.visibility eq 1`
											<div class="pb20"><i class="mem-spite see_cont"></i>
										~else`
											<div class="pb20"><i class="mem-spite mem_notav"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.0.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.0.description`</div>
										</div>
										<!--end:div-->
									</div>
								</div>
								<!--end:first col--> 
								<!--start:second col-->
								<div class="fl middle holder fullwid">
									<div class="fullwid txtc"> 
										<!--start:div-->
										~if $v.icon_visibility.2.visibility eq 1`
											<div class="pb20"><i class="mem-spite other_see"></i>
										~else`
											<div class="pb20"><i class="mem-spite other_see_inactive"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.2.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.2.description`</div>
										</div>
										<!--end:div--> 
										<!--start:div-->
										~if $v.icon_visibility.4.visibility eq 1`
											<div class="pb20"><i class="mem-spite A-act"></i>
										~else`
											<div class="pb20"><i class="mem-spite A-dis"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.4.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.4.description`</div>
										</div>
										<!--end:div--> 
										<!--start:div-->
										~if $v.icon_visibility.3.visibility eq 1`
											<div class="pb20 pt20"><i class="mem-spite T-act"></i>
										~else`
											<div class="pb20 pt20"><i class="mem-spite T-dis"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.3.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.3.description`</div>
										</div>
										<!--end:div--> 
									</div>
								</div>
								<!--end:second col--> 
								<!--start:third col-->
								<div class="fl holder fullwid">
									<div class="fullwid txtc"> 
										<!--start:div-->
										~if $v.icon_visibility.5.visibility eq 1`
											<div class="pb20 pt20"><i class="mem-spite I-act"></i>
										~else`
											<div class="pb20 pt20"><i class="mem-spite I-dis"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.5.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.5.description`</div>
										</div>
										<!--end:div--> 
										<!--start:div-->
										~if $v.icon_visibility.6.visibility eq 1`
											<div class="pb20"><i class="mem-spite R-act"></i>
										~else`
											<div class="pb20"><i class="mem-spite R-dis"></i>
										~/if`
											<div class="f14">~$v.icon_visibility.6.icon_name`</div>
											<div class="opa70">~$v.icon_visibility.6.description`</div>
										</div>
										<!--end:div--> 
									</div>
								</div>
								<!--end:third col-->
								<div class="clr"></div>
							</div>
						</div>
						<!--end:VAS--> 
						~else`
						<!--start:main EX-->
						<div class="fontlig" id="vas_srv" class="pad16" style="overflow:auto">
							<div class="txtc ex-color2 f16 pad5">~$v.sub_heading`</div>
							<!--start:image-->
							<div class="midimg posrel">
								<div class="posabs white f14 txtc lh25" style="width:55%;top: 34%">
									~$v.starting_price`<br>
									~if $v.starting_strikeout`<span style="text-decoration:line-through;">~$v.starting_strikeout`</span> ~/if`&nbsp;~$v.starting_price_string`
								</div>
								<img src="~sfConfig::get('app_site_url')`/images/jsms/membership_img/Ex-bg1.jpg" class="classimg3"/>
							</div>
							<!--end:image-->
							<!--start:listing-->
							<div class="pad3 ex-list">
								<ul>
									~foreach from=$v.icon_visibility key=kk item=vv name=jsIconsLoop`
										<li style="color:#3f3f3f;"><div>~$vv.description`</div></li>
									~/foreach`
								</ul>        
							</div>        
							<!--end:listing-->
							<div id="reqCallBack" class="txtc f14 fontlig color2 lh50" style="cursor:pointer;">Request Callback</div>
						</div>
						<!--end:main EX-->
						~/if`
						<input type="hidden" name="mainMem" value="~$v.subscription_id`">
						<input type="hidden" name="JSX" value="1">
						<input type="hidden" name="displayPage" value="3">
					</form>
				</li>
				~/foreach`
			</ul>
		</div>
	</div>
</div>
<!--end:VAS--> 

<!--start:select duration btn-->
<div id="bot_opt" class="fullwid bg7 btmo txtc" style="cursor:pointer;position:fixed;">
	~foreach from=$data.membership_plans key=k item=v name=plansLoop`
		<div id="SP~$k`" style="background-color:#ffffff;display:none;" class="dispSP txtc f14 fontreg color2 lh50">~$v.starting_price`</div>
	~/foreach`
	<div class="f22 fontreg white fullwid txtc dispibl pad20">~$data.proceed_text`<!-- <canvas class="shimpg2" id="shimmer" height="83" width="160"></canvas><div class="mem-spite mem-downar dispibl"> --></div></div></div>
</div>
<!--end:select duration btn-->

<script type="text/javascript">
	$(document).ready(function(){
		var sly;
		$('.serviceWrap').click(function(){
			if(!sly){
				var $frame = $('#oneservice');
				var $wrap  = $frame.parent();
				// Call Sly on frame
				sly = new Sly('#oneservice',{
					horizontal: 1,
					itemNav: 'forceCentered',
					smart: 1,
					activateMiddle: 1,
					mouseDragging: 1,
					touchDragging: 1,
					releaseSwing: 0,
					startAt: 0,
					scrollBar: $wrap.find('.scrollbar'),
					scrollBy: 1,
					pagesBar: $wrap.find('.pages'),
					speed: 500,
					swingSpeed: 0.5,
					syncSpeed: 0.9,
					elasticBounds: 0,
					easing: 'easeOutExpo',
					dragHandle: 1,
					dynamicHandle: 1,
					clickBar: 1,
				}).init();
			}
			sly.on('active', function(e){
				var position = this.rel.activeItem;
				$(".dispSP").hide();
				$("#SP"+position).show();
				if($("#oneservice li:eq("+(position)+")").hasClass("ex-pinkbg active")){
					$(".dispSP").hide();
				}
			});
		});
		$("#SP0").show();
		resetPageTwoHeight();
		var serviceWidth = $(window).width();
		var serviceHeight = $(window).height();
		$('.oneservice').css('height', serviceHeight);
		$('.oneservice ul li').css('width', serviceWidth);
		$('.oneservice li:first-child').trigger('click');
		// $('.opa70').css('display','none');
		$('.pb20').click(function(){
			// pageTwoPlanTransition();
			resetPageTwoHeight();
		});
		$('.dispibl').click(function(){
			$('li.active form').submit();
			//$('.pagespan').animate({'opacity':'0.5'},1500);
		});
		$('#backIco').click(function(){
			//$('.pagespan').animate({'opacity':'0.5'},1500);
		});
		sly.on('moveEnd', function(e){
			resetPageTwoHeight();
		});
		//shimmerEffect('mem_pageTwo');
		$(".slideMover").on('click', function(e){
			if($(e.target).is('.preventSlide')){
	            e.preventDefault();
	            return;
	        }
			var position = $(this).attr('position');
			sly.toCenter(position);
			console.log(position);
		});
		$(".ex-list ul li").css('width','100%');
		$(".ex-list ul li").css('background','inherit');
		$("#reqCallBack").click(function(e){
			e.preventDefault();
			var paramStr = '~$data.request_callback_params`';
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
		$("#closeOvr2").click(function(e){
			$("#callOvrTwo").hide();
		});
		$(".tapoverlay").click(function(e){
			$("#callOvrTwo").hide();
		});
	});
</script>

