<div id="resetp" class="outerdiv bg4" style="height: 473px;"> 
  <!--start:header-->
  <div id="overlayHead" class="fullwid bg1">
    <div class="pad5 clearfix white">
        <a href="/" class="white" ><div class="fl f14 fontlig wid20p txtl pt6 txtdec">Cancel</div></a>
      <div class="fl fontthin f19 wid60p txtc">Reset Password</div>
      <div id="saveBtn" class="fl f14 fontlig wid20p txtr pt6 opa50">Save</div>
    </div>
  </div>
  <!--end:header--> 
  <form  action="/common/resetPassword?submitPassword=1" method="POST" >
  <!--start:div-->  
  <div class="fullwid  brdr1">
    <div class="pad18 clearfix frm_ele">
      <div class="fl wid88p">
        <div class="color3 f14 fontlig">New Password</div>
        <input type="hidden" name="emailStr" id="emailStr" value="~$emailStr`">
        <input type="hidden" name="d" id="emailStr" value="~$d`">
        <input type="hidden" name="h" id="emailStr" value="~$h`">
        <div class="pt5"><input type="password" id="password1" name="password1" value="" class="fullwid f19 fontthin color20" placeholder="Minimum 8 characters" maxlength="40"></div>
      </div>
      <div id="showHide1" class="fr wid12p pt15 color1 f14">show</div>      
    </div>
  </div>
  <!--end:div--> 
  <!--start:div-->  
  <div class="fullwid  brdr1">
    <div class="pad18 clearfix frm_ele">
      <div class="fl wid88p">
        <div class="color3 f14 fontlig">Confirm New Password</div>
        <div class="pt5"><input type="password" id="password2" name="password2" value="" class="fullwid f19 fontthin color20" placeholder="Re-enter New Password" maxlength="40"></div>
      </div>
      <div id="showHide2" class="fr wid12p pt15 color1 f14">show</div>      
    </div>
  </div> 
  <!--end:div--> 
  </form>
</div>
