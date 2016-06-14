var checksum;
$(document).ready(function(){
  $("#deleteButtonID").bind('click',function() {
    var delete_reason=$("#DeleteReasonID").val();

    parent.location.href= '/static/passCheck?delete_option='+delete_option+'&delete_reason='+delete_reason;
  });

  $("#passCheckID").bind('click',function() {
    var pswrd = $('#passValueID').val();
    ajaxPassword(checksum,pswrd);
  });
});

function ajaxPassword(checksum,pswrd)
{
  $.ajax({                 
    url: '/profile/password_check.php?',
    data: "checksum="+checksum+"&pswrd="+pswrd,
    success: function(response) 
    {
      if(response=="true")
      {
        if(successFlow == 1){
          url = "/successStory/jsmsInputStory";
          parent.location.href = url;
        } else {
          ajaxDelete(delete_reason,delete_option);
        }
      }
      else
      {
        setTimeout(function(){
          ShowTopDownError(["<center>Invalid Password</center>"]);
        },animationtimer);
      }
    }
  });
}

function ajaxDelete(specifyReason,deleteReason)
{
  if($("#offerCheckBox input").is(':checked')) offerConsent='Y';
  else offerConsent='N';
  $.ajax({                 
    url: '/api/v1/settings/deleteProfile',
    data: {"deleteReason":deleteReason,"specifyReason":specifyReason,'offerConsent':offerConsent},
    success: function(response) 
    {
      if(response.output=="Deleted Successfully"){
        parent.location.href= "/static/logoutPage";
      }
      else 
      {
        setTimeout(function(){
          ShowTopDownError(["<center>Something went wrong</center>"]);
        },animationtimer);
      }
    }
  });
}