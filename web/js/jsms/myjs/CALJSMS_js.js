
var calTimerTime,calTimer;

$(document).ready(function() {
var calIdTemp =$("#CriticalActionlayerId").val(); 
if(calIdTemp=='18'){

    if(isIphone != '1')
    {
        $(window).resize(function()
        {
        $("#occMidDiv").css("height",window.innerHeight - 50);
        $("#occMidDiv").animate({ scrollTop:$('#occInputDiv').offset().top }, 500);
        });	
    }
    occuSelected= 0;

    $("#occInputDiv input").on('keydown',function(event){
        var self = $(this);
        setTimeout(function(){
          var regex = /[^a-zA-Z. 0-9]+/g; 
            
         var value = self.val();
         value = value.trim().replace(regex,"");
         if(value != self.val().trim())
           self.val(value);
        },1);
        
//        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0) && inputValue != 8 && (inputValue != 32 && inputValue != 0) ) { 
//            event.preventDefault(); 
//        }
    } );
    $("#occMidDiv").css("height",window.innerHeight - 50);
    $("#occClickDiv").on("click", function() { 
        if(typeof listArray == 'undefined')
        {      $.ajax({
                    url: "/static/getFieldData?k=occupation&dataType=json",
                    type: "GET",
                    success: function(res) {
                        listArray = res[0];
                        appendOccupationData(listArray);
                    },
                    error: function(res) {
                        $("#listDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
            }
            else appendOccupationData(listArray);
                $("#listDiv").removeClass("dn");
        });

     appendOccupationData = function(res) {
        $("#occList").html('');
        occuSelected = 0;
        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
                if(index1!=43) //  omitting 'others' option
                    $("#occList").append('<li occCode = "'+index1+'">' + elem1 + '</li>');
            });
        });
        $("#occList").append('<li style="margin-bottom: 20px;padding-bottom:25px" id="notFound">I didn\'t find my occupation</li>');
        $("#occList li").each(function(index, element) {
            $(this).bind("click", function() {

                $("#occSelect").html($(this).html());
                $("#occSelect").attr('occCode',$(this).attr('occCode'));
                $("#listDiv").addClass("dn");
                $('#searchOcc').val("");
                $("#occList").html("");
                if ($(this).attr("id") == "notFound") {
                    occuSelected = 0;
                    $("#contText").hide();
                    $("#inputDiv").removeClass("dn");
                    $("#occuText").focus();
                } else {
                    occuSelected = 1;
                    $("#inputDiv").addClass("dn");
                    $("#contText").hide();
                    $(this)
                }
                $("#occupationSubmit").show();
            });
        });
        $("#listLoader").addClass("dn");
        $("#occList").removeClass("dn");
        }

        }


if(calIdTemp=='20' || calIdTemp==23 ){
    if(isIphone != '1')
    {
        $(window).resize(function()
        {
        $("#stateCityMidDiv").css("height",window.innerHeight - 50);
        }); 
    }

    $("#stateCityMidDiv").css("height",window.innerHeight - 50);
    $("#stateClickDiv").on("click", function() { 
        if(typeof listArray == 'undefined')
        {      $.ajax({
                    url: "/static/getFieldData?l=state_res,city_res_jspc,country_res&dataType=json",
                    type: "GET",
                    success: function(res) {
                        listArray = res;
                        appendStateData(listArray);
                    },
                    error: function(res) {
                        $("#stateListDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
            }
            else appendStateData(listArray);
                $("#stateListDiv").removeClass("dn");
        });

    $("#cityClickDiv").on("click", function() {
            callCity(listArray);
            $("#cityListDiv").removeClass("dn");
    });

     appendStateData = function(allRes) {  
        $("#stateList").html('');
        $("#citySelect").html('Select your City');  
        if(typeof allRes == 'string')
            allRes = JSON.parse(allRes);

        res = allRes.state_res;
        if($("#CriticalActionlayerId").val()=='23')
            $("#stateList").append('<li stateCode = "-1">Outside India</li>');        stateMap = {};
         var stateIndex=1;
        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
                $.each(elem1, function(index2, elem2) {
                    $("#stateList").append('<li stateCode = "'+index2+'">' + elem2 + '</li>');
                    stateMap[stateIndex++] = index2;
                    
            });
        });
      });      
   
        $("#stateList li").each(function(index, element) { 
            $(this).bind("click", function() {
                citySelected = false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                $("#stateSelect").html($(this).html());
                $("#stateSelect").attr('stateCode',$(this).attr('stateCode'));
                $("#stateListDiv").addClass("dn");
                $("#stateList").html("");
                $("#inputDiv").hide();
                if($(this).attr('stateCode')=='-1')
                    $("#citySelect").html('Country');  
                else 
                {
                    if(calIdTemp=='23')
                        $("#citySelect").html('City');
                }
                    $("#contText").hide();
                    $("#cityClickDiv").removeClass("dn");
                
                $("#stateCitySubmit").show();
            });

        });
        $("#ListLoader").addClass("dn");
        $("#stateList").removeClass("dn");

        }

        callCity = function(allRes) {

        $("#cityList").html('');
        var cityIndexFromMap  = $("#stateSelect").attr('stateCode');
        if(typeof allRes == 'string')
            allRes = JSON.parse(allRes);
        cityMap = {};
        cityIndex = 2;
        
        occuSelected = 0;
        if(cityIndexFromMap!='-1')
        {
         $.each(allRes.city_res_jspc, function(index, elem) {
           if(index == cityIndexFromMap){
            $.each(elem[0], function(index1, elem1) {  
              $.each(elem1, function(index2, elem2){  
                    $("#cityList").append('<li cityCode = "'+index2+'">' + elem2 + '</li>');
                  
                
                });
        });
          }                                                                                                                                                                                                                                             
              });    
        }
        else {
        $.each(allRes.country_res[0], function(index, elem) {
              $.each(elem, function(index2, elem2){  
                    if(index2!='-1' && index2!='51')
                    $("#cityList").append('<li cityCode = "'+index2+'">' + elem2 + '</li>');
                  
        });
          });

        }

        $("#cityList li").each(function(index, element) {
            $(this).bind("click", function() {  
                citySelected = true;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                var tempHtml = $(this).html();
                $("#citySelect").html(tempHtml);
                if($("#CriticalActionlayerId").val()=='23')
                {
                    if(tempHtml == 'Others' && $("#stateSelect").attr('stateCode')!='-1')
                        $("#inputDiv").show();
                    else {
                        $("#cityInputDiv input").val('');
                        $("#inputDiv").hide();
                    }
                }
                $("#citySelect").attr('cityCode',$(this).attr('cityCode'));
                $("#cityListDiv").addClass("dn");
                $("#cityList").html("");
                    occuSelected = 0;
                    $("#contText").hide();
                $("#stateCitySubmit").show();
            });
        });
        $("#cityListLoader").addClass("dn");
        $("#cityList").removeClass("dn");
        }

        }



else if($("#CriticalActionlayerId").val()=='16'){
        $('body').css('background-color','#fff');
        appendData(suggestions);            
        }
else if($("#CriticalActionlayerId").val()=='19')
{
    $('body').css('background-color','#09090b');
showTimerForLightningCal(1800);
}

else {
        $('body').css('background-color','#09090b');
        if($("#submitName").length && $("#submitName").offset().top-$("#skipBtn").offset().top-70 >0)
        {
              $("#skipBtn").css("margin-top",$("#submitName").offset().top-$("#skipBtn").offset().top-70);
        }
          
    
    
    }
} 
)
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
        if(layerId==18)
                    {   

                        if (occuSelected==1)
                        {
                            var occuCode = $("#occSelect").attr('occCode');
                            dataOcc = {'editFieldArr[OCCUPATION]':occuCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dateType : 'json',
                            data: dataOcc,
                            success: function(response) {
                                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
                                CALButtonClicked=0;

                            },
                            error: function(response) {
                                }
                            });
                        }
                        else if ($("#occInputDiv input").val().trim()!='')
                        {
                            
                            var occupText = $("#occInputDiv input").val();
                            window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button+"&occupText="+occupText; 
                            CALButtonClicked=0;
                            return;
                        }
                        else{

                                showError("Please enter occupation");
                                CALButtonClicked=0;
                                return;


                        }

                    }

                if(layerId==20 || layerId==23)
                    {   
                    var stateCode = $("#stateSelect").attr('stateCode');
                    var cityCode  = $("#citySelect").attr('cityCode');
                    if (citySelected || ( stateCode=='-1' && cityCode=='0'))
                        {

                            if (layerId==23 && stateCode!='-1' && $("#cityInputDiv input").val().trim()=='' && cityCode=='0' )
                            {
                                    showError("Please enter city");
                                    CALButtonClicked=0;
                                    return;
                            }
                            if(layerId==20)
                                dataStateCity = {'editFieldArr[STATE_RES]':stateCode ,'editFieldArr[CITY_RES]':cityCode,'editFieldArr[COUNTRY_RES]': 51 };
                            else
                            {
                                if(stateCode!='-1')
                                    dataStateCity = {'editFieldArr[NATIVE_STATE]':stateCode ,'editFieldArr[NATIVE_CITY]':cityCode,'editFieldArr[NATIVE_COUNTRY]': 51,'editFieldArr[ANCESTRAL_ORIGIN]': $("#cityInputDiv input").val() };
                                else 
                                    dataStateCity = {'editFieldArr[NATIVE_STATE]':'' ,'editFieldArr[NATIVE_CITY]':'','editFieldArr[NATIVE_COUNTRY]': cityCode };
                            }

                        }
                        else{
                                if(stateCode!='-1')
                                    showError("Please select City");
                                else 
                                    showError("Please select Country");
                                CALButtonClicked=0;
                                return;


                        }
                        showLoader();
                        $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dateType : 'json',
                            data: dataStateCity,
                            success: function(response) {
                                hideLoader();
                                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
                                CALButtonClicked=0;

                            },
                            error: function(response) {
                                 hideLoader();   
                                showError('Something went wrong');

                                }
                            });
                        return;
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
						 $.myObj.ajax({
							url: '/api/v1/profile/dppSuggestionsSaveCAL?dppSaveData='+url,
							type: 'POST',
                            channel : 'mobile',
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


       function sendAltVerifyMail()
       {
                 $.ajax({
                    url: '/api/v1/profile/sendEmailVerLink?emailType=2',
                    headers: { 'X-Requested-By': 'jeevansathi' },       
                    type: 'POST',
                    success: function(response) {
                      if(response.responseStatusCode == 1)
                      {
                      showError("Something went wrong");
                      CALButtonClicked=0;
                      return;   
                      }
                 
                $("#altEmailAskVerify").hide();
            msg = "A link has been sent to your email Id "+altEmailUser+', click on the link to verify your email';
                 $("#altEmailMsg").text(msg);
                 $("#confirmationSentAltEmail").show();
                   return; 
                    }
                });              

                



       }


function showTimerForLightningCal(lightningCALTime) {
if(!lightningCALTime) return;
var expiryTime=new Date(lightningCALTime);
var timerSeconds=lightningCALTime%60;
lightningCALTime=Math.floor(lightningCALTime/60);
var timerMinutes=lightningCALTime%60;
lightningCALTime=Math.floor(lightningCALTime/60);
var timerHrs=lightningCALTime;
calTimerTime=new Date();
calTimerTime.setHours(timerHrs);
calTimerTime.setMinutes(timerMinutes);
calTimerTime.setSeconds(timerSeconds);
calTimer=setInterval('updateCalTimer()',1000);
}


function updateCalTimer(){
  var h = calTimerTime.getHours();
  var s = calTimerTime.getSeconds();
  var m = calTimerTime.getMinutes();
  if (!m && !s && !h) {
     clearInterval(calTimer);
     }
  
    calTimerTime.setSeconds(s-1);
    
    
    m = formatTime(m);
    s = formatTime(s);
    h = formatTime(h);
//  $("#calExpiryHrs").html(h);
  $("#calExpiryMnts").html(m);
  $("#calExpirySec").html(s);
    }

    function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function showLoader()
{
    setTimeout(function(){$("#ed_slider").addClass("dn");},100);
    stopTouchEvents(1,1,1);
}

function hideLoader()
{
    setTimeout(function(){$("#ed_slider").removeClass("dn");},100);
    startTouchEvents(1,1,1);
}
