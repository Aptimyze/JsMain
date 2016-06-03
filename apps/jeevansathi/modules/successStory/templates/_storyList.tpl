<!-- Start of SS -->

		~if $storyPToShow`
			~foreach from=$storyPToShow key=k item=story`

			<div class="lf">
				<div class="lf wed_row">
					<div class="no_img">
						<div id="display_main_pic_div" alt="jeevansathi" title="jeevansathi" class="lf"  oncontextmenu="return false;" style="background-image: url(~PictureFunctions::getCloudOrApplicationCompleteUrl($story.FRAME_PIC_URL)`);">
						<img border="0" width="131" height="81"  galleryimg="NO" src="~sfConfig::get("app_img_url")`/profile/ser4_images/transparent_img.gif">
						</div>
					</div>

					<div>
						<p class="b">
							<b>~if $story.HEADING`~$story.HEADING`~else`~if $story.NAME1`~$story.NAME1`~else`~if $story.NAME2`~$story.NAME2`~/if`~/if`~/if`</b>
						</p>

						<p>~substr($story.STORY,0,160)`~if strlen($story.STORY) > 160`....~/if`</p>
				
						~if strlen($story.STORY) > 160`
							<p><a href="~sfConfig::get("app_site_url")`/successStory/completestory?year=~$year`&sid=~$story.SID`" class="blink">Read Complete Story</a></p>
						~/if`

					</div>
				</div>
				<div class="clear"></div>
			</div>

			~/foreach`
		~/if`

		~if $storyToShow`
			~foreach from=$storyToShow key=k item=story`
			<div class="lf" style="border-bottom:1px solid #FED368; width:102%;_padding:10px 0;" >
				<p class="b">
					<b>
						~if $story.HEADING`~$story.HEADING` ~else` ~if $story.NAME1`~$storyNAME1` ~else` ~if $story.NAME2`~$story.NAME2`~/if`~/if`~/if`
					</b>
				</p>
				<p>~$story.STORY`</p>
				
			</div>
			~/foreach`
		~/if`

		<!-- End of SS -->
