function clearOverlay() {
    $("#callOvrTwo").hide();
    $("#callOvrOne").hide();
    $("#callOvrTwoJS").hide();
    $("#callOvrOneJS").hide();
    $("#removeOverlay").hide();
    $("#changeWithCouponOverlay").hide();
    $('html, body, #DivOuter').css({
        'overflow': 'auto',
        'height': 'auto'
    });
    $("#callButton").show();
    e.preventDefault();
}

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function setRemoveOverlayHeight() {
    var vwid = $(window).width();
    var vhgt = $(window).height();
    hgt = vhgt + "px";
    //$('#removeOverlay').css( "height", hgt );
    var n_wid = vwid - 20;
    var n_hgt = vhgt - 20;
    $('#ContLayer').css({
        "width": n_wid,
        "height": n_hgt
    });
}

function setCouponOverlayHeight() {
    var vwid = $(window).width();
    var vhgt = $(window).height();
    hgt = vhgt + "px";
    //$('#removeOverlay').css( "height", hgt );
    var n_wid = vwid - 20;
    var n_hgt = vhgt - 20;
    $('#ContLayerCoup').css({
        "width": n_wid,
        "height": n_hgt
    });
}

function checkEmptyOrNull(item) {
    if (item != undefined && item != null && item != "") {
        return true;
    } else {
        return false;
    }
}

function changeMemCookie(mainMem, mainMemDur) {
    if (checkEmptyOrNull(readCookie('mainMem'))) {
        // case when some other main membership is selected
        if (readCookie('mainMem') != mainMem) {
            createCookie('mainMem', mainMem, 0);
            createCookie('mainMemDur', mainMemDur, 0);
        } else {
            // case when another duration in same membership is selected
            if (checkEmptyOrNull(readCookie('mainMemDur')) && readCookie('mainMemDur') != mainMemDur) {
                createCookie('mainMem', mainMem, 0);
                createCookie('mainMemDur', mainMemDur, 0);
            } else {
                if(readCookie('backState') != "changePlan"){
                    // when same duration is selected again
                    eraseCookie('mainMem');
                    eraseCookie('mainMemDur');
                }
            }
        }
    } else {
        // first selection
        createCookie('mainMem', mainMem, 0);
        createCookie('mainMemDur', mainMemDur, 0);
    }
}

function trackVasCookie(vasKey, vasId) {
    if (readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))) {
        var currentVas = readCookie('selectedVas');
        if (currentVas.indexOf(",") > -1) {
            // case when more than one vas was selected
            var tempArr = currentVas.split(",");
        } else {
            // case when only one vas was selected
            var tempArr = [currentVas];
        }
        //console.log(tempArr);
        if (tempArr.length > 0) {
            // remove all other vas which start with supplied character except currently selected
            tempArr.forEach(function(item, index) {
                if (item.substring(0, 1) == vasKey && item != vasId) {
                    tempArr.splice(index, 1);
                }
            });
        }
        // check if currently selected vas exists, if yes, remove, else add to stack
        if (currentVas.indexOf(vasId) > -1) {
            var ind = tempArr.indexOf(vasId);
            tempArr.splice(ind, 1);
        } else {
            tempArr.push(vasId);
        }
        currentVas = tempArr.join(",");
        createCookie('selectedVas', currentVas, 0);
    } else {
        if(checkEmptyOrNull(readCookie('device'))){
          if ($("#" + vasId).hasClass(readCookie('device')+'_vassel')) {
              // default case when no vas was selected
              createCookie('selectedVas', vasId);
          } else {
              eraseCookie('selectedVas');
          }
        } else {
          if ($("#" + vasId).hasClass('vassel')) {
              // default case when no vas was selected
              createCookie('selectedVas', vasId);
          } else {
              eraseCookie('selectedVas');
          }
        }
    }
}

function removeFromVas(vasId) {
    if (readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))) {
        var currentVas = readCookie('selectedVas');
        if (currentVas.indexOf(",") > -1) {
            // case when more than one vas was selected
            var tempArr = currentVas.split(",");
        } else {
            // case when only one vas was selected
            var tempArr = [currentVas];
        }
        //console.log(tempArr);
        if (tempArr.length > 0) {
            // remove passed vasId
            tempArr.forEach(function(item, index) {
                if (item == vasId) {
                    tempArr.splice(index, 1);
                }
            });
        }
        currentVas = tempArr.join(",");
        if (checkEmptyOrNull(currentVas)) {
            createCookie('selectedVas', currentVas, 0);
        } else {
            eraseCookie('selectedVas');
        }
    } else {
        eraseCookie('selectedVas');
    }
}

function callRedirectManager() {
    var paramStr = "";
    if (readCookie('backState') != "changePlan") {
        if (checkEmptyOrNull(readCookie("mainMem")) && checkEmptyOrNull(readCookie("mainMemDur"))) {
            if (checkEmptyOrNull(readCookie('selectedVas'))) {
                paramStr = "displayPage=3&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur") + "&selectedVas=" + readCookie('selectedVas');
            } else {
                paramStr = "displayPage=3&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur") + "&selectedVas=";
            }
        } else {
            if (checkEmptyOrNull(readCookie('selectedVas'))) {
                paramStr = "displayPage=3&selectedVas=" + readCookie('selectedVas');
            } else {
                paramStr = "displayPage=1";
            }
        }
    } else if (checkEmptyOrNull(readCookie("mainMem")) && checkEmptyOrNull(readCookie("mainMemDur"))) {
        if (readCookie("mainMem") == "ESP" || readCookie("mainMem") == "X") {
            paramStr = "displayPage=3&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur");
        } else {
            if (checkEmptyOrNull(readCookie('selectedVas'))) {
                paramStr = "displayPage=3&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur") + "&selectedVas=" + readCookie('selectedVas');
            } else {
                paramStr = "displayPage=3&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur") + "&selectedVas=";
            }
        }
    } else {
        e.preventDefault();
        return;
    }
    if(checkEmptyOrNull(readCookie('device'))){
      paramStr += '&device=' + readCookie('device');
      window.history.pushState("newBack", "Jeevansathi Membership", "/membership/jsms?" + "displayPage=2&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur") + "&device=" + readCookie('device'));
    } else {
      window.history.pushState("newBack", "Jeevansathi Membership", "/membership/jsms?" + "displayPage=2&mainMem=" + readCookie("mainMem") + "&mainMemDur=" + readCookie("mainMemDur"));
    }
    eraseCookie('backState');
    url = "/membership/jsms?" + paramStr;
    window.location.href = url;
}

function autoPopupFreshdesk(username, email){
  var len = $("#lc_chat_layout").length;
  if(len){
    $("#lc_chat_layout").click();
    if($("#lc_chat_layout input[id*='name']").length){
      $("#lc_chat_layout input[id*='name']").val(username);
    }
    if($("#lc_chat_layout input[id*='email']").length){
      $("#lc_chat_layout input[id*='email']").val(email); 
    }
    $("#lc_chat_header").click();
  }
}

function autoPopulateFreshdeskDetails(username, email){
  if($("#lc_chat_layout input[id*='name']").length){
    var checkName = $("#lc_chat_layout input[id*='name']").val();
    if(checkName != ''){
      $("#lc_chat_layout input[id*='name']").val(username);
    }
  }
  if($("#lc_chat_layout input[id*='email']").length){
    var checkEmail = $("#lc_chat_layout input[id*='email']").val(); 
    if(checkEmail != ''){
      $("#lc_chat_layout input[id*='email']").val(email); 
    }
  }
}

function updateSelectedVas(action)
{
    var currentVas = readCookie('selectedVas');
    if(currentVas.indexOf(",") > -1){
        // case when more than one vas was selected
        var tempArr = currentVas.split(",");
    } else {
        // case when only one vas was selected
        var tempArr = [currentVas];
    }
    var memBasedFilteredVas = JSON.parse(filteredVasServices.replace(/&quot;/g,'"'));
    var newVasArr = [];
    var mainMem = readCookie('mainMem');

    if(tempArr.length > 0)
    {
        // remove all other vas which start with supplied character except currently selected
        $.each(tempArr, function(index, item){
            var vasKey = item.substring(0, 1);

            if(memBasedFilteredVas[mainMem]=== "undefined" || $.inArray(vasKey,memBasedFilteredVas[mainMem])===-1)
            {
                newVasArr.push(item);
            }
            if(index == 0)
            {
                if(index == 0){
                    $("body").find("#"+item).parent().parent().addClass("scrollTo");
                }
            }
            if(checkEmptyOrNull(readCookie('device'))){
                $("#"+item).addClass(readCookie('device')+'_vassel');   
            } else {
                $("#"+item).addClass('vassel'); 
            }
        });
        $('html, body').animate({
            scrollTop: 0
        }, 0); 
        currentVas = newVasArr.join(",");
        createCookie('selectedVas',currentVas,0);
        if(action=="jsmsLandingPage") 
            $("#continueBtn").show();
        else if(action=="jsmsVasPage")
            $("#nextButton").text('Cart');
    }
}
