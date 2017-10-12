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
	<div class="fullwid bg1 posfixTop" id="searchHeader">
		<div class="pad5">

			<div ~if $dontShowHam` style="visibility:hidden" ~/if` class="fl wid10p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>

			<div class="fl wid80p txtc color5  fontthin f19" id="totalCountId">~$heading`</div>
			<div class="fr wid10p">
			~if !$dontShowSorting`
				<a href="javascript:void(0)" id="sortByDateRelDiv"><i class="mainsp doublearw"></i></a>
			~/if`
			~if $showClose`
				<a href="javascript:void(0)" id="closeButton"><i class="mainsp comH_close posabs comH_pos1"></i></a>
			~/if`
			</div>
			<div class="clr"></div>
		</div>
	</div>
	</div>
	</div>
		~include_component('static', 'newMobileSiteHamburger')`	
	</div>
<script>
var InterestSentMessage = '~$InterestSentMessage`';
if(InterestSentMessage==1)
	ShowTopDownError(["Interest sent to ~$InterestSentToUsername`"],3000);
/** list all global Variables here */
var isLoading = false; // isLoading is a useful flag to make sure we don't send off more than one request at a time 
var _SEARCH_RESULTS_PER_PAGE = ~$_SEARCH_RESULTS_PER_PAGE`;
var minPage = 0, $div = $("#searchHeader"), message, ToshowOrNotRelaxCriteria = 0, viewSimilar = 1, viewedProfilechecksum = '~$viewedProfilechecksum`';
var firstResponse = ~$firstResponse|decodevar`;
var stypeKey = '~$stypeName`', searchSort = firstResponse.sortType, searchResultsPostParams = '', SITE_URL = "~$SITE_URL`", NAVIGATOR = "~$NAVIGATOR`", ecpBackLocation = "~$BREADCRUMB|decodevar`", showECPPage ="&toShowECP=1", historyBackStop="~$historyBackStop`";
getEcpBackLocation = function()
{
  return ecpBackLocation;
}

initEcpBackBtn = function()
{
  if(typeof getEcpBackLocation != 'function')
      return ;

  var backBtnHtml = getEcpBackLocation();

  if(backBtnHtml && backBtnHtml.length && backBtnHtml.indexOf('href') !=-1)
  {
    var dummy = document.createElement('div');
    dummy.innerHTML = backBtnHtml;
    backBtnAnchor = $(dummy).find('a').attr('href');
    backLocation = backBtnAnchor;
    var closeBtn = document.getElementById('closeButton');
    closeBtn.href = backLocation+"&fmConfirm=1";
    return backLocation;
  }
  else//History Back
  {
    var closeBtn = document.getElementById('closeButton');
	if(historyBackStop=="1")
            closeBtn.href = '/myjs/jsmsPerform';
        else
            closeBtn.href = 'javascript:history.back()';

  }
}

/*function onBackBtnECPPage()
{
  if (window.location.href.indexOf('search/MobSimilarProfiles')!=-1){
      var closeBtn = document.getElementById('closeButton');
      if(closeBtn.href.length>1){
        window.location.href= closeBtn.href;
      }
      else{
        history.back();
      }  
    
      return true;
  }
  return false;
}
*/
$(document).ready(function() {
    var backLocation = initEcpBackBtn();
    /*if(typeof historyStoreObj == "object"){
         historyStoreObj.push(onBackBtnECPPage,"#similarPage");
    }*/  
});


</script>
