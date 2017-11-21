$(document).ready(function() {
    $('#CR').click(function(e) {
        displayOverlayContent('CR');
        $("div.imgIconId").show();
        $('div.selectedOption').addClass('rv2_pad12').removeClass('padd22');
    });
    $('#DR').click(function(e) {
        displayOverlayContent('DR');
        $("div.imgIconId").show();
        $('div.selectedOption').addClass('rv2_pad12').removeClass('padd22');
    });
    $('#NB').click(function(e) {
        displayOverlayContent('NB');
        $("div.imgIconId").hide();
        $('div.selectedOption').addClass('padd22').removeClass('rv2_pad12');
    });
    $('#CSH').click(function(e) {
        displayOverlayContent('CSH');
        $("div.imgIconId").show();
        $('div.selectedOption').addClass('rv2_pad12').removeClass('padd22');
    });
    $('#PP').click(function(e) {
        displayOverlayContent('PP');
        $("div.imgIconId").show();
        $('div.selectedOption').addClass('rv2_pad12').removeClass('padd22');
    });
    $('#backOnCard').click(function(e) {
        $("#tapOverlayHead").hide();
        $("#tapOverlayContent").hide();
        $("#ContentDiv").html('');
        htmlStr = '<div class="pt10"><div class="rv2_brdr1 color8 rv2_brrad1 fontlig selectedOption" name="payMode" selId="" onclick="addPayCard(this);"><div class="disptbl fullwid"><div class="dispcell rv2_wid8 imgIconId"><div class="rv2_sprtie1" id="ic_id"></div></div><div class="dispcell vertmid pname padl10" id="name"></div><div class="dispcell vertmid rv2_wid9"><div class="rv2_sprtie1 options" id="mode_option_id"></div></div></div></div></div>';
        $("#ContentDiv").html(htmlStr);
        if(checkEmptyOrNull(readCookie('device'))){
          $('div.selectedOption').removeClass(readCookie('device')+'_selected_d');  
        } else {
          $('div.selectedOption').removeClass('selected_d');
        }
        $('html, body, #DivOuter').css({
            'overflow': 'auto',
            'height': 'auto'
        });
        e.preventDefault();
    });
    $("#contPaymentBtn").click(function() {
        //redirectTo =url = "~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails?"
        //$("#makePaymentForm").attr("action",redirectTo);
        setTimeout(function() {
            $("#makePaymentForm").submit();
        }, 200);
    });
    $("#cashChequePickup").click(function() {
        var mainMembership = $('[name=mainMembership]').val();
        var vasImpression = $('[name=vasImpression]').val();
        var upgradeMem = $('[name=upgradeMem]').val();
        var paramStr = 'displayPage=6' + '&mainMembership=' + mainMembership + '&vasImpression=' + vasImpression+'&upgradeMem='+upgradeMem;
        if(checkEmptyOrNull(readCookie('device'))){
          paramStr += '&device=' + readCookie('device');
        }
        var url = "/membership/jsms?" + paramStr;
        window.location.href = url;
    });
    $("#openCityOptionsLayer").click(function() {
        setTimeout(function() {
            chequePickupOverlay();
        }, 250);
        $("#overlayHeadingCity").show();
        $("#cityDropdown").show();
        $("#dateDropdown").hide();
        $("#overlayHeadingDate").hide();
    });
    $("#openDateOptionsLayer").click(function() {
        setTimeout(function() {
            chequePickupOverlay();
        }, 250);
        $("#overlayHeadingCity").hide();
        $("#cityDropdown").hide();
        $("#dateDropdown").show();
        $("#overlayHeadingDate").show();
    });
    $('.selectedContBtn').click(function(e) {
        $("#tapOverlayHead").hide();
        $("#tapOverlayContent").hide();
        if(checkEmptyOrNull(readCookie('device'))) {
          $('div.selectedOption').removeClass(readCookie('device')+'_selected_d');
        } else {
          $('div.selectedOption').removeClass('selected_d');
        }
        e.preventDefault();
        $('html, body, #DivOuter').css({
            'overflow': '',
            'height': ''
        });
    });
    $("#submitPickupForm").click(function(e) {
        var isValid = validatePickupForm();
        if (isValid) {
            var mainMembership = $('[name=mainMembership]').val();
            var vasImpression = $('[name=vasImpression]').val();
            var name = $("#name").val().replace(/^\s+|\s+$/g, '');
            var landline = $("#landline").val().replace(/^\s+|\s+$/g, '');
            var mobile = $("#mobile").val().replace(/^\s+|\s+$/g, '');
            var address = $("#address").val().replace(/^\s+|\s+$/g, '');
            var comment = $("#comment").val().replace(/^\s+|\s+$/g, '');
            var city = $("#city").val();
            var date = $("#date").val();
            var paramStr = 'pickupRequest=1' + '&name=' + name + '&landline=' + landline + '&mobile=' + mobile + '&address=' + address + '&comment=' + comment + '&city=' + city + '&date=' + date + mainMembership;
            paramStr = paramStr.replace(/amp;/g, '');
            url = "/api/v3/membership/membershipDetails?" + paramStr;
            $.ajax({
                type: 'POST',
                url: url,
                success: function(data) {
                    response = data;
                    if (data.status == 1) {
                        var params = data.params;
                        var redirectUrl = "/membership/jsms?" + params;
                        if(checkEmptyOrNull(readCookie('device'))){
                          redirectUrl += '&device=' + readCookie('device');
                        }
                        window.location.href = redirectUrl;
                    }
                }
            });
        }
    });
});

function displayOverlayContent(payMode) {
    $("#paymentMode").val(payMode);
    paymentOptionOverlay();
    var htmlArr = new Array();
    var htmlStr = '';
    var paymentModeId = payMode;
    var cardOptionArray = paymentOption[paymentModeId];
    $("#topHeading").html(topHeading[paymentModeId]['payment_title']);
    $("#contPaymentBtn").val(continueText[paymentModeId]['continue_text']);
    for (j = 0; j < cardOptionArray.length; j++) {
        cardArr = cardOptionArray[j];
        iconId = cardArr['ic_id'];
        name = cardArr['name'];
        modeOptionId = cardArr['mode_option_id'];
        $("#ic_id").removeClass();
        $("#ic_id").addClass('rv2_sprtie1');
        $("#ic_id").addClass(iconId);
        $("#name").html(name);
        $('[name=payMode]').attr('selId', modeOptionId);
        $('[name=payMode]').attr('payMode', payMode);
        optionTupleHtml = $("#ContentDiv").html();
        htmlArr.push(optionTupleHtml);
    }
    htmlStr = htmlArr.join("");
    $("#ContentDiv").html(htmlStr);
    $("div.selectedOption:first").trigger('click');
}

function addPayCard(e,isMaterialApp) {
    isMaterialApp = (typeof isMaterialApp !== 'undefined') ?  isMaterialApp : false;
    if(checkEmptyOrNull(readCookie('device'))&& !isMaterialApp){
      $('div.selectedOption').removeClass(readCookie('device')+'_selected_d');
    } else {
      $('div.selectedOption').removeClass('selected_d');
    }
    //$(e).find('input[name=payMode]').attr('checked',true);
    //var selectedOption=$(e).find('input[name=payMode]:checked').attr("value");
    var selectedOption = $(e).attr("selId");
    if(selectedOption == 'OTHER'){
        selectedOption = 'card5';
        $("#paymentMode").val('CR');
    } else {
        $("#paymentMode").val($(e).attr('payMode'));
    }
    if(checkEmptyOrNull(readCookie('device'))&& !isMaterialApp){
      $(e).closest('div.selectedOption').addClass(readCookie('device')+'_selected_d');
    } else {
      $(e).closest('div.selectedOption').addClass('selected_d');
    }
    $("#cardType").val(selectedOption);
}

function paymentOptionOverlay() {
    var vwid = $(window).width();
    var vhgt = $(window).height();
    hgt = vhgt + "px";
    $('html, body, #DivOuter').css({
        'overflow': 'hidden',
        'height': '100%'
    });
    /* for setting content overlay */
    var n_wid = vwid - 20;
    var n_hgt = vhgt - 20;
    $('#ContLayer').css({
        "width": n_wid,
        "height": n_hgt
    });
    var ContMid_hgt = n_hgt - (53 + 58);
    $('#ContMid').css("height", ContMid_hgt);
    $("#tapOverlayHead").show();
    $("#tapOverlayContent").show();
}

function chequePickupOverlay() {
    var vwid = $(window).width();
    var vhgt = $(window).height();
    hgt = vhgt + "px";
    $('html, body, #DivOuter').css({
        'overflow': 'hidden',
        'height': '100%'
    });
    /* for setting content overlay */
    var n_wid = vwid - 20;
    var n_hgt = vhgt - 20;
    $('#ContLayer').css({
        "width": n_wid,
        "height": n_hgt
    });
    var headtHgt = $('#ContHead').outerHeight();
    var footHgt = $('#ContBtn').outerHeight();
    var ContMid_hgt = n_hgt - (53 + 58);
    $('#ContMid').css("height", ContMid_hgt);
    $("#tapOverlayHead").show();
    $("#tapOverlayContent").show();
}

function addCashChequePickupValue(e) {
    if(checkEmptyOrNull(readCookie('device'))){
      $('div.selectedOption').removeClass(readCookie('device')+'_selected_d');
    } else {
      $('div.selectedOption').removeClass('selected_d');
    }
    var cityId = $(e).attr("cityId");
    var cityLabel = $(e).attr("selectedCityLabel");
    var dateId = $(e).attr("dateId");
    var dateLabel = $(e).attr("selectedDateLabel");
    if(checkEmptyOrNull(readCookie('device'))){
      $(e).closest('div.selectedOption').addClass(readCookie('device')+'_selected_d');
    } else {
      $(e).closest('div.selectedOption').addClass('selected_d');
    }
    if (cityId) {
        $("#city").val(cityId);
        $("#cityLabel").val(cityLabel);
        $("#cityLabelId").html($("#cityLabel").val());
    }
    if (dateId) {
        $("#date").val(dateId);
        $("#dateLabel").val(dateLabel);
        $("#dateLabelId").html($("#dateLabel").val());
    }
}

function validatePickupForm() {
    var error;
    var name = $("#name").val();
    var landline = $("#landline").val();
    var mobile = $("#mobile").val();
    var address = $("#address").val();
    var comment = $("#comment").val();
    var cityId = $("#city").val();
    var dateId = $("#date").val();
    $('#name_error').removeClass('rv2_errcolr');
    $('#landline_error').removeClass('rv2_errcolr');
    $('#mobile_error').removeClass('rv2_errcolr');
    $('#address_error').removeClass('rv2_errcolr');
    $('#comment_error').removeClass('rv2_errcolr');
    $('#city_error').removeClass('rv2_errcolr');
    $('#date_error').removeClass('rv2_errcolr');
    if (name == '') {
        $('#name_error').addClass('rv2_errcolr');
        error = 1;
    }
    if (landline == '') {
        $('#landline_error').addClass('rv2_errcolr');
        error = 1;
    } else if (landline) {
        validLandline = validateMob(landline);
        if (!validLandline) {
            $('#landline_error').addClass('rv2_errcolr');
            error = 1;
        }
    }
    if (mobile == '') {
        $('#mobile_error').addClass('rv2_errcolr');
        error = 1;
    } else if (mobile) {
        mobValid = validateMob(mobile);
        if (!mobValid) {
            $('#mobile_error').addClass('rv2_errcolr');
            error = 1;
        }
    }
    if (address == '') {
        $('#address_error').addClass('rv2_errcolr');
        error = 1;
    }
    if (comment == '') {
        $('#comment_error').addClass('rv2_errcolr');
        error = 1;
    }
    if (cityId == '') {
        $('#city_error').addClass('rv2_errcolr');
        error = 1;
    }
    if (dateId == '') {
        $('#date_error').addClass('rv2_errcolr');
        error = 1;
    }
    if (error) return false;
    else return true;
}

function validateMob(val) {
    regEx = /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
    if (!regEx.test(val)) return false;
    else return true;
}

function validatePhone(val) {
    regEx = /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{6})$/;
    if (!regEx.test(val)) return false;
    else return true;
}