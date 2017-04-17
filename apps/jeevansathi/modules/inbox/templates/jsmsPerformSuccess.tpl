<!--Start of Request Callback Layer -->
~include_component('common', 'jsmsReqCallback')`
<!--End of Request Callback Layer -->	
~*
<!-- transparent image -->
<!--
<div class="srp_overlay posabs" style="z-index:100;display:none;" id="overLayerLoaderNoTInUse"></div>
-->
*`

~assign var=firstResponseArray value=$firstResponse|decodevar`
	
<div class="perspective fullwid fullheight" id="perspective">
<div class="fullwid fullheight" id="pcontainer">
<div id="sContainer" class="posrel">
	~include_partial("global/jsms3DotLayer")`
	~*
	<!--
	<div class="tapoverlay1 posabs" style="display:none;width:100%;" id="overLayLoader">
        	<div style="display:table-cell; vertical-align:middle; text-align:center">
        		<img src="http://img.labnol.org/css/lazy.gif"> <!-- LAVESH -->
	        </div>
   	</div>
	-->*`


	<!-- header section -->
        
        <div class="fullwid bg1 posfixTop" id="searchHeader">
~if ($title2 neq null)`
    ~if ( ($infotype eq 'ACCEPTANCES_RECEIVED')||($infotype eq 'INTEREST_RECEIVED') ||($infotype eq 'NOT_INTERESTED_BY_ME'))||($visitorAllOrMatching eq 'A')` ~assign var=isReceived value=1`  ~else` ~assign var=isReceived value=0` ~/if`  
    

    <div class="padd22 txtc">
    	<div class="posrel">
            <div class="posabs ot_pos1">
                <i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i>
            </div>
            <div class="clearfix fontlig f14 wid70p ot_brdr1">
               ~if ($isReceived neq 1)`<a href="~$SITE_URL`~$url`"> ~/if` <div class="wid50p txtc lh30 ~if ($isReceived eq 1)` ot_active ~else` ot_notactive ~/if` fl">
                   ~if $isReceived eq 1` ~$title` ~else` ~$title2`  ~/if`   
                   </div> ~if ($isReceived neq 1)`</a>~/if`
               ~if ($isReceived eq 1)`<a href="~$SITE_URL`~$url`"> ~/if`  <div class="wid50p txtc lh30 ~if ($isReceived eq 1)` ot_notactive ~else` ot_active ~/if` fr">
                    ~if $isReceived eq 1` ~$title2` ~else` ~$title`  ~/if`
                    </div> ~if $isReceived eq 1` </a> ~/if`
                </div>
            </div>
         </div>
   
  
    ~else`  
        	<div class="pad5">
			<div class="fl wid20p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>
			<div class="fl wid60p txtc color5  fontthin f19" id="totalCountId">~$heading`</div>
			<!--div class="fr">
				~if !$noresultmessage`
				<a href="#" class="mrl10" id="sortByDateRelDiv"><i class="mainsp doublearw"></i></a>
				~/if`
			</div-->
			<div class="clr"></div>
		</div>
  
      <div id="interestExpiringMessageDiv"><p id="interestExpiringMessage" class="txtc bg4 pad15 color13 f12 fontlig dispnone">These interests are expiring this week and will be removed from your Inbox. Please Accept/Decline</p></div>                  
      ~/if` 
      
  </div>
</div>

</div>
    <div onclick=viewSimilarLayer() class="posabs tapoverlay" id="overlayLayerSimilarProfile" style="display:none; opacity:.75; top:0px;"></div>
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

<div id="hamburger" class="hamburgerCommon dn fullwid">	
		~include_component('static', 'newMobileSiteHamburger')`	
	</div>

</div>
<script>
/** list all global Variables here */
var isLoading = false, _SEARCH_RESULTS_PER_PAGE = ~$_SEARCH_RESULTS_PER_PAGE`, minPage = 0, $div = $("#searchHeader"), message, ToshowOrNotRelaxCriteria = 0, contactCenter = 1;
var firstResponse = ~$firstResponseArray`;
var searchSort = firstResponse.sortType, searchResultsPostParams = '', SITE_URL = "~$SITE_URL`", contactTracking = '~$tracking`', NAVIGATOR = "~$NAVIGATOR`", stypeKey = '', contactEngineChannel = "CC";
</script>
