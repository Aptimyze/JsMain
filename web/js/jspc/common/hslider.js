// JavaScript Document
function slider() {
    var count = 0;
    var slider_width = 600;
    var speed = 500;
    if (count == 0)
    $("#next").click(function() {
		if (count < ($("#images .basic").length - 1)) {
            var move_top = (count + 1) * -slider_width;
            $("#images").animate({
                left: move_top
            }, speed);
            count++;
            $("#prev").show();
        } else if (count == ($("#images .basic").length)) {
            $("#prev").show();
          }
    });
    $("#prev").click(function() {
        $("#next").show();
        if (count > 0) {
            var move_top = (count - 1) * -slider_width;
            $("#images").animate({
                left: move_top
            }, speed);
            count--;
        }
    });
}