<style>
	.rsp{padding-top:6px;}
	#box {width:285px; height:auto; background-color:#fdea66;  border:1px solid #f4d95a;
	padding:15px 17px; font-family:Arial, Helvetica, sans-serif; color:#343434}
	#box div {float:left !important;}
	.fs12 { font-size:12px;} .fs11 { font-size:11px;list-style-type:decimal;}
	.b { font-weight:bold;} .i { font-style:italic}
	.sp5_help{ clear:both; display:block; overflow:hidden; height:5px;}
	.fl_help{float:left !important;}
	.black {color:#000;}.red {color:#e93a3e}
	.list_imp {margin-left:17px; _margin-left:22px; clear: left; display: block;list-style: outside;}
	*+html .list_imp {margin-left:22px;}
	.text_new_help {color:#ee0002; position:relative !important; top:-4px !important;margin-bottom:-16px; margin-left:5px !important;left:-7px !important; width:10px !important;}
	span.arrw_help {display: block !important;position: relative !important;right: 14px !important;top: 94px !important;z-index: 1000 !important; width:13px !important;}
</style>
<script>
	
function show_help_box(para)
{
	if(para==1)
		document.getElementById('helpbox').style.display='block';
	else
		document.getElementById('helpbox').style.display='none';
}

function show_alter(field,action)
{
		document.getElementById(field).style.display="block";
		if(field=='alter_mobile_section')
		{
			document.getElementById('add_more_mobile').style.display="none";
			document.getElementById('hide_more_mobile').style.display="block";
		}
		else if(field=='alter_messenger_section')
		{
			document.getElementById('add_more_messenger').style.display="none";
			document.getElementById('hide_more_messenger').style.display="block";
		}

		if(action=='HIDE' && field=='alter_mobile_section')
		{
			document.getElementById(field).style.display="none";
			document.getElementById('add_more_mobile').style.display="block";
			document.getElementById('hide_more_mobile').style.display="none";
			document.getElementById('ALT_MOBILE_ISD').value="";
			document.getElementById('ALT_MOBILE').value="";
			document.getElementById('ALT_Showmobile').value="";
			document.getElementById('ALT_MOBILE_NUMBER_OWNER').value="2";
			document.getElementById('ALT_MOBILE_OWNER_NAME').value="";
		}
		else if(action=='HIDE' && field=='alter_messenger_section')
		{
			document.getElementById(field).style.display="none";
			document.getElementById('hide_more_messenger').style.display="none";
			document.getElementById('add_more_messenger').style.display="block";
			document.getElementById('Alt_Messenger_ID').value="";
			document.getElementById('alt_mess_channel').value="";
			document.getElementById('Alt_showMessenger').value="";
		}
}
function validate()
{
	var email_id=document.getElementById('email').value;
	var theStr = new String(email_id);
        var index = theStr.indexOf("@")+1;
        var index1 = theStr.indexOf(".");
        if(theStr.substring(index,index1)=='jeevansathi' && document.getElementById('source').value!='ofl_prof')
        {
                document.getElementById('img_avail').style.display="none";
                document.getElementById('my_inemail').style.display="none";
                document.getElementById('my_email').style.display="none"; 
                document.getElementById('contain_js').style.display="block";
                document.getElementById('email').focus();
                return false;
        }

	if(document.getElementById('junk').value=='JM'){
                document.getElementById('mobile_in_name_span').style.display="none";
                document.getElementById('mobile_span').style.display="block";
                document.getElementById('international_mobile_span').style.display="none";
                document.getElementById('Mobile').focus();
		return false;
	}

	if(document.getElementById('junk').value=='JL'){
                document.getElementById('phone_in_name_span').style.display="none";
                document.getElementById('phone_span').style.display="block";
                document.getElementById('Phone').focus();
		return false;
	}

	var phone_mob = validate_phone_mobile(document.getElementById('Phone').value,document.getElementById('Mobile').value);
	if(phone_mob != "OK")
	{	
		if(phone_mob == "PM")
		{
			document.getElementById('phone_in_name_span').style.display="none";
			document.getElementById('mobile_in_name_span').style.display="none";
			document.getElementById('phone_span').style.display="block";
			document.getElementById('mobile_span').style.display="block";
			document.getElementById('Phone').focus();
		}
		else if(phone_mob == "P")
		{
			document.getElementById('phone_in_name_span').style.display="none";
			document.getElementById('phone_span').style.display="block";
			document.getElementById('Phone').focus();
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
		return false;
	}
	else
	{
		if((document.getElementById('PHONE_OWNER_NAME').value == '')&&(document.getElementById('Phone').value != ''))
		{
			document.getElementById('phone_span').style.display="none";
			document.getElementById('phone_in_name_span').style.display="none";
			document.getElementById('phone_name_span').style.display="block";
        	        document.getElementById('PHONE_OWNER_NAME').focus();
			return false;
		}
		else if((document.getElementById('MOBILE_OWNER_NAME').value == '')&&(document.getElementById('Mobile').value != ''))
		{	
			document.getElementById('phone_span').style.display="none";
			document.getElementById('phone_name_span').style.display="none";
			document.getElementById('phone_in_name_span').style.display="none";
			document.getElementById('mobile_in_name_span').style.display="none";
                        document.getElementById('mobile_span').style.display="none";
			document.getElementById('mobile_name_span').style.display="block";
                        document.getElementById('MOBILE_OWNER_NAME').focus();
                        return false;
		}
		else 
		{
			var x = document.getElementById('PHONE_OWNER_NAME').value;
			var y = document.getElementById('MOBILE_OWNER_NAME').value;
			var lwr = "abcdefghijklmnopqrstuvwxyz ";
			var upr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ ";
        	        if(!isValid(x,lwr+upr))
                	{
				document.getElementById('phone_span').style.display="none";
				document.getElementById('phone_name_span').style.display="none";	
				document.getElementById('phone_in_name_span').style.display="block";
	                        document.getElementById('PHONE_OWNER_NAME').focus();
        	                return false;
			}
			else if(!isValid(y,lwr+upr))
                        {       
				document.getElementById('mobile_span').style.display="none";
				document.getElementById('mobile_name_span').style.display="none";
				document.getElementById('phone_in_name_span').style.display="none";
                                document.getElementById('mobile_in_name_span').style.display="block";
                                document.getElementById('MOBILE_OWNER_NAME').focus();
                                return false;
                        }
		}
	}
	var mid=document.getElementById('Messenger_ID').value;
	if(checkmid(mid))
	{
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9])+$/;
		if(checkemail(mid) && filter.test(mid) && mid.charAt(0)!=".")
		{
			document.getElementById('m_id').style.display="none";
                        return true;
		}
		else
		{
			document.getElementById('m_id').style.display="block";
			return false;
		}
	}       
}
function isValid(parm,val) {
if (parm == "") return true;
for (i=0; i<parm.length; i++) {
if (val.indexOf(parm.charAt(i),0) == -1) return false;
}
return true;
}
function checkmid(mid)
{
        var result = false;
        var theStr = new String(mid);
        var index = theStr.indexOf("@");
        if (index != -1)
                result = true;
        return result;
}
function checkemail(emailadd)
{
        var result = false;
        var theStr = new String(emailadd);
        var index = theStr.indexOf("@");
        if (index > 0)
        {
        var pindex = theStr.indexOf(".",index);
        if ((pindex > index+1) && (theStr.length > pindex+2))
                result = true;
        }
        return result;
}
function validate_phone_mobile(phone,mobile)
{
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
			//if(document.getElementById('junk').value=='JL')
			//	return "P";
                }
                if(mobile!="")
                {
			var country_val = document.getElementById('Country_Residence').value;

                        if(mobile.length < 10 && country_val == "India")
                                return "M";
			else if(mobile.length < 5 && country_val != "India")
			  	return "IM";
                        var x = mobile;
                        var filter  = /^[0-9]+$/;
                        if (!filter.test(x))
                        {
                                return "M";
                        }
			//if(document.getElementById('junk').value=='JM')
			//	return "M";
                }
        }
        return "OK";
}
</script>
<script language="JavaScript" SRC="~$IMG_URL`/profile/js/~$ajax_js`"></script>
<script>
function phoneJCheck(type)
{
        var phone_mob = validate_phone_mobile(document.getElementById('Phone').value,document.getElementById('Mobile').value);
        if(phone_mob != "OK")
        {       
                if(phone_mob == "PM")
                {
                        document.getElementById('phone_in_name_span').style.display="none";
                        document.getElementById('mobile_in_name_span').style.display="none";
                        document.getElementById('phone_span').style.display="block";
                        document.getElementById('mobile_span').style.display="block";
                        document.getElementById('Phone').focus();
                }
                else if(phone_mob == "P")
                {
                        document.getElementById('phone_in_name_span').style.display="none";
                        document.getElementById('phone_span').style.display="block";
                        document.getElementById('Phone').focus();
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
                return false;
        }

	if(type=='L')
		phone =document.getElementById('Phone').value;
	else if(type=='M')
		phone =document.getElementById('Mobile').value;
        var str ="&phone="+phone+"&type="+type;

        document.getElementById('img_sav').style.display="none";        
        document.getElementById('img_test1').style.display="block";

        request_url = "edit_profile.php?Junkcheck=1"+str;
        sendRequest('GET',request_url);

        if(document.getElementById('junk').value=='JM'){
                document.getElementById('mobile_in_name_span').style.display="none";
                document.getElementById('mobile_span').style.display="block";
                document.getElementById('international_mobile_span').style.display="none";
                document.getElementById('Mobile').focus();
		//document.getElementById('img_sav').innerHTML='<input type="button" class="b green_btn" value="Save" style="width:60px;">';
                return false;   
        }

        if(document.getElementById('junk').value=='LM'){
                document.getElementById('phone_in_name_span').style.display="none";
                document.getElementById('phone_span').style.display="block";
                document.getElementById('Phone').focus();
		//document.getElementById('img_sav').innerHTML='<input type="button" class="b green_btn" value="Save" style="width:60px;">';
                return false;
        }

	document.getElementById('mobile_in_name_span').style.display="none";
        document.getElementById('mobile_span').style.display="none";
        document.getElementById('international_mobile_span').style.display="none";
	document.getElementById('phone_in_name_span').style.display="none";
        document.getElementById('phone_span').style.display="none";
	document.getElementById('mobile_name_span').style.display="none";
	document.getElementById('phone_name_span').style.display="none";
}
function change_city()
{
        var country_code = document.getElementById('Country_Residence').value;
        request_url = "edit_profile.php?Only_city=1&Country_code="+country_code;
        sendRequest('GET',request_url);
}
function show_code()
{
        var city_code = document.getElementById('City_arr').value;
        request_url = "edit_profile.php?Only_city2=1&City_code="+city_code;
        sendRequest('GET',request_url);
}
function show_lo()
{
	var email_id=document.getElementById('email').value;
	document.getElementById('img_avail').style.display="none";
        document.getElementById('img_sav').style.display="none";        
        document.getElementById('img_test1').style.display="block";
        request_url = "edit_profile.php?verify_email=1&email_id="+email_id;
        sendRequest('GET',request_url);
	var theStr = new String(email_id);
        var index = theStr.indexOf("@")+1;
	var index1 = theStr.indexOf(".");
	if(email_id == '')
        {
		document.getElementById('inv').value=0;
		document.getElementById('img_avail').style.display="none";
                document.getElementById('my_inemail').style.display="none";
                document.getElementById('my_email').style.display="block"; 
		document.getElementById('contain_js').style.display="none";
                document.getElementById('email').focus();
                return false;
        }
	else if(theStr.substring(index,index1)=='jeevansathi' && document.getElementById('source').value!='ofl_prof')
	{
		document.getElementById('inv').value=1;
                document.getElementById('img_avail').style.display="none";
                document.getElementById('my_inemail').style.display="none";
                document.getElementById('my_email').style.display="none"; 
		document.getElementById('contain_js').style.display="block";
                document.getElementById('email').focus();
                return false;
	}
        else if(!checkemail(email_id))
        {
		document.getElementById('inv').value=1;
                document.getElementById('my_inemail').style.display="block";
                document.getElementById('my_email').style.display="none";
		document.getElementById('contain_js').style.display="none";
                document.getElementById('email').focus();
                return false;
        }
        else    
        {
                var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9])+$/;
                if(!filter.test(email_id))
                {
			document.getElementById('inv').value=1;
                        document.getElementById('my_email').style.display="none";
                        document.getElementById('my_inemail').style.display="block";
			document.getElementById('contain_js').style.display="none";
                        document.getElementById('email').focus();
                        return false;
                }
                else
                {
			document.getElementById('inv').value=0;
			document.getElementById('img_avail').style.display="none";
                        document.getElementById('my_email').style.display="none";
                        document.getElementById('my_inemail').style.display="none";
			document.getElementById('contain_js').style.display="none";
                }
        }
}
function closeLayer()
{
        $.colorbox.close();
        window.location="viewprofile.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&EditWhatNew=JST2";
}
</script>
~if $EmailDup eq 1`
<script>
document.getElementById('email').value=document.getElementById('DupEmail').value;
document.getElementById('my_exemail').style.display="block";
</script>
~/if`
<form name=form1 method=post ~if $post_login eq 1`action="edit_profile.php?CMDsubmit=1&EditWhat=ContactDetails&checksum=~$CHECKSUM`&post_login=1"~else`action="edit_profile.php?CMDsubmit=1&EditWhat=ContactDetails&checksum=~$CHECKSUM`"~/if` onsubmit="return validate();" style="margin:0px;padding:0px;">
<input type="hidden" id="junk">
<div class="pink" style="width:700px;height:auto;">
<div class="topbg">
<div class="lf pd b t12">Your Contact Information</div><div class="rf pd b t12"><a onclick="$.colorbox.close();return false;" href="#" class="blink">Close [x]</a></div>
</div><div class="clear"></div>
<div class="scrollbox2 t12" style="padding:0; width:99.9%;">

<div class="row4 rsp">
<label style="width:165px;"><img src="images/icon_archive.gif"> Email ID:</label>
<span><input type="text" style="width:180px;" name="Email" value="~$EMAIL`" id="email" onblur="show_lo();"><input type="hidden" id="DupEmail" value="~$DupEmail`"><input type="hidden" id="source" value="~$source`">
<div id="img_avail" style="display:none;">
<span style="color: rgb(11, 136, 5); padding-left: 4px;vertical-align: bottom;">
<img src="images/registration_new/grtick.gif"/>
Available
</span>
</div>
<input type="hidden" id="inv" value="">
<div class="red" id="my_email" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
&nbsp;Please enter an email address.
</div>
<div class="red" id="my_inemail" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
&nbsp;Please enter a valid email address.
</div>
<div class="red" id="my_exemail" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
&nbsp;Entered email address already exist.
</div>
<div class="red" id="contain_js" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
&nbsp;You cannot register with a jeevansathi email address. Please register using a different email address.
</div>
</span>
</div>

<input type="hidden" name="Country_Residence" id="Country_Residence" value="~$COUNTRY_RES_VAL`">
<div class="row4 rsp">
<label style="width:165px;">Country living in :</label>
<span style="width:310px;">~$COUNTRY_RES_VAL`&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[To change country <a href="#" class="blink b" onclick="closeLayer();">click here</a>]</span>
</div>

<div class="row4 rsp">
<label style="width:165px;">City living in :</label>
<div id="city_arr">~$CITY_ARR`</div>
</div>

<div class="row4 rsp">
<label style="width:165px;">Residency status :</label>
<span><select style="width:185px;" name="Rstatus">
<option value="1" ~if $RES_STATUS eq "1"`selected~/if`>Citizen</option>
<option value="2" ~if $RES_STATUS eq "2"`selected~/if`>Permanent Resident</option>
<option value="3" ~if $RES_STATUS eq "3"`selected~/if`>Work Permit</option>
<option value="4" ~if $RES_STATUS eq "4"`selected~/if`>Student Visa</option>
<option value="5" ~if $RES_STATUS eq "5"`selected~/if`>Temporary Visa</option>
</select></span>
</div>

<div class="row4 rsp">
<label style="width:165px;"><img src="images/icon_archive.gif"> Your contact address :</label>
<span>
	<textarea class="t12" style="width:220px;height:50px;font-family: arial; " rows="4" cols="8" name="Address" id="Address">~$CONTACT`</textarea>
	 Pin code <input type="text" maxlength="6" id="pincode" value="" name="pincode" style="width:100px;">
</span>
<span><select style="width:185px;" name="showAddress">
<option value="Y" ~if $SHOWADDRESS eq "Y" or $SHOWADDRESS eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWADDRESS eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<img src="images/icon_key.gif" align="top"></span>
</div>

<div class="row4 rsp">
<label style="width:165px;"><img src="images/icon_archive.gif"> Parents' address :</label>
<span>
	<textarea class="t12" style="width:220px;height:50px;font-family: arial; " rows="4" cols="8" name="Parents_Contact">~$PARENTS_CONTACT`</textarea>
	 Pin code <input type="text" maxlength="6" id="pincode" value="" name="pincode" style="width:100px;">
</span>
<span><select style="width:185px;" name="Show_Parents_Contact">
<option value="Y" ~if $SHOW_PARENTS_CONTACT eq "Y" or $SHOW_PARENTS_CONTACT eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOW_PARENTS_CONTACT eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<img src="images/icon_key.gif" align="top"></span>
</div>

<div class="row4 rsp">
<label ~if $post_login eq 1`style="width:165px;color:red;"~else`style="width:165px;"~/if`><br>
<img src="images/icon_archive.gif"> Landline number :</label>
<span class="t11"><span style="width:50px">Country<br><input type="text" size="3" name="ISD" id="ISD1" value="~$country_code`">
</span>
<span style="width:45px">Area<br>
<input type="text" size="2" name="State_Code" id="State_Code" value="~$state_code`"></span>
<span style="width:120px">Number<br><input type="text" style="width:100px;" name="Phone" value="~$PHONE_RES`" id="Phone" onblur="phoneJCheck('L');" maxlength="12"></span><br>
<div class="rf" style="padding-top:5px;"><div class="rf">of &nbsp;<select name="PHONE_NUMBER_OWNER">
~if $GENDER_LOGGED_IN eq 'F'`
<option value="1" ~if $PHONE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
~else`
<option value="2" ~if $PHONE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
~/if`
<option value="3" ~if $PHONE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
<option value="6" ~if $PHONE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
<option value="7" ~if $PHONE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
</select>&nbsp;whose name is</div><div class="clear"></div>
<div class="red" id="phone_span" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please type in a valid phone number.
<div class="sp5"></div>
</div>	
<div class="red" id="phone_name_span" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please enter the phone number owner's name.
<div class="sp5"></div>
</div>
<div class="red" id="phone_in_name_span" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Phone number owner's name cannot contain special characters.
<div class="sp5"></div>
</div>
</div>
</span>
<span style="position:relative">
	<br>
		<select style="width:185px;" name="Showphone" onmouseover="show_help_box('1');" onmouseout="show_help_box('2');">
			<option value="Y" ~if $SHOWPHONE_RES eq "Y" or $SHOWPHONE_RES eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
			<option value="N" ~if $SHOWPHONE_RES eq "N"`selected~/if`>Don't show to anybody</option>
			<option value="CN" ~if $SHOWPHONE_RES eq "CN"`selected~/if`>Receive calls anonymously</option>
		</select> &nbsp;
		<img id="image_help" src="images/registration_new/icon_key.gif" align="top">
</span>
<div id="helpbox" style="z-index:1000;position:absolute;float:left;display:none;left:633px;top:108px;">
	<div id="box">
	<div class="b fs12">Recieve Call Anonymously 
	</div> <span class="text_new_help"><b>New</b></span>
	<span class="sp5_help">&nbsp;</span>
	<div class="fs12">Your number will not be shown to other people, they
	can only call you through Jeevansathi calling system</div>
	<span class="sp5">&nbsp;</span><span class="sp5">&nbsp;</span>
	<span class="i fs11 red" style="positon:relative;left:-233px">Important</span>
	<ol start="1" class="list_imp" >
	<li class="fs11">Select this feature only if you are very perticular about showing you number to others, this may  delay your responses from other</li><li class="fs11">Selected for one of your number will be aplicable for your other numbers</li>
	</ol>
	</div>
</div>
<br>
<input type="text" style="width:100px;margin-top:5px;" name="PHONE_OWNER_NAME" value="~$PHONE_OWNER_NAME`" id="PHONE_OWNER_NAME"></span>
</div>

<div class="sp5"></div>
<div class="row4 rsp">
<label ~if $post_login eq 1`style="width:165px;color:red;"~else`style="width:165px;"~/if`><br>
<img src="images/icon_archive.gif"> Mobile number :</label>
<span class="t11"><span style="width:50px">Country<br><input type="text" size="3" name="ISD" id="ISD2" value="~$country_code_mob`"></span>
<span style="width:150px">Number<br><input type="text" style="width:140px;" name="Mobile" value="~$PHONE_MOB`" id="Mobile" onblur="phoneJCheck('M');" maxlength="15"></span><br>
<div class="rf" style="padding-top:5px;"><div class="rf">of &nbsp;<select name="MOBILE_NUMBER_OWNER">
~if $GENDER_LOGGED_IN eq 'F'`
<option value="1" ~if $MOBILE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
~else`
<option value="2" ~if $MOBILE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
~/if`
<option value="3" ~if $MOBILE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
<option value="6" ~if $MOBILE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
<option value="7" ~if $MOBILE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
</select>&nbsp;whose name is</div><div class="clear"></div>
<div class="red" id="mobile_name_span" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please enter the mobile number owner's name.
</div>
<div class="red" id="mobile_in_name_span" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Mobile number owner's name cannot contain special characters.
</div>

</div>
</span>
<span><br><select style="width:185px;" name="Showmobile" onmouseover="show_help_box('1');" onmouseout="show_help_box('2');">
<option value="Y" ~if $SHOWPHONE_MOB eq "Y" or $SHOWPHONE_MOB eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWPHONE_MOB eq "N"`selected~/if`>Don't show to anybody</option>
<option value="CN" ~if $SHOWPHONE_MOB eq "CN"`selected~/if`>Receive calls anonymously</option>
</select> &nbsp;<img src="images/icon_key.gif" align="top"><br>
<input type="text" style="width:100px;margin-top:5px;" name="MOBILE_OWNER_NAME" value="~$MOBILE_OWNER_NAME`" id="MOBILE_OWNER_NAME"></span>
</div>
<div class="clear"></div>
<div class="red" id="international_mobile_span" style="display:none; margin:0 0 0 175px;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;International Mobile number should contain atleast 5 digits.
</div>
<div class="red" id="mobile_span" style="display:none; margin:0 0 0 175px;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please type in a valid mobile number.
</div>

<div class="row4" id="add_more_mobile">
	<label style="width:165px;">&nbsp;</label>
	<span>
		<div onclick="show_alter('alter_mobile_section')">
			<img src="images/plussign.gif" style="vertical-align:bottom"/> <a style="color:#0089d0" href="javascript:void(0);"><b>Add more mobile number</b></a>
		</div>
	</span>
</div>

<div class="row4 rsp" id="alter_mobile_section" style="display:none">
	<label ~if $post_login eq 1`style="width:165px;color:red;"~else`style="width:165px;"~/if`><br>
	<img src="images/icon_archive.gif"> Alternate Mobile number :</label>
	<span class="t11">
		<span style="width:50px">Country<br>
			<input type="text" size="3" name="ALT_MOBILE_ISD" id="ALT_MOBILE_ISD" value="">
		</span>
		<span style="width:150px">Number<br>
			<input type="text" style="width:140px;" name="ALT_MOBILE" value="" id="ALT_MOBILE" onblur="phoneJCheck('M');" maxlength="11">
		</span><br>
		<div class="rf" style="padding-top:5px;">
			<div class="rf">of &nbsp;
				<select name="ALT_MOBILE_NUMBER_OWNER" id="ALT_MOBILE_NUMBER_OWNER">
					~if $GENDER_LOGGED_IN eq 'F'`
						<option value="1" ~if $ALT_MOBILE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
					~else`
						<option value="2" ~if $ALT_MOBILE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
					~/if`	
						<option value="3" ~if $ALT_MOBILE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
						<option value="6" ~if $ALT_MOBILE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
						<option value="7" ~if $ALT_MOBILE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
					</select>&nbsp;whose name is &nbsp;
			</div>
			<div class="clear"></div>
			<div class="red" id="alt_mobile_name_span" style="display:none;">
				<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please enter the mobile number owner's name.
			</div>
			<div class="red" id="alt_mobile_in_name_span" style="display:none;">
				<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Mobile number owner's name cannot contain special characters.
			</div>
		</div>
	</span>
	<span><br>
		<select style="width:185px;" name="ALT_Showmobile" id="ALT_Showmobile">
			<option value="Y" ~if $ALT_SHOWPHONE_MOB eq "Y" or $ALT_SHOWPHONE_MOB eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
			<option value="N" ~if $ALT_SHOWPHONE_MOB eq "N"`selected~/if`>Don't show to anybody</option>
			<option value="CN" ~if $ALT_SHOWPHONE_MOB eq "CN"`selected~/if`>Receive calls anonymously</option>
		</select> &nbsp;<img src="images/icon_key.gif" align="top"><br>
		<input type="text" style="width:100px;margin-top:5px;" name="ALT_MOBILE_OWNER_NAME" value="" id="ALT_MOBILE_OWNER_NAME">
	</span>
</div>
	<div class="clear"></div>
	<div class="red" id="alt_international_mobile_span" style="display:none; margin:0 0 0 175px;">
		<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;International Mobile number should contain atleast 8 digits.
	</div>
	<div class="red" id="alt_mobile_span" style="display:none; margin:0 0 0 175px;">
		<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>&nbsp;Please type in a valid mobile number.
	</div>

<div class="row4" id="hide_more_mobile" style="display:none">
	<label style="width:165px;">&nbsp;</label>
	<span>
		<div onclick="show_alter('alter_mobile_section','HIDE')">
			<img src="images/minussign.gif" style="vertical-align:bottom" /> <a  style="color:#0089d0" href="javascript:void(0);"><b>Hide more mobile number</b></a>
		</div>
	</span>
</div>

<div class="sp5"></div>
<div class="row4 rsp">
<label style="width:165px;">Suitable time to call :</label>
<span><select style="width:40px;" name="time_to_call_start">
<option value=12 ~if $time_to_call_start eq 12`selected~/if`>12</option>
<option value=1 ~if $time_to_call_start eq 1`selected~/if`>1</option>
<option value=2 ~if $time_to_call_start eq 2`selected~/if`>2</option>
<option value=3 ~if $time_to_call_start eq 3`selected~/if`>3</option>
<option value=4 ~if $time_to_call_start eq 4`selected~/if`>4</option>
<option value=5 ~if $time_to_call_start eq 5`selected~/if`>5</option>
<option value=6 ~if $time_to_call_start eq 6`selected~/if`>6</option>
<option value=7 ~if $time_to_call_start eq 7`selected~/if`>7</option>
<option value=8 ~if $time_to_call_start eq 8`selected~/if`>8</option>
<option value=9 ~if $time_to_call_start eq 9 or $time_to_call_start eq ''`selected~/if`>9</option>
<option value=10 ~if $time_to_call_start eq 10`selected~/if`>10</option>
<option value=11 ~if $time_to_call_start eq 11`selected~/if`>11</option>
</select> <select style="width:45px;" name="start_am_pm"><option value="AM" ~if $start_am_pm eq "AM"`selected~/if`>AM</option><option value="PM" ~if $start_am_pm eq "PM"`selected~/if`>PM</option></select> &nbsp;to <select style="width:40px;" name="time_to_call_end">
<option value=12 ~if $time_to_call_end eq 12`selected~/if`>12</option>
<option value=1 ~if $time_to_call_end eq 1`selected~/if`>1</option>
<option value=2 ~if $time_to_call_end eq 2`selected~/if`>2</option>
<option value=3 ~if $time_to_call_end eq 3`selected~/if`>3</option>
<option value=4 ~if $time_to_call_end eq 4`selected~/if`>4</option>
<option value=5 ~if $time_to_call_end eq 5`selected~/if`>5</option>
<option value=6 ~if $time_to_call_end eq 6`selected~/if`>6</option>
<option value=7 ~if $time_to_call_end eq 7`selected~/if`>7</option>
<option value=8 ~if $time_to_call_end eq 8`selected~/if`>8</option>
<option value=9 ~if $time_to_call_end eq 9 or $time_to_call_start eq ''`selected~/if`>9</option>
<option value=10 ~if $time_to_call_end eq 10`selected~/if`>10</option>
<option value=11 ~if $time_to_call_end eq 11`selected~/if`>11</option>
</select> <select style="width:45px;" name="end_am_pm"><option value="AM" ~if $end_am_pm eq "AM"`selected~/if`>AM</option><option value="PM" ~if $end_am_pm eq "PM" or $end_am_pm eq ""`selected~/if`>PM</option></select></span>
</div>
<div class="sp5"></div>

<div class="row4 rsp">
	<label style="width:165px;"><img src="images/icon_archive.gif"> Messenger ID :</label>
	<span>
		<input type="text" style="width:100px;" name="Messenger_ID" id="Messenger_ID" value="~$MESSENGER_ID`">&nbsp;<select style="width:111px;" name="Messenger" id="mess_channel"><option value="" ~if !$MESSENGER_CHANNEL && !$MESSENGER_ID`selected~/if`>Select one option</option>
                                                <option value="1" ~if $MESSENGER_CHANNEL eq '1' && $MESSENGER_ID`  selected ~/if`>Yahoo</option>
                                                <option value="2" ~if $MESSENGER_CHANNEL eq '2' && $MESSENGER_ID` selected ~/if`>MSN</option>
                                                <option value="3" ~if $MESSENGER_CHANNEL eq '3' && $MESSENGER_ID` selected ~/if`>Skype</option>
                                                <option value="5" ~if $MESSENGER_CHANNEL eq '5' && $MESSENGER_ID` selected ~/if`>ICQ</option>
                                                <option value="6" ~if $MESSENGER_CHANNEL eq '6' && $MESSENGER_ID` selected ~/if`>Google Talk</option>
                                                <option value="7" ~if $MESSENGER_CHANNEL eq '7' && $MESSENGER_ID` selected ~/if`>Rediff Bol</option>

</select><div class="red" id="m_id" style="display:none;">
<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
&nbsp;Please enter a valid email address or just enter the messenger Id.
</div>
</span>
<span><select style="width:185px;" name="showMessenger">
<option value="">Select Viewing Option</option>
<option value="Y" ~if $SHOWMESSENGER eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWMESSENGER eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<img src="images/icon_key.gif" align="top">
</span>
</div>

<div class="row4" id="add_more_messenger">
	<label style="width:165px;">&nbsp;</label>
	<span>
		<div onclick="show_alter('alter_messenger_section')">
			<img src="images/plussign.gif" style="vertical-align:bottom"/>	<a style="color:#0089d0" href="javascript:void(0);"><b>Add more messengers IDs</b></a>
		</div>
	</span>
</div>

<div class="row4 rsp" style="display:none" id="alter_messenger_section">
	<label style="width:165px;"><img src="images/icon_archive.gif"> Alternate Messenger ID :</label>
	<span>
		<input type="text" style="width:100px;" name="Alt_Messenger_ID" id="Alt_Messenger_ID" value="">
		<select style="width:111px;" name="Alt_Messenger" id="alt_mess_channel">
						<option value="" ~if !$ALT_MESSENGER_CHANNEL && !$ALT_MESSENGER_ID`selected~/if`>Select one option</option>
                                                <option value="1" ~if $ALT_MESSENGER_CHANNEL eq '1' && $ALT_MESSENGER_ID`  selected ~/if`>Yahoo</option>
                                                <option value="2" ~if $ALT_MESSENGER_CHANNEL eq '2' && $ALT_MESSENGER_ID` selected ~/if`>MSN</option>
                                                <option value="3" ~if $ALT_MESSENGER_CHANNEL eq '3' && $ALT_MESSENGER_ID` selected ~/if`>Skype</option>
                                                <option value="5" ~if $ALT_MESSENGER_CHANNEL eq '5' && $ALT_MESSENGER_ID` selected ~/if`>ICQ</option>
                                                <option value="6" ~if $ALT_MESSENGER_CHANNEL eq '6' && $ALT_MESSENGER_ID` selected ~/if`>Google Talk</option>
                                                <option value="7" ~if $ALT_MESSENGER_CHANNEL eq '7' && $ALT_MESSENGER_ID` selected ~/if`>Rediff Bol</option>
		</select>
		<div class="red" id="alt_m_id" style="display:none;">
			<img style="vertical-align: bottom;" src="images/registration_new/alert.gif"/>
			&nbsp;Please enter a valid email address or just enter the messenger Id.
		</div>
	</span>
	<span>
		<select style="width:185px;" name="Alt_showMessenger" id="Alt_showMessenger" >
			<option value="">Select Viewing Option</option>
			<option value="Y" ~if $ALT_SHOWMESSENGER eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
			<option value="N" ~if $ALT_SHOWMESSENGER eq "N"`selected~/if`>Don't show to anybody</option>
		</select> &nbsp;<img src="images/icon_key.gif" align="top">
	</span>
</div>

<div class="row4" id="hide_more_messenger" style="display:none">
	<label style="width:165px;">&nbsp;</label>
	<span>
		<div onclick="show_alter('alter_messenger_section','HIDE')">
			<img src="images/minussign.gif" style="vertical-align:bottom" /> <a  style="color:#0089d0" href="javascript:void(0);"><strong>Hide more messengers IDs</strong></a>
		</div>
	</span>
</div>

<div class="row4 rsp">
		<label style="width:165px;"><img src="images/icon_archive.gif"> Blackberry Pin :</label>
		<span>
			<input type="text" style="width:215px;" name="blackberyy_pin" value="" id="blackberyy_pin">
		</span>
		<span>
		<select style="width:185px;" name="show_blackberry">
			<option value="">Select Viewing Option</option>
			<option value="Y" ~if $SHOWBLACKBERRY eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
			<option value="N" ~if $SHOWBLACKBERRY eq "N"`selected~/if`>Don't show to anybody</option>
		</select> &nbsp;<img src="images/icon_key.gif" align="top">
		</span>
</div>

<div class="row4 rsp">
		<label style="width:165px;"><img src="images/icon_archive.gif"> Linkedin ID :</label>
		<span>
			<input type="text" style="width:215px;" name="linkedin_id" value="" id="linkedin_id">
		</span>
		<span>
			<select style="width:185px;" name="show_linkedin">
				<option value="">Select Viewing Option</option>
				<option value="Y" ~if $SHOWLINKEDIN eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
				<option value="N" ~if $SHOWLINKEDIN eq "N"`selected~/if`>Don't show to anybody</option>
			</select> &nbsp;<img src="images/icon_key.gif" align="top">
		</span>
</div>

<div class="row4 rsp">
		<label style="width:165px;"><img src="images/icon_archive.gif"> Facebook ID :</label>
		<span>
			<input type="text" style="width:215px;" name="facebook_id" value="" id="facebook_id">
		</span>
		<span>
			<select style="width:185px;" name="show_facebook">
				<option value="">Select Viewing Option</option>
				<option value="Y" ~if $SHOWFACEBOOK eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
				<option value="N" ~if $SHOWFACEBOOK eq "N"`selected~/if`>Don't show to anybody</option>
			</select> &nbsp;<img src="images/icon_key.gif" align="top">
		</span>
</div>

</div>
<div class="sp12" style="border:1px #F0CED6; border-top-style:solid"></div>
<div style="width:100%;text-align:center"><span id="img_sav"><input type="Submit" class="b green_btn" value="Save" style="width:60px;"></span><span id="img_test1" style="display:none;"><img src="images/registration_new/ajax-loader.gif"/></span></div>
<div><span class="t11" style="text-align:left;padding-right:40px;"> &nbsp;<img src="images/icon_archive.gif"> Any modifications to these fields are archived </span></div>
</div>
</form>

~if $callTime eq '1'`
<script type="text/javascript" language="JavaScript">
document.getElementById('Messenger_ID').focus();
</script>
~/if`
