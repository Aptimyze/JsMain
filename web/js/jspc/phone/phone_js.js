$(document).ready(function() {
$("#isdMain").attr('saved',savedIsd);
$("#phoneNumberMain").attr('saved',savedNum);
	$("#verifyButton").bind('click',function () {showOtpLayer('isdMain','phoneNumberMain');});
handleBack();
});

//Handling History Back
function handleBack() {
  if (typeof (historyStoreObj) === "undefined") {
    return;
  }
  
  if (typeof (disableBack) === "undefined" ) {
    return;
  }
  
  if (disableBack === "0" ) {
    return;
  }
  
  // Declare Varibales
  var overlay       = '.js-regOverlay';
  var overlayMsg    = '.js-regOverlayMsg';
  var overlayClose  = '.js-regOverlayClose';
  var displayNone   = 'disp-none';
  var msgTimeout    = 5000;
  var timeoutId     = null;
  //Function to show hide overlay 
  function showHideOverlay(bShow)
  {
    if(0 === $(overlay).length)
      return false;
    
    if(true === bShow){
      $(overlay).removeClass(displayNone);
      $(overlayMsg).removeClass(displayNone);
    }
    else if(false === bShow)
    {
      $(overlay).addClass(displayNone);
      $(overlayMsg).addClass(displayNone);
    }
  }
  
  //Binding Close Button on overlay
  $(overlayClose).on('click',function(){
    if(null !== timeoutId){
      clearTimeout(timeoutId);
      timeoutId = null;
    }
    showHideOverlay(false);
  });
  
  //Show Back Btn Msg
  var showBrowserBackMsg = function () {
    showHideOverlay(true);
    historyStoreObj.push(onBrowserBack, "#verify");
    timeoutId = setTimeout(function(){
      showHideOverlay(false);
      timeoutId = null;
    },msgTimeout);
  }
  
  //Function callback when browser back will called
  var onBrowserBack = function () {
    if (location.href.indexOf("register") != -1) {
      showBrowserBackMsg();
      return true;
    }
    return false;
  }

  historyStoreObj.push(onBrowserBack, "#verify");
  
};