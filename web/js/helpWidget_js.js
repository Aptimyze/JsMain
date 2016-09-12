var helpWidget = '#js-helpWidget';
var helpWidgetToggle = '.js-helpCollapses';
var helpWidgetContent = '.js-helpWidgetContent';
var requestCallBack = '.js-openRequestCallBack';
var requestCallBackOverlay = '.js-requestCallBackOverlay';
var requestCallBackClose = '.js-requestCallBackClose';
var CLOSE_STATUS = 'close-help-widget';
var formWidget = "#Widget";
var defaultEmail = "";
var defaultPhone = "";
var closeHelpWidgetIntervalId = "";
var callbackSource = "";
$(document).ready(function () {
    if (typeof (hideHelpMenu) == "undefined") {
        var hideHelpMenu = "false";
    }
    if (hideHelpMenu == "true") {
        $("#js-helpWidget").addClass('disp-none');
    }
    if (window.location.href.indexOf("/register/") != -1 || (window.location.href.indexOf("profile/registration_pg1.php") != -1) || (window.location.href.indexOf("profile/registration_new.php") != -1) || (window.location.href.indexOf("profile/registration_page1.php") != -1)) {
        return;
    }
    if ($("#js-helpWidget").length == 0) {
        return;
    }
    $(helpWidget).removeClass('dspN');
    $(requestCallBack).click(function () {
        toggleRequestCallBackOverlay(1);
    })
    $(requestCallBackClose).click(function () {
        toggleRequestCallBackOverlay(0);
    })
    $(helpWidgetToggle).click(toggleHelpWidget);
    $(formWidget).submit(requestCallBackCall);
    $(".js-dt").click(function () {
        var N_id = $(this).parent().attr('id');
        $(".js-dd ul").css('display', 'none');
        $("#" + N_id + " .js-dd ul").toggle();
    });
    $(".js-dd ul li").click(function () {
        var text = $(this).html();
        var value = $(this).attr("value");
        var text1 = $(this).text();
        var P_id = $(this).parent().parent().parent().attr('id');
        $("#" + P_id + " .js-dt span").html(text);
        $("#" + P_id + " .js-dt span").attr("value", value);
        $("#" + P_id + " .js-dt span").css("color", "#000000");
        $("#rq_query").val(value);
        $("#" + P_id + " .js-dd ul").css('display', 'none');
        $("#querryError").addClass("dspN");
    });
    $(document).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("dropdown")) {
            $(".dropdown .js-dd ul").hide();
        }
    });
    //If Expand Mode
    if (typeof showExpandMode != "undefined" && showExpandMode == "1") {
        expandHelpWidget();
    } else {
        collapseHelpWidget();
    }
    var gCloseWidgetStatus = getCookie(CLOSE_STATUS);
    //If Screen is less then 1280 then Show in Collapse State
    //or global close is set
    if ((gCloseWidgetStatus && gCloseWidgetStatus == "1") || (window.screen.availWidth < 1280)) {
        collapseHelpWidget();
    }
    defaultEmail = $('#rq_email').val();
    defaultPhone = $('#rq_phone').val();
    $('#rq_email').on("blur", checkEmail);
    $('#rq_phone').on("blur", checkPhone);
});
/*
 * Function to toggle Request call back overlay
 * @Param : show : if show = 0 provide then hide or close the overlay
 *                  else display the overlay         
 */
function toggleRequestCallBackOverlay(show, cbSource) {
    if (cbSource) {
        callbackSource = cbSource;
    } else {
        callbackSource = '';
    }
    if ($(requestCallBackOverlay).hasClass('dspN') && show) {
        $(requestCallBackOverlay).removeClass('dspN');
        if ($('#rcbResponse').length) {
            $('.js-dd ul li[value="M"]').trigger('click');
        } else {
            $('#typeOfQuery').html("What type of query do you have?");
            $('#typeOfQuery').css("color", "");
        }
    } else if (0 == show) {
        $(requestCallBackOverlay).addClass('dspN');
        $("#emailError").addClass("dspN");
        $("#phoneError").addClass("dspN");
        $("#querryError").addClass("dspN");
        if ($("#requestForm").hasClass("dspN")) {
            $('#rq_email').val(defaultEmail);
            $('#rq_phone').val(defaultPhone);
            $('#rq_query').attr("value", "");
            if ($('#rcbResponse').length) {
                $('.js-dd ul li[value="M"]').trigger('click');
            } else {
                $('#typeOfQuery').html("What type of query do you have?");
                $('#typeOfQuery').css("color", "");
            }
            $("#requestForm").removeClass("dspN");
            $("#requestSuccessMsg").addClass("dspN");
        }
        if (typeof closeHelpWidgetIntervalId == "number") {
            clearTimeout(closeHelpWidgetIntervalId);
            closeHelpWidgetIntervalId = "";
        }
        if ($('#rcbResponse').length) {
            $('#rcbResponse').remove();
        }
    }
}
/*
 * Function to toggle widget
 */
function toggleHelpWidget() {
    var width = $(helpWidgetContent)[0].getBoundingClientRect().width * -1;
    var show = ($(helpWidget).css('right').indexOf(width) == -1) ? 0 : 1;
    if (!show) { //Hide
        collapseHelpWidget();
        Set_Cookie(CLOSE_STATUS, 1, "", "/");
    } else if (show) { //Show
        expandHelpWidget();
        Set_Cookie(CLOSE_STATUS, 0, "", "/");
    }
}
//this function gets the value of first li in the ul
function getValFLi() {
    var getdata = $('.dropdown .js-dd ul').find('li:first').map(function () {
        return $(this).text();
    }).get();
    return getdata;
}

function collapseHelpWidget() {
    var width = $(helpWidgetContent)[0].getBoundingClientRect().width * -1;
    $(helpWidget).animate({
        'right': width + 'px'
    }, 200);
}

function expandHelpWidget() {
    if ($(helpWidgetContent).hasClass('dspN')) {
        $(helpWidgetContent).removeClass('dspN')
    }
    if ($(helpWidgetToggle).hasClass('r0')) {
        $(helpWidgetToggle).removeClass('r0');
        $(helpWidgetToggle).addClass('l0');
    }
    $(helpWidget).animate({
        'right': '0px'
    }, 200);
}

function requestCallBackCall() {
    $("#querryError").addClass("dspN");
    var url = '/common/requestCallBack';
    var email = $('#rq_email').val();
    var phone = $('#rq_phone').val();
    var query = $('#rq_query').attr("value");
    var date = $("#rcbSideMenudropDown0").val(),
        startTime = $("#rcbSideMenudropDown1").val(),
        endTime = $("#rcbSideMenudropDown2").val();
    var t1 = Date.parse(date + " " + startTime),
        t2 = Date.parse(date + " " + endTime),
        now = Date.parse(new Date());
    if ($('#rcbResponse').length) {
        var rcbResponse = $('#rcbResponse').attr("value");
    }
    var validate = true;
    validate = checkEmail();
    validate = (checkPhone() == false) ? false : validate;
    if (!query || query.length == 0) {
        validate = false;
        $("#querryError").html("Please select a type of query");
        $("#querryError").removeClass("dspN");
    }
    if (t2 - t1 <= 0 || t1 < now) {
        $("#sideMenuReqTimeError").show();
        validate = false;
    } else {
        $("#sideMenuReqTimeError").hide();
    }
    if (!validate) {
        return false;
    }
    $("#requestForm").addClass("dspN");
    $("#requestLoader").removeClass("dspN");
    if (callbackSource == '') {
        callbackSource = 'Help_Widget';
    }
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        timeout: 5000,
        data: {
            email: email.trim(),
            phone: phone.trim(),
            query_type: query,
            rcbResponse: rcbResponse,
            date: date,
            startTime: startTime,
            endTime: endTime,
            'device': 'desktop',
            'channel': 'JSPC',
            'callbackSource': callbackSource
        },
        success: function (result) {
            if (result.trim() == "Y") {
                $("#requestLoader").addClass("dspN");
                $("#requestSuccessMsg").html("We shall call you at the earliest.");
                $("#requestSuccessMsg").removeClass("dspN");
                //Handle RCB Communication on Acceptance page 
                if (rcbResponse && $('.js-rcbMessage').length) {
                    $('.js-rcbMessage').remove();
                    $("<div class='rel_c js-rcbMessage' id='callDiv3'><div class='ccp11 pb20 fontlig color11'><div class='mainBrdr2'><div class='f14 fontlig'>Thank you for showing interest in our plans. Our customer service executive will reach to you shortly.</div></div></div></div>").insertAfter("#outerCCTupleDiv3");
                }
                closeHelpWidgetIntervalId = setTimeout(function () {
                    toggleRequestCallBackOverlay(0);
                }, 2000);
            } else {
                handleError();
            }
        },
        error: function (result) {
            handleError()
        }
    });
    return true;
}

function validateEmail(val) {
    var regEx = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
    if (!regEx.test(val)) {
        return false;
    }
    return true;
}

function validatePhone(val) {
    var regExIndian = /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
    var regExIndianLandline = /^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
    var regExInternational = /^\+(?:[0-9][-. ]? ?){7,14}[0-9]$/;
    if (!regExIndian.test(val) && !regExInternational.test(val) && !regExIndianLandline.test(val)) {
        return false;
    }
    return true;
}

function handleError() {
    $("#requestLoader").addClass("dspN");
    $("#requestSuccessMsg").html("Something Went Wrong!");
    $("#requestSuccessMsg").removeClass("dspN");
}

function checkEmail() {
    var email = $('#rq_email').val();
    $("#emailError").addClass("dspN");
    var validate = true;
    if (!validateEmail(email.trim())) {
        //Show Error Message for email
        validate = false;
        $("#emailError").html("Please provide a valid email id");
        $("#emailError").removeClass("dspN");
    }
    return validate;
}

function checkPhone() {
    var phone = $('#rq_phone').val();
    $("#phoneError").addClass("dspN");
    var validate = true;
    if (!validatePhone(phone.trim())) {
        //Show Error Message for email
        validate = false;
        $("#phoneError").html("Please provide a valid phone number");
        $("#phoneError").removeClass("dspN");
    }
    return validate;
}

function getValFLi() {
    var getdata = $('#rcbSideMenuDrop .rcbdropdown dd ul').find('li:first').map(function () {
        return $(this).text();
    }).get();
    return getdata;
}
$("#rcbSideMenuDrop dt").click(function () {
    var N_id = $(this).parent().attr('id');
    $("dd ul").css('display', 'none');
    $("#" + N_id + " dd ul").toggle();
});
$("#rcbSideMenuDrop dd ul li").click(function () {
    var text = $(this).html();
    var text1 = $(this).text();
    var P_id = $(this).parent().parent().parent().attr('id');
    $("#" + P_id + " dt span").html(text);
    $("#" + P_id + " dd ul").css('display', 'none');
    $("#rcbSideMenu" + P_id + "").val($(this).attr('id'));
    var date = $("#rcbSideMenudropDown0").val(),
        startTime = $("#rcbSideMenudropDown1").val(),
        endTime = $("#rcbSideMenudropDown2").val();
    var t1 = Date.parse(date + " " + startTime),
        t2 = Date.parse(date + " " + endTime),
        now = Date.parse(new Date());
    if (t2 - t1 <= 0 || t1 < now) {
        $("#sideMenuReqTimeError").show();
    } else {
        $("#sideMenuReqTimeError").hide();
    }
});

function intialize() {
    var value = getValFLi();
    $.each(value, function (i, val) {
        if (i == 2) {
            val = "9 PM";
            $("#rcbSideMenuDrop #rcbSideMenudropDown" + i).val($("#rcbSideMenuDrop #dropDown" + i + " dd ul li:last").attr('id'));
        } else {
            $("#rcbSideMenuDrop #rcbSideMenudropDown" + i).val($("#rcbSideMenuDrop #dropDown" + i + " dd ul li:first").attr('id'));
        }
        $("#rcbSideMenuDrop #dropDown" + i + " dt span").html(val);
    });
}
$(document).bind('click', function (e) {
    var $clicked = $(e.target);
    if (!$clicked.parents().hasClass("rcbdropdown")) {
        $("#rcbSideMenuDrop .rcbdropdown dd ul").hide();
    }
});
intialize();