function ShowDrop(param, param2) {
    $('.sub-mainlist,.sub i.reg-droparrow').css('display', 'none');
    var Twidth = $('#' + param)[0].getBoundingClientRect().width;
    var Tleft = (Twidth / 2) - 8;
    $('#' + param + ' i.reg-droparrow').css({
        'display': 'block',
        'left': Tleft
    });
    $('.js-' + param).css('display', 'block');
}

function scrolLabel_1(param) {
    var width = $('.js-' + param)[0].getBoundingClientRect().width;
    var animate = 140 - width;
    $('.js-div1 .showdd').css('display', 'none');
    $('.js-' + param).animate({
        "left": animate,
        "top": "10px"
    }, 100, function() {
        $('.jsdd-' + param).css('display', 'block');
        if (param == "dob") {
            ShowDrop("date");
        }
    });
}

function getWidth(param) {
    var Twidth = $('#' + param)[0].getBoundingClientRect().width;
    return Twidth;
}

function ShowDropDpp(param) {
    console.log("param->" + param);
    var Nwidth = getWidth(param);
    console.log(Nwidth);
    var Nleft = (Nwidth / 2) - 8;
    $('.drop-' + param).css({
        'display': 'block',
        'left': Nleft
    });
    $('#' + param + ' .dppbox').css('display', 'block');
}

function customCheckbox(checkboxName) {
    var checkBox = $('input[name="' + checkboxName + '"]');
    $(checkBox).each(function() {
        $(this).wrap("<span class='custom-checkbox'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function() {
        $(this).parent().toggleClass("selected");
    });
}

function SlideUpFilter(param) {
    $('.' + param + 'opt').slideToggle(1000);
    $('#' + param).toggleClass('srpopenarrow');
}
$(function() {
    var ScreenWid = $(window).width();
    var ScreenHgt = $(window).height();
    $('.js-up').click(function() {
        SlideUpFilter($(this).attr("id"));
    });
    $('.lblreg').click(function() {
        var getAttr = $(this).attr('data-attr');
        scrolLabel_1(getAttr);
    });
    $('ul.rlist li').click(function() {
        var getId = $(this).closest('ul').attr('id');
        $("ul#" + getId + " li").removeClass("activeopt");
        $(this).addClass("activeopt");
        //if the clicked option has further listing option
        if ($(this).hasClass("sub")) {
            var getListId = $(this).attr("id");
            ShowDrop(getListId);
        }
    });
    $('.dppselopt').click(function() {
        $('.dpp-up-arrow').css('display', 'none');
        var getDppAttr = $(this).attr('data-attr');
        //var getDppIdAttr = $(this).attr('id');
        //console.log(getDppIdAttr);
        ShowDropDpp(getDppAttr);
    });
    $('.js-edit').click(function() {
        var getEditId = $(this).attr('id');
        console.log(getEditId);
        $('.' + getEditId + '-info, #' + getEditId).css('display', 'none');
        $('.' + getEditId + '-field, .' + getEditId + '-button').css('display', 'block');
        $('#' + getEditId + '-edit label > div').css('padding', '15px 30px 0 0');
    });
    $('.js-email').click(function() {
        $('.js-email').slideUp(400, function() {
            $('.js-email-desc').slideDown(500);
        });
    });
    //script for send interest
    $('ul.sendintropt li').click(function() {
        var getliID = $(this).attr('id');
        var temp = "";
        if (getliID) {
            temp = getliID.split("-");
        }
        var getliID = temp[1];
        DisplayDiv(getliID);
        $('ul.sendintropt li').removeClass('colr4');
        $(this).addClass('colr4');
    });
    //SRP  on click of 37 more
    $('#edumore').click(function() {
        var LayerHgt = ScreenHgt - 160;
        console.log(ScreenHgt);
        console.log(LayerHgt);
        $('.js-LayerCont').css('height', LayerHgt);
        $('.overlay, #filterlayer').css('display', 'block');
    });
})