<div align="right" class="no_b t17" style="padding-right:53px;">Photos <span id = "pictureIndex">~$currentPicIndex`</span> of <span id = "totalPicsAvailable">~$countOfPics`</span></div>

<div id = "container" ~if $FromPage eq 'ViewAlbum'` style="width:500px"~/if`>
	<div id = "slider" style="display:block">
            <table style = "width:468px; height:500px;"><tr>
                    <td valign="middle" id = "display_main_pic">
                        <div><img id = "transparent_image" border="0" oncontextmenu="return false;" galleryimg="NO" src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif" style="position: absolute; width:468px; height:500px; top:0; left:0;">
                            <img id="display_main_pic_div" src=~$frontPicUrl` style="max-width:440px;max-height:480px;">
                        </div>
                    </td>
                </tr></table>
	</div>
	<div id = "loader" style="display:none">
		<table style = "width:468px; height:500px;"><tr>
			<td valign="center"><img src = "~sfConfig::get('app_img_url')`/images/loader_big.gif" /><br /><br />Loading...</td>
		</tr></table>
	</div>
	~if $countOfPics gt 1`	
		<span id="prevBtn" style = "display:block">
			<p class = "no_b">&nbsp;Pre</p>
			<a onclick = "display_image_action('previous','~$countOfPics`');"></a>
		</span>
		
		<span id="nextBtn" style = "display:block">
			<p class = "no_b">Next</p>
			<a onclick = "display_image_action('next','~$countOfPics`');"></a>
		</span>
	~/if`
</div>
