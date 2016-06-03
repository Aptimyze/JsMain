<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://www.google.com/uds/modules/elements/transliteration/api.css" type="text/css" rel="stylesheet"/>
<link href="http://www.google.com/uds/api/elements/1.0/f14dd6b6842dee1ce42c7252389ec82d/transliteration.css"type="text/css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="~sfConfig::get(app_site_url)`/css/~$rupeeSymbol_css`">
<style>
.goog-transliterate-indic-suggestion-menu {z-index:1000;}
.inf_in{font-weight:bold; font-family:Arial,verdana; font-size:13px; padding:10px 5px 0px 5px;}
.red_new {color:#e93a3e !important}
</style>
<script type="text/javascript">
	var transliterationControl;
if(google.elements.transliteration.isBrowserCompatible())
	onLoad();
      function transliterateStateChangeHandler(e) {
	document.getElementById('checkboxId').checked = e.transliterationEnabled;
      }

      // Handler for checkbox's click event.  Calls toggleTransliteration to toggle
      // the transliteration state.

      function checkboxClickHandler() 
      {
		if(document.getElementById('spellcheck2').style.display=="block")
		{
			document.getElementById('spellcheck2').style.display="none";
			document.getElementById('spellcheck3').style.display="none";
			document.getElementById('ttip').style.display="block";
			document.getElementById('ttip1').style.display="block";
		}
		else
		{
			document.getElementById('spellcheck2').style.display="block";
			document.getElementById('spellcheck3').style.display="block";
                        document.getElementById('ttip').style.display="none";
			document.getElementById('ttip1').style.display="none";
		}
		var check_c= document.getElementById('checkboxId').checked;
		
		if(check_c == true)		
		{	
			document.getElementById('checkboxId2').checked = true;
		}
		else
		{
			document.getElementById('checkboxId2').checked = false;
		}

		if (document.getElementById('checkboxId').checked == true && document.getElementById('checkboxId2').checked == true)
	  	{
			 document.getElementById('languageDropDown1').disabled = false;
		   	 document.getElementById('languageDropDown2').disabled = false;
	        }
	     	if (document.getElementById('checkboxId').checked == false || document.getElementById('checkboxId2').checked == false)
	     	{
			document.getElementById('languageDropDown1').disabled = true;
		        document.getElementById('languageDropDown2').disabled = true;
		}
		
  	    transliterationControl.toggleTransliteration();
		
      }

      function checkboxClickHandler1() 
      {
		 if(document.getElementById('spellcheck2').style.display=="block")
                {
                        document.getElementById('spellcheck2').style.display="none";
                        document.getElementById('spellcheck3').style.display="none";
                        document.getElementById('ttip').style.display="block";
                        document.getElementById('ttip1').style.display="block";
                }
                else
                {
                        document.getElementById('spellcheck2').style.display="block";
                        document.getElementById('spellcheck3').style.display="block";
                        document.getElementById('ttip').style.display="none";
                        document.getElementById('ttip1').style.display="none";
                }
		var check_c= document.getElementById('checkboxId2').checked;		
		if(check_c == true)		
		{	
			document.getElementById('checkboxId').checked = true;
		}
		else
		{
			document.getElementById('checkboxId').checked = false;
		}
		
		if (document.getElementById('checkboxId').checked == true && document.getElementById('checkboxId2').checked == true)
	  	{
			 document.getElementById('languageDropDown1').disabled = false;
		   	 document.getElementById('languageDropDown2').disabled = false;
	        }
	     	if (document.getElementById('checkboxId').checked == false || document.getElementById('checkboxId2').checked == false)
	     	{
			document.getElementById('languageDropDown1').disabled = true;
		        document.getElementById('languageDropDown2').disabled = true;
		}
		transliterationControl.toggleTransliteration();
      }    
      // Handler for dropdown option change event.  Calls setLanguagePair to
      // set the new language.
      function languageChangeHandler(qq) {
	var  dropdown= document.getElementById(qq), txtarea , txtarea1;
	txtarea = document.getElementById("about_myself");
	txtarea1 = document.getElementById("about_family");
	var h = parseInt(txtarea.style.height),  w = parseInt(txtarea.style.width);
        var a = parseInt(txtarea1.style.height), b = parseInt(txtarea1.style.width);
        var e = txtarea.style.fontFamily;
        var f = txtarea1.style.fontFamily;
        var i = parseInt(txtarea.style.fontSize);
        var j = parseInt(txtarea1.style.fontSize);
	//alert(dropdown.options[dropdown.selectedIndex].value);
        transliterationControl.setLanguagePair(
            google.elements.transliteration.LanguageCode.ENGLISH,
            dropdown.options[dropdown.selectedIndex].value);
 	    if(h) txtarea.style.height = h + "px";
            if(w) txtarea.style.width = w + "px";

            if(a) txtarea1.style.height = a + "px";
            if(b) txtarea1.style.width = b + "px";

            if(e) txtarea.style.fontFamily = 'arial';
            if(f) txtarea1.style.fontFamily = 'arial';

            if(i) txtarea.style.fontSize = 12 +"px";
            if(j) txtarea1.style.fontSize = 12 +"px";

	    document.getElementById('languageDropDown2').options.selectedIndex=dropdown.options.selectedIndex;
	    document.getElementById('languageDropDown1').options.selectedIndex=dropdown.options.selectedIndex;
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
    
      function setFocus(box)
      {
	   if(box == 1)
	   {
 	       document.getElementById("checkboxId").Information.focus();
      	   } else if(box == 2)
	   {
	       document.myForm.Family.focus();
      	   }
      }
      google.setOnLoadCallback(onLoad);
	
    </script>
<script>
function validate()
{
	if(document.form1.Information.value == "")
	{
		document.getElementById('info_span').style.display="block";
		document.form1.Information.focus();
		return false;
	}
	var iStr=trim(document.form1.Information.value);
	iStr=trim_newline(iStr);
	if(iStr.length < 100)
	{
		document.getElementById('info_span').style.display="block";
		document.form1.Information.focus();
		return false;
	}		
}
function changeCount()
{
        var docF=document.form1;
        var str=new String();
        str=trim(docF.Information.value);
	str=trim_newline(str);
        document.getElementById('wordcount').value=str.length;
	document.getElementById('wordcount').innerHTML = str.length+" characters";
	if(document.getElementById('wordcount').value<100)
		document.getElementById('wordcount').style.color = "red";
	else
		document.getElementById('wordcount').style.color = "green";
}
function trim_newline(string){
        return string.replace(/^\s*|\s*$/g, "");
}
function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}

function setf()
{	
	document.getElementById("about_myself").focus();
}
setTimeout("setf()",2000);
function ismaxlength(obj){
var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
if (obj.getAttribute && obj.value.length>mlength)
obj.value=obj.value.substring(0,mlength)
}
function abc()
{
	setTimeout(function() { document.getElementById('txt_12').focus(); }, 2000);
	return false;
}
</script>

<script src="~sfConfig::get('app_site_url')`/jspellhtml2k4/jspell.js" language="JavaScript"></script>
<script>
var def_area=1;
function set_id(value)
{
	def_area=value;
}
function getSpellCheckArray()
{
var fieldsToCheck=new Array();

// make sure to enclose form/field object reference in quotes!
//var i;
//for(i=0;i<document.form1.elements.length;i++)
//{

if(def_area==1)
fieldsToCheck[fieldsToCheck.length]='document.forms["form1"].Information';

if(def_area==2)
fieldsToCheck[fieldsToCheck.length]='document.forms["form1"].Family';
//}

return fieldsToCheck;
}
var language;

</script>
	<div class="clear"></div>
	~$sf_data->getRaw('hiddenInput')`
	<input type="hidden" name="IncompleteMail" value="~$sf_request->getParameter('IncompleteMail')`">
<input type="hidden" name="mark" value="~$mark`">
<input type="hidden" name="post_login" value="~$post_login`">


	~if $mark eq 1 or $mark eq 3 or ($SUBYOURINFO eq '' and $YOURINFO eq '') or ($INFOLEN lt 100)`
	~if FTOLiveFlags::IS_FTO_LIVE`
	<div class="edit_scrollbox2_2">
	<div class="fto-notification-box">
	 	<p style="color:#bc001d">Complete the form and 
	     Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 

		</p>
		<p style="color:#000">See e-mail IDs &amp; Phone numbers of people you like.</p>
	</div>
	<div class="sp15">&nbsp;</div>
	~else`
	<div class="edit_scrollbox2_1">
	~/if`
	<div class="inf_in"><img src="~$IMG_URL`/profile/images/info_icon.gif" align="absmiddle">&nbsp;Please fill in the information marked in Red, or your profile is not visible to other members as it is incomplete</div>
	~else`
	<div class="edit_scrollbox2_1">
	~/if`
	<div class="green ttip_top">
		<b><strong>Tip:</strong></b> ~if $for_about_us`To be able to contact members you like and get contacted by other members, please write <br>100 characters describing your~if $for_about_value eq 2` daughter~elseif $for_about_value eq 7` son~elseif $for_about_value eq 3 or $for_about_value eq 6` sibling~elseif $for_about_value eq 4` friend~elseif $for_about_value eq 5` client~else`self~/if`.~else`Write about your interests, passions, hobbies, family background, parents, siblings etc.<br>Part of this text will be directly visible in search results.~/if`
	</div>
	<div class="sp15"></div>

	<div class="row4 no-margin-padding">
	<label  class="grey" ~if ($SUBYOURINFO eq '' and $YOURINFO eq '') or ($INFOLEN lt 100)` style="color:red!important"~/if`>About ~if $RELATION eq '1'`Me~else`~if $GENDER eq 'F'`Her~else`Him~/if`~/if` : </label>
		<span>
			<input type="checkbox" class="chbx" id="checkboxId" onclick="javascript:checkboxClickHandler();"></input>
			<select class="combo-small-more" onchange="javascript:languageChangeHandler('languageDropDown1');" id="languageDropDown1" title="Please choose your language for about self">
				<option value="hi">हिन्दी</option>
				<option value="ta">தமிழ்</option>
				<option value="te">తెలుగు</option>
				<option value="kn">ಕನ್ನಡ</option>
				<option value="ml">മലയാളം</option>
			</select>
			<div class="sp5">&nbsp;</div>
			<textarea class="textarea-big-2" style="width:400px;height:135px;" id="about_myself" name="Information" onKeyup="changeCount();return ismaxlength(this);" maxlength="5000">~$sf_data->getRaw('YOURINFO')`</textarea>
		</span>
	</div>
	<div class="sp5"></div>
	<div class="row4 no-margin-padding fl">
		<label class="grey">&nbsp;</label>
		<div class="red_new" id="info_span" style="display:none;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>Please enter atleast 100 characters.
		</div>
		<div style="padding:0 0 0 195px;">
			<span class="fs13 fl" style="width:265px;" name="wordcount" id="wordcount" ~if $CHARACTERS gt 100`style="color:green;"~else`style="color:red;"~/if`>&nbsp;~$INFOLEN` characters&nbsp;<font class="maroon">(minimum 100)</font></span>
			<i class="mar_left_38" style="display: block;" id="spellcheck2">
			<img onclick="set_id(1);spellcheckxx();return false;" src="~$IMG_URL`/profile/images/registration_new/spell-check.gif" style="cursor:pointer;"/>
			</i>
		</div>
	</div>
	<div class="row4 no-margin-padding fl">
		<label class="grey">&nbsp;</label>
		<div class="lf mar_left_10" style="display: none;" id="ttip">
			<div class="lf green b">Tip:</div>
			Type a word and hit space to get it in the chosen language,<br/>Click on a word to see more options.
			<a id="more" href="http://www.google.com/transliterate/indic/about_hi.html" target="_blank">More »</a>
			<div class="sp5"></div>
		</div>
	</div>
		<div class="row4 no-margin-padding">
			<label class="grey">About ~if $RELATION eq '1'`my~else`~if $GENDER eq 'F'`her~else`his~/if`~/if` family : </label>
		<span>
			<input class="chbx"  type="checkbox" id="checkboxId2" onclick="javascript:checkboxClickHandler1();"></input>
			<select class="combo-small-more" onchange="javascript:languageChangeHandler('languageDropDown2');" id="languageDropDown2" title="Please choose your language for about family">
			<option value="hi">हिन्दी</option>
			<option value="ta">தமிழ்</option>
			<option value="te">తెలుగు</option>
			<option value="kn">ಕನ್ನಡ</option>
			<option value="ml">മലയാളം</option>
			</select>	
			<div class="sp5"></div>			
				<textarea class="textarea-big-2" id="about_family" name="Family" style="width:400px;height:135px;" maxlength="5000" onkeyup="return ismaxlength(this);">~$FAMILYINFO`
				</textarea>
				<br><div id="errorDiv"></div>
			</span>
		</div>
		<div class="sp5"></div>
		<div class="row4 no-margin-padding fl">
		<label class="grey">&nbsp;</label>
                <i class="mar_left_big fl" style="display: block;" id="spellcheck3">
                        <img onclick="set_id(2);spellcheckxx();return false;" src="~$IMG_URL`/profile/images/registration_new/spell-check.gif" style="cursor:pointer;"/>
			<input type="text" id="txt_12" style="opacity:0; filter:alpha (opacity:0); height:0; width:0; font-size:1px;" >			
                </i>
		<div class="row4 no-margin-padding fl">
				<label class="grey">&nbsp;</label>
                <div class="lf mar_left_10" style="display: none;" id="ttip1">
                        <div class="lf green b">Tip:</div>
                        Type a word and hit space to get it in the chosen language,<br/>Click on a word to see more options.
<a id="more" href="http://www.google.com/transliterate/indic/about_hi.html" target="_blank">More »</a>
					</div>
				</div>
		</div>
		</div>
~if $for_fam eq 1`
<script>
abc();
</script>
~/if`

