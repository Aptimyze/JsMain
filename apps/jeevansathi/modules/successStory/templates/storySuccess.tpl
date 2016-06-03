<!--Header starts here-->
~include_partial('global/header',[pageName=>"SearchPage"])`
<!--Header ends here-->
<!--pink strip starts here-->
<!--Main container starts here-->
<div id="main_cont">

<!--pink strip ends here-->
<p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header',[pageName=>"SearchPage"])`
<p class="clr_4"></p>
<br>

		<div class="mid_cont">
		<div class="left_cont lf">
			<h1>Success Stories (More than ~$totalStories` and counting)</h1>
			<div class="sp8"></div>

			<div class="js_pink_bg">
				<h2>Marriages are Really made at Jeevansathi.com</h2>

			<input type="button" class="green_btn_new b t11" value="Send us your Success Story" style="width:229px; margin-top:5px;"
			~if !$sf_request->getAttribute('login')` onclick="$.colorbox({href:'/static/registrationLayer?pageSource=successStory&width=775&ajax_error=1&random=1367301390069&fromSuccess=1'});"  ~else` onclick="$.colorbox({href:'/successStory/layer?width=700'});"~/if` >

				<p class="content">
				As our numerous success stories prove, marriages are really made at Jeevansathi.com. Here's wishing all our members who found their ideal partner here a lifetime of happiness! If you found your dream match through Jeevansathi.com, we would like to hear your success story too. So, just send in your wedding/ engagement photograph and it will be exclusively featured in our "success stories".
				</p>
			</div>

			<div class="sp5">
			</div>
				~include_partial("successStory/successYear",[showYear=>"$showYear",hideYear=>"$hideYear",year=>"$storyYear"])`

			<div class="bel_success_yrs_bg">
				~if $storyYear`
					<h2 class="lf t16">Year ~$storyYear`</h2>
				~else if $parentValue`
					<h2 class="lf t16">~$parentValue` ~$mappedValue`</h2>
				~else`
					<h2 class="lf t16">Year ~$year`</h2>
				~/if`

			~if $year neq '2004' && $year neq '2005'`
				~include_partial("successStory/pagination",[prev=>"$prev",fromSeo=>$fetchStoryObj->getFromSEO(),next=>"$next",year=>"$year",totalPages=>"$totalPages",page=>"$page",fetchStoryObj=>$fetchStoryObj])`
				
			~/if`
			</div>

	~if $year eq '2004'`
		~include_partial("successStory/storyList2004")`
	~else if $year eq '2005'`
		~include_partial("successStory/storyList2005")`
	~else`
		~include_partial("successStory/storyList",[year=>"$year",storyToShow=>"$storyToShow",storyPToShow=>"$storyPToShow"])`
	~/if`

	<div class="sp16"></div>
	<!-- Down Pagination Starts here -->
	~if $year neq '2004' && $year neq '2005'`
		~include_partial("successStory/pagination",[prev=>"$prev",fromSeo=>$fetchStoryObj->getFromSEO(),next=>"$next",year=>"$year",totalPages=>"$totalPages",page=>"$page",fetchStoryObj=>$fetchStoryObj])`
	~/if`

	~if ($next eq '' && $year neq '2004' && $year neq '2005')`
		<div class="clear">
		</div>
		<div align="right" class="mar_top_10">
		<a href="~sfConfig::get('app_site_url')`/successStory/morestory?page=1" class="blink"><b>More success stories&raquo;</b></a> </div>
	~/if`
</div>

	<!-- Right Panel -->

	~include_partial("successStory/rightPanel",[rightPanelStory=>"$rightPanelStory",loginData=>"$loginData"])`

	<!-- Right Panel Ends Here -->
	
</div>
</div>
</div>
        <div class="clear"></div>
		~include_partial('global/footer')`

