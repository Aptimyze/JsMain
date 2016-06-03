
function setWidth(currentState)
{
    var totalWidth = 0;
    $("#ulAllCities"+currentState+" li").each(function(){
        var w = (this).getBoundingClientRect().width;
        totalWidth += w;
    });
    totalWidth = Math.ceil(totalWidth);
    $("#ulAllCities"+currentState).css('width',totalWidth);
    $(".ulAllCities").css("left",0).css('height',63);
    var ulWidth = $("#ulAllCities"+currentState).width();
    if(ulWidth < 480){
        $("#prevButton ,#nextButton").addClass("disp-none");
    }
    else{
        $("#prevButton ,#nextButton").removeClass("disp-none");
    }
}

function initializeHorizontalTab()
{
    if(prevStateTab){
        $("#wrap"+prevStateTab).addClass("disp-none");
        $("#scrollbar"+prevStateTab).addClass("disp-none");
        $("#parentDiv"+prevStateTab).addClass("disp-none");
        $("#all"+prevStateTab).addClass("disp-none");
        $("#li_"+prevStateTab).removeClass("active disabledTab").addClass("cursp");
        $("#p_"+prevStateTab).addClass("disp-none");
        $("[stateIdCenters='"+prevStateTab+"']").each(function(index){
            $(this).addClass("disp-none");
        });
        $("[stateIdTopRow='"+prevStateTab+"']").each(function(index){
            $(this).addClass("disp-none");
        });
        $("#allCities"+prevStateTab).addClass("disp-none").removeClass("active disabledTab");
    }
    $("#p_"+currentState).removeClass("disp-none");
    $("#li_"+currentState).addClass("active disabledTab").removeClass("cursp");
    $("[stateIdCenters='"+currentState+"']").each(function(index){
        $(this).removeClass("disp-none");
    });
    $("#wrap"+currentState).removeClass("disp-none");
    $("#scrollbar"+currentState).removeClass("disp-none");
    $("#parentDiv"+currentState).removeClass("disp-none");
    $("#all"+currentState).removeClass("disp-none");
    $("[stateIdTopRow='"+currentState+"']").each(function(index){
        $(this).removeClass("disp-none");
    });
    $("#allCities"+currentState).removeClass("disp-none cursp").addClass("active disabledTab");
    
}

function setHeightLeftRightPanel(){
    var cityContainer = 0;
    $("[stateIdCenters='"+currentState+"']").each(function(index){
        cityContainer += $(this).outerHeight();
    })
    cityContainer +=45;
    if(cityContainer < defaultVerticalStateListingHeight){
        $("#leftStateDiv").height(defaultVerticalStateListingHeight);
        $("#rightCityDiv").height(defaultVerticalStateListingHeight);
    }
    else{
        $("#leftStateDiv").height(cityContainer+100);
        $("#rightCityDiv").height(cityContainer+100);
    }
}

function setHorizontalTabs(curTab)
{
    currentState = $(curTab).attr("stateIdVertical");
    if(previousCityTab){
        $("[cityIdTopRow='"+previousCityTab+"']").removeClass("disabledTab active");
    }
    initializeHorizontalTab();
    setWidth(currentState);
    $("#prevButton").addClass("disp-none");
    setHeightLeftRightPanel();
    prevStateTab = currentState;
}

function setVerticalCenters(curTab)
{
    if(previousCityTab){
        $("[cityIdCenters='"+previousCityTab+"']").addClass("disp-none");
        $("[cityIdTopRow='"+previousCityTab+"']").removeClass("disabledTab active");
        $("#hl"+previousCityTab).addClass("disp-none");
    }
    currentCityTab = $(curTab).attr("cityIdTopRow");
    previousCityTab = currentCityTab;
    $("[stateIdCenters='"+currentState+"']").each(function(index){
        $(this).addClass("disp-none");
    });
    $(curTab).addClass("disabledTab active");
    $("[cityIdCenters='"+currentCityTab+"']").removeClass("disp-none");
    $("#hl"+currentCityTab).removeClass("disp-none");
    $("#allCities"+currentState).addClass("cursp").removeClass("active disabledTab");
    var liLeft = $(curTab).position().left;
    var ulWidth = $("#ulAllCities"+currentState).width();
    $("#leftStateDiv").height(defaultVerticalStateListingHeight);
    $("#rightCityDiv").height(defaultVerticalStateListingHeight);
    
    // To focus the selected city tab and move to the starting of ul
//   if(ulWidth > 480){
//        $("#ulAllCities"+currentState).animate({left: 0-liLeft },"slow",function(){
//            setNextPrevButtons(ulWidth);
//        });
//    }


}


function moveSlider(btn)
{
    var displayWidth = 480;
    var ulWidth = $("#ulAllCities"+currentState).width();
    var currentLeftPosition = $("#ulAllCities"+currentState).position().left;
    if(ulWidth > displayWidth){
        var btnId = $(btn).attr("id");
        if(btnId == "nextButton"){
            var moveBy = ulWidth - Math.abs(currentLeftPosition);
            if(moveBy >= (2*displayWidth)){
                moveBy = displayWidth;
            }
            else if(moveBy > displayWidth && moveBy < (2*displayWidth)){
                moveBy = moveBy - displayWidth;
            }
            else{
                moveBy = 0;
            }
            currentLeftPosition -=moveBy;
        }
        else{
            var moveBy = currentLeftPosition +displayWidth;
            if (moveBy > 0){
                moveBy = 0;
            }
            currentLeftPosition = moveBy;
        }
        $("#ulAllCities"+currentState).animate({left: currentLeftPosition},500,function(){
            setNextPrevButtons(ulWidth);
        });
    }
}

function setNextPrevButtons(ulWidth){
    var currentLeftPosition = $("#ulAllCities"+currentState).position().left;
    var checkRight = ulWidth - Math.abs(currentLeftPosition);
    if(checkRight > 480){
        $("#nextButton").removeClass("disp-none");
    }
    else{
        $("#nextButton").addClass("disp-none");
    }
    if(currentLeftPosition >= 0){
        $("#prevButton").addClass("disp-none");
    }
    else{
        $("#prevButton").removeClass("disp-none");
    }
}