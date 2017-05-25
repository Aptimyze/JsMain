<div><input type = "hidden" id = "sliderNo" value = "~$sliderNo`" /></div>

~if $tempCount`

<div class="fr mt_10" style="width:350px; ">

~if $tempCount gte 10`
        ~if $tempCount%9 eq 0`
                ~assign var='sliderPages' value=$tempCount/9`
        ~else`
                ~assign var='sliderPages' value=($tempCount/9) + 1`
        ~/if`

        ~assign var='indexNoSlider' value=0`

        <div style = "text-align:center; padding-left:15px">
                ~section name = slide_pages loop = $sliderPages`
                        <span id = "grey_box~$indexNoSlider`" ~if $sliderNo eq $indexNoSlider` style = "display:none" ~else` style = "display:inline" ~/if`><img src = "~sfConfig::get('app_img_url')`/images/gDot.png"> &nbsp;</span>
                        <span id = "blue_box~$indexNoSlider`" ~if $sliderNo eq $indexNoSlider` style = "display:inline" ~else` style = "display:none" ~/if`><img src = "~sfConfig::get('app_img_url')`/images/bDot.png"> &nbsp;</span>
                        ~assign var='indexNoSlider' value=$indexNoSlider+1`
                ~/section`
        </div>
~/if`

<div id="slideshow" ~if $tempCount lte 9` style="border-style:solid;border-width:1px;border-color: #D3D3D3;"~/if`><div id="slidesContainer">
		~foreach from=$allThumbnailPhotos item=disp key=k`
                                ~if $k mod 9 eq 0`
                                        <div class="slide">
                                        <table width="324px" border="0" cellspacing="0" cellpadding="0" height="362px">
                                ~/if`
                                ~if $k mod 3 eq 0`
                                        <tr>
                                ~/if`
                                <td align="center">
                                        ~if $disp`
						~if $whichPage eq 'add'`
                                                	<div align="center" class="mclass" style = "cursor:pointer;background-image: url(~$disp`);text-align:center;background-repeat:no-repeat;background-position:center;">
                                                		<a href="~sfConfig::get('app_site_url')`/social/viewAllPhotos/id000~$picIdArr[$k]`"><span id="pic[~$k`]" ><img  align="center" border="0" width="96" height="96" oncontextmenu="return false;" galleryimg="NO" src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif"></span></a>
						~else if $whichPage eq 'view'`
							<div align="center" class="mclass" id = "~$picIdArr[$k]`" style = "cursor:pointer;background-image: url(~$disp`);text-align:center;background-repeat:no-repeat;background-position:center;" onclick="display_image(this.id,~$k`,~$countOfPics`);">
							<img  align="center" border="0" width="96" height="96" oncontextmenu="return false;" galleryimg="NO" src="~sfConfig::get('app_img_url')`/profile/ser4_images/transparent_img.gif">
						~/if`
                                        ~else`
                                                <div align="center" class="mclass1" >
                                        ~/if`
                                        </div>
                                </td>
                                ~if $k mod 3 eq 2`
                                        </tr>
                                ~/if`
                                ~if $k mod 9 eq 8 || $k eq $tempCount`
                                        </table>
                                        </div>
                                ~/if`
                        ~/foreach`
</div></div></div>

~/if`
