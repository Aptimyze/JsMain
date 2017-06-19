<link href="http://www.google.com/uds/modules/elements/transliteration/api.css" type="text/css" rel="stylesheet"/>
<link href="http://www.google.com/uds/api/elements/1.0/f14dd6b6842dee1ce42c7252389ec82d/transliteration.css"type="text/css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="~sfConfig::get(app_site_url)`/css/~$rupeeSymbol_css`">
<style>
.goog-transliterate-indic-suggestion-menu {z-index:1000;}
.inf_in{font-weight:bold; font-family:Arial,verdana; font-size:13px; padding:10px 5px 0px 5px;}
.red_new {color:#e93a3e !important}
div.row4 label.grey {vertical-align:top;padding-right:10px;float:left;color:#797979!important; font-size:13px; width:137px!important;}
</style>

<script type="text/javascript">
	var docF=document.form1;
	function dID(arg)
	{
		return document.getElementById(arg);
	}

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
			
			document.getElementById('ttip').style.display="block";
			
		}
		else
		{
			document.getElementById('spellcheck2').style.display="block";
			
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
function blankField(action)
{
	var spanId=action.name+"_span";
	if(action.name=="Information")
	{
		if(action.value.length <100)
		{
			document.getElementById(spanId).style.display="block";
		}
	
	}
	else if(action.id=="GenderF")
	{
		if(document.getElementById("GenderF").checked || document.getElementById("GenderM").checked)
		{
			document.getElementById("Gender_span").style.display="none";
		}
		else
		{
			document.getElementById("Gender_span").style.display="block";
		}
	}
	else if(action.id=="month"|| action.id=="year"||action.id=="day")
	{
		document.getElementById("Dob_span").style.display="none";
		document.getElementById('DobM_span').style.display="none";
		document.getElementById('DobF_span').style.display="none";
		if(action.value=="")
			document.getElementById("Dob_span").style.display="block";
		else
			document.getElementById("Dob_span").style.display="none";
	}
	else if(action.value == "")
	{
		document.getElementById(spanId).style.display="block";
	}
	else
	{
		document.getElementById(spanId).style.display="none";
	}
}

function isDate(y,m,d)
{
var date = new Date(y,m-1,d);
var convertedDate =
""+date.getFullYear() + (date.getMonth()+1) + date.getDate();
var givenDate = "" + y + m + d;
if(givenDate == convertedDate)
	return true;
else 
	return false
}
function validate()
{
	document.getElementById('Income_span').style.display="none";
	document.getElementById('Occupation_span').style.display="none";
	document.getElementById('Education_Level_span').style.display="none";
	document.getElementById('Information_span').style.display="none";
	var error="";
	//relationship and gender validation
	if(document.getElementById("relationFlag").value==1)
	{
		if(document.getElementById("Realtionship").value=="")
		{
			document.getElementById("Realtionship_span").style.display="block";
			error=5;
		}
		// gender validation
	/*	if(document.getElementById("genderValue").value!="M" && document.getElementById("genderValue").value!="F")
		{
			document.getElementById("Gender_span").style.display="block";
			error=6;
		}*/
	}
		
	// Date of Birth validation
	if(document.getElementById("dOBFlag").value==1)
	{
		document.getElementById("Dob_span").style.display="none";
		document.getElementById('DobM_span').style.display="none";
		document.getElementById('DobF_span').style.display="none";
		
		if(document.getElementById("day").value=="" || document.getElementById("month").value=="" || document.getElementById('year').value=="")
		{
			document.getElementById("Dob_span").style.display="block";
			error=7;
		}
		else if(!isDate(document.form1.year.value,document.form1.month.value,document.form1.day.value))
		{
			document.getElementById('Dob_span').style.display="block";
			var error=5;
		}
		else 
		{
			var birthDate= new Date(document.form1.year.value,document.form1.month.value,document.form1.day.value);
			var today=new Date();
			var age = today.getFullYear() - birthDate.getFullYear();
			var m = today.getMonth() - birthDate.getMonth() +1;
			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
			{
				age--;
			}
			/*if(document.getElementById("genderFlag").value==1)
			{
				if(document.getElementById("GenderF").checked)
					var gender=document.getElementById("GenderF").value;
				if(document.getElementById("GenderM").checked)
					var gender=document.getElementById("GenderM").value;
			}
			else*/
				var gender=document.getElementById("genderValue").value;
			if(gender=="M")
			{
				if(age < 21)
				{
					error=11;
					document.getElementById('DobM_span').style.display="block";
					document.getElementById('DobF_span').style.display="none";
				}
			}
			else if(gender=="F")
			{
				if(age<18)
				{
					error=12;
					document.getElementById('DobM_span').style.display="none";
					document.getElementById('DobF_span').style.display="block";
				}
			}			
		}
		document.getElementById('dOBValue').value=document.form1.year.value+"-"+document.form1.month.value+"-"+document.form1.day.value;
	}	
	// Height validation
	if(document.getElementById("heightFlag").value==1)
	{
		if(document.getElementById("Height").value=="")
		{
			document.getElementById("Height_span").style.display="block";
			error=8;
		}
	}
	
	// Maritial Status And have children validation
	
	if(document.getElementById("mStatusFlag").value==1)
	{
		document.getElementById("mStatus_residence_married_span").style.display="none";
		document.getElementById("mStatus_residence_span").style.display="none";
		document.getElementById("haveChildern_residence_span").style.display="none";
		
		if(document.getElementById("mStatus_residence").value=="")
		{
			document.getElementById("mStatus_residence_span").style.display="block";
			error=9;
		}
		else if(document.getElementById("mStatus_residence").value!="N" && document.getElementById("haveChildern_residence").value=="")
		{
			document.getElementById("haveChildern_residence_span").style.display="block";
			error=10;
		}
		else if(document.getElementById("mStatus_residence").value=="M" && document.getElementById("genderValue").value=="F")
		{
			document.getElementById("haveChildern_residence_span").style.display="block";
			error=14;
		}
		else if(document.getElementById("mStatus_residence").value=="M" && document.getElementById("genderValue").value=="M" && document.getElementById("religionValue").value!="2" )
		{
			document.getElementById("mStatus_residence_married_span").style.display="block";
			error=15;
		}
	}
	// mTongue validation
	
	if(document.getElementById("mTongueFlag").value==1)
	{
		if(document.getElementById("mTongue").value=="")
		{
			document.getElementById("mTongue_span").style.display="block";
			error=10;
		}
	}
	// Religion -Caste validation
	
	if(document.getElementById("religionFlag").value==1)
	{
		if(document.getElementById("Religion").value=="")
		{
			document.getElementById("Religion_span").style.display="block";
			error=11;
		}		
		else
		{
			var rel = document.form1.Religion.value;
			var rel_temp = rel.split("|X|");
			vrel = rel_temp[0];
			document.getElementById('religionValue').value=vrel;
			if(vrel == 1 && document.getElementById("Caste_hindu").value=="")
			{
                document.getElementById("Caste_hindu_span").style.display = "block";
                document.getElementById("Caste_muslim_span").style.display = "none";
                document.getElementById("Caste_christian_span").style.display = "none";
                document.getElementById("Caste_sikh_span").style.display = "none";
                document.getElementById("Caste_jain_span").style.display = "none";
                error=20;
			}
			else if(vrel == 2 && document.getElementById("Caste_muslim").value=="")
			{
					document.getElementById("Caste_hindu_span").style.display = "none";
					document.getElementById("Caste_muslim_span").style.display = "block";
					document.getElementById("Caste_christian_span").style.display = "none";
					document.getElementById("Caste_sikh_span").style.display = "none";
					document.getElementById("Caste_jain_span").style.display = "none";
				   error=21;			 
			}
			else if(vrel == 3 && document.getElementById("Caste_christian").value=="")
			{
					document.getElementById("Caste_hindu_span").style.display = "none";
					document.getElementById("Caste_muslim_span").style.display = "none";
					document.getElementById("Caste_christian_span").style.display = "block";
					document.getElementById("Caste_sikh_span").style.display = "none";
					document.getElementById("Caste_jain_span").style.display = "none";
					error=21;
			}
			else if(vrel == 4 && document.getElementById("Caste_sikh").value=="")
			{
					document.getElementById("Caste_hindu_span").style.display = "none";
					document.getElementById("Caste_muslim_span").style.display = "none";
					document.getElementById("Caste_christian_span").style.display = "none";
					document.getElementById("Caste_sikh_span").style.display = "block";
					document.getElementById("Caste_jain_span").style.display = "none";
					error=22;
				   
			}
			else if(vrel == 9 && document.getElementById("Caste_jain").value=="")
			{
					document.getElementById("Caste_hindu_span").style.display = "none";
					document.getElementById("Caste_muslim_span").style.display = "none";
					document.getElementById("Caste_christian_span").style.display = "none";
					document.getElementById("Caste_sikh_span").style.display = "none";
					document.getElementById("Caste_jain_span").style.display = "block";
					error=23;
					
			}
			else
			{
					document.getElementById("Caste_hindu_span").style.display = "none";
					document.getElementById("Caste_muslim_span").style.display = "none";
					document.getElementById("Caste_christian_span").style.display = "none";
					document.getElementById("Caste_sikh_span").style.display = "none";
					document.getElementById("Caste_jain_span").style.display = "none";
			}
		}
	}
	//Phone Number And LandLine Number Validations
	 if(document.getElementById("phoneFLag").value==1)
	{
		 if(document.getElementById('junk').value=='JM'){
			document.getElementById('mobile_in_name_span').style.display="none";
			document.getElementById('mobile_span').style.display="block";
			document.getElementById('international_mobile_span').style.display="none";
			document.getElementById('Mobile').focus();
			error++;
		}
		if(document.getElementById('junk').value=='JL'){
			document.getElementById('phone_in_name_span').style.display="none";
			document.getElementById('phone_span').style.display="block";
			document.getElementById('Phone').focus();
			error++;
		}
	   
		var country_code = document.getElementById('country_code').value;
	   
		if(isd_verify_on_submit())
		 error++;
		var phone_mob = validate_phone_mobile(document.getElementById('Phone').value,document.getElementById('Mobile').value);
		if(phone_mob != "OK")
		{	
			if(phone_mob == "PM")
			{
				document.getElementById('phone_in_name_span').style.display="none";
				document.getElementById('mobile_in_name_span').style.display="none";
				document.getElementById('phone_span').style.display="block";
				document.getElementById('mobile_span').style.display="block";
			}
			else if(phone_mob == "P")
			{
				document.getElementById('phone_in_name_span').style.display="none";
				document.getElementById('phone_span').style.display="block";
				document.getElementById('Phone').focus();
			}
			else if(phone_mob == "S")
			{
				document.getElementById('state_code_span').style.display="block";
				document.getElementById('state_code').focus();
			}
			else if(phone_mob == "M")
			{
				document.getElementById('mobile_in_name_span').style.display="none";
				document.getElementById('mobile_span').style.display="block";
				document.getElementById('international_mobile_span').style.display="none";
				document.getElementById('Mobile').focus();
			}       
			else if(phone_mob == "IM")
			{
				document.getElementById('mobile_in_name_span').style.display="none";
				document.getElementById('mobile_span').style.display="none";
				document.getElementById('international_mobile_span').style.display="block";
				document.getElementById('Mobile').focus();
			}
			error++;
		}
	}
		
	//About Me validation
	if(document.form1.Information.value == "")
	{
		document.getElementById('Information_span').style.display="block";
		document.form1.Information.focus();
		var error=0;
	}

	var iStr=trim(document.form1.Information.value);
	iStr=trim_newline(iStr);
	if(iStr.length < 100)
	{
		document.getElementById('Information_span').style.display="block";
		document.form1.Information.focus();
		var error=1;
	}
	//Education validation
	if(document.form1.Education_Level.value == "")
	{
		document.getElementById('Education_Level_span').style.display="block";
		document.form1.Education_Level.focus();
		var error=2;
	}
	//Occupation validation
	if(document.form1.Occupation.value == "")
	{
		document.getElementById('Occupation_span').style.display="block";
		document.form1.Occupation.focus();
		var error=3;
	}
	//Income validation
	if(document.form1.Income.value == "")
	{
		document.getElementById('Income_span').style.display="block";
		document.form1.Income.focus();
		var error=4;
	}
	if(error !=0)
	{
	return false;
	}
	else
	return true;
}
function changeCount()
{
        var docF=document.form1;
        var str=new String();
        str=trim(docF.Information.value);
	str=trim_newline(str);
        document.getElementById('wordcount').value=str.length;
	document.getElementById('wordcount').innerHTML = str.length+" Characters - minimum 100 characters";
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

function showContent()
{
        var rel = document.form1.Religion.value;
	var rel_temp = rel.split("|X|");
        vrel = rel_temp[0];
        if(vrel == 1)
        {
                document.getElementById("Hindu").style.display = "block";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
        }
        else if(vrel == 2)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "block";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
               
         
        }
	else if(vrel == 3)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "block";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
               ;
        }
	else if(vrel == 4)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "block";
                document.getElementById("Jain").style.display = "none";
               
        }
	else if(vrel == 9)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "block";
                
        }
        else
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                
        }
}
function changeContent()
{
	var rel = document.form1.Religion.value;
	var rel_temp = rel.split("|X|");
        vrel = rel_temp[0];
    document.getElementById('religionValue').value=vrel;
	if(vrel == 1)
	{
		document.getElementById("Hindu").style.display = "block";
		document.getElementById("Muslim").style.display = "none";
		document.getElementById("Christian").style.display = "none";
		document.getElementById("Sikh").style.display = "none";
		document.getElementById("Jain").style.display = "none";
		
	}
	else if(vrel == 2)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "block";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                 if(document.getElementById("mStatusFlag").value==1){
					if(document.getElementById("mStatus_residence").value=="M")
						document.getElementById("mStatus_residence_married_span").style.display="none";
				}
                
       
        }
	else if(vrel == 3)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "block";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
              
        }
	else if(vrel == 4)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "block";
                document.getElementById("Jain").style.display = "none";
                
        }
	else if(vrel == 9)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "block";
           
        }
	else 
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
               
        }
}
/*function setf()
{	
	document.getElementById("about_myself").focus();
}
setTimeout("setf()",2000);
*/
function ismaxlength(obj){
var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
if (obj.getAttribute && obj.value.length>mlength)
obj.value=obj.value.substring(0,mlength)
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
<input type="hidden" name="junk" id="junk" value="">
<input type="hidden" name="genderValue" id="genderValue" value=~$GENDER`>
<input type="hidden" name="religionValue" id="religionValue" value=~$religionValue`>
<input type="hidden" name="dOBValue" id="dOBValue" value="">
<input type="hidden" name="religionFlag" id="religionFlag" value=~$religionFlag`>
<input type="hidden" name="mTongueFlag" id="mTongueFlag" value=~$mTongueFlag`>
<input type="hidden" name="phoneFLag" id="phoneFLag" value=~$phoneFLag`>
<input type="hidden" name="mStatusFlag" id="mStatusFlag" value=~$mStatusFlag`>		
<input type="hidden" name="countryCityFlag" id="countryCityFlag" value=~$countryCityFlag`>
<input type="hidden" name="heightFlag" id="heightFlag" value=~$heightFlag`>
<input type="hidden" name="dOBFlag" id="dOBFlag" value=~$dOBFlag`>
<input type="hidden" name="genderFlag" id="genderFlag" value=~$genderFlag`>
<input type="hidden" name="relationFlag" id="relationFlag" value=~$relationFlag`>
<input type="hidden" name="channel" id="channel" value=~$sf_request->getParameter('channel')`>
	~if FTOLiveFlags::IS_FTO_LIVE`
	<div class="edit_scrollbox2_2">
	<div class="fto-notification-box">
	 	<p style="color:#bc001d">Complete the form and 
	     Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 

		</p>
		<p style="color:#000">See e-mail IDs &amp; Phone numbers of people you like.</p>
	</div>
	<div class="fr fullwidth">
	  <div class="fr" style="color:#7e7e7e; margin-top:5px">All fields are compulsory. For offer details reffer to Terms &amp; Conditions</div>
	  </div>
	<div class="sp15">&nbsp;</div>
	~else`
	<div class="edit_scrollbox2_1">
	~/if`
	<div class="sp15"></div>

		
<!--RELATIONSHIP SECTION -->
	~if $relationFlag eq 1`
		<div class="row4 no-margin-padding" id ="RelationshipDiv">
			<label class="grey">Posted By :</label>
			<span>
				<select name="Realtionship" id="Realtionship" onblur="blankField(this);">
				<option value="1" selected>Self</option>
				<option value="2" >Parent</option>
				<option value="3" >Sibling</option>
				<option value="4" >Relative/Friend</option>
				<option value="5" >Marriage Bureau</option>
				<option value="6" >Other</option>
				</select>
			</span>
			<span class="red_new" id="Realtionship_span" style="display:none;"> This field is mandatory to complete profile 
			</span>
			<div class="sp15">&nbsp;</div>
		</div>

		
<!--GENDER SECTION -->

	<!--	<div class="row4 no-margin-padding fl" id ="GenderDiv" style="display:block;">
			<label class="grey">&nbsp;&nbsp;&nbsp;Gender :</label>
			<span class="radio_list" name ="Gender" >
				<input input type="radio" class="chbx" style="vertical-align:middle" name="GenderM" value="M" id="GenderM" onclick="genderToggle('M');" >&nbsp;Male&nbsp;&nbsp;&nbsp;
				<input input type="radio" class="chbx" style="vertical-align:middle" name="GenderF" value="F" id="GenderF" onclick="genderToggle('F');" onblur="blankField(this);">&nbsp;Female
			</span>
			<span class="red_new" id="Gender_span" style="display:none;"> This field is mandatory to complete profile 
			</span>
			<div class="sp15"></div>
		</div>
		
		

	 -->
	 	~/if`
	 ~if $dOBFlag eq 1`
	<!--DATE OF BIRTH SECTION -->
		<div class="row4 no-margin-padding" id ="DobDiv">
			<label class="grey">&nbsp;&nbsp;&nbsp;Date of Birth :</label>
			<span style="width:50px;">
				<select name="day" id="day" style="width:100%;" onblur="blankField(this);">
					<option selected value="">Day</option>
						<option value="1" >1</option>
						<option value="2" >2</option>
						<option value="3" >3</option>
						<option value="4" >4</option>
						<option value="5" >5</option>
						<option value="6" >6</option>
						<option value="7" >7</option>
						<option value="8" >8</option>
						<option value="9" >9</option>
						<option value="10" >10</option>
						<option value="11" >11</option>
						<option value="12" >12</option>
						<option value="13" >13</option>
						<option value="14" >14</option>
						<option value="15" >15</option>
						<option value="16" >16</option>
						<option value="17" >17</option>
						<option value="18" >18</option>
						<option value="19" >19</option>
						<option value="20" >20</option>
						<option value="21" >21</option>
						<option value="22" >22</option>
						<option value="23" >23</option>
						<option value="24" >24</option>
						<option value="25" >25</option>
						<option value="26" >26</option>
						<option value="27" >27</option>
						<option value="28" >28</option>
						<option value="29" >29</option>
						<option value="30" >30</option>
						<option value="31" >31</option>
				</select>
			</span>
			<span style="width:64px; margin-left:7px; ">
				<select name="month" id="month" style="width:100%;" onblur="blankField(this);">
					<option selected value="">Month</option>
						<option value="1" >Jan</option>
						<option value="2" >Feb</option>
						<option value="3" >Mar</option>
						<option value="4" >Apr</option>
						<option value="5" >May</option>
						<option value="6" >Jun</option>
						<option value="7" >Jul</option>
						<option value="8" >Aug</option>
						<option value="9" >Sep</option>
						<option value="10">Oct</option>
						<option value="11">Nov</option>
						<option value="12">Dec</option>
				</select>
			</span>
			<span style="width:62px ;margin-left:7px;margin-right:30px; ">
				<select name="year" id="year" style="width:100%;" onblur="blankField(this);" >
					<option selected value="">Year</option>
						<option  value=1996 >1996 </option>
						<option  value=1995 >1995 </option>
						<option  value=1994 >1994 </option>
						<option  value=1993 >1993 </option>
						<option  value=1992 >1992 </option>
						<option  value=1991 >1991 </option>
						<option  value=1990 >1990 </option>
						<option  value=1989 >1989 </option>
						<option  value=1988 >1988 </option>
						<option  value=1987 >1987 </option>
						<option  value=1986 >1986 </option>
						<option  value=1985 >1985 </option>
						<option  value=1984 >1984 </option>
						<option  value=1983 >1983 </option>
						<option  value=1982 >1982 </option>
						<option  value=1981 >1981 </option>
						<option  value=1980 >1980 </option>
						<option  value=1979 >1979 </option>
						<option  value=1978 >1978 </option>
						<option  value=1977 >1977 </option>
						<option  value=1976 >1976 </option>
						<option  value=1975 >1975 </option>
						<option  value=1974 >1974 </option>
						<option  value=1973 >1973 </option>
						<option  value=1972 >1972 </option>
						<option  value=1971 >1971 </option>
						<option  value=1970 >1970 </option>
						<option  value=1969 >1969 </option>
						<option  value=1968 >1968 </option>
						<option  value=1967 >1967 </option>
						<option  value=1966 >1966 </option>
						<option  value=1965 >1965 </option>
						<option  value=1964 >1964 </option>
						<option  value=1963 >1963 </option>
						<option  value=1962 >1962 </option>
						<option  value=1961 >1961 </option>
						<option  value=1960 >1960 </option>
						<option  value=1959 >1959 </option>
						<option  value=1958 >1958 </option>
						<option  value=1957 >1957 </option>
						<option  value=1956 >1956 </option>
						<option  value=1955 >1955 </option>
						<option  value=1954 >1954 </option>
						<option  value=1953 >1953 </option>
						<option  value=1952 >1952 </option>
						<option  value=1951 >1951 </option>
						<option  value=1950 >1950 </option>
						<option  value=1949 >1949 </option>
						<option  value=1948 >1948 </option>
						<option  value=1947 >1947 </option>
						<option  value=1946 >1946 </option>
						<option  value=1945 >1945 </option>
						<option  value=1944 >1944 </option>
						<option  value=1943 >1943 </option>
						<option  value=1942 >1942 </option>
						<option  value=1941 >1941 </option>
						<option  value=1940 >1940 </option>
						<option  value=1939 >1939 </option>
				</select>
			</span>
			<span class="red_new" id="Dob_span" style="display:none;"> Please provide a valid Date of Birth.
			</span>
			<span class="red_new" id="DobM_span" style="display:none;"> You must be at least 21 years of age
			</span>
			<span class="red_new" id="DobF_span" style="display:none;"> You must be at least 18 years of age.
			</span>
			<div class="sp15">&nbsp;</div>
		</div>
	~/if`
	
<!--HEIGHT SECTION -->

	~if $heightFlag eq 1`
		<div class="row4 no-margin-padding">
			<label class="grey">Height :</label>
			<span>
				<select name="Height" id="Height" onblur="blankField(this);">
					<option value="" >Please Select</option>
				~$sf_data->getRaw('heightDD')`</select>
			</span>
			<span class="red_new" id="Height_span" style="display:none;"> This field is mandatory to complete profile 
			</span>
			<div class="sp15">&nbsp;</div>
		</div>
		
	~/if`
	
	~if $countryCityFlag eq 1`
<!--Country-SECTION -->
	
	<div class="row4 no-margin-padding width100">
		<label class="grey">Country living in :</label>
		<div id="county_arr">
			<span>
				<select  name="country_residence" id="country_residence" onchange="displayCityDdAndIsdCode();">~$sf_data->getRaw('COUNTRY_RES')`
				</select>
			</span>
		</div>
		<div class="sp15">&nbsp;</div>
	</div>
	
	
<!--City-SECTION -->

	<div id="city_padding"></div>
	<input type="hidden" name="city_residence_selected" value="~$CITY_SELECTED`" />
	<div class="row4  no-margin-padding width100" id="city_res_show_hide">
		<label class="grey">City living in :</label>
		<span id="city_india_visible" style="display:block">
			<select style="width:185px;" name="city_residence" id="city_residence" onchange="fetch_code('CITY',this.value);">
				
			</select>
		</span>
		<div id="city_residence_submit_err" style="display:~if $cityResidence_err`inline~else`none~/if`">
			<label class="l1">&nbsp;</label><div class="err_msg">Please select a city.</div>
		</div>
		<div class="sp15">&nbsp;</div>
	</div>
	
	~/if`
	
	~if $mStatusFlag eq 1`
	<!--Marital Status-SECTION -->

	<div class="row4 no-margin-padding width100">
		<label class="grey">Marital Status :</label>
		<div id="mStatus_arr">
			<span>
				<select  name="mStatus_residence" id="mStatus_residence" onchange="mstatusChildren(this);" onblur="blankField(this);">
					<option value="" selected>Please Select</option>
					~$sf_data->getRaw('mstatusDD')`
				</select>
			</span>
			<span class="red_new" id="mStatus_residence_span" style="display:none;"> This field is mandatory to complete profile 
			</span>
			<span class="red_new" id="mStatus_residence_married_span" style="display:none;"> Please choose married only if you are muslim. 
			</span>
		</div>
		<div class="sp15">&nbsp;</div>
	</div>
	
	
	<!--Have Childern-SECTION -->

	<div class="row4 no-margin-padding width100" id ="Children" style="display:none;" >
		<label class="grey">Have Children :</label>
		<div id="haveChildern_arr">
			<span>
				<select  name="haveChildern_residence" id="haveChildern_residence" onblur="blankField(this);">
					<option value="" selected>Please Select</option>
					~$sf_data->getRaw('havechildDD')`
				</select>
			</span>
			<span class="red_new" id="haveChildern_residence_span" style="display:none;"> This field is mandatory to complete profile 
			</span>
		</div>
		<div class="sp15">&nbsp;</div>
	</div>
	
	
	~/if`

	~if $mTongueFlag eq 1`
	<!-- Mtongue-->
	<div class="row4 no-margin-padding width1001">
		<label class="grey">Mother tongue :</label>
		<span>
			<select id="mTongue" name="mTongue" onblur="blankField(this);">
				<option value=""selected>Please Select</option>
				~$sf_data->getRaw('MTONGUE')`
			</select>
		</span>
		<span class="red_new" id="mTongue_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
		<div class="sp15">&nbsp;</div>
	</div>
	~/if`
	~if $religionFlag eq 1`

	<!-- Religion Caste-->
	<div id="religionSection">
	<div class="row4 no-margin-padding">
		<label class="grey">Religion :</label>
		<span>
			<select onchange="changeContent();" id="Religion" name="Religion" onblur="blankField(this);">
				<option value="" selected>Please Select</option>
				~$sf_data->getRaw('RELIGION')`
			</select>
		</span>
		<span class="red_new" id="Religion_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
	</div>
	<div class="sp15">&nbsp;</div>


	<!--Hindu-->
	<div name="Hindu" id="Hindu">
	<div class="row4 no-margin-padding">
		<label class="grey">Caste :</label>
		<span id="casteHindu">
		<select name="Caste_hindu" id="Caste_hindu" onblur="blankField(this);">~$sf_data->getRaw('CASTE_HINDU')`</select>
		</span>
		<span class="red_new" id="Caste_hindu_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
	</div>
	<div class="sp15">&nbsp;</div>
	</div>
	<!--Hindu-->
	<!--Jain-->
	<div name="Jain" id="Jain">
	<div class="row4 no-margin-padding">
		<label class="grey">Caste :</label>
		<span id="casteJain" style="display:block">
			<select name="Caste_jain" id="Caste_jain" onblur="blankField(this);">~$sf_data->getRaw('CASTE_JAIN')`</select>
		</span>
		<span class="red_new" id="Caste_jain_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
		</div>
	<div class="sp15">&nbsp;</div>
	</div>
	<!--Jain-->
	<!--Christian-->
	<div name="Christian" id="Christian">
	<div class="row4 no-margin-padding">
		<label class="grey">Sect :</label>
		<span id="casteChristian">
			<select name="Caste_christian" id="Caste_christian" onblur="blankField(this);" >~$sf_data->getRaw('CASTE_CHRISTIAN')`</select>
		</span>
		<span class="red_new" id="Caste_christian_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
	</div>
	<div class="sp15">&nbsp;</div>
	</div>
	<!--Christian-->
	<!--Muslim-->
	<div name="Muslim" id="Muslim">
	<div class="row4 no-margin-padding">
		<label class="grey">Sect :</label>
		<span id="casteMuslim">
			<select name="Caste_muslim" id="Caste_muslim" onblur="blankField(this);" >~$sf_data->getRaw('CASTE_MUSLIM')`</select>
		 </span>
		 <span class="red_new" id="Caste_muslim_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
	</div>
	<div class="sp15">&nbsp;</div>
	</div>
	<!--Muslim-->
	<!--Sikh-->
	<div name="Sikh" id="Sikh">
	<div class="row4 no-margin-padding">
		<label class="grey">Caste :</label>
		<span id="casteSikh">
			<select name="Caste_sikh" id="Caste_sikh" onblur="blankField(this);" >~$sf_data->getRaw('CASTE_SIKH')`</select>
		</span>
		<span class="red_new" id="Caste_sikh_span" style="display:none;"> This field is mandatory to complete profile 
		</span>
	</div>
	<div class="sp15">&nbsp;</div>
	</div>
	<!--Sikh-->
	</div>
	~/if`

	~if $phoneFLag eq 1`
	<!--LANDLINE NUMBER-SECTION -->
	
	<input type="hidden" value="" name="isd_change_src" id="isd_change_src"/>
	<div class="row4  no-margin-padding width100">
		<label class="grey" ~if $post_login eq 1`style="color:red_new;"~/if`><i class="btn-archive"></i> Landline number :</label>
		<input type="hidden" name="ISD" value="~$country_code`" id="country_code">
		<span class="widthauto">
			<span class="widthauto">
				<div>Country</div>
				<div>
					<input class="combo-small-more2" type="text" size="3" name="phone_isd" id="phone_isd" value="~$country_code_mob`" onKeyUp="change_isd(this.value,'phone');" onblur="isd_verify('P',this);">
				</div>
			</span>
			<span class="widthauto mar_left_10">
				<div>Area</div>
				<div>
					<input type="text"  name="State_Code" id="state_code" value="" class="combo-small-more2">
				</div>
			</span>
			<span class="widthauto mar_left_10">
				<div>Number</div>
				<div>
					<input type="text" name="Phone" value="" id="Phone" onblur="phoneJCheck('L');" maxlength="12" style="height:20px;width:150px;">
				</div>
			</span>
		</span>
		
<!--LANDLINE NUMBER-SHOW PHONE SECTION -->

		<br>&nbsp;
		<i class="widthauto" mar_left_10 setmargin >
			<select name="Showphone" id="dont_show">
				<option style="font-size:10px;" value="Y" ~if $SHOWPHONE_RES eq "Y" or $SHOWPHONE_RES eq ""`selected~/if`>Show to All Paid Members</option> 
				<option style="font-size:10px;" value="C" ~if $SHOWPHONE_RES eq "C"`selected~/if`>Show to only Members I Accept / Express Interest In</option> 
				<option style="font-size:10px;" value="N" ~if $SHOWPHONE_RES eq "N"`selected~/if`>Don't show to anybody</option>
				~if $CALL_NOW`<option style="font-size:10px;" value="CN" ~if $SHOWPHONE_RES eq "CN"`selected~/if` id="call_anonym">Receive calls anonymously</option>~/if`
			</select> &nbsp;<i class="btn-key">&nbsp;</i>
		</i>
		
<!--LANDLINE NUMBER ERROR-SECTION -->

		<div class="red_new clear" id="phone_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please type in a valid phone number.
			<div class="sp5"></div>
		</div>
		<div class="red_new clear" id="phone_isd_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide an ISD code.
			<div class="sp5"></div>
		</div>
		<div class="red_new clear" id="phone_isd_valid_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide a valid ISD code.
			<div class="sp5"></div>
		</div>
		<div class="red_new clear" id="phone_name_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please enter the phone number owner's name.
			<div class="sp5"></div>
		</div>
		<div class="red_new clear" id="phone_in_name_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Phone number owner's name cannot contain special characters.
			<div class="sp5"></div>
		</div>
		<div class="red_new clear" id="state_code_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide std code in correct format.
			<div class="sp5"></div>
		</div>
		<div class="sp15"></div>	
	</div>	
	
	
	<!--MOBILE NUMBER-SECTION -->	
	
	<div class="row4  no-margin-padding width100">
		<label class="grey" ~if $post_login eq 1`style="color:red_new;"~/if`><i class="btn-archive"></i> Mobile number :</label>
		<span class="containerSp lf">
			<span class="widthauto">Country<br><input class="combo-small-more2" type="text" size="3" name="mobile_isd" id="country_code_mob" value="~$country_code`" onKeyUp="change_isd(this.value,'mobile');" onblur="isd_verify('M',this);">
			</span>
			<span class="widthauto mar_left_10"><div>Number</div><div><input type="text" class="textbox-small" name="Mobile" value="" id="Mobile" onblur="phoneJCheck('M');" maxlength="15"></div>
			</span>
		</span>

<!--MOBILE NUMBER-SHOW PHONE SECTION -->
		
		<input type="hidden" name="ISDMOB" value="~$country_code`" id="country_code_mob1">
		<br>
		<i class="widthauto mar_left_10 setmargin">
			<select name="Showmobile" id="dont_show1">
				<option style="font-size:10px;" value="Y" ~if $SHOWPHONE_MOB eq "Y" or $SHOWPHONE_MOB eq ""`selected~/if`>Show to All Paid Members</option> 
				<option style="font-size:10px;" value="C" ~if $SHOWPHONE_MOB eq "C"`selected~/if`>Show to only Members I Accept / Express Interest In</option>
				<option style="font-size:10px;" value="N" ~if $SHOWPHONE_MOB eq "N"`selected~/if` >Don't show to anybody</option>
				~if $CALL_NOW`<option style="font-size:10px;" value="CN" ~if $SHOWPHONE_MOB eq "CN"`selected~/if`>Receive calls anonymously</option> ~/if`
			</select> &nbsp;<i class="btn-key">&nbsp;</i>
		</i>

<!--MOBILE NUMBER- ERROR SECTION -->

		<div class="clear"></div>
		<div class="red_new" id="mobile_name_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please enter the mobile number owner's name.
		</div>
		<div class="red_new" id="mobile_in_name_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Mobile number owner's name cannot contain special characters.
		</div>

		<div class="red_new" id="international_mobile_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;International Mobile number should contain atleast 5 digits.
		</div>
		<div class="red_new" id="mobile_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please type in a valid mobile number.
		</div>
		<div class="red_new" id="mobile_isd_valid_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide a valid ISD code.
		</div>
		<div class="red_new" id="mobile_isd_span" style="display:none; margin:0 0 0 203px;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide an ISD code.
		</div>
		<div class="sp15">&nbsp;</div>
	</div>

	
	~/if`
	
	<div class="row4 no-margin-padding"  ~if $sf_request->getParameter('from_fto') && $edu_level_new neq ''`style="display:none"~/if`>
		<label class="grey">&nbsp;&nbsp;&nbsp;Highest Degree :</label>
		<span>
		<select name="Education_Level" id="Education_Level"  onblur="blankField(this);">~if $edu_level_new eq '' or $edu_level_new eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('education_level')`
		</select>
		</span>
		<span class="red_new" id="Education_Level_span" style="display:none;"> This field is mandatory to complete profile </span>
		
	<div class="sp15">&nbsp;</div>
	</div>
	<div class="row4 no-margin-padding">
	<label class="grey">Occupation :</label>
	<span>
	<select name="Occupation" onblur="blankField(this);">~if $occ_val eq '' or $occ_val eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('occupation')`</select>
	</span>
		<span class="red_new" id="Occupation_span" style="display:none;"> This field is mandatory to complete profile </span>
	<div class="sp15">&nbsp;</div>
	</div>
	<div class="row4 no-margin-padding">
	<label class="grey">&nbsp;&nbsp;Annual Income :</label>
	<span>
	<select  name="Income" onblur="blankField(this);">~if $income_val eq '' or $income_val eq 0`<option value="">Select</option>~/if`~$sf_data->getRaw('INCOME')`</select>
	</span>
		<span class="red_new" id="Income_span" style="display:none;"> This field is mandatory to complete profile </span>
	<div class="sp15">&nbsp;</div>
	</div>
	<div class="row4 no-margin-padding">
	
	<label id="AboutDisplay" class="grey">About ~if $RELATION eq '1'`Me~else`~if $GENDER eq 'F'`Her~else`Him~/if`~/if` : </label>
	<label  id="AboutMe" style="display: none;" class="grey">About Me : </label>
	<label  id="AboutHim" style="display: none;" class="grey">About Him : </label>
	<label  id="AboutHer" style="display: none;" class="grey">About Her : </label>
		<div>
			<input type="checkbox" class="chbx" id="checkboxId" onclick="javascript:checkboxClickHandler();"></input>
			<select class="combo-small-more" onchange="javascript:languageChangeHandler('languageDropDown4');" id="languageDropDown4" title="Please choose your language for about self">
				<option value="hi">हिन्दी</option>
				<option value="ta">தமிழ்</option>
				<option value="te">తెలుగు</option>
				<option value="kn">ಕನ್ನಡ</option>
				<option value="ml">മലയാളം</option>
			</select>
			<div class="sp5">&nbsp;</div>
			<textarea class="textarea-big-2" style="width:400px;height:135px;margin-left:149px;" id="about_dpp" name="Information" onKeyup="changeCount();return ismaxlength(this);" onblur="blankField(this);" maxlength="5000">~$sf_data->getRaw('YOURINFO')`</textarea>
			<span class="green ttip_top fr" style="width:139px!important;margin-right:10px;font-size:14px;">
				<b><strong>Tip :</strong></b> Write about your~if $for_about_value eq 2` daughter's~elseif $for_about_value eq 7` son's~elseif $for_about_value eq 3 or $for_about_value eq 6` sibling's~elseif $for_about_value eq 4` friend's~elseif $for_about_value eq 5` client's~/if` interests, work, education, family background, parents, siblings etc.
			</span>
		</div>
	</div>
	<div class="sp5"></div>
	<div class="row4 no-margin-padding fl">
		<div style="padding:0 0 0 146px;">
			<span class="fs13 fl" style="width:265px;" name="wordcount" id="wordcount" ~if $CHARACTERS gt 100`style="color:green;"~else`style="color:red;"~/if`>&nbsp;~$INFOLEN` Characters&nbsp;- minimum 100 characters</span>
			<i class="mar_left_38" style="display: block;" id="spellcheck2">
			<img onclick="set_id(1);spellcheckxx();return false;" src="~$IMG_URL`/profile/images/registration_new/spell-check.gif" style="cursor:pointer;"/>
			</i>
		</div>
		<div class="sp5"></div>
		<div style="padding:0 0 0 146px;">
		<span class="red_new fs13 fl" id="Information_span" style="display:none;">
			<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/> &nbsp;Please write atleast 100 characters.
			</span>
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
</div>
<script>
if(dID('countryCityFlag').value==1)
	populate_city();
if(dID('genderValue').value=="F")
	$('#mStatus_residence option[value="M"]').remove();
// need to hide gender depending upon relation selected
function relationGender(element)
{
	var relation=element.options[element.selectedIndex].value;
	var hasOption = $('#mStatus_residence option[value="M"]');
	if(relation =="2" ||relation =="2D" || relation =="6" || relation =="6D")
	{
		document.getElementById('GenderDiv').style.display="none";
		if(relation =="2" ||relation =="6")
		{
			//document.getElementById('GenderF').checked=false;
			//document.getElementById('GenderM').checked=true;
			document.getElementById('AboutHim').style.display="block";
			document.getElementById('AboutDisplay').style.display="none";
			document.getElementById('AboutHer').style.display="none";
			document.getElementById('AboutMe').style.display="none";
			document.getElementById('genderValue').value="M";
			if (hasOption.length == 0)
				$("#mStatus_residence").append('<option value="M">Married</option>');
		}
		else
		{
			//document.getElementById('GenderM').checked=false;
			//document.getElementById('GenderF').checked=true;
			document.getElementById('AboutHim').style.display="none";
			document.getElementById('AboutDisplay').style.display="none";
			document.getElementById('AboutHer').style.display="block";
			document.getElementById('AboutMe').style.display="none";
			document.getElementById('genderValue').value="F";
			if (hasOption.length != 0)
				$('#mStatus_residence option[value="M"]').remove();
		}
	}
	else
	{
		document.getElementById('genderValue').value="";
		document.getElementById('GenderDiv').style.display="block";
		document.getElementById('GenderM').checked=false;
		document.getElementById('GenderF').checked=false;
		
		if(relation =="1")
		{
			document.getElementById('AboutMe').style.display="block";
			document.getElementById('AboutHim').style.display="none";
			document.getElementById('AboutDisplay').style.display="none";
			document.getElementById('AboutHer').style.display="none";
		}	
	}
}
//gender toggle between male and female
function genderToggle(gender)
{
	document.getElementById("Gender_span").style.display="none";
	 var hasOption = $('#mStatus_residence option[value="M"]');
	 
	if(gender=="M")
	{
		document.getElementById('GenderF').checked=false;
		document.getElementById('genderValue').value="M";
		if (hasOption.length == 0)
			$("#mStatus_residence").append('<option value="M">Married</option>');
		
		if(document.getElementById("Realtionship").value==4 || document.getElementById("Realtionship").value==5)
		{
			document.getElementById('AboutMe').style.display="none";
			document.getElementById('AboutHim').style.display="block";
			document.getElementById('AboutDisplay').style.display="none";
			document.getElementById('AboutHer').style.display="none";
		}
		
	}
	else if(gender=="F")
	{
		document.getElementById('GenderM').checked=false;
		document.getElementById('genderValue').value="F";
		if (hasOption.length != 0)
			$('#mStatus_residence option[value="M"]').remove();
		
		if(document.getElementById("Realtionship").value==4 || document.getElementById("Realtionship").value==5)
		{
			document.getElementById('AboutMe').style.display="none";
			document.getElementById('AboutHim').style.display="none";
			document.getElementById('AboutDisplay').style.display="none";
			document.getElementById('AboutHer').style.display="block";
		}
	}
}

function mstatusChildren(element)
{
	document.getElementById("mStatus_residence_married_span").style.display="none";
	document.getElementById("mStatus_residence_span").style.display="none";
	document.getElementById("haveChildern_residence_span").style.display="none";
	var mStatus=element.options[element.selectedIndex].value;
	
	if(mStatus =="N")
	{
		document.getElementById('Children').style.display="none";
	}
	else if(document.getElementById("mStatus_residence").value=="M" && document.getElementById("genderValue").value=="M" && document.getElementById("religionValue").value!="2" )
	{
		document.getElementById("mStatus_residence_married_span").style.display="block";
		document.getElementById('Children').style.display="block";
	}
	else
		document.getElementById('Children').style.display="block";
}
function mstatusDropDown()
{
	if(dID('genderValue').value=="F")
		$('#mStatus_residence option[value="M"]').remove();
	else if(dID('genderValue').value=="M")
		$("#mStatus_residence").append('<option value="M">Married</option>');
}	
function displayCityDdAndIsdCode(){
	display_city_dd();
	//alert(docF.country_residence.value);
	fetch_code("COUNTRY",docF.country_residence.value);
}
function change_city()
{
    var country_code = dID('country_residence').value;
    request_url = "~$SITE_URL`/profile/edit_profile.php?Only_city=1&Country_code="+country_code;
    sendRequest('GET',request_url);
}
function closeLayer_changeCity()
{
    $.colorbox.close();
    window.location="/profile/viewprofile.php?ownview=1&EditWhatNew=JST2";
}

function validate_phone_mobile(phone,mobile)
{
    var country_val = dID('country_code').value;
    std_code=dID('state_code').value;
    if((phone=="") && (mobile==""))
    {	
        return "PM";
    }
    else
    {	
        if(phone!="")
        {
            if(phone.length < 6)
            {
                return "P";
            }
            var x = phone;
            var filter  = /^[0-9]+$/;
            if (!filter.test(x))
            {
                return "P";
            }
            if(!filter.test(std_code))
                return "S";
            //if(dID('junk').value=='JL')
            //	return "P";
        }
        if(mobile!="")
        {
            if(mobile.length != 10 && country_val == "+91")
                return "M";
            else if(mobile.length < 5 && country_val != "+91")
                return "IM";
            var x = mobile;
            var filter  = /^[0-9]+$/;
            if (!filter.test(x))
            {
                return "M";
            }
        }
    }
    return "OK";
}
function isd_verify_on_submit(){
  var err_elem=$("#isd_change_src").val();
  var isd_code=$("#country_code").val();
  var err=isd_check(isd_code);
  if(err_elem=="")
    err_elem='phone';
  var span_id="#"+err_elem;
  var err_exist=false;
  switch(err){
    case "IP":
	 span_id=span_id+"_isd_span";
	 $(span_id).css("display","block");
	 err_exist=true;
	break;
	case "IV":
	 span_id=span_id+"_isd_valid_span";
	 $(span_id).css("display","block");
	 err_exist=true;
	break;
	default:
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  $("#mobile_isd_span").css("display","none");
	  $("#moble_isd_valid_span").css("display","none");
	//  $("#alt_mobile_isd_span").css("display","none");
	  //$("#alt_mobile_isd_valid_span").css("display","none");
  }
  return err_exist;
}
function isd_verify(pos,elem){
    var isd_code=elem.value;
	var res=isd_check(isd_code);
	switch(pos){
	case "P":
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  //$("#alt_mobile_isd_span").css("display","none");
	 // $("#alt_mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	  $("#phone_isd_span").css("display","block");
	  $("#phone_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","block");
	  break;
	  default:
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	case "M":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	 // $("#alt_mobile_isd_span").css("display","none");
	 // $("#alt_mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	  $("#mobile_isd_span").css("display","block");
	  $("#mobile_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	  $("#mobile_isd_valid_span").css("display","block");
	  $("#mobile_isd_span").css("display","none");
	  break;
	  default:
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	case "AM":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	 // $("#alt_mobile_isd_span").css("display","block");
	 // $("#alt_mobile_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	//  $("#alt_mobile_isd_valid_span").css("display","block");
	//  $("#alt_mobile_isd_span").css("display","none");
	  break;
	  default:
	//  $("#alt_mobile_isd_span").css("display","none");
	//  $("#alt_mobile_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	}
}
function isd_check(isd_code){
				if(isd_code=="")
				 return "IP";
				var isd_filter=/^[+0-9][0-9]*$/;
				if(!isd_filter.test(isd_code))
					return "IV";
				isd_code=isd_code.replace("+","");
				var isd_zero_filter=/^0+/;
				if(isd_zero_filter.test(isd_code)){
					var isd_zero_match=isd_code.match(isd_zero_filter);
					var leading_zeros=isd_zero_match[0];
					isd_code=isd_code.replace(leading_zeros,"");
				}
				if(isd_code.length>3 || isd_code.length==0)
					return "IV";
				return "OK";
}
function phoneJCheck(type)
{
    var phone_mob = validate_phone_mobile(dID('Phone').value,document.getElementById('Mobile').value);
    if(phone_mob != "OK")
    {       
        if(phone_mob == "PM")
        {
            dID('phone_in_name_span').style.display="none";
            dID('mobile_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('mobile_span').style.display="block";
            dID('state_code_span').style.display="none";
        }
        else if(phone_mob == "P")
        {
            dID('phone_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('Phone').focus();
        }
        else if(phone_mob == "S")
        {
            dID('state_code_span').style.display="block";
            dID('mobile_span').style.display="none";
            dID('state_code').focus();
        }
        else if(phone_mob == "M")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('state_code_span').style.display="none";
            dID('mobile_span').style.display="block";
            dID('international_mobile_span').style.display="none";
            dID('Mobile').focus();
        }
        else if(phone_mob == "IM")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="none";
            dID('international_mobile_span').style.display="block";
            dID('Mobile').focus();
        }
        return false;
    }
    to_send_ajax_req=true;
    if(type=='L')
        phone =dID('Phone').value;
    else if(type=='M')
        phone =dID('Mobile').value;
    if(phone == '')
        to_send_ajax_req=false;
    if(to_send_ajax_req){	
        var str ="&phone="+phone+"&type="+type;

       // dID('img_sav').style.display="none";    
        //dID('img_test1').style.display="block";

        request_url = "~$SITE_URL`/profile/edit_profile.php?Junkcheck=1"+str;
        $.ajax({
url: request_url,
success: function(data){
show_junk_number(data);
}
});
}

//dID('alt_mobile_in_name_span').style.display="none";
//dID('alt_mobile_span').style.display="none";
//dID('alt_international_mobile_span').style.display="none";
//dID('alt_mobile_name_span').style.display="none";
dID('mobile_in_name_span').style.display="none";
dID('mobile_span').style.display="none";
dID('international_mobile_span').style.display="none";
dID('phone_in_name_span').style.display="none";
dID('phone_span').style.display="none";
dID('mobile_name_span').style.display="none";
dID('phone_name_span').style.display="none";
dID('state_code_span').style.display="none";
}
function show_junk_number(response)
{
    dID('junk').value='';
    dID('mobile_span').style.display="none";
  //  dID('alt_mobile_span').style.display="none";
    dID('phone_span').style.display="none";
    if(response=='JM'){
        dID('mobile_span').style.display="block";
    }   
    else if(response=='JL')	
        dID('phone_span').style.display="block";
    if(response !='NJ')
        dID('junk').value=response;

   // dID('img_test1').style.display="none";
    //dID('img_sav').style.display="block";
    return false;
}

</script>

<script>
	if(dID('religionFlag').value==1)
		showContent();
</script>
