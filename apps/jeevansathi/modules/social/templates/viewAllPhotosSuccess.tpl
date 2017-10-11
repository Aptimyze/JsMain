<!-- start header -->
~include_partial('global/header')`
<!--end header -->
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	

<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
	<div id="topSearchBand"></div>
	~include_partial('global/sub_header')`
	~include_partial('social/social_tabs')`

<!--orange strip ends here here-->
	<div class="lf t12 b" style="width:550px;padding:5px; margin-right:0px;">
		<p class="clr_4"></p>
		<div style="background-image:url(~sfConfig::get('app_img_url')`/images/ph-edit-bg.gif); background-repeat:repeat-x; width:550px; height:26px;">
			<div class="fl">
				<a href="#"></a>				<div class="filtp b mt_10">
					<a href="~sfConfig::get('app_site_url')`/social/addPhotos">Upload / Import Photos</a> ~if sfConfig::get("mod_social_video")`& Videos~/if` | All Photos [~$countOfPics`] </a> ~if sfConfig::get("mod_social_video")` | <a href="#">Video [3] </a> ~/if` ~if sfConfig::get("mod_social_video")` |  <a href="#">Audio  Profile</a> ~/if` 
				</div>
				<div>
					<p class="clr_4"></p>
    					<p class="clr_4"></p>
    				</div>
    			</div>
		</div>

		<p class="clr_4"></p>
		<div><br /></div>

<!--slider content start-->
~if $userPics`
~include_partial('social/social_mainpic',[currentPicIndex=>$currentPicIndex,countOfPics=>$countOfPics,frontPicUrl=>$frontPicUrl,countOfPics=>$countOfPics,fromPage=>'view',widthOfMainPic=>$widthOfMainPic,heightOfMainPic=>$heightOfMainPic])`
<!--slider content end-->

<div class="phtagfb1">
<div style = "width:470px; background-color:#D9D9D9; overflow:hidden; line-height:25px">
	<p class="fl b" id = "select_profile_link" ~if $currentPicIndex-1 eq 0` style = "display:none" ~else` style = "display:block" ~/if`>
		<a href="~sfConfig::get('app_site_url')`/social/profileLayer/view000~$currentPicId`?rand=~$randomNo`" id = "create_pp_button" style = "cursor:pointer">
			<input name="" type="radio" value="~$currentPicIndex-1`" id = "profileBtn" ~if $currentPicIndex-1 eq 0` checked ~/if` style = "cursor:pointer" />
		</a>
		&nbsp;Select as Profile Picture
	</p>

	<p class="fl b" id = "profile_text" ~if $currentPicIndex-1 eq 0` style = "display:block" ~else` style = "display:none" ~/if`>
		&nbsp;You selected this as profile picture
	</p>

	<p class="fr b">
		<span id = "crossDelete"><img src="~sfConfig::get('app_img_url')`/images/cross_icon1.jpg" border="0" />&nbsp;</span>
		<span id = "display_delete_link">
			<a href="#" class="no_b t12" id = "deleteThisPic" onclick = "checkProfPic('~$currentPicId`','deleteThisPic~$countOfPics`','profileBtn','~$picIdArr[0]`');">Delete this photo&nbsp;</a>
		</span>
		<span id = "loaderSmallImage" style = "display:none"><img src = "~sfConfig::get('app_img_url')`/images/loader_extra_small.gif" />&nbsp;</span>
	</p>
</div>

<div><p class="clr_18"></p></div>

<div id = "bottom_content">
    
	<div class="fl"><b>Title</b> &nbsp;
		~if $titleArr[$currentPicIndex-1]`
			<a href="#" style="color:#438dac; display:inline" id = "edit_title" onclick = "modify_title();">edit</a>
			<a href="#" style="color:#438dac; display:none" id = "add_title" onclick = "modify_title();">add</a>
		~else`
			<a href="#" style="color:#438dac; display:none" id = "edit_title" onclick = "modify_title();">edit</a>
			<a href="#" style="color:#438dac; display:inline" id = "add_title" onclick = "modify_title();">add</a>
		~/if`
    	</div>
	
	<br />
	<p class = "clr_4"></p>	
	<div class = "fl">
		<div class="phblw no_b" id = "picture_title">~$titleArr[$currentPicIndex-1]`</div>
	
		<div class="phblw no_b" id = "edit_picture_title" style = "display: none">
			<input type = "text" name = "edit_picture_title" value = "~$titleArr[$currentPicIndex-1]`" style="width:378px; height:23px;" maxlength="30" />&nbsp;
			<a href="#"style="color:#438dac;" class="b" onclick = "save_data('title');">Save</a>
		</div>
	
		<div id = "savingLoader1" style = "display:none"><img src = "~sfConfig::get('app_img_url')`/images/loader_extra_small.gif"></div>
	</div>
    	
	<div>
		<p class="clr_10"></p>
		<hr style = "color: #8D8D8D; background-color: #8D8D8D; height:2px; border:none" />
		<p class="clr_10"></p>
	</div>
    
	<div style="float:left;">
		<strong>Keywords &nbsp;</strong>
	   	~if $keywordArrStr[$currentPicIndex-1]`
			<a href="#" style="color:#438dac; display:inline" id = "edit_keywords" onclick = "modify_keywords();">edit</a>
			<a href="#" style="color:#438dac; display:none" id = "add_keywords" onclick = "modify_keywords();">add</a>
	   	~else`
			<a href="#" style="color:#438dac; display:none" id = "edit_keywords" onclick = "modify_keywords();">edit</a>
			<a href="#" style="color:#438dac; display:inline" id = "add_keywords" onclick = "modify_keywords();">add</a>
	   	~/if`
	</div>

	<div style = "float:left">
	
		<div id = "edit_picture_keywords" style = "display: none">
			<div style = "width:203px; z-index:1; display:block; position:absolute;" onclick = "display_layer('keyword_layer0');" onmouseover = "null" onmouseout = "hide_layer('keyword_layer0');"><span style = "font-size:17px; display:block; zoom:1; opacity:0; filter:alpha(opacity=0); line-height:25px; cursor:default">HIDDEN TEXT IS HERE !!</span></div>
       			<select name = "dropdown0" id = "dropdown0" style="width:200px; background-color:#FFFFFF" disabled>
   				<option value = "" name = "" id = "dropdown0value" selected />~$dropdownKeywordsLabel`
			</select>
			&nbsp; <a href="#"style="color:#438dac;" class="b" onclick = "save_data('keywords');">Save</a>
			<div onmouseover = "display_layer('keyword_layer0');" onmouseout = "hide_layer1('keyword_layer0');">
      				<div class = "no_b" id = "keyword_layer0" style = "background-color: #FFFFFF; border: 1px solid #E2E2E2; width: 200px; padding: 5px 0px 5px 0px; display: none; z-index:1; position: absolute; height:auto">
                	        	~foreach from=$keywords item=value key=kk`
                        	        	~assign var='keywd_index' value=$kk+1`
                                		<input type = "checkbox" id = "value~$kk`" value = "~$keywd_index`">~$value`<br />
                        		~/foreach`
				</div>
     			</div>
     			<input type = "hidden" id = "picture[0]" name = "picture_tag" value="~$keywordArrStr[$currentPicIndex-1]`" />
		</div>
	</div>
	
	<br />
	
	<div style = "padding: 4px 0 0 0">
		<div id = "savingLoader2" style = "display:none"><img src = "~sfConfig::get('app_img_url')`/images/loader_extra_small.gif" /></div>
		<div class="phblw no_b" id = "picture_keywords">~$currentPicKeywords`</div>
	</div>

</div>
</div>
<p class="clr_4"></p>

<div><p class="clr_18"></p></div>


<div><input type = "hidden" id = "allPhotoIds" value = "~$allPicIds`" /></div>
<div><input type = "hidden" id = "currentPicId" value = "~$currentPicId`" /></div>
<div><input type = "hidden" id = "currentPic_Type" value = "~$currentPic_Type`" /></div>

</div>

~else`
	<div class = filtp>
		You have not uploaded any photos yet. <a href='~sfConfig::get("app_site_url")`/social/addPhotos'>Click here </a> to add photos.
	</div>
	</div>
~/if`
<!--left part end -->

<!--right part start -->

<!--slider2 start -->
~include_partial('social/social_slider',[sliderNo=>$sliderNo,tempCount=>$tempCount,allThumbnailPhotos=>$allThumbnailPhotos,picIdArr=>$picIdArr,countOfPics=>$countOfPics,whichPage=>'view'])`
<!--slider2 end -->

 <!--right part end -->



<!--top tab  end -->
<p class="clr_4"></p>
<p class="clr_4"></p>





<p class="clr_18"></p>
<p class="clr_18"></p>
<!--content start -->




<!--content  start -->

<!--tabbing  start -->
<!--tabbing  end -->
<p class=" clr_18"></p>

<!--Main container ends here-->	
<!--Footer starts here and same as the footer of seo community pages-->
<p class="clr_4"></p>
<!--Footer ends here and same as the footer of seo community pages-->
</div>
~include_partial('global/footer')`
