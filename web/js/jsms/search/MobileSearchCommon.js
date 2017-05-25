
/**
* This will (add+show)/hide/ search sort layer.
* @param action : action againts sorting layer : toggle/show/hide.
*/
function searchSortLayer(action)
{
	if(action=='toggle')
	{
        	if($("#sortByDateRel").length == 0)
			action='Show';
		else
			action='Hide';
			
	}
	if(action=='Show')
	{
		var msg = sortByDateRelDiv(); 
		$(msg).appendTo("#searchHeader");
		disable_scrolling();

		$("#sortByDateRel" ).slideDown( "fast", function() {
			blurring_image("show");
		});
	}
	else if(action=='Hide')
	{
		if($("#sortByDateRel").length > 0){
			$( "#sortByDateRel" ).slideUp( "fast", function() {
				$("#sortByDateRel").remove();
				$("#overLayerLoader").remove(); 
				enable_scrolling();
				blurring_image("hide");
			});
		}
	}
	event.preventDefault();
}

function blurring_image(showBlur){
/** remove blurring effect from active,active-1,active+1 tuple*/
		var IdInView = parseInt(getInViewId().replace(/[^-\d\.]/g, ''));
		if(showBlur=="show")
			$("#idd"+IdInView+" img").addClass("blurred");
		else
			$("#idd"+IdInView+" img").removeClass("blurred");
		IdInView+=1;
		if(showBlur=="show")
			$("#idd"+IdInView+" img").addClass("blurred");
		else
			$("#idd"+IdInView+" img").removeClass("blurred");
		IdInView-=2;
		if(showBlur=="show")
			$("#idd"+IdInView+" img").addClass("blurred");
		else
			$("#idd"+IdInView+" img").removeClass("blurred");
}

/**
* html content for sort by date / relevance layer.
<div class="posabs tapoverlay" style="z-index:150;"></div>';
*/
function sortByDateRelDiv()
{
	var sbPar = removeNull(firstResponse.searchBasedParam);
	if(searchSort=='Date')
	{
       		var hrefs=
'<span class="wid49p txtc fl brdr7 pad2 dispbl"><i class="mainsp srp_srtdateact"></i><div class="color2 f16">Sort by Date</div></span>\
<a href="/search/perform/?sort_logic=T&searchId='+firstResponse.searchid+'&searchBasedParam='+sbPar+'" class="wid49p txtc fl pad2 dispbl"><i class="mainsp srp_srtrelinact"></i><div class="color1 f16">Sort By Relevence</div></a>';
	}
	else
	{
		var hrefs=
'<a href="/search/perform/?sort_logic=O&searchId='+firstResponse.searchid+'&searchBasedParam='+sbPar+'" class="wid49p txtc fl brdr7 pad2 dispbl"><i class="mainsp srp_srtdateinact"></i><div class="color1 f16">Sort by Date</div></a>\
<span class="wid49p txtc fl pad2 dispbl"><i class="mainsp srp_srtrelact"></i><div class="color2 f16">Sort By Relevance</div></span>';
	}

	var msg =
	'<div class="posabs sortby fullwid srp_zindex bg4 fontlig" style="z-index:200;display:none;" id="sortByDateRel">\
		'+hrefs+'\
		<div class="clr"></div>\
	</div>\
	<div class="srp_overlay posabs" style="z-index:100;height:1400px;" id="overLayerLoader"></div>'

	return msg;
}
