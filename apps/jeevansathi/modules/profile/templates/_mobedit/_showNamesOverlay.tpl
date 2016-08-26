<div id="nameSettingOverlay" class="dn overlay">
        <div class="wid90p bg4 topLeft50">
                <div class="padd1015 f15 fontlig brdr15">Name Privacy Setting</div>
        <div id="showName" class="changeSetting padd22 hgt75 fontlig brdr15" rel="Y">
                <div class="pt8 f15">Show my name to all</div>
            <i class="fr iconTick {{tickSelectedShow}} iconSprite"></i>
        </div>
        <div id="dontShowName" class="changeSetting padd22 hgt75 fontlig brdr15" rel="N">
                <div class="f15">Don't show my name</div>
            <i class="fr iconTick {{tickSelectedNoShow}}"></i>
                <div class="f10 fl pt6">You will not be able to see names<br> of other members</div>
        </div>
        <div id="doneBtn" class="padd1015 color2 fullwid txtc f15">Done</div>
        </div>
</div>
<script>
function CalloverlayName(thisObject){
        var selectedVal = $(thisObject).attr("rel");
        var tickSelectedShow = "tickSelected";
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
        NameOverLayerAnimation();
}
</script>