
$( document ).ready(function(){
	if(typeof(googleAnalyticsOff)=="undefined")
		var gnbHeaderON=1;
	else
		var gnbHeaderON=0;
if(gnbHeaderON==1){
$('.gnbHeader').bind("mousedown",function(event) {
	category="GlobalNavigationHeader";
	switch(event.which){
        case 1:
			var target = event.target || event.srcElement;
			var id = target.id;
            trackJsEventGA(category, "leftClick", id);
        break;
        case 2:
        break;
        case 3:
			var target = event.target || event.srcElement;
			var id = target.id;
            trackJsEventGA(category, "rightClick", id);
        break;        
        default:
			return;
        break;
            return;
    }
	});
}
});
