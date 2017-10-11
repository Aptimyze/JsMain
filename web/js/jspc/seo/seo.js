$(function() {
    $("ul.tabs li").click(function() {
        var relVal = $(this).attr('rel');
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $('.tab_content').each(function() {
            var getid = $(this).attr('id');
            if ($(this).css("visibility") == "visible") {
                $(this).fadeOut(200, function() {
                    $(this).css('visibility', 'hidden');
                    console.log($("#tab2"));
                    $('#' + relVal).fadeIn(200, function() {
                        $(this).css('visibility', 'visible')
                    });
                });
            }
        });
    });
    $(".tab_content").find('.browsebyp ul li.sub_h').each(function() {
        var TempWid = 0;
        var TempWid1 = 0,
            newLeft = 0;
        TempWid = $(this)[0].getBoundingClientRect().width;
        $(this).find('.sub').each(function() {
            TempWid1 = $(this)[0].getBoundingClientRect().width;
            newLeft = Math.abs((TempWid1 / 2) - (TempWid / 2)) - 4;
            $(this).css('left', -(newLeft));
        });
    });
    $('ul.comopts li span').click(function() {
        $('ul.comopts li').removeClass('active');
        $(this).parent().addClass('active');
        var currWidth = $(this).outerWidth();
        var currLeft = $(this).position().left;
        var currID = $(this).attr('id');
        //start sliding function  
        $(".comline").animate({
            left: currLeft,
            width: currWidth
        }, {
            duration: 200,
            queue: false
        });
        if (currID == "secb") {
            $(".js-comshift").animate({
                left: '-732px'
            }, {
                duration: 200,
                queue: false
            });
        } else {
            $(".js-comshift").animate({
                left: '0'
            }, {
                duration: 200,
                queue: false
            });
        }
    });
});