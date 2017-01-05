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
                var originalVal = $("#showAll").attr('orel');
                if(originalVal == submitObj.editFieldArray['DISPLAYNAME']){
                     submitObj.pop("DISPLAYNAME");
                }
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
function CalloverlayName(thisObject){
        var selectedVal = $(thisObject).attr("rel");
        var tickSelectedShow = "tickSelected iconSprite";
        var tickSelectedNoShow = "";
        if(selectedVal == 'N'){
             tickSelectedNoShow = tickSelectedShow;
             tickSelectedShow = '';
        }
        overlayNameTemplate=$("#nameSettingOverlay").html();
        $("#nameSettingOverlay").html('');
        overlayNameTemplate=overlayNameTemplate.replace(/\{\{tickSelectedShow\}\}/g,tickSelectedShow); 
        overlayNameTemplate=overlayNameTemplate.replace(/\{\{tickSelectedNoShow\}\}/g,tickSelectedNoShow);
        $("#nameSettingOverlay").append(overlayNameTemplate);
        $("#nameSettingOverlay").removeClass('dn');
        $("#nameSettingOverlay").css("min-height",screen.height);
	$("#nameSettingOverlay").addClass("web_dialog_overlay");
        $("#nameSettingOverlay").css("opacity",1);
        $("#nameSettingOverlay").css("background","rgba(0,0,0,0.8)");
        NameOverLayerAnimation();
}