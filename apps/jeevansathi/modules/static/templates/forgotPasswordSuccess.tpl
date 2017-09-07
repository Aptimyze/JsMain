<div id="forgotp" class="outerdiv bg4"> 
  <!--start:header-->
  <div id="overlayHead" class="fullwid bg1">
   	<div class="pad5 clearfix white">
            <a href="/" bind-slide="2" class="white" onclick='GAMapper("GA_FORGOT_CANCEL");'><div class="fl f14 fontlig wid20p txtl pt6">Cancel</div></a>
        <div class="fl fontthin f19 wid60p txtc">Forgot Password</div>
    </div>
  </div>  
  <!--end:header--> 
  <form onsubmit="return false">
  <!--start:div-->
  <div class="pad5 frm_ele">
      <textarea id="useremail" name="in_field" class="fullwid r_f1 fontthin color20" placeholder="Enter your registered Email ID or Primary Mobile Number"></textarea>
  </div>  
  <!--end:div-->
  <!--start:button-->
  <div class="posfix btmo fullwid bg7">
      <a id="sendLink" class="dispbl lh50 txtc white" onclick='GAMapper("GA_FORGOT_RESET");'>Email/Sms Link To Reset</a>
  </div>
  <!--end:button-->
  </form>
</div>