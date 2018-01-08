<!--Header starts here-->
~include_partial('global/header',[pageName=>"SearchPage"])`
<!--Header ends here-->
<!--pink strip starts here-->
<!--Main container starts here-->
<div id="main_cont">

<!--pink strip ends here-->
<p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header',[pageName=>"searchPage"])`
<p class="clr_4"></p>
<br>

<div class="mid_cont">
<div class="left_cont lf">
	<h1>Success Stories</h1>
	<div class="sp8">
	</div>
	<div class="js_pink_bg">
		<h2>Marriages are Really made at Jeevansathi.com</h2>
					
		<input type="button" class="green_btn_new b t11" value="Send us your Success Story" style="width:229px; margin-top:5px;"
		~if !$sf_request->getAttribute('login')` onclick="$.colorbox({href:'/static/registrationLayer?pageSource=successStory&width=775&ajax_error=1&random=1367301390069&fromSuccess=1'});"  ~else` onclick="$.colorbox({href:'/successStory/layer?width=700'});"~/if` >

		<p class="content">
		As our numerous success stories prove, marriages are really made at Jeevansathi.com. Here's wishing all our members who found their ideal partner here a lifetime of happiness! If you found your dream match through Jeevansathi.com, we would like to hear your success story too. So, just send in your wedding/ engagement photograph and it will be exclusively featured in our "success stories".
		</p>
	</div>

	<div class="sp5"></div>
	~include_partial("successStory/successYear",[showYear=>"$showYear",hideYear=>"$hideYear"])`
	<div class="bel_success_yrs_bg">
	
	<h2 class="lf t16">Year ~$year`</h2>
	
	
	
	<!-- Pagination -->	
	
	<ul class="pagination rf">
		~if $first eq 1`
			<li style="padding: 2px 10px;">Previous</li>
		~else`
			<li><a href="~sfConfig::get("app_site_url")`/successStory/completestory?prev_page=1&year=~$year`&sid=~$sid`">Previous</a></li>
		~/if`
		~if $last eq 1`
			 <li style="padding: 2px 10px;">Next</li>
		~else`
		        <li><a href="~sfConfig::get("app_site_url")`/successStory/completestory?next_page=1&year=~$year`&sid=~$sid`">Next</a></li>
		~/if`
	</ul>

	<!-- End of Pagination -->
</div>

<!-- Complete Success Story Starts Here -->
<div class="sp16">
</div>

<p class="lf t12">
<div id="display_main_pic_div"    class="lf" style="text-align:center;background-repeat:no-repeat;background-position:center;margin-right:15px" oncontextmenu="return false;">
<img border="0"  src="~PictureFunctions::getCloudOrApplicationCompleteUrl($pic)`" onload="update_photo(this)"   galleryimg="NO" >
</div>



	<span class="b"> ~$name1` ~if $name1 && $name2` weds ~/if` ~$name2` </span>
	<br><br>
		~$story|decodevar`
</p>
<div class="sp16"></div>

<!-- End of Success Story -->

<!-- Down Pagination Starts here  -->

<div class="sp16">
</div>
	<ul class="pagination rf">
			~if $first eq 1`
				<li style="padding: 2px 10px;">Previous</li>
			~else`
				<li><a href="~sfConfig::get('app_site_url')`/successStory/completestory?prev_page=1&year=~$year`&sid=~$sid`">Previous</a></li>
			~/if`
			~if $last eq 1`	
				<li style="padding: 2px 10px;">Next</li>
			~else`
			      	<li><a href="~sfConfig::get("app_site_url")`/successStory/completestory?next_page=1&year=~$year`&sid=~$sid`">Next</a></li>
				
			~/if`
	</ul>
</div >

<!-- End of Down Pagination -->

<!-- Right Panel -->

~include_partial("successStory/rightPanel",[rightPanelStory=>"$rightPanelStory",loginData=>"$loginData"])`

<!-- Right Panel Ends Here -->
	
</div>
</div>
</div>
<div class="clear"></div>
~include_partial('global/footer')`
		
<script>
var dontrun=0;
function update_photo(ele)
{
	if(dontrun==0)
	{
		dontrun=1;
 		var newImg = new Image();
		newImg.src = ele.src;
		curHeight = newImg.height;
		curWidth = newImg.width;
			
		$("#display_main_pic_div").css("background-image","url(~PictureFunctions::getCloudOrApplicationCompleteUrl($pic)`)");

		ele.src="~$IMG_URL`/profile/ser4_images/transparent_img.gif";
		ele.height=curHeight;
		ele.width=curWidth;
		

	}
}
</script>
