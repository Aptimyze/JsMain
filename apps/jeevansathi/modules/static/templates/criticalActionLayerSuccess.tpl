
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
           $("#secondReq").show();
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
                            dateType : 'json',
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
   ~elseif $layerId == '20'`

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
         <p class="f12 color5 pos-abs disp-none occ-pos1 js-req1">Required</p> <div id = "stateBox">   
         <select id="occList" data-placeholder="Enter your State" class="chosen-select-width">
                     </select>
          </div>
         <!-- start: in case no occupation found -->
         <div class="pt25 vishid js-otheroccInp">
           <p id = 'secondReq' class="f12 disp-none color5 txtr">Required</p>
           <div id = "cityBox"> 
            <select id="city" data-placeholder="Enter your City" class="chosen-select-width">
                     </select> 
                     </div>    
         </div>
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
                    url: "/static/getFieldData?l=state_res,city_res_jspc&dataType=json",
                    type: "GET",
                    success: function(res) {
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
        $("#occList").html('');
        occuSelected = 0;
        stateMap = {};
        var stateIndex=1;
        $("#occList").append('<option class="textTru chosenDropWid stateError" id="notFound" value="'+(stateIndex++)+'"></option>');

        $.each(res, function(index, elem) {
            $.each(elem, function(index1, elem1) {
              $.each(elem1, function(index2, elem2) {
                    $("#occList").append('<option class="textTru chosenDropWid" value="'+(stateIndex)+'" stateCode = "'+index2+'">' + elem2 +'</option>');
                stateMap[stateIndex++] = index2;
                });
        });
          });
       }

        
        appendCityData = function(res) {  
        $("#stateBox").removeClass('chosen-container-err'); 
        $('.js-req1').addClass('disp-none');
        $("#city").html('');
        var indexV = $('#occList option:selected').val();
        var keyName = stateMap[indexV];
        cityMap = {};
        cityIndex = 1;
        $("#city").append('<option class="textTru chosenDropWid" id="notFound1" value="'+(cityIndex++)+'"></option>');

        $.each(res.city_res_jspc, function(index, elem) {
           if(index == keyName){
            $.each(elem[0], function(index1, elem1) {  console.log(index1);
              $.each(elem1, function(index2, elem2){  console.log(elem2);
                if(index2!=43) //  omitting 'others' option
                    $("#city").append('<option class="textTru chosenDropWid" value="'+(cityIndex)+'" cityCode = "'+index2+'">' + elem2 +'</option>');
                  cityMap[cityIndex++] = index2;
                });
        });
          }
              });
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
    $("#cityBox").removeClass('chosen-container-err');
    $('#secondReq').addClass('disp-none');
    $('#city').trigger("chosen:updated");

   }

   function showOccSelErr(param){
     if(param=='showErr')
     {
       $('.js-req1').removeClass('disp-none');
       $('#stateBox').addClass('chosen-container-err');
     }      
   }

   function statefunc(res){
      $('#occList').on("change",function(){
           callCity(res);
          $('#city').val('');
             $('.js-otheroccInp').addClass('visb');

     });
    }
 
 var setscript=document.createElement('script');
 setscript.type='text/javascript';
 setscript.src="~sfConfig::get('app_img_url')`/min/?f=~$chosenJs`";
 
 window.onload = function(){
  callState();
   $('#city-sub').click(function(){ 
         if( $('#occList').val() == 1)
         {
           showOccSelErr('showErr');
           return;
         }
         else if( $('#city').val() == 1 )
         { 
      $('#secondReq').removeClass('disp-none');
       $('#cityBox').addClass('chosen-container-err');
           return;   
        }

       else {  
                            $(".js-otheroccInp input").val('');
                            var stateCode = stateMap[$("#occList").val()];
                            var cityCode = cityMap[$("#city").val()];
                            
                            dataCity = {'editFieldArr[COUNTRY_RES]':51 , 'editFieldArr[CITY_RES]':cityCode,'editFieldArr[STATE_RES]':stateCode};
                            $.ajax({
                            url: '/api/v1/profile/editsubmit',
                            headers: { 'X-Requested-By': 'jeevansathi' },       
                            type: 'POST',
                            dateType : 'json',
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

~elseif $layerId != '9'`
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
