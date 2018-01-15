<div>
  <!--start:top-->
  <div class="bg1 txtc pad15">
    <div class="posrel">
      <div class="fontthin f20 white">Settings</div>
      <a href="/profile/mainmenu.php"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
    </div>
  </div>
  <!--end:top-->
  <!--start:option-->
  <div class="pad18 bg4 f16 fontlig color13">
    ~if $loggedIn`
    <!--start:div-->
    ~if $showNotificationBox eq 1`
    <div class="clearfix pad12">
      <div class="fl wid49p pt15">
          <div class="color13">Notification</div>
      </div>
      <div class="fr" style="width:90px">
        <div class="outerbox posrel clearfix ~if $notificationStatus` ~else` outchange ~/if`">
          <input type="checkbox" checked value="1" class="nothid posabs" id="notifi" name="" />
          <div class="fl setdim1 chkactnot cursp" data-attr="unchk-notifi"></div>
          <div class="fr setdim1 chkactnot cursp" data-attr="chk-notifi"></div>
          <div class="posabs box ~if $notificationStatus` move ~else` notshwd ~/if`"></div>
        </div>
      </div>
    </div>
    ~/if`
    <!--end:div-->
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid94p"><a href="/static/changePass" bind-slide="1" class="color13">Change Password</a></div>
      <div class="fr pt2"><a href="/static/changePass"><i class="mainsp set_arow2"></i></a></div>
    </div>
    <!--end:div-->
    ~/if`
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid94p"><a href="/?desktop=Y" class="color13">Switch to Desktop Site</a></div>
      <div class="fr pt2"><a href="/?desktop=Y"><i class="mainsp set_arow2"></i></a></div>
    </div>
    <!--end:div-->
    ~if $loggedIn`
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid94p"><a href="/static/deleteOption" bind-slide="1" class="color13">Delete Profile</a></div>
      <div class="fr pt2"><a href="/static/deleteOption"><i class="mainsp set_arow2"></i></a></div>
    </div>
    <!--end:div-->
    ~/if`
  </div>
  <!--end:option-->
  <!--start:policy-->
  <div class="txtc set_btmlink pt15">
    <div><a href="/static/page/privacypolicy">Privacy Policy</a><span class="f14 set_color1">•</span><a href="/static/page/disclaimer">Terms of use</a><span class="f14 set_color1">•</span><a href="/static/page/fraudalert">Fraud Alert</a></div>
    ~if $loggedIn`
    <div class="pt50">
      <div>
        <a href="/P/logout.php" onclick="onLogout();">
          <i class="mainsp set_logout"></i>
          <div class="f14">Logout</div>
        </a>
      </div>
    </div>
    ~/if`
  </div>
  <!--end:policy-->
</div>
<script>
    var status = "~$notificationStatus`";
    function onLogout(){
      try{
        sessionStorage.removeItem('myjsTime');
        sessionStorage.removeItem('myjsHtml');
      } catch(e) {
        //console.log(e.stack);
      }
    }
    //console.log(status);
</script>