//need to move this in common 
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

function updateVal(key, val, e) {
    var ajaxData = {};
    ajaxData['update'] = key;
    ajaxData[key] = val;
    $.ajax({
        type: 'POST',
        url: "/api/v3/settings/alertManager",
        data: ajaxData,
        success: function(data) {
            $(e).parent().find('button').each(function() {
                $(this).removeClass('selcted');
            })
            $(e).addClass('selcted');
        }
    });
}

$(function() {
    $('.selNotif').click(function() {
        var getID = $(this).attr('data-attr');
        var b = getID.split('-');
        var getVal = $(this).attr('data-val');
        var ajaxData = {};
        var that = this;
        ajaxData['update'] = b[1];
        ajaxData[b[1]] = getVal;
        $.ajax({
            type: 'POST',
            url: "/api/v3/settings/alertManager",
            data: ajaxData,
            success: function(data) {
                if (b[0] == "unchk") {
                    if ($('#' + b[1]).prop('checked') != false) {
                        $('#' + b[1]).prop("checked", false);
                        $(that).parent().siblings('.box').toggleClass('move');
                    }
                } else {
                    if ($('#' + b[1]).prop('checked') != true) {
                        $('#' + b[1]).prop("checked", true);
                        $(that).parent().siblings('.box').toggleClass('move');
                    }
                }
            }
        });
    });

    function checkstatus() {
        $("input.settingInp").each(function() {
            if ($(this).is(":checked")) {
                $(this).siblings('.box').toggleClass('move');
            }
        });
    }
    checkstatus();
});

(function($) {
    $.fn.equalHeights = function(options) {
        var maxHeight = 0,
            $this = $(this),
            equalHeightsFn = function() {
                var height = $(this).innerHeight();
                if (height > maxHeight) {
                    maxHeight = height;
                }
            };
        options = options || {};
        $this.each(equalHeightsFn);
        if (options.wait) {
            var loop = setInterval(function() {
                if (maxHeight > 0) {
                    clearInterval(loop);
                    return $this.css('height', maxHeight);
                }
                $this.each(equalHeightsFn);
            }, 100);
        } else {
            return $this.css('height', maxHeight);
        }
    };
    // auto-initialize plugin
    $('[data-equal]').each(function() {
        var $this = $(this),
            target = $this.data('equal');
        console.log(target);
        $this.find(target).equalHeights();
    });
})(jQuery);