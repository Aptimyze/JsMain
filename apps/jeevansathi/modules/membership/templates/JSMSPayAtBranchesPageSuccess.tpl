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
<div>
    <div class="fullwid"> 
      <!--start:header-->
      <div class="bg1">
        <div class="rv2_pad1 txtc">
          <div class="posrel white">
            <div class="f19 fontthin">~$data.title`</div>
            <div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2 cursp"></i></div>        
        </div>
    </div>
</div>
<!--end:header--> 
<div id="scrollableContent">
<!--start:main body-->
~foreach from=$data.branches_data key=k item=v name=branchesLoop`
<!--start:adreess main div-->
<div>
    <!--start:div-->
    <div id="~$k`" class="rv2_bg4 fullwid pad16 f16"> 
        <div class="txtc color5 fontlig textTru">~$k`<span class="rv2_arow5"></span></div>		
    </div>    
    <!--end:div-->
    <!--start:address-->
    ~foreach from=$v key=kk item=vv name=branchesLocationLoop`
    <div class="fontlig f16">
        <div class="fullwid pad1 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` lh30">
            ~$vv.NAME`
        </div>
        <div class="fullwid bg4 pad18">
            <div class="clearfix pb10">
                <div class="fl wid10p"><i class="rv2_sprtie1 rv2_set_icons1"></i></div>
                <div class="fl wid90p color3 pt2">~$vv.CONTACT_PERSON`</div>
            </div>
            <div class="clearfix pb10">
                <div class="fl wid10p"><i class="rv2_sprtie1 rv2_set_icons2"></i></div>
                <div class="fl wid90p color3 pt2">~$vv.ADDRESS`</div>
            </div>
            <div class="clearfix pb10">
                <div class="fl wid10p"><i class="rv2_sprtie1 rv2_set_icons3"></i></div>
                <div class="fl wid90p color3 pt2"><div>~$vv.PHONE`</div><div>~$vv.MOBILE`</div></div>
            </div>        
        </div>
    </div>
    ~/foreach`
    <!--end:address-->
</div>
~/foreach`
</div>
<div id="goHome" class="bg4 txtc ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` f16 lh50">~$data.goToHomeText`</div>
<!--start:continue button-->
<div style="overflow:hidden;position: relative;">
<div id="submitBtn" class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 fontlig pinkRipple">~$data.continueText`</div>  
</div>
<!--end:continue button-->
<script type="text/javascript">
    var AndroidPromotion = 0;
    $(document).ready(function(){
        $("#pageBack, #submitBtn").click(function(e){
            window.history.back();
        });
        $("#goHome").click(function(e){
            e.preventDefault();
            window.location.href = "/profile/mainmenu.php";
        });
        ~if $data.userCityRes`
        var scrollPos = $("#scrollableContent").find('#~$data.userCityRes`').offset();
        if(scrollPos){
            $('html, body').animate({
                scrollTop: scrollPos.top
            }, 0);
        }
        ~/if`
    });
</script>
~/if`
