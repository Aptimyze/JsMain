/**
* Action to be taken when we clicked on back to search results
* 1. checks if ist a back button, then it call request for specific page and load that item
*/
//window.addEventListener("load", function(){
$(document).ready(function(){

	$('body').on('click touchstart', '#overLayerLoader', function()
	{
		searchSortLayer('Hide');
	});

	$('body').on('click', '#sortByDateRelDiv', function()
	{
		searchSortLayer('toggle');
	});

	/** 
	* Trigger next results set when we click on loader.
	*/
	$('body').on('click touchstart', '.loaderBottomDiv', function()
	{
        	triggerLoader('Next');
		return false;
	});

	/** 
	* Trigger next results set when we click on loader.
	*/
	$('body').on('click touchstart', '.loaderTopDiv', function()
	{
        	triggerLoader('Prev');
		return false;
	});


	/** 
	* The header that is added at the top will now become layer at the top tuple 
	*/
	$(window).scroll(function(event)
	{
	
	        //if(firstResponse.no_of_results<=1)
        	        //return;
		fixDiv();
        	showHideSearchHeader();
	        triggerLoader();
		//addloaderTop();
	});

	if(ifBackToSearchResults())
	{	
		//showLoaderToScreen();
		isLoading = false;
		
		var idToLoad  = $.urlParam('page');
		var loadPageToLoadId =1;
		// urlParam returns integer only if second param si given so for featured profile we need complete idToLoad
		if(idToLoad!='iddf1')
		{
			idToLoad  = $.urlParam('page',1);
			loadPageToLoadId = Math.ceil(idToLoad/_SEARCH_RESULTS_PER_PAGE); 
		}
		
		if($("#" + idToLoad).length == 0)
		{
			if(!isLoading)
			{
				triggerLoader('',loadPageToLoadId,idToLoad);
				minPage = loadPageToLoadId;
				isLoading = true;
				loadImageId =  $.urlParam('page');
				// commented by Reshu for featured profile( idToLoad%_SEARCH_RESULTS_PER_PAGE==1)) now needed for FP so that header doesnot hide photo part
				if(idToLoad=='iddf1') 
						loadPrevTuple = 1; // loads previous page as well
			}
		}
	}
	else
	{
		minPage=0;
		//showLoaderToScreen();
		if(firstResponse.no_of_results>0)
		{
			$(loaderBottomDiv).appendTo("#sContainer");
			isLoading = true;
			dataForSearchTuple(firstResponse);
			loadNextImages();
		}
		else
		{
			addNoResDivs(firstResponse.noresultmessage,"#sContainer","#searchHeader");
		}
	}
});
