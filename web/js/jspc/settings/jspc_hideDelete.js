var hideDays = 7;
$(document).ready(function() {
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
  $("specifiedID").addClass("disp-none");
  $("#specifyReasonID").addClass("disp-none");
  $("#specifyLinkID").addClass("disp-none");
  $("#specifyOtherReasonID").addClass("disp-none");
  $("#specifyOtherReason2ID").addClass("disp-none");
  $("#deleteOptionListID").show();
});

$(".sltOption").bind('click', function()
{
  var optionVal=$(this).html();
  $('#delOptionSetID').html(optionVal);
  if(optionVal=="I found my match elsewhere")
  {
    $("#DeleteTextID").html("Delete my Profile");
    $("#specifiedID").removeClass("disp-none");
    $("#specifyReasonID").removeClass("disp-none");
  }
  else if(optionVal=="I am unhappy about services")
  {
    $("#DeleteTextID").html("Delete my Profile");
    $("#specifiedID").removeClass("disp-none");
    $("#specifyOtherReasonID").removeClass("disp-none");
  }
  else if(optionVal=="I found my match from other website")
  {
    $("#DeleteTextID").html("Delete my Profile");
    $("#specifiedID").removeClass("disp-none");
    $("#specifyLinkID").removeClass("disp-none");
  }
  else if(optionVal=="I found my match on Jeevansathi.com")
  {
    $("#DeleteTextID").html("Submit");
    $("#specifiedID").addClass("disp-none");
  }
  else
  {
    $("#DeleteTextID").html("Delete my Profile");
    $("#specifiedID").removeClass("disp-none");
    $("#specifyOtherReason2ID").removeClass("disp-none");
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
     $("#passID").addClass("vishid");
     $("#passBorderID").removeClass("errbrd");
    var password = $('#DeletePassID').val(); 
    var hideAction=ajaxPassword(profilechecksum,password);
    $('#DeletePassID').val('');
    });

});

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
                        data: "checksum="+checksum+"&pswrd="+pswrd,
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
                              ajaxDelete(optionVal,specifyReason);
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
                        data: "deleteReason="+optionVal+"&specifyReason="+specifyReason+"&option=Delete",
                        //timeout: 5000,
                        success: function(response) 
                        {

                          if(response=="success redirect")
                          window.location.href= "/successStory/layer/?from_delete_profile=1";
                        else
                          window.location.href= "/static/logoutPage";
                        }
                        
                      });
}
