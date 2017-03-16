function initializeJSMSUpgradePage(){
    eraseCookie('mainMem');
    eraseCookie('mainMemDur');
    //eraseCookie('selectedVas');
    var headerHeight = $("#jsmsLandingPageHeader").height();
    var totalHeight = $(window).height();
    var contentHeight = $("#jsmsLandingContent").height();
    
    if(contentHeight < totalHeight){
    	$("#jsmsLandingContent").height(totalHeight-headerHeight);
    }
}