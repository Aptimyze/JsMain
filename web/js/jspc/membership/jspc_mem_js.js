function dropdown() {
    $(".dropdown dt").click(function () {
        $(".dropdown dd ul").toggle();
    });
    $(".dropdown dd ul li").click(function () {
        var text = $(this).html();
        $(".dropdown dt span").html(text);
        $(".dropdown dd ul").hide();
        $("#result").html("Selected value is: " + getSelectedValue("sample"));
    });

    function getSelectedValue(id) {
        return $("#" + id).find("dt span.value").html();
    }
    $(document).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
    });
}

function jsMemExpandAnimate(closeView) {
    $('.js-expand').animate({
        height: "toggle"
    }, 1000, function () {
        changeclass();
        if (closeView == true) $('.js-closeview ').css('display', 'block');
    });
}

function changeclass() {
    $("#js-panelbtn").toggleClass("mem-down");
}

function changeTabContent(param1, param2, timeout) {
    var contWidth = -($('.js-' + param1)[0].getBoundingClientRect().width);
    contWidth = param2 * contWidth;
    $('.mem-wid12t').animate({
        "left": contWidth
    }, timeout);
    $(".planfeat").each(function () {
        $(this).css('display', 'none');
    });
    $('.list-' + param1).slideDown(timeout);
    var currentTab = $('.planlist ul.tabs').find('li.active').attr('mainMemTab');
    var selectedMem = $('#tab_' + currentTab).find('.plansel');
    var m = $(selectedMem).attr('mainMem'),
        d = $(selectedMem).attr('mainMemDur');
    c = $(selectedMem).attr('mainMemContact');
    managePriceStrike(m, d);
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
    var nameEQ = escape(name) + "=",
        ca = document.cookie.split(';');
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

function changeMemCookie(mainMem, mainMemDur, mainMemContact) {
    if (checkEmptyOrNull(readCookie('mainMem'))) {
        // case when some other main membership is selected
        if (readCookie('mainMem') != mainMem) {
            createCookie('mainMem', mainMem, 0);
            createCookie('mainMemDur', mainMemDur, 0);
            createCookie('mainMemContact', mainMemContact, 0);
        } else {
            // case when another duration in same membership is selected
            if (checkEmptyOrNull(readCookie('mainMemDur')) && readCookie('mainMemDur') != mainMemDur) {
                createCookie('mainMem', mainMem, 0);
                createCookie('mainMemDur', mainMemDur, 0);
                createCookie('mainMemContact', mainMemContact, 0);
            } else {
                // when same duration is selected again
                eraseCookie('mainMem');
                eraseCookie('mainMemDur');
                eraseCookie('mainMemContact');
            }
        }
    } else {
        // first selection
        createCookie('mainMem', mainMem, 0);
        createCookie('mainMemTab', mainMem, 0);
        createCookie('mainMemDur', mainMemDur, 0);
        createCookie('mainMemContact', mainMemContact, 0);
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
        if (tempArr.length > 0) {
            // remove all other vas which start with supplied character except currently selected
            tempArr.forEach(function (item, index) {
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
        if ($("#" + vasId).hasClass('mem-vas-active')) {
            // default case when no vas was selected
            createCookie('selectedVas', vasId);
        } else {
            eraseCookie('selectedVas');
        }
    }
}

function manageVasOverlay(vasKey) {
    if (readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))) {
        var currentVas = readCookie('selectedVas');
        if (currentVas.indexOf(",") > -1) {
            // case when more than one vas was selected
            var tempArr = currentVas.split(",");
        } else {
            // case when only one vas was selected
            var tempArr = [currentVas];
        }
        if (tempArr.length > 0) {
            // remove passed vasId
            tempArr.forEach(function (item, index) {
                if (item.substring(0, 1) == vasKey) {
                    if ($("#" + vasKey + "_overlay").hasClass('disp-none')) {
                        $("#" + vasKey + "_overlay").removeClass('disp-none');
                        $("#" + item + "_overlay").removeClass('disp-none');
                    } else {
                        $("#" + vasKey + "_overlay").addClass('disp-none');
                        $("#" + item + "_overlay").addClass('disp-none');
                    }
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

function updateVasPageCart() {
    $("#vasServices").empty();
    var newHTML = "";
    if (readCookie('selectedVas') && checkEmptyOrNull(readCookie('selectedVas'))) {
        var currentVas = readCookie('selectedVas');
        if (currentVas.indexOf(",") > -1) {
            // case when more than one vas was selected
            var tempArr = currentVas.split(",");
        } else {
            // case when only one vas was selected
            var tempArr = [currentVas];
        }
        if (tempArr.length > 0) {
            // remove passed vasId
            tempArr.forEach(function (item, index) {
                var vasKey = item.substring(0, 1),
                    vasId = item,
                    vasName = $("#" + vasKey + "_name").html(),
                    vasDuration = $("#" + vasId + "_duration").html(),
                    vasPrice = $("#" + vasId + "_price span.prc").html(),
                    vasPriceStrike = $("#" + vasId + "_price_strike span.prc").html();
                if (!checkEmptyOrNull(vasPriceStrike)) {
                    vasPriceStrike = '';
                }
                newHTML += "<div class='pt40'><div class='disp-tbl fullwid'><div class='disp-cell f15 fontreg wid80p pos-rel'>" + vasName + "<span vasId='" + vasId + "' vasKey='" + vasKey + "' class='removeVasBtn vsup opa60 cursp layersZ'>REMOVE</span></div><div class='vasPlanPriceStrike disp-cell f13 fontlig opa60 txtr strike wid20p'>" + vasPriceStrike + "</div></div><div class='disp-tbl fullwid'><div class='disp-cell vbtm fontlig f13'>" + vasDuration + "</div><div class='vasPlanPrice disp-cell fontreg f15 txtr'>" + vasPrice + "</div></div></div>";
            });
        }
    }
    $("#vasServices").append(newHTML);
    var mainPrice = 0,
        mainPriceStrike = 0,
        vasPriceTotal = 0,
        vasPriceStrikeTotal = 0;
    if ($("#mainPlanPrice").length > 0) {
        mainPrice = parseFloat($("#mainPlanPrice").html().replace(',', ''));
    }
    if ($("#mainPlanStrikePrice").length > 0) {
        mainPriceStrike = parseFloat($("#mainPlanStrikePrice").html().replace(',', ''));
    }
    $(".vasPlanPrice").each(function () {
        vasPriceTotal += parseFloat($(this).html().replace(',', ''));
    });
    $(".vasPlanPriceStrike").each(function () {
        vasPriceStrikeTotal += parseFloat($(this).html().replace(',', ''));
    });
    var totalPrice = mainPrice + vasPriceTotal;
    if (isNaN(vasPriceStrikeTotal)) {
        var totalPriceStrike = mainPriceStrike + vasPriceTotal - totalPrice;
    } else {
        var totalPriceStrike = mainPriceStrike + vasPriceStrikeTotal - totalPrice;
    }
    totalPrice = totalPrice.toFixed(2);
    totalPriceStrike = totalPriceStrike.toFixed(2);
    $('#savingsBlock #totalSavings').empty();
    $("#totalPrice").empty();
    if (totalPriceStrike > 0) {
        $('#savingsBlock').removeClass('disp-none');
        $('#savingsBlock #totalSavings').append(removeZeroInDecimal(commaSeparateNumber(totalPriceStrike)));
    } else {
        $('#savingsBlock').addClass('disp-none');
        $('#savingsBlock #totalSavings').empty();
    }
    if (totalPrice > 0) {
        $('#payNowBtn').removeClass('bg_greyed');
        $('#payNowBtn').addClass('bg_pink pinkRipple hoverPink cursp');
    } else {
        $('#payNowBtn').removeClass('bg_pink pinkRipple hoverPink cursp');
        $('#payNowBtn').addClass('bg_greyed');
    }
    $("#totalPrice").append(removeZeroInDecimal(commaSeparateNumber(totalPrice)));
    $(".removeVasBtn").on('click', function () {
        var vasId = $(this).attr('vasId');
        var vasKey = $(this).attr('vasKey');
        removeCommand(vasKey, vasId);
    });
}

function preSelectVas() {
    var dur = readCookie('mainMemDur');
    if (dur == 'L') {
        dur = 12;
    }
    var index = 0,
        vasDur, vasKey, newSelectedVas = new Array();
    var PSVAS = preSelectVasGlobal.split(',');
    $('#VASdiv ul li').each(function () {
        if (index < 6) {
            var loopVal = 0;
            flag = 0
            $(this).find(".vascell").each(function () {
                vasDur = $(this).attr('id');
                vasKey = vasDur.substring(0, 1);
                vasDur = vasDur.replace(/[^0-9]/g, '');
                if (inArray(vasKey, PSVAS)) {
                    if (vasKey == 'I' && flag != 1) {
                        dur = dur + '0';
                        flag = 1;
                    }
                    if (parseInt(vasDur) <= parseInt(dur)) {
                        loopVal = vasDur;
                    }
                }
            });
            if (loopVal) {
                newSelectedVas.push(vasKey + loopVal);
            }
        }
        index++;
    });
    newSelectedVas = newSelectedVas.join(",");
    if (checkEmptyOrNull(newSelectedVas)) {
        createCookie('selectedVas', newSelectedVas, 0);
    }
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}

function updateAlreadySelectedVas() {
    var currentVas = readCookie('selectedVas');
    if (currentVas.indexOf(",") > -1) {
        // case when more than one vas was selected
        var tempArr = currentVas.split(",");
    } else {
        // case when only one vas was selected
        var tempArr = [currentVas];
    }
    var mainMem = readCookie('mainMem');
    var memBasedFilteredVas = JSON.parse(filteredVasServices.replace(/&quot;/g, '"'));
    var newVasArr = [];
    if (tempArr.length > 0) {
        // remove all other vas which start with supplied character except currently selected
        tempArr.forEach(function (item, index) {
            var vasKey = item.substring(0, 1);
            //filter out vas for eAdvantage if present in selected vas
            $("#" + item).addClass('mem-vas-active');
            if ($("#" + vasKey + "_overlay").hasClass('disp-none')) {
                if (memBasedFilteredVas[mainMem] === "undefined" || $.inArray(vasKey, memBasedFilteredVas[mainMem]) === -1) {
                    newVasArr.push(item);
                }
                $("#" + vasKey + "_overlay").removeClass('disp-none');
                $("#" + item + "_overlay").removeClass('disp-none');
            } else {
                if (memBasedFilteredVas[mainMem] === "undefined" || $.inArray(vasKey, memBasedFilteredVas[mainMem]) === -1) {
                    newVasArr.push(item);
                }
                $("#" + vasKey + "_overlay").addClass('disp-none');
                $("#" + item + "_overlay").addClass('disp-none');
            }
        });
        currentVas = newVasArr.join(",");
        createCookie('selectedVas', currentVas, 0);
    }
}

function updateTimeSpan(countdown) {
    // var span1 = document.getElementById('bannerExpandedTimer');
    var span2 = document.getElementById('bannerMinimizedTimer'),
        span3 = document.getElementById('bannerTimerVas');
    var d = new Date(countdown),
        t = new Date(),
        ms, s, m, h;
    // get the difference between right now and expiry date
    ms = d - t;
    // get the days between now and then
    d = parseInt(ms / (1000 * 60 * 60 * 24));
    //ms -= (d * 1000 * 60 * 60 * 24);
    // get hours
    h = parseInt(ms / (1000 * 60 * 60));
    ms -= (h * 1000 * 60 * 60);
    // get minutes
    m = parseInt(ms / (1000 * 60));
    ms -= (m * 1000 * 60);
    // get seconds
    s = parseInt(ms / 1000);
    if (h <= 0 && m <= 0 && s <= 0) {
        // span1.innerHTML = " <span class='disp_ib f20'>00<span class='f10 pl2'>H</span></span> <span class='disp_ib f20 pl10'>00<span class='f10 pl2'>M</span></span> <span class='disp_ib f20 pl10'>00<span class='f10 pl2'>S</span></span> ";
        span2.innerHTML = "<ul class='pt5'><li>00<span>H</span></li><li>00<span>M</span></li><li>00<span>S</span></li></ul>";
        span3.innerHTML = " <span class='disp_ib f20'>00<span class='f10 pl2'>H</span></span> <span class='disp_ib f20 pl10'>00<span class='f10 pl2'>M</span></span> <span class='disp_ib f20 pl10'>00<span class='f10 pl2'>S</span></span> ";
    } else {
        h = leftPad(h, 2);
        m = leftPad(m, 2);
        s = leftPad(s, 2);
        // span1.innerHTML = " <span class='disp_ib f20'>" + h + "<span class='f10 pl2'>H</span></span> <span class='disp_ib f20 pl10'>" + m + "<span class='f10 pl2'>M</span></span> <span class='disp_ib f20 pl10'>" + s + "<span class='f10 pl2'>S</span></span> ";
        span2.innerHTML = "<ul class='pt5'><li>" + h + "<span>H</span></li><li>" + m + "<span>M</span></li><li>" + s + "<span>S</span></li></ul>";
        span3.innerHTML = " <span class='disp_ib f20'>" + h + "<span class='f10 pl2'>H</span></span> <span class='disp_ib f20 pl10'>" + m + "<span class='f10 pl2'>M</span></span> <span class='disp_ib f20 pl10'>" + s + "<span class='f10 pl2'>S</span></span> ";
        setTimeout(function () {
            updateTimeSpan(countdown)
        }, 100);
    }
}

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}

function checkEmptyOrNull(item) {
    if (item != undefined && item != null && item != "") {
        return true;
    } else {
        return false;
    }
}

function managePriceStrike(m, d) {
    vas = readCookie('selectedVas');
    var vasActualPrice = $("#" + m + vas + "_price").text().trim().replace(',', ''),
        vasStrikePrice = $("#" + m + vas + "_price_strike").text().trim().replace(',', '');
    var strikePrice = $("#" + m + d + "_price_strike").text().trim().replace(',', ''),
        actualPrice = $("#" + m + d + "_price").text().trim().replace(',', '');
/*
        console.log("vasActualPrice",vasActualPrice);
        console.log("vasStrikePrice",vasStrikePrice);
        console.log("strikePrice",strikePrice);
        console.log("actualPrice",actualPrice);
        */
    var vasDifference = (vasStrikePrice - vasActualPrice);
    var difference = (strikePrice - actualPrice);
    console.log("Difference",difference,typeof difference);
    if(vasDifference > 0)
        difference = difference + vasDifference;
    
    if(typeof vasActualPrice != undefined)
        actualPrice=+vasActualPrice + +actualPrice;
    if(typeof vasStrikePrice != undefined)
        strikePrice=+vasStrikePrice+ +strikePrice;
    if(strikePrice<=0)
        strikePrice = '';
    if (difference > 0) {
        $('.overflowPinkRipple').css('margin-top', '0px');
        $('#' + m + "_savings_container").show();
        $('#' + m + "_savings").html(removeZeroInDecimal(difference.toFixed(2)));
    } else {
        $('#' + m + "_savings_container").hide();
        $('.overflowPinkRipple').css('margin-top', '20px');
        $('#tab_X .overflowPinkRipple').css('margin-top', '20px');
    }
    if ($("#main_" + m).hasClass("active")) {
        if (profileid) {
            $(".list-main_" + m + " #finalMemTab_" + m).html($($("#main_" + m + " span")[1]).html() + ' - ');
            $(".list-main_" + m + " #finalMemDuration_" + m).html($("#" + m + d + "_duration").text() + ' for ');
            $(".list-main_" + m + " #finalMemPrice_" + m).html($("#" + m + d + "_price").html());
        } else {
            $(".list-main_" + m + " #finalMemTab_" + m).html($($("#main_" + m + " span")[1]).html() + ' starts @ ');
            $(".list-main_" + m + " #finalMemDuration_" + m).html();
            $(".list-main_" + m + " #finalMemPrice_" + m).html($("#tab_" + m + "_startingPrice").html());
        }
    }
    actualPrice = actualPrice.toFixed(2);
    actualPrice = actualPrice.toString();
    $('#' + m + "_final_price").html(removeZeroInDecimal(commaSeparateNumber(actualPrice)));
}

function initializeMembershipPage() {
    $(".planlist li").eq(0).addClass('active').trigger('click');
    $(".benefits div").eq(0).removeClass('disp-none');
    $('#sliderContainer div').find('.plansel').each(function () {
        var m = $(this).attr('mainMem'),
            d = $(this).attr('mainMemDur');
        managePriceStrike(m, d);
    });
    $('#exclusiveContainer div').find('.active').each(function () {
        var m = $(this).attr('mainMem'),
            d = $(this).attr('mainMemDur');
        managePriceStrike(m, d);
    });
    if (checkEmptyOrNull(readCookie('mainMemTab')) && readCookie('mainMem') != "X") {
        $("ul.tabs li.active").removeClass('active');
        $("ul.tabs li[mainMemTab=" + readCookie('mainMemTab') + "]").addClass('active');
        var tabNum = $("ul.tabs li.active").index(),
            getTabId = $("ul.tabs li.active").attr('id');
        changeTabContent(getTabId, tabNum, 0);
        var m = readCookie('mainMem'),
            d = readCookie('mainMemDur'),
            c = readCookie('mainMemContact');
        if (checkEmptyOrNull(m) && checkEmptyOrNull(d)) {
            $("#tab_" + m + " .plansel").removeClass('plansel');
            $("#" + m + d).addClass('plansel');
            managePriceStrike(m, d);
        }
    }
    if (readCookie('mainMem') == "X") {
        $(".jsxDur.active").removeClass('active');
        var m = readCookie('mainMem'),
            d = readCookie('mainMemDur');
        $("#" + m + d).addClass('active');
        managePriceStrike(m, d);
    }
}
//function to format numbers in display as comma seperated
function commaSeparateNumber(val) {
    val = val.replace(',', '');
    var array = val.split(''),
        index = -3;
    while (array.length + index > 0) {
        array.splice(index, 0, ',');
        index -= 4;
    }
    var finalNo = array.join('');
    finalNo = finalNo.replace(',.', '.');
    return finalNo;
};

function removeZeroInDecimal(val) {
    return val.replace(".00", "");
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function removeCommand(vasKey, vasId) {
    $("#" + vasKey + " .vascell").each(function (e) {
        if ($(this).hasClass('mem-vas-active')) {
            $(this).removeClass('mem-vas-active');
        }
    });
    manageVasOverlay(vasKey);
    trackVasCookie(vasKey, vasId);
    updateVasPageCart();
}

function bindEscapeKey() {
    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            closeAllOverlays();
        }
    });
    $(document).click(function (e) {
        if ($(e.target).attr('class') == 'overlay1') {
            closeAllOverlays();
        }
    });
}

function closeAllOverlays() {
    $(".overlay1").remove();
    $('#cmpplan').addClass('disp-none');
    $("#topNavigationBar").removeClass('pos-rel layersZ');
    $("#requestCallback").removeClass('js-reqcallbck opa50');
    $("#requestCallbackLogout,#requestCallbackLogin").hide();
    $("#js-footer").removeClass('pos-rel').removeClass('layersZ');
    $("#topNavigationBar").addClass('layersZ');
    $("#footerRequestCallback").removeClass('js-reqcallbck').removeClass('opa50');
    //$('.overlay1').remove();
    $("#footerRequestCallbackLogout,#footerRequestCallbackLogin").hide();
    $("#topNavigationBar").removeClass('pos-rel').removeClass('layersZ');
    $("#headerRequestCallback").removeClass('js-reqcallbck').removeClass('opa50');
    //$('.overlay1').remove();
    $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").hide();
}

function manageSelectedItem() {
    var paymentOption, selectedName, selectedCardType;
    $("a.accordion-section-title").each(function () {
        if ($(this).hasClass('active')) {
            paymentOption = $(this).attr('paymentSel');
            if (checkEmptyOrNull(paymentOption)) {
                selectedName = $("#accordion-" + paymentOption).find('.selectedValue').html();
                var index = 0;
                $("#accordion-" + paymentOption + " .selectListInnerWrap dd").each(function () {
                    if ($(this).html() == selectedName) {
                        if (index != 0) {
                            selectedCardType = $("#accordion-" + paymentOption + " select.custom option").eq(index).attr('cardType');
                            if (selectedCardType == 'OTHER' && paymentOption == 'NB') {
                                paymentOption = 'CR';
                                selectedCardType = 'card5';
                            }
                            createCookie("paymentMode", paymentOption);
                            createCookie("cardType", selectedCardType);
                            clearSelectedIcons(paymentOption);
                            $("#" + paymentOption + "-" + index).find('.cursp').removeClass('memn-nosel').addClass('memnp-sel');
                        } else if (index == 0) {
                            clearSelectedIcons(paymentOption);
                        }
                    } else {
                        index++;
                    }
                });
                $("#accordion-" + paymentOption + " .defaultScrollbar dd").each(function () {
                    if ($(this).html() == selectedName) {
                        if (index != 0) {
                            selectedCardType = $("#accordion-" + paymentOption + " select.custom option").eq(index).attr('cardType');
                            if (selectedCardType == 'OTHER' && paymentOption == 'NB') {
                                paymentOption = 'CR';
                                selectedCardType = 'card5';
                            }
                            createCookie("paymentMode", paymentOption);
                            createCookie("cardType", selectedCardType);
                            clearSelectedIcons(paymentOption);
                            $("#" + paymentOption + "-" + index).find('.cursp').removeClass('memn-nosel').addClass('memnp-sel');
                        } else if (index == 0) {
                            clearSelectedIcons(paymentOption);
                        }
                    } else {
                        index++;
                    }
                });
            }
        }
    });
    if (!checkEmptyOrNull(paymentOption) || !checkEmptyOrNull(selectedCardType)) {
        eraseCookie('paymentMode');
        eraseCookie('cardType');
    }
    if (checkEmptyOrNull(readCookie('paymentMode')) && checkEmptyOrNull(readCookie('cardType'))) {
        return true;
    } else {
        return false;
    }
}

function clearSelectedIcons(paymentOption) {
    $("#" + paymentOption + "-iconList li").find('.cursp').each(function () {
        $(this).removeClass('memnp-sel').addClass('memn-nosel');
    });
}

function payAtBranchesTransition() {
    currentSelectedCity = $('#city').val();
    if (currentSelectedCity == "Select City") $('#instructionsText').addClass("disp-none");
    else $('#instructionsText').removeClass("disp-none");
    $(".branch").each(function (index, element) {
        $(this).addClass("disp-none");
    });
    $(".branch").each(function (index, element) {
        if ($(this).attr("branch_id") == currentSelectedCity + "_branch") $(this).removeClass("disp-none");
    });
}

function manageCartPaymentButtonTextChange() {
    var activeFlag;
    $("#cartPaymentSpan").html("Pay Now");
    $("a.accordion-section-title").each(function () {
        if ($(this).hasClass('active')) {
            activeFlag = 1;
            if ($(this).attr('id') == 'payAtBranches') {
                disablePayNowButtonCartPage();
                eraseCookie('paymentMode');
                eraseCookie('cardType');
            } else if ($(this).attr('id') == 'cashPickUp') {
                $("#cartPaymentSpan").html("Submit");
                enablePayNowButtonCartPage();
                eraseCookie('paymentMode');
                eraseCookie('cardType');
            } else {
                if (manageSelectedItem()) {
                    enablePayNowButtonCartPage();
                } else {
                    disablePayNowButtonCartPage();
                }
            }
        }
    });
}

function disablePayNowButtonCartPage() {
    if ($('#payNowBtn').hasClass('bg_pink')) {
        $('#payNowBtn').removeClass('bg_pink pinkRipple hoverPink cursp');
        $('#payNowBtn').addClass('bg_greyed');
    }
}

function enablePayNowButtonCartPage() {
    if ($('#payNowBtn').hasClass('bg_greyed')) {
        $('#payNowBtn').addClass('bg_pink pinkRipple hoverPink cursp');
        $('#payNowBtn').removeClass('bg_greyed');
        $('#noOptionSelected').addClass('disp-none');
    }
}

function showPreferredDatesDropDown(day, month, year) {
    var select = document.getElementById("preferredDateDay");
    day.forEach(function (item, index) {
        var el = document.createElement("option");
        el.textContent = item;
        el.value = item;
        select.appendChild(el);
    });
    var select = document.getElementById("preferredDateMonth");
    var dispMonth = [];
    var monthsInOrder = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    for (var i = 0; i < monthsInOrder.length; i++) {
        if (month.indexOf(monthsInOrder[i]) > -1) {
            dispMonth.push(monthsInOrder[i]);
        }
    }
    dispMonth.forEach(function (item, index) {
        var el = document.createElement("option");
        el.textContent = item;
        el.value = item;
        select.appendChild(el);
    });
    var select = document.getElementById("preferredDateYear");
    year.forEach(function (item, index) {
        var el = document.createElement("option");
        el.textContent = item;
        el.value = item;
        select.appendChild(el);
    });
}

function close_accordion_section() {
    $('.accordion .accordion-section-title').removeClass('active');
    $('.accordion .accordion-section-content').slideUp().removeClass('open');
}

function displayChequeAmount(amount) {
    $("#sampleChequePrice").text(removeZeroInDecimal(numberWithCommas(amount)));
    $("#amountInWords").text(toWords(amount));
}

function changeHTMLAfterCoupon(priceToBeDiscouted, originalPrice, discountPercentage) {
    priceToBeDiscouted = parseFloat(priceToBeDiscouted.replace(',', ''));
    originalPrice = parseFloat(originalPrice.replace(',', ''));
    var discount = (0.01 * discountPercentage) * priceToBeDiscouted;
    var discountedPrice = priceToBeDiscouted - discount;
    discount = originalPrice - priceToBeDiscouted + discount;
    discount = discount.toFixed(2);
    originalPrice = originalPrice.toFixed(2);
    discountedPrice = discountedPrice.toFixed(2);
    var commaSeperatedDiscountedPrice = removeZeroInDecimal(numberWithCommas(discountedPrice));
    $("#savingsContainer").removeClass("disp-none");
    $("#cartDiscount").text(removeZeroInDecimal(numberWithCommas(discount)));
    $("#discountedPriceContainer").removeClass("disp-none");
    $("#undiscountedPrice").text(commaSeperatedDiscountedPrice);
    $("#discountedPrice").text(removeZeroInDecimal(numberWithCommas(originalPrice)));
    $("#finalCartPrice").text(commaSeperatedDiscountedPrice + " | ");
    $("#payAtBranchesPrice").text(commaSeperatedDiscountedPrice);
    $("#cashPickUpPrice").text(commaSeperatedDiscountedPrice);
    displayChequeAmount(commaSeperatedDiscountedPrice);
    displayChequeDate();
}

function applyCouponOnCart(couponID, paramStr, priceToBeDiscouted, originalPrice) {
    paramStr = paramStr.replace(/amp;/g, '');
    url = "/api/v3/membership/membershipDetails?" + paramStr;
    $.myObj.ajax({
        type: 'POST',
        url: url,
        success: function (data) {
            respose = data;
            if (data.success_code != 1) {
                setCouponCodeField(data.message);
                $("#couponCode").addClass("colr5");
                eraseCookie('couponID');
                couponAjaxResponse = 0;
            } else {
                createCookie('couponID', couponID);
                $("#couponSuccessDiv").addClass("disp-tbl");
                $("#applyCouponDiv").removeClass("disp-tbl");
                $("#textcoup").text("Coupon code " + couponID + " applied");
                couponAjaxResponse = 1;
                changeHTMLAfterCoupon(priceToBeDiscouted, originalPrice, data.message);
            }
        }
    });
}

function validateMobile(mobile) {
    var regExIndian = /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
    var regExIndianLandline = /^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
    var regExInternational = /^\+(?:[0-9][-. ]? ?){7,14}[0-9]$/;
    if (!regExIndian.test(mobile) && !regExInternational.test(mobile) && !regExIndianLandline.test(mobile)) return false;
    else return true;
}

function validatePhone(val) {
    var regExIndianLandline = /^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
    if (!regExIndianLandline.test(val)) return false;
    else return true;
}

function validateCashPickupForm() {
    var error = 0;
    var address = $("#chequePickUpAddress").val();
    var name = $("#chequePickUpName").val();
    var mobile = $("#chequePickUpMobile").val();
    var phone = $("#chequePickUpPhone").val();
    var city = $("#select-cashCity .selectedValue").text();
    var valMobile, valPhone;
    if (city == "" || city == "Select City") {
        $("#cityError").removeClass("disp-none");
        error = 1;
    } else {
        $("#cityError").addClass("disp-none");
    }
    if (address == "") {
        $("#addressError").removeClass("disp-none");
        error = 1;
    } else {
        $("#addressError").addClass("disp-none");
    }
    if (name == "") {
        $("#nameError").removeClass("disp-none");
        error = 1;
    } else {
        $("#nameError").addClass("disp-none");
    }
    if (mobile == "") {
        $("#mobileError").removeClass("disp-none");
        error = 1;
    } else {
        valMobile = validateMobile(mobile);
        if (!valMobile) {
            $("#mobileError").removeClass("disp-none");
            error = 1;
        } else {
            $("#mobileError").addClass("disp-none");
        }
    }
    if (phone == "") {
        $("#phoneError").addClass("disp-none");
    } else {
        valPhone = validatePhone(phone);
        if (!valPhone) {
            error = 1;
            $("#phoneError").removeClass("disp-none");
        } else {
            $("#phoneError").addClass("disp-none");
        }
    }
    if (error) return false;
    else return true;
}

function removeCoupon(e) {
    if ((e.which || e.keyCode) == 116 && pageType == 'cartPage') {
        eraseCookie('couponID');
    }
};
//$(document).bind("keydown", removeCoupon);
$(document).on("keydown", removeCoupon);

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

function setCouponCodeField(msg) {
    $("#couponCode").val(msg);
}

function setCardTypeField(cardType) {
    var cardTypeValue = '';
    switch (cardType) {
    case 'DR':
        cardTypeValue = 'Debit Card';
        break;
    case 'CR':
        cardTypeValue = 'Credit Card';
        break;
    case 'CSH':
        cardTypeValue = 'Wallet';
        break;
    case 'NB':
        cardTypeValue = 'Net Banking';
        break;
    }
    return cardTypeValue;
}

function toWords(s) {
    var th = ['', ' Thousand', ' Million', ' Billion', ' Trillion', ' Quadrillion', ' Quintillion'];
    var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'Hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    if (x != s.length) {
        var y = s.length;
        str += 'Point ';
        for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
    }
    str += 'Only ';
    if (str == 'Only ') {
        str = 'Zero Only';
    }
    return str.replace(/\s+/g, ' ');
}

function displayChequeDate() {
    var dt = new Date();
    var d = dt.getDate();
    var m = dt.getMonth();
    var y = dt.getFullYear();
    var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $("#chequeDate").text(month[m] + " " + d + "," + " " + y);
}

function checkLogoutCase(profileid) {
    var computedVasppid = CryptoJS.MD5(profileid);
    if (checkEmptyOrNull(readCookie('vasppid'))) {
        var existingVasppid = readCookie('vasppid');
        if (existingVasppid != computedVasppid) {
            eraseCookie('selectedVas');
            eraseCookie('vasppid');
            createCookie('vasppid', computedVasppid);
        }
    } else {
        createCookie('vasppid', computedVasppid);
    }
}

function evaluateVasToBeClicked(){
    //console.log("evaluateVasToBeClicked");
    preSelectedVasId = readCookie('selectedVas');
    var duration;
    if(typeof preSelectLandingVas != "undefined"){
        if(checkEmptyOrNull(preSelectLandingVas) || checkEmptyOrNull(readCookie('selectedVas'))){
	    mainMemTabSel = readCookie('mainMemTab');
	    if(!checkEmptyOrNull(mainMemTabSel) || mainMemTabSel == "X"){
                    currentMainMemSel = $(".planlist li.active").attr('mainMemTab'),
		    mainMemTabSel = currentMainMemSel,
		    d = $('#tab_'+currentMainMemSel+' .durSel.plansel').attr("mainMemDur");
		    createCookie('mainMemTab',mainMemTabSel);
		    createCookie('mainMemDur',d);
		    duration= d=='L'?'12':d;
	    }
	    else{
            if(!checkEmptyOrNull(readCookie('mainMemDur'))){
                d = $('#tab_'+readCookie('mainMemTab')+' .durSel.plansel').attr("mainMemDur");
                createCookie('mainMemDur',d);
            }
            d = readCookie('mainMemDur');
            duration= d=='L'?'12':d;
	    }
        duration= selectClosestAddonDuration(duration,astroDurations);
        manageAstroForDiscount(duration);
            if(!$("#"+mainMemTabSel+"A"+duration).is(':checked')){
                $("#"+mainMemTabSel+"A"+duration).trigger('click');
            }
            else{
                createCookie('selectedVas',$("#"+mainMemTabSel+"A"+duration).attr("astroAddon"));
                var m = readCookie('mainMemTab'),
                dd = readCookie('mainMemDur');
                managePriceStrike(m, dd);
            }
        }
    }
}

function manageAstroForDiscount(addonDuration){
    if(alreadyVasDiscount == "0"){
        var memebership = readCookie('mainMemTab'),
            time = readCookie('mainMemDur');
        var sP = $("#" + memebership + time + "_price_strike").text().trim().replace(',', ''),
            aP = $("#" + memebership + time + "_price").text().trim().replace(',', '');
        if(sP.length != 0){
            var disc = ((sP-aP)*100)/sP;
            //console.log("Discount",disc);
        }
        for (var key in vasPrice) {
            var originalVasPrice = vasPrice[key];
            if(typeof disc != "undefined"){
                var discountedVasPrice = (originalVasPrice*(100-disc))/100;
                discountedVasPrice = discountedVasPrice.toFixed(2);
                $("#" + memebership + key + "_price_strike").removeClass("disp-none");
                $("#" + memebership + key + "_price_strike").text(removeZeroInDecimal(commaSeparateNumber(originalVasPrice)));
                $("#" + memebership + key + "_price").text(removeZeroInDecimal(commaSeparateNumber(discountedVasPrice))+" ");
            }
            else{
                $("#" + memebership + key + "_price_strike").addClass("disp-none");
                $("#" + memebership + key + "_price").text(removeZeroInDecimal(commaSeparateNumber(originalVasPrice)));
            }
        }
    }
}

function selectClosestAddonDuration(num,arr){
    var curr = arr[0];
    var diff = Math.abs (num - curr);
    for (var val = 0; val < arr.length; val++) {
        var newdiff = Math.abs (num - arr[val]);
        if (newdiff < diff) {
            diff = newdiff;
            curr = arr[val];
        }
    }
    return curr;
}

function clickCheckbox(checkBox){
    $(checkBox).click(function() { 
        if( $(this).is(':checked'))
        {
            $(checkBox).each(function() {     

                $(this).parent().removeClass("selected");  
                $(this).prop('checked', false);             
            });
             $(this).parent().addClass("selected");
             $(this).prop('checked', true);  
            createCookie('selectedVas',$(this).attr("astroAddon"));
        }
        else
        {
             $(this).parent().removeClass("selected");   
             eraseCookie('selectedVas');
        }

        var m = readCookie('mainMemTab'),
            d = readCookie('mainMemDur');
        managePriceStrike(m, d);
    })
}

function customCheckboxAstro(checkboxName) {

    var checkBox = $('input[name="' + checkboxName + '"]');

    $(checkBox).each(function() {         
          $(this).wrap("<span class='customMem-checkbox'></span>");
          if ($(this).is(':checked')) {
              $(this).parent().addClass("selected");
          }
      });

    clickCheckbox(checkBox);
         
}
$.extend({
    redirectPost: function (location, args) {
        var form = '';
        $.each(args, function (key, value) {
            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
        });
        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
    }
});