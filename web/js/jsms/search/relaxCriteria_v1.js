var relaxTapoverLay = "<div id= 'relaxCriteriaTapOverLay' class='tapoverlay posfix'> </div>";

if (firstResponse.relaxation)
{
    var relaxationArr = firstResponse.relaxation;
    var relaxationHead = firstResponse.relaxationHead;
    var relaxType = firstResponse.relaxationType;
    var ToshowOrNotRelaxCriteria = 1;
}
$(document).ready(function(){
message = "<div id= 'relaxCriteriaContent' class='srpoverlay_2 top_r1 white'><div class='txtc'><i class='mainsp relax_icon'></i><div class='f18 fontthin opa80 pt10 lh25'>"+relaxationHead+"</div></div><div class='fullwid pad18 txtc f14 opa80 fontlig relaxDetail' style='overflow:auto;height:100px;'>";
		var relaxIndex=0;
		if(relaxationArr){
		$.each(relaxationArr, function( relaxKey, relaxVal ) {  
			message+="<div class='pb10'><div class='lh30'>"+relaxKey+"</div><div>"+relaxVal+"</div></div>";
			
		});
		message+="</div><div class='posfix btmo fullwid'><div class='fullwid txtc pb10' style='background:black'><a class='white' onclick=continueWithSearch('"+relaxType+"') href='javascript:void(0);'>Continue With My Criteria</a></div><a href='javascript:void(0);' onclick=RelaxCriteria('"+relaxType+"')  class='dispbl bg7 white txtc f16 pad2 fontlig'>Relax My Criteria</a></div>";
		if(ToshowOrNotRelaxCriteria==1)
			showRelaxCriteria();
		}
});

function showRelaxCriteria(){
	$("body").prepend(relaxTapoverLay);
	$(message).insertAfter("#relaxCriteriaTapOverLay");
	var contentHeight = ($(window).height()-$("#relaxCriteriaContent .btmo").height()-$("#relaxCriteriaContent .txtc").height()-50)+'px';
	$("#relaxCriteriaContent .relaxDetail").css("height",contentHeight);
	$("#relaxCriteriaContent").css("position","fixed");
	$("#idd1").css("margin-top","0px");
	$("#sContainer").css("position", "fixed");
	$("#sContainer").css("overflow","hidden");
	$("#sContainer").css("display","block");
	$(".tapoverlay").css("opacity","0.95");
	
}

function removeRelaxCriteria(){
	$("#relaxCriteriaContent").remove();
	enable_scrolling();
	$("#idd1").css("margin-top","48px");
	$("#sContainer").css("position", "relative");
	$("#sContainer").css("overflow","auto");
	$("#relaxCriteriaTapOverLay").remove();
	$("#searchHeader").show();
}

function continueWithSearch(whichCase){
    if(whichCase=="auto")
        window.location=SITE_URL+"/search/perform?searchId="+firstResponse.searchid+"&noRelaxation=1";
    else
        removeRelaxCriteria();
}
function RelaxCriteria(whichCase){
    if(whichCase=="auto")
        removeRelaxCriteria();
    else
        window.location=SITE_URL+"/search/perform?searchId="+firstResponse.searchid+"&addEthnicities=1";
}
