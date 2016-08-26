var showToAll = "Show to All";
var Dontshow = "Don't Show";

$("document").ready(function () {
        $(document).on("click",".changeSetting",function(){
                $(this).parent().find(".changeSetting i").removeClass("tickSelected iconSprite");
                $(this).find("i").addClass("tickSelected iconSprite");
                submitObj.push("DISPLAYNAME",$(this).attr('rel'));
        });
        $(document).on("click","#doneBtn",function(){
                var selectedVal = $(".changeSetting .iconTick.tickSelected").parent().attr('rel');
                if(selectedVal=='N'){
                        $("#showText").html(Dontshow);
                }else{
                        $("#showText").html(showToAll);
                }
                $("#showAll").attr('rel',selectedVal);
                NameOverLayerAnimation(1);
        });
});
function NameOverLayerAnimation(close)
{
        if (close)
        {
                $("#nameSettingOverlay").removeClass("top_2").addClass('top_3');
                setTimeout(function () {
                        $("#nameSettingOverlay").addClass("dn").removeClass("top_3").css("margin-top", "").addClass("top_1");
                        hideCancelBackgroundDiv();
                }, animationtimer3s);
        } else
        {
                var height = $("#nameSettingOverlay").outerHeight();
                var sh = Math.floor(($(window).height() - height) / 2);

                $("#nameSettingOverlay").removeClass("dn");
                setTimeout(function () {
                        $("#nameSettingOverlay").removeClass("top_1").css("margin-top", sh).addClass("top_2");
                }, 10);
        }

}