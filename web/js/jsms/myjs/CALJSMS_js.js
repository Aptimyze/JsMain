
$(document).ready(function() {
	

          
        if($("#CriticalActionlayerId").val()=='16'){
        $('body').css('background-color','#fff');
        appendData(suggestions);            
        }  
else {
        $('body').css('background-color','#09090b');
        if($("#submitName").length && $("#submitName").offset().top-$("#skipBtn").offset().top-70 >0)
        {
              $("#skipBtn").css("margin-top",$("#submitName").offset().top-$("#skipBtn").offset().top-70);
        }
          
    
    
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
        if(CALButtonClicked===1)return;
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
        CALButtonClicked=0;
        
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


        
            
 


        function appendData(obj) {
            if (obj.Description != null || obj.Description != undefined) {
                $("#dppDescription").append(obj.Description);
            }
            $.each(obj.dppData, function(index, elem) {
                if (elem) {
                    if (elem.heading && elem.data) {
             
                        $("#dppSuggestions").append('<div class="brdr1 pad2 dispnone" id="suggest_' + elem.type + '"><div id="heading_' + elem.type + '" class="txtc fontreg pb10 color8 f16">' + elem.heading + '</div></div>');
                        if (elem.range == 0) {
                            $.each(elem.data, function(index2, elem2) {
                                $("#suggest_" + elem.type).removeClass("dispnone").append('<div class="suggestOption brdr18 fontreg txtc color8 f16 dispibl" value="' + index2 + '">' + elem2 + '</div>');
                            });
                        } else if (elem.type == "AGE") {
                            if (elem.data.HAGE != undefined && elem.data.LAGE != undefined) {
                                $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LAGE_HAGE" class="suggestOption suggestOptionRange brdr18 fontreg color8 f16 txtc" value="'+elem.data.LAGE+'_'+elem.data.HAGE+'">' + elem.data.LAGE + 'years - ' + elem.data.HAGE + 'years	</div>');
                            }
                        } else if (elem.type == "INCOME") {
                            if (elem.data.LDS != undefined && elem.data.LDS != null && elem.data.HDS != undefined && elem.data.HDS != null) {
                                $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LDS_HDS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="'+elem.data.LDS+'_'+elem.data.HDS+'">' + elem.data.LDS + ' - ' + elem.data.HDS + '</div>');
                            }
                            if (elem.data.LRS != undefined && elem.data.LRS != null && elem.data.HRS != undefined && elem.data.HRS != null) {
                                $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LRS_HRS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="'+elem.data.LRS+'_'+elem.data.HRS+'">' + elem.data.LRS + ' - ' + elem.data.HRS + '</div>');
                            };
                            if(elem.data.LRS == "No Income" && elem.data.LDS == "No Income" && elem.data.HRS == "and above" && elem.data.HDS == "and above") {
                                $("#LDS_HDS").remove();
                                $("#LRS_HRS").addClass("bothData");
                            }
                        }

                    }
                }
            });
            setTimeout(function() {
                $(".suggestOption").each(function() {
					$(this).off("click").on("click",function(){
						$(this).toggleClass("suggestSelected");
					});
				});
				$("#upgradeSuggestion").on("click",function(){
					if($(".suggestSelected").length == 0) {
						ShowTopDownError(["Please select at least one suggestion."]);
					} else{
						var sendObj = [];
						$("#dppSuggestions").children().each(function(index, element) {
                            var type=$(this).attr("id").split("_")[1],objFinal,valueArr;
							if(type == "AGE" && $("#LAGE_HAGE").hasClass("suggestSelected"))	{
								valueArr = $(this).find(".suggestOptionRange").attr("value");
								objFinal = {"type":type,"data":{"LAGE":valueArr.split("_")[0],"HAGE":valueArr.split("_")[1]}};		
								sendObj.push(objFinal);
							} else if (type == "INCOME") {
								var LDS,HDS,LRS,HRS,dataArr;
								if($("#LDS_HDS").hasClass("suggestSelected") && $("#LRS_HRS").hasClass("suggestSelected") == false) {
									LDS = $("#LDS_HDS").attr("value").split("_")[0],HDS = $("#LDS_HDS").attr("value").split("_")[1];
									dataArr = {"LDS":LDS,"HDS":HDS};
								} else if($("#LRS_HRS").hasClass("suggestSelected") && $("#LDS_HDS").hasClass("suggestSelected") == false){
									if($("#LRS_HRS").hasClass("bothData")) {
                                        dataArr = {"LRS":"No Income","HRS":"and above","LDS":"No Income","HDS":"and above"};
                                    }
                                    else {
                                        LRS = $("#LRS_HRS").attr("value").split("_")[0],HRS = $("#LRS_HRS").attr("value").split("_")[1];
                                        dataArr = {"LRS":LRS,"HRS":HRS};    
                                    }
								} else if($("#LRS_HRS").hasClass("suggestSelected") && $("#LDS_HDS").hasClass("suggestSelected")) {
									LDS = $("#LDS_HDS").attr("value").split("_")[0],HDS = $("#LDS_HDS").attr("value").split("_")[1],LRS = $("#LRS_HRS").attr("value").split("_")[0],HRS = $("#LRS_HRS").attr("value").split("_")[1];
									dataArr = {"LRS":LRS,"HRS":HRS,"LDS":LDS,"HDS":HDS};
								}
								objFinal = {"type":type,"data":dataArr};
								sendObj.push(objFinal);		
							} else{
								valueArr = [];
								$(element).find(".suggestSelected").each(function(index2, element2) {
                                    valueArr.push($(this).attr("value"));
                                });	
								if(valueArr.length != 0) {		
									objFinal = {"type":type,"data":valueArr};
									sendObj.push(objFinal);
								}
							}
                        });
                        var url = JSON.stringify(sendObj).split('"').join("%22");
						 $.ajax({
							url: '/api/v1/profile/dppSuggestionsSaveCAL?dppSaveData='+url,
							type: 'POST',
							success: function(response) {
								criticalLayerButtonsAction('','B1');
							},
							error: function(response) {
							}
						});
					}
				});
                startTouchEvents(1)
            }, 500);

        }

