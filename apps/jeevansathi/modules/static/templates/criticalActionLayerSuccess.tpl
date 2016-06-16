<script>

    function criticalLayerButtonsAction(clickAction,button) {

        $("#CALButton"+button).attr('onclick','');
            
                var layerId= $("#CriticalActionlayerId").val();
                
                    
                    if(clickAction=="close" || clickAction=='RCB') {
                    var URL="/common/criticalActionLayerTracking";
                    $.ajax({
                        url: URL,
                        type: "POST",
                        data: {"button":button,"layerId":layerId},
                    });

                    closeCurrentLayerCommon();
                    if(clickAction=='RCB')
                    {
                        toggleRequestCallBackOverlay(1, 'RCB_CAL');
                        $('.js-dd ul li[value="M"]').trigger('click');
                    }
                
                }
                else {
                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
                }
                
        }
</script>
<div id='criticalAction-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">~$titleText`</p>
                    <p class="f14 pt25 lh22">~$contentText`</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            <button id='CALButtonB1'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp bg6 f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button2Text`</button>
        </div>
    </div>

<input type="hidden" id="CriticalActionlayerId" value="~$layerId`">
