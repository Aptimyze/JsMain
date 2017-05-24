
var relaxMessage;

$(document).ready(function(){
   
    var relaxedMsg = response.relaxation;
    if(response.no_of_results>0)
    {
	    if(relaxedMsg!=null){
		var relaxationMain = response.relaxationHead.main;
		var relaxationText = response.relaxationHead.text;

		if(response.relaxationType=="auto")
		    var buttonLabel = "Revert Relaxed Criteria";
		else
		    var buttonLabel = "Relax My Criteria";

		relaxMessage =  "<div class='pt24 mainwid container' >\
		    <div class='srppad20 bg-white'>\
			    <div class='fontlig txtc'>\
			    <div class='colr5 f26 pt5'>"+relaxationMain+"</div>\
			    <div class='pt30 colr2 f15'>"+relaxationText+"</div>\
			    <div class='pt5 colr2 f15'>"+relaxedMsg+"</div>\
			</div>   \
			<div class='mauto mt15 wid200' onclick=RelaxCriteria('"+response.relaxationType+"')>\
			    <button class='fontlig f17 colrw bg5 fullwid txtc lh40 brdr-0 cursp'>"+buttonLabel+"</button>\
			</div>\
		    </div>\
		</div>";  

		showRelaxCriteria();
	    }
    }
});
 
function showRelaxCriteria(){
    	$("#relaxationBox").html(relaxMessage);
        $("#relaxationBox").slideDown();
}

function removeRelaxCriteria(){
	$("#relaxationBox").hide();
}

function RelaxCriteria(whichCase){
	if(whichCase=="auto")
		postParams = "noRelaxation=1";
	else
		postParams = "addEthnicities=1";
	var postParams;
	var infoArr = {};
	infoArr["action"] = "stayOnPage";
        //$("#relaxationBox").slideUp("normal",function(){alert("*");sendProcessSearchRequest(postParams,infoArr);});
        removeRelaxCriteria();
	sendProcessSearchRequest(postParams,infoArr);
}
