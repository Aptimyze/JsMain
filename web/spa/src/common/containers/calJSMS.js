import React from 'react';
import {removeClass, $i, $c} from './commonFunctions';
class calJSMS extends React.Component{

var calTimerTime,calTimer;


componentDidMount() {

if(this.props.calObj.LAYERID=='18'){

    if(/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)
    {
      this.iosResizeForCAL = () =>        {
              $i("occMidDiv").style.height = window.innerHeight - 50;
              $i("occMidDiv").scrollTop = $i('occInputDiv').getBoundingClientRect().top;
              }

      window.addEventListener('resize',this.iosResizeForCAL);
    }
    occuSelected= 0;

    $i("occInputDivInput").addEventListener('keydown',function(event){
        setTimeout(function(){
          var regex = /[^a-zA-Z. 0-9]+/g;

         var value = this.value;
         value = value.trim().replace(regex,"");
         if(value != this.value.trim())
           this.value = value;
        },1);

//        if(!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0) && inputValue != 8 && (inputValue != 32 && inputValue != 0) ) {
//            event.preventDefault();
//        }
    } );
    $i("occMidDiv").css("height",window.innerHeight - 50);
    $i("occClickDiv").on("click", function() {
        if(typeof listArray == 'undefined')
        {      $.ajax({
                    url: "/static/getFieldData?k=occupation&dataType=json",
                    type: "GET",
                    success: function(res) {
                        listArray = res[0];
                        appendOccupationData(listArray);
                    },
                    error: function(res) {
                        $i("listDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
            }
            else appendOccupationData(listArray);
                $i("listDiv").removeClass("dn");
        });

     appendOccupationData = function(res) {
        $i("occList").html('');
        occuSelected = 0;
        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
                if(index1!=43) //  omitting 'others' option
                    $i("occList").append('<li occCode = "'+index1+'">' + elem1 + '</li>');
            });
        });
        $i("occList").append('<li style = {{"marginBottom: '20px';paddingBottom:'25px'}} id="notFound">I didn\'t find my occupation</li>');
        $i("occList li").each(function(index, element) {
            $(this).bind("click", function() {

                $i("occSelect").html($(this).html());
                $i("occSelect").attr('occCode',$(this).attr('occCode'));
                $i("listDiv").addClass("dn");
                $i('searchOcc').val("");
                $i("occList").html("");
                if ($(this).attr("id") == "notFound") {
                    occuSelected = 0;
                    $i("contText").hide();
                    $i("inputDiv").removeClass("dn");
                    $i("occuText").focus();
                } else {
                    occuSelected = 1;
                    $i("inputDiv").addClass("dn");
                    $i("contText").hide();
                    $(this)
                }
                $i("occupationSubmit").show();
            });
        });
        $i("listLoader").addClass("dn");
        $i("occList").removeClass("dn");
        }

        }

if($i("CriticalActionlayerId").val()=='20'){
    if(isIphone != '1')
    {
        $(window).resize(function()
        {
        $i("cityMidDiv").css("height",window.innerHeight - 50);
        });
    }

    $i("cityMidDiv").css("height",window.innerHeight - 50);
    $i("stateClickDiv").on("click", function() {
        if(typeof listArray == 'undefined')
        {      $.ajax({
                    url: "/static/getFieldData?l=state_res,city_res_jspc&dataType=json",
                    type: "GET",
                    success: function(res) {
                        listArray = res;
                        appendStateData(listArray);
                    },
                    error: function(res) {
                        $i("stateListDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
            }
            else appendStateData(listArray);
                $i("stateListDiv").removeClass("dn");
        });

    $i("cityClickDiv").on("click", function() {
            callCity(listArray);
            $i("cityListDiv").removeClass("dn");
    });

     appendStateData = function(allRes) {
        $i("stateList").html('');
        $i("citySelect").html('Select your City');
        allRes = JSON.parse(allRes);

        res = allRes.state_res;

        stateMap = {};
         var stateIndex=1;
        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
                $.each(elem1, function(index2, elem2) {
                    $i("stateList").append('<li stateCode = "'+index2+'">' + elem2 + '</li>');
                    stateMap[stateIndex++] = index2;

            });
        });
      });

        $i("stateList li").each(function(index, element) {
            $(this).bind("click", function() {
                $i("stateSelect").html($(this).html());
                $i("stateSelect").attr('stateCode',$(this).attr('stateCode'));
                $i("stateListDiv").addClass("dn");
                $i("stateList").html("");

                    $i("contText").hide();
                    $i("cityClickDiv").removeClass("dn");

                $i("stateCitySubmit").show();
            });

        });
        $i("ListLoader").addClass("dn");
        $i("stateList").removeClass("dn");

        }

        callCity = function(allRes) {

        $i("cityList").html('');
        var cityIndexFromMap  = $i("stateSelect").attr('stateCode');

        allRes = JSON.parse(allRes);
        cityMap = {};
        cityIndex = 2;

        occuSelected = 0;
         $.each(allRes.city_res_jspc, function(index, elem) {
           if(index == cityIndexFromMap){
            $.each(elem[0], function(index1, elem1) {
              $.each(elem1, function(index2, elem2){
                    $i("cityList").append('<li cityCode = "'+index2+'">' + elem2 + '</li>');


                });
        });
          }
              });
        $i("cityList li").each(function(index, element) {
            $(this).bind("click", function() {

                $i("citySelect").html($(this).html());
                $i("citySelect").attr('cityCode',$(this).attr('cityCode'));
                $i("cityListDiv").addClass("dn");
                $i("cityList").html("");
                    occuSelected = 0;
                    $i("contText").hide();

                $i("stateCitySubmit").show();
            });
        });
        $i("cityListLoader").addClass("dn");
        $i("cityList").removeClass("dn");
        }

        }



else if($i("CriticalActionlayerId").val()=='16'){
        $('body').css('background-color','#fff');
        appendDataDppSugg(suggestions);
        }
else if($i("CriticalActionlayerId").val()=='19')
{
    $('body').css('background-color','#09090b');
showTimerForLightningCal(1800);
}

else {
        $('body').css('background-color','#09090b');
        if($i("submitName").length && $i("submitName").offset().top-$i("skipBtn").offset().top-70 >0)
        {
              $i("skipBtn").css("margin-top",$i("submitName").offset().top-$i("skipBtn").offset().top-70);
        }



    }
}

    var this.CALButtonClicked=0;

        validateUserName(name){
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
    criticalLayerButtonsAction(clickAction,button) {
        if(this.CALButtonClicked===1)return;
        this.CALButtonClicked=1;
        var CALParams='';
        var layerId= $i("CriticalActionlayerId").val();
        if(layerId==9 && button=='B1')
                    {
                        var newNameOfUser='',privacyShowName='';
                        newNameOfUser = ($i("nameInpCAL").val()).trim();
                        var validation=validateUserName(newNameOfUser)
                        if(validation!==true)
                        {
                            showError(validation);
                            this.CALButtonClicked=0;
                            return;
                        }
                        CALParams="&namePrivacy="+namePrivacy+"&newNameOfUser="+newNameOfUser;
                    }
        if(layerId==18)
                    {

                        if (occuSelected==1)
                        {
                            var occuCode = $i("occSelect").attr('occCode');
                            dataOcc = {'editFieldArr[OCCUPATION]':occuCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },
                            type: 'POST',
                            dateType : 'json',
                            data: dataOcc,
                            success: function(response) {
                                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button;
                                this.CALButtonClicked=0;

                            },
                            error: function(response) {
                                }
                            });
                        }
                        else if ($i("occInputDivInput").val().trim()!='')
                        {

                            var occupText = $i("occInputDivInput").val();
                            window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button+"&occupText="+occupText;
                            this.CALButtonClicked=0;
                            return;
                        }
                        else{

                                showError("Please enter occupation");
                                this.CALButtonClicked=0;
                                return;


                        }

                    }

                if(layerId==20)
                    {
                    if ($i("citySelect").html()!='' && $i("citySelect").html()!='Select your City')
                        {
                            showLoader();
                             var stateCode = $i("stateSelect").attr('stateCode');
                             var cityCode  = $i("citySelect").attr('cityCode');
                            dataStateCity = {'editFieldArr[STATE_RES]':stateCode ,'editFieldArr[CITY_RES]':cityCode,'editFieldArr[COUNTRY_RES]': 51 };
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },
                            type: 'POST',
                            dateType : 'json',
                            data: dataStateCity,
                            success: function(response) {
                                hideLoader();
                                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button;
                                this.CALButtonClicked=0;

                            },
                            error: function(response) {
                                 hideLoader();
                                showError('Something went wrong');

                                }
                            });
                        }
                        else{
                                showError("Please enter City");
                                this.CALButtonClicked=0;
                                return;


                        }

                    }



        window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button+CALParams;
        this.CALButtonClicked=0;


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







        appendDataDppSugg(obj) {
            if (obj.Description != null || obj.Description != undefined) {
                $i("dppDescription").append(obj.Description);
            }
            $.each(obj.dppData, function(index, elem) {
                if (elem) {
                    if (elem.heading && elem.data) {

                        $i("dppSuggestions").append('<div class="brdr1 pad2 dispnone" id="suggest_' + elem.type + '"><div id="heading_' + elem.type + '" class="txtc fontreg pb10 color8 f16">' + elem.heading + '</div></div>');
                        if (elem.range == 0) {
                            $.each(elem.data, function(index2, elem2) {
                                $i("suggest_" + elem.type).removeClass("dispnone").append('<div class="suggestOption brdr18 fontreg txtc color8 f16 dispibl" value="' + index2 + '">' + elem2 + '</div>');
                            });
                        } else if (elem.type == "AGE") {
                            if (elem.data.HAGE != undefined && elem.data.LAGE != undefined) {
                                $i("suggest_" + elem.type).removeClass("dispnone").append('<div id="LAGE_HAGE" class="suggestOption suggestOptionRange brdr18 fontreg color8 f16 txtc" value="'+elem.data.LAGE+'_'+elem.data.HAGE+'">' + elem.data.LAGE + 'years - ' + elem.data.HAGE + 'years	</div>');
                            }
                        } else if (elem.type == "INCOME") {
                            if (elem.data.LDS != undefined && elem.data.LDS != null && elem.data.HDS != undefined && elem.data.HDS != null) {
                                $i("suggest_" + elem.type).removeClass("dispnone").append('<div id="LDS_HDS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="'+elem.data.LDS+'_'+elem.data.HDS+'">' + elem.data.LDS + ' - ' + elem.data.HDS + '</div>');
                            }
                            if (elem.data.LRS != undefined && elem.data.LRS != null && elem.data.HRS != undefined && elem.data.HRS != null) {
                                $i("suggest_" + elem.type).removeClass("dispnone").append('<div id="LRS_HRS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="'+elem.data.LRS+'_'+elem.data.HRS+'">' + elem.data.LRS + ' - ' + elem.data.HRS + '</div>');
                            };
                            if(elem.data.LRS == "No Income" && elem.data.LDS == "No Income" && elem.data.HRS == "and above" && elem.data.HDS == "and above") {
                                $i("LDS_HDS").remove();
                                $i("LRS_HRS").addClass("bothData");
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
				$i("upgradeSuggestion").on("click",function(){
					if($(".suggestSelected").length == 0) {
						ShowTopDownError(["Please select at least one suggestion."]);
					} else{
						var sendObj = [];
						$i("dppSuggestions").children().each(function(index, element) {
                            var type=$(this).attr("id").split("_")[1],objFinal,valueArr;
							if(type == "AGE" && $i("LAGE_HAGE").hasClass("suggestSelected"))	{
								valueArr = $(this).find(".suggestOptionRange").attr("value");
								objFinal = {"type":type,"data":{"LAGE":valueArr.split("_")[0],"HAGE":valueArr.split("_")[1]}};
								sendObj.push(objFinal);
							} else if (type == "INCOME") {
								var LDS,HDS,LRS,HRS,dataArr;
								if($i("LDS_HDS").hasClass("suggestSelected") && $i("LRS_HRS").hasClass("suggestSelected") == false) {
									LDS = $i("LDS_HDS").attr("value").split("_")[0],HDS = $i("LDS_HDS").attr("value").split("_")[1];
									dataArr = {"LDS":LDS,"HDS":HDS};
								} else if($i("LRS_HRS").hasClass("suggestSelected") && $i("LDS_HDS").hasClass("suggestSelected") == false){
									if($i("LRS_HRS").hasClass("bothData")) {
                                        dataArr = {"LRS":"No Income","HRS":"and above","LDS":"No Income","HDS":"and above"};
                                    }
                                    else {
                                        LRS = $i("LRS_HRS").attr("value").split("_")[0],HRS = $i("LRS_HRS").attr("value").split("_")[1];
                                        dataArr = {"LRS":LRS,"HRS":HRS};
                                    }
								} else if($i("LRS_HRS").hasClass("suggestSelected") && $i("LDS_HDS").hasClass("suggestSelected")) {
									LDS = $i("LDS_HDS").attr("value").split("_")[0],HDS = $i("LDS_HDS").attr("value").split("_")[1],LRS = $i("LRS_HRS").attr("value").split("_")[0],HRS = $i("LRS_HRS").attr("value").split("_")[1];
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
                      this.CALButtonClicked=0;
                      return;
                      }
function
                $i("altEmailAskVerify").hide();
            msg = "A link has been sent to your email Id "+altEmailUser+', click on the link to verify your email';
                 $i("altEmailMsg").text(msg);
                 $i("confirmationSentAltEmail").show();
                   return;
                    }
                });





       }


showTimerForLightningCal(lightningCALTime) {
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
//  $i("calExpiryHrs").html(h);
  $i("calExpiryMnts").html(m);
  $i("calExpirySec").html(s);
    }

    function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

showLoader()
{
    setTimeout(function(){addClass($i("ed_slider"),"dn");},100);
    stopTouchEvents(1,1,1);
}

hideLoader()
{
    setTimeout(function(){removeClass($i("ed_slider"),"dn");},100);
    startTouchEvents(1,1,1);
}

componentWillUnmount(){
  window.removeEventListener('resize',this.iosResizeForCAL);
}


getOccupationCal(calObject){

return (<div className="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style={{'top': '0px',backgroundColor: 'rgba(102, 102, 102, 0.5)',zIndex:'104'}}>Please provide a valid email address.</div>
<div style = {{backgroundColor: 'rgb(9, 9, 11)',top: '0',right: '0',bottom: '0',left: '0'}} className="fullheight fullwid posfix">
<div id="occMidDiv" style={{'paddingTop': '20%'}} className="posrel midDiv white">
    <div className="pb10 fontlig f19 txtc">{this.calObject.TITLE}</div>
     <div className="pad0840 txtc fontlig f16">{this.calObject.TEXT}</div>
   <div className="pad0840 txtc fontlig f16">{this.calObject.SUBTEXT}</div>
    <div id="occClickDiv" className="wid90p mar0auto bg4 hgt75 mt30 pad25">
        <div id="occSelect" className="dispibl wid90p color11 fontlig f18 vtop textTru">Select</div>
        <div className="wid8p dispibl">
        <img className="fr" src="~$IMG_URL`/images/jsms/commonImg/arrow.png" /></div>
    </div>
    <div id="contText" className="fontlig f15 mt10 txtc">Select to continue</div>
    <div id="inputDiv" className="mt30 txtc dn">
        <div className="fontlig f15 white">Enter your occupation to continue</div>
        <div id="occInputDiv" className="wid90p mar15auto bg4 hgt75 pad25">
            <input id='occInputDivInput' type="text" className="fullwid fl fontlig f18" placeholder="Enter Occupation" id="occuText" />
        </div>
    </div>
</div>
</div>
<div id="listDiv" className="listDivInner bg4 scrollhid dn" style= {{ 'webkitOverflowScrolling': 'touch'}}>
<div id="listLoader" className="centerDiv"><img src="~$IMG_URL`/images/jsms/commonImg/loader.gif" /></div>
<div className="hgt70 btmShadow selDiv color11 fontlig f18 fullwid">Select</div>
<ul id="occList" className="occList color11 fontlig f18 dn">
</ul>
</div>
<div id="foot" className="posfix fullwid bg7 btmo">
<div className="scrollhid posrel">
    <input type="submit" id="occupationSubmit" className="dispnone fullwid dispbl lh50 txtc f18 white" onclick={this.criticalLayerButtonsAction('','B1')} value="OK" />
</div>
</div>
);

}

getCityCal(calObject){

return (<div className="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style={{top: '0px',backgroundColor: 'rgba(102, 102, 102, 0.5)',zIndex:'104'}}>Please provide a valid email address.</div>
        <div style={{ 'backgroundColor': 'rgb(9, 9, 11)',top: '0',right: '0',bottom: '0',left: '0' }} className="fullheight fullwid posfix">
        <div id="stateCityMidDiv" style={{'paddingTop':'20%'}} className="posrel midDiv white">
            <div className="pb10 fontlig f19 txtc">{this.calObject.TITLE}</div>
             <div className="pad0840 txtc fontlig f16">{this.calObject.TEXT}</div>
           <div className="pad0840 txtc fontlig f16">{this.calObject.SUBTEXT}</div>
            <div id="stateClickDiv" className="wid90p mar0auto bg4 hgt75 mt30 pad25">
                <div id="stateSelect" className="dispibl wid90p color11 fontlig f18 vtop textTru">Select your State</div>
                <div className="wid8p dispibl"><img className="fr" src="~$IMG_URL`/images/jsms/commonImg/arrow.png" /></div>
            </div>
            <div id="contText" className="fontlig f15 mt10 txtc">Select to continue</div>
              <div id="cityClickDiv" className="wid90p mar0auto bg4 hgt75 mt30 pad25 dn">
                <div id="citySelect" className="dispibl wid90p color11 fontlig f18 vtop textTru">Select your City</div>
                <div className="wid8p dispibl"><img className="fr" src="~$IMG_URL`/images/jsms/commonImg/arrow.png" /></div>
            </div>
            </div>
        </div>
    </div>
    <div id="stateListDiv" className="listDivInner bg4 scro`llhid dn" style= {{ 'webkitOverflowScrolling': 'touch'}}>
        <div id="ListLoader" className="centerDiv"><img src="~$IMG_URL`/images/jsms/commonImg/loader.gif" /></div>
        <div className="hgt70 btmShadow selDiv color11 fontlig f18 fullwid">Select</div>
        <ul id="stateList" className="occList color11 fontlig f18 dn">
        </ul>
    </div>

        <div id="cityListDiv" className="listDivInner bg4 scrollhid dn" style= {{ 'webkitOverflowScrolling': 'touch'}}>
        <div id="cityListLoader" className="centerDiv"><img src="~$IMG_URL`/images/jsms/commonImg/loader.gif" /></div>
        <div className="hgt70 btmShadow selDiv color11 fontlig f18 fullwid">Select</div>
        <ul id="cityList" className="occList color11 fontlig f18 dn">
        </ul>
    </div>

    <div id="foot" className="posfix fullwid bg7 btmo">
        <div className="scrollhid posrel">
            <input type="submit" id="stateCitySubmit" className="dispnone fullwid dispbl lh50 txtc f18 white" onclick={this.criticalLayerButtonsAction('','B1')} value="OK" />
        </div>
    </div>

);

}

getAlternateEmailCAL(){

  return (
  <div className="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style={{top: '0px',backgroundColor: 'rgba(102, 102, 102, 0.5)',zIndex:'104'}}>Please provide a valid email address.</div>

<div className="darkBackgrnd" id="altEmailCAL">
<div className="fontlig">
<div style={{padding: '60px 20px 0px 20px'}} className="app_clrw f18 txtc">{this.calObject.TEXTNEW}</div>
<!--    <div className="pad_new2 app_clrw f14 txtc ">{this.calObject.TEXT}</div> -->
<input id='altEmailInpCAL' type="text" className="bg4 lh60 fontthin mt30 f20 fullwid txtc" placeholder="Your alternate email" />
  <div className="pt10 f15 fontlig fullwid txtc colr8A">{this.calObject.TEXTUNDERINPUT}</div>
   <div className="pad_new app_clrw f14 txtc">{this.calObject.SUBTITLE}</div>

  <div id="CALButton" className="f14 fontlig txtc app_clrw colr8A" style={{paddingTop: '115px'}}><span id ="CALButtonB2" onclick="criticalLayerButtonsAction('{this.calObject.ACTION2}','B2');">{this.calObject.BUTTON2NEW}</span></div>

  <div onclick="validateAndSend();" type="submit" id="submitAltEmail" className="fullwid dispbl lh50 txtc f18 btmo posfix bg7 white">{this.calObject.BUTTON1NEW}</div>
</div>

</div>


<div id="confirmationSentAltEmail" className="darkBackgrnd dispnone">
<div className="fontlig">
<div className="pad_new app_clrw f20 txtc" style={{'paddingTop':'12%'}} >Email Verification</div>
<!--    <div className="pad_new2 app_clrw f14 txtc ">{this.calObject.TEXT}</div> -->
   <div className="pad_new app_clrw f14 txtc" id="altEmailMsg" style={{paddingLeft: '20px',paddingRight: '20px'}}></div>
   <div id="CALButtonB3" style={{paddingTop:'55%'}} onclick={this.criticalLayerButtonsAction('{this.calObject.ACTION1NEW}','B1')}  className="pad_new app_clrw f16 txtc">OK</div>
</div>

</div>
);

}


getGenericCAL()
{

  return (
  <div style={{backgroundColor: '#09090b'}}>
  <div  className="posrel pad18Incomplete">
    <div className="br50p txtc" style={{'height':'80px'}}>
      {this.getPhotoForPhotoCAL}
    </div>
  </div>

  <div className="txtc">
  <div className="fontlig white f18 pb10 color16">{this.calObject.TITLE}</div>
  <div className="pad1 lh25 fontlig f14" style={{color:'#cccccc'}}>{this.calObject.TEXT}</div>
  </div>
  {this.getButtonForGenericCAL()}
  </div>
);
}

getPhotoForPhotoCAL(){

if(this.calObject.LAYERID==1)

  return (
      <img id="profilepic" className="image_incomplete" src={this.calObject.genderPhoto} />
  );


  return (<div></div>);

}
getButtonForGenericCAL()
{
if(this.calObject.ACTION1)
{
  return (  <div style={{padding: '25px 0 8% 0'}}>
    <div id='CALButtonB1' className="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="criticalLayerButtonsAction(this.calObject.ACTION1,'B1');">{this.calObject.BUTTON1}</div>
    </div>
    <div id='CALButtonB2' onclick={this.criticalLayerButtonsAction(this.calObject.ACTION2,'B2')} style={{'color:''#cccccc', paddingTop: '12%'}} className="pdt15 pb10 txtc white f14">{this.calObject.BUTTON2}</div>
);
  return(  <div style={{padding: '25px 0 8% 0'}}>
    <div id='CALButtonB2' className="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick={this.criticalLayerButtonsAction(this.calObject.ACTION2,'B2')}>{this.calObject.BUTTON2}</div>
    </div>
);

}
}

getLightningCAL(){

return (<div style={{backgroundColor: '#09090b'}}>
<div  className="posrel pad18Incomplete">

<div className="br50p txtc" style={{ height:'80px'}}>
  </div>

</div>

<div className="txtc">
<div className="fontlig white f20 pb20 color16 ">{this.calObject.TITLE}</div>
<div className="pad1 lh25 fontlig calf27 calcol1">{this.calObject.discountPercentage}</div>
<div className="pad1 lh25 fontlig f20 calcol1 pb20">{this.calObject.discountSubtitle}</div>
<div className="white fontlig f16 pb30">
<span className="" >{this.calObject.startDate} &nbsp</span>
<span className="calcol1 lineth" >{this.calObject.symbol}{this.calObject.oldPrice}</span>&nbsp
<span className="" >{this.calObject.symbol}{this.calObject.newPrice}</span>
</div>
</div>
<div className="white txtc mar0auto pb30" style={{width: '60%'}}>
  <p className="f16 pt20">Hurry! Offer valid for</p>
              <ul className="time">
                <li className="inscol"><span id = "calExpiryMnts">{this.calObject.time}</span><span>M</span></li>
                  <li className="pl10"><span id = "calExpirySec">00</span><span>S</span></li>
              </ul>
</div>
<div style={{padding: '25px 0 8% 0'}}>
<div id='CALButtonB1' className="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick={this.criticalLayerButtonsAction(this.calObject.ACTION1,'B1')}>{this.calObject.BUTTON1}</div>
</div>
<!--end:div-->
<div id='CALButtonB2' onclick={this.criticalLayerButtonsAction(this.calObject.ACTION2,'B2')} style={{color:'#cccccc', paddingTop: '20px'}} className="pdt15 pb10 txtc white f14">{this.calObject.BUTTON2}</div>

</div>);


}



namePrivacyCALButtonClick()
{
let namePrivacy = this.calObject.NAME_PRIVACY;
let temp1 = namePrivacy=='Y' ? 'bg7' : 'bgBtnGrey',
temp2 = namePrivacy=='Y' ? 'bgBtnGrey' : 'bg7',
temp3= namePrivacy=='Y' ? {display:'none'} : {};

return (
<div id='CALPrivacy1' onclick="switchColors('#CALPrivacy1','#CALPrivacy2');$('#hideShowText').hide();namePrivacy='Y';" type="submit" className="dispibl f14 txtc fontlig wid49p brdrRad2 {temp1} lh40 app_clrw">Show my name to all</div>
<div id='CALPrivacy2' onclick="switchColors('#CALPrivacy2','#CALPrivacy1');$('#hideShowText').show();namePrivacy='N';" type="submit" className="dispibl f14 txtc fontlig wid49p brdrRad2 {temp2} lh40 app_clrw mlNeg2">Don't show my name</div>
<div id="hideShowText" style={temp3} className="pt10 f15 fontlig fullwid txtc colr8A">You will not be able to see names of other members.</div>
);
}

getNameCAL(){
return (<div className="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style={{top: '0px',backgroundColor: 'rgba(102, 102, 102, 0.5)','zIndex':'104'}}>Please provide a valid name.</div>

<div className="darkBackgrnd">
<div className="fontlig">
<div className="pad_new app_clrw f20 txtc">Provide Your Name</div>
  <div className="pad_new2 app_clrw f14 txtc ">{this.calObject.TEXT}</div>
<input id='nameInpCAL' value={this.calObject.nameOfUser} type="text" className="bg4 lh60 fontthin mt30 f24 fullwid txtc" placeholder="Your name here">
  <div className="pt10 f15 fontlig fullwid txtc colr8A">This field will be screened</div>
  <div className="mt30 pad_new2 hgt90">
    {this.namePrivacyCALButtonClick()}
  </div>

  <div id="skipBtn" onclick={this.criticalLayerButtonsAction(this.calObject.ACTION2,'B2')}  className="f14 fontlig txtc app_clrw pt35p">{this.calObject.BUTTON2}</div>

  <div onclick={criticalLayerButtonsAction(this.calObject.ACTION1,'B1')} type="submit" id="submitName" className="fullwid dispbl lh50 txtc f18 btmo posfix bg7 white">{this.calObject.BUTTON1}</div>
</div>

</div>);


}


getDPPSuggestions(){

  <div id="overlayHead" className="bg1">
      <div className="txtc pad15">
          <div className="posrel">
              <div className="fontthin f19 white">Desired Partner Profile</div>
              <i id="closeFromDesiredPartnerProfile" className=" posabs mainsp srch_id_cross " style="right:0; top:0px;" onclick={this.criticalLayerButtonsAction('','B2')}></i>
          </div>
      </div>

  </div>

  <div id="overlayMid" className="bg4 pad3 ">
      <div id="mainHeading" className="color8 fontreg f18 txtc pb10">Relax Your Criteria</div>
      <div id="dppDescription" className="txtc color8 fontlig f17"></div>
      <div id="dppSuggestions" className="mb30"></div>
  </div>


  <div id="foot" className="posfix fullwid bg7 btmo">
      <div className="scrollhid posrel">
          <input type="submit" id="upgradeSuggestion" className="fullwid dispbl lh50 txtc f16 pinkRipple white" value="Upgrade Desired Partner Profile" />
      </div>
  </div>




}

}
