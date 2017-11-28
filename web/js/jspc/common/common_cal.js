var buttonClicked=0;
var memTimerExtraDays=0

function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function criticalLayerButtonsAction(clickAction,button){
  console.log("clicked cal ", clickAction, button);
if(buttonClicked)return;    
buttonClicked=1;
var calTracking = '';
var layerId= $("#CriticalActionlayerId").val();
    var newNameOfUser='',namePrivacy='';
    if(layerId==9 && button=='B1'){   
        newNameOfUser = ($("#nameInpCAL").val()).trim();
        validation=validateUserName(newNameOfUser);
        if(validation!==true){
            $("#CALNameErr").text(validation);
            $("#CALNameErr").show();
            buttonClicked=0;
            return;
        }
        namePrivacy = $('input[ID="CALPrivacyShow"]').is(':checked') ? 'Y' : 'N';
      }
    if(layerId==18){   
        calTracking  +=( '&occupText=' + $(".js-otheroccInp input").val().trim());
      }

    Set_Cookie('calShown', 1, 1200);
    if(clickAction=="close" || clickAction=='RCB') {
    var URL="/common/criticalActionLayerTracking?"+calTracking;
    $.ajax({
        url: URL,
        type: "POST",
        data: {"button":button,"layerId":layerId,"namePrivacy":namePrivacy,"newNameOfUser":newNameOfUser},
    });
    if(layerId!=13 || button!='B1')
        closeCurrentLayerCommon();
    if(layerId == 14)
    {
      $("#alternateEmailSentLayer").hide();
    }
    if(clickAction=='RCB')
    {
        toggleRequestCallBackOverlay(1, 'RCB_CAL');
        $('.js-dd ul li[value="M"]').trigger('click');
    }
  /* GA tracking */
  GAMapper("GA_CAL_NO", {"layerId": layerId, "button": button});
  }
  else {
    /*GA tracking */
    GAMapper("GA_CAL_YES", {"layerId": layerId, "button": button});
    window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
  }
}
function CriticalActionLayer(){
  var CALayerShow=$("#CALayerShow").val();
  if(typeof(CALayerShow)=='undefined' ||  !CALayerShow || (getCookie("calShown") && (CALayerShow!='19')) ) return;
  if(CALayerShow!='0'){
    var layer=$("#CALayerShow").val();
    /*var discount_percentage=$("#DiscountPercentage").val();
    var discount_subtitle=$("#DiscountSubtitle").val();
    var start_date=$("#StartDate").val();
    var old_price=$("#OldPrice").val();
    var new_price=$("#NewPrice").val();
    var time = $("#TimeForLightning").val();
    var symbol = $("#Symbol").val();*/
    var url="/static/criticalActionLayerDisplay";
    if(typeof LAYERDATA.calObject != "undefined")
    var ajaxData={'layerId': LAYERDATA.calObject.LAYERID,
      'discountPercentage': LAYERDATA.calObject.discountPercentage,
      'discountSubtitle': LAYERDATA.calObject.discountSubtitle,
      'startDate': LAYERDATA.calObject.startDate,
      'oldPrice': LAYERDATA.calObject.oldPrice,
      'newPrice': LAYERDATA.calObject.newPrice,
      'time': LAYERDATA.calObject.lightningCALTime,
      'symbol': LAYERDATA.calObject.symbol
    };
    var ajaxConfig={'data':ajaxData,'url':url,'dataType':'html'};

    ajaxConfig.success=function(response){
      $('body').prepend(response);
      showLayerCommon('criticalAction-layer');
      if(CALayerShow==19){
        // var time = $("#TimeForLightning").val();
        showTimerForLightningCal(LAYERDATA.calObject.lightningCALTime);
      }
      if(CALayerShow==9) 
        $('.js-overlay').bind('click',function(){$(this).unbind();criticalLayerButtonsAction('close','B2');closeCurrentLayerCommon();});
      else
        $('.js-overlay').unbind('click');
    }
    $.myObj.ajax(ajaxConfig);
  }
}
// $(document).ready(function() {
//   var CALayerShow=$("#CALayerShow").val();
//   if(!(typeof(CALayerShow)=='undefined' ||  !CALayerShow) && CALayerShow!='0'){
//     CriticalActionLayer();
//   }
// });
// if(typeof LAYERDATA.calObject != "undefined" && LAYERDATA.calObject && typeof LAYERDATA.calObject.LAYERID != "undefined" && LAYERDATA.calObject.LAYERID && LAYERDATA.calObject.LAYERID > 0) CriticalActionLayer();
