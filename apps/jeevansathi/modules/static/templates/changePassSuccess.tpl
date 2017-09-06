<div id="changePass" class="outerdiv bg4 posrel" style="height: 473px;"> 
  <!--start:top-->
  <div id="overlayHead" class="bg1 txtc pad15 topheadM">
    <div class="posrel">
      <div class="f20 white fontthin" id="test">Change Password </div>
      <a href="/" bind-slide="2"><i class="mainsp posabs set_arow1 set_pos1"></i></a> </div>
  </div>
  <!--end:top--> 
  <div id="errDiv" style="display: none" class="fullwid set_err_bg txtc white f14 lh40">
  </div>
  <!--start:option-->
  <div class="pad18 bg4 f16 fontlig color13"> 
      <input type="hidden" name="emailStr" id="emailStr" value="~$emailStr`">
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid85p">
        <div class="fontlig f14 color20">Current Password</div>
        <input id="currPwd" type="text" placeholder="Enter current password" class="fontthin color20 f19 wid89p">
      </div>
      <div id="showHide1" style="display: none" class="fr pt15 f14 color1">Hide</div>
    </div>
    <!--end:div--> 
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid85p">
        <div class="fontlig f14 color20">New Password (Min. 8 characters)</div>
        <input id="newPwd" type="text" placeholder="Enter new password" class="fontthin color20 f19 wid89p" maxlength="40">
      </div>
      <div id="showHide2" style="display: none" class="fr pt15 f14 color1">Hide</div>
    </div>
    <!--end:div--> 
    <!--start:div-->
    <div class="clearfix pad12">
      <div class="fl wid85p">
        <div class="fontlig f14 color20">Confirm New Password</div>
        <input id="cnewPwd" type="text" placeholder="Confirm new password" class="fontthin color20 f19 wid89p" maxlength="40">
      </div>
      <div id="showHide3" style="display: none" class="fr pt15 f14 color1">Hide</div>
    </div>
    <!--end:div--> 
  </div>
  <!--end:option--> 
  <!--start:button-->
  <a id="saveBtn" class="posabs fullwid bggrey lh50 txtc fontlig f20 white" style="bottom:0;">Save</a>
  <!--end:button-->
  
</div>