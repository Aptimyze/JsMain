<div class="fullwid outerdiv bg4" id="editScreen" style="display:none;"> 
<form id="phoneVerifyEditForm">
  
  <!--start:div-->
  <div class="fullwid bg2">
    <div class="pad5">
      <a class="fl wid20p color5 fontlig f14 pt5" style="padding: 9px 0px;" onClick="closeEdit();return false;">Cancel</a>
      <div class="fl wid60p txtc color5 f19 fontthin" style="padding: 6px 0px;" id="numberTitle">Mobile Number </div>
      <div class="clr"></div>
    </div>
  </div>
  <!--end:div--> 
       
  <!--start:div-->
  <div class="phn_pl">
    <div class="fullwid pad1p">
<div class="fl color3 f19 fontlig">+ </div>

      <div class="fl wid24p padl3">

      <input type ="tel" class="f19 fontlig color3 fullwid border0" id="ISD" value="" maxlength="4"/>

      </div>

      <div class="fl color3 f19 fontlig">- </div>

      <div class="fl padl10 wid66p">

        <input type ="tel" class="f19 fontlig color3 fullwid border0" id="PHONE_MOB"  value="" maxlength="14" autofocus/>

      </div>
      <div class="clr"></div>
    </div>
  </div>
  <!--end:div--> 
  <div class=" posabs fullwid btmo">
    <div class="pt20"> <a id='js-phoneContinue' onClick="validatePhone();return false;" class="bg7 white lh30 fullwid dispbl txtc lh50">Continue</a> </div>
  </div>
</form>
</div>
