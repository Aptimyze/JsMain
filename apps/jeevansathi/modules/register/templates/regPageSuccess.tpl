<input type="hidden" name="leadid" id ="leadid" value="~$leadid`">
<input type="hidden" name="groupname" id="groupname" value="~$groupname`">
<div class="fullwid bg_1" id="mainRegContent"> 
  <!--start:overlay-->
  <div class="pos_fix fullwid layersZ reg1pos1 js-regOverlayMsg disp-none">
    <div class="wid36p mauto reg1bg1" >
    	<div class="padall-10">
        	<div class="txtr fontreg">
            	<i class="sprite2 reg1close cursp js-regOverlayClose"></i>
                <p class="txtc color2 f15 regMsgPad">You may access and update previous information after registration is complete</p>
            </div>
        </div>    
    </div>
  </div>
  <!--end:overlay-->
  <!--start:header-->
  ~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename])`
  ~include_partial("global/JSPC/_jspcCommonMemRegHeader",['PAGE'=>$pageObj->getPageName()])`

  <!--end:header--> 
  <!--start:body-->
  <div>
    <div class="container reg_wid1" > 
        <!--start: registration Forms-->
        ~include_partial("register/forms/$formName",[pageObj=>$pageObj,templateVars=>$templateVars,groupname=>$groupname])`
        <!--end: registration Forms-->
    </div>
  </div>
  <!--end:body--> 
  <!--start:gapping div-->
  <div class="clr hgt200"></div>
  <!--end:gapping div--> 
  <!--start:footer-->
 ~include_partial("global/JSPC/_jspcCommonFooter",[pixelcode=>$pixelcode])`
  <!--end:footer--> 
</div>
<input type="hidden" value="~$templateVars['gender_value']`" id="gender_value" />
~foreach from=$templateVars key=k item=v`
  <input type="hidden" value="~$v`" id="~$k`" />
~/foreach`

<ul class="rlist cpfopt disp-none" id="radio-list">
  <li class="gridfcolor" id="{{customId}}" data-dbVal="{{cusDbVal}}">{{customValue}}</li>
  {{newLi}}
</ul>
<ul class="rlist disp-none" id="other-list">
  <li class="gridfcolor" style="padding:14px" id="{{customId}}" data-dbVal="{{cusDbVal}}">{{customValue}}</li>
  {{newLi}} 
</ul>
<div id="inputBoxDummy" class="disp-none">
    <span class="reg_wid2 fr brdr-0 fontspinp pos_abs pos1_inp" id="spanDummy" type="text">{{customValue}}</span>
</div>
<div id="selected-list" class="disp-none">
    <ul id="{{cusId}}-selected-list" class="rlist {{cusId}}opt">
    </ul>
</div>
<ul id="dobDummy" class="disp-none">
   <li id="{{cusId}}">{{customValue}}</li>
   {{newLi}}
</ul>
<!--start:drop down-->
<div class="disp-none" id="gridDummy">
<div class="pos_abs bg-white brdr-1 t1 reg-zi1 reg-wid3 showdd">
  <div class="pos_rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow test-pos3 reg-zi100"></i>
    <div class="reg-zi1 regpad2 fullwid optlist scrolla gridfcolor reg-hgt200" id="multipleUls">
      <ul class="clearfix searchUl" id="gridUlDummy">
        <li class="lh30" id="{{customId}}" data-dbVal="{{cusDbVal}}" data-style >
          <div class="pl15 textTru">{{customValue}}</div>
        </li>
        {{newLi}}
      </ul>
    </div>
  </div>
</div>
</div>
<!--end:drop down--> 
<div id="alphaDiv" class="disp-none">
<div class="fontrobbold f13 brdrb-4 pl15 pt10  isGroupheading" data-dbVal="-1">{{cusHeading}}</div>
</div>
<script>
    
var dataArray = null; 
var pageId = null;
var nextPageId = null;
var incomplete = null;
~if isset($pageObj->fieldsArray)`
  dataArray = ~$pageObj->fieldsArray|decodevar`; 
  pageId  = "~$pageObj->getPageName()`";
  nextPageId = "~$pageObj->getNextPageName()`";
~/if`
~if $pageObj->isIncomplete`
var incomplete = ~$pageObj->isIncomplete`;
~/if`
~if $pageObj->ugGroup`
var ugArr = '~$pageObj->ugGroup`'.split(",");
~/if`
~if $pageObj->gGroup`
var bachelorArr = '~$pageObj->gGroup`'.split(",");
~/if`
~if $pageObj->pgGroup`
var pgDegreeArr = '~$pageObj->pgGroup`'.split(",");
~/if`
~if $pageObj->phdGroup`
var phdArr = '~$pageObj->phdGroup`'.split(",");
~/if`
var prefilledData = null;
~if isset($pageObj->incompletePrefilledData)`
  prefilledData = ~$pageObj->incompletePrefilledData|decodevar`;
~/if`
var campaignData = null;
~if isset($pageObj->campaignData)`
  var campaignData = ~$pageObj->campaignData|decodevar`;
~/if`
$(document).ready(function() {
    
	slider();
});

</script>
