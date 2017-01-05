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
             ~$data.top_heading`
           </div>
           <div class="f16 rv2_colr1 lh25 pt15">~$data.failure_message`</div>
           <div class="pt30 txtc color8">~$data.connect_message`</div>
           <!--start:div-->
           <div class="pt20">
            <div class="rv2_pad4">
              <div class="rv2_brdr1 txtc pad2 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv2_brrad1 fontlig">
                <a id="redirectToCart" href="" class="~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` lh30 dispbl pb10">~$data.try_again`</a>
              </div>
            </div>
          </div>
          <!--end:div-->
          <!--start:div-->
          <div class="pt20">
            <div class="rv2_pad4">
              <div class="rv2_brdr1 txtc pad2 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` rv2_brrad1 fontlig">
               <a href="tel:~$data.toll_free.value`" class="~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` lh30 dispbl pt10">~$data.toll_free.label`</a> 
             </div>
           </div>
         </div>
         <!--end:div-->
       </div>
     </div>
   </div>
   <!--end:div-->
 </div>
</div>

<!--start:main body--> 
<!--start:continue button-->
<div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` f16 fontlig cursp posfix btmo" style="overflow: hidden;">
  <a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" style="display: block;" class="white f15 fontreg pinkRipple rv2_pad9">~$data.proceed_text`</a>
</div>  
<!--end:continue button-->
</div>
<script type="text/javascript">
  var AndroidPromotion = 0;
  $(document).ready(function(){
    var winHeight = $(window).height();
    $(".bg4").css('height',winHeight);
    var newHref = "~sfConfig::get('app_site_url')`/membership/jsms?displayPage=3";
    if(checkEmptyOrNull(readCookie('mainMem')) && checkEmptyOrNull(readCookie('mainMemDur'))){
      newHref += "&mainMem="+readCookie('mainMem')+"&mainMemDur="+readCookie('mainMemDur');
    }
    if(checkEmptyOrNull(readCookie('selectedVas'))){
      newHref += "&selectedVas="+readCookie('selectedVas'); 
    }
    if(checkEmptyOrNull(readCookie('couponID'))){
      newHref += "&couponID="+readCookie('couponID'); 
    }
    if(checkEmptyOrNull(readCookie('device'))){
      newHref += "&device="+readCookie('device'); 
    }
    $("#redirectToCart").click(function(e){
      e.preventDefault();
      createCookie('backState','failurePage');
      window.location.href = newHref;
    });
    var username = "~$data.userDetails.USERNAME`";
    var email = "~$data.userDetails.EMAIL`";
    setInterval(function(){
      autoPopulateFreshdeskDetails(username,email);
    },100);
    setTimeout(function(){
      autoPopupFreshdesk(username,email);
    }, 90000);
  });
</script>
~/if`
