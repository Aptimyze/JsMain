<form id=FORM_NAME class=FORM_NAME action="" method="post" onSubmit="false">
<div class="bg4" id="overWrap">
<!--start:div-->
<div class="fullwid bg2 prz" id="overlayHead">
  <div class="pad1">
    <div class="fl wid20p color5 fontlig f12 pad12 opa70" id="CancelSub">Cancel</div>
    <div class="fl wid60p txtc color5 fontlig f14 textTru pad20" id="TAB_HEAD">TABS_NAME</div>   
    <div class="fl wid20p color5 txtr fontlig f12 pad12 opa70" id="SaveSub">Save</div>
    <div class="clr"></div>
  </div>
</div>
<div id="overlayContent" class="bg4">
SECTION_HTML
</div>
<!--end:div--> 
</div>

<div id="overLaySection" class="dp">

<div class="fullwid  brdr1 {{backGroundColor}} {{dhidden}}" id="{{divid}}" {{ehamburgermenu}} {{dmove}} {{dshow}} {{dhide}} {{dselect}} {{dependant}} {{dcallback}}  {{dindex}} {{DBUTTON}}>

  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p NOTFILLED">
          <div class="fl wid9p pt20 dn" id="CboxArrow"> <i class="mainsp arow6"></i> </div>
          <div class="fl wid91p dn" id="cOuter"><div class="fullwid dn" id="CboxDiv" style="padding: 20px 0px 37px 0px;"></div></div>
        <div id="key_label" class="color3 f14 fontlig {{displayDiv}} pb10">TAB_LABEL {{underScreening}}</div>
        {{inputDiv}}
      </div>
        <span id="showAll" rel="{{displaySettingsValue}}" orel="{{displaySettingsValue}}" class="fr fontlig pt15 {{displaySettings}}" onclick="{{ONCLICK_EVENT}}">
              <span id="showText" class="vTop padr5 f14">{{displaySettingsLabel}}</span><i class="iconImg2 iconSprite"></i>
        </span>
      <div class="fr wid4p pt8" style="{{HS}}"> <i class="mainsp {{displayArrow}}  " ></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
</div>
<div class='overlayName' id = "overlayName">
        ~include_partial("profile/mobedit/_showNamesOverlay")`
</div>
</form>
