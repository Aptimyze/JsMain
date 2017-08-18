<script type="text/javascript">
  ~if $logoutCase eq '1'`
  url = window.location.href.replace(window.location.pathname,"/api/v3/membership/membershipDetails");
  console.log(url);
  $.ajax({
    type: 'POST',
    url: url,
    success:function(response){
      CommonErrorHandling(response);
    }
  });
  ~/if`
</script>
~if $logoutCase neq '1'`
<meta name="format-detection" content="telephone=no">
<div class="fullwid posrel" id="DivOuter" style="overflow:hidden">
  <!--start:overlay2-->
  <div id="callOvrTwo" style="display:none;">
    <div class="tapoverlay posfix"></div>
    <div class="posfix btmo fontlig bg4 fullwid" style="z-index:110;">
      <div class="pad19">
        <div class="f14 color13"><i class="mainsp mem_coma"></i>
          <span id="reqCallBackMessage"></span>
          <br>
          <div id="closeOvr2" class="fr f14 pt15 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` cursp" style="padding-bottom:30px;padding-right:10px;">Close</div>
        </div>
      </div>
    </div>
  </div>
  <!--end:overlay2-->
  <!-- Start Overlay credit card -->
  <!--start:overlay-->
  <div class="tapoverlay posabs" style="display:none;" id="tapOverlayHead"></div>
  <!--end:overlay--> 
  <!--start:content overlay-->
  <div class="posabs rv2_pos5" style="display:none;" id="tapOverlayContent"> 
  	<div class="posrel bg4"  id="ContLayer">
      <!--start:top div-->
      <div class="bg1" id="ContHead">
        <div class="rv2_pad1 txtc">
          <div class="posrel white">
            <div class="f19 fontthin" id="topHeading">Select Your Card</div>
            <div class="posabs rv2_pos2" id="backOnCard"><i class="mainsp arow2"></i></div>
          </div>
        </div>
      </div>
      <!--end:top div--> 
      <!--start:middle part-->
      <div id="ContMid" style="overflow:auto">
        <!--start:content-->
        <div class="rv2_pad17" id="ContentDiv">
          <!--start:payment card option-->
          <div class="pt10">
            <div class="rv2_brdr1 color8 rv2_brrad1 fontlig selectedOption" name="payMode" payMode="" selId="" onclick="addPayCard(this);">
              <div class="disptbl fullwid">
                <div class="dispcell rv2_wid8 imgIconId">
                  <div class="rv2_sprtie1" id="ic_id"></div>
                </div>
                <div class="dispcell vertmid pname padl10" id="name">
                </div>
                <div class="dispcell vertmid rv2_wid9">
                  <div class="rv2_sprtie1 options"></div>    
                </div>                    
              </div>                   
            </div>            
          </div>            
          <!--end:payment card option-->
        </div>
        <!--end:content-->
      </div>
      <!--end:middle part-->
      <!--start:button-->
      <div class="posabs btmo fullwid">
       <form name="form1" id="makePaymentForm" action=~sfConfig::get('app_site_url')`/api/v3/membership/membershipDetails?~$mainMembership` method="GET">
         <input type="hidden" name="paymentMode" id="paymentMode"/>
         <input type="hidden" name="cardType" id="cardType">
         <input type="hidden" name="processPayment" value='1'>
         <input type="hidden" name="device" value='~$data.device`'>
         <input type="hidden" name="couponCodeVal" value="~$data.couponID`">
         <input type="hidden" name="couponID" value="~$data.couponID`">
         <input type="hidden" name="mainMembership" value="~$data.tracking_params.mainMembership`">
         <input type="hidden" name="userProfile" value="~$data.userProfile`">
        ~if $data.eSathiFlag eq '1' and $data.backendLink.fromBackend neq '1'`
          <input id="vasImpression" type="hidden" name="vasImpression" value="">
        ~else`
          <input id="vasImpression" type="hidden" name="vasImpression" value="~$data.tracking_params.vasImpression`">
        ~/if`
        ~if $data.upgradeMem && $data.backendLink.fromBackend neq '1'`
        <input type="hidden" name="upgradeMem" value="~$data.upgradeMem`">
        ~/if`
         ~if $data.backendLink.fromBackend eq '1'`
          <input type="hidden" name="backendRedirect" value="~$data.backendLink.fromBackend`">
          <input type="hidden" name="fromBackend" value="~$data.backendLink.fromBackend`">
          <input type="hidden" name="checksum" value="~$data.backendLink.checksum`">
          <input type="hidden" name="profilechecksum" value="~$data.backendLink.profilechecksum`">
          <input type="hidden" name="reqid" value="~$data.backendLink.reqid`">
        ~/if`
         <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
         <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn"> ~$data.proceed_text` </div>
         </div>
       </form>	
     </div>
     <!--end:button--> 
   </div>
   
 </div>
 <!--end:content overlay--> 
 <!--  Ends Overlay credit card  -->	
 
 <!--start:header-->
 <div class="bg1">
  <div class="rv2_pad1 txtc">
    <div class="posrel white">
      <div id="pageTitle" class="f19 fontthin">~$data.title`</div>
      <div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2 cursp"></i></div>
    </div>
  </div>
</div>
<!--end:header--> 

<!--start:main body-->
<div class="rv2_bg1">
  <div class="rv2_pad5"> 
    <!--start:div-->
    <div class="rv2_pad3"> 
      <!--start:total pay div-->
      <div class="disptbl fullwid rv2_brdrbtm3 pb10">
        <div id="cartPaymentText" class="dispcell f16 color7 wid30p">~$data.you_pay_text`</div>
        <div id="cartFinalPrice" class="dispcell txtr f16 wid70p"><span>~if $data.currency eq '$'`USD ~else`~$data.currency`~/if`</span>~$data.you_pay_price`</div>
      </div>
      <!--end:total pay div-->
      <div id="cartTaxText" class="rv2_colr2 fontlig f11 pt5">~$data.tax_text`</div>
    </div>
    <!--end:div--> 
    
    <!--start:div payment modes-->
    ~foreach from=$data.payment_options key=k item=v`
    <div class="pt10">
      <div class="rv2_boxshadow" id="~$v.mode_id`">
        <div class="bg4 rv2_pad3">
         <div class="disptbl fullwid rv2_brdrbtm2">
          <div class="dispcell">
            <div class="rv_ft1 color7 fontmed">~$v.name`</div>
            <div class="fontlig f16 rv2_colr2 pt10 padb5">~$v.hint_text`</div>
          </div>
          <div class="dispcell rv2_wid6 rv2_vb"> <div class="rv2_rec1"></div></div>
        </div>
      </div>
    </div>
  </div>
  ~/foreach`
  <!--end:div payment modes-->
  ~if $data.cash_cheque_pickup`
  <!--start:div-->
  <div class="pt10">
    <div class="rv2_boxshadow">
      <div class="bg4 rv2_pad3" id="cashChequePickup">
        <div class="disptbl fullwid">
          <div class="dispcell">
            <div id="cashChequePickupName" class="rv_ft1 color7 fontmed">~$data.cash_cheque_pickup.name`</div>
            <div id="cashChequePickupHint" class="fontlig f16 rv2_colr2 pt10">~$data.cash_cheque_pickup.hint_text`</div>
          </div>
          <div class="dispcell rv2_wid6 vertmid"> <i class="rv2_sprtie1 rv2_arow2"></i> </div>
        </div>
      </div>
    </div>
  </div>
  <!--end:div--> 
  ~/if`
  <div id="bottomHelpText" class="pad2 txtc fontlig rv_ft2 color8">~$data.bottom_text`</div>

  <!--start:div-->
  <div class="rv2_pad4">
   <div id="directCallButton" class="rv2_brdr1 txtc pad2 color8 rv2_brrad1 fontlig rv_ft2">
     ~if $data.currency eq '$'`
     Call us at <a style="cursor:pointer; color:~if $data.device eq 'Android_app'`#8d1316~else`#d9475c~/if` !important;"href="tel:+911204393500">+911204393500</a>
     ~else`
     Call us at <a style="cursor:pointer; color:~if $data.device eq 'Android_app'`#8d1316~else`#d9475c~/if` !important;"href="tel:18004196299">1800-419-6299</a> (Toll Free)
     ~/if`
   </div>
 </div>
 <!--end:div-->

 <!--start:div-->
 <div class="pt20">
   <div class="rv2_pad4">
     <div id="callButton" class="rv2_brdr1 txtc pad2 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv2_brrad1 fontlig rv_ft2 cursp">
      ~$data.requestCallBack.title`            	
    </div>
  </div>
</div>
<!--end:div-->

<!--start:div-->
<div class="pt20">
 <div class="rv2_pad4">
    ~if !$data.hidePayAtBranchesOption || $data.hidePayAtBranchesOption eq false`
      <div class="rv2_brdr1 txtc pad2  rv2_brrad1 fontlig">
       <div id="payAtBranch" class="~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv_ft2 cursp">~$data.pay_text1`</div>
       <div class="rv_ft3 rv2_colr2">~$data.pay_text2`</div>
      </div>
   ~/if`
 </div>
</div>
<!--end:div-->

</div>
</div>
<!--start:main body--> 

</div>

<script type="text/javascript">
  var AndroidPromotion = 0;
	var topHeading 	=new Array();
	var continueText =new Array();
	var paymentOption =new Array();
  ~foreach from=$data.payment_options key=k item=v`
  topHeading["~$v.mode_id`"] =new Array();
  continueText["~$v.mode_id`"] =new Array();
  topHeading["~$v.mode_id`"]['payment_title'] ="~$v.payment_title`"; 
  continueText["~$v.mode_id`"]['continue_text'] ="~$v.continue_text`";
  paymentOption["~$v.mode_id`"] =new Array();
  ~foreach from=$v.payment_options key=kk item=vv`
  paymentOption["~$v.mode_id`"]["~$kk`"] =new Array();
  ~foreach from=$vv key=kkk item=vvv`
  paymentOption["~$v.mode_id`"]["~$kk`"]["~$kkk`"] ="~$vvv`";
  ~/foreach`
  ~/foreach`
  ~/foreach`
  $("#callButton").click(function(e){
    historyStoreObj.push(clearOverlay,"#overlay");
    $('html, body, #DivOuter').css({
      'overflow': 'auto',
      'height': '100%'
    });
    var paramStr = '~$data.requestCallBack.params`';
    paramStr = paramStr.replace(/amp;/g,'');
    url ="~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails?" + paramStr;

    $.ajax({
      type: 'POST',
      url: url,
      success:function(data){
        response = data;
        $("#reqCallBackMessage").text(data.message);
      }
    });
    $("#callOvrTwo").show();
  });
  $('.tapoverlay').click(function(e){
    $("#callOvrTwo").hide();
    if($('#backOnCard').length){
      $("#backOnCard").trigger('click');
    }
    $('html, body, #DivOuter').css({
      'overflow': 'auto',
      'height': 'auto'
    });
    historyStoreObj.pop();
  });
  $("#closeOvr2").click(function(e){
    $("#callOvrTwo").hide();
    $('html, body, #DivOuter').css({
      'overflow': 'auto',
      'height': 'auto'
    });
    historyStoreObj.pop();
  });
  $("#payAtBranch").click(function(e){
    var url = "~sfConfig::get('app_site_url')`/membership/jsms?displayPage=10";
    var upgradeMem = $('[name=upgradeMem]').val();
    if(checkEmptyOrNull(upgradeMem)){
      url += "&upgradeMem="+upgradeMem;
    }
    if(checkEmptyOrNull(readCookie('device'))){
      url += '&device=' + readCookie('device');
    }
    window.location.href = url;
  });
  $("#pageBack").click(function(e){
      window.history.back();
  });
  var username = "~$data.userDetails.USERNAME`";
  var email = "~$data.userDetails.EMAIL`";
  setInterval(function(){
    autoPopulateFreshdeskDetails(username,email);
  },100);
  setTimeout(function(){
    autoPopupFreshdesk(username,email);
  }, 90000);
  // function to make sure window is resized properly on table view 
  $(window).load(function(){
    var h = $(window).height();
    var b = $('body').height();
    if (h-53 > b) {
      $(".rv2_pad5").css({'height':h-53});
    }
  });
</script>
~/if`
