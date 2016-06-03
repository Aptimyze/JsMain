// JavaScript Document
function slider() {
    var count = 0;
    var slider_width = 600;
    var speed = 500;
    if (count == 0)
    //$("#prev").hide();
    //console.log("1a");
    //console.log($("#images .basic").length);
        $("#next").click(function() {
            if (count < ($("#images .basic").length - 1)) {
                //console.log("3a");
                var move_top = (count + 1) * -slider_width;
                //console.log(move_top);
                $("#images").animate({
                    left: move_top
                }, speed);
                count++;
                //console.log(count);
                $("#prev").show();
            } else if (count == ($("#images .basic").length)) {
                //$("#images").animate({left : 0} , speed);
                //count = 0;      
                $("#prev").show();
                //$("#next").hide();
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
        //if(count == 0)
        //$("#prev").hide();
    });
}