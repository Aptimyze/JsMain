
$(document).ready(function() {
	$('body').css('background-color','#09090b');
                  if($("#submitName").offset().top-$("#skipBtn").offset().top-70 >0){
              $("#skipBtn").css("margin-top",$("#submitName").offset().top-$("#skipBtn").offset().top-70);
          }

} )
    var CALButtonClicked=0;
    
        function validateUserName(name){
        if(!name)return false;
        
        var arr=name.split('');
        if(/^[a-zA-Z' .]*$/.test(name) == false)return false;
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
                        
                        if(!validateUserName(newNameOfUser))
                        {
                            showError();
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
        function showError()
        {
              $( "#validation_error" ).slideDown( "slow", function() {}).delay( 800 );
              $( "#validation_error" ).slideUp( "slow", function() {});


        }


        
            