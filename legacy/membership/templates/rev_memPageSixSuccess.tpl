<div class="bg4 mndivhgt">
	<div class="pad5">
		<div class="f40 opa50 fontreg">Failure</div>
		<div class="f20 fontlig pt25 pad20">
			~$data.failure_message`
		</div>
	</div>
	<div class="mem_btm1 fullwid">
		<div class="posrel">
			<div class="posabs fullwid mem_pos3">
				<div class="disptbl mem_wh">
					<div class="dispcell vertmid white txtc mem_brdr2 bg7 f18 fontlig" style="border-radius:50px;e">
						OR
					</div>        
				</div>
			</div>
			<div class="bg7 fontlig f18 ">
				<div class="txtc"><a id="redirectToCart" href="" class="white lh50 dispbl pb10">~$data.try_again`</a></div>
			</div>
			<div class="bg7 fontlig f18 mt1">
				<div class="txtc"><a href="tel:~$data.toll_free.value`" class="white lh50 dispbl pt10">~$data.toll_free.label`</a></div>
			</div>
		</div>
		<div class="txtc pt10"><a href="~sfConfig::get('app_site_url')`/search/partnermatches" class="color2 f15 fontreg">~$data.proceed_text`</a> </div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var winHeight = $(window).height();
        $(".bg4").css('height',winHeight);
        var mainMem = readCookie('mainMem');
		var mainMemDur = readCookie('mainMemDur');
		var couponID = readCookie('couponID');
		var newHref = "~sfConfig::get('app_site_url')`/membership/revampMobileMembership?displayPage=4&mainMem="+mainMem+"&mainMemDur="+mainMemDur+"&includeMatriProfileVAS=0&JSX=1&CC=1&couponID="+couponID;
        $("#redirectToCart").attr('href',newHref);
    });
</script>