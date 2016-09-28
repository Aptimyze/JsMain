var hideDays = 7,deleteReasonSelected=0, offerConsent=0;

$(document).ready(function() {
bindOfferConsentBox();

  $( "#sevenDayHide").bind('click',function() {
  hideDays = 7;
  $("#sevenDayHide").addClass("setactive").removeClass("setbtn1");
  $("#tenDayHide").removeClass("setactive").addClass("setbtn1");
  $("#thirtyDayHide").removeClass("setactive").addClass("setbtn1");
});
$( "#tenDayHide" ).bind('click',function() {
  hideDays = 10;
  $("#tenDayHide").addClass("setactive").removeClass("setbtn1");
  $("#sevenDayHide").removeClass("setactive").addClass("setbtn1");
  $("#thirtyDayHide").removeClass("setactive").addClass("setbtn1");
});

$( "#thirtyDayHide" ).bind('click',function() {
  $("#thirtyDayHide").addClass("setactive").removeClass("setbtn1");
  $("#tenDayHide").removeClass("setactive").addClass("setbtn1");
  $("#sevenDayHide").removeClass("setactive").addClass("setbtn1");

  hideDays = 30;
});
$('#mainContainerID').bind('click', function(e)
{
  
  if(e.target.id == "delOptionID")
          return;
       if($(e.target).closest('#delOptionID').length)
          return;             
  $("#deleteOptionListID").hide();
});
$("#delOptionID").bind('click', function()
{
    $("#deleteOptionListID").show();
});

$(".sltOption").bind('click', function()
{
  
  deleteReasonSelected=1;
  $('.reasonDivCommon').hide();
  var optionVal=$(this).html();
  $('#delOptionSetID').html(optionVal);
  if($(this).hasClass('sltOption2') )
  {
    offerConsent=1;
    $('input[name="js-offerConsentCheckBox"]').closest('li').addClass("selected");
    $("#offerCheckBox").show();
    $("#DeleteTextID").html("Delete my Profile");
    $("#specifiedID").show();
    $("#deleteReasonPrompt").hide();
    $("#deleteReasonBox").removeClass('errbrd');
    $("#specifyLinkID").show();
  }
  else if ($(this).hasClass('sltOption3')){
    $('input[name="js-offerConsentCheckBox"]').closest('li').addClass("selected");
    offerConsent=1;
    $("#DeleteTextID").html("Delete my Profile");
    $("#deleteReasonPrompt").hide();
    $("#deleteReasonBox").removeClass('errbrd');
    $("#offerCheckBox").show();
    $("#specifiedID").show();
    $("#specifyReasonID").show();
  

  }

  else if ($(this).hasClass('sltOption4')){
    offerConsent=0;
    $("#DeleteTextID").html("Delete my Profile");
    $("#offerCheckBox").hide();
    $("#deleteReasonPrompt").hide();
    $("#deleteReasonBox").removeClass('errbrd');

    $("#specifiedID").show();
    $("#specifyOtherReasonID").show();
  }

  else if ($(this).hasClass('sltOption5'))
  {
    offerConsent=0;
    $("#DeleteTextID").html("Delete my Profile");
    $("#offerCheckBox").hide();
    $("#deleteReasonPrompt").hide();
    $("#deleteReasonBox").removeClass('errbrd');

    $("#specifiedID").show();
    $("#specifyOtherReason2ID").show();
  }

  else if($(this).hasClass('sltOption1'))
  {
    $('input[name="js-offerConsentCheckBox"]').closest('li').addClass("selected");
    offerConsent=1;
    $("#offerCheckBox").show();
    $("#DeleteTextID").html("Submit");
    $("#deleteReasonPrompt").hide();
    $("#deleteReasonBox").removeClass('errbrd');
    $("#specifiedID").hide();
  }
  $("#deleteOptionListID").hide();
});


$('#HideID').bind("click",function() 
  {
     $("#passID1").addClass("vishid");
     $("#passBorderID1").removeClass("errbrd");
    var password = $('#HidePassID').val(); 
    var hideAction=ajaxPassword(profilechecksum,password,'1');
    $('#HidePassID').val('');
  });


$('#DeleteID').bind("click",function() 
  {
    if(!deleteReasonSelected)
    {
      $("#deleteReasonPrompt").show();
      $("#deleteReasonBox").addClass("errbrd");
      return;
    }
    $("#passID").addClass("vishid");
    $("#passBorderID").removeClass("errbrd");
    var password = $('#DeletePassID').val(); 
    var hideAction=ajaxPassword(profilechecksum,password);
    $('#DeletePassID').val('');
  });

});


function bindOfferConsentBox(){

  var element=$('input[name="js-offerConsentCheckBox"]');
    //element.wrap("<span class='custom-checkbox'></span>");
    element.parent().addClass('custom-checkbox');
      
        element.closest('li').addClass("selected");
      
    element.click(function() {
      var liElement=$(this).closest('li');
      if(liElement.hasClass('selected')){
        offerConsent=0;
        liElement.removeClass('selected');

      }
      else{ 
        offerConsent=1;
        liElement.addClass("selected");
      }
    });

offerConsent=1;

}
function ajaxHide(hideDelete)
{
  $.ajax(
                {    

                        beforeSend : function(){
                      $("#hidePartID").addClass("settings-blur");
                       },              
                        url: '/settings/jspcSettings?hideDelete=1',
                        data: "hideDays="+hideDays+"&option="+hideDelete+"&submit=1",
                        //timeout: 5000,
                        success: function(response) 
                        {
                          $("#hidePartID").removeClass("settings-blur");
                          if(response=="HIDE SUCCESS")
                          {
                             $("#headingID").html("Show your Profile");
                             $("#hideDaysID").addClass("disp-none");
                             $("#hideTextID").addClass("disp-none");

                             $("#HideID").html("Show my Profile");
                             $("#HideID").addClass("fontlig");
                             $("#HideID").addClass("f15");
                             $("#showParaID").html("You have chosen to hide your profile for "+hideDays+" days, after which it will be visible to other users again. Use this feature to unhide your profile now.");
                             $("#hideParaID").html("You have chosen to hide your profile for "+hideDays+" days, after which it will be visible to other users again. Use this feature to unhide your profile now.");
                          }
                          else
                          {
                             $("#headingID").html("Hide your Profile");
                             $("#hideDaysID").removeClass("disp-none");
                             $("#hideTextID").removeClass("disp-none");
                             $("#HideID").html("Hide my Profile");
                             $("#HideID").addClass("fontlig");
                             $("#HideID").addClass("f15");
                             $("#hideParaID").html("Use this feature when you have decided to stop looking temporarily since you are busy, moving, in the middle of some big lifestyle changes and cannot spare the time to look seriously.");
                             $("#showParaID").html("Use this feature when you have decided to stop looking temporarily since you are busy, moving, in the middle of some big lifestyle changes and cannot spare the time to look seriously.");
                          }
                        }
                      });
}


function ajaxPassword(checksum,pswrd,hideAction)
{
  
  $.ajax(
                {                 
                        url: '/profile/password_check.php?',
                        data: "hideDelete=1&checksum="+checksum+"&pswrd="+escape(pswrd),
                        //timeout: 5000,
                        success: function(response) 
                        {

                          if(response=="true")
                          {
                            if(hideAction==1)
                             {
                            if(hideUnhide==1)
                            {
                              hideUnhide=0;
                            ajaxHide('Show');
                            
                           }
                            else
                            {
                                hideUnhide=1;
                              ajaxHide('Hide');
                              
                            }
                        }
                        else
                            {
							showLayerCommon('deleteConfirmation-layer');
                              
                            }
                          }
                          else
                          {
                            if(hideAction==1)
                            {
                             $("#passID1").removeClass("vishid");
                             $("#passBorderID1").addClass("errbrd");
                            }
                           else
                           {
                             $("#passID").removeClass("vishid");
                             $("#passBorderID").addClass("errbrd");
                           }
                          }

                        }

                    });
}




function ajaxDelete(optionVal,specifyReason)
{

 //console.log(specifyReason);
  $.ajax(
                {   

                       beforeSend : function(){
                      $("#deletePartID").addClass("settings-blur");
                       },              
                        url: '/settings/jspcSettings?hideDelete=1',
                        data: "deleteReason="+optionVal+"&specifyReason="+specifyReason+"&option=Delete&offerConsent="+(offerConsent?'Y':'N'),
                        //timeout: 5000,
                        success: function(response) 
                        {

                          if(response=="success redirect")
                          window.location.href= "/successStory/layer/?from_delete_profile=1&offerConsent="+(offerConsent?'Y':'N');
                        else
                          window.location.href= "/static/logoutPage";
                        }
                        
                      });
}

function deleteConfirmation(action)
{
		if(action=="Y"){
		var optionVal=$('#delOptionSetID').html();
                              if(optionVal=="I am unhappy about services")
                                specifyReason=$('#specifyOtherReasonID').val();
                              else if(optionVal=="I found my match from other website")
                                specifyReason=$('#specifyLinkID').val();
                              else if(optionVal=="I found my match elsewhere")
                                specifyReason=$('#specifyReasonID').val();
                              else if(optionVal=="Other reasons")
                                specifyReason=$('#specifyOtherReason2ID').val();
                              else
                                specifyReason="";
                              //console.log(optionVal);
                              //console.log(specifyReason);
                              closeCurrentLayerCommon();
                              ajaxDelete(optionVal,specifyReason);
		}
		else
			closeCurrentLayerCommon();
}
