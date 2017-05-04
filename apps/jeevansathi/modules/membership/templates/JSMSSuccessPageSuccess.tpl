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
<meta name="format-detection" content="telephone=no"><div class="fullwid"> 
  <!--start:header-->
  <div class="bg1">
    <div class="rv2_pad1 txtc">
      <div class="posrel white">
        <div class="f19 fontthin">~$data.title`</div>        
      </div>
    </div>
  </div>
  <!--end:header--> 

  <!--start:main body-->
  <div class="rv2_bg1">
    <div class="rv2_pad5"> 
      <!--start:div-->
      <div class="pt10 f16 fontlig">
        <div class="rv2_boxshadow">
          <div class="bg4 rv2_pad3"> 
           <div class="fullwid color7 rv2_brdrbtm1 pb10 rv_ft4 f16 fontmed">
             ~$data.message`
           </div>
           <div class="pt15 rv2_colr1">
             <p>Amount</p>
             <p class="fontreg"><span>~$data.currency`</span>~$data.order_content.amount`</p>
           </div>
           ~if $data.order_content.membership_plan`
           <div class="pt15 rv2_colr1">
             <p>Membership Plan</p>
             <p class="fontreg">~$data.order_content.membership_plan`</p>
           </div>
           ~/if`
           ~if $data.order_content.duration`
           <div class="pt15 rv2_colr1">
             <p>Duration</p>
             <p class="fontreg">~$data.order_content.duration`</p>
           </div>
           ~/if`
           ~if $data.order_content.vas_services`
           <div class="pt15 rv2_colr1">
             <p>Value Added Services</p>
             ~if $data.order_content.vas_services`
             ~foreach from=$data.order_content.vas_services key=k item=v name=vasLoop`
             <p class="fontreg">~$v`</p>
             ~/foreach`
             ~/if`
           </div>
           ~/if`
           <div class="pt15 rv2_colr1">
             <p>Order ID</p>
             <p class="fontreg">~$data.order_content.orderid`</p>
           </div>
           <div class="pt15 rv2_colr1">
             <p>Transaction Date</p>
             <p class="fontreg">~$data.order_content.transaction_date`</p>
           </div>
         </div>
       </div>
     </div>
     <!--end:div-->
   </div>
 </div>
 <!--end:main body--> 

 <!--start:continue button-->
 <div id="continueBtn" class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` f16 fontlig cursp posfix btmo" style="overflow: hidden;">
  <a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" style="display: block;" class="white f15 fontreg pinkRipple rv2_pad9">~$data.proceed_text`</a>
 </div>
 <!--end:continue button-->
</div>

<script type="text/javascript">
  var AndroidPromotion = 0;
  $(document).ready(function(){
    var winHeight = $(window).height();
    var continueHeight = $("#continueBtn").height();
    $('.rv2_bg1').css('height',(winHeight-continueHeight));
    eraseCookie('backState');
    eraseCookie('mainMem');
    eraseCookie('mainMemDur');
    eraseCookie('selectedVas');
    eraseCookie('couponID');
  });
  var username = "~$data.userDetails.USERNAME`";
  var email = "~$data.userDetails.EMAIL`";
  if("~$data.device eq 'Android_app'`"){
      var host = window.location.hostname;
      $("#continueBtn a").attr('href','http://'+host+'/profile/mainmenu.php');
    }
  setTimeout(function(){
    autoPopupFreshdesk(username,email);
  }, 90000);
</script>
~/if`
