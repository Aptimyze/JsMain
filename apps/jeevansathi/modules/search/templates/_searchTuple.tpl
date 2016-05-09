<!-- ~assign var='offsetVal' value=$searchResultNumber-1` -->
~assign var='offsetVal' value=$detailsArr['OFFSET']-1`
<input type="hidden" value="~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F`W~else`~$stype`~/if`" id="stype_~$detailsArr['PROFILECHECKSUM']`">
~if  $detailsArr['FEATURED'] eq Y`
	<div class="top">
		<div class="fr w107px" style="margin-top:22px;width:109px\0/IE9" >
			<div class="orgCornerLeft fl" style="margin-left:-1px" >
				&nbsp;
			</div>
			<div class="fl" style="background:#fed369; color:#d05f14; line-height:1.54; line-height:1.54\0/IE9">
				Featured Profile
			</div>
			<div class="orgCornerRight fl">
				&nbsp;
			</div>
		</div>
	</div>
	<span id="featuredProfile" >
	<div class="div_search_res_default pos_rltv1 " style="background:#fff4b6; border:1px solid #ffe552;margin-bottom:0px!important;" id="profile~$resultNumber`" >
		<input type = "hidden" id = "FeaturedProfilePosition" value = "~$featurePosition`">
		<input type = "hidden" id = "FeaturedCheckSum" value = "~$detailsArr['PROFILECHECKSUM']`">
		<input type = "hidden" id = "FeaturedResultNo" value = "~$featuredResultNo`">
~else if  $detailsArr['FEATURED'] eq F`
	<span id="featuredProfile`" >
	<div class="div_search_res_default pos_rltv1 " style="background:#fff4b6; border:1px solid #ffe552;margin-bottom:0px!important;" id="profile~$resultNumber`" >
		<input type = "hidden" id = "FeaturedCheckSum" value = "~$detailsArr['PROFILECHECKSUM']`">
		<input type = "hidden" id = "FeaturedProfilePosition" value = "~$featurePosition`">
		<input type = "hidden" id = "FeaturedResultNo" value = "~$featuredResultNo`">
~else if  $detailsArr['BOLDLISTING'] eq B`
	~if $detailsArr['IGNORED'] eq Y`
	<div class="top_tab" id="topTab~$resultNumber`" style="display:none;" >
	~else`
	<div class="top_tab" id="topTab~$resultNumber`">
	~/if`
		<div style="width:115px;width:117px\0/IE9;" class="fr">
			<div class=" skyblueCornerLeft fl" style="margin-left:-1px" >
				&nbsp;
			</div>
			<div style="background:#a3e2f5; color:#26677f; line-height:1.54" class="fl">
				Highlighted Profile
			</div>
			<div class=" skyblueCornerRight fl">
				&nbsp;
			</div>
		</div>
	</div>
	<span id="profileSpan~$resultNumber`" >
	~if $detailsArr['IGNORED'] eq Y`
	<div class="div_search_res_default pos_rltv1 " style="background:#d1f1fb; border:1px solid #a3e2f5;display:none; " id="profile~$resultNumber`" >
	~else if $detailsArr['CONTACT_REQUEST'] eq Y or  $detailsArr['PHOTO_REQUEST'] eq Y or $detailsArr['HOROSCOPE_REQUEST'] eq Y or $detailsArr['CHAT_REQUEST'] eq Y`
	<div class="div_search_res_default pos_rltv1 " style="background:#d1f1fb; border:1px solid #a3e2f5;margin-bottom:0px; " id="profile~$resultNumber`" >
	~else`
	<div class="div_search_res_default pos_rltv1 " style="background:#d1f1fb; border:1px solid #a3e2f5; " id="profile~$resultNumber`" >
	~/if`
	~if $detailsArr['FILTER_REASONS']`
                <div class="b filter_back" style="background-color:#E9F9FF; border-bottom:1px solid #a3e2f5;">Filtered you out based on your ~$detailsArr['FILTER_REASONS']`</div>
        ~/if`
~else`
	<span id="profileSpan~$resultNumber`" >
	~if $detailsArr['IGNORED'] eq Y`
	<div class="div_search_res_default pos_rltv1 " id="profile~$resultNumber`" style="display:none;" >
	~else if $detailsArr['CONTACT_REQUEST'] eq Y or  $detailsArr['PHOTO_REQUEST'] eq Y or $detailsArr['HOROSCOPE_REQUEST'] eq Y or $detailsArr['CHAT_REQUEST'] eq Y`
	<div class="div_search_res_default pos_rltv1 " style="margin-bottom:0px; " id="profile~$resultNumber`" >
	~else`
	<div class="div_search_res_default pos_rltv1 " id="profile~$resultNumber`" >
	~/if`
	~if $detailsArr['FILTER_REASONS']`
		<div class="b filter_back" style="background-color:#EFEFEF;">Filtered you out based on your ~$detailsArr['FILTER_REASONS']`</div>
	~/if`
~/if`
		<input type="hidden" id='checksum~$resultNumber`' value="~$detailsArr['PROFILECHECKSUM']`" >
		~if $loggedIn eq 1 and $detailsArr['CONTACT_REQUEST'] neq Y AND $detailsArr['CONTACT_SENT'] neq Y AND $detailsArr['CONTACT_REPLIED'] neq Y`
		<span>
		~else`
		<span style="visibility:hidden">
		~/if`
			<input type="checkbox" style="margin-left:-10px;margin-right:4px;" class="vam chbx fl dummySelect" id="checkbox_~$detailsArr['PROFILECHECKSUM']`">
		</span>
		
		<div class="div1  pos-rel" >
				~if $detailsArr['ISALBUM'] eq Y || $detailsArr['ISALBUM'] eq N`
				<div style="width:150px;height:200px;padding:3px;">
					~if $detailsArr['FEATURED'] eq F`
<!--
					<span >
-->
						<div name="featuredAlbum" id="featured~$resultNumber`" oncontextmenu="return false;" style="background-image:url(~$detailsArr['PHOTO']`);width:150px;height:200px;cursor:pointer;">
							<img alt="#"  src="/profile/ser4_images/transparent_img.gif" style="height:200px;width:150px;border=0;" />
						</div>
<!--
					</span >
-->
					~else`
						<div style="background-image:url(~$detailsArr['PHOTO']`);width:150px;height:200px;cursor:pointer;" name="showAlbum" id="showAlbum_~$detailsArr['PROFILECHECKSUM']`" class="pos-rel" oncontextmenu="return false;">
							<img alt="#"   src="/profile/ser4_images/transparent_img.gif" style="height:200px;width:150px;border=0;" />
			~if $detailsArr['VERIFICATION_SEAL'] neq 0`
                                ~include_partial("verifySealLayer",[detailsArr=>$detailsArr,profileid=>$profileid])`

			~/if`					
			                        
                                                
                                                </div>
					~/if`
				</div>
				~else if $detailsArr['ISALBUM'] eq F`
				<div style="width:150px;height:200px;" class="pos-rel" >
					
						<img alt="#"   src="~$detailsArr['PHOTO']`" style="height:200px;width:150px;" oncontextmenu="return false;" />
					~if $detailsArr['VERIFICATION_SEAL'] neq 0`
                                            ~include_partial("verifySealLayer",[detailsArr=>$detailsArr,profileid=>$profileid])`

                                        ~/if`
                                        
                                        
				</div>
				~else if $detailsArr['ISALBUM'] eq L`
				<div style="width:150px;height:200px;padding:2px;">
					<span>
						<img alt="#"  name="showRegistrationLayer" src="~$detailsArr['PHOTO']`" style="height:200px;width:150px;cursor:pointer;" oncontextmenu="return false;" />
					</span>
                                    			
				</div>
				~else if $detailsArr['ISALBUM'] eq 0`
				<span>					 
					<img alt="#"   src="~$detailsArr['PHOTO']`" style="height:200px;width:150px;" oncontextmenu="return false;" />
					~if $detailsArr['PHOTO_REQUESTED'] eq Y`
					<div class="grey-highlight" id = "photo_req_layer~$profileid`" oncontextmenu="return false;" >
						<font class="btn-req-photo white  b">
							Photo request sent
						</font>
					~else`
					<div class="grey-highlight" id = "photo_req_layer~$profileid`" oncontextmenu="return false;" ><input type="image"  src="~sfConfig::get('app_img_url')`/P/images/request-photo.jpg" class="btn-req-photo" onclick = "photo_ajax_request('~$detailsArr['PROFILECHECKSUM']`','photo_request_end',~$profileid`,'search')"  />
					~/if`
					</div>
				</span>
				~/if`
			~if $detailsArr['ISALBUM'] eq Y`
				~if $detailsArr['FEATURED'] eq F`
				<div name="featuredAlbum"  oncontextmenu="return false;" style="cursor:pointer;" id="featuredAlbum~$resultNumber`" class="blink bott_link2" >
				~else`
				<div  oncontextmenu="return false;" id="showAlbum_~$detailsArr['PROFILECHECKSUM']`" class="blink bott_link2" name="showAlbum">
				~/if`
				View Album (~$detailsArr['ALBUM_COUNT']`)
			</div>
			~elseif $detailsArr['ISALBUM'] eq N`
				~if $detailsArr['FEATURED'] eq F`
				<div name="featuredAlbum"  oncontextmenu="return false;" style="cursor:pointer;" id="featuredAlbum~$resultNumber`" class="blink bott_link" >
				~else`
				<div  oncontextmenu="return false;" id="showAlbum_~$detailsArr['PROFILECHECKSUM']`" class="blink bott_link" name = "showAlbum">
				~/if`
				Larger Photo
			</div>
			~/if`
		</div>
		~if  $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F`
		<div class="div2" style="border-right:1px solid #e9ae6a" >
		~else if  $detailsArr['BOLDLISTING'] eq B`
		<div class="div2" style="border-right:1px solid #81b0c1" >
		~else`
		<div class="div2">
		~/if`
			<div class = "fl" style = "width:5px;">&nbsp;</div>
			<div class="fs16 blink fl widthauto" >
				~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4'" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4')" >
				~else`
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4')" >
				~/if`
					<span title="View Full Profile">~$detailsArr['USERNAME']`</span>
				</a>
			</div>
                        ~if $detailsArr['paid_joined_viewed_icon'] eq paid`
				<div class="ico_separator fl nobkg">
                                    &nbsp;
                                </div>
                                <div class="pad10left fl" title="This Member has an upgraded plan" style="padding-top: 2px;color:#800000;font-weight: 700;font-size: 14px;">
					~$detailsArr['paidLabel']`
				</div>
			~else if $detailsArr['paid_joined_viewed_icon'] eq entry`
				<div class="ico_jj fl" title="Just Joined" >
					&nbsp;
				</div>
			~else if $detailsArr['paid_joined_viewed_icon'] eq viewed`
				<div class="ico_viewed fl" title="Viewed" >
					&nbsp;
				</div>
			~/if`
                         ~if $detailsArr['New'] eq 1`
                            <div class="ico_separator fl nobkg">
                                        &nbsp;
                                    </div>
                            <div class="pad10left fl" title="New Member" style="padding-top: 2px;color:#800000;">
                                            New
                            </div>
                        ~/if`
                        ~if $detailsArr['HOROSCOPE'] eq 'Y' || $detailsArr['RECOMMENDED'] eq 'Y' || $detailsArr['LINKEDIN'] eq 'Y' || $detailsArr['HIV'] eq 'Y'`
				<div class="ico_separator fl nobkg">
				&nbsp;
				</div>
			~/if`
                       
                        
			
			~if $detailsArr['HOROSCOPE'] eq 'Y'`
				<div class="ico_kund fl" title="Click to see Horoscope Details" style="cursor:pointer" onclick="return call_dp('IC_~$profileid`',1)" >
				~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
					<input type="hidden" id="IC_~$profileid`" value = "/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&kundli_type=2&offset=~$featuredResultNo`&responseTracking=4" >
				~else`
					<input type="hidden" id="IC_~$profileid`" value = "/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&kundli_type=2&offset=~$offsetVal`&responseTracking=4" >
				~/if`
					&nbsp;
				</div>
				~if $detailsArr['HOROSCOPE_VAL'] neq ''`
					<input type ="hidden" name="horo_astro_~$resultNumber`" value="~$detailsArr['HOROSCOPE_VAL']`">
					<span id="LAGAN_ID_~$profileid`"></span>
				~/if`
			~/if`
			~if $detailsArr['RECOMMENDED'] eq 'Y'`
				<div class="ico_hand fl" title="Recommended Profile" >
					&nbsp;
				</div>
			~/if`
			~if $detailsArr['LINKEDIN'] eq 'Y'`
				<div class="ico_linked fl" title="Linked-in Profile" >
					&nbsp;
				</div>
			~/if`
			~if $detailsArr['HIV'] eq 'Y'`
				<div class="ico_hiv fl" title="HIV +ve" >
					&nbsp;
				</div>
			~/if`
			<div class="sp15">
				&nbsp;
			</div>
			~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
			<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4')">
			~else`
			<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4')">
				~/if`
			<div class="data">
				~foreach from=$fieldsDisplayedInSearchTuple item=displayField key=columnName`
					~if ~$detailsArr[$columnName]` neq ''`
						~if $displayField eq Caste and ($detailsArr['DECORATED_RELIGION'] eq Buddhist or $detailsArr['DECORATED_RELIGION'] eq Jewish or $detailsArr['DECORATED_RELIGION'] eq Parsi or $detailsArr['DECORATED_RELIGION'] eq Bahai)`
						~else`
						<div class="block">
							<span class ="fl">
								~if $displayField==Caste`
									~if $detailsArr['DECORATED_RELIGION'] eq Muslim or $detailsArr['DECORATED_RELIGION'] eq Christian`
										Sect
									~else`
										Caste
									~/if`
								~else`
									~$displayField`
								~/if`
							</span>
							<span class="fl" >
								<strong>
									<span class="fl" style="width:180px;" >
										<table>
											<tr>
												<td valign="top">
													:&nbsp;
												</td>
                                                                                                <td class="ww" style="max-width: 170px;">
													~$detailsArr[$columnName]`
												</td>
											</tr>
										</table>
									</span>
								</strong>
							</span>
						</div>
						~/if`
					~/if`
				~/foreach`
			</div>
			</a>
		</div>	
		<div class="div3">
			<div class="fl width100" >
				~if $detailsArr['userLoginStatus'] eq jschat || $detailsArr['userLoginStatus'] eq gtalk`
<!--
					<a href="#" class="fl" name="chatIcon" onClick="openChatWindow(\"~$resultNumber`\",\"~$detailsArr['PROFILECHECKSUM']`\",\"~$resultNumber`\",\"~$detailsArr['USERNAME']`\",\"~$detailsArr['HAVEPHOTO']`\",'')" >
-->
					<a href="#" class="fl" name="chatIcon" onClick="openChatWindow('~$profileid`','~$detailsArr['PROFILECHECKSUM']`','~$profileid`','~$detailsArr['USERNAME']`','~$detailsArr['HAVEPHOTO']`','~$checksum`');return false;" >
						<strong>
							Online now..
						</strong>
					</a>
				~/if`
				~if $detailsArr['userLoginStatus'] eq jschat`
					<a href="#" class="fl" name="chatIcon" onClick="openChatWindow('~$profileid`','~$detailsArr['PROFILECHECKSUM']`','~$profileid`','~$detailsArr['USERNAME']`','~$detailsArr['HAVEPHOTO']`','~$checksum`');return false;" >
						<strong>
							<i class="ico_chat fl" >&nbsp;</i>
						</strong>
					</a>
				~else if $detailsArr['userLoginStatus'] eq gtalk`
					<a href="#" class="fl" name="chatIcon" onClick="openChatWindow('~$profileid`','~$detailsArr['PROFILECHECKSUM']`','~$profileid`','~$detailsArr['USERNAME']`','~$detailsArr['HAVEPHOTO']`','~$checksum`');return false;" >
						<strong>
							<i class="ico_talk fl" >&nbsp;</i>
						</strong>
					</a>
				~else`
					<div class="chocoText fl" >
						~$detailsArr['userLoginStatus']`
					</div>
				~/if`
			</div>
			<p style="clear:both;">&nbsp;</p>

			~if $loggedIn eq 1`
				~if $profileOrExpressButton eq E`
				~if $detailsArr['CONTACT_REQUEST'] eq Y or $detailsArr['CONTACT_REPLIED'] eq Y`
				~else if $detailsArr['CONTACT_SENT'] eq Y`
					<div class="gryout" style="clear:both">Interest Expressed</div>
				~else`
					<div id="eoi_~$detailsArr['PROFILECHECKSUM']`" style="clear:both">
						<a class="blink b" >
							<input type="button"  class="btn_view b" value="Express Interest" style="cursor:pointer" />
						</a>
					</div>
				~/if`
				<div style="clear:both">
					~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
					<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4')" class="blink b" >
					~else`
					<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4')" class="blink b" >
					~/if`
						View Full Profile
					</a>
				</div>
				~else`
				<div>
					~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
					<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&resposeTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&resposeTracking=4')" >
					~else`
					<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&resposeTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&resposeTracking=4')" >
					~/if`
						<input type="button"  class="btn_view b" value="View Full Profile" style="cursor:pointer" />
					</a>
				</div>
					~if $detailsArr['CONTACT_REQUEST'] eq Y or $detailsArr['CONTACT_REPLIED'] eq Y`
					~else if $detailsArr['CONTACT_SENT'] eq Y`
						<div class="gryout" >&nbsp; Interest Expressed</div>
					~else`
						<div id="eoi_~$detailsArr['PROFILECHECKSUM']`">
							&nbsp;
							<a class="blink b" >
								Express Interest
							</a>
						</div>
					~/if`
				~/if`
			<div>
			<div id="view_contact_~$detailsArr['PROFILECHECKSUM']`" >
				<a class="blink" href="#" onclick="return false">
					See Phone/Email
				</a>
				</div>
				<span id="span_~$detailsArr['USERNAME']`" style="width:360px;" >
				</span>
			</div>
			<div>
				~if $detailsArr['BOOKMARKED'] eq Y`
					<a style="color:#808080;cursor:default;" >
						Shortlisted Profile
					</a>
				~else if $detailsArr['BOOKMARKED'] eq N`
					~if $detailsArr['FEATURED'] eq F`
					<a  oncontextmenu="return false;" class="blink" href="#" name="shortlistProfile" id="shortlist~$resultNumber`" onclick="shortl(this.id)" >
						Shortlist
					</a>
					~else`
					<a  oncontextmenu="return false;" class="blink" href="#" name="shortlistProfile" id="shortlist~$resultNumber`" >
						Shortlist
					</a>
					~/if`
				~/if`
			</div>        

			~if $detailsArr['SENT_DATE']`
			<div class="fs11 mar24top" style="color:#7A7A7A;">
				Sent to you on ~$detailsArr['SENT_DATE']`
			</div>
                        ~else if $detailsArr['JOIN_DATE']`
			<div class="fs11 mar24top" style="color:#7A7A7A;">
				Joined ~$detailsArr['JOIN_DATE']`
			</div>
			~/if`

			~if  $detailsArr['FEATURED'] neq Y and $detailsArr['FEATURED'] neq F and $sameGenderSearch neq 1`
			<div class="ico_close" id="~$resultNumber`" title="Remove from future searches" style="cursor:pointer;margin-left:-8px;">
				&nbsp;
			</div> 
			~/if`
			~else`
			<div>
				~if $detailsArr['FEATURED'] eq Y || $detailsArr['FEATURED'] eq F` 
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=W&searchid=~$searchId`&j=~$currentPage`&total_rec=~$totalFeaturedProfiles`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$featuredResultNo`&responseTracking=4')" >
				~else`
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4" onclick="return redirect('~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$detailsArr['PROFILECHECKSUM']`&cid=~$cid`&inf_checksum=~$inf_checksum`&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4')" >
				~/if`
					<input type="button" value="View Full Profile" class="btn_view_grey b blink" style="margin-top:-25px;" >
				</a>
			</div>
			<div>
				<p>
					&nbsp;
				</p>
				<p>
					&nbsp;
				</p>
				<p>
					Want to contact this person?
				</p>
			</div>
			<div>
				<input type="button" class="btn_view b" name="showLoginLayer"  value="Login" style="width:50px;cursor:pointer;" />
				&nbsp;or&nbsp;
				<a href = "~sfConfig::get('app_site_url')`/profile/registration_page1.php?source=searchpage" style = "text-decoration:none;"><input type="button" class="btn_view b" value="Register Free" style="width:96px;cursor:pointer;"/></a>
			</div>
			~/if`
		</div>
		<div class="div_interactions fl position7 fs12" ~if $detailsArr['CONTACT_REQUEST'] eq Y or $detailsArr['CONTACT_REPLIED'] eq Y` style="width:258px;margin-top:-20px;margin-left:8px;" ~else` style="width:258px;margin-top:15px;margin-left:8px;" ~/if` id="openshortlist~$resultNumber`" >			
	</div>
	<div id = "PHOTO_REQ~$profileid`" onclick="javascript:check_window('close_photo_mes(~$profileid`)')"></div>
	</div>
	</span>

	~if  $detailsArr['FEATURED'] eq Y`
	<div class="fr" style="height:30px; margin-bottom:25px; width:707px">
		<div class="fl divfirst">
			~if $featured eq Y`
				Congrats! You already have Featured Profile activated on your profile!
			~else`
				Want to feature your profile here? Get Featured Profile by calling
				<strong> 
					1-800-419-6299
				</strong> 
			~/if`
		</div>
		<div class="fl"> 
			<span class="mid-bg fl"> </span>
		</div>
		<div class="fl divthird">
			<span style="background: none repeat scroll 0 0 #FFF4B7;    border-bottom: 1px solid #FFE552; border-left: 1px solid #FFE552; height: 19px; padding: 5px;width: auto; border-right:1px solid #FFE552 " class="fr" id="featuredNextPreviousSpan" >
				<div>
					<input type="button" value="< Previous" class="pagination mar_left_10 fButtons" style="width:70px;cursor:pointer;margin-top:-2px;margin-left:1px;margin-right:2px;font-weight:bold; font-size: 9pt;color:#117DAA;" id = "fPrevButton" onmouseout="this.className='pagination mar_left_10';" onmouseover="this.className='pagination_selected mar_left_10';" >
					<input type="button" value="Next > " onmouseout="this.className='pagination mar_left_10';" onmouseover="this.className='pagination_selected mar_left_10';" class="pagination mar_left_10 fButtons" style="width:45px;cursor:pointer;margin-top:-2px;margin-left:1px;font-weight:bold; font-size: 9pt;color:#117DAA;" id = "fNextButton">
				</div>
			</span>
		</div>
	</div>
	~/if`


	~if !( $detailsArr['FEATURED'] eq Y or $detailsArr['FEATURED'] eq F)`
		~if $detailsArr['CONTACT_REQUEST'] eq Y`
			~if  $detailsArr['BOLDLISTING'] eq B`
			<div class="fl  width705  a6e5fb" id="tupleMsg~$resultNumber`" style="margin-bottom:25px;">
			~else`
			<div class="fl  width705  acdec" id="tupleMsg~$resultNumber`" style="margin-bottom:25px;">
			~/if`
				<div id="acceptdecline_~$detailsArr['PROFILECHECKSUM']`"  class="fl acdec1">
					<div class="fl" style="margin-top:7px;">
						This user has expressed interest in you
					</div>  	
					<div class="fl">
						<input value="Accept" style="cursor:pointer;width:50px;height:23px;position:relative;" class="btn_view b" type="button" id="accept_~$detailsArr['PROFILECHECKSUM']`">
					</div>
					<div class="fl" style="margin-top:1px">
					<input type="button" value="Not Interested" style="font-weight:bold; font-size: 9pt;color:#117DAA;cursor:pointer;position:relative;top:2px;height:24px;width:98px;text-align:center;margin-left:2px;padding-left:2px;" class="grey-cluster-subtitle" id="decline_~$detailsArr['PROFILECHECKSUM']`">

					</div>
				</div>
			</div>
		~else if $detailsArr['PHOTO_REQUEST'] eq Y`
			~if  $detailsArr['BOLDLISTING'] eq B`
			<div class="fl  width705 a6e5fb" style="line-height:2.3;margin-bottom:25px" id="tupleMsg~$resultNumber`">
			~else`
			<div class="fl  width705 acdec" style="margin-bottom:25px; line-height:2.3;" id="tupleMsg~$resultNumber`">
			~/if`
				&nbsp;&nbsp;&nbsp;&nbsp;This user has requested your photo. 
				<a href="/social/addPhotos" class="b">
					Upload your photo now
				</a>
			</div>
		~else if $detailsArr['HOROSCOPE_REQUEST'] eq Y`
			~if  $detailsArr['BOLDLISTING'] eq B`
			<div class="fl  width705 a6e5fb" style="line-height:2.3;margin-bottom:25px;" id="tupleMsg~$resultNumber`">
			~else`
			<div class="fl  width705 acdec" style="margin-bottom:25px; line-height:2.3;" id="tupleMsg~$resultNumber`">
			~/if`
				&nbsp;&nbsp;&nbsp;&nbsp;This user has requested your horoscope. 
				<a href="/profile/viewprofile.php?ownview=1&profilechecksum=~$profileChecksum`&EditWhatNew=AstroData&stype=~$stype`&searchid=~$searchId`&j=~$currentPage`&total_rec=~$TOTAL_RECORDS`&Sort=~$SORT`&~$NAVIGATOR`&offset=~$offsetVal`&responseTracking=4" class="b">
					Upload your horoscope NOW
				</a>
			</div>
		~else if $detailsArr['CHAT_REQUEST'] eq Y`
			~if  $detailsArr['BOLDLISTING'] eq B`
			<div class="fl  width705 a6e5fb" style="line-height:2.3;margin-bottom:25px;" id="tupleMsg~$resultNumber`">
			~else`
			<div class="fl  width705 acdec" style="margin-bottom:25px; line-height:2.3;" id="tupleMsg~$resultNumber`">
			~/if`
				&nbsp;&nbsp;&nbsp;&nbsp;This user has sent you a chat request... ~$userGender` might be interested 
			</div>
		~else if  $detailsArr['BOLDLISTING'] eq B`
			<div class="fl  width705 a6e5fb" style="line-height:2.3;margin-bottom:25px;margin-top:-25px;" id="tupleMsg~$resultNumber`">
				~if $boldListing eq B`
					&nbsp;&nbsp;&nbsp;&nbsp;Congrats! You already have Profile Highlighting activated on your profile!
				~else if $PaidStatus eq paid`
					&nbsp;&nbsp;&nbsp;&nbsp;Want to highlight your profile like this? Get Highlighted Profile by calling 
					<strong> 
						1-800-419-6299
					</strong> 
				~else`
					&nbsp;&nbsp;&nbsp;&nbsp;Want to highlight your profile like this? Get Highlighted Profile by calling 
					<strong> 
						1-800-419-6299
					</strong> 
				~/if`
			</div>
		~/if`
	~/if`
	~if $detailsArr['IGNORED'] eq Y`
	<div class="div_search_res_ignored" id="ignore~$resultNumber`" >
	~else`
	<div class="div_search_res_ignored" id="ignore~$resultNumber`" style="display:none;" >
	~/if`
		~$detailsArr['USERNAME']` will be removed from your search results and other lists.<br> ~$detailsArr['USERNAME']` will not be able to contact you any further.&nbsp; 
		<a href="#" name="undoIgnore" id="undoIgnore~$resultNumber`">
			Undo
		</a>
	</div>

