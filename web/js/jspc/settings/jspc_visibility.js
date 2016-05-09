function ajaxPrivacy(privacyValue) 
{
  $.ajax(
                {                 
                        url: '/settings/jspcSettings?visibility=1&',
                        data: "privacy="+privacyValue,
                        //timeout: 5000,
                        success: function(response) 
                        {
                          
                          if(response[0] == 'A')
                          {
                            $("#VisibleAll").addClass("applied1");
                            $("#VisibleAll").html("Applied");
                            $("#VisibleAll").removeClass("cursp");
                            if(response[1] == 'F')
                            {
                              $("#VisibleCriteria").removeClass("applied1");
                              $("#VisibleCriteria").html("Set");
                              $("#VisibleCriteria").addClass("cursp");
                            }
                            else
                            {
                              $("#VisibleNone").removeClass("applied1");
                              $("#VisibleNone").html("Set");
                              $("#VisibleNone").addClass("cursp");
                            }
                          }
                          else if(response[0] == 'F')
                          {
                            $("#VisibleCriteria").addClass("applied1");
                            $("#VisibleCriteria").html("Applied");
                            $("#VisibleCriteria").removeClass("cursp");
                            if(response[1] == 'A')
                            {
                              $("#VisibleAll").removeClass("applied1");
                              $("#VisibleAll").html("Set");
                               $("#VisibleAll").addClass("cursp");
                            }
                            else
                            {
                              $("#VisibleNone").removeClass("applied1");
                              $("#VisibleNone").html("Set");
                              $("#VisibleNone").addClass("cursp");
                            }
                          }
                          else
                          {
                            $("#VisibleNone").addClass("applied1");
                            $("#VisibleNone").html("Applied");
                            $("#VisibleNone").removeClass("cursp");
                            if(response[1] == 'F')
                            {
                              $("#VisibleCriteria").removeClass("applied1");
                              $("#VisibleCriteria").html("Set");
                              $("#VisibleCriteria").addClass("cursp");
                            }
                            else
                            {
                              $("#VisibleAll").removeClass("applied1");
                              $("#VisibleAll").html("Set");
                              $("#VisibleAll").addClass("cursp");
                            }
                          }
                        }
                });

}
