<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://www.google.com/uds/modules/elements/transliteration/api.css" type="text/css" rel="stylesheet"/>
<link href="http://www.google.com/uds/api/elements/1.0/f14dd6b6842dee1ce42c7252389ec82d/transliteration.css"type="text/css" rel="stylesheet"/>

<script type="text/javascript">
      
	var transliterationControl;
	onLoad();
      function transliterateStateChangeHandler(e) {
	document.getElementById('checkboxId').checked = e.transliterationEnabled;
      }

      // Handler for checkbox's click event.  Calls toggleTransliteration to toggle
      // the transliteration state.

      function checkboxClickHandler() 
      {
	      if(document.getElementById('spellcheck1').style.display=="block")
              {
              	document.getElementById('spellcheck1').style.display="none";
                document.getElementById('ttip').style.display="block";
              }
              else
              {
              	document.getElementById('spellcheck1').style.display="block";
                document.getElementById('ttip').style.display="none";
              }
	      disable();
  	      transliterationControl.toggleTransliteration();
      }

      // Handler for dropdown option change event.  Calls setLanguagePair to
      // set the new language.
      function languageChangeHandler() {
	var dropdown = document.getElementById('languageDropDown4'), txtarea2;
	txtarea2 = document.getElementById("about_dpp");
	var c = parseInt(txtarea2.style.height), d = parseInt(txtarea2.style.width);
        var g = txtarea2.style.fontFamily;
        var k = parseInt(txtarea2.style.fontSize);

        transliterationControl.setLanguagePair(
            google.elements.transliteration.LanguageCode.ENGLISH,
            dropdown.options[dropdown.selectedIndex].value);
	    if(c) txtarea2.style.height = c + "px";
            if(d) txtarea2.style.width = d + "px";
            if(g) txtarea2.style.fontFamily = 'arial';
            if(k) txtarea2.style.fontSize = 12 +"px";


      }

      // SERVER_UNREACHABLE event handler which displays the error message.
      function serverUnreachableHandler(e) {
        document.getElementById("errorDiv").innerHTML =
            "Transliteration Server is unreachable";
      }

      // SERVER_UNREACHABLE event handler which clears the error message.
      function serverReachableHandler(e) {
        document.getElementById("errorDiv").innerHTML = "";
      }
    
	
	function disable()
      {
        if (document.getElementById('checkboxId').checked == false)
        {
                document.getElementById('languageDropDown4').disabled = true;
        }
        else if (document.getElementById('checkboxId').checked == true)
        {
                document.getElementById('languageDropDown4').disabled = false;
        }
      }
      google.setOnLoadCallback(onLoad);
function ismaxlength(obj){
var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
if (obj.getAttribute && obj.value.length>mlength)
obj.value=obj.value.substring(0,mlength)
}
        
</script>
<script src="~sfConfig::get('app_site_url')`/jspellhtml2k4/jspell.js" language="JavaScript"></script>
<script>
function getSpellCheckArray()
{
var fieldsToCheck=new Array();

// make sure to enclose form/field object reference in quotes!

fieldsToCheck[fieldsToCheck.length]='document.forms["form1"].SPOUSE';

return fieldsToCheck;
}
var language;

</script>
<input type="hidden" name="CMDsubmit" value=1>
<input type="hidden" name="EditWhat" value="spouse">
<input type="hidden" name="flag" value="PMF">
<div class="clear"></div>
<div style="height:325px;">
<div class="sp15">&nbsp;</div>
<div class="green" style="padding:5px 0 0 48px;"><b>Tip:</b> Write about the characteristics you are looking for in ~if $RELATION eq '1'`your~else`~if $GENDER eq 'F'`her~else`his~/if`~/if` spouse. </div>

<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Desired characteristics</br> of spouse  : </label>
<span>
	<div id='translControl'>
	<input class="chbx" type="checkbox" id="checkboxId" onclick="javascript:checkboxClickHandler();"></input>
			<select class="combo-small-more" onchange="javascript:languageChangeHandler();" id="languageDropDown4" title="Please choose your language for about partner">
				<option value="hi">हिन्दी</option>
				<option value="ta">தமிழ்</option>
				<option value="te">తెలుగు</option>
				<option value="kn">ಕನ್ನಡ</option>
				<option value="ml">മലയാളം</option>
			</select>
			<div class="sp5">&nbsp;</div>
	</div>
	<textarea  class="textarea-big-2" style="width:400px;height:135px;" id="about_dpp" name="SPOUSE" maxlength="1000" onkeyup="return ismaxlength(this);">~$SPOUSE|decodevar`</textarea><br><div id="errorDiv"></div>
</span>
</div>
<div class="sp5">&nbsp;</div>
<div class="row4 no-margin-padding fl">
			<i  style="display: block;margin-left:479px" id="spellcheck1">
			<img onclick="spellcheckxx();return false;" src="~$IMG_URL`/profile/images/registration_new/spell-check.gif" style="cursor:pointer;"/>
			</i>
                <div class="lf" style="display:none;margin-left:200px;" id="ttip">
                        <div class="lf green b">Tip:</div>
                        Type a word and hit space to get it in the chosen language,<br/>Click on a word to see more options.
<a id="more" href="http://www.google.com/transliterate/indic/about_hi.html" target="_blank">More »</a>
                </div>
                </div>

</div>
