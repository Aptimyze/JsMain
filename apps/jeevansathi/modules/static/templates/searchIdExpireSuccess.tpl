	<?php include_partial('global/header')?>
<div id="main_cont">
	<p class = "clr_4"></p>
	<div id="container">
		<div class="mid_cont">
			<div class="left_cont lf">
				<p class = "clr_4"></p>
				<p class = "clr_4"></p>
				<p class = "clr_4"></p>
				<h1><img src="~sfConfig::get('app_img_url')`/profile/images/icon_exp_mark.gif">&nbsp; Your search has expired</h1>
				<div class="sp2"></div>
				<form name="form1" method="post">
					<div class="gray t14" style="padding-left:35px;line-height:28px">
						Results have changed since last time you searched. Kindly perform your search again.
						 <a href="#" onclick="location.href = '~sfConfig::get(app_site_url)`/search/partnermatches'" style="color:#0046C5;">Go To Desired Partner Matches</a></div>
					</div>
				</form>
				<div class="sp16" style="height:100px;"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
	<?php include_partial('global/footer')?>
