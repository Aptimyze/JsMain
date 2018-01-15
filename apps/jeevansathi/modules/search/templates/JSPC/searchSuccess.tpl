~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<!-- header -->
~include_partial("search/JSPC/searchHeader",['searchSummaryFormatted'=>$searchSummaryFormatted,'saveSearchArray'=>$savedSearches,'populateDefaultValues'=>$populateDefaultValues,'staticSearchData'=>$staticSearchDataArray,'loggedIn'=>$loggedIn,'sid'=>$searchId,'searchListings'=>$searchListings,'stype'=>$stypeName])`

<!--Clusters -->
~include_partial("search/JSPC/searchClusterFilter")`

~include_partial("photoAlbum")`
~include_partial('global/JSPC/_jspcContactEngineButtons')`

<!--start:middle-->
<div class="bg-4">
	
	<!-- Listing Tabs -->
	~if $searchListings`
	~include_partial("search/JSPC/searchListingTabs",['isRightListing'=>$isRightListing,'clickOn'=>$clickOn,'setGap'=>$setGap,'subscriptionType'=>$subscriptionType,'showKundliList'=>$showKundliList])`
	~/if`

	<!-- loader -->
	<div id="searchResultsLoaderTop" class="searchResultsLoader disp-none mainwid container" style="padding-top:100px;padding-bottom:100px;text-align:center;">
		<img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/loader_card.gif" style="vertical-align: middle; margin: 0pt 20px 0pt 0pt;">
	</div>
	<!-- loader -->

	~if !$searchListings`
	<div class="disp-none" id="relaxationBox"></div>
	~/if`

	~include_partial("search/JSPC/zeroResults",[noresultmessage=>$noresultmessage,pageHeading=>$pageHeading,resultCount=>$resultCount])`
	<div class="mainwid container disp-none pt30 pb30" id="js-searchContainer">
		<!--sub heading -->
                    ~include_partial("search/JSPC/changeMatchAlertLogic",[noresultmessage=>$noresultmessage,pageHeading=>$pageHeading,resultCount=>$resultCount])`
		<!--sub heading -->
		<div class="clearfix">
			<!--start:left col-->
			<div class="fl srpwid5 fontlig">
                                
				<div class="f22 color11 pb8 pl15" id="pageHeading">~$pageHeading`</div>
                                <span ~if $searchListings` style="display:none;" ~/if`>
                                ~if $showSaveSearchIcon`
						~include_partial("search/JSPC/saveSearchTop",['loggedIn'=>$loggedIn,'savedSearches'=>$savedSearches,'searchSummaryFormatted'=>$searchSummaryFormatted])`
				~/if`
                                </span>
				<!--start:filter-->
				<div class="filter" id="ClusterTupleStructure"> 
				</div>

				<span style="display:none;">
					~include_partial("search/JSPC/searchCluster")`
				</span>

				
				<span ~if $searchListings` style="display:none;" ~/if`>
					<!--start:search summary-->
					<div class="srppad10 f22 color11">Search Summary</div>
					<div class="srpbg2 fontlig f14 colr2">
						<div id="fullTextSearchSummary" class="srppad11 lh20">~$searchSummaryFormatted`</div>
					</div>
					<!--end:search summary-->
					~if $showSaveSearchIcon`
						~include_partial("search/JSPC/saveSearch",['loggedIn'=>$loggedIn,'savedSearches'=>$savedSearches,'searchSummaryFormatted'=>$searchSummaryFormatted])`
					~/if`
				</span>
				
				
				<!--start:div-->
				~*
				<!--
				<div class="srpbg1 srppad11 txtc mt15 fontlig mt16">
					<i class="sprite2 srpimg3"></i>
					<div class="mauto f14 colr2 pt15 srpwid7 lh20">In-person verification of profiles by jeevansathi team</div>
					<div class="seppad12"> <a href="/static/agentinfo" class="colr5 f15 mt20">Know More</a> </div>
				</div>
				-->
				*`
				<!--end:div--> 
			</div>
			<!--start:left col--> 

			<!--start:right col-->
			<div class="fr wid725 pos-rel" id="tupleContainer">
				<!-- loader -->
				<div class="overlaywhite" style="display:none;"></div>
				<div id="searchResultsLoader" class="searchResultsLoader" style="z-index:2;display:none;position:absolute;display:none;">
					<img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/loader_card.gif" style="vertical-align: middle; margin: 0pt 20px 0pt 0pt;">
				</div>
				<!-- loader -->

				<!-- sort -->
				~if $loggedIn`
					~if $ccListings`
					<div class="fontlig f14 relv ulinline clearfix srpHeightRightcc" id="heightRight">
					~else`
					<div class="fontlig f14 relv ulinline clearfix srpHeightRight" id="heightRight">
					~/if`
						<ul class="sortOrderParent fr">
              <li  class="sortOrder sortOrderR ~if $Sorting eq 'rel'`js-sort-grey cursd~else`cursp~/if`" value=T><span class="disp_ib srpbdr3 pr10">Relevance</span></li>
							<li  class="sortOrder sortOrderF ~if $Sorting eq 'fresh'`js-sort-grey cursd~else`cursp~/if`" value=O><span class="disp_ib srpbdr3 pr10 pl10">Freshness</span></li>
							<li  class="sortOrder sortOrderOnline showOnlineNow cursp"><span class="disp_ib pl10">Online now</span></li>
						</ul>
					</div>
				~else`
						<div class="fontlig f14 relv ulinline clearfix srpHeightRight"></div>
				~/if`
				
				<!-- sort -->
                                
                                ~if $loggedIn`
					<div class="fontlig f14 relv ulinline clearfix disp-none" id="heightRightVisitors">
						<ul class="fr">
              <li  class="~if $matchedOrAll eq 'A'`js-sort-grey cursd ~else` cursp~/if` js-visitors js-visTypeA" value=A><span class="disp_ib srpbdr3 pr10">All Profile Visitors</span></li>
            <li  class="~if $matchedOrAll eq 'M' || $matchedOrAll eq ''`js-sort-grey cursd ~else` cursp~/if` js-visitors js-visTypeM" value=M><span class="disp_ib pr10 pl10">Matching Visitors Only</span></li>
						</ul>
					</div>
                                ~/if`

				<!--start:search result-->
				<div id="featuredListing">
					<div id="featuredFirstResultsBlock">
					</div>
					<div id="featuredResultsBlock" class="disp-none" style="overflow:hidden;">
					</div>
                                    <div class="srpbdr5 clearfix cursp disp-none pos-rel" id="featuredProfiles" style="top: -7px;">
						<div class="fr pos-rel labelFeatured" >
							<div class="pos-abs triangle-topright srppos2"></div>
							<div class="bg_pink f12 fontlig colrw txtc wid165 srppad15">Featured Profile  <span id="featuredMoreMsg"></span><span class="showLessFeatured disp-none">(Show Less)</span> </div>
							<div class="pos-abs disp-none triangle-topleft srppos2right"></div>
						</div>
					</div>
				</div>
				<div class="mt8" id="searchResultsBlock">
					~include_partial("searchBasicTuple",[loggedIn=>$loggedIn,defaultImage=>$defaultImage])`
				</div>
				<!--end:search result--> 
				<!--start:pagination-->
				<div class="fullwid clearfix pt30 ">
					~include_partial("search/JSPC/pagination",[paginationArray=>$paginationArray,currentPage=>$currentPage])`
				</div>
				<!--end:pageination div--> 
				<div class='mt20' id='zt_~$zedo["masterTag"]`_searchbottom'> </div>
			</div>
			<!--end:pagination--> 

		</div>
		<!--start:right col--> 
	</div>
</div>
</div>
<!--end:middle--> 

<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer--> 
<script type="text/javascript">
	/** list all global Variables here */
	var isLoading = false; // isLoading is a useful flag to make sure we don't send off more than one request at a time 
	var _SEARCH_RESULTS_PER_PAGE = ~$_SEARCH_RESULTS_PER_PAGE`;
	var response = ~$firstResponse|decodevar`;
	var searchdata= ~$staticSearchData|decodevar`;
	var searchSort = response.sortType;
	var lastSearchId;
	var lastSearchBasedParam;
	var searchResultsPostParams = ''; // paramters need to perform pagination of search
	var SITE_URL = "~$SITE_URL`";
	var searchId = "~$searchId`";
	var newTagJustJoinDate = 0;
	var setGap='~$setGap`';
	var profilesPerPage = '~$profilesPerPage`';
        var matchedOrAll='~$matchedOrAll`';
	
	//var populateDefaultValues = ~$populateDefaultValues|decodevar`;
	$(document).ready(function() {
	$(".mCustomScrollbar").mCustomScrollbar({
	    theme:"minimal"
	});
	slider();
	});
</script>
