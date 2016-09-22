
$(document).ready(function() {
	$('body').css('background-color','#09090b');
                  if($("#submitName").offset().top-$("#skipBtn").offset().top-70 >0){
              $("#skipBtn").css("margin-top",$("#submitName").offset().top-$("#skipBtn").offset().top-70);
          }

} )
    var CALButtonClicked=0;
    
        function validateUserName(name){        
        var name_of_user=name;
        name_of_user = name_of_user.replace(/\./gi, " ");
        name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
        name_of_user = name_of_user.replace(/\,|\'/gi, "");
        name_of_user = $.trim(name_of_user.replace(/\s+/gi, " "));

        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
        if($.trim(name_of_user)== "" || !allowed_chars.test($.trim(name_of_user))){
                return "Please provide a valid Full Name";
        }else{
                var nameArr = name_of_user.split(" ");
                if(nameArr.length<2){
                      return "Please provide your first name along with surname, not just the first name";
                }else{
                     return true;
                }
        }
       return true;
     
    }
    function criticalLayerButtonsAction(clickAction,button) {
        if(CALButtonClicked)return;
        CALButtonClicked=1;
        var CALParams='';
        var layerId= $("#CriticalActionlayerId").val();
        if(layerId==9 && button=='B1')
                    {   
                        var newNameOfUser='',privacyShowName='';
                        newNameOfUser = ($("#nameInpCAL").val()).trim();
                        var validation=validateUserName(newNameOfUser)
                        if(validation!==true)
                        {
                            showError(validation);
                            CALButtonClicked=0;
                            return;
                        }
                        CALParams="&namePrivacy="+namePrivacy+"&newNameOfUser="+newNameOfUser;
                    }

        
                                   window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button+CALParams; 
                               
        }


        function switchColors(id1,id2){

            $(id1).css('background-color','#d9475c');
            $(id2).css('background-color','#C6C6C6');
        }
        function showError(msg)
        {
                
              $( "#validation_error" ).text(msg);
              $( "#validation_error" ).slideDown( "slow", function() {}).delay( 3000 );
              $( "#validation_error" ).slideUp( "slow", function() {});


        }


        
            
