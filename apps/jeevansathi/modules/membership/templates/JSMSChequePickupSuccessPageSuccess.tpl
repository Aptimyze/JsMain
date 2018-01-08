<div class="fullwid"> 
  <!--start:header-->
  <div class="bg1">
    <div class="rv2_pad1 txtc">
      <div class="posrel white">
        <div class="f19 fontthin">~$data.title`</div>
        <div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2"></i></div>        
      </div>
    </div>
  </div>
  <!--end:header--> 
  <!--start:main body-->
	<div class="rv2_bg2 fontlig">
    	<div class="txtc f14 lh40 rv_ft2">~$data.topBlockMessage`</div>
      <!--start:box-->
      <div class="pad1">
          <div class="rv2_bg3 pad3 rv2_colr1 f16 rv2_brrad2">
              <div>~$data.name`</div>
              <div class="pt8">~$data.phoneNo`</div>
              <div class="pt20 lh25">~$data.address`</div>
              <div class="pt20 lh25">~$data.dateTime`</div>
          </div>
      </div>        
      <!--end:box-->
      <div class="rv2_pad13 txtc rv_ft2 color8 lh25">
      	<p>~$data.text1`</p>
        <p>~$data.text2` <span class="fontreg"><span>~$data.currency`</span>~$data.amount`</span>~$data.text3` </p>
      </div>
  </div>
  <!--start:main body--> 
  <!--start:continue button-->
  <div style="overflow:hidden;position: fixed;height: 61px;" class="fullwid disp_b btmo">
  <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 pinkRipple cursp" id="pageBackToPayment">~$data.proceed_text`</div>
  </div>  
  <!--end:continue button-->
 	<input type="hidden" name="mainMembership" value="~$mainMembership`">
</div>
<script type="text/javascript">
  var AndroidPromotion = 0;
  $("#pageBack").click(function(e){
   window.history.back();
  });
  $("#pageBackToPayment").click(function(e){
    var mainMembership =$('[name=mainMembership]').val();
    var paramStr = 'displayPage=5'+mainMembership;
    var url = "/membership/jsms?"+paramStr;
    if(checkEmptyOrNull(readCookie('device'))){
      url += '&device=' + readCookie('device');
    }
    window.location.href = url;
  });
</script>
