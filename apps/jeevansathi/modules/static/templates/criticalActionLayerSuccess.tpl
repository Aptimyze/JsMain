<script>    var CALID = '~$layerId`'; </script>

~if $layerId == '13'`<script>
    var primaryEmail = '~$primaryEmail`';
     function validateAlternateEmail(altEmail,primaryMail){        
    var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    var email = altEmail.trim();
    var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
    var start = email.indexOf('@');
    var end = email.lastIndexOf('.');
    var diff = end-start-1;
    var user = email.substr(0,start);
    var len = user.length;
    var domain = email.substr(start+1,diff).toLowerCase();
    var chosenUpdateEvent = "chosen:updated";
    var emailVerified ={};
    if(jQuery.inArray(domain.toLowerCase(),invalidDomainArr) !=  -1)
        return false;
    else if(domain == 'gmail')
    {
        if(!(len >= 6 && len <=30))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
    {
        if(!(len >= 4 && len <=32))
        {   

            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'rediff')
    {
        if(!(len >= 4 && len <=30))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    else if(domain == 'sify')
    {
        if(!(len >= 3 && len <=16))
        {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
        }
    }
    if(email=="")
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
    }

    if(!email_regex.test(email))
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Please provide a valid Alternate Email Id";
            return emailVerified;
    }
    //return true;
    if(email.toLowerCase() == primaryMail.toLowerCase())
    {
            emailVerified.valid = false;
            emailVerified.errorMessage = "Alternate and Primary Emails cannot be same";
            return emailVerified;
    }

            emailVerified.valid = true;
            emailVerified.errorMessage = "A link has been sent to your email id "+altEmail+" click on the link to verify your email.";
            return emailVerified;
     
    }

function validateAndSend(){
    
                        var altEmailUser = ($("#altEmailInpCAL").val()).trim();
                        var validation=validateAlternateEmail(altEmailUser,primaryEmail);
                        if(validation.valid!==true)
                        {  

                            $("#errorMessage").addClass('errCL1').html(validation.errorMessage);
                            $("#altEmailInpCAL").css('border','1px solid #d9475c');
                            
                            setTimeout(function(){ 
                                
                                $("#errorMessage").removeClass('errCL1').html("All Emails will also be sent to this Email ID"); 
                            $("#altEmailInpCAL").css('border','1px solid #848285');

                            }, 3000);

                            buttonClicked=0;
                            return;
                        }

                            else
                             {
                             // showLoader();
                             $.ajax({
                                url: '/api/v1/profile/editsubmit?editFieldArr[ALT_EMAIL]='+altEmailUser,
                                headers: { 'X-Requested-By': 'jeevansathi' },       
                                type: 'POST',
                                success: function(response) {
                                    criticalLayerButtonsAction('close','B1');
                                }
                            });
                            $("#altEmailDiv").hide();
                             msg ="A link has been sent to your email Id "+altEmailUser+', click on the link to verify your email';
                             $("#altEmailConfirmText").text(msg);
                             $("#alternateEmailCnfLayer").show();
                               return;
                            }
                        
                      }

</script>
<div id='criticalAction-layer' class="modal3 fontreg">
                <div class="fontlig" id="altEmailDiv">
                    <div class="f16 color11 fontreg bordrBtmGrey" style="padding: 22px 31px;">~$titleText`  <span id="CALButtonB2" class="fr dispibl f15 fontlig" style="cursor: pointer;" onclick="criticalLayerButtonsAction('~$action2`','B2');">Skip</span></div>
                    <div class="padWidget bordrBtmGrey">
                         <div class="txtc fontreg colrGrey f13" style="margin-bottom: -4%">~$contentTextNEW`</div>
                        <div style='margin-top:25px; margin-left: 2%;margin-right: 5%'>
                         <div class="wid500 txtl color5 f12 disp-none" style="position: absolute;top: 86px;" id="CALNameErr">Please provide a valid email address.</div>
                        <input type="text" id="altEmailInpCAL" class="f15 wid90p pa2 txtc" value='~$nameOfUser`' placeholder="Your alternate email" style="">
                        </div>
                        <div class="f11 colrGrey mt5 txtc" id = "errorMessage">~$textUnderInput`</div>
                        <div class="f15 pt20 colrGrey mt5 txtc wid80p" style="margin: 0px auto;">~$subtitle`</div>
                        <button id='CALButtonB1'  onclick="validateAndSend();" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink brdr-0">~$button1TextNEW`</button>
                    </div>
            </div> 
        <div id="alternateEmailCnfLayer" class="phnvwid4 mauto layersZ pos_fix setshare disp-none fullwid bg-white modal3" style="padding-top: 80px;margin-top: 40px">
        <div class="bordrBtmGrey" style="height: 35px">
        <div class="phnvp4 f17 fontreg color11 phnvbdr4" style="margin-top: -38px;margin-left: 5%">Email Verification</div>
        </div>
        <div class="color11">
        <!--start:div-->
        <div class="phnvwid3 mauto pt40 pb27 fontlig">
        <p id='altEmailConfirmText' class="txtc lh26 f15" style="padding-left: 8%;padding-right: 8%"></p>
        </div>
        <button id='CALButtonB4'  onclick="closeCurrentLayerCommon()" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink brdr-0" style="margin-left: 10%;margin-right: 10%;margin-bottom: 30px;width: 80%">OK</button>
        </div>
        <!--end:layer 1-->
        </div> 
</div>

~elseif $layerId == '19'`
<script>    
 function showTimerForLightningCal(lightningCALTime) {
 if(!lightningCALTime) return;
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
     h=h+memTimerExtraDays*24;
     
     m = formatTime(m);
     s = formatTime(s);
     h = formatTime(h);
	
 
   $("#calExpiryMnts").html(m);
   $("#calExpirySec").html(s);
     }
</script>
    
    
  <div id="criticalAction-layer" class="layerMidset setshare layersZ pos_fix calwid1 disp-none" style="display: block;"> 
    <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
        <div class="disp-cell vmid fontlig color11">
            <div class="wid470 mauto">
                <p class="f24">~$titleText`</p>
                <p class="f28 pt20">~$discountPercentage`</p>
        <p class="f24">~$discountSubtitle`</p>
                <p class="pt20 f20">~$startDate`&nbsp<span class="txtstr color12"><span >~$symbol`</span>~$oldPrice`&nbsp</span>  <span>~$symbol`</span>~$newPrice`&nbsp</p>
                <p class="f16 pt20">Hurry! Offer valid for</p>
                <ul class="time">
                  <li class="inscol"><span id = "calExpiryMnts">~$time`</span><span>M</span></li>
                    <li class=""><span id = "calExpirySec">00</span><span>S</span></li>
                </ul>
                
                
                
            </div>
        </div>
    </div>
        <div class="clearfix">
            ~if $button1Text neq ''`<button id='CALButtonB1'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>~/if`
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp ~if $button1Text eq ''`bg_pink calwid1~else` bg6 calwid2 ~/if` f18 colrw txtc fontreg lh61 brdr-0 fl">~$button2Text`</button>
        </div>
</div>

~elseif $layerId == '18'`
 
 <link href="~sfConfig::get('app_img_url')`/min/?f=/~$chosenCss`" rel="stylesheet" type="text/css"/>
 
 
     
     <style type='text/css' >
             .chosenDropWid {width: 230px; padding:10px 6px !important; }
       .occL-wid{width:560px;}
       .occL-p1{padding: 25px 30px}
       .occL-p2{padding: 13px 9px}
       .occ-bdr1{border-bottom: 1px solid #e2e2e2}
       .occ-bdr2{border: 1px solid #d9475c}
       .chosen-container-single .chosen-search input[type="text"]{display: none}
       .chosen-container{border: 1px solid #e2e2e2;padding:10px 0;}
       .occ-pos1{right:0;top:0}
       .dpp-up-arrow {background-position: -2px -31px;width: 14px;height: 11px;}
       .dpp-pos5 {top: -14px;left: 40px;}
 
       /* add this  below class dynamically once you recived the error on .chosen-container */
       .chosen-container-err{border:1px solid #d9475c;}
       .chosen-container-single .chosen-default{color:#34495e;}
 
     </style> 
 
 <div id='criticalAction-layer' class="occL-wid mauto layersZ pos_fix setshare disp-none fullwid bg-white" >
   <div class="f17 fontreg color11">
     <!-- start:header -->
     <div class="occ-bdr1 occL-p1">
       ~$titleText`
     </div>
     <!-- end:header -->
     <div class="occL-p1">
       <p class="opa80">~$contentText`</p>
       <br />
       <p class="opa80">~$subText`</p>
       <!-- start:div for chosen -->
       <div class="pos-rel pt22 mb30 fontlig noMultiSelect" id="parentChosen">  
         <p class="f12 color5 pos-abs disp-none occ-pos1 js-req1">Required</p>    
         <select id="occList" data-placeholder="" class="chosen-select-width">
                     </select>
 
         <!-- start: in case no occupation found -->
         <div class="pt25 vishid js-otheroccInp">
           <p id = 'secondReq' class="f12 disp-none color5 txtr pb5">Required</p>      
           <input  class="wid96p fontlig color11 occL-p2 f16" placeholder="Enter your occupation" type="text"/>
 
         </div>
         <!-- end: in case no occupation found -->
       </div>
       <button id="occ-sub"  class="cursp fullwid bg_pink lh63 txtc f18 fontlig colrw brdr-0">Submit</button>
       <!-- end:div for chosen -->
 
     </div>
   </div>
 
 
 
 
   </div>
   <script type="text/javascript">
           $(".js-otheroccInp input").on('keydown',function(event){
            var self = $(this);
            setTimeout(function(){
              var regex = /[^a-zA-Z. 0-9]+/g; 

             var value = self.val();
             value = value.trim().replace(regex,"");
             if(value != self.val().trim())
               self.val(value);
        },1);
           });

            function callOccupation(){ 
                    $.ajax({
                    url: "/static/getFieldData?k=occupation&dataType=json",
                    type: "GET",
                    success: function(res) {
                        var listArray = res[0];
                        appendOccupationData(listArray);
                        loadChosen(); occfunc();
                    },
                    error: function(res) {
                        $("#listDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
     }

        appendOccupationData = function(res) {
        $("#occList").html('');
        occuSelected = 0;
        occMap = {};
        
        var occIndex=1;
        $("#occList").append('<option class="textTru chosenDropWid" id="notFound" value="'+(occIndex++)+'"></option>');

        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
                if(index1!=43) //  omitting 'others' option
                    $("#occList").append('<option class="textTru chosenDropWid" value="'+(occIndex)+'" occCode = "'+index1+'">' + elem1 +'</option>');
                occMap[occIndex++] = index1;
                });
        });
        $("#occList").append('<option class="textTru chosenDropWid" id="notFound" value="'+(occIndex)+'">I didn\'t find my occupation</option>');
        occLastIndex = occIndex;
        }
   function loadChosen(){
     var config = {
       '.chosen-select'           : {},
       '.chosen-select-deselect'  : {allow_single_deselect:true},
       '.chosen-select-no-single' : {disable_search_threshold:10},
       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
       '.chosen-select-width'     : {width:"100%"},
       '.chosen-select-no-search' : {disable_search:true,width:"100%"},
       '.chosen-select-width-right':{width:"100%"}
     }
     
     
     

     for (var selector in config) {
       $(selector).chosen(config[selector]);
     }
   }
   function showOccSelErr(param){
     if(param=='showErr')
     {
       $('.js-req1').removeClass('disp-none');
       $('.chosen-container').addClass('chosen-container-err');
     }
     else
     {
 
       if( $('.js-req1').css('display')=='block')
       {
         $('.js-req1').addClass('disp-none');
         $('.chosen-container').removeClass('chosen-container-err');
       }
       if( $('.js-otheroccInp').css('visibility')=='visible')
       {
           $('.js-otheroccInp').removeClass('visb');
       }
     }
      
   }
 
   function occfunc(){
      $('#occList').on("change",function(){
 
         var indexV = $('#occList option:selected').val();
 
         if(  $(this).val() == 1  ){
             showOccSelErr('showErr');            
         } 
         else if( $(this).val() == occLastIndex){
             showOccSelErr('hideErr');
             $('.js-otheroccInp').addClass('visb');
             
         }
         else
         {
             showOccSelErr('hideErr');
         }
     });
     $('#occ-sub').click(function(){ 
         if( $('#occList').val() == 1)
         {
           showOccSelErr('showErr');
           return;
         }
         else if( $('#occList').val() == occLastIndex)
         {
           if($(".js-otheroccInp input").val().trim()=='')                   
           { 
           $("#secondReq").removeClass('vishid');
           $(".js-otheroccInp input").addClass('occ-bdr2');
           return;
           }
       }
       else {
                            $(".js-otheroccInp input").val('');
                            var occuCode = occMap[$("#occList").val()];
                            dataOcc = {'editFieldArr[OCCUPATION]':occuCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dataType : 'json',
                            data: dataOcc,
                            success: function(response) {
                                 criticalLayerButtonsAction('~$action1`','B1');


                            },
                            error: function(response) {
                                }
                            });
                        

           
           return;
       }

        
        criticalLayerButtonsAction('~$action1`','B1');
     });
   }
 
 var setscript=document.createElement('script');
 setscript.type='text/javascript';
 setscript.src="~sfConfig::get('app_img_url')`/min/?f=~$chosenJs`";
 setscript.onload = function(){callOccupation();}
 document.head.appendChild(setscript);
 
   </script>

~elseif $layerId == '14'`
<script>
var altEmail = '~$altEmail`';</script>

<div id='criticalAction-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">~$titleText`</p>
                    <p class="f14 pt25 lh22">~$contentText`</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            ~if $button1Text neq ''`<button id='CALButtonB1'  onclick="sendAltVerifyMail()" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>~/if`
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp ~if $button1Text eq ''`bg_pink calwid1~else` bg6 calwid2 ~/if` f18 colrw txtc fontreg lh61 brdr-0 fl">~$button2Text`</button>
        </div>
    </div>
            <div id="alternateEmailSentLayer" class="phnvwid4 mauto layersZ pos_fix setshare disp-none fullwid bg-white modal3" style="padding-top: 80px;margin-top: 40px">
        <div class="bordrBtmGrey" style="height: 35px">
        <div class="phnvp4 f17 fontreg color11 phnvbdr4" style="margin-top: -38px;margin-left: 5%">Email Verification</div>
        </div>
        <div class="color11">
        <!--start:div-->
        <div class="phnvwid3 mauto pt40 pb27 fontlig">
        <p id='altEmailConfirmText' class="txtc lh26 f15" style="padding-left: 8%;padding-right: 8%"></p>
        </div>
        <button id='CALButtonB4'  onclick="criticalLayerButtonsAction('~$action1`','B1')" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink brdr-0" style="margin-left: 10%;margin-right: 10%;margin-bottom: 30px;width: 80%">OK</button>
        </div>
        <!--end:layer 1-->
        </div>

   ~elseif $layerId == '20' || $layerId == '23'`

 <link href="~sfConfig::get('app_img_url')`/min/?f=/~$chosenCss`" rel="stylesheet" type="text/css"/>
      
     <style type='text/css' >
             .chosenDropWid {width: 230px; padding:10px 6px !important; }
       .cityL-wid{width:560px;}
       .cityL-p1{padding: 25px 30px}
       .cityL-p2{padding: 13px 9px}
       .city-bdr1{border-bottom: 1px solid #e2e2e2}
       .city-bdr2{border: 1px solid #d9475c}
       .chosen-container-single .chosen-search input[type="text"]{display: none}
       .chosen-container{border: 1px solid #e2e2e2;padding:10px 0;}
       .city-pos1{right:0;top:0}
       .dpp-up-arrow {background-position: -2px -31px;width: 14px;height: 11px;}
       .dpp-pos5 {top: -14px;left: 40px;}
 
       /* add this  below class dynamically once you recived the error on .chosen-container */
       .chosen-container-err{border:1px solid #d9475c;}
       .chosen-container-single .chosen-default{color:#34495e;}
 
     </style> 
 
 <div id='criticalAction-layer' class="cityL-wid mauto layersZ pos_fix setshare disp-none fullwid bg-white" >
   <div class="f17 fontreg color11">
     <!-- start:header -->
     <div class="city-bdr1 cityL-p1">
       ~$titleText`
     </div>
     <!-- end:header -->
     <div class="cityL-p1">
       <p class="opa80">~$contentText`</p>
       <br />
       <p class="opa80">~$subText`</p>
       <!-- start:div for chosen -->
       <div class="pos-rel pt22 mb30 fontlig noMultiSelect" id="parentChosen">  
         <p class="f12 color5 pos-abs disp-none city-pos1 js-req1">Required</p> <div id = "stateBox">   
         <select id="stateList" data-placeholder="Enter your State" class="chosen-select-width">
                     </select>
          </div>
         <!-- start: in case of no City found -->
         <div class="pt25 disp-none js-otheroccInp" style="padding-bottom:24px;">
           <p id = 'secondReq' class="pb5 f12  color5 txtr">Required</p>
           <div id = "cityBox"> 
            <select id="city" data-placeholder="Enter your City" class="chosen-select-width">
                     </select> 
                     </div>    
         </div>
                  <!-- start: in case no occupation found -->
                  ~if $layerId == '23'`
                  <div class="disp-none" id="otherCityInput">
                             <p id = 'thirdReq' class="f12 disp-none color5 txtr pb5">Required</p>      
         <div id='otherCityBorder' style="border: 1px solid #e0e0e0;line-height: 44px;">
           <input  class="f15 color11 fontlig wid94p occL-p2 f16"     style="margin: 0px 10px;border: none;" placeholder="Please Specify" type="text"/>
 
         </div>
         </div>
        ~/if`
         <!-- end: in case no occupation found -->
       </div>
       <button id="city-sub"  class="cursp fullwid bg_pink lh63 txtc f18 fontlig colrw brdr-0">Submit</button>
       <!-- end:div for chosen -->
 
     </div>
   </div>
 
   </div>
   <script type="text/javascript">

            function callState(){  
                    $.ajax({
                    url: "/static/getFieldData?l=state_res,city_res_jspc,country_res&dataType=json",
                    type: "GET",
                    success: function(res) {
                        if(typeof res == 'string')
                            res = JSON.parse(res);
                        var listArray = res.state_res;
                        appendStateData(listArray);
                        loadChosen(); statefunc(res);
                          },
                    error: function(res) {
                        $("#listDiv").addClass("dn");
                        ShowTopDownError(["Something went wrong"]);
                    }
                });
     }

            function callCity(res){
                        var listArray = res;
                        appendCityData(listArray);
                        loadChosen(); 
                    }                
        

     appendStateData = function(res) {
        $("#stateList").html('');
        occuSelected = 0;
        stateMap = {};
        var stateIndex=1;
        $("#stateList").append('<option class="textTru chosenDropWid stateError" id="notFound" value="'+(stateIndex++)+'"></option>');
        if(CALID==23)
        {
          $("#stateList").append('<option class="textTru chosenDropWid stateError" id="notFound" value="'+(stateIndex)+'">Outside India</option>');
          stateMap[stateIndex++] = "-1";
        }

        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
              $.each(elem1, function(index2, elem2) {
                    $("#stateList").append('<option class="textTru chosenDropWid" value="'+(stateIndex)+'" stateCode = "'+index2+'">' + elem2 +'</option>');
                stateMap[stateIndex++] = index2;
                });
        });
          });
       }

        
        appendCityData = function(res) {  
        $("#stateBox").removeClass('chosen-container-err'); 
        $('.js-req1').addClass('disp-none');
        $("#city").html('');
        var indexV = $('#stateList option:selected').val();

        var keyName = stateMap[indexV];
        cityMap = {};
        cityIndex = 1;
        $("#city").append('<option class="textTru chosenDropWid" id="notFound1" value="'+(cityIndex++)+'"></option>');
        if(keyName=='-1')
        {

              $.each(res.country_res[0], function(index, elem) {
                $.each(elem, function(index2, elem2){  
                    if(index2!='-1' && index2!='51')
                    {
                          $("#city").append('<option class="textTru chosenDropWid" value="'+(cityIndex)+'" cityCode = "'+index2+'">' + elem2 +'</option>');
                        cityMap[cityIndex++] = index2;
                    } 
                      });
              });
                
        }
        else {
              $.each(res.city_res_jspc, function(index, elem) {
               if(index == keyName){
                $.each(elem[0], function(index1, elem1) {  
                  $.each(elem1, function(index2, elem2){  
                        $("#city").append('<option class="textTru chosenDropWid" value="'+(cityIndex)+'" cityCode = "'+index2+'">' + elem2 +'</option>');
                      cityMap[cityIndex++] = index2;
                    });
                });
              }
                  });

        }
        }

   function loadChosen(){
     var config = {
       '.chosen-select'           : {},
       '.chosen-select-deselect'  : {allow_single_deselect:true},
       '.chosen-select-no-single' : {disable_search_threshold:10},
       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
       '.chosen-select-width'     : {width:"100%"},
       '.chosen-select-no-search' : {disable_search:true,width:"100%"},
       '.chosen-select-width-right':{width:"100%"}
     }

     for (var selector in config) {
       $(selector).chosen(config[selector]);
     }  
    $('#city_chosen').removeClass('chosen-container-err');
    $('#secondReq').hide();
    $('#city').trigger("chosen:updated");

   }

   function statefunc(res){
      $('#stateList').on("change",function(){
        var indexV = $('#stateList option:selected').val();
        var keyName = stateMap[indexV];
        if(keyName!='-1') {
          if(CALID==23)
            $("#city").attr('data-placeholder','City');
          else
            $("#city").attr('data-placeholder','Enter Your City');
        }
        else 
          $("#city").attr('data-placeholder','Country');

        $('#otherCityInput').hide();
        $("#stateList_chosen").removeClass('chosen-container-err');
        $('#city_chosen').removeClass('chosen-container-err');
           callCity(res);
          $('#city').val('');
          $('.js-otheroccInp').show();

     });
      if(CALID=='23')
      {
      $('#city').on("change",function(){
        var indexV = $('#stateList option:selected').val();
        var keyName = stateMap[indexV];
        $("#otherCityInput").val('');
        if(keyName!='-1') {
        var indexC = $('#city option:selected').val();
        var keyNameCity = cityMap[indexC];
         if(keyNameCity=='0'){
           $('#otherCityInput').show();
           $("#thirdReq").hide();
         }
         else 
          $('#otherCityInput').hide();
      }
        else $('#otherCityInput').hide();

         });
      }
    }
 
 var setscript=document.createElement('script');
 setscript.type='text/javascript';
 setscript.src="~sfConfig::get('app_img_url')`/min/?f=~$chosenJs`";


 
 window.onload = function(){

  $("#city").change( function(){$('#secondReq').hide();
  $('#city_chosen').removeClass('chosen-container-err');
  $('#otherCityBorder').css('border','1px solid #e0e0e0');

  });
  callState();
   $('#city-sub').click(function(){ 
        var stateCode = stateMap[$("#stateList").val()];
        if(stateCode)
          var cityCode = cityMap[$("#city").val()];

         if( $('#stateList').val() == 1)
         {
           $('.js-req1').removeClass('disp-none');
           $("#stateList_chosen").addClass('chosen-container-err');
           return;
         }
         else if( $('#city').val() == 1 )
         { 
      $('#secondReq').show();
       $('#city_chosen').addClass('chosen-container-err');
           return;   
        }
        else if (CALID==23 && stateCode!='-1' && $("#otherCityInput input").val().trim()=='' && cityCode=='0' )
        {
            $("#thirdReq").show();
            $("#otherCityBorder").css('border-color',"#d9475c");
            return;   

        }

       else {  
                            var tempText = $("#otherCityInput input").val();
                            if(CALID==23){

                            dataCity = stateCode!='-1' ? {'editFieldArr[NATIVE_STATE]':stateCode ,'editFieldArr[NATIVE_CITY]':cityCode,'editFieldArr[NATIVE_COUNTRY]': 51,'editFieldArr[ANCESTRAL_ORIGIN]': tempText } : {'editFieldArr[NATIVE_STATE]':'' ,'editFieldArr[NATIVE_CITY]':'','editFieldArr[NATIVE_COUNTRY]': cityCode };

                            }
                            else 
                            dataCity = {'editFieldArr[COUNTRY_RES]':51 , 'editFieldArr[CITY_RES]':cityCode,'editFieldArr[STATE_RES]':stateCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dataType : 'json',
                            data: dataCity,
                            success: function(response) {
                                 criticalLayerButtonsAction('~$action1`','B1');


                            },
                            error: function(response) {
                                }
                            });
           return;
       }

        
        criticalLayerButtonsAction('~$action1`','B1');
     });
   

 }
 document.head.appendChild(setscript);
 
   </script>
~elseif $layerId == '24'`
<!--================================================================================================================================-->
<style>
#readmoreConsent{
  position: absolute;
  left: 47%;
  margin-top: -3%;
  cursor: pointer;
}
#aadharField{
      color: #34495e;
      height: 30px;
      width: 426px;
      font-size: 20px;
      font-weight: 500;
      padding-left: 6px;
      letter-spacing: 1.2167em;

      margin: auto;
      background: transparent;
      display: block;
      text-align: left;
          
      background: linear-gradient(to right, currentColor 0%,
                                            currentColor calc(100%/2), 
                                            transparent 50%, 
                                            transparent 100%

                                            ) repeat-x left bottom;    

      background-size: 36px 1px;
      border: none;
      /*border-bottom: 1px solid;*/
      /*font-family: monospace;*/
    }
.scrollableCAL{
  max-height: 420px; overflow-y: auto; overflow-x: hidden;
  height: 380px;
}
.scrollbar-thin::-webkit-scrollbar-track
{
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  background-color: #F5F5F5;
}

.scrollbar-thin::-webkit-scrollbar
{
  width: 10px;
  background-color: #F5F5F5;
}

.scrollbar-thin::-webkit-scrollbar-thumb
{
  background-color: #999;
}


.bottom_fade{
  position: relative;
  height: 3em;
  overflow: hidden;
  text-align: center;
}

.bottom_fade:after {
  position: absolute;
  left: 0;
  bottom: 0;
  height: 100%;
  width: 100%;
  content: "";
  background: linear-gradient(to top,
     rgba(230,230,230, 1) 20%, 
     rgba(230,230,230, 0) 80%
  );
  pointer-events: none; /* so the text is still selectable */
} 


.disp-hidden{
  visibility : hidden !important;
}
#aadhar_input{
  padding-top: 4%;
}
#nameInputCAL{
      border:  0;
      border-bottom: 1px solid rgba(63, 72, 79, 0.5);     
      padding: 6px;     
      background: transparent;
}
#nameInputCAL:focus{
  color: #34495e;
}
#TRYAGAINBTN {
    cursor: pointer;
    display: inline-block;
    position: absolute;
    bottom: 11px;
    left: 43%;
}
.collapse-text{
  overflow: hidden;
  height: 2em;
}
#criticalAction-layer-content{
  padding-bottom: 0;
}
</style>
<script>
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
function getpos(El){
    return El.selectionStart;
  }
function setpos(El, pos){
  El.selectionStart = pos;
  El.selectionEnd = pos;
}
function is_numeric(n){
  var patt = new RegExp("^[0-9]");
  return patt.test(n);
}

  
var aadhar = "";

var COUNT = 10;
var COUNTER;
var CALInnerHtml;
var TRYAGAINTXT = "Try Again";
var TryAgainClick = false;
function restoreContent(){
  TryAgainClick = true;
  $("#aadharField").val("");

  $("#cal_content_2").hide();
  $("#cal_content_1").show();
 
  $("#closeButtonCALayer").hide();
  $("#CALButtonB2").show();
  $("#CALButtonB1").show();
  nameErrorObj = null;
  aadharErrorObj = null;
  consentErrorObj = null;

  $("#cal_content_2").html('<div class="extraNumber"><img src="~sfConfig::get("app_img_url")`/images/colorbox/loader_big.gif"></div>');
}

$("#aadharField").keydown(function(e){
  if(![37,38,39,40,8,46, 48,49,50,51,52,53,54,55,56,57].includes(e.which)){
    e.preventDefault();return;
  }
  });

function aadharVerificationApi(aadhar, UserName){
  // var CardHtml = `<div class='extraNumber'><img src="http://trunk.jeevansathi.com/images/colorbox/loader_big.gif"></div>`;
  $("#cal_content_1").hide();
  // $("#cal_content_2").html(CardHtml);
  $("#cal_content_2").show();
  $("#CALButtonB2").hide();
  $("#CALButtonB1").hide();

  var Url = "/api/v1/profile/aadharVerification?name="+UserName+"&aid="+aadhar;
  $.get(Url, function(data){
    if(data.responseStatusCode == 1){
      $("#cal_content_2").hide();
      $("#cal_content_1").show();
      $("#CALButtonB2").show();
      $("#CALButtonB1").show();

      $("#aadharError").text(data.ERROR).removeClass("disp-hidden");
      return false;
    }else if(data.responseStatusCode == 0){
      COUNT = 10;
       // updateCount(COUNT,COUNTER, UserName);
       COUNTER = setInterval(function(){
        updateCount(COUNT, COUNTER, UserName);
        --COUNT;
      }, 1000);
    }
  }, "json");
}

function updateCount(COUNT, COUNTER, UserName){
  if(COUNT <= 0){
    clearInterval(COUNTER);
    if(!TryAgainClick)$("#closeButtonCALayer").show();
        CardHtml = '<div class="mauto wid470" style="margin-top: 20%;">'+"Request Timeout"+'<br><br><span class="f18 fontlig errCL1" onclick="restoreContent();" style="cursor:pointer;">'+TRYAGAINTXT+'</span></div>';
    $("#cal_content_2").html(CardHtml);
    return;
  }
  var CardHtml = '<div class="mauto vertM"><br><div class="f80">'+COUNT+'</div><br><br><br>please wait..</div>';
  $("#cal_content_2").html(CardHtml);
  var Url  = "/api/v1/profile/aadharVerificationStatus?name="+UserName;
  $.get(Url, function(data){
    // clearInterval(COUNTER);
    switch(data.VERIFIED){
      case "Y":
        clearInterval(COUNTER);
        $("#okayButtonCALayer").show();
        CardHtml = '<div class="mauto wid470" style="margin-top: 20%;">'+data.MESSAGE+'</div>';
      break;
      case "N":
        clearInterval(COUNTER);
        if(!TryAgainClick)$("#closeButtonCALayer").show();
        CardHtml = '<div class="mauto wid470" style="margin-top: 20%;">'+data.MESSAGE+'<br><br><span class="f18 fontlig errCL1" onclick="restoreContent();" style="cursor:pointer;">'+TRYAGAINTXT+'</span></div>';
      break;
      case "P" :
        CardHtml = '<div class="mauto vertM"><br><div class="f80">'+COUNT+'</div><br><br><br>please wait..</div>';
      break;
      default:
        clearInterval(COUNTER);
        if(!TryAgainClick)$("#closeButtonCALayer").show();
        CardHtml = '<div class="mauto wid470" style="margin-top: 20%;">'+"Something went wrong."+'<br><br><span class="f18 fontlig errCL1" onclick="restoreContent();" style="cursor:pointer;">'+TRYAGAINTXT+'</span></div>';
        $("#cal_content_2").html(CardHtml);
    return;
      break;
    }
    $("#cal_content_2").html(CardHtml);

 
  }, 'json');
  
  
}
function get_aadharinput(){
  $("#closeButtonCALayer").hide();
  return $("#aadharField").val().split(' ').join('');
  
}

var nameErrorObj, aadharErrorObj, consentErrorObj;
function manageClicks(clickType){
  $("#cal_content_2").html('<div class="extraNumber"><img src="~sfConfig::get("app_img_url")`/images/colorbox/loader_big.gif"></div>');
  if(!nameErrorObj)
    nameErrorObj = $("#nameError");
  if(!aadharErrorObj)
    aadharErrorObj = $("#aadharError");
  if(!consentErrorObj)
    consentErrorObj = $("#consentError");

  nameErrorObj.addClass("disp-hidden");
  aadharErrorObj.addClass("disp-hidden");
  consentErrorObj.addClass("disp-hidden");
  switch(clickType){
    case "CALBUTTON1":
      var aadhar = get_aadharinput();
      if(aadhar.length == 12){
        var UserName = $("#nameInputCAL").val();
        var nameError = validateUserName(UserName);
        if(!nameError.length){
          if($('#' + "consentCheckbox").is(":checked")){
            // $(".scrollableCAL").css({"height": "300px"});
            aadharVerificationApi(aadhar, UserName);
          }else{
            consentErrorObj.removeClass("disp-hidden");
          }
        }else{
          nameErrorObj.text(nameError).removeClass("disp-hidden");
        }
      }else{
        // aadharErrorObj.removeClass("disp-hidden");
        $("#aadharError").text("Provide a valid Aadhar number").removeClass("disp-hidden");
      }
    break;
    case "CALBUTTON2":
      criticalLayerButtonsAction("close"/*clickAction*/,"B2"/*button*/)
    break;
    case "SKIP":
      criticalLayerButtonsAction("close"/*clickAction*/,"B2"/*button*/)
    break;
    case "OKAY":
      criticalLayerButtonsAction("close"/*clickAction*/,"B1"/*button*/)

    break;
  }
}

</script>
<div id="criticalAction-layer" class="layerMidset setshare layersZ pos_fix calwid1 disp-none" style="display: block;">
<div class=" calbg1 fullwid txtc pos-rel scrollbar-thin scrollableCAL">
<div class=" vmid fontlig color11 padalln" id="criticalAction-layer-content">
<div id="cal_content_1">
<div class="wid470 mauto">
<div class="f22">~$titleText`</div>
<div class="f14 lh22">We are moving to a secure platform by verifying Aadhaar of our users. Verify your Aadhar to appear as 'Aadhaar Verified'.<br>Your Aadhaar Number will not be shared with anyone.</div>
<div class="clearfix" id="aadhar_input">
<input type="text" name="" id="aadharField" size="12" maxlength="12" />

</div>
<div id="aadharError" class="bold f11 colrGrey mt5 txtc errCL1 disp-hidden">Provide a valid Aadhaar number</div>
<div class="f13  mt5 txtc pb30">Aadhaar details will be verified by government data</div>
<div  class="f12  mt5 txtc">Your Name (As per Aadhaar Card)</div>
<div class="pos-rel wid300 divcenter">
  <input type="text" id="nameInputCAL" class="f15 wid90p pa2 color11 txtc" value="~$NAME`" placeholder="Your name here">
  <img onclick="$('#nameInputCAL').focus();" src='~sfConfig::get("app_img_url")`/images/jspc/myjsImg/pencil.png' class="pos-abs" style="cursor: pointer;right:9px;top:5px">
</div>
<div id="nameError" class="bold f11 colrGrey mt5 txtc errCL1 disp-hidden">Mention a valid name</div>
  <div id="consentError" class="bold f11 colrGrey mt5 txtc errCL1 fl disp-hidden">Consent is needed to verify</div>

</div>
<div class="clearfix">
  <div style="display: inline-flex;">
  <div class="fl" style="width:5%"><input type="checkbox" id="consentCheckbox" checked="checked"></div>
  <div id="consentText" class="wid94p bottom_fade f13" style="line-height: 1.5; padding-bottom: 15px;text-align: center;cursor: pointer;" onclick="$(this).toggleClass('bottom_fade');$('#readmoreConsent').toggleClass('disp-hidden');">~$calObject.LEGAL_TEXT`
  </div>
  </div>
  <div id="readmoreConsent" onclick="$(this).toggleClass('disp-hidden');$('#consentText').toggleClass('bottom_fade');" class="bold  f11 colrGrey mt5 txtc errCL1" >read more</div>
</div>
</div>
<div id="cal_content_2" class="disp-none"><div class='extraNumber'><img src="http://trunk.jeevansathi.com/images/colorbox/loader_big.gif"></div></div>
</div>
</div>
<div class="clearfix">
<button id="CALButtonB1" onclick="manageClicks('CALBUTTON1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">Verify</button><button id="CALButtonB2" onclick="manageClicks('CALBUTTON2');" class="cursp  bg6 calwid2  f18 colrw txtc fontreg lh61 brdr-0 fl">Not Now</button>
</div>
<button  id='closeButtonCALayer'  class="disp-none cursp bg_pink calwid1  f18 colrw txtc fontreg lh61 brdr-0 fl" onclick="manageClicks('SKIP');">Close</button>
<button  id='okayButtonCALayer'  class="disp-none cursp bg_pink calwid1  f18 colrw txtc fontreg lh61 brdr-0 fl" onclick="manageClicks('OKAY');">Okay</button>
</div>
<input type="hidden" id="CriticalActionlayerId" value="24">
<!--================================================================================================================================-->

~elseif $layerId == '25'`
 
 <link href="~sfConfig::get('app_img_url')`/min/?f=/~$chosenCss`" rel="stylesheet" type="text/css"/>
 
 
     
     <style type='text/css' >
             .chosenDropWid {width: 230px; padding:10px 6px !important; }
       .occL-wid{width:560px;}
       .occL-p1{padding: 25px 30px}
       .occL-p2{padding: 13px 9px}
       .occ-bdr1{border-bottom: 1px solid #e2e2e2}
       .occ-bdr2{border: 1px solid #d9475c}
       .chosen-container-single .chosen-search input[type="text"]{display: none}
       .chosen-container{border: 1px solid #e2e2e2;padding:10px 0;}
       .occ-pos1{right:0;top:0}
       .dpp-up-arrow {background-position: -2px -31px;width: 14px;height: 11px;}
       .dpp-pos5 {top: -14px;left: 40px;}
 
       /* add this  below class dynamically once you recived the error on .chosen-container */
       .chosen-container-err{border:1px solid #d9475c;}
       .chosen-container-single .chosen-default{color:#34495e;}
 
     </style> 
 
 <div id='criticalAction-layer' class="occL-wid mauto layersZ pos_fix setshare disp-none fullwid bg-white" >
   <div class="f17 fontreg color11">
     <!-- start:header -->
     <div class="occ-bdr1 occL-p1">
       ~$titleText`
     </div>
     <!-- end:header -->
     <div class="occL-p1">
       <p class="opa80">~$contentText`</p>
       <br />
       <p class="opa80">~$subText`</p>
       <!-- start:div for chosen -->
       <div class="pos-rel pt22 mb30 fontlig noMultiSelect" id="parentChosen">  
         <p class="f12 color5 pos-abs disp-none occ-pos1 js-req1">Required</p>    
         <select id="occList" data-placeholder="Select Manglik Status" class="chosen-select-width">
                     </select>
 
         
       </div>
       <button id="occ-sub" onclick="onClickActionSubmit" class="cursp fullwid bg_pink lh63 txtc f18 fontlig colrw brdr-0">SUBMIT</button>
       <!-- end:div for chosen -->
 
     </div>
   </div>
 
 
 
 
   </div>
   <script type="text/javascript">
          

    
        appendManglikData = function() {
        $("#occList").html('');
        occuSelected = 0;
        occMap = {};
        res  = {
        // D :"Don't know",
        M : "Manglik",
        A : "Angshik (partial manglik)",
        N : "Non Manglik"};

        var occIndex=1;
        $("#occList").append('<option class="textTru chosenDropWid" id="notFound" value="'+(occIndex++)+'"></option>');
        $.each(res, function(index, elem) {
                      occMap[occIndex] = index;
                    $("#occList").append('<option class="textTru chosenDropWid" value="'+(occIndex++)+'" occCode = "'+index+'">' + elem +'</option>');
        });
        loadChosen();
        }
   function loadChosen(){
     var config = {
       '.chosen-select'           : {},
       '.chosen-select-deselect'  : {allow_single_deselect:true},
       '.chosen-select-no-single' : {disable_search_threshold:10},
       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
       '.chosen-select-width'     : {width:"100%"},
       '.chosen-select-no-search' : {disable_search:true,width:"100%"},
       '.chosen-select-width-right':{width:"100%"},

     }
     
     
     

     for (var selector in config) {
       $(selector).chosen(config[selector]);
     }


     $('.js-overlay').bind('click',function(){$(this).unbind();criticalLayerButtonsAction('close','B2');closeCurrentLayerCommon();});
   }
  
 
      $('#occList').on("change",function(){
        if( $('#occList').val() != 1)
          $('.js-req1').fadeOut();
         
     });


    function onClickActionSubmit(){
         if( $('#occList').val() == 1)
         {
           $('.js-req1').fadeIn();
           return;
         }
         else 
         {
            
                            var occuCode = occMap[$("#occList").val()];
                            dataOcc = {'editFieldArr[MANGLIK]':occuCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dataType : 'json',
                            data: dataOcc,
                            success: function(response) {
                                 criticalLayerButtonsAction('~$action1`','B1');


                            },
                            error: function(response) {
                                }
                            });
                        

           
           return;
       }

        
        criticalLayerButtonsAction('~$action1`','B1');
    }

     $('#occ-sub').click(function(){ 
        onClickActionSubmit();  
     });
 
 var setscript=document.createElement('script');
 setscript.type='text/javascript';
 setscript.src="~sfConfig::get('app_img_url')`/min/?f=~$chosenJs`";
 setscript.onload = function(){appendManglikData();}
 document.head.appendChild(setscript);
 
   </script>

~elseif $layerId != '9'`
<div id='criticalAction-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">~$titleText`</p>
                    <p class="f14 pt25 lh22">~$contentText`</p>
                  ~if $layerId == '26'`
                <p class="f14"><br /><b>Note: </b><span>~$calObject.NOTE_TEXT2`</span></p>
                ~/if`

                </div>            
            </div>

        </div>
        <div class="clearfix">
            ~if $button1Text neq ''`<button id='CALButtonB1'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>~/if`
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp ~if $button1Text eq ''`bg_pink calwid1~else` bg6 calwid2 ~/if` f18 colrw txtc fontreg lh61 brdr-0 fl">~$button2Text`</button>
        </div>
    </div>
~else`
    
<div id='criticalAction-layer' class="modal2 fontreg">
                <div class="fontlig" id="changeNameDiv">
                    <div class="f17 color11 fontreg bordrBtmGrey padWidget">Provide Your Name</div>
                    <div class="padWidget bordrBtmGrey">
                         <div class="txtc fontreg colrGrey f17">~$contentText`</div>
                        <div style='margin-top:25px;'>
                         <div class="wid500 txtl color5 f12 disp-none" style="position: absolute;top: 114px;" id="CALNameErr">Please provide  a valid name</div>
                        <input type="text" id="nameInpCAL" class="f15 wid90p pa2" value='~$nameOfUser`' placeholder="Your name here" style="">
                        </div>
                        <div class="f13 colrGrey mt5 txtc">This field will be screened</div>
                        <div class="radOption f15 color11 mt20">
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow' name="optionSelect" value="showAll" ~if $namePrivacy neq 'N'`checked=""~/if`><i></i> Show my name to all
                            </div>
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow2' name="optionSelect" value="dontShow" ~if $namePrivacy eq 'N'`checked=""~/if`><i></i> Don’t show my name
                            </div>
                        </div>
                        <div id='CALPrivacyInfo' class="~if $namePrivacy neq 'N'`disp-none~/if` f12 mt15 color11 txtc">You will also not be able to see names of other members.</div>
                        <button id='CALButtonB3'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink">~$button1Text`</button>
                    </div>
                    <div class="padWidget f13 colrGrey txtc">We will NEVER show your name to other users without your explicit consent </div>
                </div>
            </div>    
                    <script type="text/javascript">
                                    $("#CALPrivacyShow").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").hide();});
                                    $("#CALPrivacyShow2").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").show();});
                        
                    </script>
                        
~/if`
<input type="hidden" id="CriticalActionlayerId" value="~$layerId`">
