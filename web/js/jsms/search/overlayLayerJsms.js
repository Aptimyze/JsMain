// function for opening overlay layer
function openOverlayLayer(topBarTitle,bodyContent,buttonContent){
    var relaxTapoverLay = "<div id= 'TapOverLayLayer' class='tapoverlay posfix'> </div>";
    $("body").prepend(relaxTapoverLay);
    $("#searchHeader").slideUp();
    $("#sContainer").css("position", "fixed");
    $("#sContainer").css("overflow","hidden");
    $("#sContainer").css("display","block");
    $(".tapoverlay").css("opacity","0.95");
    $(".tapoverlay").css("z-index","107");
    if(typeof topBarTitle!="undefined" && topBarTitle!="")
        topBarOnLayer(topBarTitle);
    if(typeof bodyContent!="undefined" && bodyContent!="")
        bodyContentLayer(bodyContent);
    if(typeof buttonContent!="undefined" && buttonContent!="")
        buttonOnLayer(buttonContent);
    if(window.location.hash.length===0)
        historyStoreObj.push(browserBackSaveOverlay,"#saveLayer"); 
    var heightForScroll = $(window).height()-$("#saveSearchSubmit").height()-$(".sav-head").height();
    $("#layerBodyFrame").css("height",heightForScroll);
}

// function for body of overlay layer closing
function closeOpenLayer(){
    if($("#TapOverLayLayer").is(':visible')) {
        popBrowserStack();
    }
}

// function to hide when closing overlay
function hideForClose(){
    $("#TapOverLayLayer").remove();
    $("#saveSearchSubmit").remove();
    $("#sContainer").css("position", "relative");
    $("#sContainer").css("overflow","auto");
    $("#searchHeader").show();
    if($(".undoButton:visible").length>1)
        saveSearch();
    if(typeof manage !== 'undefined' && manage==1 && typeof crossSaveSearch !== 'undefined' && crossSaveSearch==2 ){
      window.location.reload();
    }
    enable_scrolling();
}

// function for top bar of overlay layer
function topBarOnLayer(topBarTitle){
    var topBar = '<div class="pad18 txtc sav-head sacsrc-brdr1"><div class="posrel"><div class="white fontthin f19">'+topBarTitle+'</div><div class="posabs savsrc-pos4"><i id="closeSaveSearchOverlay" class="mainsp savsrc-icon3"></i></div></div></div>';
    $("#TapOverLayLayer").append(topBar);
 
}
$("body").on("click touchstart","#closeSaveSearchOverlay",function() {
	crossSaveSearch=1;
	closeOpenLayer();
        return false;
});

// function for body of overlay layer
function bodyContentLayer(bodyContent){
    var bodyFrame = '<div style="overflow-y:scroll;" id="layerBodyFrame"></div>';
    $("#TapOverLayLayer").append(bodyFrame);
    $("#layerBodyFrame").append(bodyContent);
}

// function for button on overlay layer
function buttonOnLayer(buttonContent){
    var buttonInsert = '<div style="position: absolute; overflow: hidden;bottom: 0px;height: 52px;width: 100%;" class="btmo border0"><button class="savsrc-button fontlig f16 white txtc bg7 lh50 posfix btmo border0 fullwid pinkRipple" id="saveSearchSubmit" onclick="'+buttonContent.action+'" style="z-index:110">'+buttonContent.title+'</button></div>';
    $(buttonInsert).insertAfter("#TapOverLayLayer");
}

// function for backbutton functionality
browserBackSaveOverlay = function(){
    if($("#TapOverLayLayer").is(':visible')) {
            hideForClose();
            return true;
    } else {
            return false;
    }
}
