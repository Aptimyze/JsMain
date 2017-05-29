<!-- start header -->
~include_partial('global/header',[showSearchBand=>1,searchId=>$searchId,pageName=>$pageName,loggedInProfileid=>$loggedInProfileid,szNavType=>$szNavType,showGutterBanner=>1])`
<style>
</style>
<!--end header -->

~if $GR_LOGGEDIN eq 0 and !$GR_ISEARCH`
~if $GR_PAGE eq 1`
<!-- Google Remarketing Starts -->
<script>
/* <![CDATA[ */
var google_conversion_id = 1056682264;
var google_conversion_label = "j5CPCPy1_gIQmOLu9wM";
var google_custom_params = {
CurrentDate : '~$GR_DATE`',
              PageType : 'SearchResults',
              Gender : '~$GR_GENDER`',
              Manglik : '~$GR_MANGLIK`',
              MaritalStatus : '~$GR_MSTATUS`',
              Religion : '~$GR_RELIGION`',
              Residence : '~$GR_RESIDENCE`',
              Edu_Occ : '~$GR_EDU_OCC`',
              MotherTongue : '~$GR_MTONGUE`',
              Caste : '~$GR_CASTE`'
};

var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/1056682264/?value=0&amp;label=j5CPCPy1_gIQmOLu9wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Remarketing Ends -->

~/if`
<script type="text/javascript">

(function() {
    try {
        var viz = document.createElement('script');
        viz.type = 'text/javascript';
        viz.async = true;
        viz.src = ('https:' == document.location.protocol ?'https://ssl.vizury.com' : 'http://www.vizury.com')+ '/analyze/pixel.php?account_id=VIZVRM782';

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            pixel.parse();
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                pixel.parse();
            }
        };
    } catch (i) {
    }
})();
  
			
               
</script>
~/if`
<!--Main container starts here-->
<div id="main_cont">

	<div id="container">
	<!-- start search-->
	<!--QUICK SEARCH STARTS-->
		<p class="clr_4">
		</p>

		<div id="topSearchBand">
		</div>

		~include_partial('global/sub_header',[pageName=>$pageName])`

		<p class="clr_4">
		</p>
		<p class="clr_4">
		</p>
		<!--top tab  start here-->
		<p class="clr">
		</p>
		<p class=" clr">
		</p>
		<p class=" clr_4">
		</p>
		~if $showGotItBand`
			~include_partial('global/gotItBand',[GotItBandPage=>$GotItBandPage,GotItBandMessage=>$GotItBandMessage])`
				<p class=" clr_4"></p>
				<p class=" clr_4"></p>
				<p class=" clr_4"></p>
				<p class=" clr_4"></p>

		~/if`
		<!--Clusters -->
		~if !$hideClusters`
			~include_partial("searchCluster",[searchClustersArray=>$searchClustersArray,jsonClustersToShow=>$jsonClustersToShow,searchId=>$searchId,reverseDpp=>$reverseDpp,searchBasedParam=>$searchBasedParam,openClusters=>$openClusters,clusterLabelMappingArray=>$clusterLabelMappingArray,WIDTH=>530,income_arr_rupee_html=>$income_arr_rupee_html,income_arr_dollar_html=>$income_arr_dollar_html,income_arr_rupee_mapping_html=>$income_arr_rupee_mapping_html,income_arr_dollar_mapping_html=>$income_arr_dollar_mapping_html])`
		~/if`
		<!--Clusters -->
<div style = "display:none">
	<div id = "albumCode">
	</div>
</div>

<div class="container container_layer" id="container_layer" style="display:relative;">
<span class="foregroundImage" id="foregroundImage1"></span> <!--transparent on cluster action -->

<div id="searchResultsLoader" class="searchResultsLoader" style="z-index:2;display:none;position:absolute">
        <div style="border: 2px solid rgb(204, 204, 206); width: 252px; font: bold 18px arial; color: rgb(68, 68, 68); background: none repeat scroll 0% 0% rgb(246, 246, 246); text-align: center; -moz-border-radius: 10px 10px 10px 10px;">
                <div style="margin: 12px 0pt;">
                        <img src="~sfConfig::get('app_img_url')`/images/searchImages/loader_small.gif" style="vertical-align: middle; margin: 0pt 20px 0pt 0pt;">
                        Updating Results
                </div>
        </div>
</div>
<input type = "hidden" value = "~$searchId`" id = "searchId">


<!-- start:top search result box -->
<div id="div_topnote">
	<div class = "fl" style="font-size:23px; color:#5b5b5b;">
		
		~$heading`
		<div class="pad4top pad10bottom fs16" style="color:#5b5b5b;">
			~$subHeading`~if $partnermatchesPage eq 1` <a style="text-decoration:underline;cursor:pointer;" href="/profile/dpp"> Desired Partner Profile</a>~/if`
                        ~if $subHeadingLinkText` <a style="text-decoration:underline;font-size:14px;cursor:pointer;" href="/search/MatchAlertToggle?logic=~$subHeadingLogic`">~$subHeadingLinkText`</a>~/if`
			
		</div>
	</div>

	<!-- Sorting -->
	~if !$hideSorting`
	~if !$zeroResults`
		~if $reverseDpp eq 1`
                        ~assign var='sortReverse' value='1'`
		~elseif $twowaymatch eq 1 || $partnermatchesPage eq 1`
                        ~assign var='sortReverse' value="$searchBasedParam"`
                ~else`
                        ~assign var='sortReverse' value='0'`
                ~/if`
	<div class="fr">
		<div  class="sort_new" style="width:151px\0/IE9;"> 
			<table border="0">
				<tr>
					<td>Sort by :&nbsp;</td>
				</tr>
				<tr>
					~if $sort_logic eq 'T' || $sort_logic eq '' || $sort_logic eq 'P'`
					<td class = "b">Relevance</td>
					<td><span class="blk_arrw" style="margin-top:3px;">&nbsp;</span></td>
					~else`
					<td><a class = "blink" href="~sfConfig::get('app_site_url')`/search/perform/sort/~$searchId`/T/~$sortReverse`">Relevance</a></td>
					~/if`
					<td>|</td>
					~if $sort_logic eq 'O'`
					<td class = "b">Freshness</td>
					<td><span class="blk_arrw" style="margin-top:3px;">&nbsp;</span></td>
					~else`
					<td><a class="blink" href="~sfConfig::get('app_site_url')`/search/perform/sort/~$searchId`/O/~$sortReverse`">Freshness</a></td>
					~/if`
				</tr>
			</table>
		</div> 
	</div>
	~/if`
	~/if`
	<!-- Sorting -->

	~if !$hideTextSearch`	
	<div class="sp15" style="border-top: 1px solid #c1c1c1"></div>
	<p class="fs12">
		~$searchCriteriaText` : ~$searchedParamsText` &nbsp; 
                <a class="fs12 blink" id = "full_criteria" onclick="fullCriteria('show'); return false;">See full criteria</a>
                ~if $editDpp eq 1`
                        <a class="fs12 blink mar5left" id = "full_criteria" href="/profile/dpp">Edit</a>
                ~/if`
                <!-- See full criteria popup -->
                <div id="popup_Info" class="fl fs12" style = "display:none;" onclick="javascript:check_window('fullCriteria(\'hide\')');">
                        <table cellspacing="10px">
                        ~foreach from=$searchedParamsTextArr item=value key=kk`
                                <tr>
                                <td style = "width:110px;">~$kk`</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>~$value`<td>
                                </tr>
                        ~/foreach`
                        </table>
                </div>
                    <!-- -->
	</p>
	~/if`

	<div class="sp5">&nbsp;</div>
	~if $showSaveDppAndCriteria eq 1`
	<div class="fl">
		<i class="fl blue_arrw_dwn"></i> <a class="b fs12 blink" onclick="saveAsDpp('show',2); return false;"> Save search for future use</a>
		<div class=" div_interactions fl position3 fs12 w240" id = "saveSearchCriteria" style="display:none">
			<div class="fl divHeading lh19 white b">
				<div class="white-arrow">&nbsp;</div> Save search for future use
			</div>
			<div class="divlinks fl w240" onclick="javascript:check_window('saveAsDpp(\'hide\',2)');">
				<div style="text-align:center;display:none" id="saveSearchCriteriaLoader"><img src="~sfConfig::get('app_img_url')`/profile/images/ajax-loader.gif"></div>
				<span class="width500 fl" style="font-size:14px; display:none" id = "saveSearchCriteriaMsg1"><div class="sp12"></div><i class="ico_right_1 fl">&nbsp;</i>Your search has been saved. Next time just click it under 'My Saved Searches'.</span>
				~if $maxLimit neq 1`
				<span id = "saveSearchCriteriaMsg">
				<span class = "fl red_new" id = "errMsg1" style = "display:none">Please provide a name for saving search</span>
				<span class = "fl red_new" id = "errMsg2" style = "display:none">A search with this name already exists, please provide a different name</span>
				<span class = "fl red_new" id = "errMsg3" style = "display:none">Please try again after some time</span>
				<div class="sp12" id="errMsgSpace" style="display:none"></div>
				<span>You need not enter your search criteria every time. Save your search once and just click it whenever you want to search next. <br><br>Give a name for your search:</span><br />
				<span class="fl"><input type="text" id="saveSearchCriteriaLabel" style = "color:grey" maxlength="40" value="e.g: 28-32, Never Married, Delhi" title = "Give a name which helps you remember what you searched for" /></span><br />
				<div class="sp12"></div>
				<span class=" fl txt_center width100"><input type="button" value="Save" class="btn_view b" style="width:64px;cursor:pointer" id="saveBtn2" /></span>
				</span>
				~else`
				<span id = "saveSearchCriteriaMsg">
				<span class = "fl red_new" id = "errMsg1" style = "display:none">Please provide a name for saving search</span>
				<span class = "fl red_new" id = "errMsg2" style = "display:none">A search with this name already exists, please provide a different name</span>
				<span class = "fl red_new" id = "errMsg3" style = "display:none">Please try again after some time</span>
				<div class="sp12" id="errMsgSpace" style="display:none"></div>
				<span class="fl">You already have ~$maxSaveSearchesAllowed` saved searches. To save this search, please replace with one of these saved searches:</span>
				<div class="sp12"></div>
				~if $savedSearchExists`
                                <span class=" fl">Replace</span><br />
                                <span class=" fl"><select id="saveDD">
					~foreach from=$savedSearches item=value key=kk`
						<option value = "~$value.ID`">~$value.SEARCH_NAME`</option>
                                        ~/foreach`
					</select>
				</span>
				<div class="sp12"></div>
				~/if`
				<span>Give a name for your search</span><br />
                                <span class="fl"><input type="text" id="saveSearchCriteriaLabel" maxlength="40" style = "color:grey" value="e.g: 28-32, Never Married, Delhi" title = "Give a name which helps you remember what you searched for" /></span><br />
				<div class="sp12"></div>
				<span class=" fl txt_center width100"><input type="button" value="Save" class="btn_view b" style="width:64px;cursor:pointer" id="saveBtn2" /></span>
				</span>
				~/if`
				<div class="separator fl width100">&nbsp; </div>
				<div class="fr b"><a href="#" onclick="saveAsDpp('hide',2); return false;">Close [x]</a></div>
			</div>
		</div>
	</div>
        
                ~if $loggedIn eq 1 and $premiumDummyUser eq 1`
                        <div class="fl mrg20pxleft">
                          <i class="fl blue_arrw_dwn"></i>    <a class="b fs12 blink" onclick="saveAsDpp('show',1); return false;"> Email me matches like these</a>
                               <div class="div_interactions fl position4 fs12" id="saved_as_desired" style="display:none">
                                       <div class="fl divHeading lh19 white b"><i class = "fl wht_arw"></i> Email me matches like these</div>
                                       <div class="divlinks fl w240" onclick="javascript:check_window('saveAsDpp(\'hide\',1)');">
                                               <div id = "saveDppMsg2">You will recieve matches on your email based on this search criteria
                                               <div class="sp12"></div>
                                               <span class="width100 fl txt_center"><input type="button"  value="Save" class="btn_view b" style="width:64px;cursor:pointer" id = "saveBtn1" /></span>
                                               </div>
                                               <div style="text-align:center; display:none" id="saveDppLoader"><img src="~sfConfig::get('app_img_url')`/profile/images/ajax-loader.gif"></div>
                                               <span class="width100 fl" style="font-size:14px; display:none" id = "saveDppMsg1"><div class="sp12"></div><i class="ico_right_1 fl">&nbsp;</i>Saved Successfully</span>
                                               <div class="separator fl width100">&nbsp; </div>
                                               <div class="fr b"><a href="#" onclick="saveAsDpp('hide',1); return false;">Close [x]</a></div>
                                       </div>
                               </div>
                       </div>
                ~/if`
	~/if`
</div>

<!-- -->
~if $zeroResults || $relaxedResults || ($showCasteMapping && $showBroadeningBreadCrumbPosition eq 'up')`
<div class="message_box">
        ~if $zeroResults`
                ~assign var='addMargin' value=1`
                <div><i class="ico_info fl">&nbsp;</i>
                ~if $lastUsedCluster eq 'LAST_ACTIVITY'`
                        There are no users online who match your search. <a href="~sfConfig::get('app_site_url')`/search/perform/relaxRefinement/LAST_ACTIVITY/~$searchId`?searchBasedParam=~$searchBasedParam`"><b>See all users matching your search</b></a>.
                ~elseif $lastUsedCluster eq 'VIEWED'`
                        ~if $lastUsedClusterValue eq 'V'`
                        There are no viewed profiles which match your search. <a href="~sfConfig::get('app_site_url')`/search/perform/relaxRefinement/VIEWED/~$searchId`?searchBasedParam=~$searchBasedParam`"><b>See all users matching your search</b></a>.
                        ~else`
                        There are no unviewed profiles which match your search. <a href="~sfConfig::get('app_site_url')`/search/perform/relaxRefinement/VIEWED/~$searchId`?searchBasedParam=~$searchBasedParam`"><b>See all users matching your search</b></a>.
                        ~/if`
                ~else`
        		~if $searchBasedParam eq 'matchalerts' ||  $searchBasedParam eq 'kundlialerts'`
				While we get some recommendations for you, browse your <a href="~sfConfig::get(app_site_url)`/search/partnermatches" style="color:#0046C5;">Desired Partner Matches</a>
			~else`
	                	Kindly relax your criteria and search again
			~/if`
                ~/if`
		</div>
	~/if`

        ~if $relaxedResults && !$zeroResults`
        ~assign var='addMargin' value=1`
        <div ~if $addMargin`style="margin-top:5px;" ~/if`><i class="ico_info fl">&nbsp;</i>We have relaxed some criteria to deliver more results to you. <a class="b" href="~sfConfig::get('app_site_url')`/search/perform/noAutoRelax/~$searchId`/1?searchBasedParam=~$searchBasedParam`">Click Here</a> for your original search</div>
        ~/if`

        ~if $showCasteMapping`
        ~if $showBroadeningBreadCrumbPosition eq 'up'`
        <div ~if $addMargin`style="margin-top:5px;" ~/if`><i class="ico_info fl">&nbsp;</i>To get ~$moreProfiles` more matching profiles, <a class="b" href="~sfConfig::get('app_site_url')`/search/perform/addEthnicities/~$searchId`?searchBasedParam=~$searchBasedParam`">include these castes</a> in your search : ~$casteSuggestMessage`</div>
        ~/if`
        ~/if`

</div>
~/if`
<!-- -->

	~if $loggedIn eq 1 and $noOfResults gt 0 and $noOfPages gte $currentPage`
		~if $premiumDummyUser`
		<div class = "fl">
			&nbsp;<input type="checkbox" class="multibuttonSelect" style="width:16px;">&nbsp;
		</div>
		~/if`
	<div class="fl div3">
		<div id="eoi_multi" class="fl eoi" >
		<input type="button" class="multibutton btn_view b fl"  value="Express Interest" id="multibutton">&nbsp;<i class="arrow-down"></i>
		</div>
		<div style="font-size:14px;margin-top:6px" class="fl">
			&nbsp;Select Multiple profiles and Express interest
		</div>
	</div>
	~/if`

	~assign var='resultNumber' value=0`
	~assign var='searchResultNumber' value=0`
	~assign var='filterStart' value=0`

	~foreach from = $finalResultsArray item = detailsArr key = profileid`
		~assign var='resultNumber' value=$resultNumber+1`
		~if $detailsArr['FEATURED'] neq Y`
			~assign var='searchResultNumber' value=$searchResultNumber+1`
		~/if`

		~if $detailsArr['FILTER_REASONS'] && $filterStart eq 0`
			~assign var='filterStart' value=1`
			<div class="div_search_res_filter_msg pos_rltv1">
				<i class = "filter_arw" style="margin-top:4px;">&nbsp;</i>
				<div style="color:#FFFFFF; margin-left: 33px;">
					<span style="font-size: 18px;" class="b">Profiles below have filtered you out</span>
					<br>
					<span>Your interests will go to their "filtered" folder, so response to your interests may be delayed</span>
				</div>
			</div>
		~/if`

		~include_partial("searchTuple",[detailsArr=>$detailsArr,profileid=>$profileid,resultNumber=>$resultNumber,isAlbumArray=>$isAlbumArray,horoscopeArray=>$horoscopeArray,fieldsDisplayedInSearchTuple=>$fieldsDisplayedInSearchTuple,loggedIn=>$loggedIn,profilePicArray=>$profilePicArray,resultNumber=>$resultNumber,featurePosition=>$featurePosition,profileOrExpressButton=>$profileOrExpressButton,userGender=>$userGender,checksum=>$checksum,searchId=>$searchId,stype=>$stype,NAVIGATOR=>$NAVIGATOR,TOTAL_RECORDS=>$noOfResults,searchResultNumber=>$searchResultNumber,SORT=>$sort_logic,currentPage=>$currentPage,featuredResultNo=>$featuredResultNo,profileChecksum=>$profileChecksum,sameGenderSearch=>~$sameGenderSearch`,boldListing=>~$boldListing`,featured=>~$featured`,totalFeaturedProfiles=>~$totalFeaturedProfiles`,paidLabel=>~$paidLabel`])`

		~*
		<!--
		~if $searchResultNumber eq 5 and $noOfResultsOnCurrentPage neq 5`
			<div class="banner_space" id="searchPageMidBanner" >
			</div>
		~/if`
		-->
		*`
	~/foreach`

	~if $loggedIn eq 1 and $noOfResults gt 0 and $noOfPages gte $currentPage`
		~if $premiumDummyUser`
		<div class = "fl">
			&nbsp;<input type="checkbox" class="multibuttonSelect" style="width:16px;">&nbsp;
		</div>
		~/if`
	
	<!-- end:top search result box -->
	
	<div class="fl div3">
		<div id="eoi_bottom" class="fl eoi" >
			<input type="button" class="multibottom btn_view b fl" value="Express Interest" >
			&nbsp;<i class="arrow-up"></i> &nbsp;
		</div>
		
		<div style="font-size:14px;margin-top:6px" class="fl">
			&nbsp;Select Multiple profiles and Express interest
		</div>
	</div>
	~/if`
    
        ~if $showCasteMapping`
        	~if $showBroadeningBreadCrumbPosition eq 'down'`
		<div class="message_box">
		        <div ~if $addMargin`style="margin-top:5px;" ~/if`>
				<i class="ico_info fl">&nbsp;</i>To get ~$moreProfiles` more matching profiles, <a class="b" href="~sfConfig::get('app_site_url')`/search/perform/addEthnicities/~$searchId`?searchBasedParam=~$searchBasedParam`">include these castes</a> in your search : ~$casteSuggestMessage`
			</div>
		</div>
        	~/if`
        ~/if`

	~if !$zeroResults && $noOfResults gt 10`
	<div id="div_bot_pagination">
		<div class="fr">

			~if $reverseDpp eq 1`
				~assign var='reverseDppVars' value="&reverseDpp=1"`
			~elseif $twowaymatch eq 1`
				~assign var='reverseDppVars' value="&twowaymatch=1"`
                        ~else if $searchBasedParam`
                                ~assign var='reverseDppVars' value="&searchBasedParam=~$searchBasedParam`"`
                        ~/if`

			~if $currentPage neq 1`
				~assign var=previous value=$currentPage-1`
				<a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$previous`~$reverseDppVars`"><input type="button" onClick='location.href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$previous`~$reverseDppVars`";return false;' class="pagination" onmouseover="this.className='pagination_selected';" onmouseout="this.className='pagination';" value="Previous" style="cursor:pointer;"></a>
			~/if`

			~foreach from=$paginationArr key=key item=item`
				~if $item eq $currentPage`	
					<input type="button" class="pagination_selected" value="~$item`" >
				~else`
					<a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$item`~$reverseDppVars`">
					<input type="button" style="cursor:pointer;" onClick='location.href ="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$item`~$reverseDppVars`";return false;' class="pagination" onmouseover="this.className='pagination_selected';" onmouseout="this.className='pagination';" value="~$item`" >
					</a>
			~/if`
			~/foreach`
			~if $currentPage neq $noOfPages`
				~assign var='nextPage' value=$currentPage+1`
				<a href="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$nextPage`~$reverseDppVars`"><input type="button" onClick='location.href ="~sfConfig::get('app_site_url')`/search/perform?searchId=~$searchId`&currentPage=~$nextPage`~$reverseDppVars`";return false;' class="pagination" onmouseover="this.className='pagination_selected';" onmouseout="this.className='pagination';" style="cursor:pointer;" value="Next" /></a>
			~/if`

		</div>
	</div>
	~/if`
</div>

<!-- photo layer -->
<div style = "display:none" id = "req_mes">
	~if $havephoto eq '' or $havephoto eq 'N'`
	<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute; top:188px; left:176px" >    
		<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
			<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)">
			</div>
		<p class="width100 fl"><i class="ico_right_sml fl">&nbsp;</i>Your photo request has been sent.
		<br />
		
		<font class="b"><a href="~sfConfig::get('app_site_url')`/social/addPhotos">Upload your photo now >></a></font>
		</p>
		</div>
	</div>
	~/if`
</div>
<div style = "display:none" id = "err_mes">
	<div class="div_interactions fl  fs12" id="success_mesPROFILEID" style="position:absolute; top:188px; left:176px" >    
		<div class="divlinks fl w240 pos_rltv1" style="padding:0px 0px 10px 10px"  >
			<div class="fr ico_close_green mar_top_4" id="closeIconPROFILEID" onclick = "close_photo_mes(PROFILEID)">
			</div>
		<p class="width100 fl"><i class="ico_cross fl">&nbsp;</i>ERROR MESSAGE
		</p>
		<br />
		</div>
	</div>
</div>
<!-- photo layer ends here -->		
<!--top tab ends here -->
<!--photo and other top details starts here -->
<p class="clr_18"></p>


<!--forward link ends here  -->

~if $profileStatus eq 'I' || $profileStatus eq 'U'`
<div style="display:none" id="notactive_eoi">
<div class="divlinks fl w350 fs12 eoi">
<div class="sp15"></div>
<i class="ico_right fl"></i>~if $profileStatus eq 'I'`Your interest will be delivered only when your profile is complete. <a href='/profile/viewprofile.php?ownview=1&EditWhatNew=incompletProfile' class="b">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Complete your profile NOW</a>~else` Your interest will be delivered once your profile goes live.~/if`
<div >&nbsp;</div>
<div class="separator fl width100"></div>
                                        <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>

        </div>
</div>
~else`
~if $PaidStatus eq 'free'`
<div style="display:none" id="free_eoi">
<div class="divlinks fl w350 fs15 eoi">

<div class="sp15"></div>
   	   <i class="ico_right fl"></i>MESSAGE_SUCCESS
       <div >&nbsp;</div>
       ~if $FREE_TRIAL_OFFER`
       ~Messages::getFreeTrialOfferLink()`
  <div class="fs15">See Phone/Email of THISTHESE if HESHE ACCEPTS
    your interest</div>
    ~if $FREE_TRIAL_OFFER eq 'c'`
    <div class="sp24"></div>
<div class="fs15">To avail this offer,</div>

~include_partial("contacts/profile_phone_photo_c", ['profileObj' => $loginProfile])`
~/if`
 <div class="sp24"></div>
 <div class="fs15">~if $FREE_TRIAL_OFFER eq 'c'`Hurry!~/if` Offer~if $FREE_TRIAL_OFFER eq 'd'` expires on ~else` valid till~/if`&nbsp;<strong>~$loginProfile->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y')`</strong>
</div>

<div class="fs15">  ~Messages::getKnowMoreLink()`</div>
<div >&nbsp;</div>
<div class="separator fl width100"></div>
                                        <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>

        </div>
~else`
<div class="sp24"></div>
       <span >To send a personalized message to THISTHESE, </span>
<div class="sp15"></div>
<span class="fs15"><input class="fto-btn-green sprite-new cp" type="button" value="Buy Paid Membership" name="Membership" onclick="RedirectFromCE('/profile/mem_comparison.php?from_source=srp_after_eoi')"></span>      
<div class="sp24"></div>
 <div class="separator fl width100"></div>       
					<div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>
~/if`            
        </div>
</div>
~else`
<div style="display:none" id="paid_eoi">
<div class="divlinks fl w350 fs15 eoi" id="mes_PROFILEID">

<div class="sp15"></div>
   	   <i class="ico_right fl"></i>MESSAGE_SUCCESS
       <div class="sp15">&nbsp;</div>
       <span>Send HIMHER a personalized message </span>
       <div class="sp15"></div>
       <div class="fr widthauto">
        ~if $drafts`
        <select style="width:auto\9" id="drafts_PROFILEID" onchange="updatetextarea(this)">
       ~foreach from=$drafts key=k item=v`
       ~if !$textMessage`
       ~assign var='textMessage' value=$v.MESSAGE`
       ~/if`
       <option value='~$v.DRAFTID`'>~$v.DRAFTNAME`</option>
       ~/foreach`
       </select>
		~/if`
       </div>
       <div class="sp5"></div>
       
       <span><textarea rows="0" cols="0" class="width100" style="height:55px" id="textarea_PROFILEID">~$textMessage|decodevar`</textarea></span>      
       <div class="sp24"></div>
 <div class="txt_center"><input type="button" class="btn_view b curpt" value="Send" onclick="javascript:SEND_MESSAGE_FUNC" id="send_PROFILEID" style="width:50px"></div>
       <div class="sp24"></div>
 <div class="separator fl width100"></div>       
            <div class="fr b"><a  href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>    
        </div>
</div>
~/if`
~/if`
<div style="display:none" id="inform_mes">
<div class="divlinks fl w350 fs15 eoi">

<div class="sp15"></div>
   	   <div class="fl" ><i class="ICON_CSS fl"></i></div><div class="fs15 fl w300" >INFORM_MESSAGE</div>
		<div class="sp24"></div>
       
 <div class="separator fl width100"></div>       
            <div class="fr b"><a href="#" onclick="return CLOSE_FUNC">Close [x]</a></div>
            
        </div>
</div>
<!--Main container ends here-->

</div>
</div>
~BrijjTrackingHelper::setJsLoadFlag(1)`
~include_partial('global/footer',[bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,data=>~$loggedInProfileid`,bms_topright_loggedin=>$bms_topright_loggedin,pageName=>$pageName])`

<!-- Google remarketing-->
~if $GR_LOGGEDIN eq 0 and !$GR_ISEARCH`
	~include_partial('global/remarketing',[GR=>'0',KO=>'1',SO=>'1'])`
~/if`
<!-- remarketing ends-->

<script>
$("#saveSearchCriteriaLabel").click(function() {
if($("#saveSearchCriteriaLabel").val()=='e.g: 28-32, Never Married, Delhi')
{
$("#saveSearchCriteriaLabel").css("color","black");
$("#saveSearchCriteriaLabel").val("");
}
});

	var currentPage = "~$currentPage`";
	var savedSearchLimitReached = ~$maxLimit`;
	var loggedIn = "~$loggedIn`";
	var showOnlyGunaMatch='Y';
	var loggedInProfileid = "~$loggedInProfileid`";
	var searchId = "~$searchId`";
	if(loggedIn == '1' && '~$searchedGender`' != '~$loggedInGender`')
		astro_icons();
	var stype='~$stype`';
	var responseTracking = 4;
	var membershipStatus="~$PaidStatus`";
	~if $profileStatus eq 'I' || $profileStatus eq 'U'`
	membershipStatus="notactive";
	~/if`
	var eoiButton="~$profileOrExpressButton`";
	var himher="~$himher`";
	var heshe="~$heshe`";
	~if $drafts and $PaidStatus neq 'free'`
	var pattern1 = /\#n\#/g;
	var MESCE = new Array(); 
	~foreach from=$drafts key=k item=v`
	  temp="~$v.MESSAGE|decodevar`";
      MESCE['~$v.DRAFTID`']=temp.replace(pattern1,"\n");
       ~/foreach`
	~/if`
	var isExpressButton="~$EXPRESSBUTTON`";

	var bms_searchMid = '~$sf_request->getAttribute('bms_searchMid')`';
	
	var PH_UNVERIFIED_STATUS="~$PH_UNVERIFIED_STATUS`";
  //Stop invalid phone layer
  var SHOW_UNVERIFIED_LAYER="~$SHOW_UNVERIFIED_LAYER`";
	var FREE_TRIAL_OFFER="~$FREE_TRIAL_OFFER`";
	var presetEoiMessage="~$presetEoiMessage|decodevar`";
	var presetAccMessage="~$presetAccMessage|decodevar`";
	var presetDecMessage="~$presetDecMessage|decodevar`";
	var postDataVar={'page_source':'search'};
</script>
