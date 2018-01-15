~*
<!-- transparent image -->
<!--
<div class="srp_overlay posabs" style="z-index:100;display:none;" id="overLayerLoaderNoTInUse"></div>
-->
*`

<div class="perspective fullwid fullheight" id="perspective">
<div class="fullwid fullheight" id="pcontainer">
<div id="sContainer" class="posrel">
	~include_partial("global/jsms3DotLayer")`
	~*
	<!--
	<div class="tapoverlay1 posabs" style="display:none;width:100%;" id="overLayLoader">
        	<div style="display:table-cell; vertical-align:middle; text-align:center">
        		<img src="http://img.labnol.org/css/lazy.gif"> 
	        </div>
   	</div>
	-->*`


	<!-- header section -->
        <div class="fullwid posfixTop " id="searchHeader">
         <div class="bg1">
          	<div class="pad1">
            <div class="rem_pad1 posrel fullwid ">
              <div class="posabs" style="left:0;top:18px;"> <i id="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div>
              <div class="white fontthin f19 txtc" id="totalCountId">~$heading`</div>
              <div class="posabs savsrc-pos1">
                ~if !$dontShowSorting`
			~if $showSaveSearchIcon`
        	        <div class="posrel Openlayer dispibl"> <i class="savsrc-sp savsrc-icon1"></i> 
                	</div>
			~/if`
                    <div class="posrel dispibl"> <a href="#" class="dispibl" id="sortByDateRelDiv"><i class="mainsp doublearw"></i></a> </div>
                ~/if`
              </div>
              <div class="clr"></div>
            </div>
          </div>
          </div>
        </div>

<div onclick=viewSimilarLayer() class="posabs tapoverlay" id="overlayLayerSimilarProfile" style="display:none; opacity:.75;"></div>
<div class="posabs bg4 btmo fullwid" id="containeridd" style="position:fixed; bottom:0; display:none; z-index:102;">
    <div class="pad19 fontlig color7" id="similarProfilesView" style="padding: 20px 15px 30px 15px;">
    <div class="clearfix" style="padding-bottom: 10px;">
                  <div class="fl f16 " id="InterestSentStatus"></div>
                    <div onclick=viewSimilarLayer() class="fr f14 opa50">Close</div>
    </div>
    <div class="clearfix" style="padding-bottom: 20px;">
                  <div class="fl f16 ">View Similar Profiles</div>
    </div>
  <div id="simProfileDiv"  class="clearfix pt10">
                 <div >
                      <img src="" style="width:45%; height:60px;border-radius:30px;  visibility: hidden;">
                      <img src="~$SITE_URL`/images/jsms/commonImg/loader.gif">
                   </div> 
  </div>
            </div>
</div>

	</div>
	</div>
	<div id="hamburger" class="hamburgerCommon dn fullwid">	
		~include_component('static', 'newMobileSiteHamburger')`	
	</div>
	</div>
<script>
/** list all global Variables here */
var isLoading = false; // isLoading is a useful flag to make sure we don't send off more than one request at a time 
var _SEARCH_RESULTS_PER_PAGE = ~$_SEARCH_RESULTS_PER_PAGE`;
var minPage = 0;
var $div = $("#searchHeader");
var message;
var ToshowOrNotRelaxCriteria = 0;

var firstResponse = ~$firstResponse|decodevar`;
var stypeKey = '~$stypeName`';
var searchSort = firstResponse.sortType;
var searchResultsPostParams = ''; // paramters need to perform pagination of search
var SITE_URL = "~$SITE_URL`";
var NAVIGATOR = "~$NAVIGATOR`";
var backSearchId = "~$backSearchId`";
var BackButtonPointsHere = "~$backReferer`";
var fmBack = "~$fmBackECP`";
var showECPPage ="&toShowECP=1";
var contactEngineChannel = "S";

</script>
