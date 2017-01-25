~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename,'age'=>$loginProfile->getAGE(),'mtongue'=>$loginProfile->getMTONGUE(),'city'=>$loginProfile->getCITY_RES()])`
~if isset($fromReg)`
<!--start:overlay-->
  <div class="hpoverlay z2 js-regOverlay disp-none"></div>
  <div class="pos_fix fullwid z3 reg1pos1 js-regOverlayMsg disp-none">
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
~/if`  


<div class="fullwid bg-4"> 
  <!--start:header-->
  
  ~if isset($fromReg)`
    ~include_partial("global/JSPC/_jspcCommonMemRegHeader",['PAGE'=>"DPP",'name'=>~$name`])`
  ~else if !isset($fromBackend)`
  <header>
    <div class="cover1">
      <div class="container mainwid pt35">
        ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
        <div class="f14 srppad6 ulinline clearfix">
        </div>
          <div class="pt50 pb30 txtc fontlig f22 colrw" style="visibility: hidden;">  Welcome </div>
      </div>
    </div>
  </header>
  ~/if`
  <!--end:header--> 
  <!--start:middle part-->
  <div class="container mainwid bg-white fontlig pos-rel" id="midsec">
  ~if isset($fromReg)`
    <div class="txtr pt2 pr10">
      <a class="fontlig f13 colr4 txtu opa60 cursp" href="/social/addPhotos?fromReg=1">I will do this later</a>
    </div>
    ~/if`
    <div class="overlaywhite disp-none js-loaderShow"></div>
    <div class="overlayload disp-none js-loaderShow js-loaderDiv"><img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/loader.gif"></div>
    <div class="edwid1 mauto">
        
      <div class="pt28 pb20 txtc fontlig f22 edbrd1">Desired Partner Profile </div>
          <div class="pt10 pb20 txtc fontlig f16">The criteria you mention here determines the ‘Desired Partner Matches’ you see. So please review this information carefully. 
Moreover, Filters determine whose Interests/Calls you want to receive.</div>
<!--start: new div-->
      <div id="newdppT">
          <div class="dppnbg1 fullwid txtc lh50 colrw fontlig f19 z7" id="countScroll">
            No. of Mutual Matches with below criteria - <span id="mutualMatchCount" data-value="~$mutualMatchCount`">~$mutualMatchCount`</span>
          </div>
      </div>
      <div class="clearfix pt20 pb20 mauto wid90p">
        <div class="fl">
          <label class="control control--checkbox">
              <input type="checkbox" ~if $toggleMatchalerts eq 'dpp'`~else` checked ~/if` id="mutualMatchCountCheckBox"/>
              <div class="control__indicator"></div>
            </label>
        </div>
        <div class="fl fontlig">
          <p class="dppnc1 f18 fontreg">I also want to receive matches based on the history of my interests and acceptances</p>
            <p class="dppnc2 f15 pt7">These matches may not fully fulfil your Desired Partner Preference.</p>
        </div>
      </div>
      <!--end: new div-->




        <!--<p class="color5 f15 txtc pb30 js-apMes~if isset($existingData['ap_screen_msg'])`~else` disp-none~/if`">~if isset($existingData['ap_screen_msg'])`~$existingData['ap_screen_msg']`~/if`</p>-->
      <div id="loadLate" style="visibility:hidden;">
      ~foreach from=$arrOut key=heading item=arrData`
        ~include_Partial("profile/jspcDpp/_dppGeneric",['data'=>$arrData,'fieldArray'=>$arrData.fieldArray,'heading'=>$heading,'dropDownData'=>$dropDownData,'existingData'=>$existingData,'filterArr'=>$filterArr,'underScreeningMessage'=>$underScreeningMessage])`
      ~/foreach`
      </div>
    </div>
    ~if isset($fromReg)`
      <!--start:button-->
      <div class="dpp-pad4" id="regSaveButton">
        <a href="/profile/dpp?isSubmit=1"><button class="btn-a f20 cursp">Looks good, Proceed </button></a>
      </div>
      <!--end:button--> 
    ~else if isset($fromBackend)`
      <!--start:button-->
      <div class="dpp-pad4">
       <button id="closeFromBackend" class="btn-a f20 cursp">Looks good, Close </button>
      </div>
      <!--end:button--> 
    ~/if`
  </div>
  <div class="height100"></div>
  <!--end:middle part--> 
  ~if !isset($fromBackend)`
		~include_partial('global/JSPC/_jspcCommonFooter',[pixelcode=>$pixelcode])`
	~/if`

</div>
<script type="text/javascript">
var dppCaste  = ~$casteDropDown|decodevar`  ;
~if isset($EditWhatNew)`
var openSection = "~$EditWhatNew`";
var mapForEditWhat = ~$editWhatNewMap|decodevar`;
~/if`
var disableBack='~$fromReg`';
//Create a namespace for DPP application
dppApp = function(){
  
  //Privatre varibale and function
  var fields = {};
  var orgFields = {};
  var debugInfo = true;
  var fieldsToSave = {};
  var filterNeedToCheckArray = {'P_CITY':0,'P_CASTE':0};
  
  function getFilterCheck(fieldName){
	  if(false === filterNeedToCheckArray.hasOwnProperty(fieldName)){
		  return null;
	  }
	  return filterNeedToCheckArray[fieldName];
  }
  function setFilterCheck(fieldName,fieldValue){
	  if(false !== filterNeedToCheckArray.hasOwnProperty(fieldName)){
      filterNeedToCheckArray[fieldName] = fieldValue;
    }
  }
  //Get Function
  function getFieldValue(fieldName,forAjax){
    if(false === fields.hasOwnProperty(fieldName)){
      return null;
    }
    
    if(typeof(forAjax) != "undefined"){
      return parseValueForAjax(fields[fieldName]);
    }
    return fields[fieldName];
  };
  
  //Set Function
  function setFieldValue(fieldName,fieldValue){
    fields[fieldName] = parseValueForChosen(fieldValue);
    if(fieldName == "spouse"){
      fields[fieldName] = fieldValue;  
    }  
     orgFields[fieldName] = fieldValue;
    return fields[fieldName];
  };
  
  function setFieldsToSave(sectionName,fieldName,fieldValue){
    if(Object.keys(fieldsToSave).indexOf(sectionName) == -1){
      fieldsToSave[sectionName]={};
  }
    fieldsToSave[sectionName][fieldName.toUpperCase()] = parseValueForAjax(fieldValue);
    return fieldsToSave[sectionName][fieldName];
  };
  function getFieldsToSave(sectionName){
    if(false === fieldsToSave.hasOwnProperty(sectionName)){
      return null;
    }
    return fieldsToSave[sectionName];  
  }
  
  function covertInArray(value){
    var arr = value.split(",");
    
     if(arr.indexOf("DM") !==-1 ){
      var arrOut = [];
      for(var i=0;i<arr.length;i++){
        if(arr[i] == "DM"){
          arrOut[i] = "";
        } 
      }
      arr = arrOut;
    }
    
    return arr;
  }
  
  //Parse Value, and if string is given then convert into it an array
  function parseValueForChosen(fieldValue){
    
    if(typeof(fieldValue) == "string" && fieldValue.indexOf(",")!=-1){
      return covertInArray(fieldValue);
    }
    
    //DM means Doesnot matter
    if(typeof(fieldValue) == "string" && fieldValue=="DM"){
      return "";
    }
    
    return fieldValue;
  }
  
  //Parse Value, and if string is given then convert into it an array
  function parseValueForAjax(fieldValue){
    if(fieldValue instanceof Array)
      return fieldValue.toString();
    
    return fieldValue;
  }
  
  //Public Interface
  if(debugInfo){
    return {
      get : getFieldValue,
      set : setFieldValue,
      setForSave : setFieldsToSave,
      getForSave : getFieldsToSave,
      fields: fields,//Comment Out this
      fieldsToSave:fieldsToSave,//Comment Out this
      orgFields:orgFields,//Comment Out this
      getFilterCheck:getFilterCheck,
      setFilterCheck:setFilterCheck
    };
  }
  
  return {
      get : getFieldValue,
      set : setFieldValue,
      setForSave : setFieldsToSave,
      getForSave : getFieldsToSave,
      getFilterCheck:getFilterCheck,
      setFilterCheck:setFilterCheck
    };
}();

~foreach from=$existingData key=k item=arrData`
    dppApp.set("~$arrData.key|lower`",~$arrData.value|json_encode`); 
    ~if $arrData.key|lower eq 'spouse'`
      dppApp.set("spouse_screen",~$arrData.screenBit`);
    ~/if`
~/foreach`
if( null === dppApp.get('p_nchallenged')){
    dppApp.set('p_nchallenged',"");
}
</script>
