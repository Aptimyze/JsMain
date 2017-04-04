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
<div class="fullwid">
  <!--start:header-->
  <div class="bg1">
    <div class="rv2_pad1 txtc">
      <div class="posrel white">
        <div id="pageTitle" class="f19 fontthin">Coupon Code</div>
        <div id="applyBtn" class="posabs rv2_pos1 fontlig cursp">Apply</div>
        <div id="cancelBtn" class="posabs rv2_pos2 fontlig cursp">Cancel</div>
      </div>
    </div>
  </div>
  <!--end:header-->
  <!--start:main body-->
  <div class="bg4">
    <div class="rv2_pad5">
      <div class="pad14 ">
        <center><input autofocus="autofocus" tabindex="0" id="couponId" type="text" placeholder="Type your coupon code..." value="" class="f16 color8 border0 txtl"></center>
      </div>
    </div>
  </div>
  <!--start:main body-->
</div>
<script type="text/javascript">
  var AndroidPromotion = 0;
  var skipVasPageMembershipBased = JSON.parse("~$skipVasPageMembershipBased`".replace(/&quot;/g,'"'));
  var upgradeMem = "~$upgradeMem`";
  
  $(document).ready(function(){
    $(".bg4").css('height', $(window).height()-$(".bg1").height());
    $("#cancelBtn").click(function(e){
      window.history.back();
    });
    $("#applyBtn").click(function(e){
      e.preventDefault();
      var couponID = $("#couponId").val().replace(/^\s+|\s+$/g,'');
      var paramStr = 'validateCoupon=1&couponID='+couponID+'&serviceID='+readCookie('mainMem')+readCookie('mainMemDur')+'&upgradeMem='+upgradeMem;
      paramStr = paramStr.replace(/amp;/g,'');
      url = "~sfConfig::get('app_site_url')`/api/v2/membership/membershipDetails?" + paramStr;
      $.ajax({
        type: 'POST',
        url: url,
        success:function(data){
          response = data;
          //console.log(data);
          if(data.success_code!=1){
            ShowTopDownError([""+data.message+""]);
          }
          else{
            createCookie('couponID', couponID);
            if($.inArray(readCookie('mainMem'),skipVasPageMembershipBased)>-1){
              createCookie('backState', "couponMain");
            } else {
              createCookie('backState', "couponVas");
            }
            if(checkEmptyOrNull(readCookie('selectedVas'))){
              paramStr = "displayPage=3&mainMem="+readCookie("mainMem")+"&mainMemDur="+readCookie("mainMemDur")+"&selectedVas="+readCookie('selectedVas')+"&couponID="+couponID;  
            } else {
              paramStr = "displayPage=3&mainMem="+readCookie("mainMem")+"&mainMemDur="+readCookie("mainMemDur")+"&selectedVas="+"&couponID="+couponID+"&upgradeMem="+upgradeMem;
            }
            url = "/membership/jsms?" + paramStr;
            if(checkEmptyOrNull(readCookie('device'))){
              url += '&device=' + readCookie('device');
            }
            window.location.href = url;
          } 
        }
      });
    });
  });
</script>
~/if`